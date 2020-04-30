<?php

session_start();


#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
$mymod=$_SESSION['mod'];
$myid_intervencion=$_SESSION['id_intervencion'];
$myid_caso = $_SESSION['id_caso'];
$mydireccion = mysqli_real_escape_string($link,$_POST['direccion']);
$mytipo = mysqli_real_escape_string($link,$_POST['tipo']);
$mydescripcion = mysqli_real_escape_string($link,$_POST['descripcion']);


$mysujeto= mysqli_real_escape_string($link,$_POST['sujeto']);
$sql= mysqli_query($link,"SELECT * FROM intervencion WHERE id_caso=$myid_caso");
$count=mysqli_num_rows($sql);
$count= $count+1;
if($mymod==3) {
    $sql= "UPDATE intervencion SET id_sujeto_activo=$mysujeto, id_tipo_intervencion=$mytipo, direccion='$mydireccion', descripcion='$mydescripcion' WHERE id_intervencion=$myid_intervencion";
    echo $sql;
    mysqli_query($link,$sql);
    $_SESSION['respuesta']=1;
    echo '<script type="text/javascript">
	location.href = "detalle_intervencion.php";
    </script>';
}
else {
$sql= "INSERT INTO intervencion (id_caso,id_tipo_intervencion,id_sujeto_activo,numero_intervencion,direccion,descripcion) VALUES ($myid_caso,$mytipo,$mysujeto,$count,'$mydireccion','$mydescripcion')";
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


mysqli_close($link);
?>