<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
$myusuario=mysqli_real_escape_string($link,$_POST['usuario']);
$mydescripcion=$_POST['descripcion'];
$mydias=$_POST['dias'];
$myfecha=$_POST['fecha'];
mysqli_query($link, "INSERT INTO dias_pedidos (id_usuario,dias,fecha_desde,descripcion) VALUES ($myusuario, '$mydias', '$myfecha', '$mydescripcion')");
$_SESSION['respuesta']=1;
echo 1;

mysqli_close($link);
?>


