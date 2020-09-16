<?php

session_start();

if(isset($_SESSION['id_u'])) {


    
    $link = mysqli_connect("localhost", "root", ".google.", "safe");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    
    $sql = "SELECT id_usuario from usuario order by id_usuario desc limit 0,1";
    
    $result=mysqli_query($link,$sql);
    $count= mysqli_num_rows($result);
    if($count!=0) {
        $ret = mysqli_fetch_array($result);
        $numero=$ret['id_usuario']+1;
    }
    else {
        $numero=1;
    }
    ?>
    
    
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Nuevo Usuario</title>
    	<meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    	<link rel="stylesheet" href="assets/css/main.css" />
    	

    			
    	<!-- Alonso -->
    		<script src="//code.jquery.com/jquery-latest.js"></script>
    		<script src="miscript.js"></script>
    		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    		<script src="js/jquery-3.4.1.js"></script>
    		
    		<script>
    		function validar() {
    			var c = document.getElementById("mensaje");
    			var ctx = c.getContext("2d");
    			ctx.font = "bold 12px Verdana";
    			var formdata = new FormData($("#miform")[0]);
    
    			$.ajax({
    				type: "POST",
    				url: "crearusuario.php",
    				
    					 data:formdata,
    					 contentType: false,
    					 processData: false,
    				success: function(respuesta) {
    					if(respuesta==1) {
    						
    						window.location="admin.php";
    					}
    					else {
    						ctx.strokeStyle = "#FF0000";
    						ctx.strokeRect(1, 1, 299, 29);
    						ctx.fillStyle = "#FF0000";
    						ctx.textAlign = "center";
    						ctx.fillText(respuesta,150,20);
    					}
    				}
    			});
    		};
    
    			
    
    		</script>
    		 
    		<script type="text/javascript">
    		
    		$('#escala').on('change',function(){
    		
        	var category=$("#escala").val();
        	var url="obtenercategoria.php";
    
    		$.ajax({
    
            	url:url,
                type:"POST",
                data:{category:category}
    
    		}).done(function(data){
    
            	$("#categoria").html(data);
            })    
         	});
    		
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
    						<h2>Nuevo Usuario</h2>
    						<p>Datos del usuario</p>						
    					</header>
    					
    					<div class="box">
    			
    						<form method="post" id="miform" enctype="multipart/form-data" action="javascript:validar();">
    							
    							<div align="center">
    							<canvas id="mensaje" width="300" height="30"></canvas>
    							</div>
    							
    							<input type='hidden' name="Idusuario" id="Idusuario" value="<?php echo $numero;?>">
    								
    								
    							<div class="row gtr-50 gtr-uniform">
    							
    								
    								<div class="col-6 col-12-mobilep">	
    									<input type="text"  value="1. Introduce datos personales" style="font-weight: bold;" readonly="readonly"/>
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									
    								</div>
    							</div>
    							<br>
    							<div class="row gtr-50 gtr-uniform">
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="nombre" id="nombre" placeholder="Nombre" required>
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="apellido1" id="apellido1" placeholder="Primer Apellido">
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="apellido2" id="apellido2" placeholder="Segundo Apellido">
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="DNI" id="DNI" placeholder="D.N.I" maxlength="9" required>
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="CP" id="CP" placeholder="C.P." maxlength="6" required>
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="telefono" id="telefono" placeholder="Telefono" maxlength="9">
    								</div>
    									
    								
    							<div class='col-4 col-12-mobilep'>
    							<select name='escala' id='escala'>
       							<option value=0>Escala</option>
    							<?php 
    							$resultado = mysqli_query($link, "select id_escala, nombre FROM escala");
    							$contador=0;
    							while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
    							    foreach ($line as $col_value) {
    							        if ($contador==0) {
    							            echo "<option value='$col_value'>";
    							            $contador++;
    							        }
    							        else {
    							            echo " ".$col_value."</option>";
    							            $contador=0;
    							        }
    							    }
    							}
    							?>
    							 </select></div>
    							
    							<div class='col-4 col-12-mobilep'>
    							<select id='categoria' name='categoria'>
    							<option value=0>Categoria</option>
    							</select></div>
    							
    							<div class='col-4 col-12-mobilep'>	
                                <select name='grupo_ciber' id='grupo_ciber'> 
                                <option value=0>Grupo</option>
    		
    							<?php
    							$resultado = mysqli_query($link, "select * FROM grupo_ciber");
    							$contador=0;
    							while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
    							    foreach ($line as $col_value) {
    							        if ($contador==0) {
    							            echo "<option value='$col_value'>";
    							            $contador++;
    							        }
    							        else {
    							            echo " ".$col_value."</option>";
    							            $contador=0;
    							        }
    							    }
    							}
    							?>
    							 </select> 
    							 </div>
    							
    							
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="apodo" id="apodo" placeholder="Apodo" required>
    								</div>						
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="usuario" id="usuario" placeholder="Usuario" required>
    								</div>	
    								 <div class="col-4 col-12-mobilep">	
    									<input type="password" name="password" id="password" placeholder="Contraseña" required>
    								</div>
    								
    								<div class="col-4 col-12-mobilep">	
    									<input type="email" name="email" id="email" placeholder="Correo Electronico">
    								</div>
    								<div class="col-4 col-8-mobilep">	
    									
    									<input type="text" value="Foto [selecciona el fichero]" readonly="readonly">
    									<input type="file" id="imagen" name="imagen" accept="image/png, .jpeg, .jpg, image/gif">
    
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									<select name="administrador" id="administrador" onchange="escribir(this.value);" required>
    								    	<option value=0>Perfil Administrador</option>
    								      	<option value="SI">SI</option>
    								      	<option value="NO">NO</option>
    								    </select>
    								</div>
    								</div>
    								<br><br>
            						<div class="row gtr-50 gtr-uniform">
            							
            								
            								<div class="col-6 col-12-mobilep">	
            									<input type="text" value="2. Introduce datos vehiculo personal" style="font-weight: bold;" readonly="readonly"/>
            								</div>
            								<div class="col-4 col-12-mobilep">	
            									
            								</div>
            								<div class="col-4 col-12-mobilep">	
            									
            								</div>
    								</div>
    								<br>			
    						<div class="row gtr-50 gtr-uniform">
    							
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="marca" id="marca" placeholder="Marca Vehículo" >
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="modelo" id="modelo" placeholder="Modelo Vehículo">
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="matricula" id="matricula" placeholder="Matricula Vehículo" maxlength="7">
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="color" id="color" placeholder="Color Vehículo">
    								</div>
    							
    						</div>
    						<br>
    						<div class="col-12">
    							<ul class="actions special">
    								<li><input type="submit" value="Crear" /></li>			
    								<li><input type="button" onclick="location.href='admin.php';" value="Volver" class="estilo"></li>					
    							</ul>
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
