
 
 <?php
session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$category=$_POST["category"];


$link = mysqli_connect("localhost", "root", ".google.", "safe");

// comprobar la conexi�n
if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

    $contador=0;
    if($category!=0) {
    $resultado = mysqli_query($link, "SELECT vf.id_usuario, u.apodo FROM viajes_funcionario vf 
        inner join usuario u on u.id_usuario=vf.id_usuario
        WHERE id_viajes=$category");
    $resultado_quitar=mysqli_query($link, "select u.apodo, dias from compensacion c
    inner join usuario u on u.id_usuario=c.id_usuario
    where id_viajes=$category");
    }
    else {
        $resultado = mysqli_query($link, "SELECT id_usuario,    apodo FROM usuario");
    }
    echo "<option value=''>Seleccione un usuario</option>";
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $id_usuario=$line['id_usuario'];
        $apodo=$line['apodo'];
        $sql="Select dias from compensacion c
        inner join usuario u on u.id_usuario=c.id_usuario
        where id_viajes=$category and c.id_usuario=$id_usuario"; 
        $result=mysqli_query($link, $sql);
        $count=mysqli_num_rows($result);
        if($count==0) {
            echo "<option value=$id_usuario>$apodo</option>";
        }
                
    }

?>
 