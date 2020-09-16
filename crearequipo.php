<?php

session_start();

$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

$id_usuario=$_POST['usuario'];
$myid_intervencion=$_SESSION['id_intervencion'];

$sql=mysqli_query($link, "INSERT INTO equipo_intervencion (id_intervencion, id_usuario) values ($myid_intervencion, $id_usuario)");
$_SESSION['respuesta']=2;
echo 1;