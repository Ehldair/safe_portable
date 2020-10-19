<?php
isset($_SESSION) || session_start();

// start a session

if(isset($_SESSION['id_u'])) {
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Fall� la conexi�n: %s\n", mysqli_connect_error());
        exit();
    }
    
    $respuesta=$_SESSION['respuesta'];
    
    $año=date("Y");
    $sql="Select año from año_viajes where año=$año";
    $result=mysqli_query($link, $sql);
    $count=mysqli_num_rows($result);
    if($count==0) {
        $sql="INSERT into año_viajes (año) values ($año)";
        mysqli_query($link, $sql);
    }
    $sql = "SELECT id_caso,c.año, c.numero,  c.nombre, c.descripcion FROM
    caso c inner join estado_caso e ON c.id_estado_caso=e.id_estado_caso
    WHERE e.estado='Abierto' AND id_caso!=1 ORDER BY año desc, numero desc";
    $resultado=mysqli_query($link, $sql);
    $count=mysqli_num_rows($resultado);
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    
    <head>
    
    
    	<title>SAFE</title>
    	<meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    	<link rel="stylesheet" href="assets/css/main.css" />
    	
    	
    			
    	<!-- Alonso -->
    		<script src="//code.jquery.com/jquery-latest.js"></script>
    		<script src="miscript.js"></script>
    		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    		<script src="js/jquery-3.4.1.js"></script>
    		<script>
    		function respuesta() {
    			var respuesta=<?php echo $respuesta; ?>;
    			if(respuesta==1) {
    				var c = document.getElementById("mensajes");
    				var ctx = c.getContext("2d");
    				ctx.font = "bold 12px Verdana";
    				ctx.clearRect(0, 0, c.width, c.height);
    				ctx.strokeStyle = "#3DBA26";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#3DBA26";
    				ctx.textAlign = "center";
    				ctx.fillText("Caso portable creado",150,20);
    				<?php $_SESSION['respuesta']=0; ?>
    				setTimeout(borrar,5000);
				}
    			else if(respuesta==2) {
    				var c = document.getElementById("mensajes");
    				var ctx = c.getContext("2d");
    				ctx.font = "bold 12px Verdana";
    				ctx.clearRect(0, 0, c.width, c.height);
    				ctx.strokeStyle = "#3DBA26";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#3DBA26";
    				ctx.textAlign = "center";
    				ctx.fillText("Caso eliminado",150,20);
    				<?php $_SESSION['respuesta']=0; ?>
    				setTimeout(borrar,5000);
				}
    			else if(respuesta==3) {
    				var c = document.getElementById("mensajes");
    				var ctx = c.getContext("2d");
    				ctx.font = "bold 12px Verdana";
    				ctx.clearRect(0, 0, c.width, c.height);
    				ctx.strokeStyle = "#3DBA26";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#3DBA26";
    				ctx.textAlign = "center";
    				ctx.fillText("Comisaría agregada",150,20);
    				<?php $_SESSION['respuesta']=0; ?>
    				setTimeout(borrar,5000);
				}
    			else if(respuesta==4) {
    				var c = document.getElementById("mensajes");
    				var ctx = c.getContext("2d");
    				ctx.font = "bold 12px Verdana";
    				ctx.clearRect(0, 0, c.width, c.height);
    				ctx.strokeStyle = "#3DBA26";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#3DBA26";
    				ctx.textAlign = "center";
    				ctx.fillText("Grupo agregado",150,20);
    				<?php $_SESSION['respuesta']=0; ?>
    				setTimeout(borrar,5000);
				}
    			else if(respuesta==5) {
    				var c = document.getElementById("mensajes");
    				var ctx = c.getContext("2d");
    				ctx.font = "bold 12px Verdana";
    				ctx.clearRect(0, 0, c.width, c.height);
    				ctx.strokeStyle = "#FF0000";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#FF0000";
    				ctx.textAlign = "center";
    				ctx.fillText("Caso con viaje asociado. No se elimina.",150,20);
    				<?php $_SESSION['respuesta']=0; ?>
    				
    			}
    		}
    		function borrar() {
    			var c = document.getElementById("mensajes");
    			var ctx = c.getContext("2d");
    			ctx.clearRect(0, 0, c.width, c.height);
    		}
    		</script>
    		<script>
    		function pregunta_Caso(opSelect){ 
    			var category=opSelect;
        		var url="eliminarcaso.php?mod=1";
    			if (confirm('¿Estas seguro de eliminar el caso?')){ 
    				$.ajax({
    					url:url,
    		        	type:"POST",
    		        	data:{category:category}

    		      	}).done(function(data){
						
    		    		  location.href = "inicio.php";   
    		      	});  
       			}
     			else {
     				
     	 		} 	
    		} 
			function cabecera(){
    			
    			$('#cabecera').load('cabecera.php');                
    			respuesta();
    			
    		};
    		</script> 

    
    </head>
    
    <body class="is-preload" onload="cabecera();">
	<div id="page-wrapper">
	<div id="cabecera">
    
    </div>
		<section id="main" class="container">
			<header>
				<h2> Casos</h2>
				<p>Casos abiertos</p>
				
			</header>			
			<div class="row">
						<div class="col-12">
							<!-- Table -->
								<section class="box">
									<div class="table-wrapper">
										<h3>Listado Casos Abiertos</h2>
										<div align="center">
						    				<canvas id="mensajes" width="350" height="30"></canvas>
						    			</div>
								

<?php 

if($count!=0) {

    echo "
								<table>
									<thead>
										<tr>
											<th>Caso</th>
                                            <th>Operacion</th>
											<th>Descripcion</th>
                                            <th></th>
                                            <th>Eliminar</th>
                                            
										</tr>
									</thead>
									<tbody>
			
	";


    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $id_caso= $line['id_caso'];
        $ano= $line['año'];
        $numero= $line['numero'];
        $nombre= $line['nombre'];
        $descripcion= $line['descripcion'];
        
        
        echo "<tr>
        					<td style='text-align: left'>
                            	<form>";
        $id_caso_original=$id_caso;
        $id_caso=base64_encode($id_caso);
        $ano2=substr($ano,2,2);
       
        ?>
    						<a href="asunto.php?id_caso=<?php echo $id_caso;?>" style="color: black" >
    							<b><?php echo $numero.'_'.$ano2;?></b>
    						</a>
    
                    
        <?php 
        echo "</td>";
        echo "<td align='left'>$nombre</td>";
        echo "<td align='left'>$descripcion</td>";
        echo "<td align='left'>";
        
        ?>
    						<a href="crear_casoportable.php?id_caso=<?php echo $id_caso;?>">
    							Crear Caso Portable
    						</a>
    
                    
        <?php 
        echo "</td>";
        echo "<td><a href='#' onclick='pregunta_Caso($id_caso_original);'>
                <img src='img/eliminar.png' alt='Enlace' width=20 height=20/>
                </a></td>";
        echo "</form>";
        echo "</tr>";
    }//while

echo "						<tbody>
					</table>
			</section>";


}  // if
else {
    echo "<br><b>";
    echo "NO EXISTEN CASOS";
    echo "</b>";
}

}
?>

			</div>
	
		
	</section>
	</div>
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