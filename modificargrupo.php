<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe");

// comprobar la conexi�n
if (mysqli_connect_errno()) {
    printf("Fall� la conexi�n: %s\n", mysqli_connect_error());
    exit();
}

$myid_grupo=base64_decode(mysqli_real_escape_string($link,$_GET['id_grupo']));
$_SESSION['id_grupo']=$myid_grupo;

$sql = "SELECT  p.nombre_provincia,nombre_comisaria,nombre_grupo, id_grupo_investigacion, movil, telefono, responsable FROM grupo_investigacion g
INNER JOIN comisaria c On c.id_comisaria=g.id_comisaria
INNER JOIN PROVINCIA p on p.id_provincia=c.id_provincia WHERE id_grupo_investigacion=$myid_grupo";
$resultado=mysqli_query($link, $sql);
$ret=mysqli_fetch_array($resultado);


?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
 <title>Modificar grupo</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
	

			
	<!-- Alonso -->
		<script src="//code.jquery.com/jquery-latest.js"></script>
		<script src="miscript.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script src="js/jquery-3.4.1.js"></script>
		
		<script type="text/javascript">
    		function preguntaGrupo(opSelect){ 
        		var category=opSelect;
        		var url="eliminargrupo.php";
    			if (confirm('¿Estas seguro de eliminar el grupo?')){ 
    				$.ajax({
    					url:url,
    		        	type:"POST",
    		        	data:{category:category}

    		      	}).done(function(data){
						
    		    		  location.href = "grupo.php";   
    		      	});  
       			}
     			else {
     				location.href = "grupo.php";
     	 		} 	
    		} 
    	</script> 

</head>


<body class="is-preload">
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
    						<h2>Modificar Grupo</h2>						
    					</header>
    					
    					
    					<div class="box">
    						<form method="post" id="myform" action="creargrupo.php?mod=1">
    							Modificar un grupo <br><br>
    							<div align="center">
    							<canvas id="mensajes" width="350" height="30"></canvas>
    							</div>
    							<div class="row gtr-50 gtr-uniform">

    								
    								
    								<div class="col-6 col-12-mobilep">	

<?php 
        echo "Nombre:";
        echo "<input type='text' name='nombre_grupo' id='nombre_grupo' value='$ret[nombre_grupo]'>";
        echo "<input type='hidden' name='id_grupo' id='id_grupo' value='$ret[id_grupo_investigacion]'>";
   ?>     
        </div>
		<div class="col-6 col-12-mobilep">	
		<?php 
		echo "Móvil:";
		echo " <input type='text' name='movil' id='movil' value='$ret[movil]' placeholder='Móvil'>";
		    ?>
   		</div>
   		<div class="col-6 col-12-mobilep">	
   		<?php 
   		echo "Teléfono:";
   		echo "<input type='text' name='telefono' id='telefono' value='$ret[telefono]' placeholder='Teléfono'>";
   		?>
   		</div>
    	
    	<div class="col-6 col-12-mobilep">
    	<?php 
    	echo "Responsable:";
  		echo "<input type='text' name='responsable' id='responsable' value='$ret[responsable]' placeholder='Responsable'>";
  		?>
		</div>
		<div class="col-12">
    							<ul class="actions special">
    								<li><input type="submit" id="alta" value="Modificar Grupo"/></li>	
    								<li><input type="button" onclick="location.href='gestion_grupo.php';" value="Volver"><br></li>							
    							</ul>
    						</div>
    						</div>
		</form>
		</div>

			</section>";


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