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
if($mod!=1) {
    $myciudad=mysqli_real_escape_string($link,$_POST['ciudad']);
    $mytransporte=mysqli_real_escape_string($link,$_POST['transporte']);
    $mycaso=mysqli_real_escape_string($link,$_POST['caso']);
    if(isset($_POST['descripcion'])) {
        $mydescripcion=mysqli_real_escape_string($link,$_POST['descripcion']);
    }
    else {
        $mydescripcion=NULL;
    }
    mysqli_query($link, "INSERT INTO viajes (id_caso,id_transporte,ciudad,descripcion) VALUES ($mycaso, $mytransporte, '$myciudad', '$mydescripcion')");
    $_SESSION['respuesta']=1;
    echo 1;
}
else {
    $myid_viajes=$_SESSION['id_viajes'];
    $myciudad=mysqli_real_escape_string($link,$_POST['ciudad']);
    $mytransporte=mysqli_real_escape_string($link,$_POST['transporte']);
    if(isset($_POST['descripcion'])) {
        $mydescripcion=mysqli_real_escape_string($link,$_POST['descripcion']);
    }
    else {
        $mydescripcion=NULL;
    }
    $mycaso=mysqli_real_escape_string($link,$_POST['caso']);
    mysqli_query($link, "UPDATE viajes set id_caso=$mycaso, id_transporte=$mytransporte, ciudad='$myciudad', descripcion='$mydescripcion' where id_viajes=$myid_viajes");
    $_SESSION['respuesta']=2;
    echo 2;
}

mysqli_close($link);
?>


