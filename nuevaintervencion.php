<?php

session_start();

if(isset($_SESSION['id_u'])) {

    $link = mysqli_connect("localhost", "root", ".google.", "safe");
    
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
    
    $myid_caso=$_SESSION['id_caso'];
        
        
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
    		<script src="miscript.js"></script>
    
    </head>
    
    <body class="is-preload">
    	<div id="page-wrapper">
    
    	<!-- Main -->
    		<section id="main" class="container">
    			<header>
    				<h2>Agregar intervención</h2>
    				<h3><p></p></h3>
    			</header>
    			
    			<div class="row">
    						<div class="col-12">
    						
    							<form action='crearintervencion.php' method='post'>
    								<section class="box">
    							
    																
    									<h3>Agregar nueva intervención al caso</h3>
    									
    									<div class="row">
    										<div class="col-6 col-12-mobilep">																						
    										<h4>Seleccione  sujeto activo relacionado.</h4>
    										
    <?php
    										echo "<input type='radio' name='sujeto' id='sujeto' value='$ret[id_su]'>";
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
        echo "<input type='text' name='descripcion' id='descripcion' placeholder='Por ejemplo, domicilio del detenido'>";
      
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
     </body>
     <?php 
}
else {
    echo "Error";
}
     ?>
</html>