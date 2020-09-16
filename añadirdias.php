<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
if(isset($_POST['viaje'])) {
    $myid_viajes=$_POST['viaje'];
}
else {
    $myid_viajes='null';
}
$myusuario=mysqli_real_escape_string($link,$_POST['usuario']);
$mydescripcion=$_POST['descripcion'];
$mydias=$_POST['dias'];
$mytipo=$_POST['tipo'];
mysqli_query($link, "INSERT INTO compensacion (id_tipo_compensacion,id_usuario,id_viajes,dias,descripcion, fecha_alta_dias) VALUES ($mytipo,$myusuario, $myid_viajes, '$mydias', '$mydescripcion', NOW())");
$_SESSION['respuesta']=1;
echo 1;

mysqli_close($link);
?>


