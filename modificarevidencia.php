<?php

session_start();

if(isset($_SESSION['id_u'])) {

    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    
    $mynombre=mysqli_real_escape_string($link, $_POST['nombre']);
    $mynumero=mysqli_real_escape_string($link, $_POST['numero']);
    $myn_s=mysqli_real_escape_string($link, $_POST['n_s']);
    $mycapacidad=mysqli_real_escape_string($link, $_POST['capacidad']);
    $mymarca=mysqli_real_escape_string($link, $_POST['marca']);
    $mymodelo=mysqli_real_escape_string($link, $_POST['modelo']);
    $myobservaciones=mysqli_real_escape_string($link, $_POST['observaciones']);
    $mytipo=mysqli_real_escape_string($link, $_POST['tipo']);
    $mysubtipo=mysqli_real_escape_string($link, $_POST['subtipo']);
    $myid_tipo=mysqli_real_escape_string($link, $_POST['id_tipo']);
    $myid_subtipo=mysqli_real_escape_string($link, $_POST['id_subtipo']);
    $mydisco=mysqli_real_escape_string($link, $_POST['disco']);
    $myid_disco=mysqli_real_escape_string($link, $_POST['id_disco']);
    $mynumero_intervencion=mysqli_real_escape_string($link, $_POST['numero_intervencion']);
    $myalias=mysqli_real_escape_string($link, $_POST['alias']);
    $mypin=mysqli_real_escape_string($link, $_POST['pin']);
    $mypatron=mysqli_real_escape_string($link, $_POST['patron']);
    if(isset($_POST['id_tipo_capacidad'])) {
    $myid_tipo_capacidad=mysqli_real_escape_string($link, $_POST['id_tipo_capacidad']);
    }
    $mytipo_capacidad=mysqli_real_escape_string($link, $_POST['tipo_capacidad']);
    echo "select id_disco_almacenado, nombre FROM disco_almacenado where id_disco!=$myid_disco";


    
    ?>
    
   <!DOCTYPE html>
<html lang="es-ES">
<head>
 <title>Modificar Evidencia</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
	

			
	<!-- Alonso -->

      <script src="//code.jquery.com/jquery-latest.js"></script>
      <script src="miscript.js"></script>
      <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
      <script src="js/jquery-3.4.1.js"></script>
      <script>
    		function validar() {
    			var c = document.getElementById("mensaje");
    			var ctx = c.getContext("2d");
    			ctx.font = "bold 12px Verdana";
    			
    			var formdata = new FormData($("#myform")[0]);
    			formdata.append("mod", 1);
    			$.ajax({
    				type: "POST",
    				url: "crearevidencia.php",
    				data:formdata,
    				contentType: false,
    				processData: false,
    				success: function(respuesta) {
    					if(respuesta==1) {
    						window.location="detalle_evidencia.php";
    					}
    					else {
    						ctx.clearRect(0, 0, c.width, c.height);	
    						ctx.clearRect(0, 0, c.width, c.height);	
    						ctx.strokeStyle = "#FF0000";
    						ctx.strokeRect(1, 1, 299, 29);
    						ctx.fillStyle = "#FF0000";
    						ctx.textAlign = "center";
    						ctx.fillText(respuesta,150,20);
    					}
    					}
    			});	
    	
    		};
    </script>
    <script>
    function activarTipoCapacidad() {
    	document.getElementById('tipo_capacidad').disabled=false;
    	var valor=$('input:text[name=capacidad]').val();
    	if (($('input:text[name=capacidad]').val().length < 1) || (valor=='0')) {
    		document.getElementById('tipo_capacidad').disabled=true;
    	}
    }
    function vaciarpatron() {
    	document.getElementById("borrarpatron").value=0;
    	document.getElementById("borrar").style.display = 'none';
    	document.getElementById("div_patron").style.display = 'none';
    	$("#dibujo").empty();
       
    }
    function rein_patron() {
    	  $("#dibujo").empty();
    	  document.getElementById('aceptar').disabled=true;
    	  document.getElementById('frase').style.display = 'inline';
    		 document.getElementById('frase2').style.display = 'none';
    		 $('input[name=patron]').prop('checked', false);  
    	  $.ajax({
    
    	     	url:"borrarpatron.php",
    	         type:"POST",
    
    	  });
    	  
    }
    </script>
    <script>
    function prepararpagina() {
    	$('input[name=patron]').removeAttr('checked');
    	$("#nopatron *").attr("disabled", false);
    	activarTipoCapacidad();
    	document.getElementById('botones').style.display = 'block';
    	document.getElementById('radiopatron').style.display = 'none';
    	document.getElementById('frase3').style.display = 'none';
    	document.getElementById('frase').style.display = 'none';
    	document.getElementById('frase2').style.display = 'none';
    	document.getElementById('patron').checked = false;
    	var tiene=document.getElementById('check_alias').checked;
    	$.ajax({
    
         	url:"borrarpatron.php",
             type:"POST",
    
      });
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
    		$('input[name=patron]').prop('checked', false);
    		$("#dibujo").empty();
    		$("#nopatron *").attr("disabled", false);
    		document.getElementById('botones').style.display = 'block';
    		document.getElementById('radiopatron').style.display = 'none';
    		document.getElementById('frase3').style.display = 'none';
    		document.getElementById('frase').style.display = 'none';
    		document.getElementById('frase2').style.display = 'none';
    		document.getElementById('patron').checked = false;	 
    		$.ajax({
    
    	     	url:"borrarpatron.php",
    	         type:"POST",
    
    			});
    	}
    }
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
                cambiar($("#subtipo option:selected").val(),<?php echo $mynumero_intervencion;?>);
          })    
    };
    </script>
     <script>
      function cambiar(opSelect,intervencion){
    	  var inter=intervencion;
    	  var tipo=opSelect;
    	  var tipos = $.ajax({
    
    		  type: "GET", 
              url: 'obteneretiquetas.php',
              data : { "tipo" : tipo, "inter" : inter },
              dataType: 'text',//indicamos que es de tipo texto plano
              async: false     //ponemos el parámetro asyn a falso
          }).responseText;
    	  
    	  document.getElementById('nombre_nuevo').value=tipos;	
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
      function cabecera(){
			
			$('#cabecera').load('cabecera.php');                
			prepararpagina();
			
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
    						<h2>Modificar Evidencia <b><?php echo $mynombre;?><?php echo $mynumero;?></b></h2>						
    					</header>
    					
    					<div align='center'>
    						<canvas id='mensaje' width='250' height='30'></canvas>
    					</div>
    					
    					<div class="box">
    						<form action='javascript:validar();' id='myform' method='post'>
    							<h3>Datos de la evidencia </h3>
    							<div id="nopatron">	
    								
    								<div class="row gtr-50 gtr-uniform">
    							
    								<div class="col-2 col-12-mobilep">	
    									<?php
    									 echo "<input type='text'  name='nombre_nuevo' id='nombre_nuevo' value='$mynombre' readonly>";
    									 echo "<input type='hidden'  name='nombre_original' id='nombre_original' value='$mynombre'>";
    									?>		
    								</div>
    								
    								<div class="col-2 col-12-mobilep">
    									<?php
											echo "<input type='text'  name='numero_nuevo' id='numero_nuevo' value='$mynumero' required pattern='\d*'>";
   					 						echo "<input type='hidden'  name='numero_original' id='numero_original' value='$mynumero'>";
										?>
    								</div> 
    								
    								<div class="col-4 col-12-mobilep">
 <?php   								
    //cargo la lista de tipos
    echo "<select name='tipo' id='tipo' onchange='cambiarsubtipo(this.value);' required>";
    $contador=0;
    echo "<option value='$myid_tipo' selected>$mytipo</option>";
    $resultado = mysqli_query($link, "select id_tipo_evidencia, nombre FROM tipo_evidencia where id_tipo_evidencia!=$myid_tipo");
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
									
								   	<div class="col-4 col-12-mobilep">
								   	<?php


//cargo la lista de subtipos
        echo "<select name='subtipo' id='subtipo' onchange='cambiar(this.value,$mynumero_intervencion);' required>";
        echo "<option value='$myid_subtipo' selected>$mysubtipo</option>";
        echo "</select>";

?>	   	
								   	</div>
								   	
								   		
    								
    								<div class="col-6 col-12-mobilep">
    									<?php
    										echo "<input type='text'  name='marca' id='marca' value='$mymarca' placeholder='Marca'>";
    									?>
    								</div>
    								
    								<div class="col-6 col-12-mobilep">
    									<?php
											 echo "<input type='text'  name='modelo' id='modelo' value='$mymodelo' placeholder='Modelo'>";
										?>
    								
    								</div> 
    								
    								
									<div class="col-5 col-12-mobilep">
    									<?php
    										echo "<input type='text'  name='n_s' id='n_s' value='$myn_s' placeholder='n/s'>";
										?>
    								</div>	
    								
    								
    								
    								<div class="col-2 col-12-mobilep">
    									<?php	    								
    										echo "<input type='text'  name='capacidad' id='capacidad' value='$mycapacidad' placeholder='Capacidad' pattern='[-+]?[0-9]*[.,]?[0-9]+' oninput='activarTipoCapacidad();'>";
										?>
    								</div>
    								
    								<div class="col-2 col-12-mobilep">
    								
 <?php

//cargo la lista de los tipos de capacidad
    
    $contador=0;
    if($myid_tipo_capacidad!=null) {
    echo "<select name='tipo_capacidad' id='tipo_capacidad'>";
    echo "<option value='$myid_tipo_capacidad' selected>$mytipo_capacidad</option>";
    $resultado = mysqli_query($link, "select id_tipo_capacidad, tipo_capacidad FROM tipo_capacidad where id_tipo_capacidad!=$myid_tipo_capacidad");
    }
    else {
    
        echo "<select name='tipo_capacidad' id='tipo_capacidad' disabled>";
    
        $resultado = mysqli_query($link, "select id_tipo_capacidad, tipo_capacidad FROM tipo_capacidad");
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

 									 
    								
    							echo "</div>	
    								<div class='col-3 col-12-mobilep'>";									    									
					    								echo	"<input type='text' name='alias' id='alias' value='$myalias' placeholder='Alias'>";     	 													 
					    							echo "</div>";
    			?>					    								
    								
    								<div class="col-12">
    									<?php
											echo "<textarea name='observaciones' id='observaciones' value='$myobservaciones' placeholder='Observaciones'>$myobservaciones</textarea>";
										?>
    								
    								</div>
    								<div class="col-2 col-12-mobilep">
	        										<select name="disco" id="disco">
	        										
                                    <?php 
                                    if($mydisco=='0' or $mydisco==null or $mydisco=='null') {
                                        echo "<option selected value=''>Disco</option>";
                                        $resultado = mysqli_query($link, "select id_disco_almacenado, nombre FROM disco_almacenado");
                                    }
                                    else {
                                        echo "<option selected value='$myid_disco'>$mydisco</option>";
                                        $resultado = mysqli_query($link, "select id_disco_almacenado, nombre FROM disco_almacenado where id_disco_almacenado!=$myid_disco"); 
                                        echo "<option value=''></option>";
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
                                
                                    ?>
                                    </select>
                                    </div>
                                    <div class="col-5 col-12-mobilep">
    									<?php
    										echo "<input type='text'  name='pin' id='pin' value='$mypin' placeholder='Pin'>";
										?>
									</div>
    								<div class="col-5 col-12-mobilep">
        										
        							</div>						
    							 </div> <!-- gtr 50 -->
					
					
  							
    				
    							</div> <!-- nopatron -->
    							
    							<br>
    							
    							<h3>Dibujar el Patrón</h3>	
    					
    							<div id="div_patron">	
    							
 <!-- dibujar el patron -->		                							    
                				    <div class="row gtr-50 gtr-uniform">
                				    	
                				    	<div class="col-4 col-12-mobilep">	

<?php                				    
if(!empty($mypatron) or $mypatron!='' or $mypatron!=null) {
        echo "Patron:<br>";
        $primeronumero=0;
        $longitud=strlen($mypatron)-1;
        echo "<svg id='dibujo' width='250' height='75'>";
        for ($i = 0; $i <= $longitud; $i++) {
            $numero=substr($mypatron, $i, 1);
            if(is_numeric(substr($mypatron, $i, 1))) {
                $numero=substr($mypatron, $i, 1);
                $siguiente=substr($mypatron, $i+2, 1);
                $ultimo=substr($mypatron,$longitud-1,1);
                if($numero==1) {
                    if($primeronumero==0) {
                        $x1=90;
                        $y1=15;
                        $primeronumero=1;
                        if($siguiente==2 or $siguiente==3) {
                            $x3=$x1-12;
                            $y3=$y1+5;
                            echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                        }
                        else if ($siguiente==4 or $siguiente==7) {
                            $x3=$x1-6;
                            $y3=$y1-1;
                            echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                        }
                        else {
                            $x3=$x1-12;
                            $y3=$y1;
                            echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                        }
                    }
                    else {
                        $x2=90;
                        $y2=15;
                        
                        echo "<line x1=$x1 y1=$y1 x2=$x2 y2=$y2 stroke='black' stroke-width='3'></line>";
                        $x1=$x2;
                        $y1=$y2;
                        if($ultimo==1) {
                            $x3=$x1-12;
                            $y3=$y1+4;
                            echo "<text x=$x3 y=$y3 fill='red' style='font-size: 20px'>●</text>";
                        }
                    }
                } else if ($numero==2) {
                    if($primeronumero==0) {
                        $x1=125;
                        $y1=15;
                        $x3=$x1-6;
                        $y3=$y1-1;
                        $primeronumero=1;
                        echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                    }
                    else {
                        $x2=125;
                        $y2=15;
                        
                        echo "<line x1=$x1 y1=$y1 x2=$x2 y2=$y2 stroke='black' stroke-width='3'></line>";
                        $x1=$x2;
                        $y1=$y2;
                        if($ultimo==2) {
                            $x3=$x1-6;
                            $y3=$y1-1;
                            echo "<text x=$x3 y=$y3 fill='red' style='font-size: 20px'>●</text>";
                        }
                    }
                } else if ($numero==3) {
                    if($primeronumero==0) {
                        $x1=160;
                        $y1=15;
                        $primeronumero=1;
                        if($siguiente==1 or $siguiente==2) {
                            $x3=$x1;
                            $y3=$y1+5;
                            echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                        }
                        else if ($siguiente==6 or $siguiente==9) {
                            $x3=$x1-6;
                            $y3=$y1-1;
                            echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                        }
                        else {
                            $x3=$x1;
                            $y3=$y1;
                            echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                        }
                    }
                    else {
                        $x2=160;
                        $y2=15;
                        
                        echo "<line x1=$x1 y1=$y1 x2=$x2 y2=$y2 stroke='black' stroke-width='3'></line>";
                        $x1=$x2;
                        $y1=$y2;
                        if($ultimo==3) {
                            $x3=$x1;
                            $y3=$y1+4;
                            echo "<text x=$x3 y=$y3 fill='red' style='font-size: 20px'>●</text>";
                        }
                    }
                    $primeronumero=1;
                } else if ($numero==4) {
                    if($primeronumero==0) {
                        $x1=90;
                        $y1=40;
                        $primeronumero=1;
                        $x3=$x1-12;
                        $y3=$y1+5;
                        echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                    }
                    else {
                        $x2=90;
                        $y2=40;
                        
                        echo "<line x1=$x1 y1=$y1 x2=$x2 y2=$y2 stroke='black' stroke-width='3'></line>";
                        $x1=$x2;
                        $y1=$y2;
                        if($ultimo==4) {
                            $x3=$x1-12;
                            $y3=$y1+6;
                            echo "<text x=$x3 y=$y3 fill='red' style='font-size: 20px'>●</text>";
                        }
                    }
                } else if ($numero==5) {
                    if($primeronumero==0) {
                        $x1=125;
                        $y1=40;
                        $primeronumero=1;
                        $x3=$x1-6;
                        $y3=$y1+5;
                        echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                        
                    }
                    else {
                        $x2=125;
                        $y2=40;
                        
                        echo "<line x1=$x1 y1=$y1 x2=$x2 y2=$y2 stroke='black' stroke-width='3'></line>";
                        $x1=$x2;
                        $y1=$y2;
                        if($ultimo==5) {
                            $x3=$x1-6;
                            $y3=$y1+5;
                            echo "<text x=$x3 y=$y3 fill='red' style='font-size: 20px'>●</text>";
                        }
                    }
                } else if ($numero==6) {
                    if($primeronumero==0) {
                        $x1=160;
                        $y1=40;
                        $primeronumero=1;
                        $x3=$x1;
                        $y3=$y1+5;
                        echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                        
                    }
                    else {
                        $x2=160;
                        $y2=40;
                        
                        echo "<line x1=$x1 y1=$y1 x2=$x2 y2=$y2 stroke='black' stroke-width='3'></line>";
                        $x1=$x2;
                        $y1=$y2;
                        if($ultimo==6) {
                            $x3=$x1;
                            $y3=$y1+6;
                            echo "<text x=$x3 y=$y3 fill='red' style='font-size: 20px'>●</text>";
                        }
                    }
                } else if ($numero==7) {
                    if($primeronumero==0) {
                        $x1=90;
                        $y1=65;
                        $primeronumero=1;
                        if($siguiente==1 or $siguiente==4) {
                            $x3=$x1-6;
                            $y3=$y1+11;
                            echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                        }
                        else if ($siguiente==8 or $siguiente==9) {
                            $x3=$x1-12;
                            $y3=$y1+6;
                            echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                        }
                        else {
                            $x3=$x1-12;
                            $y3=$y1+8;
                            echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                        }
                    }
                    else {
                        $x2=90;
                        $y2=65;
                        
                        echo "<line x1=$x1 y1=$y1 x2=$x2 y2=$y2 stroke='black' stroke-width='3'></line>";
                        $x1=$x2;
                        $y1=$y2;
                        if($ultimo==7) {
                            $x3=$x1-12;
                            $y3=$y1+8;
                            echo "<text x=$x3 y=$y3 fill='red' style='font-size: 20px'>●</text>";
                        }
                    }
                } else if ($numero==8) {
                    if($primeronumero==0) {
                        $x1=125;
                        $y1=65;
                        $x3=$x1-6;
                        $y3=$y1+11;
                        $primeronumero=1;
                        echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                    }
                    else {
                        $x2=125;
                        $y2=65;
                        
                        echo "<line x1=$x1 y1=$y1 x2=$x2 y2=$y2 stroke='black' stroke-width='3'></line>";
                        $x1=$x2;
                        $y1=$y2;
                        if($ultimo==8) {
                            $x3=$x1-6;
                            $y3=$y1+11;
                            echo "<text x=$x3 y=$y3 fill='red' style='font-size: 20px'>●</text>";
                        }
                    }
                } else if ($numero==9) {
                    if($primeronumero==0) {
                        $x1=160;
                        $y1=65;
                        $primeronumero=1;
                        if($siguiente==8 or $siguiente==7) {
                            $x3=$x1;
                            $y3=$y1+6;
                            echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                        }
                        else if ($siguiente==6 or $siguiente==3) {
                            $x3=$x1-6;
                            $y3=$y1+11;
                            echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                        }
                        else {
                            $x3=$x1;
                            $y3=$y1+10;
                            echo "<text x=$x3 y=$y3 fill='green' style='font-size: 20px'>●</text>";
                        }
                    }
                    else {
                        $x2=160;
                        $y2=65;
                        echo "<line x1=$x1 y1=$y1 x2=$x2 y2=$y2 stroke='black' stroke-width='3'></line>";
                        $x1=$x2;
                        $y1=$y2;
                        if($ultimo==9) {
                            $x3=$x1;
                            $y3=$y1+8;
                            echo "<text x=$x3 y=$y3 fill='red' style='font-size: 20px'>●</text>";
                        }
                    }
                }
            }
        }
        echo "</svg>";
        echo "<input type='hidden' name='borrarpatron' id='borrarpatron' value=2>";
        echo "<br><br>";
        echo "<input type='button' id='borrar' name='borrar' value='Borrar Patron' class='estilo' onclick='vaciarpatron();'>";
        echo "
        </div>  <!-- col 4 -->
        </div>    <!--  row gtr 50 -->
        </div> <!--  patron --> 
        ";
    }
    
    else {
        
?>
	<div class="row gtr-50 gtr-uniform">
                				    	
      
		
		<div class="col-12 col-12-mobilep">
			<input type='checkbox' id='tienepatron' name='tienepatron' onchange="tiene_patron(this.checked);">	    													    									
			<label for=tienepatron>Patrón</label>   
		</div>
        
        <object id="svg-object" data="path/to/external.svg" type="image/svg+xml">  
        	<svg id="dibujo" width='250' height='75'>    
    		</svg>
    	</object>
    	
        <div id="frase"> Pulsa el primer punto del patrón</div>
        <div id="frase2"> Pulsa el siguiente punto</div>
        <div id="frase3"> Patron grabado correctamente</div>
        
        <div id="radiopatron">
        
        	<input type="radio" name="patron" id="patron1" value="1" onchange="grabarnumero();"><label for="patron1"></label>        	
    		<input type="radio" name="patron" id="patron2" value="2" onchange="grabarnumero();"><label for="patron2"></label>
    		<input type="radio" name="patron" id="patron3" value="3" onchange="grabarnumero();"><label for="patron3"></label><br><br>
    		<input type="radio" name="patron" id="patron4" value="4" onchange="grabarnumero();"><label for="patron4"></label>
    		<input type="radio" name="patron" id="patron5" value="5" onchange="grabarnumero();"><label for="patron5"></label>
    		<input type="radio" name="patron" id="patron6" value="6" onchange="grabarnumero();"><label for="patron6"></label><br><br>
    		<input type="radio" name="patron" id="patron7" value="7" onchange="grabarnumero();"><label for="patron7"></label>
    		<input type="radio" name="patron" id="patron8" value="8" onchange="grabarnumero();"><label for="patron8"></label>
    		<input type="radio" name="patron" id="patron9" value="9" onchange="grabarnumero();"><label for="patron9"></label><br><br>
    		<input type="button" name="aceptar" id="aceptar" value="Aceptar" onclick="grabarpatron();" class='button special small'>
    		<input type="button" name="reiniciar" id="reiniciar" value="Reiniciar" onclick="rein_patron();"	class='button special small'>
    		<input type='hidden' name='borrarpatron' id='borrarpatron' value=1>
    		
        </div>	
        
      </div> <!-- gtr -->
    
<?php 
    }
    
?>
    
        						<br>
        						    
    							<div class="col-12" id="botones">
						    							<ul class="actions special">
						    								<li><input type="submit" value="Modificar"></li>	
						    								<li><input type="button" onclick="location.href='detalle_evidencia.php';" value="Volver"><br></li>							
						    							</ul>
								</div>
								</div>
								</div>
								</div>
								    						
    						</form>   						
    					</div>  <!-- box -->
    				</section> <!-- container -->
    		</div>	<!-- page-wrapper -->
    		
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