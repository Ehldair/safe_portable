<?php
session_start();
$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
$myid_viajes=$_SESSION['id_viajes'];
$myid_usuario=mysqli_real_escape_string($link,$_POST["category"]);

$sql="delete FROM viajes_funcionario WHERE id_usuario=$myid_usuario and id_viajes=$myid_viajes";
mysqli_query($link, $sql);
$_SESSION['respuesta']=1;

?>