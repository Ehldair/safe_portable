<?php
session_start();

if(isset($_SESSION['id_u'])) {
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    if(isset($_GET['id_viajes'])) {
        $myid_viajes=base64_decode(mysqli_real_escape_string($link,$_GET['id_viajes']));
        $_SESSION['id_viajes']=$myid_viajes;
    }
    else {
        if(isset($_SESSION['id_viajes'])) {
            $myid_viajes=$_SESSION['id_viajes'];
        }
    }
    $respuesta=$_SESSION['respuesta'];

    $sql="select id_viajes, t.nombre as t_nom, v.id_transporte as id_transporte, c.nombre as c_nom, ciudad, v.descripcion as descr, c.año as año from viajes v
    inner join caso c on c.id_caso=v.id_caso
    inner join transporte t on t.id_transporte=v.id_transporte
    where id_viajes=$myid_viajes";
    $resultado=mysqli_query($link, $sql);
    $ret=mysqli_fetch_array($resultado);
    
    $sql_funcionarios="select u.apodo, vf.id_usuario, Date_format(fecha_inicio,'%d-%m-%Y') as fecha_inicio, Date_format(fecha_fin,'%d-%m-%Y') as fecha_fin, timestampdiff(DAY, vf.fecha_inicio, vf.fecha_fin) as noches from viajes_funcionario vf
    INNER JOIN usuario u on u.id_usuario=vf.id_usuario where id_viajes=$myid_viajes";
    $resultado_funcionarios=mysqli_query($link, $sql_funcionarios);
    $count_funcionarios=mysqli_num_rows($resultado_funcionarios);
    
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Detalle	 Viaje</title>
    	<meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    	<link rel="stylesheet" href="assets/css/main.css" />
		
    		
    			
    	<!-- Alonso -->
    		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    		<script src="js/jquery-3.4.1.js"></script>
    		
    		<script>
    		function respuesta() {
    			var respuesta=<?php echo $respuesta; ?>;
    			var c = document.getElementById("mensaje");
    			var ctx = c.getContext("2d");
    			ctx.font = "bold 12px Verdana";
    			ctx.clearRect(0, 0, c.width, c.height);	
    			if(respuesta==1) {
    				ctx.strokeStyle = "#3DBA26";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#3DBA26";
    				ctx.textAlign = "center";
    				ctx.fillText("Funcionario eliminado",150,20);
    				<?php $_SESSION['respuesta']=0; ?>
    				setTimeout(borrar,5000);
    			}
    			else {
    				if(respuesta==2) {
    					ctx.strokeStyle = "#3DBA26";
    					ctx.strokeRect(1, 1, 299, 29);
    					ctx.fillStyle = "#3DBA26";
    					ctx.textAlign = "center";
    					ctx.fillText("Viaje modificado",150,20);
    					<?php $_SESSION['respuesta']=0; ?>
    					setTimeout(borrar,5000);
    				}
    				else {
    					if(respuesta==3) {
    						ctx.strokeStyle = "#3DBA26";
    						ctx.strokeRect(1, 1, 299, 29);
    						ctx.fillStyle = "#3DBA26";
    						ctx.textAlign = "center";
    						ctx.fillText("Funcionario añadido",150,20);
    						<?php $_SESSION['respuesta']=0; ?>
    						setTimeout(borrar,5000);					}
    					else {
    						if(respuesta==4) {
    							ctx.strokeStyle = "#3DBA26";
    							ctx.strokeRect(1, 1, 299, 29);
    							ctx.fillStyle = "#3DBA26";
    							ctx.textAlign = "center";
    							ctx.fillText("Intervención añadida",150,20);
    							<?php $_SESSION['respuesta']=0; ?>
    							setTimeout(borrar,5000);
    						}
    						else {
    							if(respuesta==5) {
    								ctx.strokeStyle = "#3DBA26";
    								ctx.strokeRect(1, 1, 299, 29);
    								ctx.fillStyle = "#3DBA26";
    								ctx.textAlign = "center";
    								ctx.fillText("Sujeto eliminado",150,20);
    								<?php $_SESSION['respuesta']=0; ?>
    								setTimeout(borrar,5000);
    							}
    							else {
    								if(respuesta==6) {
    									ctx.strokeStyle = "#3DBA26";
    									ctx.strokeRect(1, 1, 299, 29);
    									ctx.fillStyle = "#3DBA26";
    									ctx.textAlign = "center";
    									ctx.fillText("Intervención eliminada",150,20);
    									<?php $_SESSION['respuesta']=0; ?>
    									setTimeout(borrar,5000);
    								}
    								else {
    									if(respuesta==7) {
    										ctx.strokeStyle = "#3DBA26";
    										ctx.strokeRect(1, 1, 299, 29);
    										ctx.fillStyle = "#3DBA26";
    										ctx.textAlign = "center";
    										ctx.fillText("Evidencia eliminada",150,20);
    										<?php $_SESSION['respuesta']=0; ?>
    										setTimeout(borrar,5000);
    									}
    									else {
    										if(respuesta==8) {
    											ctx.strokeStyle = "#3DBA26";
    											ctx.strokeRect(1, 1, 299, 29);
    											ctx.fillStyle = "#3DBA26";
    											ctx.textAlign = "center";
    											ctx.fillText("Caso creado",150,20);
    											<?php $_SESSION['respuesta']=0; ?>
    											setTimeout(borrar,5000);
    										}
    										else {
        										if(respuesta==9) {
        											ctx.strokeStyle = "#FF0000";
        											ctx.strokeRect(1, 1, 299, 29);
        											ctx.fillStyle = "#FF0000";
        											ctx.textAlign = "center";
        											ctx.fillText("Ya existe la intervención",150,20);
        											<?php $_SESSION['respuesta']=0; ?>
        											setTimeout(borrar,5000);
        										}
        									}
    									}    									
    								}
    							}	
    						}
    					}
    				}
    			}
    		}
    		</script>
    		
    		<script type="text/javascript">
    		function borrar() {
    			var c = document.getElementById("mensaje");
    			var ctx = c.getContext("2d");
    			ctx.clearRect(0, 0, c.width, c.height);
    		};
    		
    		</script>
    		<script type="text/javascript">
    		function preguntaFuncionario(opSelect){ 
        		var category=opSelect;
        		var url="eliminarfuncionario.php";
    			if (confirm('¿Estas seguro de quitar al funcionario de este viaje?')){ 
    				$.ajax({
    					url:url,
    		        	type:"POST",
    		        	data:{category:category}

    		      	}).done(function(data){
						
    		    		  location.href = "detalle_viaje.php";   
    		      	});  
       			}
     			else {
     				
     	 		} 	
    		} 
    	</script> 
    		<script type="text/javascript">
    		function preguntaViaje(){ 
    			if (confirm('¿Estas seguro de eliminar el viaje?')){ 
    				$.ajax({
    					type: "POST",
    					url: "eliminarviaje.php",
    					contentType: false,
    					processData: false,
    					success: function(respuesta) {
    							location.href="viajes.php";
    					}
    				});
       			}
     			else {
     				location.href = "detalle_viaje.php";
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
    				<h2>Viaje <?php echo $ret['ciudad'];?></h2>
    			</header>
    			    			
    			<div class="row">
    						<div class="col-12">
    							
    							<!-- Lists -->
    							<form action='modificarviaje.php' method='post'>
    								<section class="box">
    									<div align="center">
		    								<canvas id="mensaje" width="300" height="30"></canvas>
		    							</div>
    									<div><h2><em><b>Detalles del Viaje</b></em></h2></div>

    									<div class="row">
    										<div class="col-6 col-12-mobilep">
    											<h4></h4>
    											<ul class="alt">
    												
    												<li>Operación: <b><?php echo $ret['c_nom'];?></b></li>
    												
    												<li>Descripción: <b><?php echo $ret['descr'];?></b></li>
    												<li>Transporte: <b><?php echo $ret['t_nom'] ?></b>
    													<input type='hidden' name='ciudad' id='ciudad' value='<?php echo $ret['ciudad'];?>'>
    													<input type='hidden' name='nombre_caso' id='nombre_caso' value='<?php echo $ret['c_nom'];?>'>
    													<input type='hidden' name='nombre_transporte' id='nombre_transporte' value='<?php echo $ret['t_nom'];?>'>
    													<input type='hidden' name='id_transporte' id='id_transporte' value='<?php echo $ret['id_transporte'];?>'>
    													<input type='hidden' name='descripcion' id='descripcion' value='<?php echo $ret['descr'];?>'>
    													<input type='hidden' name='año' id='año' value='<?php echo $ret['año'];?>'>
    												</li>
    												<li>
    													Año: <b><?php echo $ret['año'] ?></b>
    													
    												</li>
    											</ul>	
    											
    												
    
    									</div>
    									
    									<!--  Botones -->
    									<div class="col-12">
    										<ul class="actions special">										  
    											<li><br><input type='submit' value='Modificar' /></li>
    											<?php
    											if($count_funcionarios==0) {
    											    echo "<li><br><input type='button' onclick='preguntaViaje();' value='Eliminar'><br></li>";
    											}
                                                ?>
    											<li><br><input type="button" onclick="location.href='viajes.php';" value="Volver"><br></li>
    										  							
    										</ul>
    										

    									</div>	
    								
    									
    										
    									<!-- Lista de funcionarios -->	
    									<div class="col-12 col-12-mobilep">
    									<h3><b>Funcionarios del viaje</b> <a href="nuevofuncionario.php"> <img src="img/usuario.png"> </a></h3> 
    									
    									
    									
    
    <?php
									
    								if ($count_funcionarios != 0) {
    

    ?>								  

								<table>
									<thead>
										<tr>
											<th>Nombre</th>
                                            <th>Desde</th>
                                            <th>Hasta</th>
                                            <th>Noches</th>
                                            <th></th>
										</tr>
									</thead>
									<tbody>


<?php


    
    
    
        while ($line_funcionarios = mysqli_fetch_array($resultado_funcionarios, MYSQLI_ASSOC)) {
            $apodo=$line_funcionarios['apodo'];
            $fecha_inicio=$line_funcionarios['fecha_inicio'];
            $fecha_fin=$line_funcionarios['fecha_fin'];
            $noches=$line_funcionarios['noches'];
            $id_usuario=$line_funcionarios['id_usuario'];
            $id_funcionario=base64_encode($id_usuario);
            
            echo "<tr>";   
            echo "<td>";
            echo "<a href='viajes_funcionario.php?id_funcionario=$id_funcionario'>$apodo</a>";
            echo "</td>";
            echo "<td>".$fecha_inicio."</td>";
            echo "<td>".$fecha_fin."</td>";
            echo "<td>".$noches."</td>";
            echo "<td><a href='#' onclick='preguntaFuncionario($id_usuario);'>
    	                    <img src='img/eliminar.png' alt='Enlace' width=20 height=20/>
    	                    </a></td>";
            echo "</tr>";
        }
            

?>
 
    	
<?php        
    } else {
    
        ?>
        <ul>
    		<li>No hay funcionarios añadidos a este viaje</li>										
    	</ul>  
        
        
    <?php 
    }										
    
    ?>										
    									
    										</tbody>
    									</table>
    								</div>
    							
    
    
    
    

    
    
    <?php
    
    mysqli_close($link);
}
else {
    echo "Error";
}
?>
										</div>									
    								</section>
    							</form>	
							</div> <!-- row -->
						</div> <!--coll12 -->
						</section>
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

