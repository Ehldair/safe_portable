<?php
session_start();
$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
$myid_su=$_SESSION['id_su'];


$sql="delete FROM sujeto_activo WHERE id_sujeto_activo=$myid_su";
mysqli_query($link, $sql);

$_SESSION['respuesta']=5;
?>