<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$myinter=0;
$mytipo=$_GET['tipo'];
if( isset($_GET['inter']) ) {
    $myinter = $_GET['inter'];
}

$myid_caso=$_SESSION['id_caso'];



$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");

// comprobar la conexi�n
if (mysqli_connect_errno()) {
    printf("Fall� la conexi�n: %s\n", mysqli_connect_error());
    exit();
}
$resultado = mysqli_query($link, "SELECT etiqueta as et FROM subtipo_evidencia where id_subtipo_evidencia='$mytipo'");
$ret = mysqli_fetch_array($resultado);
$resultado_id_intervencion=mysqli_query($link, "SELECT id_intervencion as id FROM intervencion where id_caso='$myid_caso' AND numero_intervencion=$myinter");
$ret_id_intervencion=mysqli_fetch_array($resultado_id_intervencion);
$_SESSION['id_intervencion']= $ret_id_intervencion['id'];

echo $myinter.$ret['et'];

?>
