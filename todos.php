<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas

$link = mysqli_connect("localhost", "root", ".google.", "safe");

// comprobar la conexi�n
if (mysqli_connect_errno()) {
    printf("Fall� la conexi�n: %s\n", mysqli_connect_error());
    exit();
}

$resultado = mysqli_query($link, "SELECT c.id_estado_caso, c.id_caso, c.año,c.numero,  c.nombre, c.descripcion FROM caso c inner join estado_caso e ON c.id_estado_caso=e.id_estado_caso AND id_caso!=1 ORDER BY fecha_alta_caso DESC");
$count=mysqli_num_rows($resultado);

?>


<!DOCTYPE html>
<html lang="es-ES">
<head>
 <title>Nuevo Asunto</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
	
	<!-- Pelayo -->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/jquery.dropotron.min.js"></script>
		<script src="assets/js/jquery.scrollex.min.js"></script>
		<script src="assets/js/browser.min.js"></script>
		<script src="assets/js/breakpoints.min.js"></script>
		<script src="assets/js/util.js"></script>
		<script src="assets/js/main.js"></script>
			
	<!-- Alonso -->
		<script src="//code.jquery.com/jquery-latest.js"></script>
		<script src="miscript.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script src="js/jquery-3.4.1.js"></script>

</head>

<body class="is-preload">
	<div id="page-wrapper">

	<!-- Main -->
		<section id="main" class="container">
			<header>
				<h2>Casos</h2>
				<p>Listado de todos los casos</p>
			</header>
			
			<div class="row">
						<div class="col-12">

							<!-- Table -->
								<section class="box">
									
									<div class="table-wrapper">
							
<?php
if($count!=0) {							
	echo "
								<table>
									<thead>
										<tr>
											<th>Caso</th>
											<th>Operación</th>
											<th>Descripción</th>
										</tr>
									</thead>
									<tbody>
			
	";		

	$contador=0;

	while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
    	foreach ($line as $col_value) {
        	if($contador==0) {
            	if($col_value==1) {
                	$rojo=1; 
               	 	$azul=0;
            	}
            	else {
                	$azul=1;
                	$rojo=0;
            	}
            	$contador++;
        	}
        	else {
            	if($contador==1) {
                	$id_caso=$col_value;
                	$contador++;
            	}
            	else {
                	if($contador==2) {
                    	$año=substr($col_value,2,2);
                    	$contador++;
                	}
                	else
                    	if($contador==3) {
                        	if($rojo==1) {
 echo "                       
                        		<tr>	                   
                                    <td style='text-align: left'>";
                                    $id_caso=base64_encode($id_caso);
?>
						<a href="asunto.php?id_caso=<?php echo $id_caso;?>" style='color:black;'>
						<?php echo $col_value;?>_<?php echo $año;?>
						</a> 
						
<?php 
               
               
                				$contador++;
                        	}
                        	else {
                            	if($azul==1) {
echo "                       
                        			<tr>
                                        <td style='text-align: left'>";
                                        $id_caso=base64_encode($id_caso);
?>
									<a href="asunto.php?id_caso=<?php echo $id_caso;?>" style='color:red;'>
									<?php echo $col_value;?>_<?php echo $año;?>
									</a> 
						
<?php 
               
                			   		
               
                					$contador++;
                            	}
                        }
                    }
                    else {
                        if ($contador<5) {
                            echo "                       
                        	
								<td style='text-align: left'> ";
                            		echo $col_value;
                            		echo "</td>";
                            		$contador++;
                        }
                        else {
                            echo "                       
                        	
								<td style='text-align: left'> ";
                            	echo $col_value;
                            	echo "</td>";
                            	$contador=0;
                        }
                    }
                }
            }
 echo "     </form>	";
        }
    echo "						</tr>";
}

echo "						<tbody> 
					</table>
			</section>";
}
else {
    echo "<br><b>";
    echo "NO EXISTEN CASOS";
    echo "</b>";
}



?>

	
			</div>
		</div>
		
	</section>	
	</div>
</body>
</html>
