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
    $myjurisdiccion=$_POST['jurisdiccion'];
    $mynombre = $_POST['nombre'];
    $mynumero= $_POST['numero'];
    
    
    $sql= "INSERT INTO juzgado (jurisdiccion, nombre, numero) VALUES ('$myjurisdiccion','$mynombre',$mynumero)";
    mysqli_query($link,$sql);
    
    $_SESSION['respuesta']=1;
    echo 1;  
}else {
    $myjurisdiccion=$_POST['jurisdiccion'];
    $mynombre = $_POST['nombre'];
    $mynumero= $_POST['numero'];
    $myid_juzgado=$_SESSION['id_juzgado'];
    $sql= mysqli_query($link, "UPDATE juzgado set jurisdiccion='$myjurisdiccion', nombre='$mynombre',numero= $mynumero where id_juzgado=$myid_juzgado");
    $_SESSION['respuesta']=2;
    echo "<script type='text/javascript'>
    location.href = 'gestion_juzgado.php';
    </script>";
    
}

mysqli_close($link);
?>