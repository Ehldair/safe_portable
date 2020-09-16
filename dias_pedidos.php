<?php
session_start();

if(isset($_SESSION['id_u'])) {
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe");
    
    $sql=mysqli_query($link, "SELECT apodo from usuario where id_usuario=$_SESSION[id_u]");
    $ret=mysqli_fetch_array($sql);

    
    $sql_pedidos="Select dias,descripcion, date_format(fecha_desde, '%d/%m/%Y') as fecha_desde from dias_pedidos where id_usuario=$_SESSION[id_u]";
    echo $sql_pedidos;
    $result_pedidos=mysqli_query($link, $sql_pedidos);
    $count_pedidos=mysqli_num_rows($result_pedidos);
    
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Dias Gastados</title>
    	<meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    	<link rel="stylesheet" href="assets/css/main.css" />
    	

    			
    	<!-- Alonso -->
    		<script src="//code.jquery.com/jquery-latest.js"></script>
    		<script src="miscript.js"></script>
    		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    		<script src="js/jquery-3.4.1.js"></script>
    
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
    				<h2> Dias Gastados</h2>
    				<p>Dias de compensacion totales gastados por <?php echo $ret['apodo']?></p>
    			</header>			
    			<div class="row">
    						<div class="col-12">
    							<!-- Table -->
    								<section class="box">
    									<div class="table-wrapper">
    
    <?php      									  
        if($count_pedidos!=0) {

        echo "<table>
    	<thead>
        <tr>
        <th>Fecha Inicio</th><th>Descripcion</th><th>Dias</th>
        </tr>
        </thead>
        <tbody>";
        $dias_totales=0;
        while ($line = mysqli_fetch_array($result_pedidos, MYSQLI_ASSOC)) {
            $dias=$line['dias'];
            $fecha_inicio=$line['fecha_desde'];
            $descripcion=$line['descripcion'];

        $dias_totales=$dias_totales+$dias;
        echo "<tr><td>".$fecha_inicio."</td>";
        echo "<td>".$descripcion."</td>";
        echo "<td>".$dias."</td>";
        echo "</tr>";
        
        }
                                          
        echo "</div>";
        echo "<tr><td></td><td></td><td>Días totales gastados: $dias_totales</td>";
        }
        
        else {
            echo "<div align='center'>No hay compensaciones</div><br>";
        }  
    }
else {
    echo "Error";
}
echo "</div></table>";

?>
		<div align='center'>  
		<input type="button" onclick="location.href='compensacion_usuario.php';" value="Ver Compensaciones">
        <input type="button" onclick="location.href='inicio.php';" value="Volver"><br>
        </div>

   </div>
   </section>
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
    </html>