<?php

session_start();


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

if(isset($_POST["caso"])) {
    $caso= $_POST["caso"];
}
else {
    $caso=0;
}
if(isset($_POST["intervencion"])) {
    $intervencion= $_POST["intervencion"];
}
else {
    $intervencion=NULL;
}
if(isset($_POST["cabecera"])) {
    $cabecera= $_POST["cabecera"];
}
else {
    $cabecera="caca";
}



$_SESSION['mod']=$mod;



$link = mysqli_connect("localhost", "root", ".google.", "safe");
if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

$resultado_fecha_dia = mysqli_query($link, "select day(now()) as dia");
$count= mysqli_num_rows($resultado_fecha_dia);
if($count!=0) {
    $ret = mysqli_fetch_array($resultado_fecha_dia);
    $dia=$ret['dia'];
}
else {
    $dia=null;
}
//Cambia idioma para el mes
$resultado_idioma = mysqli_query($link, "SET lc_time_names = 'es_ES'");
if (!$resultado_idioma) {
    echo  mysqli_error($link);
    
}

$resultado_fecha_mes = mysqli_query($link, "select monthname(now()) as mes");
$count= mysqli_num_rows($resultado_fecha_mes);
if($count!=0) {
    $ret = mysqli_fetch_array($resultado_fecha_mes);
    $mes=$ret['mes'];
}
else {
    $mes=null;
}
$resultado_fecha_ano = mysqli_query($link, "select year(now()) as ano");
$count= mysqli_num_rows($resultado_fecha_ano);
if($count!=0) {
    $ret = mysqli_fetch_array($resultado_fecha_ano);
    $ano=$ret['ano'];
}
else {
    $ano=null;
}

$resultado_fecha_hora = mysqli_query($link, "select hour(now()) as hora");
$count= mysqli_num_rows($resultado_fecha_hora);
if($count!=0) {
    $ret = mysqli_fetch_array($resultado_fecha_hora);
    $hora=$ret['hora'];
}
else {
    $hora=null;
}

$resultado_fecha_minuto = mysqli_query($link, "select minute(now()) as minuto");
$count= mysqli_num_rows($resultado_fecha_minuto);
if($count!=0) {
    $ret = mysqli_fetch_array($resultado_fecha_minuto);
    $minuto=$ret['minuto'];
}
else {
    $minuto=null;
}

if(isset($_POST["fechainicioE"])) {
    $fechainicioE= $_POST["fechainicioE"];
    if ($fechainicioE) {
        
        $fechadiligencia_formateada=strftime('%d-%B-%YT%H:%M:%S', strtotime($fechainicioE));
        // si la fecha llega rellena, coge los datos del formulario.
        //si no coge la de sistema, que definimos arriba
        $fechaT=explode("T", $fechadiligencia_formateada);
        
        $fecha=explode("-", $fechaT[0]);
        $diaD=$fecha[0];
        $mesD=$fecha[1];
        $anofechainicioE=$fecha[2];
        
        $tiempo=explode(":", $fechaT[1]);
        $horaD=$tiempo[0];
        $minutoD=$tiempo[1];
        
    }
    else{
        $diaD=$dia;
        $mesD=$mes;
        $anofechainicioE=$ano;
        $horaD=$hora;
        $minutoD=$minuto;
    }
}
else {

    $resultado_fecha_ano = mysqli_query($link, "select year(now()) as ano");
    $count= mysqli_num_rows($resultado_fecha_ano);
    if($count!=0) {
        $ret = mysqli_fetch_array($resultado_fecha_ano);
        $anofechainicioE=$ret['ano'];
    }
    else {
        $anofechainicioE=null;
    }
 
}

if(isset($_POST["fechafinE"])) {
    $fechafinE= $_POST["fechafinE"];
    if ($fechafinE) {
        
        $fechadiligencia_formateada=strftime('%d-%B-%YT%H:%M:%S', strtotime($fechafinE));
        // si la fecha llega rellena, coge los datos del formulario.
        //si no coge la de sistema, que definimos arriba
        $fechaT=explode("T", $fechadiligencia_formateada);
        
        $fecha=explode("-", $fechaT[0]);
        $diaD=$fecha[0];
        $mesD=$fecha[1];
        $anofechafinE=$fecha[2];
        
        $tiempo=explode(":", $fechaT[1]);
        $horaD=$tiempo[0];
        $minutoD=$tiempo[1];
        
    }
    else{
        $diaD=$dia;
        $mesD=$mes;
        $anofechafinE=$ano;
        $horaD=$hora;
        $minutoD=$minuto;
    }
}
else {
   
    $resultado_fecha_ano = mysqli_query($link, "select year(now()) as ano");
    $count= mysqli_num_rows($resultado_fecha_ano);
    if($count!=0) {
        $ret = mysqli_fetch_array($resultado_fecha_ano);
        $anofechafinE=$ret['ano'];
    }
    else {
        $anofechafinE=null;
    }

    
}
print isset($_POST["fechainioE"]);
print isset($_POST["fechafinE"]);
print $anofechainicioE;
print $anofechafinE;

$sql_tipo = "select te.nombre as nombre, count(e.nombre) as numero
from caso as c
inner join evidencia as e on c.id_caso=e.id_caso
inner join tipo_evidencia as te on e.id_tipo_evidencia=te.id_tipo_evidencia
where c.año between '$anofechainicioE' and '$anofechafinE'
group by te.id_tipo_evidencia";



#por subtipo de evidencia
$sql_subtipo = "select te.nombre as nombre, ste.nombre as subtipo,  count(e.nombre) as numero
from caso as c
inner join evidencia as e on c.id_caso=e.id_caso
inner join tipo_evidencia as te on e.id_tipo_evidencia=te.id_tipo_evidencia
inner join subtipo_evidencia as ste on e.id_subtipo_evidencia=ste.id_subtipo_evidencia
where c.año between '$anofechainicioE' and '$anofechafinE'
group by te.id_tipo_evidencia, ste.id_subtipo_evidencia"; 


$resultado_tipo=mysqli_query($link, $sql_tipo);
$count_tipo=mysqli_num_rows($resultado_tipo);

$resultado_subtipo=mysqli_query($link, $sql_subtipo);
$count_subtipo=mysqli_num_rows($resultado_subtipo);

?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
 <title>Estadisticas predefinidas</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
	
	

			
	<!-- Alonso -->
		<script src="//code.jquery.com/jquery-latest.js"></script>
		<script src="miscript.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script src="js/jquery-3.4.1.js"></script>
		<script src="miscript.js"></script>
	
	<!-- Opcion de estadisticas con JS -->
	
	<script>
	function validar() {
		var formdata = new FormData($("#myform")[0]);
		var url="EGEA_detalles.php";

		

		alert ("entro");

		/*var nombre= ;
        var horas= document.getElementById('horas').value;
        var url="graficas.php?nom="+nombre+"&hrs="+horas;
        fi = document.getElementById('grafica');
        var imagen = document.createElement('img');
        imagen.src=url
        fi.appendChild(imagen);*/

		 $.ajax({
			 
			 url:url,
	         type:"POST",
	         data:formdata,
				contentType: false,
				processData: false,
				success: function(data) {
	         alert(data);
	        	 //$("#grafica2").html('<img src="data:image/png;base64,' + data + '" />');
		        	//https://www.youtube.com/watch?v=2-MLZwe9amE
	        	 //https://stackoverrun.com/es/q/1865981
	        	$('#grafica2').append(data);
				
		 
	        	  
	         }
		 });   
		
	            
	}
	</script>
		
		

		
	
</head>

<body class="is-preload";>
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
	
	
	<!-- Main -->
		<section id="main" class="container">
			<header><h2>Estadisticas predefinidas</h2></header>
			<div class="box">
				<div class="col-12 col-12-mobilep" style="text-align:center">
    				<h3>Estadistica General Intervencion</h3>
    				<h3><?php echo $anofechainicioE;?> - <?php echo $anofechafinE;?></h3>
    				<div class="row">
    						<div class="col-12">
    								<section class="box">
    								<hr color="blue" size=3>
    								<div class="row">
    									<div id='Tabla_tipo' class="col-6 col-12-mobilep">
										
        									 <?php
        									 if ($count_tipo != 0) {
        
                                             ?>											
        											<h3><b>Listado por tipo</h3>
        									  																				
        
            								 <?php
            					
            								 echo "<table>";
            								 echo '	<thead>
                                            										<tr>
                                                                                        
                                            											<th>Tipo de evidencia</th>
                                                                                        <th>Número de evidencias</th>
                                                                                 	</tr>
                                            									</thead>
                                            									<tbody>';
                                                while ($ret = mysqli_fetch_array($resultado_tipo, MYSQLI_ASSOC)) {
                                                    $nombre=$ret['nombre'];
                                                    $numero=$ret['numero'];
                                                    echo "<tr>";  
                                                    echo "<td>$nombre</td>";
                                                    echo "<td>$numero</td>";
                                                    echo "<tr>";
                                                    
                                                }
                                                echo "</tbody></table>";
                                            
                                            } else {
                                            ?>
                                                <h4>No hay evidencias para ese año</h4>											
                                            <?php   
                                            }									
                                            ?>
                                       		
                                       		</div>
                                   		
                                       		<div id='grafica1' class="col-6 col-12-mobilep">
                                   		
                                       		<img id='img1'>
                                            </div> 
                                         
                                        </div> 
                                       <hr color="blue" size=3>
                                        <div class="row"> 
                                    
                                       <div id='Tabla_subtipo' class="col-6 col-12-mobilep">
                                   		
    									 <?php
    									 if ($count_subtipo != 0) {
    
                                         ?>											
    											<h3><b>Listado por subtipo</b></h3>
    																			
    
        								 <?php
        								 echo "<table>";
        								 echo '	<thead>
                                        										<tr>
                                                                                    
                                        											<th>Tipo de evidencias</th>
                                                                                    <th>Subtipo de evidencia</th>
                                                                                    <th>Número de evidencias</th>
                                                                             	</tr>
                                        									</thead>
                                        									<tbody>';
                                            while ($ret = mysqli_fetch_array($resultado_subtipo, MYSQLI_ASSOC)) {
                                                $nombre=$ret['nombre'];
                                                $subtipo=$ret['subtipo'];
                                                $numero=$ret['numero'];
                                                echo "<tr>";  
                                                echo "<td>$nombre</td>";
                                                echo "<td>$subtipo</td>";
                                                echo "<td>$numero</td>";
                                                echo "<tr>";
                                                
                                            }
                                            echo "</tbody></table>";
                                        
                                        } else {
                                        ?>
                                            <h4>No hay evidencias para ese año</h4>											
                                        <?php   
                                        }									
                                        ?>
                                   		
                                   		</div>
                                   		
                                   		                                   		
                                   		
                                   		<div id='grafica2' class="col-6 col-12-mobilep">
                                   		
                                   		
                                   		<img id='img1'>
                                        </div>  
                                   		<hr color="blue" size=3>
                                   	</div>                                       
                    				</section>
    							
    						</div>
    				</div>
    		
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


    
