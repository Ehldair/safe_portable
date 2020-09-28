<?php
session_start();
$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
$myid_caso=$_SESSION['id_caso'];
if(isset($_GET['mod'])) {
    $mod=$_GET['mod'];
}
if(empty($_GET['mod'])){
    $mod=0;
}


if($mod==0) {
    $sql="Select * from viajes where id_caso=$myid_caso";
    $result=mysqli_query($link, $sql);
    $count=mysqli_num_rows($result);
    if($count!=0) {
        $_SESSION['respuesta']=5;
    }
    else {
    
    $sql="delete FROM caso WHERE id_caso=$myid_caso";
    mysqli_query($link, $sql);
    
    $_SESSION['respuesta']=2;
    }
}
else {
    $sql="Select * from evidencia where id_caso=$myid_caso";
    $result=mysqli_query($link, $sql);
    $count=mysqli_num_rows($result);
    if($count!=0) {
        while ($line_evidencias = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id_evidencia=$line_evidencias['id_evidencia'];
            $sql_hash="Select * from hash where id_evidencia=$id_evidencia";
            $result_hash=mysqli_query($link, $sql_hash);
            $count_hash=mysqli_num_rows($result_hash);
            if($count_hash!=0) {                
                $sql_del_hash="delete FROM hash WHERE id_evidencia=$id_evidencia";
                mysqli_query($link, $sql_del_hash);
            }
            $sql_evidencia_registro="Select * from evidencia_registro where id_evidencia=$id_evidencia";
            $result_evidencia_registro=mysqli_query($link, $sql_evidencia_registro);
            $count_evidencia_registro=mysqli_num_rows($result_evidencia_registro);
            if($count_evidencia_registro!=0) {
                $sql_del_evidencia_registro="delete FROM evidencia_registro WHERE id_evidencia=$id_evidencia";
                mysqli_query($link, $sql_del_evidencia_registro);
            }
            $sql_del_evidencia="delete FROM evidencia WHERE id_evidencia=$id_evidencia";
            mysqli_query($link, $sql_del_evidencia);
        }
    }
    $sql="Select * from intervencion where id_caso=$myid_caso";
    $result=mysqli_query($link, $sql);
    $count=mysqli_num_rows($result);
    if($count!=0) {
        $sql_del_intervencion="delete FROM intervencion WHERE id_caso=$myid_caso";
        mysqli_query($link, $sql_del_intervencion);
    }
    $sql="Select * from sujeto_activo where id_caso=$myid_caso";
    $result=mysqli_query($link, $sql);
    $count=mysqli_num_rows($result);
    if($count!=0) {
        $sql_del_sujeto="delete FROM sujeto_activo WHERE id_caso=$myid_caso";
        mysqli_query($link, $sql_del_sujeto);
        echo "Sujeto: ".$sql_del_sujeto;
    }
    $sql="delete FROM caso WHERE id_caso=$myid_caso";
    mysqli_query($link, $sql);
    $_SESSION['respuesta']=2;
}
?>