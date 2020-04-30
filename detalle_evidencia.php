<?php

session_start();

if(isset($_SESSION['id_u'])) {

    $link = mysqli_connect("localhost", "root", ".google.", "safe");
    
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    
    $myid_caso=$_SESSION['id_caso'];
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
    
    /*incluyo estas tres lineas*/
    $resultado_evidencias=mysqli_query($link, "SELECT tiene_subevidencias from evidencia where id_evidencia=$myid_ev");
    $ret_evidencias=mysqli_fetch_array($resultado_evidencias);
    $numero_evidencias= $ret_evidencias['tiene_subevidencias'];
    
    
    ?>
    <!DOCTYPE html>
    <html lang="es-ES">
    <head>
      <link rel="stylesheet" type="text/css" href="Estilo.css">
      <meta charset="utf-8">
      <script src="//code.jquery.com/jquery-latest.js"></script>
      <script src="miscript.js"></script>
      <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
      <script src="js/jquery-3.4.1.js"></script>
      
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
    <body class="fondo" onload="respuesta();">
    <div align="center"><br>
    <h1>SAFE</h1>
    
    <div align="center">
    <canvas id="mensaje" width="300" height="30"></canvas>
    </div>
    
    <?php
        echo "<form action='modificarevidencia.php' method='post'><br>";
    
    echo "Nombre Evidencia:";
    echo "<input type='text'  name='nombre' id='nombre' size=3 class='estilo' value=$ret[nom_ev] readonly>";
    
    echo "<input type='text'  name='numero' id='numero' size=2 class='estilo' value='$ret[num_ev]' readonly>";
    
    echo "Número Serie:";
    echo "<input type='text'  name='n_s' size=15 class='estilo' value='$ret[n_s]' readonly>";
    
    echo "Capacidad:";
    echo "<input type='text'  name='capacidad' size=2 class='estilo' value='$ret[capacidad]' readonly>";
    
    if($ret['capacidad']!=null) {
    echo "<input type='text'  name='tipo_capacidad' size=2 class='estilo' value='$ret[tipo_capacidad]' readonly>";
    }
    else {
        echo "<input type='hidden'  name='tipo_capacidad' value='$ret[tipo_capacidad]'>";
    }
    echo "<input type='hidden'  name='id_tipo_capacidad' value='$ret[id_tipo_capacidad]'>";
    
    echo "<br><br>";
    echo "Marca:";
    echo "<input type='text'  name='marca' size=6 class='estilo' value='$ret[marca]' readonly>";
    
    echo "Modelo:";
    echo "<input type='text'  name='modelo' size=10 class='estilo' value='$ret[modelo]' readonly>";
    
    echo "Observaciones:";
    echo "<input type='text'  name='observaciones' size=10 class='estilo' value='$ret[obs_ev]' readonly>";
    
    echo "<br><br>";
    
    echo "Tipo Evidencia";
    echo "<input type='text' name='tipo' id='tipo' class='estilo' value='$ret[nom_tip]' readonly>";
    echo "<input type='text' name='subtipo' id='subtipo' class='estilo' value='$ret[nom_sub]' readonly>";
    echo "<input type='hidden' name='id_tipo' id='id_tipo' class='estilo' value='$ret[id_tip]'>";
    echo "<input type='hidden' name='id_subtipo' id='id_subtipo' class='estilo' value='$ret[id_sub]'>";
    
    echo "Disco";
    echo "<input type='text' name='disco' id='disco' class='estilo' value='$ret[nom_disc]' readonly>";
    echo "<input type='hidden' name='id_disco' id='id_disco' class='estilo' value='$ret[id_disc]' readonly>";
    
    echo "Intervencion";
    echo "<input type='text' name='intervencion' id='intervencion' class='estilo' value='$ret[int_dir]' readonly>";
    echo "<input type='hidden' name='id_intervencion' id='id_intervencion' class='estilo' value='$ret[id_int]'>";
    echo "<input type='hidden' name='numero_intervencion' id='numero_intervencion' class='estilo' value='$ret[nom_i]'>";
    
    if(!empty($ret['al'])) {
        echo "Alias";
        echo "<input type='text' name='alias' id='alias' class='estilo' value='$ret[al]' readonly>";
    }
    else {
        echo "<input type='hidden' name='alias' id='alias' value=''>";
    }
    echo "<br><br>";
    if(!empty($ret['pin'])) {
        echo "PIN";
        echo "<input type='text' name='pin' id='pin' class='estilo' value='$ret[pin]' readonly>";
    }
    else {
        echo "<input type='hidden' name='pin' id='pin' value=''>";
    }
    echo "<br><br>";
    if(!empty($ret['pat'])) {
        echo "Patron:<br>";
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
    
    
    echo "<br><br>";
    echo "<input type='submit' value='Modificar' class='estilo'>";
    echo "</form>";
    ?>
    
    <input type='hidden' name="numero_evidencias" id="numero_evidencias" value="<?php echo $numero_evidencias;?>">
    <input type="button" onclick="pregunta(<?php echo $numero_evidencias;?>)" value="Eliminar evidencias" class="estilo">
    
    <br>
    <input type="button" onclick="location.href='asunto.php';" value="Volver" class="estilo"><br>
    
    
    
    <?php
    
    //compruebo si tiene subevidencias y en caso afirmativo cargo la tabla
    $sql= "select e.nombre, e.numero_evidencia, s.nombre as nom_sub, t.nombre as nom_tipo,  e.n_s, e.capacidad, e.marca, e.modelo, e.observaciones from evidencia e
                        inner join tipo_evidencia t on t.id_tipo_evidencia=e.id_tipo_evidencia
                        inner join subtipo_evidencia s on s.id_subtipo_evidencia=e.id_subtipo_evidencia
                        inner join caso c on c.id_caso=e.id_caso
                        where c.id_caso='$myid_caso' AND relacionado_con=$myid_ev order By id_intervencion asc";
    $resultado_subevidencia=mysqli_query($link, $sql);
    $count=mysqli_num_rows($resultado_subevidencia);
    
    if($count!=0) {
        echo "<br><b>Evidencias que dependen</b><br>";
        
        echo "<br><table border='1' style='border-collapse: collapse; margin:0 auto; background-color: #def;border-style: none;'><tr><th>Nombre</th><th>Tipo Almacenamiento</th><th>Subtipo</th><th>Numero Serie</th><th>Capacidad</th><th>Marca</th><th>Modelo</th><th>Observaciones</th></tr>";
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
                            echo "<a style='color:#000000;' href='detalle_evidencia.php?nombre=$nombre&numero=$numero'>$nombre_original$col_value</a>";
                            echo "</td>";
                            $contador ++;
                        }
                        else {
                                if ($contador < 8) {
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
        echo "</table>";
    }
    else {
        //compruebo si depende de alguna evidencia y en caso afirmativo cargo la tabla
        $sql= "select e.relacionado_con as rel from evidencia e
                WHERE e.id_evidencia=$myid_ev ORDER By id_intervencion asc";
                $resultado_evidencia_padre=mysqli_query($link, $sql);
                $ret2=mysqli_fetch_array($resultado_evidencia_padre);
                if($ret2['rel']!=null) {
                    $sql= "select e.nombre, e.numero_evidencia, s.nombre as nom_sub, t.nombre as nom_tipo,  e.n_s, e.capacidad, e.marca, e.modelo, e.observaciones from evidencia e
                    inner join tipo_evidencia t on t.id_tipo_evidencia=e.id_tipo_evidencia
                    inner join subtipo_evidencia s on s.id_subtipo_evidencia=e.id_subtipo_evidencia
                    inner join caso c on c.id_caso=e.id_caso
                    where c.id_caso='$myid_caso' AND id_evidencia=$ret2[rel] order By id_intervencion asc";
                    $resultado_padre=mysqli_query($link, $sql);
                    $count=mysqli_num_rows($resultado_padre);
                    if($count!=0) {
                        echo "<br><b>Evidencia de la que depende</b><br>";
                   
                        echo "<br><table border='1' style='border-collapse: collapse; margin:0 auto; background-color: #def;border-style: none;'><tr><th>Nombre</th><th>Tipo Almacenamiento</th><th>Subtipo</th><th>Numero Serie</th><th>Capacidad</th><th>Marca</th><th>Modelo</th><th>Observaciones</th></tr>";
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
                                    if ($contador < 8) {
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
                    echo "</table>";
                    }
                }
    }
    
    //creo la lista de registro
    $sql = "select er.id_evidencia_registro,u.apodo,es.nombre as estado, p.nombre as programa, ap.nombre as accion_programa, h.hash, er.observaciones, er.fecha_alta_estado from evidencia_registro er
    inner join usuario u on er.id_usuario=u.id_usuario
    inner join estado_evidencia es on er.id_estado_evidencia=es.id_estado_evidencia
    left join programa p on er.id_programa=p.id_programa
    left join accion_programa ap on er.id_accion_programa=ap.id_accion_programa
    left join hash h on er.id_hash=h.id_hash
    where er.id_evidencia=$ret[id_ev] ORDER BY er.fecha_alta_estado ASC";
    $resultado=mysqli_query($link, $sql);
    $count=mysqli_num_rows($resultado);
    
    
    
    echo "<br><b>Historial Evidencia</b><br><br>";
    
    if($count!=0) {
        
        echo "
    								<table border='1' style='border-collapse: collapse; margin:0 auto; background-color: #def;border-style: none;'>
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
        
        
    //echo "<br><table border='1' style='border-collapse: collapse; margin:0 auto; background-color: #def;border-style: none;'><tr><th>Usuario</th><th>Estado</th><th>Fecha</th><th>Detalles</th><th>HASH</th></tr><tr>";
    
    $contador=0;
    while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        
        $id_registro= $line['id_evidencia_registro'];
        $estado= $line['estado'];
        $nombre = $line['apodo'];
        $programa= $line['programa'];
        $accion_programa= $line['accion_programa'];
        $hash= $line['hash'];
        $detalles= $line['observaciones'];
        $fecha= $line['fecha_alta_estado'];
        
        
        echo "<tr>
    					
                        	<form>";
        $id_registro=base64_encode($id_registro);
        echo "<td align='left'><a href='detalle_registro.php?id_registro=$id_registro'>$estado</a></td>";
        echo "<td align='left'>$nombre</td>"; 
        echo "<td aling='left'>$programa</td>";
        echo "<td aling='left'>$accion_programa</td>";
        echo "<td align='left'>$hash</td>";
        echo "<td align='left'>$detalles</td>";
        echo "<td align='left'>$fecha</td>";
        
        
        echo "</form>";
        echo "</tr>";
    } //while
    
    echo "						<tbody>
    					</table>
    			</section>";
    
    }  // if
    
    else {
        echo "<br><b>";
        echo "NO EXISTE REGISTRO";
        echo "</b>";
    }
    ?>
    <form method="POST" action="crearestado.php" id="myform">
    <b><br>Agregar Estado </b>
    <br><br> 
    <?php 
    
    ?>
    Usuario:
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
    
    Estado: 
    
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
    
    Programa:
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
    
    Accion:
    	
    	  <select id="accion_programa" name="accion_programa">
    	  <option value=null>Accion del programa</option>
    	  <?php
            $contador=0;
            $resultado = mysqli_query($link, "select id_accion_programa, nombre FROM accion_programa");
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
    <div class="auto" id="detalles">
    <b><br>Agregar Detalles<br><br></b>
    Detalles:
    <input type="text" name="detalles" id ="detalles" size=80>
    </div>
    
    <!--  
    XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
    para ocultar el hash ya que no siempre es necesario solo cuando se selecciona una accion determinada
    XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
    <div class="auto" id="detalles" style="display: none">
    -->
    
    <div class="auto" id="hash">
    <br><br>
    <b><br>Agregar HASH <br><br></b>
    HASH:
    <input type="text" name="num_hash" id ="num_hash" size=40>
    Tipo HASH:
     <select id="tipo_hash" name="tipo_hash">
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
}
else {
    echo "Error";
}
        ?>
    	</select>
</div>
<br><br>


<input type="submit" value="Agregar estado" class="estilo">
</form>
  	<br>

</div>
</body>
</html>