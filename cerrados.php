<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe");

// comprobar la conexi�n
if (mysqli_connect_errno()) {
    printf("Fall� la conexi�n: %s\n", mysqli_connect_error());
    exit();
}
$sql = "SELECT id_caso, c.año,c.numero,  c.nombre, c.descripcion FROM caso c inner join estado_caso e ON c.id_estado_caso=e.id_estado_caso WHERE e.estado='Cerrado' AND id_caso!=1 ORDER BY fecha_alta_caso DESC";
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
		

			
	<!-- Alonso -->
		<script src="//code.jquery.com/jquery-latest.js"></script>
		<script src="miscript.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script src="js/jquery-3.4.1.js"></script>

</head>

<body class="is-preload">

	<!-- Header -->
    				<header id="header">
    					<h1><a href="">Safe Ciber</a> Gestión Sección Ciberterrorismo</h1>
    					<nav id="nav">
    						<ul>
    							<li><a href="inicio.php">Home</a></li>
    							<li>
    								<a href="#" class="icon solid fa-angle-down">Casos</a>
    								<ul>
    									<li><a href="busqueda_Caso.php">Buscar</a></li>
    									<li><a href="nuevoasunto.php">Nuevo</a></li>
    
    									<li>
    										<a href="#">Listar</a>
    										<ul>
    											<li><a href="abiertos.php">Abiertos</a></li>
    											<li><a href="cerrados.php">Cerrados</a></li>
    											<li><a href="todos.php">Todos</a></li>
    										</ul>
    									</li>
    									
    								</ul>
    							</li>
    							<li>
    								<a href="#" class="icon solid fa-angle-down">Gestión</a>
    								<ul>
    									<li><a href="compensacion_usuario.php">Compensaciones</a></li>
    									<li><a href="viajes_año.php">Viajes</a></li>
    								</ul>	
    							</li>
    							<?php if ($_SESSION['admin'] ==2) {?>
    							<li>
    								<a href="#" class="icon solid fa-angle-down">Administración</a>
    								<ul>
    									<li>
    										<a href="#">Usuario</a>
    										<ul>
    											<li><a href="nuevousuario.php">Nuevo</a></li>
    											<li><a href="#">Gestión</a></li>
    											<li><a href="#"></a></li>
    										</ul>
    									</li>
    									<li>
    										<a href="#">Viajes</a>
    										<ul>
    											<li><a href="nuevoviaje.php">Nuevo</a></li>
    											<li><a href="viajes.php">Gestión</a></li>
    											<li><a href="#"></a></li>
    										</ul>
    									</li>
    									<li>
    										<a href="#">Compensaciones</a>
    										<ul>
    											<li><a href="nuevosdias.php">Añadir días</a></li>
    											<li><a href="pedirdias.php">Pedir días</a></li>
    											<li><a href="gestion_dias.php">Gestión</a></li>
    											<li><a href="#"></a></li>
    										</ul>
    									</li>
    									<li>
    										<a href="#">Desplegables</a>
    										<ul>
    											<li><a href="#">Grupo</a>
    											<ul>
    												<li><a href="nuevogrupo.php">Nuevo grupo</a></li>
    												<li><a href="gestion_grupo.php">Gestión grupo</a></li></ul>
    											</li>
    											<li><a href="#">Comisaría</a>
    											<ul>
    												<li><a href="nuevogrupo_comisaria.php">Nueva Comisaría</a></li>
    												<li><a href="gestion_comisaria.php">Gestión comisaría</a></li></ul>
    											</li>
    											<li><a href="#">Juzgado</a>
    											<ul>
    												<li><a href="nuevojuzgado.php">Nuevo juzgado</a></li>
    												<li><a href="gestion_juzgado.php">Gestión juzgado</a></li></ul>
    											</li>
    										</ul>
    									</li>
    								</ul>	
    							</li>
    							<?php }?>
    							
    							
    							<li><a href="login.php" class="button">Cerrar</a></li>
    						</ul>
    					</nav>
    				</header>
	
	
	<div id="page-wrapper">

	<!-- Main -->
		<section id="main" class="container">
			<header>
			<h2>Casos</h2>
				<p>Listado de casos cerrados</p>
			</header>
			
			<div class="row">
						<div class="col-12">

							<!-- Table -->
								<section class="box">
									
									<div class="table-wrapper">
										<h3>Listado casos cerrados</h3>
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
$contador="0";
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
						<a href="asunto.php?id_caso=<?php echo $id_caso;?>" style='color: red;'>
							<?php echo $col_value;?>_<?php echo $año;?>
						</a> 
						
<?php 
               
                $contador++;
            }
            else {
             
                    if ($contador<4) {
                        echo "<td align='center'>";
                        echo $col_value;
                        echo "</td>";
                        $contador++;
                    }
                    else {
                        echo "<td align='center'>";
                        echo $col_value;
                        echo "</td>";
                        $contador=0;
                    }
                }
            }
        } // else
        echo "</form>";
    
    } // forecha
    
    echo "</tr>";

} // while
?>
						<tbody> 
					</table>
				</div> <!-- table-wrapper  -->
			</section>
<?php
} // if


else {
?>
   <header></header><h3> No hay casos cerrados</h3></header>
   
<?php   
}
?>


			</div> <!-- col12  -->
		</div> <!-- row  --> 
		
	  </section>	
	</div> <!-- pagewapper  --> 
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