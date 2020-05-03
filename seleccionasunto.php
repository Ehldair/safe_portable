<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");

// comprobar la conexi�n
if (mysqli_connect_errno()) {
    printf("Fall� la conexi�n: %s\n", mysqli_connect_error());
    exit();
}


    
    $sql = "SELECT id_caso,c.año, c.numero,  c.nombre, c.descripcion 
    FROM caso c inner join estado_caso e ON c.id_estado_caso=e.id_estado_caso 
    WHERE e.estado='Abierto' AND id_caso!=1 ORDER BY fecha_alta_caso DESC";
    $resultado=mysqli_query($link, $sql);
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
					<p>Casos grabados</p>
				</header>
			
				<div class="row">
							<div class="col-12">
							Selecciona caso a subir
								<!-- Table -->
									<section class="box">
									
										<div class="table-wrapper">

	<?php 

    if($count!=0) {
    ?>

								<table>
									<thead>
										<tr>
											<th>Caso</th>
											<th>Operación</th>
											<th>Descripción</th>
										</tr>
									</thead>
									<tbody>
			
	<?php

    $contador=0;

    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        foreach ($line as $col_value) {
            if ($contador==0) {
                $id_caso=$col_value;
                $contador++;
              }
               else {
                   if($contador==1) {
                        $año=substr($col_value,2,2); 
                        $contador++;
                   }
                    else {
                        if($contador==2) {
                            echo "
    				        <tr>	
    					   <td style='text-align: left'>";
                            $id_caso=base64_encode($id_caso);
        ?>
    						<a href="subirdatos.php?id_caso=<?php echo $id_caso;?>" style="color: black" >
    							<b><?php echo $col_value;?>_<?php echo $año;?></b>
    						</a>
    
                    
        <?php 
                   
                    $contador++;
                        }
                        else {
                 
                            if ($contador<4) {
                                echo "<td align='left'>";
                                echo $col_value;
                                echo "</td>";
                                $contador++;
                            }
                            else {
                                echo "<td align='left'>";
                                echo $col_value;
                                echo "</td>";
                                $contador=0;						 
                            }
    				
                        }
                    }
               } // else

        echo "</form>";
        }// forecha
    
    echo "</tr>";

    }//while
?>
						<tbody> 
					</table>
				</div> <!-- table-wrapper  -->
			</section>
			
<?php
    }  // if

    else {
?>    
	<header></header><h3> No hay casos abiertos</h3></header>
<?php
    }

?>

			</div> <!-- col12  -->
		</div> <!-- row  --> 
		
	  </section>	
	</div> <!-- pagewapper  --> 
</body>
</html>