<?php
session_start();
$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
$myid_caso=$_SESSION['id_caso'];

$sql="Select * from viajes where id_caso=$myid_caso";
$result=mysqli_query($link, $sql);
$count=mysqli_num_rows($result);
if($count!=0) {
    $_SESSION['respuesta']=5;
}
else {

$sql="delete FROM caso WHERE id_caso=$myid_caso";
mysqli_query($link, $sql);

$_SESSION['respuesta']=2;
}

?>