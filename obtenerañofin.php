
 
 <?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$category=$_POST["category"];



// comprobar la conexi�n
if (mysqli_connect_errno()) {
    printf("Fall� la conexi�n: %s\n", mysqli_connect_error());
    exit();
}
if($category!=0) {

    for($i=$category;$i<=2020;$i++) {
        echo "<option value=$i>".$i."</option>";   
        }
}

?>
 