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
    com.id_comisaria as id_com, com.nombre_comisaria as nom_com
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
    
    
    // cargo lista de sujetos
    
    $resultado_sujetos = mysqli_query($link, "select distinct s.id_sujeto_activo, s.nombre, s.apellido1, s.apellido2
    FROM sujeto_activo s
    WHERE id_caso=$myid_caso");
    $count_sujetos = mysqli_num_rows($resultado_sujetos);
    
    // cargo lista de intervenciones
    
    $resultado_intervenciones = mysqli_query($link, "select i.id_intervencion,  t.nombre as nom_tipo, t.descripcion as des_tipo, s.nombre, s.apellido1, s.apellido2, i.direccion, i.descripcion
    FROM intervencion i
    INNER JOIN tipo_intervencion t ON t.id_tipo_intervencion=i.id_tipo_intervencion
    INNER JOIN sujeto_activo s ON s.id_sujeto_activo=i.id_sujeto_activo
    INNER JOIN caso c ON c.id_caso=i.id_caso
    WHERE i.id_caso=$myid_caso ORDER BY id_intervencion");
    $count_intervenciones = mysqli_num_rows($resultado_intervenciones);
    
    
    // comienzo carga de lista de evidencias
    
    $resultado_evidencias = mysqli_query($link, "select  e.tiene_subevidencias, id_evidencia, e.nombre, e.numero_evidencia, s.nombre as nom_sub, t.nombre as nom_tipo,  e.n_s, e.capacidad, e.marca, e.modelo from evidencia e
    inner join tipo_evidencia t on t.id_tipo_evidencia=e.id_tipo_evidencia
    inner join subtipo_evidencia s on s.id_subtipo_evidencia=e.id_subtipo_evidencia
    inner join caso c on c.id_caso=e.id_caso
    where c.id_caso='$myid_caso' AND relacionado_con is null order By nombre asc");
    $count_evidencias = mysqli_num_rows($resultado_evidencias);
    ?>
    
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
     <title>Detalle	 Asunto</title>
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
    		</script> 
    
    </head>
    
    <body class="is-preload" onload="respuesta();">
    	<div id="page-wrapper">
    
    	<!-- Main -->
    		<section id="main" class="container">
    			<header>
    				<h2>Operación <?php echo $ret['num_caso'];?>_<?php echo substr($ret['año_caso'], 2);?></h2>
    				<h3><p>Detalle del caso</p></h3>
    			</header>
    			<div align="center">
    				<canvas id="mensaje" width="300" height="30"></canvas>
    			</div>
    			<div class="row">
    						<div class="col-12">
    
    							<!-- Lists -->
    							<form action='modificarasunto.php' method='post'>
    								<section class="box">
    									<h3>Número de caso: <b><?php echo $ret['num_caso'];?>_<?php echo substr($ret['año_caso'], 2);?></b></h3>
    									<input type='hidden' name='numero' id='numero' value=<?php echo $ret['num_caso'] ?>>
    									<input type='hidden' name='año' id='año' value=<?php echo $ret['año_caso'] ?>>
    									<div class="row">
    										<div class="col-6 col-12-mobilep">
    
    											<ul class="alt">
    												<li>Operación: <b><?php echo $ret['nom_caso'];?></b></li>
    												
    												<li>Descripción: <b><?php echo $ret['des_caso'];?></b></li>
    												<li>Tipo delictivo: <b><?php echo $ret['nom_tipo'] ?></b>
    													<input type='hidden' name='nombre' id='nombre' value='<?php echo $ret['nom_caso'];?>'>
    													<input type='hidden' name='descripcion' id='descripcion' value='<?php echo $ret['des_caso'];?>'>
    													<input type='hidden' name='id_tipo' id='id_tipo' value='<?php echo $ret['id_tipo'];?>'>
    													<input type='hidden' name='tipo_caso' id='tipo_casp' value='<?php echo $ret['nom_tipo'];?>'>
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
    										
    											<h4><b>Sujetos activos relacionados</b></h4>
    											<ol>
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
    
    											echo " </ol> ";     
    } else {
    ?>
    										
    											<h4>Sujetos activos relacionados</h4>											
    											<ul>
    												<li>No hay sujetos activos relacionados con esta operación</li>												
    											</ul>  
    										
    										
    																				
    
    <?php    										
    }
    ?>
    											
    											
    											<a href='nuevosujeto.php' class='button special small'>Agregar Sujeto</a>
    											
    											
    											<br><br>
    											
    <!-- LISTA DE INTERVENCIONES -->											
    											
    <?php
    if ($count_intervenciones != 0) {
    
    ?>											
    											<h4><b>Intervenciones del caso</b></h4>
    											<ol>										
    
    <?php
        $contador = 0;
        $entro = 0;
        $entro2 = 0;
        while ($line_intervenciones = mysqli_fetch_array($resultado_intervenciones, MYSQLI_ASSOC)) {
    		echo "<li>";
            $nombre_sujeto = "";
            foreach ($line_intervenciones as $col_value) {
                if($contador==0) {
                    $id_intervencion=base64_encode($col_value);
                    $contador++;
                }
                else {
                    if ($contador == 1) {
                	
                        if ($entro2 == 0) {
                            echo "<a href='detalle_intervencion.php?id_intervencion=$id_intervencion'>".$col_value."</a>"; 
                            echo " - ";
                            $entro2 = 1;
                        } 
                        else {
                            echo $col_value;
                       
                            $entro2 = 0;
                            $contador ++;
                        
                        }
                    }
                    else {
                        if ($contador == 2) {
                            if ($entro < 2) {
                                $nombre_sujeto = $nombre_sujeto . " " . $col_value;
                                $entro ++;
                            }
                            else {
                            
                                echo $nombre_sujeto . " " . $col_value . " - C\\ ";
                           
                                $contador ++;
                                $entro = 0;
                            }
                        }
                        else {
                            if ($contador < 4) {
                            
                                echo $col_value . " [ ";
                            
                                $contador ++;
                            } 
                            else {
                            
                                echo $col_value . " ] ";
                            
                                $contador = 0;
                            }
                        }
                    }
                }
            }
            echo "</li>";
        }
    
    											echo " </ol> ";
    
    } else {
    ?>
    
        										<h4>Intervenciones del caso</h4>											
    											<ul>
    												<li>No hay intervenciones  relacionadas con esta operación</li>												
    											</ul> 
    <?php   
    }									
    ?>									
    
    											
    											
    												<a href="nuevaintervencion.php" class="button special small">Agregar Intervención </a>
    											
    												
    									</div>
    									
    									<!-- Lista de intervenciones -->	
    
    
    										
    									<!-- Lista de evidencias -->	
    									<div class="col-12 col-12-mobilep">
    									<h4><b>Evidencias del caso<b></b></h4>
    
    <?php
    
    								if ($count_evidencias != 0) {
    
    ?>																		
    									
    									
    
    <?php
        echo "<br><div class='table-wrapper'><table><thead><tr><th>Nombre</th><th>Depende de</th><th>Tipo Almacenamiento</th><th>Subtipo</th><th>Numero Serie</th><th>Capacidad</th><th>Marca</th><th>Modelo</th></tr></thead>";
        $contador = 0;
        $contador2 = 0;
        $tiene_sub = 0;
        $entra = 0;
    
        $entra_nombre = 0;
    
    
    
        while ($line_evidencias = mysqli_fetch_array($resultado_evidencias, MYSQLI_ASSOC)) {
            echo "<tr>";
            foreach ($line_evidencias as $col_value) {
                if ($contador == 0) {
                    $entra = 0;
                    $tiene_sub = $col_value;
                    $contador ++;
                } else {
                    if ($contador == 1) {
                        $id = $col_value;
                        $contador ++;
                    } else {
                        if ($contador == 2) {
                            if ($entra_nombre == 0) {
                                echo "<td>";
                                $nombre_original = $col_value;
                                $nombre=base64_encode($nombre_original);
                                $entra_nombre = 1;
                            } else {
                                if ($entra_nombre == 1) {
                                    $numero=base64_encode($col_value);
                                    echo "<a style='color:#000000;' href='detalle_evidencia.php?nombre=$nombre&numero=$numero'>$nombre_original$col_value</a>";
                                    echo "</td>";
                                    $contador ++;
                                    $entra_nombre = 0;
                                }
                            }
                        } else {
                            if($contador==3) {
                                echo "<td></td>";
                                $contador++;
                            }
                            if ($contador <= 8) {
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
                                $entra = 1;
                            }
                        }
                    }
                    if ($tiene_sub == 1 and $entra == 1) {
                        echo "</tr>";
                        $entra = 0;
                        $result = mysqli_query($link, "select e.nombre, e.numero_evidencia, s.nombre as nom_sub, t.nombre as nom_tipo,  e.n_s, e.capacidad, e.marca, e.modelo from evidencia e
                        inner join tipo_evidencia t on t.id_tipo_evidencia=e.id_tipo_evidencia
                        inner join subtipo_evidencia s on s.id_subtipo_evidencia=e.id_subtipo_evidencia
                        inner join caso c on c.id_caso=e.id_caso
                        where c.id_caso='$myid_caso' AND relacionado_con=$id order By id_intervencion asc");
                        $result2 = mysqli_query($link, "select e.nombre as nom, e.numero_evidencia as num from evidencia e where id_evidencia=$id");
                        $ret=mysqli_fetch_array($result2);
                        while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            echo "<tr>";
                            foreach ($line as $col_value) {
                                if ($contador2 == 0) {
                                    if ($entra_nombre == 0) {
                                        echo "<td>";
                                        $nombre_original = $col_value;
                                        $nombre=base64_encode($nombre_original);
                                        $entra_nombre = 1;
                                    } else {
                                        if ($entra_nombre == 1) {
                                            $numero=base64_encode($col_value);
                                            echo "<a style='color:red;' href='detalle_evidencia.php?nombre=$nombre&numero=$numero'>$nombre_original$col_value</a>";
                                            echo "</td>";
                                            $contador2 ++;
                                            $entra_nombre = 0;
                                        }
                                    }
                                } else {
                                    if($contador2==1) {
                                        echo "<td>";
                                        echo $ret['nom'].$ret['num'];
                                        echo "</td>";
                                        $contador2 ++;
                                    }
                                    if ($contador2 < 7) {
                                        echo "<td align='center'>";
                                        echo $col_value;
                                        echo "</td>";
                                        $contador2 ++;
                                    } 
                                    else {
                                        echo "<td align='center'>";
                                        echo $col_value;
                                        echo "</td>";
                                        $contador2 = 0;
                                    }
                                }
                            }
                            echo "</tr>";
                        }
                    }
                }
            }
    
        }
        echo "</table></div>";
    
    	echo "<a href='listado_intervenciones.php' class='button special small'>Agregar Evidencia </a> ";    
    	echo "<a href='busqueda_evidencia.php' class='button special icon solid fa-search small'>Buscar</a>";
        
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
    	
    									
    									
    								<!--  Botones -->
    									<div class="col-12">
    										<ul class="actions special">										  
    											<li><input type='submit' value='Modificar' /></li>
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
                                                            echo "<li><input type='button' onclick='pregunta();' value='Eliminar'><br></li>";
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
    											<li><input type="button" onclick="location.href='inicio.php';" value="Volver"><br></li>
    										  							
    										</ul>
    									</div>								
    									
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
