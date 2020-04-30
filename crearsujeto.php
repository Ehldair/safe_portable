<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

$myid_su=$_SESSION['id_su'];
$mymod=$_SESSION['mod'];

$myid_caso = $_SESSION['id_caso'];
$myapellido1=mysqli_real_escape_string($link,$_POST['apellido1']);
$myapellido2=mysqli_real_escape_string($link,$_POST['apellido2']);
$mynombre=mysqli_real_escape_string($link,$_POST['nombre']);
if($mymod==0) {
    $sql= "INSERT INTO sujeto_activo (id_caso, nombre, apellido1, apellido2) VALUES ($myid_caso,'$mynombre','$myapellido1', '$myapellido2')";
    mysqli_query($link,$sql);
    $_SESSION['respuesta']=3;
    echo '<script type="text/javascript">
	location.href = "asunto.php";
    </script>';
}
else {
    if($mymod==1) {
        $sql= "UPDATE sujeto_activo SET nombre='$mynombre', apellido1='$myapellido1',  apellido2='$myapellido2' WHERE id_sujeto_activo=$myid_su";
        mysqli_query($link,$sql);
        $_SESSION['respuesta']=1;
        echo '<script type="text/javascript">
	   location.href = "detalle_sujeto.php";
        </script>';
    }
}
 


mysqli_close($link);
?>