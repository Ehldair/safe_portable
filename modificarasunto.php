<?php
session_start();

if(isset($_SESSION['id_u'])) {
    
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    $mynumero = mysqli_real_escape_string($link, $_POST['numero']);
    $myaño = mysqli_real_escape_string($link, $_POST['año']);
    $mynombre = mysqli_real_escape_string($link, $_POST['nombre']);
    $mydescripcion = mysqli_real_escape_string($link, $_POST['descripcion']);
    $myid_tipo = mysqli_real_escape_string($link, $_POST['id_tipo']);
    $mytipo = mysqli_real_escape_string($link, $_POST['tipo_caso']);
    $myid_grupo = mysqli_real_escape_string($link, $_POST['id_grupo']);
    $myid_ca = mysqli_real_escape_string($link, $_POST['id_ca']);
    $myca= mysqli_real_escape_string($link, $_POST['nom_ca']);
    $myid_provincia = mysqli_real_escape_string($link, $_POST['id_pro']);
    $myprovincia= mysqli_real_escape_string($link, $_POST['nom_pro']);
    $myid_comisaria = mysqli_real_escape_string($link, $_POST['id_com']);
    $mycomisaria= mysqli_real_escape_string($link, $_POST['nom_com']);
    $mygrupo = mysqli_real_escape_string($link, $_POST['grupo']);
    $mygrupo = mysqli_real_escape_string($link, $_POST['grupo']);
    $myfecha = mysqli_real_escape_string($link, $_POST['fecha']);
    $borrar=0;
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Nuevo Asunto</title>
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
    		<script src=»https://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.0/jquery.validate.min.js» type=»text/javascript»></script>
    		<script src=»js/jquery-validate.js»></script>
    	
    		
    		<script>
    		function validar() {
    			var c = document.getElementById("mensajes");
    			var ctx = c.getContext("2d");
    			ctx.font = "bold 12px Verdana";
    
    			var formdata = new FormData($("#myform")[0]);
    			formdata.append("mod", 1);
    			$.ajax({
    				type: "POST",
    				url: "crearasunto.php",
    				data:formdata,
    				contentType: false,
    				processData: false,
    				success: function(respuesta) {
    					if(respuesta==1) {
    						window.location="asunto.php";
    					}
    					else {
    						ctx.clearRect(0, 0, c.width, c.height);	
    						ctx.strokeStyle = "#FF0000";
    						ctx.strokeRect(1, 1, 599, 29);
    						ctx.fillStyle = "#FF0000";
    						ctx.textAlign = "center";
    						ctx.fillText(respuesta,300,20);
    					}
    				}
    			});
    		};	
    
    			
    
    		</script>
    <script type="text/javascript">
    
    
    function sel_provincia(opSelect){
    	
    	var category=opSelect;
    	var url="obtenerprovincia.php";
    	var pro= $.ajax({
    
    		url:url,
            type:"POST",
            data:{category:category}
    
          }).done(function(data){
    
                $("#provincia").html(data);
                document.getElementById("provincia").focus();
                document.getElementById("ca").required=true;
                document.getElementById("provincia").required=true;
                document.getElementById("comisaria").required=true;
                document.getElementById("grupo").required=true;
          })    
    };
    
    function sel_comisaria(opSelect){
    		
    	var category=opSelect;
    	var url="obtenercomisaria.php";
    	var pro= $.ajax({
    
    		url:url,
    	    type:"POST",
    	    data:{category:category}
        
        }).done(function(data){
    
        	$("#comisaria").html(data);
        	document.getElementById("comisaria").focus();
    	})    
    };
    function sel_grupo(opSelect){
    			
    	var category=opSelect;
    	var url="obtenergrupo.php";
    	var pro= $.ajax({
    
    		url:url,
    		type:"POST",
    		data:{category:category}
    
    	}).done(function(data){
    
    		$("#grupo").html(data);
    		document.getElementById("grupo").focus();
    	})    
    };
    </script>
    <script>
    function eliminar() {
    	document.getElementById('diligencias_nuevo').value = "";
    	document.getElementById('diligencias_nuevo').disabled=true;
    	document.getElementById("año_diligencias_nuevo").value=0;
    	document.getElementById('año_diligencias_nuevo').disabled=true;
    	document.getElementById('juzgado_nuevo').value=0;
    	document.getElementById('juzgado_nuevo').disabled=true;
    	document.getElementById('borrar').value=1;
    	document.getElementById('btn1').style.display = 'none';
    }
    </script>
    <script>
    function agregar() {
    	document.getElementById('diligencias_nuevo').disabled = false;
    	document.getElementById('diligencias_nuevo').value = "";
    	document.getElementById('año_diligencias_nuevo').disabled=false;
    	document.getElementById('año_diligencias_nuevo').remove(0);
    	document.getElementById("juzgado_nuevo").disabled=false;
    	document.getElementById('juzgado_nuevo').remove(0);
    	document.getElementById('sonnuevas').value=2;
    	document.getElementById('btn1').style.display = 'none';
    }
      </script>
    </head>
    
    <body class="is-preload">
    		<div id="page-wrapper">
    
    			<!-- Main -->
    				<section id="main" class="container">
    					
    					<header>
    						<h2>Modificar Caso</h2>						
    					</header>
    					
    					<div class="box">
    						<form method="post" id="myform" action="javascript:validar()";>
    							Datos del caso <br><br>
    							
    							<div class="row gtr-50 gtr-uniform">
    							
    								<div class="col-3 col-12-mobilep">								
    										Asunto
    										<?php
    											echo "<input type='text'  name='numero_nuevo' id='numero_nuevo'  value=$mynumero required pattern='\d*'>";
    											echo "<input type='hidden' name='numero_original' id='numero_original' value=$mynumero>";
    										?>
    								</div>
    								
    								<div class="col-3 col-12-mobilep">
    <?php
    echo "Año";
    
    echo "<select name='año_nuevo' id='año_nuevo'>";
    echo "<option value='$myaño' selected>$myaño</option>";
    for ($i = 2016; $i <= 2020; $i ++) {
        if ($i != $myaño) {
            echo "<option value='$i'>$i</option>";
        }
    }
    echo "</select>";
    echo "<input type='hidden' name='año_original' id='año_original' value='$myaño'> ";
    ?>						
    								
    							  </div>
    							  <div class="col-3 col-12-mobilep">
   <?php
   echo "Fecha <br>";
   
   echo "<input type='date' name='fecha'  id='fecha'  value='$myfecha' required>";
   ?> 
    							  	
    							  </div>
    							  
    							  <div class="col-3 col-12-mobilep">	
    							  
    <?php
    echo "Nombre";
    
    echo "<input type='text' name='nombre'  id='nombre'  value='$mynombre'>";
    ?>						
    							  
    							  </div>
    							  
    							  <div class="col-12">
    <?php							  
    echo "Descripcion";
    echo "<textarea name='descripcion'  id='descripcion' value='$mydescripcion' rows='4'>$mydescripcion</textarea>";
    ?>
    							</div>
    
    <?php 
    
    
    
    
    
    
    
    if (! empty($_POST['id_diligencias'])) {
        $sonnuevas=0;
        $myid_diligencias = mysqli_real_escape_string($link, $_POST['id_diligencias']);
        $mydiligencias = mysqli_real_escape_string($link, $_POST['diligencias']);
        $myaño_diligencias = mysqli_real_escape_string($link, $_POST['año_diligencias']);
        $myid_juzgado = mysqli_real_escape_string($link, $_POST['id_juzgado']);
        $myjuzgado = mysqli_real_escape_string($link, $_POST['juzgado']);
        $mynum_juzgado = mysqli_real_escape_string($link, $_POST['num_juzgado']);
    
    ?>
    
    							<div class="col-4 col-12-mobilep">
    <?php						
        echo "Numero Diligencias";
        echo "<input type='text' name='diligencias_nuevo' id='diligencias_nuevo'  value=$mydiligencias required pattern='\d*'>";
    	echo "<input type='hidden' name='diligencias_original' id='diligencias_original' value='$mydiligencias'>";
    	echo "<input type='hidden' name='id_diligencias' id='id_diligencias' value='$myid_diligencias'>";
    	
        
    ?>
    							
    							</div>
    <?php							
    
    ?>
    							<div class="col-4 col-12-mobilep">
    
    <?php
        echo "Año";
        echo "<select name='año_diligencias_nuevo' id='año_diligencias_nuevo'>";
        echo "<option value='$myaño_diligencias' selected>$myaño_diligencias</option>";
        for ($i = 2016; $i <= 2020; $i ++) {
            if ($i != $myaño_diligencias) {
                echo "<option value='$i'>$i</option>";
            }
        }
        echo "</select>";
    
        echo "<input type='hidden' name='año_diligencias_original' id='año_diligencias_original' value='$myaño_diligencias'>";
    ?>							
    
    							</div>
    							
    							<div class="col-4 col-12-mobilep">
    							
    <?php
    // cargo la lista de juzgados
    	echo "Juzgado";
        echo "<select name='juzgado_nuevo' id='juzgado_nuevo'>";
        echo "<option value='$myid_juzgado' selected>$myjuzgado $mynum_juzgado</option>";
        $resultado = mysqli_query($link, "select id_juzgado, nombre, numero FROM juzgado where id_juzgado!=0 and id_juzgado!=$myid_juzgado");
        $contador = 2;
        while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            foreach ($line as $col_value) {
                if ($contador == 2) {
                    echo "<option value='$col_value'>";
                    $contador = 0;
                } else {
                    if ($contador == 0) {
                        echo $col_value;
                        $contador ++;
                    } else {
                        echo " " . $col_value . "</option>";
                        $contador ++;
                    }
                }
            }
        }
        echo "</select>";
        echo "<input type='hidden' name='juzgado_original' id='juzgado_original' value='$myid_juzgado'>";
    ?>
    						</div>
    <?php
    
        
    }
    else {
    ?>
    						<div class="col-4 col-12-mobilep">
    <?php
    
        $sonnuevas=1;
        echo "Numero Diligencias";
        echo "<input type='text' name='diligencias_nuevo' id='diligencias_nuevo'  disabled required pattern='\d*'>";
        					echo "</div>";
    
    						echo "<div class='col-4 col-12-mobilep'>  ";
        echo "Año:";
        echo "<select name='año_diligencias_nuevo' id='año_diligencias_nuevo' disabled>";
        echo "<option value='0' selected></option>";
        for ($i = 2016; $i <= 2020; $i ++) {
            if ($i != $myaño_diligencias) {
                echo "<option value='$i'>$i</option>";
            }
        }
        echo "</select>";
        					echo "</div>";
    						echo "<div class='col-4 col-12-mobilep'>  ";
    	echo "Tipo Delictivo";
        echo "<select name='juzgado_nuevo' id='juzgado_nuevo' disabled>";
        echo "<option value='0' selected></option>";
        $resultado = mysqli_query($link, "select id_juzgado, nombre, numero FROM juzgado where id_juzgado!=0");
        $contador = 2;
        while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            foreach ($line as $col_value) {
                if ($contador == 2) {
                    echo "<option value='$col_value'>";
                    $contador = 0;
                } else {
                    if ($contador == 0) {
                        echo $col_value;
                        $contador ++;
                    } else {
                        echo " " . $col_value . "</option>";
                        $contador ++;
                    }
                }
            }
        }
        
        echo "</select>";
    						echo "</div>";				
        
    }
    ?>							
    							
    
    							
    							<div class="col-4 col-12-mobilep">
    							
    <?php
    // Cargo la lista de tipos delictivos
    echo "Tipo Delictivo";
    
    echo "<select name='tipo_caso' id='tipo_caso'>";
    echo "<option value='$myid_tipo' selected>$mytipo</option>";
    $resultado = mysqli_query($link, "select * FROM tipo_caso");
    $contador2 = 0;
    $entro = 0;
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        foreach ($line as $col_value) {
            if ($contador2 == 0) {
                if ($col_value == $myid_tipo) {
                    $entro = 1;
                    $contador2 ++;
                } else {
                    echo "<option value='$col_value'>";
                    $contador2 ++;
                }
            } else {
                if ($entro == 1) {
                    $entro = 0;
                    $contador2 = 0;
                } else {
                    echo " " . $col_value . "</option>";
                    $contador2 = 0;
                }
            }
        }
    }
    echo "</select>";
    ?>							
    							</div>
    							
    							<div class="col-4 col-12-mobilep">
    <?php
    // cargo la lista de CA
    echo " Comunidad Autónoma:";
    echo "<select name='ca' id='ca' onchange='sel_provincia(this.value);' autofocus>";
    echo "<option value=$myid_ca>$myca</option>";
    echo "<option value=''>Selecciona una CA</option>";
    $resultado = mysqli_query($link, "select id_ca,nombre_ca FROM CA");
    $contador = 0;
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        foreach ($line as $col_value) {
            if ($contador == 0) {
                echo "<option value='$col_value'>";
                $contador ++;
            } else {
                echo " " . $col_value . "</option>";
                $contador = 0;
            }
        }
    }
    echo "</select>";
    ?>							
    							</div>
    							
    							<div class="col-4 col-12-mobilep">
    <?php
    // cargo la lista de provincias segun la CA seleccionada
    
    echo " Provincias";
    echo "<select id='provincia' name='provincia' onchange='sel_comisaria(this.value);'>";
    echo "<option value=$myid_provincia>$myprovincia</option>";
    echo "</select>";
    ?>							
    							</div>
    							
    							<div class="col-4 col-12-mobilep">
    <?php
    // cargo la lista de comisarias segun la provincia seleccionada
    
    echo " Comisaría";
    echo "<select id='comisaria' name='comisaria' onchange='sel_grupo(this.value);'>";
    echo "<option value=$myid_comisaria>$mycomisaria</option>";
    echo "</select>";
    
    ?>							
    							</div>
    							
    							<div class="col-4 col-12-mobilep">
    <?php
    // cargo la lista de grupos segun la comisaria seleccionada
    
    echo " Grupo";
    echo "<select id='grupo' name='grupo' required>";
    echo "<option value=$myid_grupo>$mygrupo</option>";
    echo "</select>";
    ?>							
    							</div>
    							
    							<div class="col-4 col-12-mobilep">
    <?php
    echo "Estado: ";
    echo "<select name='estado' id='estado'>";
    echo "<option value='1'> Abierto </option>";
    echo "<option value='2'> Cerrado </option>";
    echo "</select>";
    ?>							
    							</div>
    							
    							<br>
    							<div align="center">
    							<canvas id="mensajes" width="600" height="30"></canvas>
    							</div>
    							<div class="col-12">
    									<ul class="actions special">
    									
    <?php
    										echo "<li><input type='hidden' name='borrar' id='borrar' value=$borrar></li>";
    										echo "<li><input type='hidden' name='sonnuevas' id='sonnuevas' value=$sonnuevas></li>";
    										echo "<li><input type='submit' id='modificar' value='Modificar' ></li>";
    										if (! empty($_POST['id_diligencias'])) {
    											echo "<li><input type='button' id='btn1' value='Borrar Diligencias' class='caso' onclick='eliminar()'></li>";
    										}
    										else {
    											echo "<li><input type='button' id='btn1' value='Agregar Diligencias' class='caso' onclick='agregar()'></li>";
    										}
    ?>
    										<li><input type="button" onclick="location.href='asunto.php';" value="Volver"></li>						
    									</ul>
    								</div>
    								
    							
    <?php
}
else {
    echo "Error";
}
?>
						</div>
							
						</form>
					</div>
			</section>
	</div>
