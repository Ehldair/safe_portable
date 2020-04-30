<?php
session_start();
$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
$myid_caso=$_SESSION['id_caso'];


$sql="delete FROM caso WHERE id_caso=$myid_caso";
mysqli_query($link, $sql);

$_SESSION['respuesta']=2;

?>