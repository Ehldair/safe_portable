<?php

session_start();





$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

?>

<!DOCTYPE html>
<html lang="es-ES">

<head>


	<title>Busqueda Avanzada Casos</title>
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



<?php


$contador=0;
$inner=0;
$existe=0;

if(!empty($_POST['numero'])) {
    $mynumero= mysqli_real_escape_string($link,$_POST['numero']);
    $existe=1;
}
else {
    $mynumero=0;
}
if(!empty($_POST['año_inicio'])) {
$myaño_inicio= mysqli_real_escape_string($link,$_POST['año_inicio']);
$existe=1;
}
else {
    $myaño_inicio=0;
    
}
if(!empty($_POST['año_fin'])) {
    $myaño_fin= mysqli_real_escape_string($link,$_POST['año_fin']);
    $existe=1;
}
else {
    $myaño_fin=0;
}
if(!empty($_POST['nombre'])) {
$mynombre = mysqli_real_escape_string($link,$_POST['nombre']);
$existe=1;
}
else {
    $mynombre=0;
}
if(!empty($_POST['diligencias'])) {
$mydiligencias = mysqli_real_escape_string($link,$_POST['diligencias']);
$existe=1;
}
else {
    $mydiligencias=0;
}
if(!empty($_POST['año_diligencias_inicio'])) {
$myaño_diligencias_inicio = mysqli_real_escape_string($link,$_POST['año_diligencias_inicio']);
$existe=1;
}
else {
    $myaño_diligencias_inicio=0;
}
if(!empty($_POST['año_diligencias_fin'])) {
    $myaño_diligencias_fin = mysqli_real_escape_string($link,$_POST['año_diligencias_fin']);
    $existe=1;
}
else {
    $myaño_diligencias_fin=0;
}
if(!empty($_POST['juzgado'])) {
$myjuzgado= mysqli_real_escape_string($link,$_POST['juzgado']);
$existe=1;
}
else {
    $myjuzgado=0;
}
if(!empty($_POST['tipo_caso'])) {
$mytipo= mysqli_real_escape_string($link,$_POST['tipo_caso']);
$existe=1;
}
else {
    $mytipo=0;
}
if(!empty($_POST['ca'])) {
$myca= mysqli_real_escape_string($link,$_POST['ca']);
$existe=1;
}
else {
    $myca=0;
}
if(!empty($_POST['provincia'])) {
$myprovincia= mysqli_real_escape_string($link,$_POST['provincia']);
$existe=1;
}
else {
    $myprovincia=0;
}
if(!empty($_POST['comisaria'])) {
$mycomisaria= mysqli_real_escape_string($link,$_POST['comisaria']);
$existe=1;
}
else {
    $mycomisaria=0;
}
if(!empty($_POST['grupo'])) {
$mygrupo= mysqli_real_escape_string($link,$_POST['grupo']);
$existe=1;
}
else {
    $mygrupo=0;
}
if(isset($_POST['conjuncion'])) {
    $myconjuncion=" OR ";
}
else {
    $myconjuncion=" AND ";
}

echo "Numero: ".$mynumero;
echo "<br>Año Inicio: ".$myaño_inicio;
echo "<br>Año Fin: ".$myaño_fin;
echo "<br>Nombre: ".$mynombre;
echo "<br>Diligencias: ".$mydiligencias;
echo "<br>Año Diligencias: ".$myaño_diligencias_inicio;
echo "<br>Año Diligencias: ".$myaño_diligencias_fin;
echo "<br>Juzgado: ".$myjuzgado;
echo "<br>Tipo: ".$mytipo;
echo "<br>Ca: ".$myca;
echo "<br>Provincia: ".$myprovincia;
echo "<br>Comisaria: ".$mycomisaria;
echo "<br>Grupo: ".$mygrupo;
    

$busca = array(
    1    => $mynumero,
    2  => $myaño_inicio,
    3  => $myaño_fin,
    4  => $mynombre,
    5    => $mydiligencias,
    6  => $myaño_diligencias_inicio,
    7  => $myaño_diligencias_fin,
    8  => $myjuzgado,
    9 => $mytipo,
    10 => $mygrupo,
);

$contador=count($busca);

$sql= "Select id_caso,c.año, c.numero,  c.nombre, c.descripcion";
$sql2= "WHERE ";
$sql3="INNER JOIN diligencias d on d.id_diligencias=c.id_diligencias ";
$entro=0;
for($i=1;$i<=$contador;$i++) {
    if($i==1) {
        if($busca[$i]!=0) {
            if($entro==0) {
                $entro=1;
        }
            $sql2.="c.numero=".$busca[$i];
        }
    }
    else {
        if($i==2) {
            if($busca[$i]!=0) {
                if($entro==0) {
                    $sql2.="c.año BETWEEN ".$busca[$i];
                    $entro=1;
                }
                else {
                    $sql2.=$myconjuncion."c.año BETWEEN ".$busca[$i];
                    $entro=1;
                }
            }
        }
        else {
            if($i==3) {
                
                if($busca[$i]!='0') {
                    
                    if($entro==0) {
                      
                        $sql2.=" AND ".$busca[$i];
                        $entro=1;
                    }
                    else {
                        
                        $sql2.=" AND ".$busca[$i];
                    }
                }
            }
            else {
                if($i==4) {

                    if($busca[$i]!='0') {
                    
                        if($entro==0) {
                            $sql2.="c.nombre LIKE '%"."$busca[$i]"."%'";
                            $entro=1;
                        }
                        else {
                            $sql2.=$myconjuncion."c.nombre like '%"."$busca[$i]"."%'";
                        }
                    }
                }
                else {
                    if($i==5) {
                        if($busca[$i]!=0) {
                            $inner=1;
                            if($entro==0) {
                                $sql2.="d.numero=".$busca[$i];
                                $entro=1;
                            }
                            else {
                                $sql2.=$myconjuncion."d.numero=".$busca[$i];
                            }
                        }
                    }
                    else {
                        if($i==6) {
                            if($busca[$i]!=0) {
                                $inner=1;
                                if($entro==0) {
                                    $sql2.="d.año  BETWEEN ".$busca[$i];
                                    $entro=1;
                                }
                                else {
                                    $sql2.=$myconjuncion."d.año  BETWEEN ".$busca[$i];
                                }   
                            }
                        }
                        else {
                            if($i==7) {
                                if($busca[$i]!='0') {  
                                    if($entro==0) {  
                                        $sql2.=" AND ".$busca[$i];
                                        $entro=1;
                                    }
                                    else {   
                                        $sql2.=" AND ".$busca[$i];
                                    }
                                }
                            }
                            else {
                                if($i==8) {
                                    if($busca[$i]!=0) {
                                        $inner=1;
                                        if($entro==0) {
                                            $sql2.="d.id_juzgado=".$busca[$i];
                                            $entro=1;
                                        }
                                        else {
                                            $sql2.=$myconjuncion."d.id_juzgado=".$busca[$i];
                                        }
                                    }
                                }
                                else {
                                    if($i==9) {
                                        if($busca[$i]!='0') {
                                            if($entro==0) {
                                                $sql2.="c.id_tipo_caso=".$busca[$i];
                                                $entro=1;
                                            }
                                            else {
                                                $sql2.=$myconjuncion."c.id_tipo_caso=".$busca[$i];
                                            }
                                        }
                                    }
                                    else {
                                        if($i==10) {
                                            if($busca[$i]!='0') {
                                                if($entro==0) {
                                                    $sql2.="c.id_grupo_investigacion=".$busca[$i];
                                                    $entro=1;
                                                }
                                                else {
                                                    $sql2.=$myconjuncion."c.id_grupo_investigacion=".$busca[$i];
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
$sql=$sql." FROM caso c ";
if($existe==0) {
    $sql=$sql." Where id_caso!=1";
    $sentencia= $sql;
}
else {
    if($inner!=0) {
        $sentencia= $sql.$sql3.$sql2;
    }
    else {
        $sentencia= $sql.$sql2;
    }
}

?>
</script>
</head>

<body class="is-preload">
<div id="page-wrapper">

<!-- Header -->
<header id="header">
<h1><a href="login.php">Safe Ciber</a> Gestión Sección Ciberterrorismo</h1>
<nav id="nav">
<ul>
<li><a href="inicio.php">Home</a></li>
<li>
<a href="#" class="icon solid fa-angle-down">Layouts</a>
<ul>
<li><a href="generic.html">Generic</a></li>
<li><a href="contact.html">Contact</a></li>
<li><a href="elements.html">Elements</a></li>
<li>
<a href="#">Submenu</a>
<ul>
<li><a href="#">Option One</a></li>
<li><a href="#">Option Two</a></li>
<li><a href="#">Option Three</a></li>
<li><a href="#">Option Four</a></li>
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
<h2>Busqueda Avanzada Casos</h2>
<p></p>

<?php 
echo "<br><table border='1' style='border-collapse: collapse; margin:0 auto; background-color: #def;border-style: none;'><tr><th>Caso</th><th>Nombre</th><th>Descripcion</th></tr>";
$resultado = mysqli_query($link, $sentencia);
$contador=0;
while ($line = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
    foreach ($line as $col_value) {
        if ($contador==0) {
            $id_caso=$col_value;
            $contador++;
        }
        else {
            if($contador==1) {
                $año=substr($col_value,2,2);
                $contador++;
            }
            else {
                if($contador==2) {
                    echo "
				<tr>
					<td style='text-align: left'>";
                    $id_caso=base64_encode($id_caso);
                    ?>
						<a href="asunto.php?id_caso=<?php echo $id_caso;?>">
						<?php echo $col_value;?>_<?php echo $año;?>
						</a> 

                
                <?php 
               
                $contador++;
            }
            else {
             
                    if ($contador<4) {
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
        } // else

    } // foracha
    
    echo "</tr>";

}




mysqli_close($link);
?>

			<input type="button" onclick="location.href='inicio.php';" value="Volver" class="estilo"><br>
			<div id="div-results"></div>

	</div>
	
		
	
	
</body>
</html>
