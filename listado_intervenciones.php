<?php 
session_start();

if(isset($_SESSION['id_u'])) {

    $link = mysqli_connect("localhost", "root", ".google.", "safe");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    $myid_caso=$_SESSION['id_caso'];
    $_SESSION['mod']=2;
    $respuesta=$_SESSION['respuesta'];
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <link rel="stylesheet" type="text/css" href="Estilo.css"> 
      <meta charset="utf-8">
      <script src="//code.jquery.com/jquery-latest.js"></script>
      <script src="miscript.js"></script>
      <script>
    		function validar() {
    			var c = document.getElementById("mensaje");
    			var ctx = c.getContext("2d");
    			ctx.font = "bold 12px Verdana";
    			var formdata = new FormData($("#myform")[0]);
    			formdata.append("mod", 0);
    			$.ajax({
    				type: "POST",
    				url: "crearevidencia.php",
    				data:formdata,
    				contentType: false,
    				processData: false,
    				success: function(respuesta) {
    						if(respuesta==2) {
    							window.location="asunto.php";
    						}
    						else {
    							ctx.clearRect(0, 0, c.width, c.height);
    							ctx.strokeStyle = "#FF0000";
    							ctx.strokeRect(1, 1, 249, 29);
    							ctx.fillStyle = "#FF0000";
    							ctx.textAlign = "center";
    							ctx.fillText(respuesta,130,20);
    						}
    					}
    			});
    		};
    </script>
    <script type="text/javascript">
    function cambiarsubtipo(opSelect){
    	
    	var category=opSelect;
    	var url="obtenersubtipo.php";
    	var pro= $.ajax({
    
    		url:url,
            type:"POST",
            data:{category:category}
    
          }).done(function(data){
    
                $("#subtipo").html(data);
                cambiar($("#subtipo option:selected").val());
          })    
    };
    </script>
    <script>
    		function habilitar(value)
    		{
    			if(value==true) {
    				document.getElementById("padre").style.display = 'inline';  
    				var category=$('input:radio[name=intervencion]:checked').val();
    				var url="obtenerdepende.php";
    
    					$.ajax({
    
    			        	url:url,
    			            type:"POST",
    			            data:{category:category}
    
    					}).done(function(data){
    						
    			        	$("#padre").html(data);
    			        })     
    			}
    			else if(value==false){
    				// deshabilitamos
    				document.getElementById("padre").style.display = 'none';
    			}
    		}
    		function habilitar_alias(value)
    		{
    			if(value==true)
    			{
    				// habilitamos
    				document.getElementById("alias").disabled=false;
    			}else if(value==false){
    				// deshabilitamos
    				document.getElementById("alias").disabled=true;
    				document.getElementById("alias").value=null;
    			}
    		}
    		 function cambiar(opSelect){
    			  
    			  var inter=$('input:radio[name=intervencion]:checked').val();
    			  
    			  var tipo=opSelect;
    			  var tipos = $.ajax({
    
    				  type: "GET", 
    		          url: 'obteneretiquetas.php',
    		          data : { "tipo" : tipo, "inter" : inter},
    		          dataType: 'text',//indicamos que es de tipo texto plano
    		          async: false     //ponemos el parámetro asyn a falso
    		      }).responseText;
    			  
    			  document.getElementById('nombre').value=tipos;
    			  }
    		  function rein_patron() {
    			  $("#dibujo").empty();
    			  document.getElementById('aceptar').disabled=true;
    			  document.getElementById('frase').style.display = 'inline';
    				 document.getElementById('frase2').style.display = 'none';
    			  $('input[name=patron]').removeAttr('checked');  
    			  $.ajax({
    
    			     	url:"borrarpatron.php",
    			         type:"POST",
    
    			  });
    			  
    		  }
    </script>
     <script>
     function respuesta() {
    	 	<?php $_SESSION['patron']=null;?>
    	    document.getElementById('evidencias').style.display = 'none';
    	    document.getElementById('botones').style.display = 'none';
    	    document.getElementById('frase').style.display = 'none';
    		document.getElementById('frase2').style.display = 'none';
    		document.getElementById('frase3').style.display = 'none';
    		var respuesta=<?php echo $respuesta; ?>;
    		var c = document.getElementById("mensaje");
    		var ctx = c.getContext("2d");
    		ctx.font = "bold 12px Verdana";
    		ctx.clearRect(0, 0, c.width, c.height);	
    		if(respuesta==1) {
    			ctx.strokeStyle = "#3DBA26";
    			ctx.strokeRect(1, 1, 299, 29);
    			ctx.fillStyle = "#3DBA26";
    			ctx.textAlign = "center";
    			ctx.fillText("Intervención añadida",150,20);
    			<?php $_SESSION['respuesta']=0; ?>
    		}
     }
    </script>
    <script>
    function activarTipoCapacidad() {
    	document.getElementById('tipo_capacidad').disabled=false;
    	var valor=$('input:text[name=capacidad]').val();
    	if (($('input:text[name=capacidad]').val().length < 1) || (valor=='0')) {
    		document.getElementById('tipo_capacidad').disabled=true;
    	}
    }
    </script>
    <script>
    function abrir_formulario(radio) {
    	$.ajax({
    
         	url:"borrarpatron.php",
             type:"POST",
    
    	});
    	$("#dibujo").empty();
    	document.getElementById('evidencias').style.display = 'block';
    	document.getElementById('botones').style.display = 'block';
    	document.getElementById('padre').style.display = 'none';
    	document.getElementById('radiopatron').style.display = 'none';
    	document.getElementById('tienepatron').checked=false;
    	document.getElementById('frase3').style.display = 'none';
    	$('input[name=patron]').removeAttr('checked'); 
    	var category=$('input:radio[name=intervencion]:checked').val();
    	var url="obtenerdepende.php";
    
    		$.ajax({
    
            	url:url,
                type:"POST",
                data:{category:category}
    
    		}).done(function(data){
    			if(data!='') {
    				document.getElementById('checkdepende').style.display = 'inline';
    				document.getElementById('depende').checked=false;
    			}
    			else {
    				document.getElementById('checkdepende').style.display = 'none';
    			}
    		});  
    	var opSelect=$('select[name=tipo]').val();
    	cambiarsubtipo(opSelect);
    	var tipo=document.getElementById('subtipo').value;
    	cambiar(tipo);
    }
    </script>
    <script>
    function tiene_patron(value) {
    
    	if(value==true) {
    		// habilitamos el div patron
    		$("#nopatron *").attr("disabled", "disabled").off('click');
    		document.getElementById('botones').style.display = 'none';
    		document.getElementById('radiopatron').style.display = 'block';
    		document.getElementById('aceptar').disabled=true;
    		document.getElementById('frase').style.display = 'inline';
    	}else if(value==false){
    		// deshabilitamos y habilitamos el resto
    		$('input[name=patron]').removeAttr('checked');
    		$("#nopatron *").attr("disabled", false);
    		document.getElementById('botones').style.display = 'block';
    		document.getElementById('radiopatron').style.display = 'none';
    		document.getElementById('frase3').style.display = 'none';
    		document.getElementById('frase').style.display = 'none';
    		document.getElementById('frase2').style.display = 'none';
    		document.getElementById('patron').checked = false;
    		var tiene=document.getElementById('check_alias').checked;
    		habilitar_alias(tiene); 	 
    		$.ajax({
    
    	     	url:"borrarpatron.php",
    	         type:"POST",
    
    			});
    	}
    }
    </script>
    <script>
      function grabarnumero() {
    	
    	 document.getElementById('frase').style.display = 'none';
    	 document.getElementById('frase2').style.display = 'inline';
    	 var numero=$('input:radio[name=patron]:checked').val();
    	 $.ajax({
    
         	url:"grabarpatron.php",
             type:"POST",
             data:{numero:numero}
    
    	 }).done(function(data) {
    	 		$.ajax({
    	     	url:"longitudpatron.php",
    	        type:"POST",
    			}).done(function(data_longitud){
    				if(data_longitud>2) {
    					document.getElementById('aceptar').disabled=false;
    				 	$.ajax({
    				   		url:"numerosdibujar.php",
    				        type:"POST",
    						}).done(function(data){
    							var svg = document.getElementById('dibujo');								
    							var x1=data.slice(0,3);
    							var y1=data.slice(4,7);
    							var x2=data.slice(8,11);
    							var y2=data.slice(12,15);
    							if(data_longitud==4) {
    								var element = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    								element.setAttributeNS(null, 'fill', 'green');
    								element.setAttributeNS(null, 'x', x1);
    								element.setAttributeNS(null, 'y', y1);
    								var txt = document.createTextNode("●");
    								element.appendChild(txt);
    								svg.appendChild(element);
    							}
    							else {
    								var element = document.getElementById('final');
    								svg.removeChild(element);
    							}
    							var line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
    							line.setAttribute('x1', x1);
    							line.setAttribute('y1', y1);
    							line.setAttribute('x2', x2);
    							line.setAttribute('y2', y2);
    							line.setAttribute('stroke', 'black');
    							line.setAttribute('stroke-width', '2');
    							svg.appendChild(line);
    							var element = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    							element.setAttributeNS(null, 'id', 'final');
    							element.setAttributeNS(null, 'fill', 'red');
    							element.setAttributeNS(null, 'x', x2);
    							element.setAttributeNS(null, 'y', y2);
    							var txt = document.createTextNode("●");
    							element.appendChild(txt);
    							svg.appendChild(element);
    										
    						});
    				}
    			});
    	 });
      }
    		
      </script>
      <script>
      function grabarpatron() {
    	  document.getElementById('frase').style.display = 'none';
    	  document.getElementById('frase2').style.display = 'none';
    	  document.getElementById('frase3').style.display = 'inline';
    	  document.getElementById('radiopatron').style.display = 'none';
    	  document.getElementById('tienepatron').style.display = 'inline';
    	  $("#nopatron *").attr("disabled", false);
    	  document.getElementById('botones').style.display = 'block';
      }
      </script>
    </head>
    <body class="fondo" onload="respuesta();">
    <div align="center"><br>
    
    
    <?php echo "<h1>SAFE</h1>";
    $resultado = mysqli_query($link, "select i.numero_intervencion as nom_i, t.nombre as nom_tipo, s.nombre, s.apellido1, s.apellido2, i.direccion, i.descripcion
    FROM intervencion i
    INNER JOIN tipo_intervencion t ON t.id_tipo_intervencion=i.id_tipo_intervencion
    INNER JOIN sujeto_activo s ON s.id_sujeto_activo=i.id_sujeto_activo
    INNER JOIN caso c ON c.id_caso=i.id_caso
    WHERE i.id_caso=$myid_caso ORDER BY i.id_intervencion");
    $count=mysqli_num_rows($resultado);
    
    if($count==0) {
            echo '<script type="text/javascript">
    	    location.href = "nuevaintervencion.php?mod=2";
            </script>';
       
    }
    
    echo "<B>LISTADO INTERVENCIONES</b><br>";
    echo "<canvas id='mensaje' width='300' height='30'></canvas>";
    echo "<br><br>Seleccione una intervención";
    echo "<div style='text-align:center;position:absolute;top:-5px;left:575px;'>";
    echo "</div>";
    echo "<br><table border='1' style='border-collapse: collapse; margin:0 auto; background-color: #def;border-style: none;'><tr><th></th><th>Número</th><th>Tipo</th><th>Sujeto</th><th>Dirección</th><th>Descripcion</th></tr><tr>";
    $contador=0;
    $i=1;
    $entro=0;
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $nombre_sujeto="";
    
    
        foreach ($line as $col_value) {
            if($contador==0) {
                echo "<td align='center'>";
               
       	        echo "<input type='radio' name='intervencion' id='intervencion$i' value='$col_value' required onchange='abrir_formulario(this.value);'>";
       	        
       	        
                echo "</td>";
                echo "<td>";
                echo $col_value;
                echo "</td>";
                $contador++; 
                $i++;
            } 
                else {
                    if($contador==1) {
                        echo "<td>";
                        echo $col_value;
                        echo "</td>";
                        $contador++;
                    }
                    else {
                        
                if($contador==2) {
                    if($entro<2) {
                        $nombre_sujeto=$nombre_sujeto." ".$col_value;
                        $entro++;
                    }
                    else {
                        echo "<td align='center'>";
                        echo $nombre_sujeto." ".$col_value;
                        echo "</td>";
                        $contador++;
                        $entro=0;
                    }
                }
                else {
                        if($contador<4 ) {
                            echo "<td>";
                            echo $col_value;
                            echo "</td>";
                            $contador++;
                        }
                        else {
                            echo "<td>";
                            echo $col_value;
                            echo "</td>";
                            $contador=0;
                        }
                    }
                }
            }
        }
        echo "</tr>";
    }
    echo "</table>";
    
    
    echo "<br><form method='POST' action='nuevaintervencion.php'>";
    echo "<input type='submit' value='Agregar Intervencion' class='estilo'>";
    echo "</form>";
    ?>
    <input type='button' onclick="location.href='asunto.php';" value='Volver' class='estilo'><br>
    <br>
    
    <div id="evidencias">
    
     	<form action="javascript:validar();" id="myform" method="post">
     	<br>
     	<div id="nopatron">
     	Nombre Evidencia:
     	
     	<input type='text'  name='nombre' id='nombre' size='2' readonly>
     	
     	<input type="text" name="numero" id="numero" size="2" required pattern='\d*'>
     	
     	¿Alias?:
     	 <input type="checkbox"  id="check_alias" name="check_alias" onchange="habilitar_alias(this.checked);">
     	 <input type="text" name="alias" id="alias" size="5" disabled>
     	
     	
     	<div id='checkdepende'>
     	¿Depende de otra?
        	<input type="checkbox"  id="depende" name="depende" onchange="habilitar(this.checked);">
        
       
    	Evidencias:
        <select name="padre" id="padre">
    	</select>
    	</div>
    		
    	<br><br>
    	Numero Serie:
     	<input type="text"  name="n_s" id="n_s" size="10">
     	Capacidad:
     	<input type="text"  name="capacidad" id="capacidad" size="2" pattern='[-+]?[0-9]*[.,]?[0-9]+' oninput="activarTipoCapacidad()">
     	<select id="tipo_capacidad" name="tipo_capacidad" disabled > 
     	<?php 
        $contador=0;
        $resultado = mysqli_query($link, "select id_tipo_capacidad, tipo_capacidad FROM tipo_capacidad");
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
     	Marca:
     	<input type="text"  name="marca" id="marca" size="10">
     	Modelo:
     	<input type="text"  name="modelo" id="modelo" size="10">
     	<br><br>
     	Observaciones:
     	<input type="text"  name="observaciones" id="observaciones" size="10">
     	Tipo Evidencias:
     	<select id="tipo" name="tipo" onchange="cambiarsubtipo(this.value)" required> 
     	<?php 
        $contador=0;
        $resultado = mysqli_query($link, "select id_tipo_evidencia, nombre FROM tipo_evidencia");
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
        
        <select name="subtipo" id="subtipo" onchange="cambiar(this.value);" required>
        <option value='1'>HDD interno</option>
        </select>
        Disco:
        <select name="disco" id="disco">
        <?php 
        $contador=0;
        $resultado = mysqli_query($link, "select id_disco_almacenado, nombre FROM disco_almacenado");
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
        	 
        <br><br>
        PIN:
         	<input type="text"  name="pin" id="pin" size="10">
         	<br><br>
        </div>
        <input type="checkbox" id="tienepatron" name="tienepatron" onchange="tiene_patron(this.checked);">Tiene patron
        <br><br>
        <object id="svg-object" data="path/to/external.svg" type="image/svg+xml">  
        	<svg id="dibujo" width='250' height='75'>    
    		</svg>
    	</object>
    	<br>
        <div id="frase"> Pulsa el primer punto del patrón</div>
        <div id="frase2"> Pulsa el siguiente punto</div>
        <div id="frase3"> Patron grabado correctamente</div>
        
        <div align="center" id="radiopatron">
        	<input type="radio" name="patron" id="patron" value="1" onchange="grabarnumero();">
    		<input type="radio" name="patron" id="patron" value="2" onchange="grabarnumero();">
    		<input type="radio" name="patron" id="patron" value="3" onchange="grabarnumero();"><br>
    		<input type="radio" name="patron" id="patron" value="4" onchange="grabarnumero();">
    		<input type="radio" name="patron" id="patron" value="5" onchange="grabarnumero();">
    		<input type="radio" name="patron" id="patron" value="6" onchange="grabarnumero();"><br>
    		<input type="radio" name="patron" id="patron" value="7" onchange="grabarnumero();">
    		<input type="radio" name="patron" id="patron" value="8" onchange="grabarnumero();">
    		<input type="radio" name="patron" id="patron" value="9" onchange="grabarnumero();"><br><br>
    		<input type="button" name="aceptar" id="aceptar" value="Aceptar" onclick="grabarpatron();" class="estilo">
    		<input type="button" name="reiniciar" id="reiniciar" value="Reiniciar" onclick="rein_patron();" class="estilo">
    		
        </div>
        <br><br>
        </div>  
        
        
      
    <div id="botones">
      	<input type="submit" value="Crear" class="estilo">
      	</form>	
      	<br>
      	<input type="button" onclick="location.href='asunto.php';" value="Volver" class="estilo"><br>
    </div>
    
    </div>
    
    
    <?php 
}
else {
    echo "Error";
}
?>

</body>
</html>