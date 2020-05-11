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

    $resultado = mysqli_query($link, "SELECT * FROM intervencion WHERE id_caso='$myid_caso'");
    $count=mysqli_num_rows($resultado);
    $numero_intervencion=$count+1;
    
    echo $numero_intervencion;

?>