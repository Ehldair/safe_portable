<?php
session_start();
$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}



$myid=mysqli_real_escape_string($link,$_POST["category"]);

$sql_hash="SELECT id_hash FROM evidencia_registro WHERE id_evidencia_registro=$myid";
$resultado_hash=mysqli_query($link, $sql_hash);
$ret_hash=mysqli_fetch_array($resultado_hash);


$sql="delete FROM evidencia_registro WHERE id_evidencia_registro=$myid";
mysqli_query($link, $sql);

$sql_eliminar_hash="delete FROM hash WHERE id_hash=$ret_hash[id_hash]";
mysqli_query($link, $sql_eliminar_hash);


$_SESSION['respuesta']=3;

?>