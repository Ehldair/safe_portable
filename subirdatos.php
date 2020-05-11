<?php
session_start();

if(isset($_SESSION['id_u'])) {

    $link_portable = mysqli_connect("localhost", "root", ".google.", "safe_portable");

    $link = mysqli_connect("localhost", "root", ".google.", "safe");

    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    $carpeta = '../../../Scripts';
    if (!file_exists($carpeta)) {
        mkdir($carpeta, 0777, true);
    }
   
    $myid_caso=base64_decode(mysqli_real_escape_string($link,$_GET['id_caso']));
    $select_caso=mysqli_query($link_portable, "Select * FROM caso WHERE id_caso=$myid_caso");
    $ret_caso=mysqli_fetch_array($select_caso);
    $nombre_archivo=$ret_caso['numero']."_".substr($ret_caso['año'], 2);
    $direccion='../../../Scripts/'.$nombre_archivo.'.sql';
    $archivo = fopen($direccion,"w");
    $comprobacion_caso=mysqli_query($link, "Select * from caso where numero=$ret_caso[numero] and año=$ret_caso[año]");
    $count_caso=mysqli_num_rows($comprobacion_caso);

    // Primero comprobamos que no exista ningún caso con ese mismo número y año
    if($count_caso==0) {
        if(isset($ret_caso['id_diligencias'])) {
            $select_diligencias=mysqli_query($link_portable, "Select * FROM diligencias WHERE id_diligencias=$ret_caso[id_diligencias]");
            $ret_diligencias=mysqli_fetch_array($select_diligencias);
            if($ret_caso['id_diligencias']!=null or $ret_caso['diligencias']!='') {
                // Comprobamos si existen ya diligencias con el mismo numero, año y juzgado. Si no existen, se crean las diligencias, luego se selecciona el ID proporcionado por la BBDD y posteriormente
                // se inserta el caso.
                $sql_diligencias_safe = "SELECT id_diligencias FROM diligencias  where id_juzgado=$ret_diligencias[id_juzgado] AND numero=$ret_diligencias[numero] AND año=$ret_diligencias[año]";
                $result_diligencias_safe=mysqli_query($link,$sql_diligencias_safe);
                $ret_diligencias_safe=mysqli_fetch_array($result_diligencias_safe);
                $count_diligencias_safe = mysqli_num_rows($result_diligencias_safe);
                if($count_diligencias_safe!=0) {
                    // Si existen, comprobamos si están vinculadas a algun caso y si es así, no se agrega el caso.
                    $sql_caso_diligencias = "SELECT id_caso,numero,año FROM caso  where id_diligencias=$ret_diligencias_safe[id_diligencias]";
                    $result_caso_diligencias=mysqli_query($link,$sql_caso_diligencias);
                    $ret_caso_diligencias=mysqli_fetch_array($result_caso_diligencias);
                    $count_caso_diligencias = mysqli_num_rows($result_caso_diligencias);
                    if($count_caso_diligencias!=0) {
                        echo "Ya existen esas mismas diligencias en el caso ".$ret_caso_diligencias['numero']."_".substr( $ret_caso_diligencias['año'], 2);
                    }
                    else {
                        $sql = "INSERT INTO caso(id_diligencias, id_tipo_caso, id_grupo_investigacion, numero, año, nombre, fecha_alta_caso, descripcion, id_estado_caso) values ($ret_diligencias_safe[id_diligencias],$ret_caso[id_tipo_caso],$ret_caso[id_grupo_investigacion],$ret_caso[numero],'$ret_caso[año]','$ret_caso[nombre]',NOW(),'$ret_caso[descripcion]', 1)";
                        mysqli_query($link, $sql);
                        fputs($archivo,$sql.";\n");
                  
                    }
                }
                else {
                    $sql_insert_diligencias="INSERT INTO diligencias (id_juzgado, numero, año, fecha) values ($ret_diligencias[id_juzgado], $ret_diligencias[numero], $ret_diligencias[año], NOW())";
                    mysqli_query($link,$sql_insert_diligencias);
                    fputs($archivo,$sql_insert_diligencias.";\n");
                    $sql_id_diligencias="Select id_diligencias From diligencias Where id_juzgado=$ret_diligencias[id_juzgado] AND numero=$ret_diligencias[numero] AND año=$ret_diligencias[año]";
                    $result_id_diligencias=mysqli_query($link,$sql_id_diligencias);
                    $ret_id_diligencias=mysqli_fetch_array($result_id_diligencias);
                    $sql = "INSERT INTO caso(id_diligencias, id_tipo_caso, id_grupo_investigacion, numero, año, nombre, fecha_alta_caso, descripcion, id_estado_caso) values ($ret_id_diligencias[id_diligencias], $ret_caso[id_tipo_caso],$ret_caso[id_grupo_investigacion],$ret_caso[numero],'$ret_caso[año]','$ret_caso[nombre]',NOW(),'$ret_caso[descripcion]', 1)";
                    mysqli_query($link, $sql);
                    fputs($archivo,$sql.";\n");
                
                }
            }
        }
        else {
            $sql = "INSERT INTO caso( id_tipo_caso, id_grupo_investigacion, numero, año, nombre, fecha_alta_caso, descripcion, id_estado_caso) values ($ret_caso[id_tipo_caso],$ret_caso[id_grupo_investigacion],$ret_caso[numero],'$ret_caso[año]','$ret_caso[nombre]',NOW(),'$ret_caso[descripcion]', 1)";
            mysqli_query($link, $sql);
            fputs($archivo,$sql.";\n");
           
        }
    }
     
        // Después comprobamos si el caso que queremos añadir tiene diligencias asociadas. Si no tiene se inserta el caso directamente.
        
            $query_id_caso=mysqli_query($link, "SELECT id_caso from caso where numero=$ret_caso[numero] and año=$ret_caso[año]");
            $ret_id_caso=mysqli_fetch_array($query_id_caso);
            $myid_caso_safe=$ret_id_caso['id_caso'];
            //si hay, primero vamos a agregar los sujetos_activos del caso
            $sql="Select id_sujeto_activo,nombre,apellido1,apellido2 from sujeto_activo where id_caso=$myid_caso";
            $result_sujeto=mysqli_query($link_portable, $sql);
            $count_sujeto=mysqli_num_rows($result_sujeto);
            if($count_sujeto!=0) {
                while ($fila_sujeto = mysqli_fetch_row($result_sujeto)) {
                        $sql="INSERT INTO sujeto_activo(id_caso,nombre, apellido1,apellido2) values ($myid_caso_safe,'$fila_sujeto[1]', '$fila_sujeto[2]', '$fila_sujeto[3]')";
                        mysqli_query($link, $sql);
                        fputs($archivo,$sql.";\n");
                        $query_sujeto=mysqli_query($link, "Select id_sujeto_activo from sujeto_activo WHERE id_caso=$myid_caso_safe and nombre='$fila_sujeto[1]' AND apellido1='$fila_sujeto[2]' AND apellido2='$fila_sujeto[3]'");
                        $ret_sujeto_safe=mysqli_fetch_array($query_sujeto);
                    //se selecionan las intervenciones a añadir y se procede a ello, diferenciando si el id_sujeto_activo es 1(SIN DETENIDO) o no
                    $sql="select * from intervencion where id_caso=$myid_caso and (id_sujeto_activo=$fila_sujeto[0] OR id_sujeto_activo=1)";
                    $result_intervencion=mysqli_query($link_portable, $sql);
                    $count_intervencion=mysqli_num_rows($result_intervencion);
                    if($count_intervencion!=0) {
                        while ($fila_intervencion = mysqli_fetch_row($result_intervencion)) {
                            if($fila_intervencion[3]!=1) {
                                $sql="INSERT INTO intervencion(id_caso, id_tipo_intervencion, id_sujeto_activo, numero_intervencion, direccion, descripcion) values ($myid_caso_safe,$fila_intervencion[2], $ret_sujeto_safe[id_sujeto_activo], $fila_intervencion[4],'$fila_intervencion[5]','$fila_intervencion[6]')";
                                fputs($archivo,$sql.";\n");
                            }
                            else {
                                $sql="INSERT INTO intervencion(id_caso, id_tipo_intervencion, id_sujeto_activo, numero_intervencion, direccion, descripcion) values ($myid_caso_safe,$fila_intervencion[2], 1, $fila_intervencion[4],'$fila_intervencion[5]','$fila_intervencion[6]')";
                                fputs($archivo,$sql.";\n");
                            }
                            mysqli_query($link, $sql);
                            $query_intervencion=mysqli_query($link, "Select id_intervencion from intervencion WHERE id_caso=$myid_caso_safe and (id_sujeto_activo=$ret_sujeto_safe[id_sujeto_activo] OR id_sujeto_activo=1) AND numero_intervencion=$fila_intervencion[4]");
                            $ret_intervencion_safe=mysqli_fetch_array($query_intervencion);
                            // se selecionan las evidencias que dependen del caso y de la intervención
                            $sql="Select * from evidencia where id_caso=$myid_caso and id_intervencion=$fila_intervencion[0] and relacionado_con is null";
                            $result_evidencias=mysqli_query($link_portable, $sql);
                            $count_evidencias=mysqli_num_rows($result_evidencias);
                            if($count_evidencias!=0) {
                                while ($fila_evidencias = mysqli_fetch_row($result_evidencias)) {
                                    if(empty($fila_evidencias[9])) {
                                        $fila_evidencias[9]='null';
                                    }
                                    if(empty($fila_evidencias[14])) {
                                        $fila_evidencias[14]='null';
                                    }
                                    if(empty($fila_evidencias[19])) {
                                        $fila_evidencias[19]='null';
                                    }
                                    $sql = "INSERT INTO evidencia(id_tipo_evidencia, id_subtipo_evidencia, id_disco_almacenado, id_caso, id_intervencion, nombre, 
                                            fecha_alta_evidencia, n_s, capacidad, marca, modelo, observaciones, tiene_subevidencias, relacionado_con, numero_evidencia, 
                                            alias, patron, pin, id_tipo_capacidad ) values ($fila_evidencias[1],$fila_evidencias[2],$fila_evidencias[3], $myid_caso_safe, 
                                            $ret_intervencion_safe[id_intervencion], '$fila_evidencias[6]', '$fila_evidencias[7]', '$fila_evidencias[8]', $fila_evidencias[9], 
                                            '$fila_evidencias[10]', '$fila_evidencias[11]', '$fila_evidencias[12]', '$fila_evidencias[13]', $fila_evidencias[14], 
                                            '$fila_evidencias[15]','$fila_evidencias[16]', '$fila_evidencias[17]', '$fila_evidencias[18]', $fila_evidencias[19])";
                                    mysqli_query($link, $sql);
                                    fputs($archivo,$sql.";\n");
                                    $query_evidencia=mysqli_query($link, "Select id_evidencia from evidencia WHERE id_caso=$myid_caso_safe and id_intervencion=$ret_intervencion_safe[id_intervencion] AND nombre='$fila_evidencias[6]' AND numero_evidencia='$fila_evidencias[15]'");
                                    $ret_evidencia_safe=mysqli_fetch_array($query_evidencia);
                                    // se comprueba si la evidencia añadida tiene hashes asociados y si es así, se agrega el hash a la BBDD
                                    $sql="Select * from hash where id_evidencia=$fila_evidencias[0]";
                                    $result_hash=mysqli_query($link_portable, $sql);
                                    $count_hash=mysqli_num_rows($result_hash);
                                    if($count_hash!=0){
                                        while ($fila_hash = mysqli_fetch_row($result_hash)) {
                                            $sql = "INSERT INTO hash(id_evidencia, id_tipo_hash, hash) values ($ret_evidencia_safe[id_evidencia], $fila_hash[2], '$fila_hash[3]')";
                                            mysqli_query($link, $sql);
                                            fputs($archivo,$sql.";\n");
                                            $query_hash=mysqli_query($link, "SELECT id_hash from hash where id_evidencia=$ret_evidencia_safe[id_evidencia] and hash='$fila_hash[3]'");
                                            $ret_hash_safe=mysqli_fetch_array($query_hash);
                                            //se añade el registro asociado a ese hash
                                            $sql="Select * from evidencia_registro where id_hash=$fila_hash[0]";
                                            $result_registro=mysqli_query($link_portable, $sql);
                                            $count_registro=mysqli_num_rows($result_registro);
                                            if($count_registro!=0) {
                                                while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                                    $sql = "INSERT INTO evidencia_registro(id_evidencia, id_estado_evidencia, id_usuario, id_programa, id_accion_programa, id_hash, 
                                                            observaciones,fecha_alta_estado) values ($ret_evidencia_safe[id_evidencia], $fila_registro[2], $fila_registro[3], 
                                                            $fila_registro[4], $fila_registro[5], $ret_hash_safe[id_hash] ,'$fila_registro[7]', '$fila_registro[8]')";
                                                            mysqli_query($link, $sql);
                                                            fputs($archivo,$sql.";\n");
                                                }
                                            }
                                        }
                                        
                                    }
                                    // se añaden el resto de registros que no tienen hashes asociados
                                        $sql="Select * from evidencia_registro where id_evidencia=$fila_evidencias[0] and id_hash is null";
                                        $result_registro=mysqli_query($link_portable, $sql);
                                        $count_registro=mysqli_num_rows($result_registro);
                                        if($count_registro!=0) {
                                            while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                                if(empty($fila_registro[4])) {
                                                    $fila_registro[4]='null';
                                                }
                                                if(empty($fila_registro[5])) {
                                                    $fila_registro[5]='null';
                                                }
                                                if(empty($fila_registro[6])) {
                                                    $fila_registro[6]='null';
                                                }
                                                $sql = "INSERT INTO evidencia_registro(id_evidencia, id_estado_evidencia, id_usuario, id_programa, id_accion_programa, id_hash,
                                                            observaciones,fecha_alta_estado) values ($ret_evidencia_safe[id_evidencia], $fila_registro[2], $fila_registro[3],
                                                            $fila_registro[4], $fila_registro[5], $fila_registro[6] ,'$fila_registro[7]', '$fila_registro[8]')";
                                                            mysqli_query($link, $sql);
                                                            fputs($archivo,$sql.";\n");
                                            }
                                        }
                                    // se comprueba si existen evidencias que dependen de la que acabamos de añadir y en su caso se añaden
                                    $sql="Select * from evidencia where relacionado_con=$fila_evidencias[0]";
                                    $result_evidencias_dependientes=mysqli_query($link_portable, $sql);
                                    $count_evidencias_dependientes=mysqli_num_rows($result_evidencias_dependientes);
                                    if($count_evidencias_dependientes!=0) {
                                        while ($fila_evidencias_dependientes = mysqli_fetch_row($result_evidencias_dependientes)) {
                                            if(empty($fila_evidencias_dependientes[9])) {
                                                $fila_evidencias_dependientes[9]='null';
                                            }
                                            if(empty($fila_evidencias_dependientes[19])) {
                                                $fila_evidencias_dependientes[19]='null';
                                            }
                                            $sql = "INSERT INTO evidencia(id_tipo_evidencia, id_subtipo_evidencia, id_disco_almacenado, id_caso, id_intervencion, nombre,
                                            fecha_alta_evidencia, n_s, capacidad, marca, modelo, observaciones, tiene_subevidencias, relacionado_con, numero_evidencia,
                                            alias, patron, pin, id_tipo_capacidad ) values ($fila_evidencias_dependientes[1],$fila_evidencias_dependientes[2],$fila_evidencias_dependientes[3], $myid_caso_safe,
                                            $ret_intervencion_safe[id_intervencion], '$fila_evidencias_dependientes[6]',  '$fila_evidencias_dependientes[7]', '$fila_evidencias_dependientes[8]',
                                            $fila_evidencias_dependientes[9], '$fila_evidencias_dependientes[10]', '$fila_evidencias_dependientes[11]', '$fila_evidencias_dependientes[12]', 
                                            '$fila_evidencias_dependientes[13]', $ret_evidencia_safe[id_evidencia] ,'$fila_evidencias_dependientes[15]','$fila_evidencias_dependientes[16]', 
                                            '$fila_evidencias_dependientes[17]', '$fila_evidencias_dependientes[18]', $fila_evidencias_dependientes[19])";
                                            mysqli_query($link, $sql);
                                            fputs($archivo,$sql.";\n");
                                            $query_evidencia=mysqli_query($link, "Select id_evidencia from evidencia WHERE id_caso=$myid_caso_safe and id_intervencion=$ret_intervencion_safe[id_intervencion] AND nombre='$fila_evidencias_dependientes[6]' AND numero_evidencia='$fila_evidencias_dependientes[15]'");
                                            $ret_evidencia_safe=mysqli_fetch_array($query_evidencia);
                                            // se comprueba si la evidencia añadida tiene hashes asociados y en su caso se añaden
                                            $sql="Select * from hash where id_evidencia=$fila_evidencias_dependientes[0]";
                                            $result_hash=mysqli_query($link_portable, $sql);
                                            $count_hash=mysqli_num_rows($result_hash);
                                            if($count_hash!=0){
                                                while ($fila_hash = mysqli_fetch_row($result_hash)) {
                                                    $sql = "INSERT INTO hash(id_evidencia, id_tipo_hash, hash) values ($ret_evidencia_safe[id_evidencia], $fila_hash[2], '$fila_hash[3]')";
                                                    mysqli_query($link, $sql);
                                                    fputs($archivo,$sql.";\n");
                                                    $query_hash=mysqli_query($link, "SELECT id_hash from hash where id_evidencia=$ret_evidencia_safe[id_evidencia] and hash='$fila_hash[3]'");
                                                    $ret_hash_safe=mysqli_fetch_array($query_hash);
                                                    //se añade el registro asociado a ese hash
                                                    $sql="Select * from evidencia_registro where id_hash=$fila_hash[0]";
                                                    $result_registro=mysqli_query($link_portable, $sql);
                                                    $count_registro=mysqli_num_rows($result_registro);
                                                    if($count_registro!=0) {
                                                        while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                                            $sql = "INSERT INTO evidencia_registro(id_evidencia, id_estado_evidencia, id_usuario, id_programa, id_accion_programa, id_hash,
                                                            observaciones,fecha_alta_estado) values ($ret_evidencia_safe[id_evidencia], $fila_registro[2], $fila_registro[3],
                                                            $fila_registro[4], $fila_registro[5], $ret_hash_safe[id_hash] ,'$fila_registro[7]', '$fila_registro[8]')";
                                                            mysqli_query($link, $sql);
                                                            fputs($archivo,$sql.";\n");
                                                        }
                                                    }
                                                }
                                            }
                                            // se añaden el resto de registros que no tienen hashes asociados
                                            $sql="Select * from evidencia_registro where id_evidencia=$fila_evidencias_dependientes[0] and id_hash is null";
                                            $result_registro=mysqli_query($link_portable, $sql);
                                            $count_registro=mysqli_num_rows($result_registro);
                                            if($count_registro!=0) {
                                                while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                                    if(empty($fila_registro[4])) {
                                                        $fila_registro[4]='null';
                                                    }
                                                    if(empty($fila_registro[5])) {
                                                        $fila_registro[5]='null';
                                                    }
                                                    if(empty($fila_registro[6])) {
                                                        $fila_registro[6]='null';
                                                    }
                                                    $sql = "INSERT INTO evidencia_registro(id_evidencia, id_estado_evidencia, id_usuario, id_programa, id_accion_programa, id_hash,
                                                            observaciones,fecha_alta_estado) values ($ret_evidencia_safe[id_evidencia], $fila_registro[2], $fila_registro[3],
                                                            $fila_registro[4], $fila_registro[5], $fila_registro[6] ,'$fila_registro[7]', '$fila_registro[8]')";
                                                            mysqli_query($link, $sql);
                                                            fputs($archivo,$sql.";\n");
                                                }
                                            }
                                        }
                                    }
                                }
                            }  
                        }
                    }
                }
            }
            else {
                $sql="select * from intervencion where id_caso=$myid_caso and id_sujeto_activo=1";
                $result_intervencion=mysqli_query($link_portable, $sql);
                $count_intervencion=mysqli_num_rows($result_intervencion);
                if($count_intervencion!=0) {
                    while ($fila_intervencion = mysqli_fetch_row($result_intervencion)) {
                            $sql="INSERT INTO intervencion(id_caso, id_tipo_intervencion, id_sujeto_activo, numero_intervencion, direccion, descripcion) values ($myid_caso_safe,$fila_intervencion[2], 1, $fila_intervencion[4],'$fila_intervencion[5]','$fila_intervencion[6]')";
                            fputs($archivo,$sql.";\n");
                            mysqli_query($link, $sql);
                            $query_intervencion=mysqli_query($link, "Select id_intervencion from intervencion WHERE id_caso=$myid_caso_safe and id_sujeto_activo=1 AND numero_intervencion=$fila_intervencion[4]");
                            $ret_intervencion_safe=mysqli_fetch_array($query_intervencion);
                            // se selecionan las evidencias que dependen del caso y de la intervención
                            $sql="Select * from evidencia where id_caso=$myid_caso and id_intervencion=$fila_intervencion[0] and relacionado_con is null";
                            $result_evidencias=mysqli_query($link_portable, $sql);
                            $count_evidencias=mysqli_num_rows($result_evidencias);
                            if($count_evidencias!=0) {
                                while ($fila_evidencias = mysqli_fetch_row($result_evidencias)) {
                                    if(empty($fila_evidencias[9])) {
                                        $fila_evidencias[9]='null';
                                    }
                                    if(empty($fila_evidencias[14])) {
                                        $fila_evidencias[14]='null';
                                    }
                                    if(empty($fila_evidencias[19])) {
                                        $fila_evidencias[19]='null';
                                    }
                                    $sql = "INSERT INTO evidencia(id_tipo_evidencia, id_subtipo_evidencia, id_disco_almacenado, id_caso, id_intervencion, nombre,
                                            fecha_alta_evidencia, n_s, capacidad, marca, modelo, observaciones, tiene_subevidencias, relacionado_con, numero_evidencia,
                                            alias, patron, pin, id_tipo_capacidad ) values ($fila_evidencias[1],$fila_evidencias[2],$fila_evidencias[3], $myid_caso_safe,
                                            $ret_intervencion_safe[id_intervencion], '$fila_evidencias[6]', '$fila_evidencias[7]', '$fila_evidencias[8]', $fila_evidencias[9],
                                            '$fila_evidencias[10]', '$fila_evidencias[11]', '$fila_evidencias[12]', '$fila_evidencias[13]', $fila_evidencias[14],
                                            '$fila_evidencias[15]','$fila_evidencias[16]', '$fila_evidencias[17]', '$fila_evidencias[18]', $fila_evidencias[19])";
                                            mysqli_query($link, $sql);
                                            fputs($archivo,$sql.";\n");
                                            $query_evidencia=mysqli_query($link, "Select id_evidencia from evidencia WHERE id_caso=$myid_caso_safe and id_intervencion=$ret_intervencion_safe[id_intervencion] AND nombre='$fila_evidencias[6]' AND numero_evidencia='$fila_evidencias[15]'");
                                            $ret_evidencia_safe=mysqli_fetch_array($query_evidencia);
                                            // se comprueba si la evidencia añadida tiene hashes asociados y si es así, se agrega el hash a la BBDD
                                            $sql="Select * from hash where id_evidencia=$fila_evidencias[0]";
                                            $result_hash=mysqli_query($link_portable, $sql);
                                            $count_hash=mysqli_num_rows($result_hash);
                                            if($count_hash!=0){
                                                while ($fila_hash = mysqli_fetch_row($result_hash)) {
                                                    $sql = "INSERT INTO hash(id_evidencia, id_tipo_hash, hash) values ($ret_evidencia_safe[id_evidencia], $fila_hash[2], '$fila_hash[3]')";
                                                    mysqli_query($link, $sql);
                                                    fputs($archivo,$sql.";\n");
                                                    $query_hash=mysqli_query($link, "SELECT id_hash from hash where id_evidencia=$ret_evidencia_safe[id_evidencia] and hash='$fila_hash[3]'");
                                                    $ret_hash_safe=mysqli_fetch_array($query_hash);
                                                    //se añade el registro asociado a ese hash
                                                    $sql="Select * from evidencia_registro where id_hash=$fila_hash[0]";
                                                    $result_registro=mysqli_query($link_portable, $sql);
                                                    $count_registro=mysqli_num_rows($result_registro);
                                                    if($count_registro!=0) {
                                                        while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                                            $sql = "INSERT INTO evidencia_registro(id_evidencia, id_estado_evidencia, id_usuario, id_programa, id_accion_programa, id_hash,
                                                            observaciones,fecha_alta_estado) values ($ret_evidencia_safe[id_evidencia], $fila_registro[2], $fila_registro[3],
                                                            $fila_registro[4], $fila_registro[5], $ret_hash_safe[id_hash] ,'$fila_registro[7]', '$fila_registro[8]')";
                                                            mysqli_query($link, $sql);
                                                            fputs($archivo,$sql.";\n");
                                                        }
                                                    }
                                                }
                                                
                                            }
                                            // se añaden el resto de registros que no tienen hashes asociados
                                                            $sql="Select * from evidencia_registro where id_evidencia=$fila_evidencias[0] and id_hash is null";
                                                            $result_registro=mysqli_query($link_portable, $sql);
                                                            $count_registro=mysqli_num_rows($result_registro);
                                                            if($count_registro!=0) {
                                                                while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                                                    if(empty($fila_registro[4])) {
                                                                        $fila_registro[4]='null';
                                                                    }
                                                                    if(empty($fila_registro[5])) {
                                                                        $fila_registro[5]='null';
                                                                    }
                                                                    if(empty($fila_registro[6])) {
                                                                        $fila_registro[6]='null';
                                                                    }
                                                                $sql = "INSERT INTO evidencia_registro(id_evidencia, id_estado_evidencia, id_usuario, id_programa, id_accion_programa, id_hash,
                                                                observaciones,fecha_alta_estado) values ($ret_evidencia_safe[id_evidencia], $fila_registro[2], $fila_registro[3],
                                                                $fila_registro[4], $fila_registro[5], $fila_registro[6] ,'$fila_registro[7]', '$fila_registro[8]')";
                                                                mysqli_query($link, $sql);
                                                                fputs($archivo,$sql.";\n");
                                                                }
                                                            }
                                                            // se comprueba si existen evidencias que dependen de la que acabamos de añadir y en su caso se añaden
                                                            $sql="Select * from evidencia where relacionado_con=$fila_evidencias[0]";
                                                            $result_evidencias_dependientes=mysqli_query($link_portable, $sql);
                                                            $count_evidencias_dependientes=mysqli_num_rows($result_evidencias_dependientes);
                                                            if($count_evidencias_dependientes!=0) {
                                                                while ($fila_evidencias_dependientes = mysqli_fetch_row($result_evidencias_dependientes)) {
                                                                    if(empty($fila_evidencias_dependientes[9])) {
                                                                        $fila_evidencias_dependientes[9]='null';
                                                                    }
                                                                    if(empty($fila_evidencias_dependientes[19])) {
                                                                        $fila_evidencias_dependientes[19]='null';
                                                                    }
                                                                    $sql = "INSERT INTO evidencia(id_tipo_evidencia, id_subtipo_evidencia, id_disco_almacenado, id_caso, id_intervencion, nombre,
                                                                    fecha_alta_evidencia, n_s, capacidad, marca, modelo, observaciones, tiene_subevidencias, relacionado_con, numero_evidencia,
                                                                    alias, patron, pin, id_tipo_capacidad ) values ($fila_evidencias_dependientes[1],$fila_evidencias_dependientes[2],$fila_evidencias_dependientes[3], $myid_caso_safe,
                                                                    $ret_intervencion_safe[id_intervencion], '$fila_evidencias_dependientes[6]',  '$fila_evidencias_dependientes[7]', '$fila_evidencias_dependientes[8]',
                                                                    $fila_evidencias_dependientes[9], '$fila_evidencias_dependientes[10]', '$fila_evidencias_dependientes[11]', '$fila_evidencias_dependientes[12]',
                                                                    '$fila_evidencias_dependientes[13]', $ret_evidencia_safe[id_evidencia] ,'$fila_evidencias_dependientes[15]','$fila_evidencias_dependientes[16]',
                                                                    '$fila_evidencias_dependientes[17]', '$fila_evidencias_dependientes[18]', $fila_evidencias_dependientes[19])";
                                                                    mysqli_query($link, $sql);
                                                                    fputs($archivo,$sql.";\n");
                                                                    $query_evidencia=mysqli_query($link, "Select id_evidencia from evidencia WHERE id_caso=$myid_caso_safe and id_intervencion=$ret_intervencion_safe[id_intervencion] AND nombre='$fila_evidencias_dependientes[6]' AND numero_evidencia='$fila_evidencias_dependientes[15]'");
                                                                    $ret_evidencia_safe=mysqli_fetch_array($query_evidencia);
                                                                    // se comprueba si la evidencia añadida tiene hashes asociados y en su caso se añaden
                                                                    $sql="Select * from hash where id_evidencia=$fila_evidencias_dependientes[0]";
                                                                    $result_hash=mysqli_query($link_portable, $sql);
                                                                    $count_hash=mysqli_num_rows($result_hash);
                                                                    if($count_hash!=0){
                                                                        while ($fila_hash = mysqli_fetch_row($result_hash)) {
                                                                            $sql = "INSERT INTO hash(id_evidencia, id_tipo_hash, hash) values ($ret_evidencia_safe[id_evidencia], $fila_hash[2], '$fila_hash[3]')";
                                                                            mysqli_query($link, $sql);
                                                                            fputs($archivo,$sql.";\n");
                                                                            $query_hash=mysqli_query($link, "SELECT id_hash from hash where id_evidencia=$ret_evidencia_safe[id_evidencia] and hash='$fila_hash[3]'");
                                                                            $ret_hash_safe=mysqli_fetch_array($query_hash);
                                                                            //se añade el registro asociado a ese hash
                                                                            $sql="Select * from evidencia_registro where id_hash=$fila_hash[0]";
                                                                            $result_registro=mysqli_query($link_portable, $sql);
                                                                            $count_registro=mysqli_num_rows($result_registro);
                                                                            if($count_registro!=0) {
                                                                                while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                                                                    $sql = "INSERT INTO evidencia_registro(id_evidencia, id_estado_evidencia, id_usuario, id_programa, id_accion_programa, id_hash,
                                                                                    observaciones,fecha_alta_estado) values ($ret_evidencia_safe[id_evidencia], $fila_registro[2], $fila_registro[3],
                                                                                    $fila_registro[4], $fila_registro[5], $ret_hash_safe[id_hash] ,'$fila_registro[7]', '$fila_registro[8]')";
                                                                                    mysqli_query($link, $sql);
                                                                                    fputs($archivo,$sql.";\n");
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    // se añaden el resto de registros que no tienen hashes asociados
                                                                    $sql="Select * from evidencia_registro where id_evidencia=$fila_evidencias_dependientes[0] and id_hash is null";
                                                                    $result_registro=mysqli_query($link_portable, $sql);
                                                                    $count_registro=mysqli_num_rows($result_registro);
                                                                    if($count_registro!=0) {
                                                                        while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                                                            if(empty($fila_registro[4])) {
                                                                                $fila_registro[4]='null';
                                                                            }
                                                                            if(empty($fila_registro[5])) {
                                                                                $fila_registro[5]='null';
                                                                            }
                                                                            if(empty($fila_registro[6])) {
                                                                                $fila_registro[6]='null';
                                                                            }
                                                                            $sql = "INSERT INTO evidencia_registro(id_evidencia, id_estado_evidencia, id_usuario, id_programa, id_accion_programa, id_hash,
                                                                            observaciones,fecha_alta_estado) values ($ret_evidencia_safe[id_evidencia], $fila_registro[2], $fila_registro[3],
                                                                            $fila_registro[4], $fila_registro[5], $fila_registro[6] ,'$fila_registro[7]', '$fila_registro[8]')";
                                                                            mysqli_query($link, $sql);
                                                                            fputs($archivo,$sql.";\n");
                                                                        }
                                                                    }
                                                                }
                                                            }
                                }
                            }
                    }
                }
            }

     fclose($archivo);
   /*$_SESSION['respuesta']=1;
   echo '<script type="text/javascript">
	   location.href = "inicio.php";
        </script>';*/
        
}
else {
    echo "Error";
}

