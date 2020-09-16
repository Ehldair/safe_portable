<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$myid_caso=$_SESSION['id_caso'];
$category=$_POST["category"];




$link = mysqli_connect("localhost", "root", ".google.", "safe");

// comprobar la conexi�n
if (mysqli_connect_errno()) {
    printf("Fall� la conexi�n: %s\n", mysqli_connect_error());
    exit();
}
$contador=0;
$resultado_id_intervencion=mysqli_query($link, "SELECT id_intervencion as id FROM intervencion where id_caso='$myid_caso' AND numero_intervencion=$category");
$ret_id_intervencion=mysqli_fetch_array($resultado_id_intervencion);
$resultado=mysqli_query($link, "SELECT id_evidencia,nombre,numero_evidencia as nom FROM evidencia where id_caso='$myid_caso' AND id_intervencion=$ret_id_intervencion[id]  AND relacionado_con IS NULL");
while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
    foreach ($line as $col_value) {
        if($contador==0) {
            echo "<option value=$col_value>";
            $contador++;
        }
        else {
            if($contador==1){
                echo $col_value;
                $contador++;
            }
            else {
                if($contador==2) {
                    echo $col_value."</option>";
                    $contador=0;
                }
            }
        }
    }
}

?>
