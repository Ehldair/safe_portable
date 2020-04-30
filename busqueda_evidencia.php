<?php

session_start();

if(isset($_SESSION['id_u'])) {

    $link = mysqli_connect("localhost", "root", ".google.", "safe");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    ?>
    
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Busqueda Evidencia</title>
    	<meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    	<link rel="stylesheet" href="assets/css/main.css" />
    	
    	<!-- Pelayo -->
    		<script src="assets/js/jquery.min.js"></script>
    		<script src="assets/js/jquery.dropotron.min.js"></script>
    		<script src="assets/js/jquery.scrollex.min.js"></script>
    		<script src="assets/js/browser.min.js"></script>
    		<script src="assets/js/breakpoints.min.js"></script>
    		<script src="assets/js/util.js"></script>
    		<script src="assets/js/main.js"></script>
    			
    	<!-- Alonso -->
    		<script src="//code.jquery.com/jquery-latest.js"></script>
    		<script src="miscript.js"></script>
    		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    		<script src="js/jquery-3.4.1.js"></script>
      
      
      <script type="text/javascript">
    
    	$('#ca').on('change',function(){
    		
      var category=$("#ca").val();
      var url="obtenerprovincia.php";
    
           $.ajax({
    
             url:url,
             type:"POST",
             data:{category:category}
    
           }).done(function(data){
    
                 $("#provincia").html(data);
           })    
       });
    
    	$('#provincia').on('change',function(){
    		
    		  var category=$("#provincia").val();
    		  var url="obtenercomisaria.php";
    
    		       $.ajax({
    
    		         url:url,
    		         type:"POST",
    		         data:{category:category}
    
    		       }).done(function(data){
    
    		             $("#comisaria").html(data);
    		       })    
    		   });
    
    	$('#comisaria').on('change',function(){
    		
    		  var category=$("#comisaria").val();
    		  var url="obtenergrupo.php";
    
    		       $.ajax({
    
    		         url:url,
    		         type:"POST",
    		         data:{category:category}
    
    		       }).done(function(data){
    
    		             $("#grupo").html(data);
    		       })    
    		   });
    	  </script>
    	  <script>
    	  function cambiar(opSelect) {
    		  var category=opSelect;
    		  if(category!="0") { 
    		  document.getElementById('año_fin').disabled = false;
    		  var url="obtenerañofin.php";
    			var pro= $.ajax({
    
    				url:url,
    				type:"POST",
    				data:{category:category}
    
    			}).done(function(data){
    
    				$("#año_fin").html(data);
    			})    
    		  }
    		  else {
    			  document.getElementById('año_fin').value = "";
    			  document.getElementById('año_fin').disabled = true;
    		  }
    		};
    		function cambiardil(opSelect) {
    			  var category=opSelect;
    			  if(category!="0") { 
    			  document.getElementById('año_diligencias_fin').disabled = false;
    			  var url="obtenerañofin.php";
    				var pro= $.ajax({
    
    					url:url,
    					type:"POST",
    					data:{category:category}
    
    				}).done(function(data){
    
    					$("#año_diligencias_fin").html(data);
    				})    
    			  }
    			  else {
    				  document.getElementById('año_diligencias_fin').value = "";
    				  document.getElementById('año_diligencias_fin').disabled = true;
    			  }
    			};
    	  </script>
    	  
    	  
    </head>
    
    <body class="is-preload">
    		<div id="page-wrapper">
    
    			<!-- Main -->
    				<section id="main" class="container">
    					
    					<header>
    						<h2>Busqueda evidencia</h2>						
    					</header>
    
    					<div class="box">
    						<form method="post" action="crearbusqueda_evidencia.php" method="post">
    							Busqueda avanzada evidencia <br><br>
    							
    							<div class="row gtr-50 gtr-uniform">
    							
    								<div class="col-2 col-12-mobilep">								
    									<input type="text" name="nombre" id="nombre" placeholder="Nom Evidencia"/>
    								</div>
    								
    								<div class="col-2 col-12-mobilep">								
    									<input type="text" name="numero" id="numero" placeholder="Num Evidencia"/>
    								</div>
    								
    								<div class="col-3 col-12-mobilep">								
    									<input type="text" name="n_s" id="n_s" placeholder="Num Serie"/>
    								</div>
    								
    								<div class="col-4 col-12-mobilep">								
    									<input type="text" name="capacidad" id="capacidad" placeholder="Capacidad"/>
    								</div>
    								
    								<div class="col-2 col-12-mobilep">								
    									<input type="text" name="marca" id="marca" placeholder="Marca"/>
    								</div>
    								
    								<div class='col-3 col-12-mobilep'>
    									<input type="text" name="modelo" id="modelo" placeholder="Modelo"/>
    								</div>
    	
    <?php 
    	
    	//cargo la lista de tipo evidencia
    	echo "<div class='col-4 col-12-mobilep'> <select name='tipo_evidencia' id='tipo_evidencia'>";
        	echo "<option value=0>- Tipo Evidencia -</option>";
    	$resultado = mysqli_query($link, "SELECT id_subtipo_evidencia, nombre FROM safe.subtipo_evidencia;");
    	$contador=0;
    	while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
    	    foreach ($line as $col_value) {
    	        if ($contador==0) {
    	            echo "<option value='$col_value'>";
    	            $contador++;
    	        }
    	        else {
    	            echo $col_value."</option>";
    	            $contador=0;
    	        }
    	    }
    	}
    	echo "</select></div>";
    	
    	//cargo la lista de discos
    	echo "<div class='col-2 col-12-mobilep'> <select name='disco' id='disco'>";
    	echo "<option value=0>- Disco -</option>";
    	$resultado = mysqli_query($link, "SELECT id_disco_almacenado, nombre FROM safe.disco_almacenado;");
    	$contador=0;
    	while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
    	    foreach ($line as $col_value) {
    	        if ($contador==0) {
    	            echo "<option value='$col_value'>";
    	            $contador++;
    	        }
    	        else {
    	            echo $col_value."</option>";
    	            $contador=0;
    	        }
    	    }
    	}
    	echo "</select></div>";
    
    ?>
    	
    								<div class='col-2 col-12-mobilep'>
    									<input type="text" name="alias" id="alias" placeholder="Alias"/>
    								</div>
    								<div class="col-3 col-12-narrower">
    								
    								</div>	
    								<div class="col-4 col-12-narrower">
    								
    								</div>	
    																				
    								
    								
    								
    																
    					
    								<div class="col-12">	
    									<ul class="actions special">
    										<li> <input type="submit"  value="Buscar" class="estilo"> </li>
    										<li> <input type="button" onclick="location.href='asunto.php';" value="Volver" class="estilo"> </li>							
    									</ul>						
    						
    								</div>
    								
    								<div class="col-2 col-12-narrower">
    									<input type="checkbox" id="conjuncion" name="conjuncion">
    									<label for="conjuncion">Busqueda inclusiva</label>
    								</div> 
    								
    								<div class="col-8 col-12-narrower">
    								
    								</div>		
    					    
    					    </div>					    
    					  </form>	    
    						
    				</div>
    					
    			</section>
    		</div>	
    
    </body>
    <?php 
}
else {
 echo "Error";
}?>
</html>

    
