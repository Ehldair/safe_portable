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
    $ret_caso=mysqli_fetch_array($select_caso);
    
    
    if(isset($ret_caso['id_diligencias'])) {
        $select_diligencias=mysqli_query($link_portable, "Select * FROM diligencias WHERE id_diligencias=$ret_caso[id_diligencias]");
        $ret_diligencias=mysqli_fetch_array($select_diligencias);
        if($ret_caso['id_diligencias']!=null or $ret_caso['diligencias']!='') {
            $sql = "SELECT id_diligencias FROM diligencias  where id_juzgado=$ret_diligencias[id_juzgado] AND numero=$ret_diligencias[numero] AND año=$ret_diligencias[año]";
            $result=mysqli_query($link,$sql);
            echo $sql."<br>";
            $ret_diligencias_safe=mysqli_fetch_array($result);
            $count = mysqli_num_rows($result);
            if($count!=0) {
                $sql = "SELECT id_caso,numero,año FROM caso  where id_diligencias=$ret_diligencias_safe[id_diligencias]";
                $result=mysqli_query($link,$sql);
                $ret_caso_diligencias=mysqli_fetch_array($result);
                echo $sql."<br>";
                $count = mysqli_num_rows($result);
                if($count=!0) {
                    echo "Ya existen esas mismas diligencias en el caso ".$ret_caso_diligencias['numero']."_".substr( $ret_caso_diligencias['año'], 2);
                }
                else {
                    $sql = "INSERT INTO caso(id_diligencias, id_tipo_caso, id_grupo_investigacion, numero, año, nombre, fecha_alta_caso, descripcion, id_estado_caso) values ($ret_diligencias_safe[id_dil],$ret_caso[id_tipo_caso],$ret_caso[id_grupo_investigacion],$ret_caso[numero],'$ret_caso[año]','$ret_caso[nombre]',NOW(),'$ret_caso[descripcion]', 1)";
                    echo $sql;
                }
            }
            else {
                $sql = "INSERT INTO caso( id_tipo_caso, id_grupo_investigacion, numero, año, nombre, fecha_alta_caso, descripcion, id_estado_caso) values ($ret_caso[id_tipo_caso],$ret_caso[id_grupo_investigacion],$ret_caso[numero],'$ret_caso[año]','$ret_caso[nombre]',NOW(),'$ret_caso[descripcion]', 1)";
                echo $sql;
            }
        }
    }
    else {
        $sql = "INSERT INTO caso( id_tipo_caso, id_grupo_investigacion, numero, año, nombre, fecha_alta_caso, descripcion, id_estado_caso) values ($ret_caso[id_tipo_caso],$ret_caso[id_grupo_investigacion],$ret_caso[numero],'$ret_caso[año]','$ret_caso[nombre]',NOW(),'$ret_caso[descripcion]', 1)";
        echo $sql;
    }
   
    
}
else {
    echo "Error";
}

