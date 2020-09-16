<?php
session_start();

$link = mysqli_connect("localhost", "root", ".google.", "safe");

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
    
    if(isset($_POST['Idusuario'])) {
        $myid_usuario = mysqli_real_escape_string($link,$_POST['Idusuario']);
    }
    
    $mygrupocibernuevo = mysqli_real_escape_string($link,$_POST['grupo_ciber_nuevo']);
    $mynombrenuevo = mysqli_real_escape_string($link,$_POST['nombre_nuevo']);
    $myapellido1nuevo = mysqli_real_escape_string($link,$_POST['apellido1_nuevo']);
    $myapellido2nuevo = mysqli_real_escape_string($link,$_POST['apellido2_nuevo']);
    $mydninuevo = mysqli_real_escape_string($link,$_POST['DNI_nuevo']);
    $mycpnuevo = mysqli_real_escape_string($link,$_POST['CP_nuevo']);
    
    if (isset($_POST["telefono_nuevo"])){
        $mytelefononuevo = mysqli_real_escape_string($link,$_POST['telefono_nuevo']);
    }
    else {
        $mytelefononuevo='NULL';
    }
    
    $myapodonuevo = mysqli_real_escape_string($link,$_POST['apodo_nuevo']);
    $errorpass=0;
    $myusuarionuevo = mysqli_real_escape_string($link,$_POST['usuario_nuevo']);
    $myusuariooriginal = mysqli_real_escape_string($link,$_POST['usuario_original']);
    
    $mypasswordoriginal = mysqli_real_escape_string($link,$_POST['password_original']);
    
    if ($myusuarionuevo == $myusuariooriginal){
        $var= $_POST["password_nuevo"];
        if (empty($var)){
            $passwordnuevo=$mypasswordoriginal;
            
        }
        else {
            $mypasswordnuevo = mysqli_real_escape_string($link,$_POST['password_nuevo']);
            $hash = "'$myusuarionuevo''$mypasswordnuevo'";
            $passwordnuevo= sha1($hash);
        }
        
        
    }
    else {
        $var= $_POST["password_nuevo"];
        if (empty($var)){
            
            echo "Se ha cambiado el nombre de usuario, DEBES cambiar la contraseña.";
            $errorpass=1;
            /*$mypasswordnuevo='1234';
             $hash = "'$myusuarionuevo''$mypasswordnuevo'";
             $passwordnuevo= sha1($hash);*/
        }
        else {
            
            $mypasswordnuevo = mysqli_real_escape_string($link,$_POST['password_nuevo']);
            $hash = "'$myusuarionuevo''$mypasswordnuevo'";
            $passwordnuevo= sha1($hash);
        }
    }
    
    $administradornuevo = mysqli_real_escape_string($link,$_POST['administrador_nuevo']);
    
    if($administradornuevo=='SI') {
        $myadministrador=2;
    }
    else {
        $myadministrador=1;
    }
    if (isset($_POST["email_nuevo"])){
        $myemailnuevo = mysqli_real_escape_string($link,$_POST['email_nuevo']);
    }
    else {
        $myemailnuevo='NULL';
    }
    
    
    
    // if (count($_FILES) >0){
    
    if(isset($_FILES["imagen"])) {
        
        if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
            $imgContenido = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
            
            $sql="UPDATE usuario set nombre='$mynombrenuevo', apellido1='$myapellido1nuevo', apellido2='$myapellido2nuevo',
            id_grupo_ciber='$mygrupocibernuevo',
            id_perfil_safe='$myadministrador',
            dni = '$mydninuevo',
            cp = '$mycpnuevo',
            telefono = '$mytelefononuevo',
            apodo = '$myapodonuevo' ,
            usuario = '$myusuarionuevo', pass = '$passwordnuevo', mail ='$myemailnuevo', fecha_alta_usuario=NOW()
            , foto='$imgContenido' WHERE id_usuario=$myid_usuario";
        }
        else
        {
            
            $sql="UPDATE usuario set nombre='$mynombrenuevo', apellido1='$myapellido1nuevo', apellido2='$myapellido2nuevo',
            id_grupo_ciber='$mygrupocibernuevo',
            id_perfil_safe='$myadministrador',
            dni = '$mydninuevo',
            cp = '$mycpnuevo',
            telefono = '$mytelefononuevo',
            apodo = '$myapodonuevo' ,
            usuario = '$myusuarionuevo', pass = '$passwordnuevo', mail ='$myemailnuevo', fecha_alta_usuario=NOW()
             WHERE id_usuario=$myid_usuario";
        }
    }
    else {
        
        $sql="UPDATE usuario set nombre='$mynombrenuevo', apellido1='$myapellido1nuevo', apellido2='$myapellido2nuevo',
            id_grupo_ciber='$mygrupocibernuevo',
            id_perfil_safe='$myadministrador',
            dni = '$mydninuevo',
            cp = '$mycpnuevo',
            telefono = '$mytelefononuevo',
            apodo = '$myapodonuevo' ,
            usuario = '$myusuarionuevo', pass = '$passwordnuevo', mail ='$myemailnuevo', fecha_alta_usuario=NOW()
             WHERE id_usuario=$myid_usuario";
    }
    
    $mymarcanuevo = mysqli_real_escape_string($link,$_POST['marca_nuevo']);
    $mymodelonuevo = mysqli_real_escape_string($link,$_POST['modelo_nuevo']);
    $mymatriculanuevo = mysqli_real_escape_string($link,$_POST['matricula_nuevo']);
    $mycolornuevo = mysqli_real_escape_string($link,$_POST['color_nuevo']);
    
    
    $errorusuario=0;
    $errorcoche=0;
    
    $resultado=mysqli_query($link, "SELECT * from usuario where id_usuario=$myid_usuario");
    $count=mysqli_num_rows($resultado);
    if($count!=0) {
        
        $result = mysqli_query($link,$sql);
        if (!$result) {
            echo  mysqli_error($link);
            $errorusuario=1;
        }
        
        
    }
    else {
        echo "Falló no devuelve ningun registro la consulta de la tabla: usuario";
        $errorusuario=1;
    }
    
    $resultado=mysqli_query($link, "SELECT * from coche where id_usuario=$myid_usuario");
    $count=mysqli_num_rows($resultado);
    if($count!=0) {
        
        $sql="UPDATE coche set marca='$mymarcanuevo', modelo='$mymodelonuevo',
            matricula='$mymatriculanuevo', color='$mycolornuevo'
            WHERE id_usuario=$myid_usuario";
        //echo "sql $sql";
        $result = mysqli_query($link,$sql);
        if (!$result) {
            echo  mysqli_error($link);
            $errorcoche=1;
        }
        else
        {
            $errorusuario=0;
            
        }
        
    }
    else {
        echo "Falló no devuelve ningun registro la consulta de la tabla: coche";
        $errorcoche=1;
    }
}

if($errorusuario!=1 AND $errorcoche!=1 AND $errorpass !=1) {
    $_SESSION['respuesta']=2;
    echo 2;
}



mysqli_close($link);
?>
