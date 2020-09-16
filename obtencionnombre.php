<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas

$category=$_POST["category"];

$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");


// comprobar la conexi�n
if (mysqli_connect_errno()) {
    printf("Fall� la conexi�n: %s\n", mysqli_connect_error());
    exit();
}

if($category!='') {


$sql="SELECT nombre as nombre FROM caso WHERE id_caso=$category";
$resultado = mysqli_query($link, $sql);
$ret=mysqli_fetch_array($resultado);
echo $ret['nombre'];
}
else {
    echo "";
}




?>