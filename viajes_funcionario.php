<?php
session_start();

if(isset($_SESSION['id_u'])) {

    $link = mysqli_connect("localhost", "root", ".google.", "safe");

    if(isset($_GET['id_funcionario'])) {
        $myid_funcionario=base64_decode(mysqli_real_escape_string($link,$_GET['id_funcionario']));
        $_SESSION['id_funcionario']=$myid_funcionario;
    }
    else {
        if(isset($_SESSION['id_funcionario'])) {
            $myid_funcionario=$_SESSION['id_funcionario'];
        }
    }
    
    if(isset($_GET['mod'])) {
        $mymod=$_GET['mod'];
    }
    else {
        $mymod=0;
    }
   
    $myaño=$_SESSION['año'];
    $sql="Select apodo from usuario where id_usuario=$myid_funcionario";
    $result=mysqli_query($link, $sql);
    $ret=mysqli_fetch_array($result);
    
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Casos</title>
    	<meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    	<link rel="stylesheet" href="assets/css/main.css" />
    	
    			
    	<!-- Alonso -->
    		<script src="//code.jquery.com/jquery-latest.js"></script>
    		<script src="miscript.js"></script>
    		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    		<script src="js/jquery-3.4.1.js"></script>
    
    </head>
    <script>
    
    function cambiaraño() {
    	var año=document.getElementById("año").value;
        var parametros={"año":año};
        $.ajax({
            type: "POST",
            url: "obtenerviajes_funcionario.php",
            data: parametros,
            success: function(response) {
                $('#div-results').html(response);                
            }
        });
    }
    
    </script>
    <?php 
    if($mymod==0) {
        
        $sql_viajes="select vi.id_caso,vi.ciudad,  timestampdiff(DAY, v.fecha_inicio, v.fecha_fin) as noches from viajes_funcionario v
    INNER JOIN viajes vi ON vi.id_viajes=v.id_viajes
    where id_usuario=$myid_funcionario AND YEAR(fecha_inicio)=$myaño order by v.fecha_inicio DESC" ;
        $result_viajes=mysqli_query($link, $sql_viajes);
        $count_viajes=mysqli_num_rows($result_viajes);
    ?>
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
    			<div id='div-results'>
    		<section id="main" class="container">
    			<header>
    				<h2> Viajes</h2>
    				<p>Viajes Totales de <?php echo $ret['apodo']; ?> en <?php echo $myaño;?></p>
    			</header>		
    		
    			<div class="row">
    						<div class="col-12">
    							<!-- Table -->
    								<section class="box">
    									<div class="table-wrapper">
<?php     									  
        if($count_viajes!=0) {
    
            echo "
        								<table>
        									<thead>
                                                <tr>
                                                    <th>AÑO $myaño</th>
                                                </tr>
        										<tr>
        											<th>Ciudad</th>
                                                    <th>Noches</th>
                                                  <th><select id='año' name='año' onchange='cambiaraño()'>";
                                            $resultado = mysqli_query($link, "select año FROM año_viajes order by año");
                                            while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                                                foreach ($line as $col_value) {
                                                    if($col_value==$myaño) {
                                                        echo "<option value='$col_value' selected>$col_value</option>'";
                                                      
                                                    }
                                                    else {
                                                        echo "<option value='$col_value'>$col_value</option>'";    								        
                                                    }
                                                }
                                            }   
                                            echo "</select></th>  
                                                        </tr>
                                                
        									       </thead>
                                                <tbody>";
            $contador=0;
            while ($line = mysqli_fetch_array($result_viajes, MYSQLI_ASSOC)) {
                echo "<tr>";
                foreach ($line as $col_value) {
                    if($contador==0) {
                        $id_caso=base64_encode($col_value);
                        $contador++;
                    }
                    else if($contador==1) {
                        echo "<td align='left'><a href='asunto.php?id_caso=$id_caso'>$col_value</a></td>";
                        $contador++;
                    }
                    else {
                        echo "<td align='left'>$col_value<td>";
                        $contador=0;
                    }
                }
                echo "</tr>";
            } 
        }
        else {
            echo " <table>
                    <thead>
                        <tr>
                        <th></th><th></th>
                            <th><select id='año' name='año' onchange='cambiaraño()'>";
            $resultado = mysqli_query($link, "select año FROM año_viajes order by año");
            while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                foreach ($line as $col_value) {
                    if($col_value==$myaño) {
                        echo "<option value='$col_value' selected>$col_value</option>'";
                        
                    }
                    else {
                        echo "<option value='$col_value'>$col_value</option>'";
                    }
                }
            }
            echo "</select></th>
                                            </tr>
                                            </thead>
                                            <tbody>";
            echo "<tr><td>No hay viajes en el año $myaño</td><td></td><td></td></tr><br>";
        }
    }
    else {
        $sql_años="Select DISTINCT YEAR(fecha_inicio) FROM viajes_funcionario where id_usuario=$myid_funcionario ORDER BY YEAR(FECHA_INICIO) DESC";
        $result_años=mysqli_query($link, $sql_años);
        $count_años=mysqli_num_rows($result_años);
       
        ?>
        
        <div id="page-wrapper">
        
        <!-- Main -->
        <section id="main" class="container">
        <header>
        <h2> Viajes</h2>
        <p>Viajes Totales de <?php echo $ret['apodo']; ?></p>
    			</header>			
    			<div class="row">
    						<div class="col-12">
    							<!-- Table -->
    								<section class="box">
    									<div class="table-wrapper">
<?php     									  
        if($count_años!=0) {
    
    
    								
                                             while ($fila_años = mysqli_fetch_row($result_años)) {
                                                    echo "<table>
    									            <thead>";
                                                    echo "<tr>";
                                                    echo "<th>AÑO $fila_años[0]</th><th></th><th></th>

                                                    </tr>
                                                    <tr>
                                                        <th>Ciudad</th>
                                                        <th>Noches</th>
                                                        <th></th>
                                                    </tr>";
                                             
 
        echo "  							       </thead>
                                                <tbody>";;
        $contador=0;
        $noches=0;
        $sql_viajes="select vi.id_caso,vi.ciudad,  timestampdiff(DAY, v.fecha_inicio, v.fecha_fin) as noches from viajes_funcionario v
        INNER JOIN viajes vi ON vi.id_viajes=v.id_viajes
        where id_usuario=$myid_funcionario AND YEAR(fecha_inicio)=$fila_años[0]";
        $result_viajes=mysqli_query($link, $sql_viajes);
        while ($line = mysqli_fetch_array($result_viajes, MYSQLI_ASSOC)) {
            echo "<tr>";
            foreach ($line as $col_value) {
                if($contador==0) {
                    $id_caso=base64_encode($col_value);
                    $contador++;
                }
                else if($contador==1) {
                    echo "<td align='left'><a href='asunto.php?id_caso=$id_caso'>$col_value</a></td>";
                    $contador++;
                }
                else {
                    $noches=$noches+$col_value;
                    echo "<td align='left'>$col_value<td>";
                    $contador=0;
                }
            }
            echo "</tr>";
            
        }
        echo "<tr> <td></td><td>Noches Totales: ".$noches."</td><td></td></tr>";
        
                                                 }
                                                 
                                                 
        echo "</div>";
        
        }
        else {
            echo "<div align='center'>No hay viajes</div><br>";
        }
    }
}
else {
    echo "Error";
}
echo "</div></table>";

?>
		<div align='center'>  
   		<input type="button" onclick="location.href='viajes_funcionario.php?mod=1';" value="Ver Todos">
        <input type="button" onclick="location.href='viajes_año.php';" value="Volver"><br>
        </div>

   </div>
   </section>
   </div>
   </div>
   </section>
   </div>

    </div>
    
    </section>
    </div>
	</div>    
    
   
    </section>
     </div>
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