<?php
session_start();
$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}



$myid_dias_pedidos=mysqli_real_escape_string($link,$_POST["category"]);

$sql="delete FROM dias_pedidos WHERE id_dias_pedidos=$myid_dias_pedidos";
mysqli_query($link, $sql);

$_SESSION['respuesta']=2;

?>