<?php
session_start();

if(isset($_SESSION['id_u'])) {
 
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    if(isset($_GET['id_su'])) {
        $myid_su=base64_decode(mysqli_real_escape_string($link,$_GET['id_su']));
        $_SESSION['id_su']=$myid_su;
    }
    else {
        if(isset($_SESSION['id_su'])) {
            $myid_su=$_SESSION['id_su'];
        }
    }
    
    $respuesta=$_SESSION['respuesta'];
    
    ?>
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
    <title>Detalle Sujeto</title>
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
    			var c = document.getElementById("mensajes");
    			var ctx = c.getContext("2d");
    			ctx.font = "bold 12px Verdana";
    			if(respuesta==1) {
    				ctx.strokeStyle = "#3DBA26";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#3DBA26";
    				ctx.textAlign = "center";
    				ctx.fillText("Sujeto modificado",150,20);
    			}
    }
    </script>
    <script type="text/javascript">
    	function pregunta(){ 
    		if (confirm('¿Estas seguro de eliminar el sujeto?')){ 
    			$.ajax({
    				type: "POST",
    				url: "eliminarsujeto.php",
    				contentType: false,
    				processData: false,
    				success: function(respuesta) {
    					window.location="asunto.php";
    				}
    			});
       		}
     		else {
     			location.href = "asunto.php";
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
    						<h2>Detalles Sujeto</h2>						
    					</header>
    					<div align="center">
    						<canvas id="mensajes" width="300" height="30"></canvas>
    					</div>
    					<div class="box">
    						<form method="post" id="myform" action="modificarsujeto.php">
    							<h3>Detalles de Sujeto</h3>
    <?php 
    $sql="Select nombre,apellido1,apellido2 FROM sujeto_activo WHERE id_sujeto_activo=$myid_su";
    $resultado=mysqli_query($link, $sql);
    
   
	echo "
								<table>
									<thead>
										<tr>
											<th>Nombre</th>
                                            <th>Primer Apellido</th>
											<th>Segundo</th>
                                                                                      
										</tr>
									</thead>
									<tbody>
			
	";
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        echo "<tr>";
        foreach ($line as $col_value) {
            echo "<td>".$col_value."</td>";
            }
        echo "</tr>";
    }
    echo "</tbody></table>";
    
    $sql_borrar="Select * from intervencion where id_sujeto_activo=$myid_su";
    $resultado_borrar=mysqli_query($link, $sql_borrar);
    $count_borrar=mysqli_num_rows($resultado_borrar)
    
    ?>
    <div class="col-12">
    <ul class="actions special">
    
    <li><input type='submit' value='Modificar' />
    <?php
    if ($count_borrar==0) {
        echo "<li><input type='button' onclick='pregunta();' value='Eliminar'><br></li>";
    }
    else {
        echo "<li><input type='button' onclick='location.href='asunto.php';' value='No se puede eliminar' disabled><br></li>";
        
    }
    ?>
    <li><input type="button" onclick="location.href='asunto.php';" value="Volver"><br></li>
    
    </ul>
    </div>		
    
    
    
    
    </form>
    <?php 
    
    
    
    //cargo la lista de intervenciones en las que es el sujeto activo
    $sql="Select distinct i.id_intervencion as id_int, t.nombre as nom, i.direccion as dir, i.descripcion as des FROM intervencion i 
    INNER JOIN tipo_intervencion t on t.id_tipo_intervencion=i.id_tipo_intervencion
    WHERE id_sujeto_activo=$myid_su";
    $resultado_intervencion=mysqli_query($link, $sql);
    $count=mysqli_num_rows($resultado_intervencion);
    if($count!=0) {
        echo "<h3>Intervenciones en las que participa</h3>";
        echo "
								<table>
									<thead>
										<tr>
											<th>Tipo</th>
                                            <th>Dirección</th>
											<th>Descripcion</th>
                                                                                        
										</tr>
									</thead>
									<tbody>
			
	";
		$contador=0;
        while ($line_intervencion = mysqli_fetch_array($resultado_intervencion, MYSQLI_ASSOC)) {
            echo "<tr>";
            foreach ($line_intervencion as $col_value) {
                if ($contador == 0) {
                    $id_intervencion=base64_encode($col_value);
                    $contador++;
                }
                else {
                    if ($contador == 1) {
                        echo "<td>";
                        echo "<a href='detalle_intervencion.php?id_intervencion=$id_intervencion'>".$col_value."</a>"; 
                        echo "</td>";
                        $contador ++;
                    }
                    else {
                        if ($contador < 3) {
                            echo "<td>";
                            echo $col_value;
                            echo "</td>";
                            $contador ++;
                        }
                        else {
                            echo "<td>";
                            echo $col_value;
                            echo "</td>";
                            $contador = 0;
                        }
                    }
                }
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    }
}
else {
    echo "Error";
}
?>
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