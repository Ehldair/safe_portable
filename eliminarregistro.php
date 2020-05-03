<?php
session_start();
$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

$myid_registro=$_SESSION['id_registro'];


$sql="delete FROM evidencia_registro WHERE id_evidencia_registro=$myid_registro";
mysqli_query($link, $sql);

$_SESSION['respuesta']=3;
?>