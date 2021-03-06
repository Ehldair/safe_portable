<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");

// comprobar la conexiï¿½n
if (mysqli_connect_errno()) {
    printf("Fallo la conexion: %s\n", mysqli_connect_error());
    exit();
}
if(isset($_SESSION['id_u'])) {
    
    $sql = "SELECT id_caso,c.año, c.numero,  c.nombre, c.descripcion FROM
    caso c inner join estado_caso e ON c.id_estado_caso=e.id_estado_caso
    WHERE e.estado='Abierto' AND id_caso!=1 ORDER BY año desc, numero desc";
    
    $resultado=mysqli_query($link, $sql);
    $count=mysqli_num_rows($resultado);
    
    
    ?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
 <title>Casos</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
	
	
	
			
	<!-- Alonso -->
		<script src="//code.jquery.com/jquery-latest.js"></script>
		<script src="miscript.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script src="js/jquery-3.4.1.js"></script>
		<script>
		function cabecera(){
			
			$('#cabecera').load('cabecera.php');                
			
		};
		</script>

</head>

<body class="is-preload" onload="cabecera();">

	<div id="cabecera">
    
    </div>
	
	
	<div id="page-wrapper">

	<!-- Main -->
		<section id="main" class="container">
			<header>
				<h2> Casos</h2>
				<p>Casos abiertos</p>
			</header>			
			<div class="row">
						<div class="col-12">
							<!-- Table -->
								<section class="box">
									<div class="table-wrapper">
										<h3>Listado casos abiertos</h3>

<?php 

if($count!=0) {

    echo "
								<table>
									<thead>
										<tr>
											<th>Caso</th>
                                            <th>Operacion</th>
											<th>Descripcion</th>
                                            <th>Portada</th>
                                            
										</tr>
									</thead>
									<tbody>
			
	";


    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $id_caso= $line['id_caso'];
        $ano= $line['año'];
        $numero= $line['numero'];
        $nombre= $line['nombre'];
        $descripcion= $line['descripcion'];
        
        
        echo "<tr>
        					<td style='text-align: left'>
                            	<form>";
        
        $id_caso=base64_encode($id_caso);
        $ano2=substr($ano,2,2);
       
        ?>
    						<a href="asunto.php?id_caso=<?php echo $id_caso;?>" style="color: black" >
    							<b><?php echo $numero.'_'.$ano2;?></b>
    						</a>
    
                    
        <?php 
        echo "</td>";
        echo "<td align='left'>$nombre</td>";
        echo "<td align='left'>$descripcion</td>";
        echo "<td align='left'>";
        
        ?>
    						<a href="generaportada.php?id_caso=<?php echo $id_caso;?>" target="_blank" >
    							<img src="img/iconopdf.png" alt="Enlace" width=20 height=20/>
    						</a>
    
                    
        <?php 
        echo "</td>";
        echo "</form>";
        echo "</tr>";
    }//while

echo "						<tbody>
					</table>
			</section>";


}  // if
else {
    echo "<br><b>";
    echo "NO EXISTEN CASOS";
    echo "</b>";
}

}
?>

			</div>
	
		
	</section>
	</div>
	</div>
	</section>	
	</div>
</body>

<!-- Pelayo -->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/jquery.dropotron.min.js"></script>
		<script src="assets/js/jquery.scrollex.min.js"></script>
		<script src="assets/js/browser.min.js"></script>
		<script src="assets/js/breakpoints.min.js"></script>
		<script src="assets/js/util.js"></script>
		<script src="assets/js/main.js"></script>
		
</html>