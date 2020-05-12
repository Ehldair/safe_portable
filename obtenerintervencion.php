<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");

$myid_caso=$_SESSION['id_caso'];

// comprobar la conexi�n
if (mysqli_connect_errno()) {
    printf("Fall� la conexi�n: %s\n", mysqli_connect_error());
    exit();
}

    $resultado = mysqli_query($link, "SELECT numero_intervencion FROM intervencion WHERE id_caso='$myid_caso' and numero_intervencion=(Select MAX(numero_intervencion) from intervencion)");
    $ret=mysqli_fetch_array($resultado);
    $numero_intervencion=$ret['numero_intervencion']+1;
    
    echo $numero_intervencion;

?>