<?php
session_start();
$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

$myid_registro=$_SESSION['id_registro'];

$sql_hash="SELECT id_hash FROM evidencia_registro WHERE id_evidencia_registro=$myid_registro";
$resultado_hash=mysqli_query($link, $sql_hash);
$ret_hash=mysqli_fetch_array($resultado_hash);


$sql="delete FROM evidencia_registro WHERE id_evidencia_registro=$myid_registro";
mysqli_query($link, $sql);

$sql_eliminar_hash="delete FROM hash WHERE id_hash=$ret_hash[id_hash]";
mysqli_query($link, $sql_eliminar_hash);


$_SESSION['respuesta']=3;
?>