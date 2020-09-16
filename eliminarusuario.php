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
    
    $errorusuario=0;
    $errorcoche=0;
    
    $resultado_Ev_Reg=mysqli_query($link, "SELECT * from evidencia_registro where id_usuario=$myid_usuario");
    $count_Ev_Reg=mysqli_num_rows($resultado_Ev_Reg);
    if($count_Ev_Reg==0) 
    {
    
        $resultado=mysqli_query($link, "SELECT * from usuario where id_usuario=$myid_usuario");
        $count=mysqli_num_rows($resultado);
        if($count!=0) {
            
            $resultadoco=mysqli_query($link, "SELECT * from coche where id_usuario=$myid_usuario");
            $countco=mysqli_num_rows($resultadoco);
            if($countco!=0) {
                
                $sqlco="DELETE FROM coche where id_usuario=$myid_usuario";
                
                $resultco = mysqli_query($link,$sqlco);
                if (!$resultco) {
                    echo  mysqli_error($link);
                    $errorcoche=1;
                }
                else
                {
                    $errorcoche=0;
                }
            }
            
            $sql="DELETE FROM usuario where id_usuario=$myid_usuario";
            
            $result = mysqli_query($link,$sql);
            if (!$result) {
                  echo  mysqli_error($link);
                  $errorusuario=1;
            }
            else
            {
                $errorusuario=0;           
            }
        
        }
            
        else {
            echo "Falló no devuelve ningun registro la consulta de la tabla: usuario";
            $errorusuario=1;
        }
    }
    else {
            echo "NO SE ELIMINA EL USUARIO. El usuario tiene registros en la tabla: evidencia_registros";
            $errorusuario=1;
    }
        
}

if($errorusuario!=1 AND $errorcoche!=1) {
    $_SESSION['respuesta']=3;
    echo 3;
}



mysqli_close($link);
?>
