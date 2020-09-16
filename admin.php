<?php 
session_start();


$respuesta=$_SESSION['respuesta'];

?>

<!DOCTYPE html>
<html lang="es-ES">

<head>

	<title>Dashboard</title>
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
			
			
			if(respuesta!=0) {
				var ctx = c.getContext("2d");
				ctx.font = "bold 12px Verdana";
				ctx.clearRect(0, 0, c.width, c.height);
				ctx.strokeStyle = "#3DBA26";
				ctx.strokeRect(1, 1, 299, 29);
				ctx.fillStyle = "#3DBA26";
				ctx.textAlign = "center";

    			if(respuesta==1) {
    					
    				ctx.fillText("Usuario creado correctamente",150,20);
    			}
    			if(respuesta==2) {
    				
    				ctx.fillText("Usuario modificado correctamente",150,20);
    			}
    			if(respuesta==3) {
    				
    				ctx.fillText("Usuario eliminado correctamente",150,20);
    			}
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
		
		<script type="text/javascript">
			
			$(document).ready(function() {
			    $('#btn1').on('click', function(){
			    	var c = document.getElementById("mensaje");
					var ctx = c.getContext("2d");
			    	ctx.clearRect(0, 0, c.width, c.height);
			    	dataString = {admin: document.getElementById("admin").value, id_u:document.getElementById("id_u").value};
			        $.ajax({
			            type: "POST",
			            url: "nuevousuario.php",
			            data: dataString, 
			            success: function(response) {
			                $('#div-results').html(response);                
			            }
			        });
			    });
			 
			    $('#btn2').on('click', function(){
			    	var c = document.getElementById("mensaje");
					var ctx = c.getContext("2d");
			    	ctx.clearRect(0, 0, c.width, c.height);
			    	dataString = {admin: document.getElementById("admin").value, id_u:document.getElementById("id_u").value};
			        $.ajax({
			            type: "POST",
			            url: "modificarusuario.php",
			            data: dataString, 
			            success: function(response) {
			                $('#div-results').html(response);
			            }
			        });
			    });
			});
<?php
// start a session

    $admin=$_SESSION['admin'];

    $id_u=$_SESSION['id_u'];

?>

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
						<h2>Administracion</h2>
						<p></p>
						<div align="center">
							<canvas id="mensaje" width="300" height="30"></canvas>
							</div>
						<div class="col-12">	
						<ul class="actions special">
    						<li><input type="button" id="btn1" value="Nuevo usuario" class="estilo"></li>
    						<li><input type="button" id="btn2" value="Gestionar usuario" class="estilo"></li>
    						<li><form action="viajes.php" method="post">
    							<input type='submit' value='Gestión Viajes'>
    						</form></li>
    						<input type='hidden' name="admin" id="admin" value="<?php echo $admin;?>">
    						<input type='hidden' name="id_u" id="id_u" value="<?php echo $id_u;?>">
						</ul>
						<ul class="actions special">

		   
		   <li><input type="button" onclick="location.href='inicio.php';" value="Volver" class="estilo"></li>
		   
		  </ul>
		  </div></header>
		  </section>
		  
			<div id="div-results"></div>
				<form>
					<input type="hidden">
			</form>

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