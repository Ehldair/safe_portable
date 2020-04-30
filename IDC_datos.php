<?php

session_start();



$link = mysqli_connect("localhost", "root", ".google.", "safe");
if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

if(isset($_GET["mod"])) {
    $mod= $_GET["mod"];
}
else {
    if(isset($_POST["mod"])) {
        $mod= $_POST["mod"];
    }
    else {
        if(isset($_SESSION["mod"])){
            $mod=$_SESSION['mod'];
        }
        else {
            $mod= 0;
        }
    }
}

if(isset($_POST["casos"])) {
    $caso= $_POST["casos"];
}
else {
    $caso=0;
}
if(isset($_POST["informes"])) {
    $informe= $_POST["informes"];
}
else {
    $informe=NULL;
}

$numcaso=$caso;



$_SESSION['mod']=$mod;


$resultado = mysqli_query($link, "select * from usuario");
$resultado2 = mysqli_query($link, "select * from usuario");
$count=mysqli_num_rows($resultado);


?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
 <title>Informes predefinidos</title>
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
			<header><h2>Informes predefinidos</h2></header>
			<div class="box">
				<div class="col-12 col-12-mobilep" style="text-align:center">
				<h3>Informe Detalle Caso:  Caso: <?php echo $numcaso;?> </h3>
				
				<div class="row">
						<div class="col-12">
							<form id="form_inf_pred" name="form_inf_pred" action='<?php echo $informe;?>.php' method="POST" target=_blank>
								<section class="box">
									
									<input type='hidden' name="caso" id="caso" value="<?php echo $caso;?>">
									
									<h3><b>Necesitamos algunos datos para emitir este informe</b></h3> 
                                 	<br>  
                                 	<hr color="blue" size=3>
                                    <h4>DATOS DILIGENCIA  </h4>
									<br>  							
									<div class="row">
										<div class="col-6 col-12-mobilep">
											<h4>Selecciona Intructor de la Diligencia</h4>
										
											<?php
                                            if ($count != 0) 
                                            {
                							?>
                								<br>
                    							<select name='instructor' size='3' required>
                                                <?php
                                                    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                                                        
                                                        $nombre=$line['nombre'];
                                                        $apodo=$line['apodo'];
                                                        $cp = $line['cp'];
                                                        
                                                     
                                                        echo "<option value='$cp'>$nombre : $cp [$apodo]</option>";
                                                        
                                                    } //while
                                                    ?>
                                                     </select>
                                                    
                                              
                                         </div>
                                         <div class="col-6 col-12-mobilep">
                                                 <h4>Selecciona Secretario de la Diligencia</h4>
                                                 <br>
                                                  <select name='secretario' size='3' required>
                                                       <?php
                                                    while ($line2 = mysqli_fetch_array($resultado2, MYSQLI_ASSOC)) {
                                                        
                                                        $nombreS=$line2['nombre'];
                                                        $apodoS=$line2['apodo'];
                                                        $cpS = $line2['cp'];
                                                        
                                                       
                                                        echo "<option value='$cpS'>$nombreS: $cpS [$apodoS]</option>";
                                                        
                                                    } //while
                                                    ?>
                                                      </select>
                                                 </div>
                                                 <br>
                                                <?php	
                                                }  // if
                							
                							else {
                							    ?>
                							     <br><b>
                							     "NO EXISTEN FUNCIONARIOS";
                							     </b>
                							    <?php	}
    							            ?>
                               				  	 
											</div> <!-- <div class="row"> -->
                                       <br>
                                       <div class="row">
										<div class="col-6 col-12-mobilep">
											<h4>Selecciona fecha de la generacion del acta </h4>
											<h5>(por defecto la actual)<h5>
											
                								<br>
                    					
                                                 <input type="datetime-local" name="fechadiligencia" id="fechadiligencia" value="2015-02-21 15:35">
                                                    
                                              
                                         </div>
                                         <div class="col-6 col-12-mobilep">
                                                 
                                         </div>
                                                 <br>
                                               
                               				  	 
											</div> <!-- <div class="row"> -->
                                        <br>  
                                       <hr color="blue" size=3>
                                        <h4>DATOS ENTREGA MATERIAL </h4>
										<br>  
                                       <div class="row">
                                       
                                       <div class="col-6 col-12-mobilep">
										<h4>Selecciona fecha de entrega material al grupo </h4>
											<h5>(por defecto la actual)</h5>
											
                								<br>
                    					
                                                 <input type="datetime-local" name="fechaentregamaterial" id="fechaentregamaterial" value="2015-02-21 15:35">
                                                    
                                              
                                         </div>
                                      
                                         <div class="col-6 col-12-mobilep">
                                                 <h4>Funcionario de investigación</h4>
                                                   <br>
                                                   <br>
                                                   
                                                 		<input type="text" name="carnetfentrega" id="carnetfentrega" placeholder="Carnet Profesional" maxlength="6" required/>
							
                                                  
                                         </div>
                                                 <br>
                                               
                               				  	 
											</div> <!-- <div class="row"> -->
                                            
										</div>
									<br>
									<div class="col-12">
											<ul class="actions special">
										  
												<li><input type='submit'  value="Genera informe" ><br></li>
											  	
												<li><input type="button" onclick="location.href='informes.php';" value="Volver"><br></li>
											  							
											</ul>
										</div>
										
									
								</section>
							</form>
					</div>
			</div>
		</section>								
	</div>
 </body>
</html>