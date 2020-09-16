<?php
session_start();


$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Fall� la conexi�n: %s\n", mysqli_connect_error());
    exit();
}

$myusername = strtolower(mysqli_real_escape_string($link,$_POST['nombre']));
$mypassword = mysqli_real_escape_string($link,$_POST['password']);

$hash = "'$myusername''$mypassword'";
$password= sha1($hash);
 

$sql = "SELECT id_perfil_safe as perfil,id_usuario as id_u FROM usuario WHERE usuario = '$myusername' and pass = '$password'";
$result = mysqli_query($link,$sql);
//$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

$count = mysqli_num_rows($result);
$ret = mysqli_fetch_array($result); 
$admin=$ret['perfil'];
   
$_SESSION['id_u']=$ret['id_u'];

if($count == 1) {
    if ($admin==1) {
        $_SESSION['admin'] = 1;
    }
    else {
        $_SESSION['admin'] = 2;
    }
    header("Location: inicio.php");
}
else {
    echo '<script type="text/javascript">
        alert("Usuario Invalido");
	    location.href ="login.php";
     </script>';
    
}

mysqli_close($link);
?>

