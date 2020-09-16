<?php

session_start();

if(isset($_SESSION['id_u'])) {
    

    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    if(isset($_GET['id_registro'])) {
        $myid_registro=base64_decode($_GET['id_registro']);
        $_SESSION['id_registro']=$myid_registro;
    }
    else {
        if(isset($_SESSION['id_registro'])) {
            $myid_registro=$_SESSION['id_registro'];
        }
    }
    $category=$myid_registro;
    
    $respuesta=$_SESSION['respuesta'];
    
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
    <title>Detalles Registro</title>
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
    			if(respuesta==1) {
    				var c = document.getElementById("mensaje");
    				var ctx = c.getContext("2d");
    				ctx.font = "bold 12px Verdana";
    				ctx.clearRect(0, 0, c.width, c.height);
    				ctx.strokeStyle = "#3DBA26";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#3DBA26";
    				ctx.textAlign = "center";
    				ctx.fillText("Estado modificado",150,20);
    				<?php $_SESSION['respuesta']=0; ?>
    			}
    		}
    </script>
    <script type="text/javascript">
    	function pregunta(opSelect){
    		var category=opSelect; 
    		var url="eliminarregistro.php";
    		if (confirm('¿Estas seguro de eliminar el registro?')){ 
    			$.ajax({
    				url:url,
    		        type:"POST",
    		        data:{category:category}

    		      }).done(function(data){
						
    		    	  location.href = "detalle_evidencia.php";   
    		      });  
       		}
     		else {
     			location.href = "detalle_evidencia.php";
     	 	} 	
    	} 
    	</script> 
    
    
    </head>
    
    
    <body class="is-preload" onload="respuesta();">
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
    						<h2>Detalle Registro</h2>						
    					</header>
    					<div align="center">
    						<canvas id="mensaje" width="300" height="30"></canvas>
    					</div>
    					<div class="box">
    						<form method="post" id="myform" action="modificarestado.php">
    							<h3>Detalles del registro de estado de la evidencia</h3>
    <?php 
    $sql="select u.apodo,es.nombre as estado, p.nombre as programa, ap.nombre as accion_programa, h.hash, er.observaciones, o.nombre_ordenadores
    from evidencia_registro er
    inner join usuario u on er.id_usuario=u.id_usuario
    inner join estado_evidencia es on er.id_estado_evidencia=es.id_estado_evidencia
    left join ordenadores o ON o.id_ordenadores=er.id_ordenadores
    left join programa p on er.id_programa=p.id_programa
    left join accion_programa ap on er.id_accion_programa=ap.id_accion_programa
    left join hash h on er.id_hash=h.id_hash
    where er.id_evidencia_registro=$myid_registro ORDER BY er.fecha_alta_estado ASC";
    $resultado=mysqli_query($link, $sql);
    
    
    
echo "
								<table>
									<thead>
										<tr>
											<th>Apodo</th>
                                            <th>Estado</th>
											<th>Programa</th>
											<th>Acción Programa</th>
                                            <th>Hash</th>
											<th>Observaciones</th>
                                            <th>Ordenador</th>                                            
										</tr>
									</thead>
									<tbody>
			
	";
    $contador=0;
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        echo "<tr>";
        foreach ($line as $col_value) {
            echo "<td>".$col_value."</td>";
        }
        echo "</tr>";
    }
    echo "</tbody></table>";

}
else {
    echo "Error";
}
?>
<div class="col-12">
<ul class="actions special small">

<li><input type='submit' value='Modificar' class="button special small"/>
<li><input type='button' onclick='pregunta(<?php echo $category;?>);' value='Eliminar' class="button special small"></li>
<li><input type="button" onclick="location.href='detalle_evidencia.php';" value="Volver" class="button special small"></li>

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





