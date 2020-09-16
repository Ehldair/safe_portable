
 
 <?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$category=$_POST["category"];


$link = mysqli_connect("localhost", "root", ".google.", "safe");

// comprobar la conexi�n
if (mysqli_connect_errno()) {
    printf("Fall� la conexi�n: %s\n", mysqli_connect_error());
    exit();
}

    $contador=0;
    $resultado = mysqli_query($link, "SELECT id_comisaria,nombre_comisaria FROM comisaria WHERE id_provincia='$category'");
    echo "<option value=''>Seleccione una comisaria</option>";
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        foreach ($line as $col_value) {
            if($contador==0) {
                echo "<option value=$col_value>";
                $contador++;
            }
            else {
                echo "$col_value</option>";
                $contador=0;
            }
            
            
        }
    }

?>
 