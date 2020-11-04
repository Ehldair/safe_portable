<?php

session_start();

if(isset($_SESSION['id_u'])) {
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    if($_SESSION['mod']==2){
        $mod=$_SESSION['mod'];
    }
    else {
        $mod= 1;
    }
    
    $_SESSION['mod']=$mod;
    
    $respuesta=$_SESSION['respuesta'];
    
    $myid_caso=$_SESSION['id_caso'];
    $sql="select fecha_alta_caso FROM caso WHERE id_caso=$myid_caso";
    $result=mysqli_query($link, $sql);
    $ret_fecha=mysqli_fetch_array($result);
    
    //cargo la lista de sujetos
    
    $resultado = mysqli_query($link, "select distinct s.id_sujeto_activo, s.nombre, s.apellido1, s.apellido2
    FROM sujeto_activo s
    WHERE id_caso=$myid_caso");
    
    $resultado2 = mysqli_query($link, "select distinct s.id_sujeto_activo as id_su, s.nombre as nom, s.apellido1 as ape
    FROM sujeto_activo s
    WHERE id_caso=1");
    $ret = mysqli_fetch_array($resultado2);
    
    
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Nueva Intervencion</title>
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
    			var c = document.getElementById("mensaje");
    			var ctx = c.getContext("2d");
    			ctx.font = "bold 12px Verdana";
    			ctx.clearRect(0, 0, c.width, c.height);	
    			if(respuesta==1) {
    				ctx.strokeStyle = "#FF0000";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#FF0000";
    				ctx.textAlign = "center";
    				ctx.fillText("Ya existe esa intervención",150,20);
    				<?php $_SESSION['respuesta']=0; ?>
    				setTimeout(borrar,5000);
    			}
    		};
			function asignarnumero() {
				respuesta();
				var numero = $.ajax({
				
  				  type: "GET", 
  		          url: 'obtenerintervencion.php',
  		          async: false     //ponemos el parámetro asyn a falso
  		      }).responseText;
				document.getElementById("numero_envio").value=numero;
  			  document.getElementById('numero').value=numero+" (las evidencias serán por ejemplo "+numero+"TEL1, "+numero+"HD1,....)";
			}
			function cambionumero() {
				
				var numero=$('input:text[name=numero]').val();
				if (isNaN(numero)) {
					asignarnumero();
					document.getElementById("direccion").focus();
				}
				else {
					document.getElementById("numero_envio").value=numero;
					document.getElementById('numero').value=numero+" (las evidencias serán por ejemplo "+numero+"TEL1, "+numero+"HD1,....)";
					document.getElementById("direccion").focus();
					
				}	
			}
			function cabecera(){
    			
    			$('#cabecera').load('cabecera.php');                
    			asignarnumero();
    			
    		};

    		</script>
    
    </head>
    
    <body class="is-preload" onload="cabecera()">
    <div id="cabecera">
    
    </div>
    	<div id="page-wrapper">
	<!-- Header -->
    				
    	<!-- Main -->
    		<section id="main" class="container">
    			<header>
    				<h2>Agregar intervención</h2>
    			</header>
    			
    			<div class="row">
    						<div class="col-12">
    						
    							<form action='crearintervencion.php' method='post'>
    								<section class="box">
    								<div align="center">
    									<canvas id="mensaje" width="350" height="30"></canvas>
    									</div>
    							
    																
    									<h3>Agregar nueva intervención al caso</h3>
    									
    									<div class="row">
    										<div class="col-6 col-12-mobilep">																						
    										<h4>Seleccione  sujeto activo relacionado.</h4>
    										
    <?php
    										echo "<input type='radio' name='sujeto' id='sujeto' value='$ret[id_su]' required>";
    										echo"<label for='sujeto'>";
     											echo $ret['nom'];
     											echo " ".$ret['ape'];
    											echo " </label> <br>";
    			
    										
    	$i=1;
        $contador=0;
        $entro=0;
        while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            foreach ($line as $col_value) {
                if ($contador==0) {
    
                    echo "<input type='radio' name='sujeto' id='sujeto$i'; value=$col_value required>";
    				echo"<label for='sujeto$i'>";
                    $contador++; 
                    $i++;
                    
                }
                else {
                    if($entro<2) {
                        
                        echo $col_value."  ";
                        
                        $entro++;
                    }
                    else {
                       
                        echo $col_value." ";
                       
                        $entro=0;
                        $contador=0;
    					echo"</label><br> ";
                    }
                }
    				
            }
           
        }
    
        echo "<BR><br>";
    ?>										
    										
    										
    										<!-- boton -->
    										
    										</div>
    										
    										<div class="col-6 col-12-mobilep">
    											<h4>Datos de la intervención:</h4>
    <?php										
    
    echo "Número: ";
    ?>
   <input type='text' name='numero' id='numero' required  onchange='cambionumero()' onfocus="this.value=''">
   <input type='hidden' name='numero_envio' id='numero_envio'>
    <?php    
    
    
    										echo "Tipo Intervención: ";
        									echo "<select name='tipo'>";
        $resultado = mysqli_query($link, "select * from tipo_intervencion");
        $contador=0;
        $entro=0;
        while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            foreach ($line as $col_value) {
                if ($contador==0) {
                    echo "<option value='$col_value'>";
                    $contador++;
                }
                else {
                    if($entro==0) {
                        echo $col_value." ";
                        $entro=1;
                    }
                    else {
                        echo $col_value."</option> ";
                        $entro=0;
                        $contador=0;
                    }
                }
            }
        }
        echo "</select>";
    
        echo "Dirección: ";    
        echo "<input type='text' name='direccion' id='direccion' placeholder='Por ejemplo, Gran Vía de Hortaleza s/n Madrid'>";   
        
        echo "Descripción:";
        echo "<input type='text' name='descripcion' id='descripcion' placeholder='Por ejemplo, domicilio del detenido (No poner equipo aquí)'>";
        
        echo "Fecha:";
        echo "<br><input type='date' name='fecha' id='fecha' value=$ret_fecha[fecha_alta_caso] required>";
        	
      
    ?>	
    
    									
    										</div>
    										
    										<!-- botones aceptar y volver -->
    										
    										
    										
    									</div>
    									<br>
    									<div class="col-12">
    											<ul class="actions special">
    										  
    												<li><input type='submit' value="Aceptar"/></li>	
    												<li><input type="button" onclick="location.href='asunto.php';" value="Volver"><br></li>
    											  							
    											</ul>
    										</div>
    										
    									
    								</section>
    							</form>
    					</div>
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
     <?php 
}
else {
    echo "Error";
}
     ?>
</html>