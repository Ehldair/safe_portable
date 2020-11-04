<?php

session_start();


#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
$mymod=$_SESSION['mod'];
if(isset($_SESSION['id_intervencion'])) {
    $myid_intervencion=$_SESSION['id_intervencion'];
}
$myid_caso = $_SESSION['id_caso'];
$mydireccion = mysqli_real_escape_string($link,$_POST['direccion']);
$mytipo = mysqli_real_escape_string($link,$_POST['tipo']);
$mydescripcion = mysqli_real_escape_string($link,$_POST['descripcion']);
$myfecha=$_POST['fecha'];




$mysujeto= mysqli_real_escape_string($link,$_POST['sujeto']);
$sql= mysqli_query($link,"SELECT * FROM intervencion WHERE id_caso=$myid_caso");
if($mymod==3) {
    $sql= "UPDATE intervencion SET id_sujeto_activo=$mysujeto, id_tipo_intervencion=$mytipo, direccion='$mydireccion', descripcion='$mydescripcion', fecha_alta_intervencion='$myfecha' WHERE id_intervencion=$myid_intervencion";
    mysqli_query($link,$sql);
    $_SESSION['respuesta']=1;
    echo '<script type="text/javascript">
	location.href = "detalle_intervencion.php";
    </script>';
}
else {
    $mynumero=mysqli_real_escape_string($link,$_POST['numero_envio']);
    $comprobacion_intervencion=mysqli_query($link,"Select * From INTERVENCION where id_caso=$myid_caso AND numero_intervencion=$mynumero");
    $count=mysqli_num_rows($comprobacion_intervencion);
    if($count==0) {
        $sql= "INSERT INTO intervencion (id_caso,id_tipo_intervencion,id_sujeto_activo,numero_intervencion,direccion,descripcion,fecha_alta_intervencion) VALUES ($myid_caso,$mytipo,$mysujeto,$mynumero,'$mydireccion','$mydescripcion','$myfecha')";
        mysqli_query($link,$sql);
        if($mymod==1 or $mymod==0) {
            $_SESSION['respuesta']=4;
            echo '<script type="text/javascript">
	       location.href = "asunto.php";
            </script>';
        }
        else {
            if($mymod==2) {
                $_SESSION['respuesta']=1;
                echo '<script type="text/javascript">
	           location.href = "listado_intervenciones.php";
                </script>';
            }
        }
    }
    else {
        $_SESSION['respuesta']=9;
        echo '<script type="text/javascript">
    	location.href = "asunto.php";
        </script>';
    }
}


mysqli_close($link);
?>