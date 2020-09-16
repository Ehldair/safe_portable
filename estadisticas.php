<?php

session_start();


$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}
?>


<!DOCTYPE html>
<html lang="es-ES">
<head>
 <title>Estadísticas</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
	

			
	<!-- Alonso -->
		<script src="//code.jquery.com/jquery-latest.js"></script>
		<script src="miscript.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script src="js/jquery-3.4.1.js"></script>
  
  
  <script type="text/javascript">
	function accion(opSelect){
	
		  var informes=$("#estadisticas").val();
		  var url="obtener_datostipo_infor_est.php";

		       $.ajax({

		         url:url,
		         type:"POST",
		         data:{informes:informes}

		       }).done(function(data){

		             $("#casos").html(data);
		       })    
		   };
	

   	
	  function informe_datos() {
		  var lista = document.getElementById("estadisticas");
		// Obtener el valor de la opción seleccionada
		  var valorSeleccionado = lista.options[lista.selectedIndex].value;

		  var url=valorSeleccionado+"_datos.php";
		 
		  var form = document.getElementById("informedatos");
		  form.method = "post";
		  form.action = url;
		  form.submit();
				};
		
		
	  </script>
	  
   
		   
	  
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
    								</ul>	
    							</li>
    							
    							
    							<li><a href="login.php" class="button">Cerrar</a></li>
    						</ul>
    					</nav>
    				</header>
		
				<section id="main" class="container">
					<header><h2>Estadisticas</h2></header>
					<div class="box">
						<div class="col-12 col-12-mobilep" style="text-align:center">
						<h4>Listado de estadisticas predefinidas</h4>
							<br>
							 <form method="post" name="informedatos" id="informedatos" action="javascript:informe_datos();">
							           
							   <h5>Selecciona Estadistica predefinida</h5>
    							    <br>
    							     <div>
        							     
        							     <select name='estadisticas' id='estadisticas' onchange="accion(this.value);">
            							     <option value=0>Estadistica</option>
            							     <option value='EGC'> Estadistica General por Caso </option>
            							     <option value='EGI'> Estadistica General por Intervencion </option>
            							     <option value='EGEA'> Estadistica General de Evidencias por Año</option>
            							  </select>
    							     </div>
    							    
    							     <br>
    							    
    							     <div>
        							     <br>
        							     <h5>Selecciona Caso</h5>
        							     <br>
            							    <select id='casos' name='casos' onchange="accion_casos(this.value);">
                							<option value=0>Casos</option>
                							</select>
                                     </div>
                                    
                                     <br>
                                    
                                                                         
                             		<hr>       
                   				<div class="col-4 col-12-narrower">
        								
        								</div>															
        								
        								<div class="col-12">	
        									<ul class="actions special">
        										<li> <input type="submit"  value="Estadistica" class="estilo"> </li>
        										<li> <input type="button" onclick="location.href='inicio.php';" value="Volver" class="estilo"> </li>							
        									</ul>						
        						
        								</div>
        								
        								
        						
        					    					    
        					  	</form>	 
        				</div> <!-- <div class="col-12 col-12-mobilep" style="text-align:center"> -->
        					    
					  </div>	
				</div>
				
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

    
