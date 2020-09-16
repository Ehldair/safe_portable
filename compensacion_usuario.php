<?php
session_start();

if(isset($_SESSION['id_u'])) {
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe");
    
    $sql=mysqli_query($link, "SELECT apodo from usuario where id_usuario=$_SESSION[id_u]");
    $ret=mysqli_fetch_array($sql);

    $sql="Select dias, t.tipo_compensacion as tipo_compensacion, v.ciudad as viaje, c.descripcion from compensacion c
        inner join tipo_compensacion t ON t.id_tipo_compensacion=c.id_tipo_compensacion
        left join viajes v on v.id_viajes=c.id_viajes
        where id_usuario=$_SESSION[id_u]
        order by fecha_alta_dias ASC";
    echo $sql;
    $result=mysqli_query($link, $sql);
    $count=mysqli_num_rows($result);
    
    $sql_pedidos="Select * from dias_pedidos where id_usuario=$_SESSION[id_u]";
    echo $sql_pedidos;
    $result_pedidos=mysqli_query($link, $sql_pedidos);
    $count_pedidos=mysqli_num_rows($result_pedidos);
    
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Compensaciones</title>
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
    				<h2> Compensaciones</h2>
    				<p>Dias de compensacion totales de <?php echo $ret['apodo']?></p>
    			</header>			
    			<div class="row">
    						<div class="col-12">
    							<!-- Table -->
    								<section class="box">
    									<div class="table-wrapper">
    
    <?php      									  
        if($count!=0) {

        echo "<table>
    	<thead>
        <tr>
        <th>Tipo</th><th>Descripcion</th><th>Viaje</th><th>Dias</th>
        </tr>
        </thead>
        <tbody>";
        $dias_totales=0;
        $dias_gastados=0;
        while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $dias=$line['dias'];
            $tipo=$line['tipo_compensacion'];
            $descripcion=$line['descripcion'];
            $viaje=$line['viaje'];

        $dias_totales=$dias_totales+$dias;
        echo "<tr><td>".$tipo."</td>";
        echo "<td>".$descripcion."</td>";
        echo "<td>".$viaje."</td>";
        echo "<td>".$dias."</td>";
        echo "</tr>";
        
        }
                                          
        echo "</div>";
        
        }
        else {
            echo "<div align='center'>No hay compensaciones</div><br>";
        }
        if($count_pedidos!=0) {
            while ($line_pedidos = mysqli_fetch_array($result_pedidos, MYSQLI_ASSOC)) {
                $dias=$line_pedidos['dias'];
                $dias_gastados=$dias_gastados+$dias;
                
            }
            echo "<tr><td>Días gastados</td><td></td><td></td><td>".$dias_gastados."</td></tr>";
            $dias_totales=$dias_totales-$dias_gastados;
            echo "<tr> <td></td><td></td><td></td><td>Dias Totales: ".$dias_totales."</td></tr>";
        }

    }
else {
    echo "Error";
}
echo "</div></table>";

?>
		<div align='center'>  
		<?php if($count_pedidos!=0) {?>
		<input type="button" onclick="location.href='dias_pedidos.php';" value="Ver Días Pedidos">
		<?php }?>
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