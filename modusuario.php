<?php
session_start();

if(isset($_SESSION['id_u'])) {

    $myadmin=$_SESSION['admin'];
    
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    if(isset($_POST['id_usuario'])) {
        $myid_usuario = mysqli_real_escape_string($link,$_POST['id_usuario']);
    }
    //$sql="SELECT * from usuario where id_usuario=$myid_usuario";
    
    $sql="SELECT u.*, g.nombre as nombre_grupo_ciber, e.nombre as nombre_escala, c.nombre as nombre_categoria, co.* FROM usuario u
    INNER JOIN grupo_ciber g ON u.id_grupo_ciber=g.id_grupo_ciber
    INNER JOIN escala e ON u.id_escala=e.id_escala 
    INNER JOIN categoria c ON u.id_categoria=c.id_categoria 
    INNER JOIN coche co ON co.id_usuario=u.id_usuario
    WHERE u.id_usuario=$myid_usuario";
    $resultado=mysqli_query($link, $sql);
    
    $count=mysqli_num_rows($resultado);
    if($count==0) {
        ?>       
             <script type="text/javascript">
                alert("Fallo al realizar la consulta del usuario de la tabla : usuario.\n\n Detalles: \n\n <?php echo  mysqli_error($link) ?>");
                location.href = "admin.php";
             </script>
        
        <?php
    }
    else {
        $ret = mysqli_fetch_array($resultado);
    }
    ?>
    
    
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Gestion de usuarios</title>
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
    		<script type="text/javascript">
    		
    		$('#escala').on('change',function(){
    		
        	var category=$("#escala").val();
        	var url="obtenercategoria.php";
    
    		$.ajax({
    
            	url:url,
                type:"POST",
                data:{category:category}
    
    		}).done(function(data){
    
            	$("#categoria").html(data);
            })    
         	});
    		
        </script>    
    	<script>
    		function validar(valor) {
    			var c = document.getElementById("mensaje");
    			var ctx = c.getContext("2d");
    			ctx.font = "bold 12px Verdana";
    			var formdata = new FormData($("#miform")[0]);
    			if (valor == 1)
    				{ 
    					var destino="modifiusuario.php"
    				}
    				else
    				{
    					var destino="eliminarusuario.php"
    				}
    			$.ajax({
    				type: "POST",
    				url: destino,				
    					 data:formdata,
    					 contentType: false,
    					 processData: false,
    				success: function(respuesta) {
    					if(respuesta==2 || respuesta==3) {
    						
    						window.location="admin.php";
    					}
    					else {
    						ctx.strokeStyle = "#FF0000";
    						ctx.strokeRect(1, 1, 799, 29);
    						ctx.fillStyle = "#FF0000";
    						ctx.textAlign = "center";
    						ctx.fillText(respuesta,400,20);
    					}
    				}
    			});
    		};
    
    			
    
    		</script> 
    	
    </head>
    
    <body class="is-preload">
    		<div id="page-wrapper">
    
    			<!-- Main -->
    				<section id="main" class="container">
    					
    					<header>
    						<h2>Gestión de Usuario</h2>						
    					</header>
    					
    					<div class="box">
    						<form name=idformulario id="miform" method="post" enctype="multipart/form-data">
    							
    							<div align="center">
    								<canvas id="mensaje" width="800" height="30"></canvas>
    							</div>
    						
    							<div class="row gtr-50 gtr-uniform">
    								<div class="col-6 col-12-mobilep">
    									<input type="text" value="1. - Informacion de: Datos personales" style="font-weight: bold;" readonly="readonly"/>		
    								
        							</div>
    								<div class="col-4 col-12-mobilep" align="right">
    									
        								<?php echo '<img src="data:image/jpeg;base64,'.base64_encode($ret['foto']).' " width="100" height="100"/>' ;?>
        							</div>
    							</div>
    							<br>
    							<div class="row gtr-50 gtr-uniform">
    										
    								<?php echo "<input type='hidden' name='Idusuario' id='Idusuario' value='$myid_usuario'>";?>
    								
    									
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="nombre_nuevo" id="nombre_nuevo" value="<?php echo $ret['nombre'];?>" placeholder="Nombre" required/>
    									<?php echo "<input type='hidden' name='nombre_original' id='nombre_original' value='$ret[nombre]'>";?>
    								
    								</div>				
    								
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="apellido1_nuevo" id="apellido1_nuevo" value="<?php echo $ret['apellido1'];?>" placeholder="Primer Apellido" required/>
    									<?php echo "<input type='hidden' name='apellido1_original' id='apellido1_original' value='$ret[apellido1]'>";?>
    								
    							
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="apellido2_nuevo" id="apellido2_nuevo" value="<?php echo $ret['apellido2'];?>" placeholder="Segundo Apellido" required/>
    									<?php echo "<input type='hidden' name='apellido2_original' id='apellido2_original' value='$ret[apellido2]'>";?>
    								
    							
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="DNI_nuevo" id="DNI_nuevo" value="<?php echo $ret['dni'];?>" placeholder="D.N.I" maxlength="9" required>
    									<?php echo "<input type='hidden' name='DNI_original' id='DNI_original' value='$ret[dni]'>";?>
    								
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="CP_nuevo" id="CP_nuevo" value="<?php echo $ret['cp'];?>" placeholder="C.P." maxlength=6 required>
    									<?php echo "<input type='hidden' name='CP_original' id='CP_original' value='$ret[cp]'>";?>
    								
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="telefono_nuevo" id="telefono_nuevo" value="<?php echo $ret['telefono'];?>" placeholder="Telefono" maxlength="9">
    									<?php echo "<input type='hidden' name='telefono_original' id='telefono_original' value='$ret[telefono]'>";?>
    								
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="ecala_nuevo" id="escala_nuevo" value="<?php echo $ret['nombre_escala'];?>" placeholder="Escala" readonly="readonly"/>
    								
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="categoria_nuevo" id="categoria_nuevo" value="<?php echo $ret['nombre_categoria'];?>" placeholder="Categoria" readonly="readonly"/>
    									
    								</div>
    
    							<?php 	
    								
    
    							//grupo ciber
    							echo "<div class='col-4 col-12-mobilep'>	<select name='grupo_ciber_nuevo' id='grupo_ciber_nuevo'>"; 
    							echo "<option value='$ret[id_grupo_ciber]' selected>$ret[nombre_grupo_ciber]</option>";
                                
    							$resultado = mysqli_query($link, "select * FROM grupo_ciber");
    							$contador=0;
    							while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
    							    foreach ($line as $col_value) {
    							        if ($contador==0) {
    							            echo "<option value='$col_value'>";
    							            $contador++;
    							        }
    							        else {
    							            echo " ".$col_value."</option>";
    							            $contador=0;
    							        }
    							    }
    							}
    							echo "</select> </div>";
    						    echo "<input type='hidden' name='grupo_original' id='grupo_original' value='$ret[nombre_grupo_ciber]'>";
    							
    							
    							?>	
    							
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" name="apodo_nuevo" id="apodo_nuevo" value="<?php echo $ret['apodo'];?>" placeholder="Apodo" required>
    									<?php echo "<input type='hidden' name='apodo_original' id='apodo_original' value='$ret[apodo]'>";?>
    								
    								</div>						
    								 <div class="col-4 col-12-mobilep">	
    									<input type="text" name="usuario_nuevo" id="usuario_nuevo" value="<?php echo $ret['usuario'];?>" placeholder="Usuario" required>
    									<?php echo "<input type='hidden' name='usuario_original' id='usuario_original' value='$ret[usuario]'>";?>
    								
    								</div>	
    								 <div class="col-4 col-12-mobilep">	
    									<input type="password" name="password_nuevo" id="password_nuevo" placeholder="Contraseña">
    									<?php echo "<input type='hidden' name='password_original' id='password_original' value='$ret[pass]'>";?>
    								
    								</div>
    								 <input type='hidden' name="myadmin" id="myadmin" value="<?php echo $myadmin;?>">
    								
    								<div class="col-4 col-12-mobilep">	
    									<input type="email" name="email_nuevo" id="email_nuevo" value="<?php echo $ret['mail'];?>" placeholder="Correo Electrónico">
    									<?php echo "<input type='hidden' name='email_original' id='email_original' value='$ret[mail]'>";?>
    								
    								</div>
    								<div class="col-4 col-12-mobilep">	
    									<input type="text" value="Foto [selecciona el fichero]" readonly="readonly">
    									<input type="file" id="imagen" name="imagen" accept="image/png, .jpeg, .jpg, image/gif">
    									
    								</div>
    								<?php 	
    								
    							//administrador
    							echo "<div class='col-4 col-12-mobilep'>";
                                echo "<select name='administrador_nuevo' id=administrador_nuevo> onchange=escribir(this.value);"; 
                                if($ret[id_perfil_safe]==2) {
                                    echo "<option value='SI' selected>SI</option>";
                                    echo "<option value='NO'>NO</option>";
                                }
                                else {
                                    echo "<option value='SI'>SI</option>";
                                    echo "<option value='NO' selected>NO</option>";
                                }
                                echo "</select> </div>";
    						    echo "<input type='hidden' name='administrador_original' id='administrador_original' value='$ret[id_perfil_safe]'>";
    							
    							
    							?>
    							</div>
    							<br><br>
    							<div class="row gtr-50 gtr-uniform">
    								<div class="col-6 col-12-mobilep">
    									<input type="text" value="2. - Informacion de: Datos de vehiculo" style="font-weight: bold;" readonly="readonly"/>		
    								
        							</div>
    								
        							<div class="col-4 col-12-mobilep">
    									
        							</div>
        							<div class="col-4 col-12-mobilep">
    									
        							</div>
    							</div>
    							
    							<br>
    								<div class="row gtr-50 gtr-uniform">
        								<div class="col-4 col-12-mobilep">	
    										<input type="text" name="marca_nuevo" id="marca_nuevo" value="<?php echo $ret['marca'];?>" placeholder="Marca Vehículo">
    										<?php echo "<input type='hidden' name='marca_original' id='marca_original' value='$ret[marca]'>";?>
    									</div>
    									<div class="col-4 col-12-mobilep">	
    										<input type="text" name="modelo_nuevo" id="modelo_nuevo" value="<?php echo $ret['modelo'];?>" placeholder="Modelo Vehículo">
    										<?php echo "<input type='hidden' name='modelo_original' id='modelo_original' value='$ret[modelo]'>";?>
    								
    									</div>
    									<div class="col-4 col-12-mobilep">	
    										<input type="text" name="matricula_nuevo" id="matricula_nuevo" value="<?php echo $ret['matricula'];?>" placeholder="Matrícula Vehículo" maxlength="7" >
    										<?php echo "<input type='hidden' name='matricula_original' id='matricula_original' value='$ret[matricula]'>";?>
    								
    									</div>
    									<div class="col-4 col-12-mobilep">	
    										<input type="text" name="color_nuevo" id="color_nuevo" value="<?php echo $ret['color'];?>" placeholder="Color Vehículo">
    										<?php echo "<input type='hidden' name='color_original' id='color_original' value='$ret[color]'>";?>
    
    									</div>
    								
    							
    								</div>
    						<br>
    						<div class="col-12">
    							<ul class="actions special">
    								<li><input type="button" onclick="validar(1)" value="Modificar" /></li>		
    								<li><input type="button" onclick="validar(2)" value="Eliminar" /></li>	
    								<li><input type="button" onclick="location.href='admin.php';" value="Volver" class="estilo"></li>							
    							</ul>
    						</div>
    
    					    
    					    					    
    					  </form>
    					  
    					  
    								    
    						
    				</div>	
    			</section>
    		</div>	
    
    </body>
    <?php 
}
else {
    echo "Error";
}
    
    ?>
</html>
