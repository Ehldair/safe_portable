<?php

session_start();


if(isset($_SESSION['id_u'])) {
    #imprimimos las variables que estas enviando para saber si estan llegando completas
    
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    // comprobar la conexi�n
    if (mysqli_connect_errno()) {
        printf("Fall� la conexi�n: %s\n", mysqli_connect_error());
        exit();
    }
    $sql = "SELECT id_usuario, nombre, apodo, cp, dni, telefono, usuario FROM usuario ORDER BY id_usuario DESC";
    $resultado=mysqli_query($link, $sql);
    $count=mysqli_num_rows($resultado);
    
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Modificar usuario</title>
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
    
    </head>
    
    <body class="is-preload">
    	<div id="page-wrapper">
    
    	<!-- Main -->
    		<section id="main" class="container">
    			<header>
    				<h2> Gestionar Usuarios</h2>
    				<p>Seleccionar usuario a modificar/eliminar</p>
    			</header>			
    			<div class="row">
    						<div class="col-12">
    							<!-- Table -->
    								<section class="box">
    									<div class="table-wrapper">
    
    <?php 
    
    if($count!=0) {
    
    echo "
    								<table>
    									<thead>
    										<tr>
    											<th>Apodo</th>
                                                <th>Nombre</th>
    											<th>C.P</th>
                                                <th>D.N.I</th>
                                                <th>Telefono</th>
    											<th>Usuario de la APP</th>
                                                
    										</tr>
    									</thead>
    									<tbody>
    			
    	";
    
    
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $id_usuario = $line['id_usuario'];
        $nombre= $line['nombre'];
        $apodo= $line['apodo'];
        $cp= $line['cp'];
        $dni= $line['dni'];
        $telefono= $line['telefono'];
        $usuario= $line['usuario'];
        
        
        echo "<tr>
    					<td style='text-align: left'>
                        	<form action='modusuario.php' method='POST'>";
        echo "<input type='hidden' name='id_usuario' id='id_usuario' value=$id_usuario>";
        
        echo "<input type='submit' class='sinbordefondo' value=$apodo></td>";
        echo "<td align='left'>$nombre</td>";
        echo "<td align='left'>$cp</td>";
        echo "<td align='left'>$dni</td>";
        echo "<td align='left'>$telefono</td>";    
        echo "<td align='left'>$usuario</td>";
        
        echo "</form>";
        echo "</tr>";
    } //while
    
    echo "						<tbody>
    					</table>
    			</section>";
    
    }  // if
    
    else {
        echo "<br><b>";
        echo "NO EXISTEN USUARIOS";
        echo "</b>";
    }
}
else {
    echo "Error";
}
?>

			</div>
		</div>
		
	</section>	
	</div>
</body>
</html>