<?php
session_start();

if(isset($_SESSION['id_u'])) {
 
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    if(isset($_GET['id_su'])) {
        $myid_su=base64_decode(mysqli_real_escape_string($link,$_GET['id_su']));
        $_SESSION['id_su']=$myid_su;
    }
    else {
        if(isset($_SESSION['id_su'])) {
            $myid_su=$_SESSION['id_su'];
        }
    }
    
    $respuesta=$_SESSION['respuesta'];
    
    ?>
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
    <title>Detalle Sujeto</title>
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
    			var c = document.getElementById("mensajes");
    			var ctx = c.getContext("2d");
    			ctx.font = "bold 12px Verdana";
    			if(respuesta==1) {
    				ctx.strokeStyle = "#3DBA26";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#3DBA26";
    				ctx.textAlign = "center";
    				ctx.fillText("Sujeto modificado",150,20);
    			}
    }
    </script>
    <script type="text/javascript">
    	function pregunta(){ 
    		if (confirm('¿Estas seguro de eliminar el sujeto?')){ 
    			$.ajax({
    				type: "POST",
    				url: "eliminarsujeto.php",
    				contentType: false,
    				processData: false,
    				success: function(respuesta) {
    					window.location="asunto.php";
    				}
    			});
       		}
     		else {
     			location.href = "asunto.php";
     	 	} 	
    	} 
    	</script> 
    
    </head>
    
    <body class="is-preload" onload="respuesta();">
    		<div id="page-wrapper">
    
    			<!-- Main -->
    				<section id="main" class="container">
    					
    					<header>
    						<h2>Detalle Sujeto</h2>						
    					</header>
    					<div align="center">
    						<canvas id="mensajes" width="300" height="30"></canvas>
    					</div>
    					<div class="box">
    						<form method="post" id="myform" action="modificarsujeto.php">
    							Detalles de Sujeto<br><br>
    <?php 
    $sql="Select nombre,apellido1,apellido2 FROM sujeto_activo WHERE id_sujeto_activo=$myid_su";
    $resultado=mysqli_query($link, $sql);
    
    echo "<br><table><tr><th>Nombre</th><th>Primer Apellido</th><th>Segundo Apellido</th></tr>";
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        echo "<tr>";
        foreach ($line as $col_value) {
            echo "<td>".$col_value."</td>";
            }
        echo "</tr>";
    }
    echo "</table>";
    
    $sql_borrar="Select * from intervencion where id_sujeto_activo=$myid_su";
    $resultado_borrar=mysqli_query($link, $sql_borrar);
    $count_borrar=mysqli_num_rows($resultado_borrar)
    
    ?>
    <div class="col-12">
    <ul class="actions special">
    
    <li><input type='submit' value='Modificar' />
    <?php
    if ($count_borrar==0) {
        echo "<li><input type='button' onclick='pregunta();' value='Eliminar'><br></li>";
    }
    else {
        echo "<li><input type='button' onclick='location.href='asunto.php';' value='No se puede eliminar' disabled><br></li>";
        
    }
    ?>
    <li><input type="button" onclick="location.href='asunto.php';" value="Volver"><br></li>
    
    </ul>
    </div>		
    
    
    
    
    </form>
    <?php 
    
    
    
    //cargo la lista de intervenciones en las que es el sujeto activo
    $sql="Select distinct i.id_intervencion as id_int, t.nombre as nom, i.direccion as dir, i.descripcion as des FROM intervencion i 
    INNER JOIN tipo_intervencion t on t.id_tipo_intervencion=i.id_tipo_intervencion
    WHERE id_sujeto_activo=$myid_su";
    $resultado_intervencion=mysqli_query($link, $sql);
    $count=mysqli_num_rows($resultado_intervencion);
    if($count!=0) {
        echo "<center><br><b>Intervenciones en las que participa</b><br>";
        echo "<br><table border='1' style='border-collapse: collapse; margin:0 auto; background-color: #def;border-style: none;'><tr><th>Tipo</th><th>Dirección</th><th>Descripcion</th></tr>";
        $contador=0;
        while ($line_intervencion = mysqli_fetch_array($resultado_intervencion, MYSQLI_ASSOC)) {
            echo "<tr>";
            foreach ($line_intervencion as $col_value) {
                if ($contador == 0) {
                    $id_intervencion=base64_encode($col_value);
                    $contador++;
                }
                else {
                    if ($contador == 1) {
                        echo "<td>";
                        echo "<a href='detalle_intervencion.php?id_intervencion=$id_intervencion'>".$col_value."</a>"; 
                        echo "</td>";
                        $contador ++;
                    }
                    else {
                        if ($contador < 3) {
                            echo "<td align='center'>";
                            echo $col_value;
                            echo "</td>";
                            $contador ++;
                        }
                        else {
                            echo "<td align='center'>";
                            echo $col_value;
                            echo "</td>";
                            $contador = 0;
                        }
                    }
                }
            }
            echo "</tr>";
        }
        echo "</table>";
    }
}
else {
    echo "Error";
}
?>
</div>
</section>
</div>


</body>



</html>