<?php
session_start();

if(isset($_SESSION['id_u'])) {
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    
    if(isset($_SESSION['id_caso'])) {
        $myid_caso=$_SESSION['id_caso'];
    }
    
    $resultado = mysqli_query($link, "SELECT c.numero as num_caso, c.año as año_caso, c.nombre as nom_caso FROM caso c WHERE c.id_caso=$myid_caso");
    $ret = mysqli_fetch_array($resultado);
    
    // cargo lista de sujetos
    
    $resultado_intervencion = mysqli_query($link, "select id_intervencion,numero_intervencion,direccion from intervencion where id_caso=$myid_caso");
    $ret_intervencion=mysqli_fetch_array($resultado_intervencion);
    $id_intervencion_real=$ret_intervencion['id_intervencion'];
    $resultado_intervencion2 = mysqli_query($link, "select id_intervencion,numero_intervencion,direccion from intervencion where id_caso=$myid_caso");
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Configuracion Hoja de Evidencias</title>
    	<meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    	<link rel="stylesheet" href="assets/css/main.css" />
		
    		
    			
    	<!-- Alonso -->
    		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    		<script src="js/jquery-3.4.1.js"></script>
    		
    		<script type="text/javascript">
			function cabecera(){
    			
    			$('#cabecera').load('cabecera.php');                
    			inicio();
    			
    		};
    		function inicio(){ 
        		document.getElementById("intervencion").style.display = 'none';
        		document.getElementById("intervenciones").disabled='true';
        		document.getElementById("todas_int").disabled='true';
        		document.getElementById("no_todas_int").disabled='true';
        		document.getElementById("datos_mostrar").style.display = 'none';
        		document.getElementById("entera_evidencias").style.display = 'none';
        		document.getElementById("datos_evidencias_mostrar").style.display = 'none';
        		document.getElementById("lista_evidencias").style.display = 'none';
        		document.getElementById("evidencias").style.display = 'none';			
        		document.getElementById("todas_ev").disabled= true;
        		document.getElementById("no_todas_ev").disabled= true;
        		
    		} 
    		function esconder(){ 
        		document.getElementById("intervencion").style.display = 'none';
        		document.getElementById("datos_mostrar").style.display = 'none';
        		document.getElementById("entera_evidencias").style.display = 'none';
        		document.getElementById("datos_evidencias_mostrar").style.display = 'none';
        		document.getElementById("lista_evidencias").style.display = 'none';	
        		document.getElementById("intervenciones").disabled='true';
        		document.getElementById("todas_int").disabled='true';
        		document.getElementById("no_todas_int").disabled='true';
        		document.getElementById("todos_datos_ev").disabled= 'true';
        		document.getElementById("no_todos_datos_ev").disabled= 'true';
        		document.getElementById("todas_ev").disabled= 'true';
        		document.getElementById("no_todas_ev").disabled= 'true';
        		document.getElementById("caso_num").checked= false;
        		document.getElementById("fecha").checked= false;	
        		document.getElementById("diligencias").checked= false;	
        		document.getElementById("juzgado").checked= false;
        		document.getElementById("detenido").checked= false;	
        		document.getElementById("direccion").checked= false;			
        		document.getElementById("todos_datos_ev").checked= false;
        		document.getElementById("no_todos_datos_ev").checked= false;
        		document.getElementById("todas_int").checked=false;
   				document.getElementById("no_todas_int").checked=false;
       		} 
    		function desplegar(){ 
        		document.getElementById("intervencion").style.display = 'block';
        		document.getElementById("intervenciones").disabled=false;
        		document.getElementById("todas_int").disabled=false;
   				document.getElementById("no_todas_int").disabled=false;
    		} 
 
    		function todosdatos() {
    			document.getElementById("datos_mostrar").style.display = 'none';	
   				document.getElementById("entera_evidencias").style.display = 'block';	
   				document.getElementById("todos_datos_ev").disabled= false;
        		document.getElementById("no_todos_datos_ev").disabled= false;
   				document.getElementById("caso_num").checked= false;
        		document.getElementById("fecha").checked= false;	
        		document.getElementById("diligencias").checked= false;	
        		document.getElementById("juzgado").checked= false;
        		document.getElementById("detenido").checked= false;	
        		document.getElementById("direccion").checked= false;	
    		}
    		function seleccion_datos() {
   				document.getElementById("datos_mostrar").style.display = 'block';	
   				document.getElementById("entera_evidencias").style.display = 'block';		    			
    		} 
    		function todosdatos_evidencias() {
    			document.getElementById("datos_evidencias_mostrar").style.display = 'none';
    			document.getElementById("lista_evidencias").style.display = 'block';
    			document.getElementById("todas_ev").disabled=false;
    			document.getElementById("no_todas_ev").disabled= false;
    			document.getElementById("marca_modelo").checked= false;
   				document.getElementById("ns").checked= false;
        		document.getElementById("capacidad").checked= false;	
        		document.getElementById("clonado").checked= false;	
        		document.getElementById("sistema").checked= false;
        		document.getElementById("patron").checked= false;	
        		document.getElementById("pin").checked= false;
        		document.getElementById("observaciones").checked= false;
        		document.getElementById("oculto").style.display='block';
        		
    		}
    		function seleccion_datos_evidencias() {
    			document.getElementById("datos_evidencias_mostrar").style.display = 'block';	
    			document.getElementById("lista_evidencias").style.display = 'block';
    			document.getElementById("oculto").style.display='none';
    			document.getElementById("todas_ev").disabled=false;
    			document.getElementById("no_todas_ev").disabled= false;
    		}
    		function todas() {
    			document.getElementById("evidencias").style.display = 'none';
    		}
			function seleccion_evidencias() {
				document.getElementById("evidencias").style.display = 'block';
				document.getElementById("evidencias").disabled = false;
			}
			function cambiar_intervencion(value) {
				 
				var category=value;
				var url="obtenerevidencias_hoja.php";

						$.ajax({
							url:url,
				            type:"POST",
				            data:{category:category}

						}).done(function(data){
				    	    
	    	                $("#evidencias").html(data);
						})
			}
    	</script> 
    
    </head>
    
    <body class="is-preload" onload="cabecera();">
    	<div id="page-wrapper">
	<!-- Header -->
    <div id="cabecera">
    
    </div>

    	<!-- Main -->
    		<section id="main" class="container">
    			<header>
    				<h2>Caso <?php echo $ret['num_caso'];?>_<?php echo substr($ret['año_caso'], 2);?></h2>
    				<h3><p>Operación <?php echo $ret['nom_caso'];?></p></h3>
    			</header>
    			    			
    			<div class="row">
    				<div class="col-12"><!-- Lists -->
    					<form action='crearhoja.php' method='post'>
    						<section class="box">
    							<div class="row">
    								<div class="col-12 col-12-mobilep">	
    									<h4>Escoge los datos a mostrar en la hoja de evidencias:</h4>
    								</div>
    								<div class="col-3 col-12-mobilep"></div>	
                					<div class="col-2 col-12-mobilep">	
                						<input type='radio' name='entera' id='entera' value='1' onchange="esconder()" required>
                						<label for='entera'>Entera</label>
                					</div>
                					<div class="col-6 col-12-mobilep">	
                						<input type='radio' name='entera' id='no_entera' value='2' onchange="desplegar()" required>
                						<label for='no_entera'>Elegir opciones</label>
                					</div>
									<div class="col-3 col-12-mobilep"></div>
									<div id="intervencion" class="col-6 col-12-mobilep">
                        						<br>
                        						Selecciona una intervencion
                        						<select id="intervenciones" name="intervenciones" onchange="cambiar_intervencion(this.value)" required>
                        						<option value="">Selecciona una intervencion</option>
                        						<?php 
                                                                    while ($line = mysqli_fetch_array($resultado_intervencion2, MYSQLI_ASSOC)) {
                                                                        $id_intervencion=$line['id_intervencion'];
                                                                        $numero=$line['numero_intervencion'];
                                                                        $direccion=$line['direccion'];
                                                                        echo "<option value=$id_intervencion>$numero - $direccion</option>";
                    
                                                                    } ?>  
        										</select>
                        				
                    					
                    					<br>
                    						Datos a mostrar:
                    						<br>
                        					<input type='radio' name='todos_datos' id='todas_int' value='1' onchange="todosdatos()" required>
                        					<label for='todas_int'>Todos</label>
                        				
                        					<input type='radio' name='todos_datos' id='no_todas_int' value='2' onchange="seleccion_datos()" required>
                        					<label for='no_todas_int'>Escoger datos</label>

                    				</div>
                    				<div class="col-3 col-12-mobilep"></div>
                    				<div class="col-9 col-12-mobilep"></div>		
                    				<div id="datos_mostrar" align="center"	>
											Selecciona datos:                    					
                    						<br>
                    						<input type='checkbox' name='caso_num' id='caso_num' value="caso_num">
                    						<label for='caso_num'>Caso</label>
                    					
                    					
                    						<input type='checkbox' name='fecha' id='fecha' value="fecha"> 
                    						<label for='fecha'>Fecha</label>
                    					
                    					
                    						<input type='checkbox' name='diligencias' id='diligencias' value="diligencias">
                    						<label for='diligencias'>Diligencias</label>
                    					
                    						<input type='checkbox' name='juzgado' id='juzgado' value="juzgado">
                    						<label for='juzgado'>Juzgado</label>
                    					
                    						<input type='checkbox' name='detenido' id='detenido' value="detenido">
                    						<label for='detenido'>Detenido</label>
                    					
                    						<input type='checkbox' name='direccion' id='direccion' value="direccion">
                    						<label for='direccion'>Dirección</label>
                    					
                    				</div>
                    				<div class="col-3 col-12-mobilep"></div>
                    				<div class="col-3 col-12-mobilep"></div>
                    				<div id="entera_evidencias" class="col-6 col-12-mobilep">
                    						Datos de las evidencias a mostrar:
                    						<br>
                    						<input type='radio' name='todos_datos_evidencias' id='todos_datos_ev' onchange="todosdatos_evidencias()" value='1' required>
                    						<label for='todos_datos_ev'>Todos</label>
                    					
                    						<input type='radio' name='todos_datos_evidencias' id='no_todos_datos_ev' onchange="seleccion_datos_evidencias()" value='2' required>
                    						<label for='no_todos_datos_ev'>Escoger datos evidencias</label>
                    					
                    				</div>
                    				<div id="datos_evidencias_mostrar" align="center"	>
											Selecciona datos:                    					
                    						<br>
                    						<input type='checkbox' name='marca_modelo' id='marca_modelo' value="marca_modelo">
                    						<label for='marca_modelo'>Marca/Modelo</label>
                    					                  					
                    						<input type='checkbox' name='ns' id='ns' value="ns"> 
                    						<label for='ns'>N/S</label>
                    					
                    						<input type='checkbox' name='capacidad' id='capacidad' value="capacidad">
                    						<label for='capacidad'>Capacidad</label>
                    					
                    						<input type='checkbox' name='clonado' id='clonado' value="clonado">
                    						<label for='clonado'>Clonado</label>
                    					
                    						<input type='checkbox' name='sistema' id='sistema' value="sistema">
                    						<label for='sistema'>Sistema</label>
                    					
                    						<input type='checkbox' name='patron' id='patron' value="patron">
                    						<label for='patron'>Patron</label>
                    						
                    						<input type='checkbox' name='pin' id='pin' value="pin">
                    						<label for='pin'>Pin</label>
                    						
                    						<input type='checkbox' name='observaciones' id='observaciones' value="observaciones">
                    						<label for='observaciones'>Observaciones</label>
                    					
                    				</div>
                    				<div class="col-3 col-12-mobilep"></div>
                    				<div class="col-3 col-12-mobilep" id="oculto"></div>
                    				<div id="lista_evidencias" class="col-6 col-12-mobilep">
                    						Evidencias a mostrar:
                    						<br>
                    						<input type='radio' name='todas_evidencias' id='todas_ev' onchange="todas()" value='1' required>
                    						<label for='todas_ev'>Todas</label>
                    					
                    						<input type='radio' name='todas_evidencias' id='no_todas_ev' onchange="seleccion_evidencias()" value='2' required>
                    						<label for='no_todas_ev'>Escoger evidencias</label>
                    					
                    				</div>
                    				<div class="col-3 col-12-mobilep"></div>
                    				<div class="col-3 col-12-mobilep"></div>
                    				<div id="evidencias" align="center"	>
											Selecciona datos:                    					
                    						<br>                						                					
                    				</div>
									<br>
                    				<div class="col-12">
                    					<ul class="actions special">
    										<li><input type='submit' value="Aceptar"/></li>	
                    						<li><input type="button" onclick="location.href='asunto.php';" value="Volver"><br></li>
    										</ul>
                    				</div>
                				</div>
            				</section>
						</form>	
					</div> <!-- col12 -->
				</div>  <!-- row -->			
			</section>		
		</div>
	</body>
</html>

    
    
    <?php
    
    mysqli_close($link);
}
else {
    echo "Error";
}
?>
		

    	<!-- Pelayo -->
    		<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>    
    
    </html>

