<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
$mymod=$_SESSION['mod'];
$myid_registro=$_SESSION['id_registro'];
$myid_ev=$_SESSION['id_ev'];
$myusuario=mysqli_real_escape_string($link,$_POST['usuario']);
$myestado=mysqli_real_escape_string($link,$_POST['estado']);
$mytipo_hash=mysqli_real_escape_string($link,$_POST['tipo_hash']);
if (isset($_POST['programa'])) {
    $myprograma=mysqli_real_escape_string($link,$_POST['programa']);
}
else {
    $myprograma='null';
}

if (isset($_POST['accion_programa'])) {
    $myaccion_programa=mysqli_real_escape_string($link,$_POST['accion_programa']);
}
else {
    $myaccion_programa='null';
}

if (isset($_POST['detalles'])) {
    $myobservaciones=mysqli_real_escape_string($link,$_POST['detalles']);
}
else {
    $myobservaciones="SIN OBSERVACIONES";
}

if (!empty($_POST['num_hash'])) {
$myhash=mysqli_real_escape_string($link,$_POST['num_hash']);
}
else {
$myhash='0';
}
if (!empty($_POST['num_hash_original'])) {
    $myhash_original=mysqli_real_escape_string($link,$_POST['num_hash_original']);
}
else {
    $myhash_original='0';
}
if (isset($_POST['id_hash'])) {
    $myid_hash=mysqli_real_escape_string($link,$_POST['id_hash']);
}
else {
    $myid_hash='0';
}
if($mymod!=1) {
    if($myhash!='0') {
        $sql= mysqli_query($link, "INSERT INTO hash (id_evidencia,id_tipo_hash,hash) VALUES ($myid_ev, $mytipo_hash, '$myhash')");
        $sql= mysqli_query($link, "SELECT id_hash FROM hash WHERE id_evidencia=$myid_ev and hash='$myhash'");
        $ret = mysqli_fetch_array($sql);
        $myid_hash=$ret['id_hash'];
        $sql= mysqli_query($link,"INSERT INTO evidencia_registro (id_evidencia,id_estado_evidencia,id_usuario,id_programa,id_accion_programa,id_hash,observaciones, fecha_alta_estado) VALUES ($myid_ev, $myestado, $myusuario, $myprograma, $myaccion_programa,$myid_hash,'$myobservaciones', NOW())");
        
    }
    
    else {
    
    $sql="INSERT INTO evidencia_registro (id_evidencia,id_estado_evidencia,id_usuario,id_programa,id_accion_programa,observaciones, fecha_alta_estado) VALUES ($myid_ev, $myestado, $myusuario, $myprograma, $myaccion_programa, '$myobservaciones', NOW())";
    mysqli_query($link,  $sql);
    }
    $_SESSION['respuesta']=2;
    echo "<script type='text/javascript'>
    location.href = 'detalle_evidencia.php';
    </script>";
    
}
else {

    if($myhash!=$myhash_original AND $myhash!='0' AND $myhash_original!='0') {
        $sql="Update hash set id_tipo_hash=$mytipo_hash, hash='$myhash' WHERE id_hash=$myid_hash";
        mysqli_query($link, $sql);
        echo $sql;
        $sql= "UPDATE evidencia_registro set id_estado_evidencia=$myestado, id_usuario=$myusuario, id_programa=$myprograma, id_accion_programa=$myaccion_programa, observaciones='$myobservaciones' WHERE id_evidencia_registro=$myid_registro";
        mysqli_query($link, $sql);
        echo $sql;
        echo "Actualizo";
    }
    else {
        if($myhash!='0' and $myhash_original=='0') {
            $sql= mysqli_query($link, "INSERT INTO hash (id_evidencia,id_tipo_hash,hash) VALUES ($myid_ev, $mytipo_hash, '$myhash')");
            $sql= mysqli_query($link, "SELECT id_hash FROM hash WHERE id_evidencia=$myid_ev and hash='$myhash'");
            $ret = mysqli_fetch_array($sql);
            $myid_hash=$ret['id_hash'];
            mysqli_query($link, "UPDATE evidencia_registro set id_estado_evidencia=$myestado, id_usuario=$myusuario, id_programa=$myprograma, id_accion_programa=$myaccion_programa,id_hash='$myid_hash',observaciones='$myobservaciones' where id_evidencia_registro=$myid_registro");    
        }
        else {
            $sql="UPDATE evidencia_registro set id_estado_evidencia=$myestado, id_usuario=$myusuario, id_programa=$myprograma, id_accion_programa=$myaccion_programa, observaciones='$myobservaciones' WHERE id_evidencia_registro=$myid_registro";
            mysqli_query($link, $sql );
            echo $sql;
            echo "ENTRO AQUI";
        }
    }
    $_SESSION['respuesta']=1;
    echo "<script type='text/javascript'>
    location.href = 'detalle_registro.php';
    </script>";
    
}
    

   
  
mysqli_close($link);

?>