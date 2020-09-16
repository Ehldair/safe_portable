<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe");

// comprobar la conexi�n
if (mysqli_connect_errno()) {
    printf("Fall� la conexi�n: %s\n", mysqli_connect_error());
    exit();
}
$sql = "SELECT  u.id_usuario, u.apodo as apodo, sum(dias) as dias from compensacion c
inner join usuario u on u.id_usuario=c.id_usuario
group by id_usuario order by id_usuario";
$resultado=mysqli_query($link, $sql);
$count=mysqli_num_rows($resultado);

$respuesta=$_SESSION['respuesta'];
echo $respuesta;


?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
 <title>Gestión Días</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
	

			
	<!-- Alonso -->
		<script src="//code.jquery.com/jquery-latest.js"></script>
		<script src="miscript.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script src="js/jquery-3.4.1.js"></script>
		<script>
    		function respuesta() {
			var respuesta=<?php echo $respuesta; ?>;
			var c = document.getElementById("mensaje");
				if(respuesta==1) {
    				var ctx = c.getContext("2d");
    				ctx.font = "bold 12px Verdana";
    				ctx.clearRect(0, 0, c.width, c.height);
    				ctx.strokeStyle = "#3DBA26";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#3DBA26";
    				ctx.textAlign = "center";
    				ctx.fillText("Días Añadidos",150,20);
    			} else if(respuesta==2) {
    				var ctx = c.getContext("2d");
    				ctx.font = "bold 12px Verdana";
    				ctx.clearRect(0, 0, c.width, c.height);
    				ctx.strokeStyle = "#3DBA26";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#3DBA26";
    				ctx.textAlign = "center";
    				ctx.fillText("Dias Eliminados",150,20);
    			}			
    		<?php $_SESSION['respuesta']=0; ?>
			setTimeout(borrar,5000);
    		}
		function borrar() {
			var c = document.getElementById("mensaje");
			var ctx = c.getContext("2d");
			ctx.clearRect(0, 0, c.width, c.height);
		}
		</script>

</head>


<body class="is-preload" onload="respuesta();">
	<div id="page-wrapper">
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
	
	
	<!-- Main -->
		<section id="main" class="container">
			<header>
				<h2> Gestionar Días</h2>
				<p>Seleccionar días a modificar/eliminar</p>
			</header>			
			<div align="center">
    			<canvas id="mensaje" width="300" height="30"></canvas>
    		</div>
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
                                            <th>Usuario</th>
                                            <th>Dias</th>
										</tr>
									</thead>
									<tbody>
			
	";


while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
    $id_usuario=$line['id_usuario'];
    $apodo = $line['apodo'];
    $dias= $line['dias'];
    $id_usuario64=base64_encode($id_usuario);   
    $sql_pedidos = "SELECT  sum(dias) as dias from dias_pedidos d
    inner join usuario u on u.id_usuario=d.id_usuario
    where d.id_usuario=$id_usuario";
    $resultado_pedidos=mysqli_query($link, $sql_pedidos);
    $count_pedidos=mysqli_num_rows($resultado_pedidos);

    if($count_pedidos!=0) {
        $ret=mysqli_fetch_array($resultado_pedidos);
        $dias2=$ret['dias'];
        $dias=$dias-$dias2;
    }
    echo "<tr>
    					<td style='text-align: left'>";
    
    
    echo "<a href='modificardias.php?id_usuario=$id_usuario64' style='color: black' >
        <b>$apodo </b></a>";
    echo "<td align='left'>$dias</td>";
    echo "</form>";
    echo "</tr>";
} //while

echo "						<tbody>
					</table>
			</section>";

}  // if

else {
    echo "<br><b>";
    echo "NO EXISTEN DIAS";
    echo "</b>";
}
?>

			</div>
			</section>
			</div>
		</div>
		
	</section>	
	</div>
	
		<!-- Pelayo -->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/jquery.dropotron.min.js"></script>
		<script src="assets/js/jquery.scrollex.min.js"></script>
		<script src="assets/js/browser.min.js"></script>
		<script src="assets/js/breakpoints.min.js"></script>
		<script src="assets/js/util.js"></script>
		<script src="assets/js/main.js"></script>
	
</body>
</html>