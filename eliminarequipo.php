<?php
session_start();
$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
$myid_usuario=mysqli_real_escape_string($link,$_POST["category"]);
$myid_intervencion=$_SESSION['id_intervencion'];

$sql="delete FROM equipo_intervencion WHERE id_usuario=$myid_usuario and id_intervencion=$myid_intervencion";
mysqli_query($link, $sql);
$_SESSION['respuesta']=3;

?>