<?php
session_start();

if(isset($_SESSION['id_u'])) {

    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    $myid_registro=$_SESSION['id_registro'];
    
    $_SESSION['mod']=1;
    
    ?>
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
    <title>Modificar Estado</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    

    
    <!-- Alonso -->
    <script src="//code.jquery.com/jquery-latest.js"></script>
    <script src="miscript.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <script src="js/jquery-3.4.1.js"></script>
    <script src="miscript.js"></script>
     <script type="text/javascript">
     
     function accion(opSelect){
    	 	var estado=document.getElementById("estado").value
    		var category=opSelect;
    		var url="obtenerprograma.php";
    		if(estado!=3) {
    			var pro= $.ajax({
    				url:url,
	    	        type:"POST",
    		        data:{category:category}
    
    		      }).done(function(data){
    
    	    	        $("#accion_programa").html(data);
    	      	})
    		}	    
    	};
    </script>
    <script>
    function cambiarhash() {
    	var est=document.getElementById('estado').value;
    	if(est==3) {
    		document.getElementById('num_hash').disabled = true;
    		document.getElementById('tipo_hash').disabled = true;
    		document.getElementById('accion_programa').disabled=true;
    		document.getElementById('accion_programa').value='';
    	
    	}
    	else {
    		
    		document.getElementById('accion_programa').disabled=false;
    		document.getElementById('num_hash').disabled = false;
    		document.getElementById('tipo_hash').disabled = false;
    	}
    }
    function cabecera(){
		
		$('#cabecera').load('cabecera.php');                
		cambiarhash();
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
    						<h2>Modificar Estado</h2>						
    					</header>
    					
    					<div class="box">
    						<form method="post" id="myform" action="crearestado.php">
    							<h3>Modificar Estado</h3>
    							
    							<div class="row gtr-50 gtr-uniform">
    							
    								<div class="col-3 col-12-mobilep">	
    							
    							
    										
    <?php 
    
    //se cargan todos los datos necesarios para la posterior modificación del estado de la evidencia
    $sql="select u.id_usuario,u.apodo as apodo,es.id_estado_evidencia,es.nombre as estado,p.id_programa, p.nombre as programa,ap.id_accion_programa, ap.nombre as accion_programa,
    ti.id_tipo_hash, ti.tipo_hash, h.id_hash, h.hash as hash, er.observaciones as obs, date_format(fecha_alta_estado, '%Y-%m-%dT%H:%i') as fecha_alta_estado,er.id_ordenadores, o.nombre_ordenadores
    from evidencia_registro er
    inner join usuario u on er.id_usuario=u.id_usuario
    inner join estado_evidencia es on er.id_estado_evidencia=es.id_estado_evidencia
    left join ordenadores o ON o.id_ordenadores=er.id_ordenadores
    left join programa p on er.id_programa=p.id_programa
    left join accion_programa ap on er.id_accion_programa=ap.id_accion_programa
    left join hash h on er.id_hash=h.id_hash
    left join tipo_hash ti on h.id_tipo_hash=ti.id_tipo_hash
    where er.id_evidencia_registro=$myid_registro ORDER BY er.fecha_alta_estado ASC";
    $resultado=mysqli_query($link, $sql);
    $ret=mysqli_fetch_array($resultado);
    
    
    //se carga el usuario ya grabado y se crea el select con el resto de usuarios

    
    echo "<select name='usuario' id='usuario'>";
    echo "<option value=$ret[id_usuario] selected>$ret[apodo]</option>";
    $contador=0;
    $resultado = mysqli_query($link, "select id_usuario, apodo FROM usuario where id_usuario!=$ret[id_usuario]");
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        foreach ($line as $col_value) {
            if($contador==0) {
                    echo "<option value=$col_value>";
                    $contador++;
                    
            }
            else {
                    echo "$col_value</option>";
                    $contador=0;
            } 
        }
    }
    
    echo "</select>";
?>
	</div>
	
	<div class="col-3 col-12-mobilep">	
 
<?php    
    //se carga el estado ya grabado y se crea el select con el resto de estados 
     echo "
    
    <select name='estado' id='estado' required onchange='cambiarhash(this.value);'>";
    $contador=0;
    echo "<option value=$ret[id_estado_evidencia] selected>$ret[estado]</option>";
    $resultado = mysqli_query($link, "select id_estado_evidencia, nombre FROM estado_evidencia WHERE id_estado_evidencia!=1 AND id_estado_evidencia!=$ret[id_estado_evidencia]");
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        foreach ($line as $col_value) {
            if($contador==0) {
                echo "<option value=$col_value>";
                $contador++;
            }
            else {
                echo "$col_value</option>";
                $contador=0;
            }
            
            
        }
    }
    echo "</select>";
    
    
    //se carga el programa ya grabado y se crea el select con el resto de programas
    
?>
	</div>
	
	<div class="col-3 col-12-mobilep">	
		
<?php
   
    
      
    echo "<select id='programa' name='programa' onchange='accion(this.value);'>";
   
    
        
    $contador=0;
    if($ret['id_programa']!=null OR $ret['id_programa']!='') {
        $resultado = mysqli_query($link, "select id_programa, nombre FROM programa WHERE id_programa!=$ret[id_programa]");
        echo "<option value=$ret[id_programa]>$ret[programa]</option>";
    }
    else {
        $resultado = mysqli_query($link, "select id_programa, nombre FROM programa");
        echo "<option value=''>Selecciona el programa</option>";
    }
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        foreach ($line as $col_value) {
            if($contador==0) {
                echo "<option value=$col_value>";
                $contador++;
            }
            else {
                echo "$col_value</option>";
                $contador=0;
            }
        }
    }
    
    echo "</select>";

?>
		</div>
		
		<div class="col-3 col-12-mobilep">	
	
<?php
    
    //cargo la accion de programa ya grabada y creo el select con el resto de acciones de programa
    
    echo "    
    <select id='accion_programa' name='accion_programa'>
    <option value=$ret[id_accion_programa]>$ret[accion_programa]</option>";     	
    echo "</select>";

?>
		</div>
		
	
	
	
	<div class="col-3 col-12-mobilep">	
<?php    
		
    // se cargan la fecha
    
    
    echo "<input type='datetime-local' name='fecha' id ='fecha' value='$ret[fecha_alta_estado]' size=80>";
?>

	</div>
	<div class="col-5 col-12-mobilep">		
<?php 

   
    // se carga el hash
    	
    echo "
    <input type='text' name='num_hash' id ='num_hash' value='$ret[hash]' placeholder='Hash'>";
 ?>
 	
 	</div>
 	
 	<div class="col-2 col-12-mobilep">
 	<select name="ordenador" id="ordenador">
 	<option value="">Selecciona el ordenador</option>
    <?php
    $contador=0;
    if($ret['nombre_ordenadores']=='' OR $ret['nombre_ordenadores']==null) {
        $resultado = mysqli_query($link, "select id_ordenadores,nombre_ordenadores FROM ordenadores");
        
    }
    else {
        $resultado = mysqli_query($link, "select id_ordenadores,nombre_ordenadores FROM ordenadores where id_ordenadores!=$ret[id_ordenadores]");
        echo "<option value='$ret[id_ordenadores]' selected>$ret[nombre_ordenadores]</option>";
    }
    
   
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        foreach ($line as $col_value) {
            if($contador==0) {
                echo "<option value=$col_value>";
                $contador++;
            }
            else {
                echo "$col_value</option>";
                $contador=0;
            }
            
            
        }
    }
    ?>
        </select>
 	</div>
 	
 		<div class="col-2	 col-12-mobilep">	
<?php 		   
    // se cargan el select con los tipos de hash
    echo "
    <select id='tipo_hash' id='tipo_hash' name='tipo_hash'>";
    if($ret['id_tipo_hash']!=null) {
        echo "<option value=$ret[id_tipo_hash]>$ret[tipo_hash]</option>";
        $resultado = mysqli_query($link, "select id_tipo_hash,tipo_hash FROM tipo_hash where id_tipo_hash!=$ret[id_tipo_hash]");
    }
    else {
    
        $resultado = mysqli_query($link, "select id_tipo_hash,tipo_hash FROM tipo_hash");
    }
    
    $contador=0;
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        foreach ($line as $col_value) {
            if($contador==0) {
                echo "<option value=$col_value>";
                $contador++;
            }
            else {
                echo "$col_value</option>";
                $contador=0;
            }
        }
    }
    
    echo "</select>

    ";
    
    ?>

    </div>
        		<div class="col-12">		

<?php
    
    // se cargan los detalles
    
    echo "
   
		<textarea name='observaciones' id='observaciones' value='$ret[obs]' placeholder='Observaciones...' rows='4'>$ret[obs]</textarea>";
?>
	</div>
    <div class="col-12">
    <ul class="actions special">
    <input type="hidden" name="id_hash" id="id_hash" value="<?php echo $ret['id_hash']?>">
    <input type="hidden" name="num_hash_original" id="num_hash_original" value="<?php echo $ret['hash']?>">
    <li><input type='submit' value='Modificar' /></li>
    <li><input type="button" onclick="location.href='detalle_evidencia.php';" value="Volver"><br></li>
    
    </ul>
    </div>		
    
    
    </div>
    
    </form>
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
    
    <?php 
}
else {
    echo "Error";
}
?>

</html>