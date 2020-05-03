<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");

if (mysqli_connect_errno()) {
    printf("Fallo la conexion: %s\n", mysqli_connect_error());
    exit();
}

if(isset($_GET["mod"])) {
    $mod= $_GET["mod"];
}
else {
    if(isset($_POST["mod"])) {
        $mod= $_POST["mod"];
    }
    else {
        $mod= 0;
    }
}


if($mod!="1") 
{
  
    
    $myidusuario = mysqli_real_escape_string($link,$_POST['Idusuario']);
    
    $myusername = strtolower(mysqli_real_escape_string($link,$_POST['usuario']));
    $mypassword = mysqli_real_escape_string($link,$_POST['password']);
    //generacion de pass
    $hash = "'$myusername''$mypassword'";
    $password= sha1($hash);
    
    $myadministrador = mysqli_real_escape_string($link,$_POST['administrador']);
    
    if($myadministrador==SI) {
        $myadministradores=2;
    }
    else {
        $myadministradores=1;
    }
    $myescala = mysqli_real_escape_string($link,$_POST['escala']);
    $mycategoria = mysqli_real_escape_string($link,$_POST['categoria']);
    $mygrupo = mysqli_real_escape_string($link,$_POST['grupo_ciber']);
    
   
    
    $myapellido1 = strtolower(mysqli_real_escape_string($link,$_POST['apellido1']));
    $myapellido2 = strtolower(mysqli_real_escape_string($link,$_POST['apellido2'])); 
    $mydni = mysqli_real_escape_string($link,$_POST['DNI']);
    $mycp = mysqli_real_escape_string($link,$_POST['CP']);  
    $mytelefono = mysqli_real_escape_string($link,$_POST['telefono']); 
    $myapodo = strtolower(mysqli_real_escape_string($link,$_POST['apodo']));  
    $mynombre = strtolower(mysqli_real_escape_string($link,$_POST['nombre']));  
    $myemail = strtolower(mysqli_real_escape_string($link,$_POST['email'])); 
    
    //if (isset($_POST['imagen'])){
    if(isset($_FILES["imagen"])) {
            if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
                $imgContenido = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
                
           }
       
    }
    else {
        $imgContenido=NULL;
       
    }
    
    
    
    $mymarca = mysqli_real_escape_string($link,$_POST['marca']);
    $mymodelo = strtolower(mysqli_real_escape_string($link,$_POST['modelo']));
    $mymatricula = strtolower(mysqli_real_escape_string($link,$_POST['matricula']));
    $mycolor = strtolower(mysqli_real_escape_string($link,$_POST['color']));
}

$errorusuario=0;
$errorcoche=0;
			


$sql = "SELECT id_usuario FROM usuario WHERE usuario = '$myusername'";
$result = mysqli_query($link,$sql);

if (!$result) {
    echo "Fallo en la consulta de la tabla: usuario";
    $errorusuario=0;
    }
    else 
    {
        $count = mysqli_num_rows($result);
        
        if(isset($_POST['admin'])) {
            if($count == 1) {
                echo "Usuario ADMIN ya existe";
                $errorusuario=1;
            }
            else {
                $sql = "INSERT INTO usuario (usuario, pass, fecha_alta_usuario, id_perfil_safe) VALUES ('$myusername','$password', NOW(), '2')";
                $result = mysqli_query($link,$sql);
                echo "Usuario ADMINISTRADOR creado";
                $errorusuario=0;
            }
            
        }
        else {
            if($count == 1) {
                echo " - Usuario ya existe";
                $errorusuario=1;
            }
            else {
                
                $sql = "INSERT INTO usuario (id_usuario, id_escala, id_categoria, id_grupo_ciber, id_perfil_safe, nombre, apellido1, apellido2, dni, cp, telefono, apodo, usuario, pass, fecha_alta_usuario, mail, foto) VALUES ('$myidusuario', '$myescala', '$mycategoria', '$mygrupo', '$myadministradores' ,'$mynombre','$myapellido1', '$myapellido2','$mydni', '$mycp','$mytelefono', '$myapodo', '$myusername',  '$password', NOW(), '$myemail', '$imgContenido')";
                $result = mysqli_query($link,$sql);
                if (!$result) {
                    echo mysqli_error($link); 
                    $errorusuario=1;
         
            
                }
                else 
                {
                    $sqlcoche = "INSERT INTO coche (id_usuario, marca, modelo, matricula, color) VALUES ('$myidusuario', '$mymarca', '$mymodelo', '$mymatricula', '$mycolor')";
                    $resultcoche = mysqli_query($link,$sqlcoche);
                    if (!$resultcoche) {
            
            
                        echo mysqli_error($link); 
                        $errorcoche=1;
            
                    }
                    else
                    {
                        //usuario creado correctamente
                        
                        $errorusuario=0;
                        $errorcoche=0;
                    }
                }
            }
            
            
            
        }
}

if($errorusuario!=1 AND $errorcoche!=1) {
    $_SESSION['respuesta']=1;
    echo 1;
}
mysqli_close($link);
?>

