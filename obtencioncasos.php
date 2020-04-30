<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$myaño=0;
$myaño=$_GET['año'];


$link = mysqli_connect("localhost", "root", ".google.", "safe");

// comprobar la conexi�n
if (mysqli_connect_errno()) {
    printf("Fall� la conexi�n: %s\n", mysqli_connect_error());
    exit();
}
$sql = "SELECT numero from caso where año='$myaño' order by numero desc limit 0,1";
$resultado=mysqli_query($link, $sql);
$count= mysqli_num_rows($resultado);
if($count!=0) {
    $ret = mysqli_fetch_array($resultado);
    $numero=$ret['numero']+1;
}
else {
    $numero=1;
}
echo $numero;
?>
