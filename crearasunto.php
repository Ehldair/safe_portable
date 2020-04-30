<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

if(isset($_GET["mod"])) {
    $mod= $_GET["mod"];
}
else {
    if(isset($_POST["mod"])) {
        $mod= $_POST["mod"];
    }
    else {
        $mod= 0;
    }
}
if(!empty($_SESSION['id_caso'])) {
    $myid_caso=$_SESSION['id_caso'];
}
else {
    $myid_caso=0;
}
if($mod!="1") {
    $mynumero = mysqli_real_escape_string($link,$_POST['numero']);
    $myaño = mysqli_real_escape_string($link,$_POST['año']);
    if(isset($_POST["nombre"])) {
        $mynombre = mysqli_real_escape_string($link,$_POST['nombre']);
    }
    else {
        $mynombre=null;
    }
    if (isset($_POST["descripcion"])){
        $mydescripcion = mysqli_real_escape_string($link,$_POST['descripcion']);
    }
    else {
        $mydescripcion= null;
    }
    if (isset($_POST["diligencias"])){
        $mydiligencias = mysqli_real_escape_string($link,$_POST['diligencias']);
    }
    if (isset($_POST["año_diligencias"])){
        $myaño_diligencias = mysqli_real_escape_string($link,$_POST['año_diligencias']);
    }
    if (isset($_POST["juzgado"])){
        $myjuzgado = mysqli_real_escape_string($link,$_POST['juzgado']);
    }
    if (isset($_POST["tipo_caso"])){
        $mytipo_caso = mysqli_real_escape_string($link,$_POST['tipo_caso']);
    }
    if (isset($_POST["tipo_caso"])){
        $myca = mysqli_real_escape_string($link,$_POST['ca']);
    }
    if (isset($_POST["provincia"])){
        $myprovincia = mysqli_real_escape_string($link,$_POST['provincia']);
    }
    if (isset($_POST["comisaria"])){
        $mycomisaria = mysqli_real_escape_string($link,$_POST['comisaria']);
    }
    if (isset($_POST["grupo"])){
        $mygrupo = mysqli_real_escape_string($link,$_POST['grupo']);
    }
    if(!empty($_POST["diligencias"])) {
        $sql = "SELECT d.id_diligencias FROM diligencias d where d.numero=$mydiligencias AND d.año=$myaño_diligencias AND d.id_juzgado=$myjuzgado";
        $result=mysqli_query($link,$sql);
        $count = mysqli_num_rows($result);
        if($count!=0) {
            echo "Las diligencias ya existen en otro caso";
        }
        else {
            $sql = "SELECT * FROM caso where numero=$mynumero AND año=$myaño";
            $result=mysqli_query($link,$sql);
            $count = mysqli_num_rows($result);
            if($count!=0) {
                echo "Ya existe un caso con ese mismo número";
            }
            else {
                $sql = "INSERT INTO diligencias(id_juzgado, numero, año, fecha) values ($myjuzgado,$mydiligencias,$myaño_diligencias,NOW())";
                mysqli_query($link,$sql);
                $sql = "SELECT id_diligencias FROM diligencias WHERE numero=$mydiligencias AND año=$myaño_diligencias";
                $result=mysqli_query($link,$sql);
                $line = mysqli_fetch_array($result, MYSQLI_ASSOC);
                foreach($line as $col_value) {
                    $myid_diligencias=$col_value;
                }
                $sql = "INSERT INTO caso(id_diligencias, id_tipo_caso, id_grupo_investigacion, numero, año, nombre, fecha_alta_caso, descripcion, id_estado_caso) values ($myid_diligencias,$mytipo_caso,$mygrupo,$mynumero,'$myaño','$mynombre',NOW(),'$mydescripcion', 1)";
                mysqli_query($link,$sql);
                $sql = "SELECT id_caso FROM caso WHERE numero=$mynumero AND año=$myaño";
                $resultado=mysqli_query($link,$sql);
                $ret_caso=mysqli_fetch_array($resultado);
                $_SESSION['respuesta']=8;
                $_SESSION['id_caso']=$ret_caso['id_caso'];
                echo 1;
            }
        }
    }
    else {
        $sql = "SELECT * FROM caso where numero=$mynumero AND año=$myaño";
        $result=mysqli_query($link,$sql);
        if(mysqli_errno($link)!=0) {
            echo "Faltan campos por rellenar";
        }
        else {
            $count = mysqli_num_rows($result);
            if($count!=0) {
                echo "Ya existe un caso con ese mismo número";
            }
            else {
                $sql = "INSERT INTO caso( id_tipo_caso,  id_grupo_investigacion, numero, año, nombre, fecha_alta_caso, descripcion, id_estado_caso) values ($mytipo_caso,$mygrupo,$mynumero,$myaño,'$mynombre',NOW(),'$mydescripcion', 1)";
                mysqli_query($link,$sql);
                if(mysqli_errno($link)!=0) {
                    echo "Faltan campos por rellenar";
                }
                else {
                    $sql = "SELECT id_caso FROM caso WHERE numero=$mynumero AND año=$myaño";
                    $resultado=mysqli_query($link,$sql);
                    $ret_caso=mysqli_fetch_array($resultado);
                    $_SESSION['respuesta']=8;
                    $_SESSION['id_caso']=$ret_caso['id_caso'];
                    echo 1;
                }
            
            }
        }
    }
}
else {
    $mysonnuevas=mysqli_real_escape_string($link,$_POST['sonnuevas']);
    if(isset($_POST['borrar'])){
        $myborrar=mysqli_real_escape_string($link,$_POST['borrar']);
    }
    else {
        $myborrar=0;
    }
    
    $mynumero_nuevo=mysqli_real_escape_string($link,$_POST['numero_nuevo']);
    $mynumero_original=mysqli_real_escape_string($link,$_POST['numero_original']);
    $myaño_nuevo=mysqli_real_escape_string($link,$_POST['año_nuevo']);
    $myaño_original=mysqli_real_escape_string($link,$_POST['año_original']);
    $mynombre=mysqli_real_escape_string($link,$_POST['nombre']);
    $mydescripcion=mysqli_real_escape_string($link,$_POST['descripcion']);
    if(isset($_POST['diligencias_nuevo'])) {
        $mydiligencias_nuevo = mysqli_real_escape_string($link, $_POST['diligencias_nuevo']);
    }
    if(isset($_POST['diligencias_original'])) {
        $mydiligencias_original = mysqli_real_escape_string($link, $_POST['diligencias_original']);
    }
    if(isset($_POST['año_diligencias_nuevo'])) {
        $myaño_diligencias_nuevo = mysqli_real_escape_string($link, $_POST['año_diligencias_nuevo']);
    }
    if(isset($_POST['año_diligencias_original'])) {
        $myaño_diligencias_original = mysqli_real_escape_string($link, $_POST['año_diligencias_original']);
    }
    if(isset($_POST['juzgado_nuevo'])) {
        $myjuzgado_nuevo = mysqli_real_escape_string($link, $_POST['juzgado_nuevo']);
    }
    if(isset($_POST['juzgado_original'])) {
        $myjuzgado_original = mysqli_real_escape_string($link, $_POST['juzgado_original']);
    }
    if(isset($_POST['id_diligencias'])) {
        $myid_diligencias = mysqli_real_escape_string($link, $_POST['id_diligencias']);
    }
    if($mysonnuevas==0 and $myborrar==0) {
        $agregardiligencias = 1;
    }
    else {
        if($mysonnuevas==2) {
            $agregardiligencias=2;
        }
        else {
            $agregardiligencias=0;
            $errordiligencias=0;
            $nuevasdiligencias=0;
            $cambiardiligencias=0;
        }
    }
    $myestado = mysqli_real_escape_string($link,$_POST['estado']);
    $mytipo_caso = mysqli_real_escape_string($link,$_POST['tipo_caso']);
    $mygrupo = mysqli_real_escape_string($link, $_POST['grupo']);
    
    if((($mynumero_nuevo!=$mynumero_original) or ($myaño_nuevo!=$myaño_original)) AND ($mynumero_nuevo!=0)) {
        $sql="Select id_caso from caso where numero='$mynumero_nuevo' and año=$myaño_nuevo";
        $resultado=mysqli_query($link, $sql);
        $count=mysqli_num_rows($resultado);
        if($count!=0) {
            echo "Ya existe un caso con el mismo número. ";
            $errorcaso=1;
        }
        else {
            $errorcaso=0;
            $cambiarnumero=1;
        }
    }
    else {
        $errorcaso=0;
        $cambiarnumero=0;
    }
    if($agregardiligencias==1) {
        if(($mydiligencias_nuevo!=$mydiligencias_original) OR ($myaño_diligencias_nuevo!=$myaño_diligencias_original) OR ($myjuzgado_nuevo!=$myjuzgado_original)) {
            $sql="Select id_diligencias From diligencias where numero=$mydiligencias_nuevo AND año=$myaño_diligencias_nuevo AND id_juzgado=$myjuzgado_nuevo";
            $resultado=mysqli_query($link, $sql);
            $count=mysqli_num_rows($resultado);
            if($count!=0) {
                $ret=mysqli_fetch_array($resultado);
                $sql="Select * FRom caso where id_diligencias=$ret[id_diligencias]";
                $resultado=mysqli_query($link, $sql);
                $count=mysqli_num_rows($resultado);
                if($count!=0) {
                    echo "Ya existen las mismas evidencias en otro caso.";
                    $errordiligencias=1;
                    $errorcaso=1;
                }
                else {
                    if($mydiligencias_nuevo==0) {
                        echo "Las diligencias no pueden ser 0.";
                        $errordiligencias=1;
                        $errorcaso=1;
                    }
                    else {
                        $nuevasdiligencias=0;
                        $cambiardiligencias=1;
                        $errordiligencias=0;
                    }
                }
            }
            else {
                if($mydiligencias_nuevo==0) {
                    echo "Las diligencias no pueden ser 0.";
                    $errordiligencias=1;
                    $errorcaso=1;
                }
                else {
                    $errordiligencias=0;
                    $nuevasdiligencias=1;
                    $cambiardiligencias=0;
                }
            }
        }
        else {
            $errordiligencias=0;
            $nuevasdiligencias=0;
            $cambiardiligencias=0;
        }
    }
    else {
        if($agregardiligencias==2) {
            $sql="Select id_diligencias FRom diligencias where numero=$mydiligencias_nuevo AND año=$myaño_diligencias_nuevo AND id_juzgado=$myjuzgado_nuevo";
            $resultado=mysqli_query($link, $sql);
            $count=mysqli_num_rows($resultado);
            if($count!=0) {
                $ret=mysqli_fetch_array($resultado);
                $sql="Select * FRom caso where id_diligencias=$ret[id_diligencias]";
                $resultado=mysqli_query($link, $sql);
                $count=mysqli_num_rows($resultado);
                if($count!=0) {
                    echo "Ya existen las mismas evidencias en otro caso.";
                    $errordiligencias=1;
                    $errorcaso=1;
                }
                else {
                    if($mydiligencias_nuevo==0) {
                        echo "Las diligencias no pueden ser 0.";
                        $errordiligencias=1;
                        $errorcaso=1;
                    }
                    else {
                        $errordiligencias=0;
                        $nuevasdiligencias=0;
                        $cambiardiligencias=1;
                    }
                }
            }
            else {
                if($mydiligencias_nuevo==0) {
                    echo "Las diligencias no pueden ser 0.";
                    $errordiligencias=1;
                    $errorcaso=1;
                }
                else {
                    $errordiligencias=0;
                    $nuevasdiligencias=1;
                    $cambiardiligencias=0;
                }
            }
        }
    }
    if($errorcaso!=1) {
        $sql="UPDATE caso set id_tipo_caso=$mytipo_caso, id_grupo_investigacion=$mygrupo, id_estado_caso=$myestado, nombre='$mynombre', descripcion='$mydescripcion'";
        $sql2=" WHERE id_caso=$myid_caso";
        if($cambiarnumero==1) {
            $sql=$sql." ,numero=$mynumero_nuevo, año=$myaño_nuevo";
        }
        $sql=$sql.$sql2;
        $resultado=mysqli_query($link, $sql);
    }
    $entro=0;
    if($errordiligencias!=1 and $errorcaso!=1) {
        if($nuevasdiligencias==1) {
            $entro=1;
            $sql="INSERT INTO diligencias(id_juzgado, numero, año, fecha) VALUES ($myjuzgado_nuevo,$mydiligencias_nuevo,$myaño_diligencias_nuevo, NOW())";
            $resultado=mysqli_query($link, $sql);
        }
        else {
            if($cambiardiligencias==1) {
                $entro=1;
            }
        }
        if($entro==1) {
            $sql="SELECT id_diligencias FROM diligencias where numero=$mydiligencias_nuevo AND año=$myaño_diligencias_nuevo AND id_juzgado=$myjuzgado_nuevo";
            $resultado=mysqli_query($link, $sql);
            $ret=mysqli_fetch_array($resultado);
            $sql="UPDATE caso set id_diligencias=$ret[id_diligencias] WHERE id_caso=$myid_caso";
            $resultado=mysqli_query($link, $sql);
        }
    }
    if($myborrar==1) {
        $sql="UPDATE caso set id_diligencias=null WHERE id_caso=$myid_caso";
        $resultado=mysqli_query($link, $sql);
        $errordiligencias=0;
    }
    if($errorcaso!=1 AND $errordiligencias!=1) {
        $_SESSION['respuesta']=1;
        echo 1;
    }
}

mysqli_close($link);
?>


