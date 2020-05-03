<?php

session_start();

if(isset($_SESSION['id_u'])) {
    

    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    if(isset($_GET['id_registro'])) {
        $myid_registro=base64_decode($_GET['id_registro']);
        $_SESSION['id_registro']=$myid_registro;
    }
    else {
        if(isset($_SESSION['id_registro'])) {
            $myid_registro=$_SESSION['id_registro'];
        }
    }
    
    $respuesta=$_SESSION['respuesta'];
    
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
    <title>Detalles Registro</title>
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
    <script>
    		function respuesta() {
    			var respuesta=<?php echo $respuesta; ?>;
    			if(respuesta==1) {
    				var c = document.getElementById("mensaje");
    				var ctx = c.getContext("2d");
    				ctx.font = "bold 12px Verdana";
    				ctx.clearRect(0, 0, c.width, c.height);
    				ctx.strokeStyle = "#3DBA26";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#3DBA26";
    				ctx.textAlign = "center";
    				ctx.fillText("Estado modificado",150,20);
    				<?php $_SESSION['respuesta']=0; ?>
    			}
    		}
    </script>
    <script type="text/javascript">
    	function pregunta(){ 
    		if (confirm('¿Estas seguro de eliminar el registro?')){ 
    			$.ajax({
    				type: "POST",
    				url: "eliminarregistro.php",
    				contentType: false,
    				processData: false,
    				success: function(respuesta) {
    					location.href = "detalle_evidencia.php";
    				}
    			});
       		}
     		else {
     			location.href = "detalle_registro.php";
     	 	} 	
    	} 
    	</script> 
    
    
    </head>
    
    
    <body class="is-preload" onload="respuesta();">
    		<div id="page-wrapper">
    
    			<!-- Main -->
    				<section id="main" class="container">
    					
    					<header>
    						<h2>Detalles Registro</h2>						
    					</header>
    					<div align="center">
    						<canvas id="mensaje" width="300" height="30"></canvas>
    					</div>
    					<div class="box">
    						<form method="post" id="myform" action="modificarestado.php">
    							Detalles del registro de estado de la evidencia<br><br>
    <?php 
    $sql="select u.apodo,es.nombre as estado, p.nombre as programa, ap.nombre as accion_programa, h.hash, er.observaciones
    from evidencia_registro er
    inner join usuario u on er.id_usuario=u.id_usuario
    inner join estado_evidencia es on er.id_estado_evidencia=es.id_estado_evidencia
    left join programa p on er.id_programa=p.id_programa
    left join accion_programa ap on er.id_accion_programa=ap.id_accion_programa
    left join hash h on er.id_hash=h.id_hash
    where er.id_evidencia_registro=$myid_registro ORDER BY er.fecha_alta_estado ASC";
    $resultado=mysqli_query($link, $sql);
    
    
    
    echo "<br><table><tr><th>Apodo</th><th>Estado</th><th>Programa</th><th>Acción Programa</th><th>HASH</th><th>Observaciones</th></tr>";
    $contador=0;
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        echo "<tr>";
        foreach ($line as $col_value) {
            echo "<td>".$col_value."</td>";
        }
        echo "</tr>";
    }
    echo "</table>";

}
else {
    echo "Error";
}
?>
<div class="col-12">
<ul class="actions special">

<li><input type='submit' value='Modificar' />
<li><input type='button' onclick='pregunta();' value='Eliminar'><br></li>
<li><input type="button" onclick="location.href='detalle_evidencia.php';" value="Volver"><br></li><br>

</form>
</div>
</section>
</div>


</body>





