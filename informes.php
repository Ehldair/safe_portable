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
 <title>Informes</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
	<script src="//code.jquery.com/jquery-latest.js"></script>
	<script src="miscript.js"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
	<script src="js/jquery-3.4.1.js"></script>
    <script type="text/javascript">

	
	function accion(opSelect){
	
		  var informes=$("#informes").val();
		  var url="obtener_datostipo_infor_est.php";

		       $.ajax({

		         url:url,
		         type:"POST",
		         data:{informes:informes}

		       }).done(function(data){

		             $("#casos").html(data);
		       })    
		   };
	
	
	function accion_casos(opSelect){
	
		var casos=$("#casos").val();
		$('#div-results').load('obtener_caso_intervencion.php?id_caso='+casos);                
		
		
	};
		   
		 
	  function informe_datos() {
		  var lista = document.getElementById("informes");
		// Obtener el valor de la opción seleccionada
		  var valorSeleccionado = lista.options[lista.selectedIndex].value;

		  var lista_caso = document.getElementById("casos");
			// Obtener el valor de la opción seleccionada
			  var valorSeleccionado_caso = lista_caso.options[lista_caso.selectedIndex].value;

			if (valorSeleccionado ==0 || valorSeleccionado_caso ==0)
			{
				alert ("sain");
			}
			else
			{
		  var url=valorSeleccionado+"_datos.php";
		 
		  var form = document.getElementById("informedatos");
		  form.method = "post";
		  form.action = url;
		  form.submit();
			}
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
    											<li><a href="modificarusuario.php">Gestión</a></li>
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
					<header><h2>Informes</h2></header>
					<div class="box">
						<div class="col-12 col-12-mobilep" style="text-align:center">
						<h4>Listado de informes predefinidos</h4>
							<br>
										
							 <form method="post" name="informedatos" id="informedatos" action="javascript:informe_datos();">
                                       
							   <h5>Selecciona Informe predefinido</h5>
    							    <br>
    							     <div>
        							     <select name='informes' id='informes' onchange="accion(this.value);">
            							     <option value=0>Informes</option>
            							     <option value='IGACB'> Informe General por Caso- Acto Clonado Base </option>
            							     <option value='IGIACB'> Informe General por Intervencion - Acto de Clonado Base</option>
            							     <option value='IGC'> Informe General - Caso </option>
            							     <option value='IDC'> Informe Detallado - Caso </option>
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
                   	
        					         <!-- Lista de evidencias -->	
            						<div id='div-results'>
            							<h3><b>Evidencias del caso</b> </h3> 
            							
                                   </div> <!-- <div id='div-results'> -->
        					
                            	 <div class="col-12">
        							<ul class="actions special small">
        								<li> <input type='submit'  value='Informe' class='estilo'> </li>
                                    </ul>
                                </div>	
                   			</form>
				</div>
				</div> <!-- <div class="box"> -->
				
			</section>
	</div>
</body>
</html>

    
