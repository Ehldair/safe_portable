<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas



$link = mysqli_connect("localhost", "root", ".google.", "safe");

$myaño=mysqli_real_escape_string($link,$_POST["año"]);

$_SESSION['año']=$myaño;

$sql="select v.id_usuario as id_usuario,u.apodo as apodo,COUNT(v.id_usuario) as cuenta from viajes_funcionario v inner join usuario u on u.id_usuario=v.id_usuario WHERE YEAR(fecha_inicio)=$myaño
GROUP BY v.id_usuario order by cuenta";
$result=mysqli_query($link, $sql);
$count=mysqli_num_rows($result);

$sql="select u.apodo, u.id_usuario
    from usuario u
    LEFT JOIN viajes_funcionario v ON v.id_usuario=u.id_usuario and YEAR(fecha_inicio)=$myaño
    where v.id_usuario IS NULL";
$result_nulos=mysqli_query($link, $sql);

?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
 <title>Casos</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
	
			
	<!-- Alonso -->
		<script src="//code.jquery.com/jquery-latest.js"></script>
		<script src="miscript.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script src="js/jquery-3.4.1.js"></script>

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
				<h2> Viajes</h2>
				<p>Viajes Totales <?php echo $myaño;?></p>
			</header>			
			<div class="row">
						<div class="col-12">
							<!-- Table -->
								<section class="box">
									<div class="table-wrapper">

<?php 

if($count!=0) {

    echo "
								<table>
									<thead>
										<tr>
											<th>Funcionario</th>
                                            <th>Total Viajes Año</th>
										
									         <th><select id='año' name='año' onchange='cambiaraño()'>";
                                            $resultado = mysqli_query($link, "select año FROM año_viajes order by año");
                                            while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                                                foreach ($line as $col_value) {
                                                    if($col_value==$myaño) {
                                                        echo "<option value='$col_value' selected>$col_value</option>'";
                                                      
                                                    }
                                                    else {
                                                        echo "<option value='$col_value'>$col_value</option>'";    								        
                                                    }
                                                }
                                            }   
                                            echo "</select></th>                                       
                                                </tr>
									       </thead>
                                        <tbody>";
  
    $contador=0;
    while ($line = mysqli_fetch_array($result_nulos, MYSQLI_ASSOC)) {
        $apodo=$line['apodo'];
        $id_usuario=$line['id_usuario'];
        $id_funcionario=base64_encode($id_usuario);
        echo "<tr>";
        echo "<tr>";
        echo "<td align='left'><a href='viajes_funcionario.php?id_funcionario=$id_funcionario'>$apodo</a></td>";
        echo "<td align='left'>0</td>";
        echo "<td></td>";
        echo "</tr>";
    }
    while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $id_usuario=$line['id_usuario'];
        $apodo=$line['apodo'];
        $cuenta=$line['cuenta'];
        $id_funcionario=base64_encode($id_usuario);
        echo "<tr>";
        echo "<td align='left'><a href='viajes_funcionario.php?id_funcionario=$id_funcionario'>$apodo</a></td>";
        echo "<td align='left'>$cuenta</td>";
        echo "<td></td>";
        echo "</tr>";
    }
}
else {
    echo "
								<table>
									<thead>
										<tr>
											<th></th>
                                            <th></th>
		    
									         <th><select id='año' name='año' onchange='cambiaraño()'>";
                                            $resultado = mysqli_query($link, "select año FROM año_viajes order by año");
                                            $count=mysqli_num_rows($resultado);
                                            $contador=1;
                                            while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                                                foreach ($line as $col_value) {
                                                    if($col_value==$myaño) {
                                                        echo "<option value='$col_value' selected>$col_value</option>'";
                                                        $contador++;
                                                    }
                                                    else {
                                                        echo "<option value='$col_value'>$col_value</option>'";
                                                    }
                                                }
                                            }   
                                            echo "</select></th>                                       
                                                </tr>
									       </thead>
                                        <tbody>";

    echo "<tr><td></td><td align='center'>";
    echo "No hay viajes disponibles";
    echo "</td><td></td></tr>";
}
    ?>
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

