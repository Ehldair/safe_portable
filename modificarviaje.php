<?php
session_start();

if(isset($_SESSION['id_u'])) {
    
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    $myid_viajes=$_SESSION['id_viajes'];
    $myciudad=$_POST['ciudad'];
    $mydescripcion=$_POST['descripcion'];
    $mynombre_transporte=$_POST['nombre_transporte'];
    $myid_transporte=$_POST['id_transporte'];
    $sql=mysqli_query($link, "Select v.id_caso as id_caso, c.numero as numero, c.año as año from viajes v
    inner join caso c ON c.id_caso=v.id_caso where id_viajes=$myid_viajes");
    $ret=mysqli_fetch_array($sql);
    $año=substr($ret['año'], 2);
    $caso=$ret['numero'].'_'.$año;
    $id_caso=$ret['id_caso'];
    
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Modificar Viaje</title>
    	<meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    	<link rel="stylesheet" href="assets/css/main.css" />
    	

    			
    	<!-- Alonso -->
    		<script src="//code.jquery.com/jquery-latest.js"></script>
    		<script src="miscript.js"></script>
    		<script src=»https://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.0/jquery.validate.min.js» type=»text/javascript»></script>
    		<script src=»js/jquery-validate.js»></script>
    	
    		
    		<script>
    		function validar() {
    			var c = document.getElementById("mensajes");
    			var ctx = c.getContext("2d");
    			ctx.font = "bold 12px Verdana";
    
    			var formdata = new FormData($("#myform")[0]);
    			formdata.append("mod", 1);
    			$.ajax({
    				type: "POST",
    				url: "crearviaje.php",
    				data:formdata,
    				contentType: false,
    				processData: false,
    				success: function(respuesta) {
    					if(respuesta==2) {
    						window.location="detalle_viaje.php";
    					}
    					else {
    						ctx.clearRect(0, 0, c.width, c.height);	
    						ctx.strokeStyle = "#FF0000";
    						ctx.strokeRect(1, 1, 599, 29);
    						ctx.fillStyle = "#FF0000";
    						ctx.textAlign = "center";
    						ctx.fillText(respuesta,300,20);
    					}
    				}
    			});
    		};	
    
    			
    
    		</script>
   <script>
    	function rellenarnombre(){
    		
    		var category=document.getElementById("caso").value;
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
    
    <body class="is-preload" onload="rellenarnombre();">
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
    						<h2>Modificar Viaje</h2>						
    					</header>
    					
    					<div class="box">
    						<form method="post" id="myform" action="javascript:validar();">
    							Datos del viaje <br><br>
    							
    							<div class="row gtr-50 gtr-uniform">
    							
    								<div class="col-3 col-12-mobilep">								
    										Ciudad
    										<?php
    											echo "<input type='text'  name='ciudad' id='ciudad'  value='$myciudad' required>";
    										?>
    								</div>
    								
    							  <div class="col-2 col-12-mobilep">
    
    <?php
        echo "Transporte";
        echo "<select name='transporte' id='transporte'>";
        echo "<option value='$myid_transporte' selected>$mynombre_transporte</option>";
        $resultado = mysqli_query($link, "select * FROM transporte where id_transporte!=$myid_transporte");
        while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            $id_transporte=$line['id_transporte'];
            $nombre_transporte=$line['nombre'];
            echo "<option value='$id_transporte'>$nombre_transporte</option>";
        }
        echo "</select>";

    ?>							
    
    							</div>
    							<div class="col-3 col-12-mobilep">
    								<?php 
    								    echo "Caso";
    									echo "<select id='caso' name='caso' onchange='rellenarnombre();'required> ";
    									echo "<option value='$id_caso'>$caso</option>";
    									$sql="select c.id_caso,c.numero,c.año from caso c
                                        Left JOIN viajes v on v.id_caso=c.id_caso
                                        WHERE v.id_caso IS NULL and c.id_caso!=1 and c.id_caso!=$id_caso";
    									$result=mysqli_query($link, $sql);
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
    								Nombre Operación
    								<input type="text" name="nombre" id="nombre" disabled>
    								</div>
    								
    							  <div class="col-12">
    <?php							  
    echo "Descripcion";
    echo "<textarea name='descripcion'  id='descripcion' value='$mydescripcion' rows='4'>$mydescripcion</textarea>";
    ?>
    							</div>
							
    							</div>
    							
    							<br>
    							<div align="center">
    							<canvas id="mensajes" width="600" height="30"></canvas>
    							</div>
    							<div class="col-12">
    									<ul class="actions special">
    										<li><input type='submit' id='modificar' value='Modificar' ></li>
    										<li><input type="button" onclick="location.href='detalle_viaje.php';" value="Volver"></li>						
    									</ul>
    								</div>
    								
    							
    <?php
}
else {
    echo "Error";
}
?>
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
</html>	
