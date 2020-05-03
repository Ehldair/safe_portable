<?php
session_start();
$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
$myid_intervencion=$_SESSION['id_intervencion'];


$sql="delete FROM intervencion WHERE id_intervencion=$myid_intervencion";
mysqli_query($link, $sql);

$_SESSION['respuesta']=6;
?>