<?php
session_start();
$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}



$myid_compensacion=mysqli_real_escape_string($link,$_POST["category"]);

$sql="delete FROM compensacion WHERE id_compensacion=$myid_compensacion";
mysqli_query($link, $sql);

$_SESSION['respuesta']=2;

?>