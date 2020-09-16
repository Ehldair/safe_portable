<?php

session_start();



$link = mysqli_connect("localhost", "root", ".google.", "safe");
if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

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

if(isset($_POST["estadisticas"])) {
    $estadistica= $_POST["estadisticas"];
}
else {
    $estadistica=NULL;
}

$_SESSION['mod']=$mod;


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
				
				<div class="row">
						<div class="col-12">
							<form id="form_inf_pred" name="form_inf_pred" action='<?php echo $estadistica;?>.php' method="POST" target=_blank>
								<section class="box">
									
									<input type='hidden' name="cabecera" id="cabecera" value=134>
									
									<h3><b>Necesitamos algunos datos para emitir esta estadistica</b></h3> 
                                 	<br>  
                                 	<hr color="blue" size=3>
                                    <h4>DATOS   </h4>
									<br>  							
									<div class="row">
										
                                       <div class="row">
										<div class="col-6 col-12-mobilep">
											<h4>Selecciona fecha inicio de la generacion de la estadistica </h4>
											<h5> Por defecto año en curso</h5>										
                								<br>
                    					
                                                 <input type="datetime-local" name="fechainicioE" id="fechainicioE">
                                                    
                                              
                                         </div>
                                         <div class="col-6 col-12-mobilep">
                                             <h4>Selecciona fecha fin de la generacion de la estadistica </h4>
												<h5> Por defecto año en curso</h5>
											
                								<br>
                    					
                                                 <input type="datetime-local" name="fechafinE" id="fechafinE">
                                                    
                                              
                                         </div>
                                                 <br>
                                               
                               				  	 
											</div> <!-- <div class="row"> -->
                                      
										</div>
									<br>
									<div class="col-12">
											<ul class="actions special">
										  
												<li><input type='submit'  value="Genera estadistica" ><br></li>
											  	
												<li><input type="button" onclick="location.href='estadisticas.php';" value="Volver"><br></li>
											  							
											</ul>
										</div>
										
									
								</section>
							</form>
					</div>
			</div>
		</section>								
	</div>
 </body> 
</html>