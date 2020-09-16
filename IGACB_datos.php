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

$datos_caso=explode("-", $caso);
$numcaso=$datos_caso[0];
$numintervencion=$datos_caso[1];

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
    									<li><a href="informes.php">Informes</a></li>
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
			<header><h2>Informes predefinidos</h2></header>
			<div class="box">
				<div class="col-12 col-12-mobilep" style="text-align:center">
				<h3>Informe General - Acta Clonacion Base<h3>
				
				<h3>  Caso: <?php echo $numcaso;?></h3>
				
				<div class="row">
						<div class="col-12">
							<form id="form_inf_pred" name="form_inf_pred" action='<?php echo $informe;?>.php' method="POST" target=_blank>
								<section class="box">
									
									<input type='hidden' name="caso" id="caso" value="<?php echo $caso;?>">
									
									<h3><b>Necesitamos algunos datos para emitir este informe</b></h3> 
                                 	<br>
                                 	<hr color="blue" size=3>
                                    <h4>FORMATO DE SALIDA</h4>
									  	<select id='tipo' name='tipo' required>
                                         <option value='PDF'>PDF</option>
                                         <option value='DOC'>DOC</option>
                                         </select>
                                           
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
											<h5>(por defecto la actual)</h5>
											
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
                                                   	<input type="text" name="carnetfentrega" id="carnetfentrega" placeholder="Carnet Profesional" maxlength="6" required />
							
                                                  
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