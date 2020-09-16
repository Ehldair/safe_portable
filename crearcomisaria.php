<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
if(isset($_GET['mod'])){
    $mod=1;
}
else {
    $mod=0;
}
if($mod!=1) {
$myca=mysqli_real_escape_string($link,$_POST['ca']);
$myprovincia=mysqli_real_escape_string($link,$_POST['provincia']);
$mycomisaria=mysqli_real_escape_string($link,$_POST['comisaria']);

$sql= mysqli_query($link, "INSERT INTO comisaria (id_ca,id_provincia,nombre_comisaria) VALUES ($myca, $myprovincia, '$mycomisaria')");
$_SESSION['respuesta']=1;
echo 1;
}
else {
    $myca=mysqli_real_escape_string($link,$_POST['ca']);
    $myprovincia=mysqli_real_escape_string($link,$_POST['provincia']);
    $mycomisaria=mysqli_real_escape_string($link,$_POST['comisaria']);
    $myid_comisaria=$_SESSION['id_comisaria'];
    $sql= mysqli_query($link, "UPDATE comisaria set nombre_comisaria='$mycomisaria', id_ca=$myca, id_provincia= $myprovincia where id_comisaria=$myid_comisaria");
    $_SESSION['respuesta']=2;
    echo "<script type='text/javascript'>
    location.href = 'gestion_comisaria.php';
    </script>";
    
}

mysqli_close($link);
?>


