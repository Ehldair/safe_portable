<?php

session_start();

if(isset($_GET["mod"])) {
    $mod= $_GET["mod"];
}
else {
    if(isset($_POST["mod"])) {
        $mod= $_POST["mod"];
    }
    else {
        if(isset($_SESSION["mod"])){
            $mod=$_SESSION['mod'];
        }
        else {
            $mod= 0;
        }
    }
}

if(isset($_POST["caso"])) {
    $caso= $_POST["caso"];
}
else {
    $caso=0;
}
if(isset($_POST["intervencion"])) {
    $intervencion= $_POST["intervencion"];
}
else {
    $intervencion=NULL;
}
if(isset($_POST["cabecera"])) {
    $cabecera= $_POST["cabecera"];
}
else {
    $cabecera="caca";
}




$_SESSION['mod']=$mod;


$link = mysqli_connect("localhost", "root", ".google.", "safe");
if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

$sql = "SELECT * 
FROM caso where id_caso=$caso";

$resultado=mysqli_query($link, $sql);
$count=mysqli_num_rows($resultado);

?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
 <title>Estadisticas predefinidas</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
	
		<!-- Alonso -->
		<script src="//code.jquery.com/jquery-latest.js"></script>
		<script src="miscript.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script src="js/jquery-3.4.1.js"></script>
		<script src="miscript.js"></script>
	
	<!-- Opcion de estadisticas con JS -->
	
	<script>
	function validar() {
		
		
			alert ("entro");
			var url="EGI_detalles.php?casos="+2+"&intervencion="+1;
            fi = document.getElementById('grafica');
            var imagen = document.createElement('img');
            imagen.src=url;
            fi.appendChild(imagen);
          
               
	}
	</script>
		
		

		
	
</head>

<body class="is-preload";>
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
    								</ul>	
    							</li>
    							
    							
    							<li><a href="login.php" class="button">Cerrar</a></li>
    						</ul>
    					</nav>
    				</header>
	
	
	<!-- Main -->
		<section id="main" class="container">
			<header><h2>Estadisticas predefinidas</h2></header>
			<div class="box">
				<div class="col-12 col-12-mobilep" style="text-align:center">
    				<h3>Estadistica General Intervencion</h3>
    				
    				<h3>  Caso: <?php echo $caso;?> Intervencion: <?php echo $intervencion;?> </h3>
    								
    				<div class="row">
    						<div class="col-12">
    								<section class="box">
    									<div id='grafica' style="height: 650; width: 450;">
    									
        								  <img src="'EGI_detalles.php?casos='+2+'&intervencion='+1'" alt="" border="0">
                                   		
                                   		</div>  
                              
                                   		
                                   		                                           
                    				</section>
    							
    						</div>
    				</div>
    				<input type="submit" value="Crear grafica" onclick="validar()">

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


    
