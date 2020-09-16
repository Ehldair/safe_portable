<?php


session_start();

if(isset($_SESSION['id_u'])) {
    

    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
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
    $myid_caso=$_SESSION['id_caso'];
    $respuesta=$_SESSION['respuesta'];
    
    ?>
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
    <title>Detalle Intervención</title>
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
    				setTimeout(borrar,5000);
    			}
    			if(respuesta==2) {
    				ctx.strokeStyle = "#3DBA26";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#3DBA26";
    				ctx.textAlign = "center";
    				ctx.fillText("Funcionario añadido",150,20);
    				setTimeout(borrar,5000);
    			}
    			if(respuesta==3) {
    				ctx.strokeStyle = "#3DBA26";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#3DBA26";
    				ctx.textAlign = "center";
    				ctx.fillText("Funcionario eliminado",150,20);
    				setTimeout(borrar,5000);
    			}
    }
    </script>
    <script type="text/javascript">
    	function borrar() {
			var c = document.getElementById("mensajes");
			var ctx = c.getContext("2d");
			ctx.clearRect(0, 0, c.width, c.height);
		};
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
    	function preguntaFuncionario(opSelect){ 
    		var category=opSelect;
    		var url="eliminarequipo.php";
			if (confirm('¿Estas seguro de quitar al funcionario de este equipo?')){ 
				$.ajax({
					url:url,
		        	type:"POST",
		        	data:{category:category}

		      	}).done(function(data){
					
		    		  location.href = "detalle_intervencion.php";   
		      	});  
   			}
 			else {
 				
 	 		} 	
		} 
    	</script> 
    
    </head>
    
    <body class="is-preload" onload="respuesta();">
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
    									<li><a href="nuevoasunto.php">Nuevo</a></li>
    									
    								</ul>
    							</li>

    							
    							
    							
    							<li><a href="login.php" class="button">Cerrar</a></li>
    						</ul>
    					</nav>
    				</header>
    			
    
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
    							<h3>Detalles de la Intervención</h3>
    <?php 
    $sql="Select id_tipo_intervencion,direccion,descripcion FROM intervencion WHERE id_intervencion=$myid_intervencion";
    $resultado=mysqli_query($link, $sql);
    
    
    
    echo "
								<table>
									<thead>
										<tr>
											<th>Interveción</th>
                                            <th>Dirección</th>
											<th>Descripcion</th>
                                                                                        
										</tr>
									</thead>
									<tbody>
			
	";
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
    echo "</tbody></table>";
    
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
    <form action="listado_intervenciones.php" method="post" id="myform">
    <input type="hidden" name="intervencion" id="intervencion" value="<?php echo $ret['numero_intervencion']?>">
    <li><input type="submit" name="Agregar" id="Agregar" value="Agregar Evidencia"></li><br>
    </form>
    <form action="nuevoequipo.php" method="POST" id="myform2"> 
    <input type="hidden" name="intervencion" id="intervencion" value="<?php echo $myid_intervencion?>">
    <li><input type="submit" name="AgregarEquipo" id="AgregarEquipo" value="Agregar Equipo"></li><br>
    </form>
    </ul>
    
    </div>		
    
    <?php 
    
    
    
    //cargo el equipo de la intervención
    $sql_apodo="SELECT e.id_usuario,u.apodo FROM EQUIPO_INTERVENCION e
    INNER JOIN usuario u ON u.id_usuario=e.id_usuario
    WHERE id_intervencion=$myid_intervencion";
    $resultado_apodo=mysqli_query($link, $sql_apodo);
    $count=mysqli_num_rows($resultado_apodo);
    if($count!=0) {
        
        echo "<h3>Equipo de la intervención</h3>";
		echo "
								<table>
									<thead>
										<tr>
											<th>Apodo</th>
										</tr>
									</thead>
									<tbody>";
        $contador=0;
        while($line=mysqli_fetch_array($resultado_apodo)) {
            $apodo=$line['apodo'];
            $id_usuario=$line['id_usuario'];
            echo "<tr><td>$apodo</td>";
            echo "<td align='right'><a href='#' onclick='preguntaFuncionario($id_usuario);'>
    	    <img src='img/eliminar.png' alt='Enlace' width=20 height=20/>
    	    </a></td></tr>";
        }
        echo "</table>";
    }


    
    
    
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
        echo "<h3>Sujeto de la intervención</h3>";
		echo "
								<table>
									<thead>
										<tr>
											<th>Nombre</th>
                                            <th>Primer Apellido</th>
											<th>Segundo Apellido</th>
                                                                                        
										</tr>
									</thead>
									<tbody>
			
	";
        $contador=0;
        while ($line_sujeto = mysqli_fetch_array($resultado_sujeto, MYSQLI_ASSOC)) {
            $id_sujeto_activo=$line_sujeto['id_sujeto_activo'];
            $nombre=$line_sujeto['nombre'];
            $apellido1=$line_sujeto['apellido1'];
            $apellido2=$line_sujeto['apellido2'];
            $id_su=base64_encode($id_sujeto_activo);
            echo "<tr>";
            echo "<td><a href='detalle_sujeto.php?id_su=$id_su'>$nombre</a></td>";
            echo "<td>$apellido1</td>";
            echo "<td>$apellido2</td>";
            echo "</tr>";
        echo "</tbody></table>";
        }
    }
    //cargo las evidencias de la intervención
    $resultado_evidencias = mysqli_query($link, "select  e.tiene_subevidencias, e.id_evidencia, e.nombre, e.numero_evidencia, s.nombre as nom_sub,  e.n_s, e.capacidad, e.marca, e.modelo, e.alias
    from evidencia e
    inner join tipo_evidencia t on t.id_tipo_evidencia=e.id_tipo_evidencia
    inner join subtipo_evidencia s on s.id_subtipo_evidencia=e.id_subtipo_evidencia
    inner join caso c on c.id_caso=e.id_caso
    inner join evidencia_registro er ON er.id_evidencia=e.id_evidencia
    where c.id_caso=$myid_caso AND e.id_intervencion=$myid_intervencion AND relacionado_con is null
    GROUP BY e.id_evidencia
    order By nombre asc");
    $count_evidencias=mysqli_num_rows($resultado_evidencias);
    if($count_evidencias!=0) {
        echo "<h3>Evidencias de la intervención</h3>";
        echo "
								<table>
									<thead>
										<tr>
											<th>Nombre</th>
                                            <th>Depende de</th>
                                            <th>Subtipo</th>
                                            <th>N/S</th>
                                            <th>Marca</th>
                                            <th>Modelo</th>
                                            <th>Estado</th>
                                            <th>Ordenador Extracción</th>
                                            <th></th>   
										</tr>
									</thead>
									<tbody>
    		    
	";
        $contador=0;
        while ($line_evidencias = mysqli_fetch_array($resultado_evidencias, MYSQLI_ASSOC)) {
            $tiene_sub=$line_evidencias['tiene_subevidencias'];
            $id_evidencia=$line_evidencias['id_evidencia'];
            $nombre=$line_evidencias['nombre'];
            $numero_evidencia=$line_evidencias['numero_evidencia'];
            $subtipo=$line_evidencias['nom_sub'];
            $n_s=$line_evidencias['n_s'];
            //      $capacidad=$line_evidencias['capacidad'];
            $marca=$line_evidencias['marca'];
            $modelo=$line_evidencias['modelo'];
            $alias=$line_evidencias['alias'];
            echo "<tr>";
            echo "<td>";
            $entra = 0;
            $nombre_original = $nombre;
            $nombre=base64_encode($nombre_original);
            $numero=base64_encode($numero_evidencia);
            echo "<a style='color:#000000;' href='detalle_evidencia.php?nombre=$nombre&numero=$numero'>$nombre_original$numero_evidencia</a>";
            if(!empty($alias)) {
                echo "<br>($alias)";
            }
            echo "</td>";
            echo "<td></td>";
            echo "<td>".$subtipo."</td>";
            echo "<td>".$n_s."</td>";
            //    echo "<td>".$capacidad."</td>";
            echo "<td>".$marca."</td>";
            echo "<td>".$modelo."</td>";
            $resultado_estado=mysqli_query($link, "select max(er.id_estado_evidencia) as id_estado from evidencia_registro er
            where id_evidencia=$id_evidencia");
            $ret_estado=mysqli_fetch_array($resultado_estado);
            $resultado_estado_nombre=mysqli_query($link, "select nombre from estado_evidencia where id_estado_evidencia=$ret_estado[id_estado]");
            $ret_nombre=mysqli_fetch_array($resultado_estado_nombre);
            $resultado_ordenador=mysqli_query($link, "select o.nombre_ordenadores as ordenador from evidencia_registro er
            Inner join ordenadores o on o.id_ordenadores=er.id_ordenadores
            where id_evidencia=$id_evidencia");
            $ret_ordenador=mysqli_fetch_array($resultado_ordenador);
            echo "<td>".$ret_nombre['nombre']."</td>";
            if(!empty($ret_ordenador['ordenador'])) {
                echo "<td>".$ret_ordenador['ordenador']."</td>";
            }
            else {
                echo "<td></td>";
            }
            $entra = 1;
            if($tiene_sub!=1) {
                echo "<td><a href='#' onclick='preguntaEvidencia($id_evidencia);'>
                <img src='img/eliminar.png' alt='Enlace' width=20 height=20/>
                </a></td>";
            }
            else {
                echo "<td></td>";
            }
            if ($tiene_sub == 1 and $entra == 1) {
                echo "</tr>";
                $entra = 0;
                $result = mysqli_query($link, "select e.nombre, e.numero_evidencia, s.nombre as nom_sub,  e.n_s, e.capacidad, e.marca, e.modelo,e.id_evidencia, e.alias  from evidencia e
                        inner join tipo_evidencia t on t.id_tipo_evidencia=e.id_tipo_evidencia
                        inner join subtipo_evidencia s on s.id_subtipo_evidencia=e.id_subtipo_evidencia
                        inner join caso c on c.id_caso=e.id_caso
                        inner join evidencia_registro er ON er.id_evidencia=e.id_evidencia
                        where c.id_caso='$myid_caso' AND relacionado_con=$id_evidencia
                        GROUP BY e.id_evidencia
                        order By nombre asc");
                $result2 = mysqli_query($link, "select e.nombre as nom, e.numero_evidencia as num from evidencia e where id_evidencia=$id_evidencia");
                $ret=mysqli_fetch_array($result2);
                while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $nombre=$line['nombre'];
                    $numero_evidencia=$line['numero_evidencia'];
                    $subtipo=$line['nom_sub'];
                    $n_s=$line['n_s'];
                    $capacidad=$line['capacidad'];
                    $marca=$line['marca'];
                    $modelo=$line['modelo'];
                    $id_evidencia_sub=$line['id_evidencia'];
                    $alias=$line['alias'];
                    echo "<tr>";
                    echo "<td>";
                    $nombre_original = $nombre;
                    $nombre=base64_encode($nombre_original);
                    $numero=base64_encode($numero_evidencia);
                    echo "<a style='color:red;' href='detalle_evidencia.php?nombre=$nombre&numero=$numero'>$nombre_original$numero_evidencia</a>";
                    if(!empty($alias)) {
                        echo "<br>($alias)";
                    }
                    echo "</td>";
                    echo "<td>";
                    echo $ret['nom'].$ret['num'];
                    echo "</td>";
                    echo "<td>".$subtipo."</td>";
                    echo "<td>".$n_s."</td>";
                    // echo "<td>".$capacidad."</td>";
                    echo "<td>".$marca."</td>";
                    echo "<td>".$modelo."</td>";
                    $resultado_estado=mysqli_query($link, "select max(er.id_estado_evidencia) as id_estado from evidencia_registro er
                            where id_evidencia=$id_evidencia_sub");
                    $ret_estado=mysqli_fetch_array($resultado_estado);
                    $resultado_estado_nombre=mysqli_query($link, "select nombre from estado_evidencia where id_estado_evidencia=$ret_estado[id_estado]");
                    $ret_nombre=mysqli_fetch_array($resultado_estado_nombre);
                    $resultado_ordenador=mysqli_query($link, "select o.nombre_ordenadores as ordenador from evidencia_registro er
                            Inner join ordenadores o on o.id_ordenadores=er.id_ordenadores
                            where id_evidencia=$id_evidencia_sub");
                    $ret_ordenador=mysqli_fetch_array($resultado_ordenador);
                    echo "<td>".$ret_nombre['nombre']."</td>";
                    if(!empty($ret_ordenador['ordenador'])) {
                        echo "<td>".$ret_ordenador['ordenador']."</td>";
                    }
                    else {
                        echo "<td></td>";
                    }
                    echo "<td><a href='#' onclick='preguntaEvidencia($id_evidencia_sub);'>
    	                    <img src='img/eliminar.png' alt='Enlace' width=20 height=20/>
    	                    </a></td>";
                    echo "</tr>";
                }
            }
            echo "</tr>";
        }
        echo "</tbody></table></div>";
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


  <!-- Pelayo -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery.dropotron.min.js"></script>
    <script src="assets/js/jquery.scrollex.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>


</html>