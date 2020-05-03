<?php
session_start();

if(isset($_SESSION['id_u'])) {

    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    $myid_su=$_SESSION['id_su'];
    
    $_SESSION['mod']=1;
    
    
    
    ?>
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
    <title>Modificar Sujeto</title>
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
    						<h2>Modificar Sujeto</h2>						
    					</header>
    					
    					<div class="box">
    						<form method="post" id="myform" action="crearsujeto.php">
    							Modificar Sujeto<br><br>
    <?php 
    $sql="Select nombre,apellido1,apellido2 FROM sujeto_activo WHERE id_sujeto_activo=$myid_su";
    $resultado=mysqli_query($link, $sql);
    $ret=mysqli_fetch_array($resultado);
    
    
    echo "<br><table><tr><th>Nombre</th><th>Primer Apellido</th><th>Segundo Apellido</th></tr>";
    echo "<tr><td><input type='text' name='nombre' id='nombre' value='$ret[nombre]' required pattern='[a-z A-Z áéíóú]*'></td>";
    echo "<td><input type='text' name='apellido1' id='apellido1' value='$ret[apellido1]' pattern='[a-z A-Z áéíóú]*'></td>";
    echo "<td><input type='text' name='apellido2' id='apellido2' value='$ret[apellido2]' pattern='[a-z A-Z áéíóú]*'></td></tr>";
    echo "</table>";
    ?>
    <div class="col-12">
    <ul class="actions special">
    <li><input type='submit' value='Modificar' /></li>
    <li><input type="button" onclick="location.href='asunto.php';" value="Volver"><br></li>
    
    </ul>
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