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
$mycomisaria=mysqli_real_escape_string($link,$_POST['comisaria']);
$mygrupo=mysqli_real_escape_string($link,$_POST['grupo']);
$mymovil=mysqli_real_escape_string($link,$_POST['movil']);
$mytelefono=mysqli_real_escape_string($link,$_POST['telefono']);
$myresponsable=mysqli_real_escape_string($link,$_POST['responsable']);

$sql= mysqli_query($link, "INSERT INTO grupo_investigacion (id_comisaria,nombre_grupo,movil,telefono,responsable) VALUES ($mycomisaria, '$mygrupo', '$mymovil', '$mytelefono', '$myresponsable')");
$_SESSION['respuesta']=1;
echo 1;

}
else {
    $mygrupo=mysqli_real_escape_string($link,$_POST['nombre_grupo']);
    $myid_grupo=mysqli_real_escape_string($link,$_POST['id_grupo']);
    $mymovil=mysqli_real_escape_string($link,$_POST['movil']);
    $mytelefono=mysqli_real_escape_string($link,$_POST['telefono']);
    $myresponsable=mysqli_real_escape_string($link,$_POST['responsable']);
    $myid_grupo=$_SESSION['id_grupo'];
    $sql= mysqli_query($link, "UPDATE grupo_investigacion set nombre_grupo='$mygrupo', movil='$mymovil',telefono= '$mytelefono', responsable='$myresponsable' where id_grupo_investigacion=$myid_grupo");
    $_SESSION['respuesta']=2;
    echo "<script type='text/javascript'>
    location.href = 'gestion_grupo.php';
    </script>";
    
}

mysqli_close($link);
?>


