A<?php

session_start();

if(isset($_SESSION['id_u'])) {

    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    
    /*
     * OLD
     * $sql = "SELECT numero from caso where año='2020' order by numero desc limit 0,1";
     */
    
    /* 20200318_1220 modifico el valor 2020 por una funcion que proporciona el año en curso,
     * ya que si no habria que modificar esta query cada año*/
    
    $sql = "SELECT numero from caso where año=YEAR(CURDATE()) order by numero desc limit 0,1";
    
    $result=mysqli_query($link,$sql);
    $count= mysqli_num_rows($result);
    if($count!=0) {
        $ret = mysqli_fetch_array($result);
        $numero=$ret['numero']+1;
    }
    else {
        $numero=1;
    }
    ?>
    
    
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Nuevo Asunto</title>
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
    
    		<script src=»https://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.0/jquery.validate.min.js» type=»text/javascript»></script>
    		<script src=»js/jquery-validate.js»></script>
    	
    		
    		<script>
    		function validar() {
    			var c = document.getElementById("mensajes");
    			var ctx = c.getContext("2d");
    			ctx.font = "bold 12px Verdana";
    			var formdata = new FormData($("#myform")[0]);
    			formdata.append("mod", 0);
    			$.ajax({
    				type: "POST",
    				url: "crearasunto.php",
    				data:formdata,
    				contentType: false,
    				processData: false,
    				success: function(respuesta) {
    					if(respuesta==1) {
    						window.location="asunto.php";
    					}
    					else {
    						ctx.clearRect(0, 0, c.width, c.height);	
    						ctx.clearRect(0, 0, c.width, c.height);	
    						ctx.strokeStyle = "#FF0000";
    						ctx.strokeRect(1, 1, 349, 29);
    						ctx.fillStyle = "#FF0000";
    						ctx.textAlign = "center";
    						ctx.fillText(respuesta,175,20);
    					}
    				}
    			});
    		};
    
    			
    
    		</script>
    		
    		
    		
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
    			  function escribir(opSelect){
    				  var año=opSelect;
    				  var numero = $.ajax({
    
    					  type: "GET", 
    			          url: 'obtencioncasos.php?año='+año,
    			              dataType: 'text',//indicamos que es de tipo texto plano
    			              async: false     //ponemos el parámetro asyn a falso
    			      }).responseText;
    				  
    				  document.getElementById('numero').value=numero;
    			  }
    		</script>
    </head>
    
    <body class="is-preload">
    		<div id="page-wrapper">
    
    			<!-- Main -->
    				<section id="main" class="container">
    					
    					<header>
    						<h2>Nuevo Caso</h2>						
    					</header>
    					
    					
    					<div class="box">
    						<form method="post" id="myform" action="javascript:validar();">
    							Dar de alta un caso <br><br>
    							<div align="center">
    							<canvas id="mensajes" width="350" height="30"></canvas>
    							</div>
    							<div class="row gtr-50 gtr-uniform">
    							
    								<div class="col-2 col-12-mobilep">								
    									<input type="text" name="numero" id="numero" value="<?php echo $numero;?>" required pattern="\d*" placeholder="Caso" />
    								</div>
    								
    								<div class="col-2 col-12-mobilep">	
    								<?php 
    								// cargo la lista de años
    							
    								echo "<select name='año' id='año' onchange='escribir(this.value);'>";
    								$resultado = mysqli_query($link, "select año FROM año_viajes");
    								$count=mysqli_num_rows($resultado);
    								$contador=1;
    								while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
    								    foreach ($line as $col_value) {
    								        if($contador!=$count) {
    								            echo "<option value='$col_value'>$col_value</option>'";
    								            $contador++;
    								        }
    								        else {
    								            echo "<option value='$col_value' selected>$col_value</option>'";    								        }
    								    }
    								}
    								echo "</select> </div>";
    								?>
    								
    								<div class="col-4 col-12-mobilep">									
    									<input type="text" name="nombre" id="nombre" placeholder="Nombre operación">
    								</div>
    							<?php 	
    								//cargo la lista de tipos de caso 
    								
    								echo "<div class='col-4 col-12-mobilep'>	<select name='tipo_caso' id='tipo_caso'>";
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
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="diligencias" id="diligencias" pattern="\d*" placeholder="Número Diligencias">
    								</div>
    													
    								<div class="col-2 col-12-mobilep">	
    									<?php 
    								// cargo la lista de años de diligencias
    							
    								echo "<select name='año_diligencias' id='año_diligencias'>";
    								$resultado = mysqli_query($link, "select año FROM año_viajes");
    								$count=mysqli_num_rows($resultado);
    								$contador=1;
    								while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
    								    foreach ($line as $col_value) {
    								        if($contador!=$count) {
    								            echo "<option value='$col_value'>$col_value</option>'";
    								            $contador++;
    								        }
    								        else {
    								            echo "<option value='$col_value' selected>$col_value</option>'";    								        }
    								    }
    								}
    								echo "</select> </div>";
    								 
    
    							
    
    								//cargo la lista de juzgados
    								echo "<div class='col-6 col-12-mobilep'> <select name='juzgado' id='juzgado'>";
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
    								echo "<div class='col-6 col-12-mobilep'>";
                                    echo "<select name='ca' id='ca' required>
       											<option value=''>Comunidad Autónoma</option> ";
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
    								echo "<div class='col-6 col-12-mobilep'>";
    								echo "<select id='provincia' name='provincia' required>";
    								echo "<option value=''>Provincia</option>";
    								echo "</select></div>";
    	
    								//cargo la lista de comisarias segun la provincia seleccionada
    								echo "<div class='col-6 col-12-mobilep'>";
    								echo "<select id='comisaria' name='comisaria' required>";
    								echo "<option value=''>Comisaria</option>";
    								echo "</select></div>";
    	
    								//cargo la lista de grupos segun la comisaria seleccionada								
    								echo "<div class='col-6 col-12-mobilep'>";
    								echo "<select id='grupo' name='grupo' required>";
    								echo "<option value=''>Grupo</option>";
    								echo "</select></div>";
     
    								
    
    
    						?>
    
    
    						<div class="col-12">
    							<textarea name="descripcion" id="descripcion" placeholder="Descripción del caso..." rows="4"></textarea>
    						</div>							
    								
    								<span id="mensajes" style="color:#FF0000;"></span>
    								
    						<div class="col-12">
    							<ul class="actions special">
    								<li><input type="submit" id="alta" value="Alta Caso"/></li>	
    								<li><input type="button" onclick="location.href='inicio.php';" value="Volver"><br></li>							
    							</ul>
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
}
    ?>

</html>







