<?php
session_start();
$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
$myid_viajes=$_SESSION['id_viajes'];
$sql="delete FROM viajes WHERE id_viajes=$myid_viajes";
mysqli_query($link, $sql);
$_SESSION['respuesta']=1;


?>