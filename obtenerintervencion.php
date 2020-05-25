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

$sql="SELECT MAX(numero_intervencion) as numero_intervencion FROM intervencion WHERE id_caso='$myid_caso'";
$resultado = mysqli_query($link, $sql);
$count=mysqli_num_rows($resultado);
if($count!=0) {
    $ret=mysqli_fetch_array($resultado);
    $numero_intervencion=$ret['numero_intervencion']+1;
}
else {
    $numero_intervencion=1;
}

echo $numero_intervencion;

?>