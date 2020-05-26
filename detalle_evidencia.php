<?php

session_start();

if(isset($_SESSION['id_u'])) {
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    
    $myid_caso=$_SESSION['id_caso'];
    $sql="SELECT numero,año FROM caso where id_caso=$myid_caso";
    $result=mysqli_query($link, $sql);
    $ret_caso=mysqli_fetch_array($result);
    $myid_u=$_SESSION['id_u'];
    if(isset($_GET['nombre'])) {
        $mynombre=base64_decode(mysqli_real_escape_string($link, $_GET['nombre']));
        $_SESSION['nombre']=$mynombre;
    }
    else {
        $mynombre=$_SESSION['nombre'];
    }
    if(isset($_GET['numero'])) {
        $mynumero=base64_decode(mysqli_real_escape_string($link, $_GET['numero']));
        $_SESSION['numero']=$mynumero;
    }
    else {
        $mynumero=$_SESSION['numero'];
    }
    
    
    $respuesta=$_SESSION['respuesta'];
    $_SESSION['mod']=0;
    $_SESSION['patron']=null;
    
    
    $resultado = mysqli_query($link, "SELECT i.numero_intervencion as nom_i, e.id_evidencia as id_ev, e.nombre as nom_ev, e.numero_evidencia as num_ev,i.id_intervencion as id_int,
    i.direccion as int_dir, e.id_tipo_evidencia as id_tip, e.numero_evidencia as num_ev, e.id_subtipo_evidencia as id_sub,t.nombre as nom_tip, s.nombre as nom_sub,
    e.id_disco_almacenado as id_disc, d.nombre as nom_disc, e.id_caso as id_caso, e.n_s as n_s, e.capacidad, e.marca, e.modelo, e.observaciones as obs_ev, e.alias as al, e.patron as pat,
    e.pin as pin, tp.tipo_capacidad as tipo_capacidad, e.id_tipo_capacidad as id_tipo_capacidad
    FROM evidencia e
    INNER JOIN tipo_evidencia t ON e.id_tipo_evidencia=t.id_tipo_evidencia
    INNER JOIN subtipo_evidencia s ON e.id_subtipo_evidencia=s.id_subtipo_evidencia
    LEFT JOIN disco_almacenado d ON d.id_disco_almacenado=e.id_disco_almacenado
    INNER JOIN intervencion i  ON i.id_intervencion=e.id_intervencion
    LEFT JOIN tipo_capacidad tp  ON tp.id_tipo_capacidad=e.id_tipo_capacidad
    WHERE e.id_caso=$myid_caso and e.nombre='$mynombre' and e.numero_evidencia='$mynumero'");
    $ret = mysqli_fetch_array($resultado);
    $myid_ev=$ret['id_ev'];
    $_SESSION['id_ev']=$myid_ev;
    
    /*incluyo estas dos lineas*/
    $resultado_dependientes=mysqli_query($link, "SELECT id_evidencia from evidencia where relacionado_con=$myid_ev");
    
    $contador_relacionado_con=mysqli_num_rows($resultado_dependientes);
    
    
    
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Detalle	 Evidencia</title>
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
    			if(respuesta==1) {
    				var c = document.getElementById("mensaje");
    				var ctx = c.getContext("2d");
    				ctx.font = "bold 12px Verdana";
    				ctx.clearRect(0, 0, c.width, c.height);
    				ctx.strokeStyle = "#3DBA26";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#3DBA26";
    				ctx.textAlign = "center";
    				ctx.fillText("Evidencia modificada",150,20);
    				<?php $_SESSION['respuesta']=0; ?>
    				setTimeout(borrar,5000);
    			}
    			if(respuesta==2) {
    				var c = document.getElementById("mensaje");
    				var ctx = c.getContext("2d");
    				ctx.font = "bold 12px Verdana";
    				ctx.clearRect(0, 0, c.width, c.height);
    				ctx.strokeStyle = "#3DBA26";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#3DBA26";
    				ctx.textAlign = "center";
    				ctx.fillText("Registro añadido",150,20);
    				<?php $_SESSION['respuesta']=0; ?>
    				setTimeout(borrar,5000);
    			}
    			if(respuesta==3) {
    				var c = document.getElementById("mensaje");
    				var ctx = c.getContext("2d");
    				ctx.font = "bold 12px Verdana";
    				ctx.clearRect(0, 0, c.width, c.height);
    				ctx.strokeStyle = "#3DBA26";
    				ctx.strokeRect(1, 1, 299, 29);
    				ctx.fillStyle = "#3DBA26";
    				ctx.textAlign = "center";
    				ctx.fillText("Registro eliminado",150,20);
    				<?php $_SESSION['respuesta']=0; ?>
    				setTimeout(borrar,5000);
    			}
    			
    			
    		}
    </script>
    <script type="text/javascript">
    		function borrar() {
    			var c = document.getElementById("mensaje");
    			var ctx = c.getContext("2d");
    			ctx.clearRect(0, 0, c.width, c.height);
    		};
    		
    		</script>
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
    function cambiarhash(estado) {
    	var est=estado;
    	if(est==3) {
    		document.getElementById('num_hash').disabled = true;
    		document.getElementById('tipo_hash').disabled = true;
    		document.getElementById('accion_programa').disabled=true;
    		document.getElementById('accion_programa').value='';
    	}
    	else {
    		document.getElementById('num_hash').disabled = false;
    		document.getElementById('tipo_hash').disabled = false;
    	
    	}
    }
    
    </script>
    
    <!-- XXXXXX incluyo esto  -->
    
    <script type="text/javascript">
    	function pregunta(num_evidencias){ 
    		var nev=num_evidencias;
    		if(nev==0) {
    			if (confirm('¿Estas seguro de eliminar los datos de la evidencia?')){ 
    				$.ajax({
    					type: "POST",
    					url: "eliminarevidencia.php",
    					contentType: false,
    					processData: false,
    					success: function(respuesta) {
    						location.href = "asunto.php";
    					}
    				});
       			}
     			else {
     				location.href = "detalle_evidencia.php";
     	 	 	} 
    		}
    		else {
    
    			alert("No se puede eliminar la evidencia porque hay otras dependiendo de ella");
    
    				
    
    		}
    	}
    	</script> 
    <!-- XXXXXX incluyo esto  -->
    
    </head>
    



	<body class="is-preload" onload="respuesta();">
    	<div id="page-wrapper">
    	
    		<!-- Header -->
    				<header id="header">
    					<h1><a href="login.php">Safe Ciber</a> Gesti&oacuten Secci&oacuten Ciberterrorismo</h1>
    					<nav id="nav">
    						<ul>
    							<li><a href="inicio.php">Home</a></li>
    							<li>
    								<a href="#" class="icon solid fa-angle-down">Casos</a>
    								<ul>
    									<li><a href="generic.html">Buscar</a></li>
    									<li><a href="contact.html">Nuevo</a></li>
    
    									<li>
    										<a href="#">Listar</a>
    										<ul>
    											<li><a href="#">Abiertos</a></li>
    											<li><a href="#">Cerrados</a></li>
    											<li><a href="#">Todos</a></li>
    										</ul>
    									</li>
    								</ul>
    							</li>
    							<li><a href="#" class="button">Sign Up</a></li>
    						</ul>
    					</nav>
    				</header>
   
    	<!-- Main -->
    		<section id="main" class="container">
    			<header>
    				<h2>Operación <?php echo $ret_caso['numero']."_".substr($ret_caso['año'], 2, 2) ?></h2>
    				<h3><p>Detalle de Evidencia</p></h3>
    			</header>
    			<div align="center">
    				<canvas id="mensaje" width="300" height="30"></canvas>
    			</div>
    			<div class="row">
    						<div class="col-12">
    
    							<!-- Lists -->
    							<form action='modificarevidencia.php' method='post'>
    							
    							
    								<section class="box">

    									<h3>Evidencia <b><?php echo $ret['nom_ev'];echo $ret['num_ev'];?></b></h3>
    									
    									<div class="row">
    										<div class="col-6 col-12-mobilep">
    										
    											<ul class="alt">
    												<li><b>Marca :</b> <?php echo $ret['marca'] ;?></li>
    												<input type="hidden" name="marca" id="marca" value="<?php echo $ret['marca']; ?>">
    												<li><b>Modelo :</b> <?php echo $ret['modelo'];?></li>  
    												<input type="hidden" name="modelo" id="modelo" value="<?php echo $ret['modelo']; ?>">
    												<li><b>Tipo de evidencia :</b> <?php echo $ret['nom_tip'];?></li>
    												<input type="hidden" name="tipo" id="tipo" value="<?php echo $ret['nom_tip']; ?>">
    												<input type="hidden" name="id_tipo" id="d_tipo"  value="<?php echo $ret['id_tip']?>">
    												<li><b>Subtipo de evidencia :</b> <?php echo $ret['nom_sub'];?></li>
    												<input type="hidden" name="subtipo" id="subtipo" value="<?php echo $ret['nom_sub']; ?>">
    												<input type="hidden" name="id_subtipo" id="id_subtipo" value="<?php echo $ret['id_sub']?>">  
    												
    												
    												
    												
<?php    
    if(!empty($ret['al'])) {
?>
													<li><b>Alias :</b> <?php echo $ret['al'];?></li>
        
<?php        
    }
    else {
        echo "<input type='hidden' name='alias' id='alias' value=''>";
    }

if(!empty($ret['pin'])) {

?>
        											
<?php
    }
    else {
        echo "<input type='hidden' name='pin' id='pin' value=''>";
    }

?>
    												<li><b>Dirección de intervencion :</b> <?php echo $ret['int_dir'];?></li>
    											</ul>
    											<input type="hidden" name="nombre" id="nombre" value="<?php echo $ret['nom_ev']; ?>">
    											<input type="hidden" name="numero" id="numero" value="<?php echo $ret['num_ev']; ?>">
    											<input type='hidden' name='id_intervencion' id='id_intervencion' class='estilo' value='<?php echo $ret['id_int']?>'>
    											<input type='hidden' name='numero_intervencion' id='numero_intervencion' class='estilo' value='<?php echo $ret['nom_i'] ?>'>
    										</div> <!-- div -->
    									
    										
    										<div class="col-6 col-12-mobilep">
    										
    											<ul class="alt">
    												<li><b>Capacidad:</b> <?php echo $ret['capacidad'];?>
    												<input type="hidden" name="capacidad" id="capacidad" value="<?php echo $ret['capacidad']; ?>">
    												<input type="hidden" name="capacidad" id="capacidad" value="<?php echo $ret['capacidad']; ?>">
    												
    												<?php
														    if($ret['capacidad']!=null) {
                                                                echo $ret['tipo_capacidad'];
                                                                echo "<input type='hidden'  name='tipo_capacidad' id='tipo_capacidad' value='$ret[tipo_capacidad]'>";
                                                            }
                                                            else {
                                                                echo "<input type='hidden'  name='tipo_capacidad' id='tipo_capacidad' value='$ret[tipo_capacidad]'>";
                                                            }
	                                                        echo "<input type='hidden'  name='id_tipo_capacidad' id='id_tipo_capacidad' value='$ret[id_tipo_capacidad]'>";
?>
    												
    												</li>
    												
    												  												
    												
    												
    												
    												<li><b>Disco :</b> <?php echo $ret['nom_disc'];?></li>
    												<input type='hidden' name='disco' id='disco' class='estilo' value='<?php  echo $ret['nom_disc']?>'>
    												<input type='hidden' name='id_disco' id='id_disco' class='estilo' value='<?php  echo $ret['id_disc']?>'>
    												<li><b>N/S:</b> <?php echo $ret['n_s'];?></li>
    												<input type="hidden" name="n_s" id="n_s" value="<?php echo $ret['n_s']; ?>">
    												<li><b>Observaciones :</b> <?php echo $ret['obs_ev'];?></li>
    												<input type="hidden" name="observaciones" id="observaciones" value="<?php echo $ret['obs_ev']; ?>">
    												<li><b>Pin :</b> <?php echo $ret['pin'];?></li>
    												<input type="hidden" name="pin" id="pin" value="<?php echo $ret['pin']; ?>">
    												<li></li>
    											</ul>
<?php    												
    											
    											if(!empty($ret['pat'])) {
        echo "<b>Patron:</><br>";
        $primeronumero=0;
        $patron=$ret['pat'];
        $longitud=strlen($patron)-1;
        echo "<svg width='250' height='75'>";
        for ($i = 0; $i <= $longitud; $i++) {
            $numero=substr($patron, $i, 1);
            if(is_numeric(substr($patron, $i, 1))) {
                $numero=substr($patron, $i, 1);
                $siguiente=substr($patron, $i+2, 1);
                $ultimo=substr($patron,$longitud-1,1);
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
        echo "<input type='hidden' name='patron' id='patron' value='$ret[pat]'>";
    }
    else {
        echo "<input type='hidden' name='patron' id='patron' value=''>";
    }
    											
?>    											
    											
    										</div>
    										
    										
    										
    										
    									
    										
    										
    									<div class="col-12">
    											<ul class="actions special">
    												<li><input type='submit' value='Modificar' class="button special small"></li>
    											
    												<li><input type='hidden' name="numero_evidencias" id="numero_evidencias" value="<?php echo $contador_relacionado_con;?>" ></li>
    												<li><input type="button" onclick="pregunta(<?php echo $contador_relacionado_con;?>)" value="Eliminar evidencias" class="button special small"></li>
    												<li><input type="button" onclick="location.href='asunto.php';" value="Volver" class="button special small"></li>
    											</ul>
    									</div>
    									<br>
    									<div class="col-12 col-12-mobilep">
    									
    									
    									
    									<?php
    
    //compruebo si tiene subevidencias y en caso afirmativo cargo la tabla
    $sql= "select e.nombre, e.numero_evidencia, s.nombre as nom_sub, t.nombre as nom_tipo,  e.n_s, e.capacidad, e.marca, e.modelo from evidencia e
                        inner join tipo_evidencia t on t.id_tipo_evidencia=e.id_tipo_evidencia
                        inner join subtipo_evidencia s on s.id_subtipo_evidencia=e.id_subtipo_evidencia
                        inner join caso c on c.id_caso=e.id_caso
                        where c.id_caso='$myid_caso' AND relacionado_con=$myid_ev order By id_intervencion asc";
    $resultado_subevidencia=mysqli_query($link, $sql);
    $count=mysqli_num_rows($resultado_subevidencia);
    
    if($count!=0) {
        echo "<h4><p><b>Evidencias relacionadas con ".$ret['nom_ev'].$ret['num_ev']."</b></p></h4>";
        
        echo "<div class='table-wrapper'><table><thead><tr><th>Nombre</th><th>Tipo Almacenamiento</th><th>Subtipo</th><th>Numero Serie</th><th>Capacidad</th><th>Marca</th><th>Modelo</th></tr></thead>";
        $contador=0;
        while ($line_evidencias = mysqli_fetch_array($resultado_subevidencia, MYSQLI_ASSOC)) {
            foreach ($line_evidencias as $col_value) {
                    if ($contador == 0) {
                        $nombre_original = $col_value;
                        $nombre=base64_encode($nombre_original);
                        $contador++;
                    } 
                    else {
                        if ($contador == 1) {
                            echo "<td>";
                            $numero=base64_encode($col_value);
                            echo "<a  href='detalle_evidencia.php?nombre=$nombre&numero=$numero'>$nombre_original$col_value</a>";
                            echo "</td>";
                            $contador ++;
                        }
                        else {
                                if ($contador < 7) {
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
        echo "</table></div>";
    }
    else {
        //compruebo si depende de alguna evidencia y en caso afirmativo cargo la tabla
        $sql= "select e.relacionado_con as rel from evidencia e
                WHERE e.id_evidencia=$myid_ev ORDER By id_intervencion asc";
                $resultado_evidencia_padre=mysqli_query($link, $sql);
                $ret2=mysqli_fetch_array($resultado_evidencia_padre);
                if($ret2['rel']!=null) {
                    $sql= "select e.nombre, e.numero_evidencia, s.nombre as nom_sub, t.nombre as nom_tipo,  e.n_s, e.capacidad, e.marca, e.modelo from evidencia e
                    inner join tipo_evidencia t on t.id_tipo_evidencia=e.id_tipo_evidencia
                    inner join subtipo_evidencia s on s.id_subtipo_evidencia=e.id_subtipo_evidencia
                    inner join caso c on c.id_caso=e.id_caso
                    where c.id_caso='$myid_caso' AND id_evidencia=$ret2[rel] order By id_intervencion asc";
                    $resultado_padre=mysqli_query($link, $sql);
                    $count=mysqli_num_rows($resultado_padre);
                    if($count!=0) {
                                          
                        echo "<div class='table-wrapper'><table ><thead><tr><th>Nombre</th><th>Tipo Almacenamiento</th><th>Subtipo</th><th>Numero Serie</th><th>Capacidad</th><th>Marca</th><th>Modelo</th></tr></thead>";
                        $contador=0;
                        while ($line_padre = mysqli_fetch_array($resultado_padre, MYSQLI_ASSOC)) {
                        foreach ($line_padre as $col_value) {
                            if ($contador == 0) {
                                $nombre_original = $col_value;
                                $nombre=base64_encode($nombre_original);
                                $contador++;
                            }
                            else {
                                if ($contador == 1) {
                                    echo "<td>";
                                    $numero=base64_encode($col_value);
                                    echo "<a style='color:#000000;' href='detalle_evidencia.php?nombre=$nombre&numero=$numero'>$nombre_original$col_value</a>";
                                    echo "</td>";
                                    $contador ++;
                                }
                                else {
                                    if ($contador < 7) {
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
                    echo "</table></div>";
                    }
                }
    }

//creo la lista de registro
    $sql = "select er.id_evidencia_registro,u.apodo,es.nombre as estado, p.nombre as programa, ap.nombre as accion_programa, h.hash, er.observaciones, 
    date_format(er.fecha_alta_estado, '%d/%m/%Y %H:%i') as fecha_alta_estado from evidencia_registro er
    inner join usuario u on er.id_usuario=u.id_usuario
    inner join estado_evidencia es on er.id_estado_evidencia=es.id_estado_evidencia
    left join programa p on er.id_programa=p.id_programa
    left join accion_programa ap on er.id_accion_programa=ap.id_accion_programa
    left join hash h on er.id_hash=h.id_hash
    where er.id_evidencia=$ret[id_ev] ORDER BY er.fecha_alta_estado DESC";
    $resultado=mysqli_query($link, $sql);
    $count=mysqli_num_rows($resultado);

?>
    									
    									</div>	
    									
    									<div class="col-12 col-12-mobilep">
    										
    										<h4><p><b>Historial de la evidencia <?php echo $ret['nom_ev'];echo $ret['num_ev'];?></b></p></h4>
    										
 <?php   									
      if($count!=0) {
        
        echo "
    								<div class='table-wrapper'>
    									<table >
    									<thead>
    										<tr>
                                                <th>Estado</th>
    											<th>Usuario</th> 
    											<th>Programa</th>
                                                <th>Acción Programa</th>
                                                <th>HASH</th>
                                                <th>Observaciones</th>
                                                <th>Fecha</th>
    										</tr>
    									</thead>
    									<tbody>
                            
    	";
        
        
    
    $contador=0;
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        
        $id_registro= $line['id_evidencia_registro'];
        $estado= $line['estado'];
        $nombre = $line['apodo'];
        $programa= $line['programa'];
        $accion_programa= $line['accion_programa'];
        $hash= $line['hash'];
        $observaciones= $line['observaciones'];
        $fecha= $line['fecha_alta_estado'];
        
        
        echo "<tr>
    					
                        	<form>";
        $id_registro=base64_encode($id_registro);
        echo "<td><a href='detalle_registro.php?id_registro=$id_registro'>$estado</a></td>";
        echo "<td>$nombre</td>"; 
        echo "<td>$programa</td>";
        echo "<td>$accion_programa</td>";
        echo "<td>$hash</td>";
        echo "<td>$observaciones</td>";
        echo "<td>$fecha</td>";
        
        
        echo "</form>";
        echo "</tr>";
    } //while
    
    echo "						</tbody>
    					</table>
    			</section>";
    
    }  // if
    
    else {
        echo "<br><b>";
        echo "NO EXISTE REGISTRO";
        echo "</b>";
    }
    ?>  									
    									
    									
    									</div>
    									
    									
    									
    									

  									</div> <!-- row -->
    									
    								</section>
    							
    								
    							</form>
    						</div> <!-- col12 -->
    						
    						
    						
    						
    			</div> <!-- row -->
    			
    			
				
			</section>
			
			<section id="main" class="container">

					<div class="box">
						<h4><p><b>Agregar nuevo estado<b></b></p></h4>
						<form  method="POST" action="crearestado.php" id="myform">
							<div class="row gtr-50 gtr-uniform">

								<div class="col-4 col-12-mobilep">
									    <select name="usuario" id="usuario">
    <?php
    $resultado = mysqli_query($link, "select apodo FROM usuario where id_usuario=$myid_u");
    $ret = mysqli_fetch_array($resultado);
    echo "<option value=$myid_u selected>$ret[apodo]</option>"; 
    $contador=0;
    $entro=0;
    $resultado = mysqli_query($link, "select id_usuario, apodo FROM usuario");
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        foreach ($line as $col_value) {
            if($contador==0) {
                if($col_value==$myid_u) {
                    $entro=1;
                    $contador++;
                    
                }
                else {
                    echo "<option value=$col_value>";
                    $contador++;
                   
                }
            }
            else {
                if($entro==1) {
                    $entro=0;
                    $contador=0;
                }
                else {
                    echo "$col_value</option>";
                    $contador=0;
                }
            }
            
            
        }
    }
    ?>
        </select>
        
								</div>
								
								<div class="col-4 col-12-mobilep">
<select name="estado" id="estado" required onchange="cambiarhash(this.value);">
    <?php
    $contador=0;
    $resultado = mysqli_query($link, "select id_estado_evidencia, nombre FROM estado_evidencia WHERE id_estado_evidencia!=1");
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
								
								<div class="col-4 col-12-mobilep">
<select id="programa" name="programa" onchange="accion(this.value);">
    	  <option value=null>Selecciona un programa</option>
    	  <?php
            $contador=0;
            $resultado = mysqli_query($link, "select id_programa, nombre FROM programa");
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
								
								<div class="col-4 col-12-mobilep">
 <select id="accion_programa" name="accion_programa">
    	  <option value=null>Accion del programa</option>
</select>								
								</div>
								
								 <!--  
    XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
    para ocultar el hash ya que no siempre es necesario solo cuando se selecciona una accion determinada
    XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
    <div class="auto" id="detalles" style="display: none">
    
     <div class="auto" id="hash">
     
     
     </div>
    
    -->
    							<div class="col-3 col-12-mobilep">
								
									<input type="datetime-local" name="fecha" id ="fecha" placeholder="fecha" required>
								</div>
								
								<div class="col-3 col-12-mobilep">
								
									<input type="text" name="num_hash" id ="num_hash" placeholder="Hash">
								</div>
								
								
								<div class="col-2 col-12-mobilep">
<select id="tipo_hash" name="tipo_hash" >
     <?php
            $contador=0;
            $resultado = mysqli_query($link, "select id_tipo_hash,tipo_hash FROM tipo_hash");
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
								<div class="col-12">
    							<textarea name="observaciones" id="observaciones" placeholder="Observaciones" rows="2"></textarea>
    						</div>	
								
								<div class="col-12">
									<ul class="actions special">
										<li><input type="submit" value="Agregar estado" /></li>
									</ul>
								</div>
						
					</div>
				</form>	
			</div>
		  </section>
				
  		</div> <!-- pag wrapper -->



    



     <?php
            
}
else {
    echo "Error";
}
        ?>




</body>
</html>