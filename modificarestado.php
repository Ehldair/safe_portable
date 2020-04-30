<?php
session_start();

$link = mysqli_connect("localhost", "root", ".google.", "safe");

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
 <script type="text/javascript">
 function accion(opSelect){
	 
		
		var category=opSelect;
		var url="obtenerprograma.php";
		var pro= $.ajax({

			url:url,
	        type:"POST",
	        data:{category:category}

	      }).done(function(data){

	            $("#accion_programa").html(data);
	      })    
	};
</script>
<script>
function cambiarhash(estado) {
	var est=estado;
	if(est==3) {
		document.getElementById('hash').style.display = 'none';
	
	}
	else {
		document.getElementById('hash').style.display = 'block';
	
	}
}

</script>

</head>

<body class="is-preload">
		<div id="page-wrapper">

			<!-- Main -->
				<section id="main" class="container">
					
					<header>
						<h2>Modificar Estado</h2>						
					</header>
					
					<div class="box">
						<form method="post" id="myform" action="crearestado.php">
							Modificar Estado<br><br>
										
<?php 

//se cargan todos los datos necesarios para la posterior modificación del estado de la evidencia
$sql="select u.id_usuario,u.apodo as apodo,es.id_estado_evidencia,es.nombre as estado,p.id_programa, p.nombre as programa,ap.id_accion_programa, ap.nombre as accion_programa,
ti.id_tipo_hash, ti.tipo_hash, h.id_hash, h.hash as hash, er.observaciones as obs
from evidencia_registro er
inner join usuario u on er.id_usuario=u.id_usuario
inner join estado_evidencia es on er.id_estado_evidencia=es.id_estado_evidencia
left join programa p on er.id_programa=p.id_programa
left join accion_programa ap on er.id_accion_programa=ap.id_accion_programa
left join hash h on er.id_hash=h.id_hash
left join tipo_hash ti on h.id_tipo_hash=ti.id_tipo_hash
where er.id_evidencia_registro=$myid_registro ORDER BY er.fecha_alta_estado ASC";
$resultado=mysqli_query($link, $sql);
$ret=mysqli_fetch_array($resultado);


//se carga el usuario ya grabado y se crea el select con el resto de usuarios
echo "Usuario:";
echo "<select name='usuario' id='usuario'>";
echo "<option value=$ret[id_usuario] selected>$ret[apodo]</option>";
$contador=0;
$entro=0;
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

//se carga el estado ya grabado y se crea el select con el resto de estados 
 echo "Estado:

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

echo "Programa:
<select id='programa' name='programa' onchange='accion(this.value);'>";

echo "<option value=$ret[id_programa] selected>$ret[programa]</option>";
$contador=0;
$resultado = mysqli_query($link, "select id_programa, nombre FROM programa WHERE id_programa!=$ret[id_programa]");
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

//cargo la accion de programa ya grabada y creo el select con el resto de acciones de programa

echo "Accion:

<select id='accion_programa' name='accion_programa'>
<option value=$ret[id_accion_programa]>$ret[accion_programa]</option>";
$contador=0;
$resultado = mysqli_query($link, "select id_accion_programa, nombre FROM accion_programa where id_accion_programa!=$ret[id_accion_programa]");
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

// se cargan los detalles

echo "Detalles:
<input type='text' name='detalles' id ='detalles' value='$ret[obs]' size=80>";

// se carga el hash
echo "<div class='auto' id='hash'>";
echo "HASH:
<input type='text' name='num_hash' id ='num_hash' value='$ret[hash]' size=40>";

// se cargan el select con los tipos de hash
echo "Tipo HASH:
<select id='tipo_hash' name='tipo_hash'>";
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
</div><br>";

?>
<div class="col-12">
<ul class="actions special">
<input type="hidden" name="id_hash" id="id_hash" value="<?php echo $ret['id_hash']?>">
<input type="hidden" name="num_hash_original" id="num_hash_original" value="<?php echo $ret['hash']?>">
<li><input type='submit' value='Modificar' /></li>
<li><input type="button" onclick="location.href='asunto.php';" value="Volver"><br></li>

</ul>
</div>		




</form>
</div>
</section>
</div>


</body>



</html>