<?php

session_start();

if(isset($_SESSION['id_u'])) {

    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    $_SESSION['mod']=0;
    ?>
    
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Nuevo Sujeto</title>
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
    						<h2>Nuevo Sujeto Activo</h2>						
    					</header>
    					
    					<div class="box">
    						<form method="post" action="crearsujeto.php">
    							Dar de alta un suejto activo en el caso<br><br>
    							
    							<div class="row gtr-50 gtr-uniform">
    							
    								<div class="col-4 col-12-mobilep">													
    									<input type="text" name="nombre" id="nombre" required pattern="[a-z A-Z áéíóú]*" placeholder="Nombre">
    								</div>
    								
    								<div class="col-4 col-12-mobilep">													
    									<input type="text" name="apellido1" id="apellido1" pattern="[a-z A-Z áéíóú]*" placeholder="Apellido 1">
    								</div>
    								
    								<div class="col-4 col-12-mobilep">													
    									<input type="text" name="apellido2" id="apellido2" pattern="[a-z A-Z áéíóú]*" placeholder="Apellido 2">
    								</div>
    								
    								<div class="col-12">
    									<ul class="actions special">
    									<?php
    										echo "<input type='submit' value='Añadir' class='estilo'>";
    									?>
    										<li><input type="button" onclick="location.href='asunto.php';" value="Volver" /></li>																
    									</ul>
    								</div>
    								
    								
    							</div>
    						</form>
    						
    				</div>	
    			</section>
    		</div>	
    
    </body>
    <?php 
}
else {
    echo "Error";
}
    ?>

</html>
							

