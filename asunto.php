<?php
session_start();

if(isset($_SESSION['id_u'])) {
    
  
    
    
    $link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    if(isset($_GET['id_caso'])) {
        $myid_caso=base64_decode(mysqli_real_escape_string($link,$_GET['id_caso']));
        $_SESSION['id_caso']=$myid_caso;
    }
    else {
        if(isset($_SESSION['id_caso'])) {
            $myid_caso=$_SESSION['id_caso'];
        }
    }
    $respuesta=$_SESSION['respuesta'];
    $_SESSION['mod']=0;
    
    $resultado = mysqli_query($link, "SELECT c.numero as num_caso, c.año as año_caso, c.nombre as nom_caso, c.descripcion as des_caso, t.id_tipo_caso as id_tipo,
    t.nombre as nom_tipo, g.id_grupo_investigacion as id_grup, g.nombre_grupo as grup , ca.id_ca as id_ca,ca.nombre_ca as nom_ca, p.id_provincia as id_pro, p.nombre_provincia as nom_pro,
    com.id_comisaria as id_com, com.nombre_comisaria as nom_com, date_format(fecha_alta_caso, '%d/%m/%Y') as fecha, date_format(fecha_alta_caso, '%Y-%m-%d') as fecha_original, c.id_estado_caso
    FROM caso c
    INNER JOIN tipo_caso t ON c.id_tipo_caso=t.id_tipo_caso
    INNER JOIN grupo_investigacion g ON c.id_grupo_investigacion=g.id_grupo_investigacion
    INNER JOIN comisaria com on g.id_comisaria=com.id_comisaria
    INNER JOIN provincia p on com.id_provincia=p.id_provincia
    INNER JOIN ca ca on com.id_ca=ca.id_ca
    WHERE c.id_caso=$myid_caso");
    $ret = mysqli_fetch_array($resultado);
    $resultado2 = mysqli_query($link, "SELECT c.id_diligencias as id_dil, d.numero as num_d, d.año as año_d, j.id_juzgado as id_juz, j.nombre as nom_juz, j.numero as num_juz,j.jurisdiccion as jur FROM diligencias d
    INNER JOIN caso c ON d.id_diligencias=c.id_diligencias
    INNER JOIN juzgado j ON d.id_juzgado=j.id_juzgado
    WHERE c.id_caso=$myid_caso");
    $count = mysqli_num_rows($resultado2);
    if ($count != 0) {
        $ret2 = mysqli_fetch_array($resultado2);
    }
    $resultado_disco=mysqli_query($link, "SELECT distinct d.nombre as nom from disco_almacenado d
    inner join evidencia e on e.id_disco_almacenado=d.id_disco_almacenado
    where e.id_caso=$myid_caso");
    $count_disco = mysqli_num_rows($resultado_disco);

    
    
    // cargo lista de sujetos
    
    $resultado_sujetos = mysqli_query($link, "select distinct s.id_sujeto_activo, s.nombre, s.apellido1, s.apellido2
    FROM sujeto_activo s
    WHERE id_caso=$myid_caso");
    $count_sujetos = mysqli_num_rows($resultado_sujetos);
    
    // cargo lista de intervenciones
    
    $resultado_intervenciones = mysqli_query($link, "select i.id_intervencion as id_int,  t.nombre as nom_tipo, t.descripcion as des_tipo, 
    s.nombre as nom, s.apellido1 as ape1, s.apellido2 as ape2, i.direccion as dir, i.descripcion as des, numero_intervencion as num
    FROM intervencion i
    INNER JOIN tipo_intervencion t ON t.id_tipo_intervencion=i.id_tipo_intervencion
    INNER JOIN sujeto_activo s ON s.id_sujeto_activo=i.id_sujeto_activo
    INNER JOIN caso c ON c.id_caso=i.id_caso
    WHERE i.id_caso=$myid_caso ORDER BY id_intervencion");
    $count_intervenciones = mysqli_num_rows($resultado_intervenciones);
    
    
    // comienzo carga de lista de evidencias
    
    $resultado_evidencias = mysqli_query($link, "select  e.tiene_subevidencias, e.id_evidencia, e.nombre, e.numero_evidencia, s.nombre as nom_sub,  e.n_s, e.capacidad, e.marca, e.modelo, e.alias
    from evidencia e
    inner join tipo_evidencia t on t.id_tipo_evidencia=e.id_tipo_evidencia
    inner join subtipo_evidencia s on s.id_subtipo_evidencia=e.id_subtipo_evidencia
    inner join caso c on c.id_caso=e.id_caso
    inner join evidencia_registro er ON er.id_evidencia=e.id_evidencia
    where c.id_caso=$myid_caso AND relacionado_con is null 
    GROUP BY e.id_evidencia	
    order By nombre asc, numero_evidencia");
    $count_evidencias = mysqli_num_rows($resultado_evidencias);
    
    $resultado_evidencias_total=mysqli_query($link, "Select * from evidencia where id_caso=$myid_caso");
    $count_total=mysqli_num_rows($resultado_evidencias_total);
    
    $resultado_evidencias_total=mysqli_query($link, "Select * from evidencia where id_caso=$myid_caso");
    $count_total=mysqli_num_rows($resultado_evidencias_total);
    $resultado_evidencias_terminadas=mysqli_query($link, "select distinct e.id_evidencia from evidencia e
    inner join evidencia_registro er on er.id_evidencia=e.id_evidencia
    where id_caso=$myid_caso and (er.id_estado_evidencia=3 or er.id_estado_evidencia=7 or er.id_estado_evidencia=8)");
    $count_evidencias_terminadas=mysqli_num_rows($resultado_evidencias_terminadas);
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Detalles del Caso</title>
    	<meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    	<link rel="stylesheet" href="assets/css/main.css" />
		
    		
    			
    	<!-- Alonso -->
    		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    		<script src="js/jquery-3.4.1.js"></script>
    		
    		<script>
    		function respuesta() {
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
    				ctx.fillText("Caso modificado",150,20);
    				<?php $_SESSION['respuesta']=0; ?>
    				setTimeout(borrar,5000);
    			}
    			else {
    				if(respuesta==2) {
    					ctx.strokeStyle = "#3DBA26";
    					ctx.strokeRect(1, 1, 299, 29);
    					ctx.fillStyle = "#3DBA26";
    					ctx.textAlign = "center";
    					ctx.fillText("Evidencia agregada",150,20);
    					<?php $_SESSION['respuesta']=0; ?>
    					setTimeout(borrar,5000);
    				}
    				else {
    					if(respuesta==3) {
    						ctx.strokeStyle = "#3DBA26";
    						ctx.strokeRect(1, 1, 299, 29);
    						ctx.fillStyle = "#3DBA26";
    						ctx.textAlign = "center";
    						ctx.fillText("Sujeto agregado",150,20);
    						<?php $_SESSION['respuesta']=0; ?>
    						setTimeout(borrar,5000);					}
    					else {
    						if(respuesta==4) {
    							ctx.strokeStyle = "#3DBA26";
    							ctx.strokeRect(1, 1, 299, 29);
    							ctx.fillStyle = "#3DBA26";
    							ctx.textAlign = "center";
    							ctx.fillText("Intervención añadida",150,20);
    							<?php $_SESSION['respuesta']=0; ?>
    							setTimeout(borrar,5000);
    						}
    						else {
    							if(respuesta==5) {
    								ctx.strokeStyle = "#3DBA26";
    								ctx.strokeRect(1, 1, 299, 29);
    								ctx.fillStyle = "#3DBA26";
    								ctx.textAlign = "center";
    								ctx.fillText("Sujeto eliminado",150,20);
    								<?php $_SESSION['respuesta']=0; ?>
    								setTimeout(borrar,5000);
    							}
    							else {
    								if(respuesta==6) {
    									ctx.strokeStyle = "#3DBA26";
    									ctx.strokeRect(1, 1, 299, 29);
    									ctx.fillStyle = "#3DBA26";
    									ctx.textAlign = "center";
    									ctx.fillText("Intervención eliminada",150,20);
    									<?php $_SESSION['respuesta']=0; ?>
    									setTimeout(borrar,5000);
    								}
    								else {
    									if(respuesta==7) {
    										ctx.strokeStyle = "#3DBA26";
    										ctx.strokeRect(1, 1, 299, 29);
    										ctx.fillStyle = "#3DBA26";
    										ctx.textAlign = "center";
    										ctx.fillText("Evidencia eliminada",150,20);
    										<?php $_SESSION['respuesta']=0; ?>
    										setTimeout(borrar,5000);
    									}
    									else {
    										if(respuesta==8) {
    											ctx.strokeStyle = "#3DBA26";
    											ctx.strokeRect(1, 1, 299, 29);
    											ctx.fillStyle = "#3DBA26";
    											ctx.textAlign = "center";
    											ctx.fillText("Caso creado",150,20);
    											<?php $_SESSION['respuesta']=0; ?>
    											setTimeout(borrar,5000);
    										}
    										else {
        										if(respuesta==9) {
        											ctx.strokeStyle = "#FF0000";
        											ctx.strokeRect(1, 1, 299, 29);
        											ctx.fillStyle = "#FF0000";
        											ctx.textAlign = "center";
        											ctx.fillText("Ya existe la intervención",150,20);
        											<?php $_SESSION['respuesta']=0; ?>
        											setTimeout(borrar,5000);
        										}
        									}
    									}    									
    								}
    							}	
    						}
    					}
    				}
    			}
    		}
    		</script>
    		<script type="text/javascript">
    		function preguntaEvidencia(opSelect){ 
        		var category=opSelect;
        		var url="eliminarevidencia.php";
    			if (confirm('¿Estas seguro de eliminar la evidencia?')){ 
    				$.ajax({
    					url:url,
    		        	type:"POST",
    		        	data:{category:category}

    		      	}).done(function(data){
						
    		    		  location.href = "asunto.php";   
    		      	});  
       			}
     			else {
     				
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
    		function pregunta(){ 
    			if (confirm('¿Estas seguro de eliminar el caso?')){ 
    				$.ajax({
    					type: "POST",
    					url: "eliminarcaso.php",
    					contentType: false,
    					processData: false,
    					success: function(respuesta) {
    							location.href="inicio.php";
    					}
    				});
       			}
     			else {
     				location.href = "asunto.php";
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
	<!-- Header -->
    <div id="cabecera">
    
    </div>

    	<!-- Main -->
    		<section id="main" class="container">
    			<header>
    				<h2>Caso <?php echo $ret['num_caso'];?>_<?php echo substr($ret['año_caso'], 2);?></h2>
    				<h3><p>Operación <?php echo $ret['nom_caso'];?></p></h3>
    			</header>
    			    			
    			<div class="row">
    						<div class="col-12">
    							
    							<!-- Lists -->
    							<form action='modificarasunto.php' method='post'>
    								<section class="box">
    									<div align="center">
		    								<canvas id="mensaje" width="300" height="30"></canvas>
		    							</div>
    									<div><h2><em><b>Detalles del Caso</b></em></h3></div>
    									
    									<input type='hidden' name='numero' id='numero' value=<?php echo $ret['num_caso'] ?>>
    									<input type='hidden' name='año' id='año' value=<?php echo $ret['año_caso'] ?>>
    									<div class="row">
    										<div class="col-6 col-12-mobilep">
    											<h4></b></h3>
    											<ul class="alt">
    												<li>Número de caso: <b><?php echo $ret['num_caso'];?>_<?php echo substr($ret['año_caso'], 2);?></b></li>
    												
    												<li>Operación: <b><?php echo $ret['nom_caso'];?></b></li>
    												
    												<li>Descripción: <b><?php echo $ret['des_caso'];?></b></li>
    												<li>Tipo delictivo: <b><?php echo $ret['nom_tipo'] ?></b>
    													<input type='hidden' name='nombre' id='nombre' value='<?php echo $ret['nom_caso'];?>'>
    													<input type='hidden' name='descripcion' id='descripcion' value='<?php echo $ret['des_caso'];?>'>
    													<input type='hidden' name='id_tipo' id='id_tipo' value='<?php echo $ret['id_tipo'];?>'>
    													<input type='hidden' name='tipo_caso' id='tipo_caso' value='<?php echo $ret['nom_tipo'];?>'>
    												</li>
    												<li>
    													Grupo Investigación: <b><?php echo $ret['grup'] ?></b>
    													<input type='hidden' name='id_ca' id='id_ca' value='<?php echo $ret['id_ca'];?>'>
    													<input type='hidden' name='nom_ca' id='nom_ca' value='<?php echo $ret['nom_ca'];?>'>
    													<input type='hidden' name='id_pro' id='id_pro' value='<?php echo $ret['id_pro'];?>'>
    													<input type='hidden' name='nom_pro' id='nom_pro' value='<?php echo $ret['nom_pro'];?>'>
    													<input type='hidden' name='id_com' id='id_com' value='<?php echo $ret['id_com'];?>'>
    													<input type='hidden' name='nom_com' id='nom_com' value='<?php echo $ret['nom_com'];?>'>																		
    													<input type='hidden' name='id_grupo' id='id_grupo' value='<?php echo $ret['id_grup'];?>'>
    													<input type='hidden' name='grupo' id='grupo' value='<?php echo $ret['grup'];?>'>
    													<input type='hidden' name='estado' id='estado' value='<?php echo $ret['id_estado_caso'];?>'>
    												</li>
    												<li>
    													Fecha: <b><?php echo $ret['fecha'] ?></b>
    													<input type='hidden' name='fecha' id='fecha' value='<?php echo $ret['fecha_original'];?>'>
    												</li>
    												<li>
    													Disco: <b><?php 
    													$i=0;
    													if($count_disco!=0) {
    													   while ($line = mysqli_fetch_array($resultado_disco, MYSQLI_ASSOC)) {
    													       $disco=$line['nom'];
    													       echo $disco;   
    													       if($count_disco>1 and $i<$count_disco-1) {        
    													               echo ", ";
    													               $i++;					           
    													       }
    													    }
    													       
    													}?>
    													    </b>
    													<input type='hidden' name='fecha' id='fecha' value='<?php echo $ret['fecha_original'];?>'>
    												</li>
    											</ul>	
    											
    												
    <?php													if ($count != 0) {
    														  echo "<h4><b>Pocedimiento Judicial</b></h4>
    														<ul>";	
    															
    															echo "<li>Número de diligencias: <i><b>";
    ?>
        														<?php echo $ret2['num_d'];?>/<?php echo $ret2['año_d'];?>
    <?php    														
        														echo "<input type='hidden' name='id_diligencias' id='id_diligencias' value='$ret2[id_dil]' >";
    															echo "</i></li> ";
    															
    															echo "</b><li> Jurisdicción: <i><b>";
    															
    															echo $ret2['jur'];
    															
    															echo "<input type='hidden' name='jurisdiccion' id='jurisdiccion' value='$ret2[jur]'>";
    															echo "</b></i></li> ";
    														
    
    
    															echo "</b><li> Juzgado: <i><b>";
        														
    															echo $ret2['nom_juz']." ".$ret2['num_juz'];
    															
    															echo "<input type='hidden' name='diligencias' id='diligencias' value='$ret2[num_d]'>";
    															echo "<input type='hidden' name='año_diligencias' id='año_diligencias' value='$ret2[año_d]'>";
    															echo "<input type='hidden' name='juzgado' id='juzgado' value='$ret2[nom_juz]'>";
    															echo "<input type='hidden' name='num_juzgado' id='num_juzgado' value='$ret2[num_juz]'>";
    															echo "<input type='hidden' name='id_juzgado' id='id_juzgado' value='$ret2[id_juz]'>";
    															echo "</b></i></li> ";
    
    														}
    ?>												
    												
    											</ul>					
    											
    										</div> <!--  col6 -->
    										
    										<!-- lista de sujetos activos -->
    										<div class="col-6 col-12-mobilep">
    <?php 
    													
    if ($count_sujetos != 0) {
    
    ?>	
    										
    											<h3><b>Sujetos activos relacionados</b></h4>
    											<ul>
    <?php
        $contador = 0;
        $entro = 0;
        while ($line_sujetos = mysqli_fetch_array($resultado_sujetos, MYSQLI_ASSOC)) {
            foreach ($line_sujetos as $col_value) {
                if ($contador == 0) {
                    echo "<li>";
                    echo "<a href='detalle_sujeto.php?id_su=";
                    echo base64_encode($col_value)."'>";
                    $contador ++;
                } else {
                    if ($entro < 2) {
                        echo $col_value;
                        echo "  ";
                        
                        $entro ++;
                    } else {
                        
                        echo $col_value . " ";
                        echo "</a>";
                        $entro = 0;
                        $contador = 0;
                    }
                }
            }
            echo "</li>";
        }
    
    											echo " </ul> ";     
    } else {
    ?>
    										
    											<h3>Sujetos activos relacionados</h4>											
    											<ul>
    												<li>No hay sujetos activos relacionados con esta operación</li>												
    											</ul>  
    										
    										
    																				
    
    <?php    										
    }
    ?>
    											
    											
    											<a href='nuevosujeto.php' class='button special small'>Agregar Sujeto</a>
    											

    											
    												
    									</div>
    									 
    									<!-- Lista de intervenciones -->	
    
    									<div class="col-12 col-12-mobilep">
    										
    										 <?php
    if ($count_intervenciones != 0) {
    
    ?>											
    											<h3><b>Intervenciones del caso</b></h4>
    											<ul>										
    
    <?php
        $contador = 0;
        $entro = 0;
        $entro2 = 0;
        
        while ($line_intervenciones = mysqli_fetch_array($resultado_intervenciones, MYSQLI_ASSOC)) {
            $id_int=$line_intervenciones['id_int'];
            $nom_tipo=$line_intervenciones['nom_tipo'];
            $des_tipo=$line_intervenciones['des_tipo'];
            $nom=$line_intervenciones['nom'];
            $ape1=$line_intervenciones['ape1'];
            $ape2=$line_intervenciones['ape2'];
            $dir=$line_intervenciones['dir'];
            $desc=$line_intervenciones['des'];
            $num=$line_intervenciones['num'];
    		echo "<li>";
            $id_intervencion=base64_encode($id_int);
            echo "<a href='detalle_intervencion.php?id_intervencion=$id_intervencion'>".$num."</a>"; 
            echo " - ".$nom_tipo;
            echo " - ";
            echo $nom." ".$ape1." ".$ape2;
            echo " - ";
            echo $dir;
            if(!empty($desc)) {
               echo " [".$desc."]";
            }
           
            
            echo "</li>";
        }
    
    											echo " </ul>   ";
    
    } else {
    ?>
    
        										<h4>Intervenciones del caso</h4>											
    											<ul>
    												<li>No hay intervenciones  relacionadas con esta operación</li>												
    											</ul> 
    <?php   
    }									
    ?>									
    											
    											
    											<ul class="actions small">
													<li><a href="nuevaintervencion.php" class="button special small">Agregar Intervención </a></li>
    											</ul>
    									
    									</div>
    									
    									<!--  Botones -->
    									<div class="col-12">
    										<ul class="actions special">										  
    											<li><br><input type='submit' value='Modificar' /></li>
    											<?php
    											$sql_borrar_evidencia="Select * FROM evidencia where id_caso=$myid_caso";
    											$resultado_borrar_evidencia=mysqli_query($link, $sql_borrar_evidencia);
    											$count_borrar_evidencia=mysqli_num_rows($resultado_borrar_evidencia);
                                                if ($count_borrar_evidencia==0) {
                                                    $sql_borrar_intervencion="Select * FROM intervencion where id_caso=$myid_caso";
                                                    $resultado_borrar_intervencion=mysqli_query($link, $sql_borrar_intervencion);
                                                    $count_borrar_intervencion=mysqli_num_rows($resultado_borrar_intervencion);
                                                    if($count_borrar_intervencion==0) {
                                                        $sql_borrar_sujeto="Select * FROM sujeto_activo where id_caso=$myid_caso";
                                                        $resultado_borrar_sujeto=mysqli_query($link, $sql_borrar_sujeto);
                                                        $count_borrar_sujeto=mysqli_num_rows($resultado_borrar_sujeto);
                                                        if($count_borrar_sujeto==0) {
                                                            echo "<li><br><input type='button' onclick='pregunta();' value='Eliminar'><br></li>";
                                                        }
                                                        else {
                                                            //no se muetra el boton eliminar
                                                        }
                                                    }
                                                    else {
                                                        //no se muetra el boton eliminar
                                                    }
                                                }
                                                else {
                                                    //no se muetra el boton eliminar
                                                }
                                                ?>
    											<li><br><input type="button" onclick="location.href='inicio.php';" value="Volver"><br></li>
    										  							
    										</ul>
    										

    									</div>	
    									
    										
    									<!-- Lista de evidencias -->	
    									<div class="col-12 col-12-mobilep">
    									<h3><b>Evidencias del caso</b> <a href="listado_intervenciones.php"> <img src="images/add.png"> </a>(   <?php echo $count_total;?>   Evidencias, <?php echo $count_evidencias_terminadas;?> Finalizadas, <?php echo $count_total-$count_evidencias_terminadas;?> Pendientes)</h3> 
    									
    									
    									
    
    <?php
									
    								if ($count_evidencias != 0) {
    

    ?>								  

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


<?php

        



		$contador = 0;
        $tiene_sub = 0;
        $entra = 0;
    
    
    
    
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

?>
    <div class="col-12">
    	<ul class="actions special small">
    
			<li><a href="listado_intervenciones.php" class="button special small">Agregar</a></li>
			<li><a href="busqueda_evidencia.php" class="button special small">Buscar</a></li>
			<li><a href="configuracion_hoja.php" class="button special small">Hoja de evidencias</a></li>
		</ul>
	</div>	
    	
<?php        
    } else {
    
        ?>
        <ul>
    		<li>No hay evidencias relacionadas con esta operación</li>										
    	</ul>  
    	<a href='listado_intervenciones.php?mod=2'>Agregar Evidencia</a>
    	<br><br>
        
        
    <?php 
    }										
    
    ?>										
    									</div>	<!-- evidencias -->
    	
    									
    									

    									
    								</section>
    							</form>	
    						</div> <!-- col12 -->
    					</div>  <!-- row -->			
    		</section>		
       </div>
       
       
       
    </body>
    
    
    
    </html>

    
    
    <?php
    
    mysqli_close($link);
}
else {
    echo "Error";
}
?>
		</div>								
    									
    								</section>
    							</form>	
							</div> <!-- row -->
						</div> <!--coll12 -->
    				</div>  	
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

