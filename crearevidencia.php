<?php
session_start();


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

$myid_u=$_SESSION['id_u'];
$myid_caso=$_SESSION['id_caso'];


#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}



if($mod!="1") {
    
    //recojo los valores
    $mynombre = mysqli_real_escape_string($link,$_POST['nombre']);
    $mynumero = mysqli_real_escape_string($link,$_POST['numero']);
    $myn_s = mysqli_real_escape_string($link,$_POST['n_s']);
    if(empty($_POST['n_s'])) {
        $myn_s=0;
    }
    if(empty($_POST['capacidad']) OR $_POST['capacidad']==0) {
        $mycapacidad='null';
    }
    else {
        $mycapacidad = mysqli_real_escape_string($link,$_POST['capacidad']);
    }
    $mymarca = mysqli_real_escape_string($link,$_POST['marca']);
    $mymodelo = mysqli_real_escape_string($link,$_POST['modelo']);
    $myobservaciones = mysqli_real_escape_string($link,$_POST['observaciones']);
    $mysubtipo = mysqli_real_escape_string($link,$_POST['subtipo']);
    $mydisco = mysqli_real_escape_string($link,$_POST['disco']);
    if(isset($_POST['tipo_capacidad'])) {
        $mytipo_capacidad = mysqli_real_escape_string($link,$_POST['tipo_capacidad']);
    }
    else {
        $mytipo_capacidad='null';
    }
    if(isset($_POST['disco']) AND ($_POST['disco']!='')) {
        $mydisco = mysqli_real_escape_string($link,$_POST['disco']);
    }
    else {
        $mydisco='null';
    }
    
    if (isset($_POST['alias'])) {
        $myalias = mysqli_real_escape_string($link,$_POST['alias']);
    }
    else {
        $myalias=null;
    }
    if (isset($_POST['pin'])) {
        $mypin = mysqli_real_escape_string($link,$_POST['pin']);
    }
    else {
        $mypin=null;
    }
    $mypadre = 0;
    $myintervencion = $_SESSION['id_intervencion'];
    if(isset($_POST['depende'])) {
        $mypadre = mysqli_real_escape_string($link,$_POST['padre']);
    }
    
    //Comienzan las comprobaciones
    $sql= mysqli_query($link, "Select id_evidencia as tipo FROM evidencia WHERE id_caso=$myid_caso AND nombre='$mynombre' AND numero_evidencia='$mynumero'");
    $count = mysqli_num_rows($sql);
    
    if($count!=0) {
        echo "Ya existe esa evidencia ";
    }
    else {
        
        $sql= mysqli_query($link, "Select id_tipo_evidencia as tipo FROM subtipo_evidencia WHERE id_subtipo_evidencia='$mysubtipo'");
        $ret= mysqli_fetch_array($sql);
        $mytipo=$ret['tipo'];
        
        $sql = "INSERT INTO evidencia(id_tipo_evidencia, id_subtipo_evidencia, id_disco_almacenado, id_caso, id_intervencion, nombre, numero_evidencia, alias, pin, fecha_alta_evidencia, n_s, capacidad, marca, modelo, observaciones, id_tipo_capacidad, tiene_subevidencias) values ($mytipo,$mysubtipo,$mydisco,$myid_caso, $myintervencion, '$mynombre', '$mynumero', '$myalias', '$mypin', NOW(), '$myn_s', $mycapacidad, '$mymarca', '$mymodelo', '$myobservaciones',$mytipo_capacidad, 0 )";
        mysqli_query($link,$sql);
        
        $sql=  "Select id_evidencia as id FROM evidencia WHERE id_caso=$myid_caso AND nombre='$mynombre' AND numero_evidencia=$mynumero";
        
        $resultado=mysqli_query($link, $sql);
        $ret= mysqli_fetch_array($resultado);
        $myid_evidencia=$ret['id'];
        if($mypadre!=0) {
            $sql= mysqli_query($link, "update evidencia set relacionado_con='$mypadre' WHERE id_evidencia=$myid_evidencia");
            $sql= mysqli_query($link, "update evidencia set tiene_subevidencias='1' WHERE id_evidencia=$mypadre");
        }
        if(isset($_SESSION['patron'])) {
            $sql= mysqli_query($link, "update evidencia set patron='$_SESSION[patron]' WHERE id_evidencia=$myid_evidencia");
        }
        $sql="INSERT INTO evidencia_registro(id_evidencia,id_estado_evidencia,id_usuario,fecha_alta_estado) VALUES ($myid_evidencia, 1, $myid_u, NOW())";
        mysqli_query($link,$sql);
        $_SESSION['respuesta']=2;
        echo 2;
    }
}
else {
    $myborrarpatron=$_POST['borrarpatron'];
    $mynombre_nuevo = mysqli_real_escape_string($link,$_POST['nombre_nuevo']);
    $mynombre_original = mysqli_real_escape_string($link,$_POST['nombre_original']);
    $mynumero_nuevo = mysqli_real_escape_string($link,$_POST['numero_nuevo']);
    $mynumero_original = mysqli_real_escape_string($link,$_POST['numero_original']);
    if(isset($_POST['tipo_capacidad'])) {
        $mytipo_capacidad = mysqli_real_escape_string($link,$_POST['tipo_capacidad']);
    }
    if(isset($_POST['n_s'])) {
        $myn_s = mysqli_real_escape_string($link,$_POST['n_s']);
    }
    if(empty($_POST['capacidad']) or $_POST['capacidad']==0) {
        $mycapacidad='null';
        $mytipo_capacidad='null';
    }
    else {
        $mycapacidad = mysqli_real_escape_string($link,$_POST['capacidad']);
    }
    
    if(isset($_POST['marca'])) {
        $mymarca = mysqli_real_escape_string($link,$_POST['marca']);
    }
    if(isset($_POST['modelo'])) {
        $mymodelo = mysqli_real_escape_string($link,$_POST['modelo']);
    }
    
    if(isset($_POST['observaciones'])) {
        $myobservaciones = mysqli_real_escape_string($link,$_POST['observaciones']);
    }
    if(isset($_POST['tipo'])) {
        $mytipo = mysqli_real_escape_string($link,$_POST['tipo']);
    }
    if(isset($_POST['subtipo'])) {
        $mysubtipo = mysqli_real_escape_string($link,$_POST['subtipo']);
    }
    if(isset($_POST['disco']) AND ($_POST['disco']!='') AND ($_POST['disco']!='null') AND ($_POST['disco']!=null)) {
        $mydisco = mysqli_real_escape_string($link,$_POST['disco']);
    }
    else {
        $mydisco='null';
    }
    if(isset($_POST['alias'])) {
        $myalias = mysqli_real_escape_string($link,$_POST['alias']);
    }
    if (isset($_POST['pin'])) {
        $mypin = mysqli_real_escape_string($link,$_POST['pin']);
    }
    
    if($mynumero_nuevo==$mynumero_original AND $mynombre_nuevo==$mynombre_original) {
        
        $sql= mysqli_query($link, "Select id_evidencia as ev FROM evidencia WHERE id_caso='$myid_caso' AND nombre='$mynombre_original' AND numero_evidencia='$mynumero_original'");
        $ret= mysqli_fetch_array($sql);
        $myid=$ret['ev'];
        
        $sql= mysqli_query($link, "update evidencia SET id_tipo_evidencia=$mytipo, id_subtipo_evidencia=$mysubtipo, id_disco_almacenado=$mydisco, id_caso='$myid_caso', n_s='$myn_s', capacidad=$mycapacidad, id_tipo_capacidad=$mytipo_capacidad, marca='$mymarca', modelo='$mymodelo', observaciones='$myobservaciones', alias='$myalias', pin='$mypin' WHERE id_evidencia=$myid");
        if($myborrarpatron==0) {
            $sql= mysqli_query($link, "update evidencia set patron=null WHERE id_evidencia=$myid");
        }
        else {
            if($myborrarpatron==1) {
                
                $sql= "update evidencia set patron='$_SESSION[patron]' WHERE id_evidencia=$myid";
                mysqli_query($link, $sql);
                
            }
        }
        $_SESSION['respuesta'] = 1;
        echo 1;
    }
    else {
        $sql= mysqli_query($link, "Select id_evidencia as tipo FROM evidencia WHERE id_caso=$myid_caso AND nombre='$mynombre_nuevo' AND numero_evidencia='$mynumero_nuevo'");
        $count = mysqli_num_rows($sql);
        if($count!=0) {
            echo "Ya existe esa evidencia";
        }
        else {
            $sql = mysqli_query($link, "Select id_evidencia as ev FROM evidencia WHERE id_caso='$myid_caso' AND nombre='$mynombre_original' AND numero_evidencia='$mynumero_original'");
            $ret = mysqli_fetch_array($sql);
            $myid = $ret['ev'];
            
            $sql = mysqli_query($link, "update evidencia SET id_tipo_evidencia=$mytipo, id_subtipo_evidencia=$mysubtipo, id_disco_almacenado=$mydisco, id_caso='$myid_caso', nombre='$mynombre_nuevo', numero_evidencia='$mynumero_nuevo', n_s='$myn_s', capacidad=$mycapacidad, id_tipo_capacidad=$mytipo_capacidad, marca='$mymarca', modelo='$mymodelo', observaciones='$myobservaciones', alias='$myalias', pin='$mypin' WHERE id_evidencia=$myid");
            if($myborrarpatron==0) {
                $sql= mysqli_query($link, "update evidencia set patron=null WHERE id_evidencia=$myid");
            }
            else {
                if($myborrarpatron==1) {
                    
                    $sql= "update evidencia set patron='$_SESSION[patron]' WHERE id_evidencia=$myid";
                    mysqli_query($link, $sql);
                    
                }
            }
            
            $_SESSION['nombre'] = $mynombre_nuevo;
            
            $_SESSION['numero'] = $mynumero_nuevo;
            $_SESSION['respuesta'] = 1;
            echo 1;
        }
    }
}

mysqli_close($link);
?>

