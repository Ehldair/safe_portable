<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe");

// comprobar la conexiï¿½n
if (mysqli_connect_errno()) {
    printf("Fallo la conexion: %s\n", mysqli_connect_error());
    exit();
}
if(isset($_SESSION['id_u'])) {
    
    $sql = "select id_viajes, t.nombre as t_nom, c.nombre as c_nom, ciudad, v.descripcion as descr, c.año as año from viajes v
    inner join caso c on c.id_caso=v.id_caso
    inner join transporte t on t.id_transporte=v.id_transporte
    where año=YEAR(CURDATE())
    ORDER BY AÑo DESC, fecha_alta_caso DESC";
    
    $resultado=mysqli_query($link, $sql);
    $count=mysqli_num_rows($resultado);
    
    $respuesta=$_SESSION['respuesta'];
    
    ?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
 <title>Viajes</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
	
	
			
	<!-- Alonso -->
		<script src="//code.jquery.com/jquery-latest.js"></script>
		<script src="miscript.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script src="js/jquery-3.4.1.js"></script>
		
		<script>
    
    function cambiaraño() {
    	var año=document.getElementById("año").value;
        var parametros={"año":año};
        $.ajax({
            type: "POST",
            url: "obtenertodosviajes.php",
            data: parametros,
            success: function(response) {
                $('#div-results').html(response);                
            }
        });
    }
    
    </script>

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
	
<div id='div-results'>
	<div id="page-wrapper">

	<!-- Main -->
		<section id="main" class="container">
			<header>
				<h2> Viajes</h2>
			</header>			
			<div align="center">
		    	<canvas id="mensaje" width="300" height="30"></canvas>
		   	</div>
			<div class="row">
						<div class="col-12">
							<!-- Table -->
								<section class="box">
									<div class="table-wrapper">
										<h3>Gestión Viajes</h3>

<?php 

if($count!=0) {

    echo "
								<table>
									<thead>
                                        <tr>
                                            <th><select name='año' id='año' onchange='cambiaraño();'>";
                                            $resultado_año = mysqli_query($link, "select año FROM año_viajes order by año");
                                                $count=mysqli_num_rows($resultado_año);
                                                $contador=1;
                                                while ($line = mysqli_fetch_array($resultado_año, MYSQLI_ASSOC)) {
                                                    foreach ($line as $col_value) {
                                                        if($contador!=$count) {
                                                            echo "<option value='$col_value'>$col_value</option>'";
                                                            $contador++;
                                                        }
                                                        else {
                                                            echo "<option value='$col_value' selected>$col_value</option>'";    								        
                                                        }
                                                    }
                                                }   
                                                echo "</select></th>   
                                        </tr>
										<tr>
											<th>Ciudad</th>
                                            <th>Año</th>
                                            <th>Operacion</th>
											<th>Descripcion</th>
                                            <th>Transporte</th>
                                            
										</tr>
									</thead>
									<tbody>
			
	";

    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $id_viajes= $line['id_viajes'];
        $nombre_transporte= $line['t_nom'];
        $nombre_caso= $line['c_nom'];
        $ciudad= $line['ciudad'];
        $descripcion= $line['descr'];
        $año=$line['año'];
        
        
        
        $id_viajes=base64_encode($id_viajes);
        ?>
        <tr>
        <td>
        <a href="detalle_viaje.php?id_viajes=<?php echo $id_viajes;?>" style="color: black" >
    		<b><?php echo $ciudad?></b>
    	</a>
        </td>
        <td>
        <?php
        echo $año;
        ?>   
        </td>
        <td>
        <?php
        echo $nombre_caso;
        ?>
        </td>
        <td>
        <?php
        echo $descripcion;
        ?>
        </td>
        <td>
        <?php
        echo $nombre_transporte;
        ?>
        </td>
        </tr>
        <?php 
    
    }
}  // if
else {
    echo "<br><b>";
    echo "<br>NO EXISTEN CASOS";
    echo "</b>";
}

}
?>

			</div>
	
		
	</section>
	</div>
	</div>
	</section>	
	</div>
	</div>
</body>

<!-- Pelayo -->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/jquery.dropotron.min.js"></script>
		<script src="assets/js/jquery.scrollex.min.js"></script>
		<script src="assets/js/browser.min.js"></script>
		<script src="assets/js/breakpoints.min.js"></script>
		<script src="assets/js/util.js"></script>
		<script src="assets/js/main.js"></script>
		
</html>