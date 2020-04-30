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

$sql = "SELECT * 
FROM caso where id_caso=$caso";

$resultado=mysqli_query($link, $sql);
$count=mysqli_num_rows($resultado);

?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
 <title>Estadisticas predefinidas</title>
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
	
	<!-- Opcion de estadisticas con JS -->
	
	<script>
	function validar() {
		var formdata = new FormData($("#myform")[0]);
		var url="EGI_detalles.php";

		

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
	<!-- Main -->
		<section id="main" class="container">
			<header><h2>Estadisticas predefinidas</h2></header>
			<div class="box">
				<div class="col-12 col-12-mobilep" style="text-align:center">
    				<h3>Estadistica General Intervencion</h3>
    				
    				<h3>  Caso: <?php echo $caso;?> Intervencion: <?php echo $intervencion;?> </h3>
    				<form  name="myform" id="myform" method='post' action="javascript:validar()">
    				
    				<input type='hidden' name="caso" id="caso" value="caso99">
					<input type='hidden' name="intervencion" id="intervencion" value="dolor">
					<input type='hidden' name="cabecera" id="cabecera" value="cosas">
									
    				<div class="row">
    						<div class="col-12">
    								<section class="box">
    									<div id='grafica' style="height: 650; width: 450;">
    									
        								
                                   		
                                   		</div>  
                                   		<hr color="blue" size=3>
                                   		
                                   		<div id='grafica2' style="height: 650; width: 450;">
                                   		<img id='img1'>
                                   		<img id='img2'>
                                   		</div>  
                                   		
                                   		                                           
                    				</section>
    							
    						</div>
    				</div>
    				<input type="submit" value="Aceptar">
    				</form>
    			</div>
			</div>
		</section>								
	</div>
 </body>
</html>


    
