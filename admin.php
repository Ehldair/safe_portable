<?php 
session_start();


if(isset($_SESSION['id_u'])) {

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
    			    $('#btn3').on('click', function(){
    			    	var c = document.getElementById("mensaje");
    					var ctx = c.getContext("2d");
    			    	ctx.clearRect(0, 0, c.width, c.height);
    			    	dataString = {admin: document.getElementById("admin").value , id_u:document.getElementById("id_u").value};
    			        $.ajax({
    			            type: "POST",
    			            url: "borrarusuario.php",
    			            data: dataString, 
    			            success: function(response) {
    			                $('#div-results').html(response);
    			                
    			            }
    			        });
    			    });
    			    $('#btn4').on('click', function(){
    			    	var c = document.getElementById("mensaje");
    					var ctx = c.getContext("2d");
    			    	ctx.clearRect(0, 0, c.width, c.height);
    			    	dataString = {admin: document.getElementById("admin").value , id_u:document.getElementById("id_u").value};
    			        $.ajax({
    			            type: "POST",
    			            url: "inicio.php",
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
    					<h1><a href="login.php">Safe Ciber</a> Gesti&oacuten Secci&oacuten Ciberterrorismo</h1>
    					<nav id="nav">
    						<ul>
    							<li><a href="inicio.php">Home</a></li>
    							<li>
    								<a href="#" class="icon solid fa-angle-down">Layouts</a>
    								<ul>
    									<li><a href="generic.html">Generic</a></li>
    									<li><a href="contact.html">Contact</a></li>
    									<li><a href="elements.html">Elements</a></li>
    									<li>
    										<a href="#">Submenu</a>
    										<ul>
    											<li><a href="#">Option One</a></li>
    											<li><a href="#">Option Two</a></li>
    											<li><a href="#">Option Three</a></li>
    											<li><a href="#">Option Four</a></li>
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
    						<h2>Administracion</h2>
    						<p></p>
    						<div align="center">
    							<canvas id="mensaje" width="300" height="30"></canvas>
    							</div>
    						<div class="col-12">	
    						<ul class="actions special">
        						<li><input type="button" id="btn1" value="Nuevo usuario" class="estilo"></li>
        						<li><input type="button" id="btn2" value="Gestionar usuario" class="estilo"></li>
        						<!--  <li><input type="button" id="btn3" value="Eliminar usuario" class="estilo"></li> -->
        						<input type='hidden' name="admin" id="admin" value="<?php echo $admin;?>">
        						<input type='hidden' name="id_u" id="id_u" value="<?php echo $id_u;?>">
    						</ul>
    						<ul class="actions special">
    
    <?php 
    		if ($_SESSION['admin']==2) {
    		  
    		   echo "<form action='admin.php' method='post'>";
               echo "<input type='submit' value='Administración' class='admin'>"; 	   
    		   echo "</form>";   
    		}
}
else {
    echo "Error";
}
		?>
		   
		   <li><input type="button" onclick="location.href='inicio.php';" value="Volver" class="estilo"></li>
		   </ul>
			<div id="div-results"></div>
				<form>
					<input type="hidden">
			</form>

	</div>	
</body>

</html>