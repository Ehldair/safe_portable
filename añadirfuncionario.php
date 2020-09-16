<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

    $myid_viajes=$_SESSION['id_viajes'];
    $myusuario=mysqli_real_escape_string($link,$_POST['usuario']);
    $myfecha_inicio=$_POST['fecha_inicio'];
    $myfecha_fin=$_POST['fecha_fin'];
    mysqli_query($link, "INSERT INTO VIAJES_FUNCIONARIO (id_usuario,id_viajes,fecha_inicio,fecha_fin) VALUES ($myusuario, $myid_viajes, '$myfecha_inicio','$myfecha_fin')");
    $_SESSION['respuesta']=3;
    echo 3;

mysqli_close($link);
?>


