
 
 <?php

session_start();



$informe=$_POST["informes"];

$link = mysqli_connect("localhost", "root", ".google.", "safe");

// comprobar la conexi�n
if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

//Intervencion
if ($informe == "IGIACB" || $informe == "EGI")
{
    $sql = "select i.id_caso as caso_i, i.numero_intervencion as num_i,
    t.nombre as nom_tipo, s.nombre, s.apellido1, s.apellido2, i.direccion, i.descripcion
    FROM intervencion i
    INNER JOIN tipo_intervencion t ON t.id_tipo_intervencion=i.id_tipo_intervencion
    INNER JOIN sujeto_activo s ON s.id_sujeto_activo=i.id_sujeto_activo
    INNER JOIN caso c ON c.id_caso=i.id_caso
    ORDER BY i.id_caso, i.numero_intervencion";
}
if ($informe == "IGC" || $informe == "IGACB" || $informe == "EGC")
{
    $sql = "select c.id_caso as id_caso, c.numero as numero_caso, c.año as ano_caso, c.nombre as nombre_caso,
    d.numero as numero_diligencias, d.año as año_diligencias, d.fecha as fecha_diligencias,
    j.nombre as nombre_juzgado, j.numero as numero_juzgado    
    FROM caso c
    INNER JOIN diligencias d ON d.id_diligencias=c.id_diligencias
    INNER JOIN juzgado j ON j.id_juzgado=d.id_juzgado
    ORDER BY c.id_caso";
}
    $contador=0;
    $resultado = mysqli_query($link, $sql);

    
    
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        
        
        if ($informe == "IGIACB" || $informe == "EGI")
        {
            $caso=$line['caso_i'];
            $numero=$line['num_i'];
            $tipo = $line['nom_tipo'];
            $sujeto= $line['nombre'];
            $ap1= $line['apellido1'];
            $ap2= $line['apellido2'];
            $direccion= $line['direccion'];
            $descripcion= $line['descripcion'];
            
            echo "<option value='$caso-$numero'>$informe - $caso - $numero # $tipo - $sujeto $ap1 $ap2 [$direccion]</option>";
        }
        if ($informe == "IGC" || $informe == "IGACB" || $informe == "EGC")
        {
            
            
            $id_caso=$line['id_caso'];
            $numero_caso=$line['numero_caso'];
            $año_caso=$line['ano_caso'];
            $nombre_caso=$line['nombre_caso'];
            
            $numero_diligencias=$line['numero_diligencias'];
            $año_diligencias=$line['año_diligencias'];
            $fecha_diligencias=$line['fecha_diligencias'];
            $nombre_juzgado=$line['nombre_juzgado'];
            $numero_juzgado=$line['numero_juzgado'];
            
            echo "<option value='$id_caso'>$informe - Caso [$id_caso - $año_caso - $nombre_caso] Diligencias [$numero_diligencias/$año_diligencias] Juzgado [$nombre_juzgado numero $numero_juzgado] </option>";
        }
    } //while
    
    

?>
 