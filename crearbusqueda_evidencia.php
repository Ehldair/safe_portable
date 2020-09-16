<?php

session_start();
?>
<!DOCTYPE html>
<html lang="es-ES">

<head>


	<title>Busqueda Avanzada Evidencias</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
	

			
	<!-- Alonso -->
		<script src="//code.jquery.com/jquery-latest.js"></script>
		<script src="miscript.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script src="js/jquery-3.4.1.js"></script>
		<script type="text/javascript">
		
<?php

$myid_caso=$_SESSION['id_caso'];

$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}


$contador=0;
$inner=0;

if(!empty($_POST['nombre'])) {
    $mynombre= mysqli_real_escape_string($link,$_POST['nombre']);
}
else {
    $mynombre=0;
}
if(!empty($_POST['numero'])) {
    $mynumero= mysqli_real_escape_string($link,$_POST['numero']);
}
else {
    $mynumero=0;
}
if(!empty($_POST['n_s'])) {
    $myn_s= mysqli_real_escape_string($link,$_POST['n_s']);
}
else {
    $myn_s=0;
}
if(!empty($_POST['capacidad'])) {
    $mycapacidad= mysqli_real_escape_string($link,$_POST['capacidad']);
}
else {
    $mycapacidad=0;
}
if(!empty($_POST['marca'])) {
    $mymarca = mysqli_real_escape_string($link,$_POST['marca']);
}
else {
    $mymarca=0;
}
if(!empty($_POST['modelo'])) {
    $mymodelo = mysqli_real_escape_string($link,$_POST['modelo']);
}
else {
    $mymodelo=0;
}
if(!empty($_POST['tipo_evidencia'])) {
    $mytipo_evidencia = mysqli_real_escape_string($link,$_POST['tipo_evidencia']);
}
else {
    $mytipo_evidencia=0;
}
if(!empty($_POST['disco'])) {
    $mydisco = mysqli_real_escape_string($link,$_POST['disco']);
}
else {
    $mydisco=0;
}
if(!empty($_POST['alias'])) {
$myalias= mysqli_real_escape_string($link,$_POST['alias']);
}
else {
    $myalias=0;
}
if(!empty($_POST['estado'])) {
    $myestado= mysqli_real_escape_string($link,$_POST['estado']);
}
else {
    $myestado=0;
}
if(isset($_POST['conjuncion'])) {
    $myconjuncion=" OR ";
    $conjuncion=1;
}
else {
    $myconjuncion=" AND ";
    $conjuncion=0;
}

/*echo "Nombre: ".$mynombre;
echo "<br>Numero Serie: ".$myn_s;
echo "<br>Capacidad: ".$mycapacidad;
echo "<br>Marca: ".$mymarca;
echo "<br>Modelo: ".$mymodelo;
echo "<br>Tipo: ".$mytipo_evidencia;
echo "<br>Disco: ".$mydisco;
echo "<br>Alias: ".$myalias;
echo "<br>Conjuncion: ".$myconjuncion;*/

$busca = array(
    1  => $mynombre,
    2  => $myn_s,
    3  => $mycapacidad,
    4  => $mymarca,
    5  => $mymodelo,
    6  => $mytipo_evidencia,
    7  => $mydisco,
    8  => $myalias,
    9  => $mynumero,
    10 => $myestado,
);

$contador=count($busca);

$sql= "Select e.nombre as nom_ev,numero_evidencia,c.año, c.numero, n_s,capacidad,marca,modelo, s.nombre as nom_s";
$sql2= "WHERE e.id_caso=".$myid_caso;
if($conjuncion==1) {
    $sql2=$sql2." AND";
}
$sql3="INNER JOIN evidencia_registro er on er.id_evidencia=e.id_evidencia
        INNER JOIN estado_evidencia ee on ee.id_estado_evidencia=er.id_estado_evidencia ";
$entro=0;
for($i=1;$i<=$contador;$i++) {
    if($i==1) {
        if($busca[$i]!='0') {
            if($entro==0 and $conjuncion==1) {
                $sql2.="(e.nombre LIKE '%".$busca[$i]."%'";
                $entro=1;
            }
            $sql2.=$myconjuncion."e.nombre LIKE '%".$busca[$i]."%'";
        }
    }
    else {
        if($i==2) {
            if($busca[$i]!=0) {
                if($entro==0 and $conjuncion==1) {
                    $sql2.="(e.n_s LIKE '%".$busca[$i]."%'";
                    $entro=1;
                }
                else {
                    $sql2.=$myconjuncion."e.n_s LIKE '%".$busca[$i]."%'";
                    $entro=1;
                }
            }
        }
        else {
            if($i==3) { 
                if($busca[$i]!='0') {
                    if($entro==0 and $conjuncion==1) {
                        $sql2.="(e.capacidad LIKE '%".$busca[$i]."%'";
                        $entro=1;
                    }
                    else { 
                        $sql2.=$myconjuncion."e.capacidad LIKE '%".$busca[$i]."%'";
                    }
                }
            }
            else {
                if($i==4) {
                    if($busca[$i]!='0') {
                        if($entro==0 and $conjuncion==1) {
                            $sql2.="(e.marca LIKE '%".$busca[$i]."%'";
                            $entro=1;
                        }
                        else {
                            $sql2.=$myconjuncion."e.marca LIKE '%".$busca[$i]."%'";
                        }
                    }
                }
                else {
                    if($i==5) {
                        if($busca[$i]!='0') {
                            if($entro==0 and $conjuncion==1) {
                                $sql2.="(e.modelo LIKE '%".$busca[$i]."%'";
                                $entro=1;
                            }
                            else {
                                $sql2.=$myconjuncion."e.modelo LIKE '%".$busca[$i]."%'";
                            }
                        }
                    }
                    else {
                        if($i==6) {
                            if($busca[$i]!=0) {
                                if($entro==0 and $conjuncion==1) {
                                    $sql2.="(e.id_subtipo_evidencia LIKE '%".$busca[$i]."%'";
                                    $entro=1;
                                }
                                else {
                                    $sql2.=$myconjuncion."e.id_subtipo_evidencia LIKE '%".$busca[$i]."%'";
                                }   
                            }
                        }
                        else {
                            if($i==7) {
                                if($busca[$i]!='0') {  
                                    if($entro==0 and $conjuncion==1) {  
                                        $sql2.="(e.id_disco_almacenado LIKE '%".$busca[$i]."%'";
                                        $entro=1;
                                    }
                                    else {   
                                        $sql2.=$myconjuncion."e.id_disco_almacenado LIKE '%".$busca[$i]."%'";
                                    }
                                }
                            }
                            else {
                                if($i==8) {
                                    if($busca[$i]!=0) {
                                        if($entro==0 and $conjuncion==1) {
                                            $sql2.="(e.alias LIKE '%".$busca[$i]."%'";;
                                            $entro=1;
                                        }
                                        else {
                                            $sql2.=$myconjuncion."e.alias LIKE '%".$busca[$i]."%'";
                                        }
                                    }
                                }
                                else {
                                    if($i==9) {
                                        if($busca[$i]!='0') {
                                            if($entro==0 and $conjuncion==1) {  
                                                $sql2.="(e.numero_evidencia LIKE '%".$busca[$i]."%'";;
                                                $entro=1;
                                            }
                                            else {
                                                $sql2.=$myconjuncion."e.numero_evidencia LIKE '%".$busca[$i]."%'";
                                            }
                                        }
                                    }
                                    else {
                                        if($i==10) {
                                            if($busca[$i]!='0') {
                                                if($entro==0 and $conjuncion==1) {
                                                    $sql2.="(er.id_estado_evidencia LIKE '%".$busca[$i]."%'";;
                                                    $entro=1;
                                                    $inner=1;
                                                }
                                                else {
                                                    $sql2.=$myconjuncion."er.id_estado_evidencia LIKE '%".$busca[$i]."%'";
                                                    $inner=1;
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
        }
    }
}
$sql = trim($sql, ',');
if($inner!=0) {
    $sql.= " ,ee.nombre";
}
$sql.= " FROM evidencia e 
INNER JOIN subtipo_evidencia s ON e.id_subtipo_evidencia=s.id_subtipo_evidencia
       INNER JOIN caso c ON e.id_caso=c.id_caso ";
if($inner!=0) {
    $sentencia= $sql.$sql3.$sql2;
}
else {
    $sentencia= $sql.$sql2;
}
if($conjuncion==1) {
    $sentencia= $sentencia.")";
}

echo $sentencia;


?>
</script>
</head>

<body class="is-preload">
<div id="page-wrapper">

<!-- Header -->
    				<header id="header">
    					<h1><a href="">Safe Ciber</a> Gestión Sección Ciberterrorismo</h1>
    					<nav id="nav">
    						<ul>
    							<li><a href="inicio.php">Home</a></li>
    							<li>
    								<a href="#" class="icon solid fa-angle-down">Casos</a>
    								<ul>
    									<li><a href="busqueda_Caso.php">Buscar</a></li>
    									<li><a href="nuevoasunto.php">Nuevo</a></li>
    
    									<li>
    										<a href="#">Listar</a>
    										<ul>
    											<li><a href="abiertos.php">Abiertos</a></li>
    											<li><a href="cerrados.php">Cerrados</a></li>
    											<li><a href="todos.php">Todos</a></li>
    										</ul>
    									</li>
    									
    								</ul>
    							</li>
    							<li>
    								<a href="#" class="icon solid fa-angle-down">Gestión</a>
    								<ul>
    									<li><a href="compensacion_usuario.php">Compensaciones</a></li>
    									<li><a href="viajes_año.php">Viajes</a></li>
    								</ul>	
    							</li>
    							<li>
    								<a href="#" class="icon solid fa-angle-down">Administración</a>
    								<ul>
    									<li>
    										<a href="#">Usuario</a>
    										<ul>
    											<li><a href="nuevousuario.php">Nuevo</a></li>
    											<li><a href="#">Gestión</a></li>
    											<li><a href="#"></a></li>
    										</ul>
    									</li>
    									<li>
    										<a href="#">Viajes</a>
    										<ul>
    											<li><a href="nuevoviaje.php">Nuevo</a></li>
    											<li><a href="viajes.php">Gestión</a></li>
    											<li><a href="#"></a></li>
    										</ul>
    									</li>
    								</ul>	
    							</li>
    							
    							
    							<li><a href="login.php" class="button">Cerrar</a></li>
    						</ul>
    					</nav>
    				</header>


<!-- Main -->
<section id="main" class="container">
<header>
<h2>Busqueda Avanzada Evidencias</h2>
<p></p>


<?php 
echo "<br><table border='1' style='border-collapse: collapse; margin:0 auto; background-color: #def;border-style: none;'><tr><th>Nombre</th><th>Numero caso</th><th>N. Serie</th><th>Capacidad</th><th>Marca</th><th>Modelo</th><th>Tipo Evidencia</th>";
if($inner==1) {
echo "<th>Estado</th>";
}
echo "</tr>";
$resultado = mysqli_query($link, $sentencia);
$contador=0;
while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
    foreach ($line as $col_value) {
        if($contador==0) {
            $nombre_original=$col_value;
            $contador++;
        }
        else {
            if($contador==1) {
                echo "<tr><td style='text-align: left'>";
                $nombre=base64_encode($nombre_original);
                $numero=base64_encode($col_value);
 
                echo "<a href='detalle_evidencia.php?nombre=$nombre&numero=$numero'>$nombre_original$col_value</a>";

                echo "</td>";
                $contador++;
            }
            else {
                if($contador==2){
                    $año=substr($col_value,2,2);
                    $contador++;
                }
                else {
                    if($contador==3) {
                        echo "<td align='left'>";
                        echo $col_value."_".$año;
                        echo "</td>";
                        $contador++;
                    }
                    else {
                        if($inner==1) {
                            if ($contador<9) {    
                                echo "<td align='left'>";
                                echo $col_value;
                                echo "</td>";
                                $contador++;
                            }
                            else {
                                echo "<td align='left'>";
                                echo $col_value;
                                echo "</td>";
                                $contador=0;
                            }
                        }
                        else {
                            if ($contador<8) {
                                echo "<td align='left'>";
                                echo $col_value;
                                echo "</td>";
                                $contador++;
                            }
                            else {
                                echo "<td align='left'>";
                                echo $col_value;
                                echo "</td>";
                                $contador=0;
                            }
                        } 
                    }
                }
            }
        }// else
    } 
    echo "</tr>";
} 
   
echo "</table>";




mysqli_close($link);
?>

			<input type="button" onclick="location.href='busqueda_evidencia.php';" value="Volver" class="estilo"><br>
			<div id="div-results"></div>

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

