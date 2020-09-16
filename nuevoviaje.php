<?php

session_start();

if(isset($_SESSION['id_u'])) {
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    $sql = "SELECT numero from caso where año=YEAR(CURDATE()) order by numero desc limit 0,1";
    
    $result=mysqli_query($link,$sql);
    $count= mysqli_num_rows($result);
    if($count!=0) {
        $ret = mysqli_fetch_array($result);
        $numero=$ret['numero']+1;
    }
    else {
        $numero=1;
    }
    ?>
    
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Nuevo Viaje</title>
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
    				url: "crearviaje.php",
    				data:formdata,
    				contentType: false,
    				processData: false,
    				success: function(respuesta) {
    					if(respuesta==1) {
    						window.location="viajes.php";
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
    		
    	<script>
    	function rellenarnombre(opSelect){
    		
    		var category=opSelect;
    		var url="obtencionnombre.php";
    		$.ajax({

    			url:url,
    	        type:"POST",
    	        data:{category:category}

    		}).done(function(data){
    	    	document.getElementById('nombre').value=data;
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
    					
    					<header>
    						<h2>Nuevo Viaje</h2>						
    					</header>
    					
    					
    					<div class="box">
    						<form method="post" id="myform" action="javascript:validar();">
    							Dar de alta un viaje <br><br>
    							<div align="center">
    							<canvas id="mensajes" width="350" height="30"></canvas>
    							</div>
    							<div class="row gtr-50 gtr-uniform">

    								<div class="col-3 col-12-mobilep">								
    									<input type="text" name="ciudad" id="ciudad" required pattern="[A-Z a-z Ñ ñ]*" placeholder="Ciudad" />
    								</div>
    								
    								<div class="col-2 col-12-mobilep">			
    								<?php
    								echo "<select id='transporte' name='transporte' required> ";
    								$sql="Select * FROM transporte";
    								$result=mysqli_query($link, $sql);
    								while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    								    $id_transporte=$line['id_transporte'];
    								    $nombre=$line['nombre'];
    								    echo "<option value='$id_transporte'>$nombre</option>"; 
    								}
 	
    								echo "</select>";
    								?>					
    									
    								</div>
    								
    								<div class="col-3 col-12-mobilep">
    								<?php 
    									echo "<select id='caso' name='caso' onchange='rellenarnombre(this.value);'required> ";
    									$sql="select c.id_caso,c.numero,c.año from caso c
                                        Left JOIN viajes v on v.id_caso=c.id_caso
                                        WHERE v.id_caso IS NULL and c.id_caso!=1";
    									$result=mysqli_query($link, $sql);
    									echo "<option value=''>Elige el caso</option>";
    									while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    									    $id_caso=$line['id_caso'];
    									    $numero=$line['numero'];
    									    $año=$line['año'];
    									    $año=substr($año, 2);
    									    $caso=$numero.'_'.$año;
    									    echo "<option value='$id_caso'>$caso</option>";
    									}
    									echo "</select>";
    								?>
    								</div>
    								
    								<div class="col-4 col-12-mobilep">
    								<input type="text" name="nombre" id="nombre" disabled>
    								</div>
    
    						<div class="col-12">
    							<textarea name="descripcion" id="descripcion" placeholder="Descripción del viaje..." rows="4"></textarea>
    						</div>							
    								
    								<span id="mensajes" style="color:#FF0000;"></span>
    								
    						<div class="col-12">
    							<ul class="actions special">
    								<li><input type="submit" id="alta" value="Alta Viaje"/></li>	
    								<li><input type="button" onclick="location.href='inicio.php';" value="Volver"><br></li>							
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







