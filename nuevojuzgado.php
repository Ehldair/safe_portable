<?php

session_start();

if(isset($_SESSION['id_u'])) {
    
    
    ?>
    
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Añadir juzgado</title>
    	<meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    	<link rel="stylesheet" href="assets/css/main.css" />
    	

    			
    	<!-- Alonso -->
    		<script src="//code.jquery.com/jquery-latest.js"></script>
    		<script src="miscript.js"></script>
    		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    		<script src="js/jquery-3.4.1.js"></script>
    
    		<script src=»https://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.0/jquery.validate.min.js» type=»text/javascript»></script>
    		<script src=»js/jquery-validate.js»></script>
    	
    		
    		<script>
    		function validar() {
    			var c = document.getElementById("mensajes");
    			var ctx = c.getContext("2d");
    			ctx.font = "bold 12px Verdana";
    			var formdata = new FormData($("#myform")[0]);
    			formdata.append("mod", 0);
    			$.ajax({
    				type: "POST",
    				url: "crearjuzgado.php",
    				data:formdata,
    				contentType: false,
    				processData: false,
    				success: function(respuesta) {
    					if(respuesta==1) {
    						window.location="gestion_juzgado.php";
    					}
    					else {
    						ctx.clearRect(0, 0, c.width, c.height);	
    						ctx.clearRect(0, 0, c.width, c.height);	
    						ctx.strokeStyle = "#FF0000";
    						ctx.strokeRect(1, 1, 349, 29);
    						ctx.fillStyle = "#FF0000";
    						ctx.textAlign = "center";
    						ctx.fillText(respuesta,175,20);
    					}
    				}
    			});
    		};
    
    			
    
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
    					
    					<div class="box">
    						<form method="post" id="myform" action="javascript:validar();">
    							Añadir juzgado <br><br>
    							<div align="center">
    							<canvas id="mensajes" width="350" height="30"></canvas>
    							</div>
    							<div class="row gtr-50 gtr-uniform">
    								<div class="col-5 col-12-mobilep">			
    									Jurisdicción:					
    									<input type="text" name="jurisdiccion" id="jurisdiccion" required pattern="[a-z A-Z . ÁÉÍÓÚáéíóú]*" placeholder="Jurisdiccion (Ejemplo Zamora)" />
    								</div>

    								<div class="col-5 col-12-mobilep">			
    									Nombre:					
    									<input type="text" name="nombre" id="nombre" required pattern="[a-z A-Z . ÁÉÍÓÚáéíóú]*" placeholder="Nombre (Ejemplo Juzgado de Instrucción)" />
    								</div>
    								
    								<div class="col-2 col-12-mobilep">		
    								Número:						
    									<input type="text" name="numero" id="numero" required pattern="\d.*" placeholder="Número" />
    								</div>
    								
    						<div class="col-12">
    							<ul class="actions special">
    								<li><input type="submit" id="alta" value="Alta Juzgado"/></li>	
    								<li><input type="button" onclick="location.href='gestion_juzgado.php';" value="Volver"><br></li>							
    							</ul>
    						</div>
    						
    					    
    					    </div>
    					    </form>
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
    <?php
}
else {
    echo "Error";
}
    ?>

</html>







