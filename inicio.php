<?php
isset($_SESSION) || session_start();

// start a session

if(isset($_SESSION['id_u'])) {
        $admin=$_SESSION['admin'];
    
        $id_u=$_SESSION['id_u'];
        
        $respuesta=$_SESSION['respuesta'];
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    
    <head>
    
    
    	<title>Dashboard</title>
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
    		<script>
    		function respuesta() {
    			var respuesta=<?php echo $respuesta; ?>;

    				if(respuesta==2) {
    					var c = document.getElementById("mensaje");
    					var ctx = c.getContext("2d");
    					ctx.font = "bold 12px Verdana";
    					ctx.clearRect(0, 0, c.width, c.height);
    					ctx.strokeStyle = "#3DBA26";
    					ctx.strokeRect(1, 1, 299, 29);
    					ctx.fillStyle = "#3DBA26";
    					ctx.textAlign = "center";
    					ctx.fillText("Caso eliminado",150,20);
    					<?php $_SESSION['respuesta']=0; ?>
    				}
    	        
    	      }
    		</script>
    		
    		<script type="text/javascript">
    			
    			$(document).ready(function() {
    			    $('#btn1').on('click', function(){
    			    	var c = document.getElementById("mensaje");
    					var ctx = c.getContext("2d");
    			    	ctx.clearRect(0, 0, c.width, c.height);
    			        $.ajax({
    			            type: "POST",
    			            url: "abiertos.php",
    			            
    			            success: function(response) {
    			                $('#div-results').html(response);                
    			            }
    			        });
    			    });
    			    $('#btn4').on('click', function(){
    			    	var c = document.getElementById("mensaje");
    					var ctx = c.getContext("2d");
    			    	ctx.clearRect(0, 0, c.width, c.height);
    			        $.ajax({
    			            type: "POST",
    			            url: "nuevoasunto.php",
    			          
    			            success: function(response) {
    			                $('#div-results').html(response);
    			                
    			            }
    			        });
    			    });  
    			});
    
    
    </script>
    
    </head>
    
    <body class="is-preload" onload="respuesta();">
    		<div id="page-wrapper">
    
    			<!-- Header -->
    				<header id="header">
    					<h1><a href="login.php">Safe ver. PORTABLE</a> Gesti&oacuten Secci&oacuten Ciberterrorismo</h1>
    					<nav id="nav">
    						<ul>
    							<li><a href="inicio.php">Home</a></li>
    							<li>
    								<a href="#" class="icon solid fa-angle-down">Casos</a>
    								<ul>
    									<li><a href="generic.html">Buscar</a></li>
    									<li><a href="contact.html">Nuevo</a></li>
    
    									<li>
    										<a href="#">Listar</a>
    										<ul>
    											<li><a href="#">Abiertos</a></li>
    										</ul>
    									</li>
    								</ul>
    							</li>
    							<li><a href="#" class="button">Sign Up</a></li>
    						</ul>
    					</nav>
    				</header>
    
    			<!-- Main -->
    				<section id="main" class="container">
    					<header>
    						<h2>Dashboard</h2>
    						<p></p>
    						<div align="center">
    							<canvas id="mensaje" width="300" height="30"></canvas>
    							</div>
    						<div class="col-12">	
    									<ul class="actions special">
    										<li><input type="button" id="btn4" value="Nuevo Caso"></li>
    										<li><input type="button" id="btn1" value="Casos Grabados"></li>
    										
    										
    									</ul>
    									
    						</div>
    						<div class="col-12">	
 <?php
}
else {
 echo "   Error";
}
?>

			
			<div id="div-results"></div>
				<form>
					<input type="hidden">
				</form>

	</div>
</body>
</html>