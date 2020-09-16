<?php
session_start();

if(isset($_SESSION['id_u'])) {

    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    $myid_su=$_SESSION['id_su'];
    
    $_SESSION['mod']=1;
    
    
    
    ?>
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
    <title>Modificar Sujeto</title>
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
    						<h2>Modificar Sujeto</h2>						
    					</header>
    					
    					<div class="box">
    						<form method="post" id="myform" action="crearsujeto.php">
    							Modificar Sujeto<br><br>
    <?php 
    $sql="Select nombre,apellido1,apellido2 FROM sujeto_activo WHERE id_sujeto_activo=$myid_su";
    $resultado=mysqli_query($link, $sql);
    $ret=mysqli_fetch_array($resultado);
    
    
    echo "<br><table><tr><th>Nombre</th><th>Primer Apellido</th><th>Segundo Apellido</th></tr>";
    echo "<tr><td><input type='text' name='nombre' id='nombre' value='$ret[nombre]' required pattern='[a-z A-Z . ÁÉÍÓÚáéíóú Ññ]*'></td>";
    echo "<td><input type='text' name='apellido1' id='apellido1' value='$ret[apellido1]' pattern='[a-z A-Z . ÁÉÍÓÚáéíóú Ññ]*'></td>";
    echo "<td><input type='text' name='apellido2' id='apellido2' value='$ret[apellido2]' pattern='[a-z A-Z . ÁÉÍÓÚáéíóú Ññ]*'></td></tr>";
    echo "</table>";
    ?>
    <div class="col-12">
    <ul class="actions special">
    <li><input type='submit' value='Modificar' /></li>
    <li><input type="button" onclick="location.href='asunto.php';" value="Volver"><br></li>
    
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