<?php
session_start();

if(isset($_SESSION['id_u'])) {
    
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    $myid_caso=$_SESSION['id_caso'];
    
    
    ?>
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
    <title>Listado Hashes</title>
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
    	function cabecera(){
			
			$('#cabecera').load('cabecera.php');                
			
		}; 
    	</script> 
    
    </head>
    
    <body class="is-preload" onload="cabecera();">
    		<div id="page-wrapper">
	<div id="cabecera">
    
    </div>
    			
    			
    			<!-- Main -->
    				<section id="main" class="container">
    					
    					<header>
    						<h2>Listado Hashes</h2>						
    					</header>
    					<div align="center">
    						<canvas id="mensajes" width="300" height="30"></canvas>
    					</div>
    					<div class="box">
    						<form method="post" id="myform" action="modificarsujeto.php">
    							<h3>Listado Hashes</h3>
    <?php 
    $sql="select distinct e.nombre, e.numero_evidencia,h.hash from evidencia_registro er
    inner join evidencia e on e.id_evidencia=er.id_evidencia
    inner join hash h on h.id_hash=er.id_hash
    where id_estado_evidencia=2 and e.id_caso=$myid_caso
    order By nombre asc, numero_evidencia";
    $resultado=mysqli_query($link, $sql);
    
   
	echo "
								<table>
									<thead>
										<tr>
											<th>Nombre</th>
                                            <th>Hash</th>
											
                                                                                      
										</tr>
									</thead>
									<tbody>
			
	";
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $nombre=$line['nombre'];
        $numero=$line['numero_evidencia'];
        $hash=$line['hash'];
        echo "<tr>";
        echo "<td>".$nombre.$numero."</td>";
        echo "<td>".$hash."</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
    
    ?>
    
    <div class="col-12">
    <ul class="actions special">    
    <li><input type="button" onclick="location.href='asunto.php';" value="Volver" class="button special small"></li>
    </ul>
    </div>
    <?php 
    
   
}
else {
    echo "Error";
}
?>
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



</html>