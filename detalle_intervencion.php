<?php


session_start();

if(isset($_SESSION['id_u'])) {
    

    $link = mysqli_connect("localhost", "root", ".google.", "safe");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    if(isset($_GET['id_intervencion'])) {
        $myid_intervencion=base64_decode(mysqli_real_escape_string($link,$_GET['id_intervencion']));
        $_SESSION['id_intervencion']=$myid_intervencion;
    }
    else {
        if(isset($_SESSION['id_intervencion'])) {
            $myid_intervencion=$_SESSION['id_intervencion'];
        }
    }
    
    $respuesta=$_SESSION['respuesta'];
    
    ?>
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
    <title>Detalle Intervención</title>
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
    				ctx.fillText("Intervencion modificada",150,20);
    			}
    }
    </script>
    <script type="text/javascript">
    	function pregunta(){ 
    		if (confirm('¿Estas seguro de eliminar la intervención?')){ 
    			$.ajax({
    				type: "POST",
    				url: "eliminarintervencion.php",
    				contentType: false,
    				processData: false,
    				success: function(respuesta) {
    					location.href = "asunto.php";
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
    						<h2>Detalle Intervención</h2>						
    					</header>
    					<div align="center">
    						<canvas id="mensajes" width="300" height="30"></canvas>
    					</div>
    					<div class="box">
    						<form method="post" id="myform" action="modificarintervencion.php">
    							Detalles de la Intervención<br><br>
    <?php 
    $sql="Select id_tipo_intervencion,direccion,descripcion FROM intervencion WHERE id_intervencion=$myid_intervencion";
    $resultado=mysqli_query($link, $sql);
    
    
    
    echo "<br><table><tr><th>Tipo Intervención</th><th>Dirección</th><th>Descripción</th></tr>";
    $contador=0;
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        echo "<tr>";
        foreach ($line as $col_value) {
            if($contador==0) {
                $sql_tipo="Select nombre From tipo_intervencion where id_tipo_intervencion=$col_value";
                $resultado_tipo=mysqli_query($link, $sql_tipo);
                $ret_tipo=mysqli_fetch_array($resultado_tipo);
                echo "<td>".$ret_tipo['nombre']."</td>";
                $contador++;
            }
            else {
                if($contador<2) {
                    echo "<td>".$col_value."</td>";
                    $contador++;
                }
                else {
                    echo "<td>".$col_value."</td>";
                    $contador=0;
                }
            } 
        }
        echo "</tr>";
    }
    echo "</table>";
    
    $sql_borrar="Select * from evidencia where id_intervencion=$myid_intervencion";
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
    <li><input type="button" onclick="location.href='asunto.php';" value="Volver"><br></li><br>
    
    </form>
    <?php
    $sql="Select numero_intervencion from intervencion where id_intervencion=$myid_intervencion";
    $resultado=mysqli_query($link, $sql);
    $ret=mysqli_fetch_array($resultado);
    ?>
    <form action="nuevaevidencia.php" method="post" id="myform">
    <input type="hidden" name="intervencion" id="intervencion" value="<?php echo $ret['numero_intervencion']?>">
    <li><input type="submit" name="Agregar" id="Agregar" value="Agregar Evidencia"><li>
    </form>
    </ul>
    
    </div>		
    
    
    
    
    <?php 
    
    
    
    //cargo el sujeto de la intervención
    $sql_id_sujeto="SELECT id_sujeto_activo FROM INTERVENCION WHERE id_intervencion=$myid_intervencion";
    $resultado_id_sujeto=mysqli_query($link, $sql_id_sujeto);
    $ret=mysqli_fetch_array($resultado_id_sujeto);
    $_SESSION['id_sujeto']=$ret['id_sujeto_activo'];
    $sql="Select id_sujeto_activo,nombre,apellido1,apellido2 from sujeto_activo
    WHERE id_sujeto_activo=$ret[id_sujeto_activo] and id_sujeto_activo!=1";
    $resultado_sujeto=mysqli_query($link, $sql);
    $count=mysqli_num_rows($resultado_sujeto);
    if($count!=0) {
        echo "<center><br><b>Sujeto de la intervención</b><br>";
        echo "<br><table border='1' style='border-collapse: collapse; margin:0 auto; background-color: #def;border-style: none;'><tr><th>Nombre</th><th>Primer Apellido</th><th>Segundo Apellido</th></tr>";
        $contador=0;
        while ($line_sujeto = mysqli_fetch_array($resultado_sujeto, MYSQLI_ASSOC)) {
            echo "<tr>";
            foreach ($line_sujeto as $col_value) {
                if ($contador == 0) {
                    $id_su=base64_encode($col_value);
                    $contador++;
                }
                else {
                    if ($contador == 1) {
                        echo "<td>";
                        echo "<a href='detalle_sujeto.php?id_su=$id_su'>".$col_value."</a>"; 
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