<?php

session_start();

if(isset($_SESSION['id_u'])) {
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    if($_SESSION['mod']==2){
        $mod=$_SESSION['mod'];
    }
    else {
        $mod= 1;
    }
    
    $_SESSION['mod']=$mod;
    
    $respuesta=$_SESSION['respuesta'];
    
    $myid_caso=$_SESSION['id_caso'];
    
    //cargo la lista de sujetos
    
    $resultado = mysqli_query($link, "select distinct s.id_sujeto_activo, s.nombre, s.apellido1, s.apellido2
    FROM sujeto_activo s
    WHERE id_caso=$myid_caso");
    
    $resultado2 = mysqli_query($link, "select distinct s.id_sujeto_activo as id_su, s.nombre as nom, s.apellido1 as ape
    FROM sujeto_activo s
    WHERE id_caso=1");
    $ret = mysqli_fetch_array($resultado2);
    
    
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Nueva Intervencion</title>
    	<meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    	<link rel="stylesheet" href="assets/css/main.css" />
    	

    			
    	<!-- Alonso -->
    		<script src="//code.jquery.com/jquery-latest.js"></script>
    		<script src="miscript.js"></script>
    		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    		<script src="js/jquery-3.4.1.js"></script>
    		<script src="miscript.js"></script>
    		
    		<script>
    		function respuesta() {
    			var respuesta=<?php echo $respuesta; ?>;
    			var c = document.getElementById("mensaje");
    			var ctx = c.getContext("2d");
    			ctx.font = "bold 12px Verdana";
    			ctx.clearRect(0, 0, c.width, c.height);	
    			if(respuesta==1) {
    				ctx.strokeStyle = "#FF0000";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#FF0000";
    				ctx.textAlign = "center";
    				ctx.fillText("Ya existe esa intervención",150,20);
    				<?php $_SESSION['respuesta']=0; ?>
    				setTimeout(borrar,5000);
    			}
    		};
			function asignarnumero() {
				respuesta();
				var numero = $.ajax({
				
  				  type: "GET", 
  		          url: 'obtenerintervencion.php',
  		          async: false     //ponemos el parámetro asyn a falso
  		      }).responseText;
				document.getElementById("numero_envio").value=numero;
  			  document.getElementById('numero').value=numero+" (las evidencias serán por ejemplo "+numero+"TEL1, "+numero+"HD1,....)";
			}
			function cambionumero() {
				
				var numero=$('input:text[name=numero]').val();
				if (isNaN(numero)) {
					asignarnumero();
					document.getElementById("direccion").focus();
				}
				else {
					document.getElementById("numero_envio").value=numero;
					document.getElementById('numero').value=numero+" (las evidencias serán por ejemplo "+numero+"TEL1, "+numero+"HD1,....)";
					document.getElementById("direccion").focus();
					
				}	
			}
			

    		</script>
    
    </head>
    
    <body class="is-preload" onload="asignarnumero()">
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
    				<h2>Agregar intervención</h2>
    			</header>
    			
    			<div class="row">
    						<div class="col-12">
    						
    							<form action='crearintervencion.php' method='post'>
    								<section class="box">
    								<div align="center">
    									<canvas id="mensaje" width="350" height="30"></canvas>
    									</div>
    							
    																
    									<h3>Agregar nueva intervención al caso</h3>
    									
    									<div class="row">
    										<div class="col-6 col-12-mobilep">																						
    										<h4>Seleccione  sujeto activo relacionado.</h4>
    										
    <?php
    										echo "<input type='radio' name='sujeto' id='sujeto' value='$ret[id_su]' required>";
    										echo"<label for='sujeto'>";
     											echo $ret['nom'];
     											echo " ".$ret['ape'];
    											echo " </label> <br>";
    			
    										
    	$i=1;
        $contador=0;
        $entro=0;
        while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            foreach ($line as $col_value) {
                if ($contador==0) {
    
                    echo "<input type='radio' name='sujeto' id='sujeto$i'; value=$col_value required>";
    				echo"<label for='sujeto$i'>";
                    $contador++; 
                    $i++;
                    
                }
                else {
                    if($entro<2) {
                        
                        echo $col_value."  ";
                        
                        $entro++;
                    }
                    else {
                       
                        echo $col_value." ";
                       
                        $entro=0;
                        $contador=0;
    					echo"</label><br> ";
                    }
                }
    				
            }
           
        }
    
        echo "<BR><br>";
    ?>										
    										
    										
    										<!-- boton -->
    										
    										</div>
    										
    										<div class="col-6 col-12-mobilep">
    											<h4>Datos de la intervención:</h4>
    <?php										
    
    echo "Número: ";
    ?>
   <input type='text' name='numero' id='numero' required  onchange='cambionumero()' onfocus="this.value=''">
   <input type='hidden' name='numero_envio' id='numero_envio'>
    <?php    
    
    
    										echo "Tipo Intervención: ";
        									echo "<select name='tipo'>";
        $resultado = mysqli_query($link, "select * from tipo_intervencion");
        $contador=0;
        $entro=0;
        while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            foreach ($line as $col_value) {
                if ($contador==0) {
                    echo "<option value='$col_value'>";
                    $contador++;
                }
                else {
                    if($entro==0) {
                        echo $col_value." ";
                        $entro=1;
                    }
                    else {
                        echo $col_value."</option> ";
                        $entro=0;
                        $contador=0;
                    }
                }
            }
        }
        echo "</select>";
    
        echo "Dirección: ";    
        echo "<input type='text' name='direccion' id='direccion' placeholder='Por ejemplo, Gran Vía de Hortaleza s/n Madrid'>";   
        
        echo "Descripción:";
        echo "<input type='text' name='descripcion' id='descripcion' placeholder='Por ejemplo, domicilio del detenido (No poner equipo aquí)'>";
      
    ?>	
    
    									
    										</div>
    										
    										<!-- botones aceptar y volver -->
    										
    										
    										
    									</div>
    									<br>
    									<div class="col-12">
    											<ul class="actions special">
    										  
    												<li><input type='submit' value="Aceptar"/></li>	
    												<li><input type="button" onclick="location.href='asunto.php';" value="Volver"><br></li>
    											  							
    											</ul>
    										</div>
    										
    									
    								</section>
    							</form>
    					</div>
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