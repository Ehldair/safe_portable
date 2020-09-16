<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

if(isset($_POST['numero'])) {
    $numero=$_POST["numero"];
}
else {
    $numero=0;
}



    $_SESSION['patron']=$_SESSION['patron'].$numero."-";


echo $_SESSION['patron'];




mysqli_close($link);

?>