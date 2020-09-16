<?php
session_start();
$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
$myid_comisaria=mysqli_real_escape_string($link,$_POST["category"]);

$sql="Select * from grupo_investigacion where id_comisaria=$myid_comisaria";
$result=mysqli_query($link, $sql);
$count=mysqli_num_rows($result);
if($count!=0) {
    $_SESSION['respuesta']=4;
}
else {
    
    $sql="delete FROM comisaria WHERE id_comisaria=$myid_comisaria";
    mysqli_query($link, $sql);
    
    $_SESSION['respuesta']=3;
}

?>