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
 <title>Busqueda Caso</title>
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
						<h2>Busqueda caso</h2>						
					</header>
					
					<form method="post" action="crearbusqueda_caso.php" id="myform">
						<div class="box">
						
							Busqueda avanzada caso <br><br>
							
							<div class="row gtr-50 gtr-uniform">
							
								<div class="col-2 col-12-mobilep">								
									<input type="text" name="numero" id="numero" placeholder="Num asunto"/>
								</div>
								
								<div class="col-3 col-12-mobilep">

    								<select name="año_inicio" id="año_inicio" onchange="cambiar(this.value);">
   										<option value="0"  selected>- Año caso desde -</option>
    									<option value="2016">2016</option>
      									<option value="2017">2017</option>
      									<option value="2018">2018</option>
      									<option value="2019">2019</option>
      									<option value="2020">2020</option>
    								</select>
    							</div>	
    		
    							<div class="col-3 col-12-mobilep">
    								<select name="año_fin" id="año_fin" disabled>
   										<option value="0"  selected>- Año caso hasta -</option>
    									<option value="2016">2016</option>
      									<option value="2017">2017</option>
      									<option value="2018">2018</option>
      									<option value="2019">2019</option>
      									<option value="2020">2020</option>
        						 	</select>    							
    							</div>								
								
								<div class="col-4 col-12-mobilep">								
									<input type="text" name="nombre" id="nombre" placeholder="Nombre operación"/>
								</div>
								
								<div class="col-2 col-12-mobilep">								
									<input type="text" name="diligencias" id="diligencias" placeholder="Diligencias"/>
								</div>
								
								<div class="col-3 col-12-mobilep">	
									<select name="año_diligencias_inicio" id="año_diligencias_inicio" onchange="cambiardil(this.value);">
								    	<option value="0"  selected>- Diigencias desde -</option>
    									<option value="2016">2016</option>
      									<option value="2017">2017</option>
      									<option value="2018">2018</option>
      									<option value="2019">2019</option>
      									<option value="2020">2020</option>
								    </select>
								</div>
								
								<div class="col-3 col-12-mobilep">	
									<select name="año_diligencias_fin" id="año_diligencias_fin" disabled>
								    	<option value="0"  selected>- Diligencias hasta -</option>
    									<option value="2016">2016</option>
      									<option value="2017">2017</option>
      									<option value="2018">2018</option>
      									<option value="2019">2019</option>
      									<option value="2020">2020</option>
								    </select>
								</div>

<?php 
	
	//cargo la lista de juzgados
	echo "<div class='col-4 col-12-mobilep'> <select name='juzgado'>";
	echo "<option value='0'>- Juzgado -</option>";
	$resultado = mysqli_query($link, "select id_juzgado, nombre, numero FROM juzgado where id_juzgado!=0");
	$contador=2;
	while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
	    foreach ($line as $col_value) {
	       if ($contador==2) { 
	           echo "<option value='$col_value'>";
	           $contador=0;
	       }
	       else {
	           if($contador==0) {
	               echo $col_value;
	               $contador++;	           }
	           else {
	               echo " ".$col_value."</option>";
	               $contador++;
	           }
	       }
        }
	}
	echo "</select> </div>";	
	
	//cargo la lista de CA
	echo "<div class='col-4 col-12-mobilep'> <select name='ca' id='ca'>";
    	echo "<option value=0>- Comunidad Autónoma -</option>";
	$resultado = mysqli_query($link, "select id_ca,nombre_ca FROM CA");
	$contador=0;
	while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
	    foreach ($line as $col_value) {
	        if ($contador==0) {
	            echo "<option value='$col_value'>";
	            $contador++;
	        }
	        else {
	            echo " ".$col_value."</option>";
	            $contador=0;
	        }
	    }
	}
	echo "</select></div>";
	
	//cargo la lista de provincias segun la CA seleccionada
	echo "<div class='col-4 col-12-mobilep'> <select id='provincia' name='provincia'>";
		echo "<option value='0' selected>- Provincia -</option>";
	echo "</select></div>";
	
	//cargo la lista de comisarias segun la provincia seleccionada
	echo "<div class='col-4 col-12-mobilep'><select id='comisaria' name='comisaria'>";
		echo "<option value='0' selected>- Comisaría -</option>";
	echo "</select></div>";
	
	//cargo la lista de grupos segun la comisaria seleccionada
	echo "<div class='col-4 col-12-mobilep'><select id='grupo' name='grupo'>";
		echo "<option value='0' selected>- Grupo investigación -</option>";
	echo "</select></div>";
	
	//cargo la lista de tipos de caso
	echo "<div class='col-4 col-12-mobilep'>	<select name='tipo_caso'>";
	echo "<option value='0'>- Tipo de caso -</option>";
	$resultado = mysqli_query($link, "select * FROM tipo_caso");
	$contador=0;
	while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
	    foreach ($line as $col_value) {
	        if ($contador==0) {
	            echo "<option value='$col_value'>";
	            $contador++;
	        }
	        else {
	            echo " ".$col_value."</option>";
	            $contador=0;
	        }          
	    }
	}
	echo "</select> </div>";

?>
	
	
								<div class="col-4 col-12-narrower">
								
								</div>															
								
								
								
																
					
								<div class="col-12">	
									<ul class="actions special">
										<li> <input type="submit"  value="Buscar" class="estilo"> </li>
										<li> <input type="button" onclick="location.href='inicio.php';" value="Volver" class="estilo"> </li>							
									</ul>						
						
								</div>
								
								<div class="col-4 col-12-narrower">
									<input type="checkbox" id="conjuncion" name="conjuncion">
									<label for="conjuncion">Busqueda inclusiva</label>
								</div> 
								
								<div class="col-8 col-12-narrower">
								
								</div>		
					    
					    					    
					  	</form>	    
					  </div>	
				</div>
					
			</section>
		</div>	

</body>

</html>

    
