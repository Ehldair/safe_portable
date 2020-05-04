<?php
session_start();

if(isset($_SESSION['id_u'])) {

    $link_portable = mysqli_connect("localhost", "root", ".google.", "safe_portable");

    $link = mysqli_connect("localhost", "root", ".google.", "safe");

    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }


    $myid_caso=base64_decode(mysqli_real_escape_string($link,$_GET['id_caso']));
    $select_caso=mysqli_query($link_portable, "Select * FROM caso WHERE id_caso=$myid_caso");
    echo "Primera busqueda: Select * FROM caso WHERE id_caso=$myid_caso<br>";
    $ret_caso=mysqli_fetch_array($select_caso);
    $comprobacion_caso=mysqli_query($link, "Select * from caso where numero=$ret_caso[numero] and año=$ret_caso[año]");
    $count_caso=mysqli_num_rows($comprobacion_caso);

    // Primero comprobamos que no exista ningún caso con ese mismo número y año
    if($count_caso!=0) {
        echo "Ya existe ese mismo caso";
    }
    else {    
        // Después comprobamos si el caso que queremos añadir tiene diligencias asociadas. Si no tiene se inserta el caso directamente.
        if(isset($ret_caso['id_diligencias'])) {
            $select_diligencias=mysqli_query($link_portable, "Select * FROM diligencias WHERE id_diligencias=$ret_caso[id_diligencias]");
            echo "Segunda busqueda: Select * FROM diligencias WHERE id_diligencias=$ret_caso[id_diligencias]<br>";
            $ret_diligencias=mysqli_fetch_array($select_diligencias);
            if($ret_caso['id_diligencias']!=null or $ret_caso['diligencias']!='') {
                // Comprobamos si existen ya diligencias con el mismo numero, año y juzgado. Si no existen, se crean las diligencias, luego se selecciona el ID proporcionado por la BBDD y posteriormente
                // se inserta el caso.
                $sql_diligencias_safe = "SELECT id_diligencias FROM diligencias  where id_juzgado=$ret_diligencias[id_juzgado] AND numero=$ret_diligencias[numero] AND año=$ret_diligencias[año]";
                $result_diligencias_safe=mysqli_query($link,$sql_diligencias_safe);
                echo "Tercera busqueda: ".$sql_diligencias_safe."<br>";
                $ret_diligencias_safe=mysqli_fetch_array($result_diligencias_safe);
                $count_diligencias_safe = mysqli_num_rows($result_diligencias_safe);
                if($count_diligencias_safe!=0) {
                    // Si existen, comprobamos si están vinculadas a algun caso y si es así, no se agrega el caso.
                    $sql_caso_diligencias = "SELECT id_caso,numero,año FROM caso  where id_diligencias=$ret_diligencias_safe[id_diligencias]";
                    $result_caso_diligencias=mysqli_query($link,$sql_caso_diligencias);
                    $ret_caso_diligencias=mysqli_fetch_array($result_caso_diligencias);
                    echo "Cuarta busqueda: ".$sql_caso_diligencias."<br>";
                    $count_caso_diligencias = mysqli_num_rows($result_caso_diligencias);
                    if($count_caso_diligencias!=0) {
                        echo "Ya existen esas mismas diligencias en el caso ".$ret_caso_diligencias['numero']."_".substr( $ret_caso_diligencias['año'], 2);
                    }
                    else {
                        $sql = "INSERT INTO caso(id_diligencias, id_tipo_caso, id_grupo_investigacion, numero, año, nombre, fecha_alta_caso, descripcion, id_estado_caso) values ($ret_diligencias_safe[id_diligencias],$ret_caso[id_tipo_caso],$ret_caso[id_grupo_investigacion],$ret_caso[numero],'$ret_caso[año]','$ret_caso[nombre]',NOW(),'$ret_caso[descripcion]', 1)";
                        mysqli_query($link, $sql);
                        echo $sql;
                        $query_id_caso=mysqli_query($link, "SELECT id_caso from caso where numero=$ret_caso[numero] and año=$ret_caso[año]");
                        $ret_id_caso=mysqli_fetch_array($query_id_caso);
                        $myid_caso_safe=$ret_id_caso['id_caso'];
                    }
                }
                else {
                    $sql_insert_diligencias="INSERT INTO diligencias (id_juzgado, numero, año, fecha) values ($ret_diligencias[id_juzgado], $ret_diligencias[numero], $ret_diligencias[año], NOW())";
                    echo $sql_insert_diligencias."<br>";
                    mysqli_query($link,$sql_insert_diligencias);
                    $sql_id_diligencias="Select id_diligencias From diligencias Where id_juzgado=$ret_diligencias[id_juzgado] AND numero=$ret_diligencias[numero] AND año=$ret_diligencias[año]";
                    $result_id_diligencias=mysqli_query($link,$sql_id_diligencias);
                    $ret_id_diligencias=mysqli_fetch_array($result_id_diligencias);
                    echo $sql_id_diligencias."<br>";
                    $sql = "INSERT INTO caso(id_diligencias, id_tipo_caso, id_grupo_investigacion, numero, año, nombre, fecha_alta_caso, descripcion, id_estado_caso) values ($ret_id_diligencias[id_diligencias], $ret_caso[id_tipo_caso],$ret_caso[id_grupo_investigacion],$ret_caso[numero],'$ret_caso[año]','$ret_caso[nombre]',NOW(),'$ret_caso[descripcion]', 1)";
                    mysqli_query($link, $sql);
                    echo $sql;
                    $query_id_caso=mysqli_query($link, "SELECT id_caso from caso where numero=$ret_caso[numero] and año=$ret_caso[año]");
                    $ret_id_caso=mysqli_fetch_array($query_id_caso);
                    $myid_caso_safe=$ret_id_caso['id_caso'];
                }
            }
        }
        else {
            $sql = "INSERT INTO caso( id_tipo_caso, id_grupo_investigacion, numero, año, nombre, fecha_alta_caso, descripcion, id_estado_caso) values ($ret_caso[id_tipo_caso],$ret_caso[id_grupo_investigacion],$ret_caso[numero],'$ret_caso[año]','$ret_caso[nombre]',NOW(),'$ret_caso[descripcion]', 1)";
            mysqli_query($link, $sql);
            echo $sql;
        }
        //comprobamos despues de agregar el caso, si hay evidencias
        $sql="Select * from evidencia where id_caso=$myid_caso";
        echo "<br>".$sql."<br>";
        $result_evidencias=mysqli_query($link_portable, $sql);
        $ret_evidencias=mysqli_fetch_array($result_evidencias);
        $count_evidencias=mysqli_num_rows($result_evidencias);
        if($count_evidencias!=0) {
            //si hay, primero vamos a agregar los sujetos_activos del caso
            $sql="Select id_sujeto_activo,nombre,apellido1,apellido2 from sujeto_activo where id_caso=$myid_caso and id_caso!=1";
            echo "<br>".$sql;
            $result_sujeto=mysqli_query($link_portable, $sql);
            $count_sujeto=mysqli_num_rows($result_sujeto);
            if($count_sujeto!=0) {
                while ($fila_sujeto = mysqli_fetch_row($result_sujeto)) {
                    $sql="INSERT INTO sujeto_activo(id_caso,nombre, apellido1,apellido2) values ($myid_caso_safe,'$fila_sujeto[1]', '$fila_sujeto[2]', '$fila_sujeto[3]')";
                    mysqli_query($link, $sql);
                    $query_sujeto=mysqli_query($link, "Select id_sujeto_activo from sujeto_activo WHERE id_caso=$myid_caso_safe and nombre='$fila_sujeto[1]' AND apellido1='$fila_sujeto[2]' AND apellido2='$fila_sujeto[3]'");
                    $ret_sujeto_safe=mysqli_fetch_array($query_sujeto);
                    echo "<br>".$sql;
                    echo "<br> Select id_sujeto_activo from sujeto_activo WHERE id_caso=$myid_caso_safe and nombre='$fila_sujeto[1]' AND apellido1='$fila_sujeto[2]' AND apellido2='$fila_sujeto[3]'";
                    echo "<br> ID_Sujeto: ".$ret_sujeto_safe['id_sujeto_activo'];
                    $sql="select * from intervencion where id_caso=$myid_caso and id_sujeto_activo=$fila_sujeto[0]";
                    echo "<br>". $sql;
                    $result_intervencion=mysqli_query($link_portable, $sql);
                    $count_intervencion=mysqli_num_rows($result_intervencion);
                    if($count_intervencion!=0) {
                        while ($fila_intervencion = mysqli_fetch_row($result_intervencion)) {
                            $sql="INSERT INTO intervencion(id_caso, id_tipo_intervencion, id_sujeto_activo, numero_intervencion, direccion, descripcion) values ($myid_caso_safe,$fila_intervencion[2], $ret_sujeto_safe[id_sujeto_activo], $fila_intervencion[4],'$fila_intervencion[5]','$fila_intervencion[6]')";
                            echo "<br>" .$sql;
                        }
                    }
                }
            }
            echo "<br> Contador Evidencias: ".$count_evidencias;
            echo "NUMERO CASO SAFE: ".$myid_caso_safe;
            //$sql = "INSERT INTO evidencia(id_tipo_evidencia, id_subtipo_evidencia, id_disco_almacenado, id_caso, id_intervencion, nombre, numero_evidencia, alias, pin, fecha_alta_evidencia, n_s, capacidad, marca, modelo, observaciones, tiene_subevidencias, relacionado_con, patron, id_tipo_capacidad ) values ($ret_evidencias[id_tipo_evidencia],$ret_evidencias[id_subtipo_evidencia],$ret_evidencias[id_disco_almacenado], $myid_caso_safe, $myintervencion, '$ret_evidencias[nombre]', '$ret_evidencias[numero]', '$ret_evidencias[alias]', '$ret_evidencias[pin]', $ret_evidencias[fecha_alta_evidencia], '$ret_evidencias[n_s]', $ret_evidencias[capacidad], '$ret_evidencias[marca]', '$ret_evidencias[modelo]', '$ret_evidencias[observaciones]',$ret_evidencias[tiene_subevidencias], $ret_evidencias[relacionado_con], $ret_evidencias[patron], $ret_evidencias[id_tipo_capacidad])";
            
        }
        
        
    }  
}
else {
    echo "Error";
}

