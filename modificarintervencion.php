<?php

session_start();

if(isset($_SESSION['id_u'])) {

    $link = mysqli_connect("localhost", "root", ".google.", "safe");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    $_SESSION['mod']=3;
    
    $myid_intervencion=$_SESSION['id_intervencion'];
    $myid_caso=$_SESSION['id_caso'];
    
    
    //cargo la lista de sujetos
    
    $resultado = mysqli_query($link, "select distinct s.id_sujeto_activo, s.nombre, s.apellido1, s.apellido2
    FROM sujeto_activo s
    WHERE id_caso=$myid_caso");
    
    $resultado2 = mysqli_query($link, "select distinct s.id_sujeto_activo as id_su, s.nombre as nom
    FROM sujeto_activo s
    WHERE id_caso=1");
    $ret = mysqli_fetch_array($resultado2);
    
    
    
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Modificar Intervención</title>
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
    	
    	
    	<div id="page-wrapper">
    
    	<!-- Main -->
    		<section id="main" class="container">
    			<header>
    				<h2>Agregar intervención</h2>
    			</header>
    			
    			<div class="row">
    						<div class="col-12">
    						
    							<form action='crearintervencion.php' method='post'>
    								<section class="box">
    							
    																
    									<h3>Modificar intervención</h3>
    									
    									<div class="row">
    										<div class="col-6 col-12-mobilep">																						
    										<h4>Seleccione  sujeto activo relacionado.</h4>
    										
    <?php
    $id_sujeto=$_SESSION['id_sujeto'];
    if($id_sujeto==1) {
        echo "<input type='radio' name='sujeto' id='sujeto' value='$ret[id_su]' checked required>";
    										
    }
    else {
        echo "<input type='radio' name='sujeto' id='sujeto' value='$ret[id_su]' required>";
    }
    echo"<label for='sujeto'>";
    echo $ret['nom'];
    echo " </label> <br>";
    										
    	$i=1;
    	$contador = 0;
    	$entro = 0;
      
        
        while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            foreach ($line as $col_value) {
                if ($contador == 0) {
                    if($col_value==$id_sujeto) {
                        echo "<input type='radio' name='sujeto' id='sujeto$i'; value=$col_value required checked>";
                        echo"<label for='sujeto$i'>";
                        $contador ++;
                        $i++;
                    }
                    else {
                        echo "<input type='radio' name='sujeto' id='sujeto$i'; value=$col_value required>";
    				    echo"<label for='sujeto$i'>";
    				    $contador ++;
                        $i++;
                    }
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
    $sql_intervencion="Select id_tipo_intervencion,direccion,descripcion FROM intervencion WHERE id_intervencion=$myid_intervencion";
    $resultado_intervencion=mysqli_query($link, $sql_intervencion);
    $ret_intervencion=mysqli_fetch_array($resultado_intervencion);
    $sql_tipo="Select nombre From tipo_intervencion where id_tipo_intervencion=$ret_intervencion[id_tipo_intervencion]";
    $resultado_tipo=mysqli_query($link, $sql_tipo);
    $ret_tipo=mysqli_fetch_array($resultado_tipo);
    
    
                                            echo "Tipo Intervención: ";
        									echo "<select name='tipo' id='tipo'>";
        $resultado = mysqli_query($link, "select id_tipo_intervencion, nombre from tipo_intervencion");
        //$ret=mysqli_fetch_array($resultado);
        $contador = 0;
        $entro = 0;
        echo "<option value=$ret_intervencion[id_tipo_intervencion]>$ret_tipo[nombre]</option>";
        while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            foreach ($line as $col_value) {
                if ($contador == 0) {
                    if ($col_value == $ret_intervencion['id_tipo_intervencion']) {
                        $entro = 1;
                        $contador ++;
                    } else {
                        echo "<option value='$col_value'>";
                        $contador ++;
                    }
                } else {
                    if ($entro == 1) {
                        $entro = 0;
                        $contador = 0;
                    } else {
                        echo " " . $col_value . "</option>";
                        $contador = 0;
                    }
                }
            }
        }
        echo "</select>";
    
        echo "Dirección: ";   
        
        echo "<input type='text' name='direccion' id='direccion' value='$ret_intervencion[direccion]'>";   
        
        echo "Descripción:";
        echo "<input type='text' name='descripcion' id='descripcion' value='$ret_intervencion[descripcion]' placeholder='(No poner equipo aquí)'>";
      
    ?>	
    
    									
    										</div>
    										
    										<!-- botones aceptar y volver -->
    										
    										
    										
    									</div>
    									<br>
    									<div class="col-12">
    											<ul class="actions special">
    										  
    												<li><input type='submit' value="Aceptar"/></li>	
    												<li><input type="button" onclick="location.href='detalle_intervencion.php';" value="Volver"><br></li>
    											  							
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