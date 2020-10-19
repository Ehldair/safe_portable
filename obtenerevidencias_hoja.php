<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$myid_caso=$_SESSION['id_caso'];
$category=$_POST["category"];




$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");

// comprobar la conexi�n
if (mysqli_connect_errno()) {
    printf("Fall� la conexi�n: %s\n", mysqli_connect_error());
    exit();
}
$i=0;
$resultado=mysqli_query($link, "SELECT id_evidencia,nombre,numero_evidencia FROM evidencia where id_caso='$myid_caso' AND id_intervencion='$category'");
while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
    $id_evidencia=$line['id_evidencia'];
    $nombre=$line['nombre'];
    $numero_evidencia=$line['numero_evidencia'];
    echo "<input type='checkbox' name='lista_ev[]' id=$i value=$id_evidencia>";
    echo "<label for=$i>$nombre$numero_evidencia</label>";
    $i++;
}


?>
