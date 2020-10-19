<?php
include('FPDF/fpdf.php');
$pdf = new FPDF("L");

session_start();
$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");
$myid_caso=$_SESSION['id_caso'];
$entera=$_POST['entera'];
if(isset($_POST['intervenciones'])) {
    $id_intervencion=$_POST['intervenciones'];
}
if(isset($_POST['todos_datos'])) {
    $todos_datos=$_POST['todos_datos'];
    $resultado_sujeto=mysqli_query($link, "select i.id_sujeto_activo,nombre,apellido1,apellido2,i.id_intervencion from sujeto_activo s
    inner join intervencion i on i.id_sujeto_activo=s.id_sujeto_activo where i.id_caso=$myid_caso and i.id_intervencion=$id_intervencion");
}
else {
    $resultado_sujeto=mysqli_query($link, "select i.id_sujeto_activo,nombre,apellido1,apellido2,i.id_intervencion from sujeto_activo s
    inner join intervencion i on i.id_sujeto_activo=s.id_sujeto_activo where i.id_caso=$myid_caso");
}

if(isset($_POST['todos_datos_evidencias'])) {
    $todos_datos_evidencias=$_POST['todos_datos_evidencias'];
}
if($entera==1) {
    $todos_datos_evidencias=1;
}
if(isset($_POST['todas_evidencias'])) {
    $todas_evidencias=$_POST['todas_evidencias'];
}
if($entera!=1 and $todas_evidencias!=1) {
    $evidencias="";
    $coma=0;
    $num_evidencias=0;
    if(!isset($_POST['lista_ev'])) {
        $todas_evidencias=1;
    }
    else {
        foreach($_POST['lista_ev'] as $seleccion) {
            if($coma==0) {
                $evidencias=$evidencias.$seleccion;
                $coma=1;
                $num_evidencias++;
            }
            else {
                $evidencias=$evidencias.",".$seleccion;
                $num_evidencias++;
            }
            
        }
    }
}


$resultado_año=mysqli_query($link, "select numero,año, DATE_FORMAT(fecha_alta_caso,'%d/%m/%Y') as fecha_alta_caso, id_diligencias from caso where id_caso=$myid_caso");
$ret=mysqli_fetch_array($resultado_año);

while ($line_sujeto = mysqli_fetch_array($resultado_sujeto, MYSQLI_ASSOC)) {
    $pdf->SetFont('Arial','B',8);
    $pdf->AddPage();
    $pdf->Cell(20,5,'CASO',1,0,'C');
    $pdf->Cell(30,5,'FECHA',1,0,'C');
    $pdf->Cell(20,5,'DILIGENCIAS',1,0,'C');
    $pdf->Cell(40,5,'JUZGADO',1,0,'C');
    $pdf->Cell(60,5,'DETENIDO',1,0,'C');
    $pdf->Cell(100,5,'DIRECCION REGISTRO',1,0,'C');
    $pdf->Ln();
    $pdf->SetFont('Arial','',8);
    if(isset($_POST['caso_num']) or ($entera==1) or ($todos_datos==1)) {
        $pdf->Cell(20,6,$ret['numero'].'_'.substr($ret['año'],0,2),1,0,'C');
    }
    else {
        $pdf->Cell(20,6,'',1,0,'C');
    }
    if(isset($_POST['fecha']) or ($entera==1) or ($todos_datos==1)) {
        $pdf->Cell(30,6,$ret['fecha_alta_caso'],1,0,'C');
    }
    else {
        $pdf->Cell(30,6,'',1,0,'C');
    }
    
    
    if(isset($ret['id_diligencias'])) {
        $resultado_diligencias=mysqli_query($link, "select id_juzgado, numero, año from diligencias where id_diligencias=$ret[id_diligencias]");
        $ret_diligencias=mysqli_fetch_array($resultado_diligencias);
        $resultado_juzgado=mysqli_query($link, "select jurisdiccion, nombre, numero from juzgado where id_juzgado=$ret_diligencias[id_juzgado]");
        $ret_juzgado=mysqli_fetch_array($resultado_juzgado);
        $nombre=$ret_juzgado['nombre'];
        $trozos = explode(" ", $nombre);
        $acronimo="";
        for($i=0;$i<count($trozos);$i++) {
            $acr=substr($trozos[$i], 0,1);
            if(ctype_upper($acr)) {
                $acronimo=$acronimo.$acr;
            }
        }
        if(isset($_POST['diligencias']) or ($entera==1) or ($todos_datos==1)) {
            $pdf->Cell(20,6,$ret_diligencias['numero'].'/'.$ret_diligencias['año'],1,0,'C');
        }
        else {
            $pdf->Cell(20,6,'',1);
        }
        if(isset($_POST['juzgado']) or ($entera==1) or ($todos_datos==1)) {
            $pdf->Cell(40,6,utf8_decode($acronimo.' '.$ret_juzgado['numero'].','.$ret_juzgado['jurisdiccion']),1,0,'C');
        }
        else {
            $pdf->Cell(40,6,'',1);
        }
        
    }
    else {
        $pdf->Cell(20,6,'',1);
        $pdf->Cell(40,6,'',1);
    }
    
    if(mysqli_num_rows($resultado_sujeto)==0) {
        $id_sujeto=1;
        if(isset($_POST['detenido']) or ($entera==1) or ($todos_datos==1)) {
            $pdf->Cell(60,6,'Sin Detenido',1,0,'C');
        }
        else {
            $pdf->Cell(60,6,'',1,0,'C');
        }
    }
    else {
        $id_sujeto=$line_sujeto['id_sujeto_activo'];
        if(isset($_POST['detenido']) or ($entera==1) or ($todos_datos==1)) {
            $pdf->Cell(60,6,utf8_decode($line_sujeto['nombre'].' '.$line_sujeto['apellido1'].' '.$line_sujeto['apellido2']),1,0,'C');
        }
        else {
            $pdf->Cell(60,6,'',1,0,'C');
        }
    }
    
    $resultado_intervencion=mysqli_query($link, "select direccion from intervencion where id_intervencion=$line_sujeto[id_intervencion]");
    $ret_intervencion=mysqli_fetch_array($resultado_intervencion);
    if(isset($_POST['direccion']) or ($entera==1) or ($todos_datos==1)) {
        $pdf->Cell(100,6,utf8_decode($ret_intervencion['direccion']),1,0,'C');
    }
    else {
        $pdf->Cell(100,6,'',1,0,'C');
    }
    $pdf->Ln();
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(20,8,'EVIDENCIA',1,0,'C');
    $pdf->Cell(30,8,'MARCA Y MODELO',1,0,'C');
    $pdf->Cell(60,8,'N/S - IMEI - ICC',1,0,'C');
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(15,8,'CAPACIDAD',1,0,'C');
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(20,5,'CLONADO',0,0,'C');
    $pdf->Cell(25,8,'SISTEMA',1,0,'C');
    $pdf->Cell(20,8,'EXTRACCION',1,0,'C');
    $pdf->Cell(15,8,'PATRON',1,0,'C');
    $pdf->Cell(15,8,'PIN',1,0,'C');
    $pdf->Cell(50,8,'OBSERVACIONES',1,0,'C');
    $pdf->SetXY(135,26);
    $pdf->Cell(10,3,'inicio',0,0,'C');
    $pdf->Cell(10,3,'fin',0,0,'C');
    $pdf->Ln();
    $y=29;
    $n=0;
    if($entera==1 or $todas_evidencias==1) {
        $resultado_evidencias=mysqli_query($link, "select * from evidencia where id_caso=$myid_caso and id_intervencion=$line_sujeto[id_intervencion] order by numero_evidencia");
    }
    else {
        $sql="select * from evidencia where id_caso=$myid_caso";
        $trozos_evidencias=explode(",", $evidencias);
        for($x=0;$x<$num_evidencias;$x++) {
            if($x==0) {
                $sql=$sql." AND id_evidencia=".$trozos_evidencias[$x];
            }
            else {
                $sql=$sql." OR id_evidencia=".$trozos_evidencias[$x];
            }
        }
        $resultado_evidencias=mysqli_query($link,$sql);
    }
    while ($line_evidencias = mysqli_fetch_array($resultado_evidencias, MYSQLI_ASSOC)) {
        $nombre=$line_evidencias['nombre'];
        $numero=$line_evidencias['numero_evidencia'];
        if(isset($_POST['marca_modelo']) or ($entera==1) or ($todos_datos_evidencias==1)) {
            $marca=$line_evidencias['marca'];
            if(isset($line_evidencias['modelo']) and $line_evidencias['modelo']!='NULL' and $line_evidencias['modelo']!=null) {
                $modelo=$line_evidencias['modelo'];
            }
            else {
                $modelo=" ";
            }
        }
        else {
            $marca=" ";
            $modelo=" ";
        }
        if(isset($_POST['patron']) or ($entera==1) or ($todos_datos_evidencias==1)) {
            $patron=$line_evidencias['patron'];
        }
        else {
            $patron=null;
        }
        if(isset($_POST['ns']) or ($entera==1) or ($todos_datos_evidencias==1)) {
            $n_s=$line_evidencias['n_s'];
        }
        else {
            $n_s=" ";
        }
        if(isset($_POST['capacidad']) or ($entera==1) or ($todos_datos_evidencias==1)) {
            $capacidad=$line_evidencias['capacidad'];
            $tipo_capacidad=$line_evidencias['id_tipo_capacidad'];
        }
        else {
            $capacidad=" ";
            $tipo_capacidad=" ";
        }
        if(isset($_POST['observaciones']) or ($entera==1) or ($todos_datos_evidencias==1)) {
            $observaciones=$line_evidencias['observaciones'];
        }
        else {
            $observaciones=" ";
        }
        if(isset($_POST['pin']) or ($entera==1) or ($todos_datos_evidencias==1)) {
            $pin=$line_evidencias['pin'];
        }
        else {
            $pin=" ";
        }
        $id_evidencia=$line_evidencias['id_evidencia'];
        $pdf->SetY($y);
        $pdf->SetFont('Arial','',7);
        $pdf->Cell(20,11,$nombre.$numero,1,0,'C');
        $pdf->SetXY(30,$y-2);
        $pdf->Cell(30,13,$marca,'B',0,'C');
        $pdf->SetXY(32,$y+3);
        $pdf->Cell(28,11,$modelo,0,0,'C');
        $pdf->SetXY(60,$y);
        $pdf->Cell(60,11,$n_s,1,0,'C');
        $pdf->Cell(15,11,$capacidad,1,0,'L');
        
        $resultado_clonado=mysqli_query($link, "select * from evidencia_registro where id_evidencia=$id_evidencia and id_estado_evidencia=2 LIMIT 1");
        $count_clonado=mysqli_num_rows($resultado_clonado);
        $ret_clonado=mysqli_fetch_array($resultado_clonado);
        if(isset($_POST['clonado']) or ($entera==1) or ($todos_datos_evidencias==1)) {
            if($count_clonado!=0) {
                $pdf->Image('img/aceptar.png',137,$y+3,5);
                $pdf->Cell(10,11,'',1,0,'C');
                $pdf->Image('img/aceptar.png',148,$y+3,5);
                $pdf->Cell(10,11,'',1,0,'C');
                
            }
            else {
                $ret_clonado['id_programa']=0;
                $ret_clonado['id_accion_programa']=0;
                $pdf->Image('img/cancelar.png',137,$y+3,5);
                $pdf->Cell(10,11,'',1,0,'C');
                $pdf->Image('img/cancelar.png',148,$y+3,5);
                $pdf->Cell(10,11,'',1,0,'C');
            }
        }
        else {
            $pdf->Cell(10,11,'',1,0,'C');
            $pdf->Cell(10,11,'',1,0,'C');
            
        }
        if(!isset($_POST['sistema']) and $entera!=1 and $todos_datos_evidencias!=1) {
            $ret_clonado['id_programa']=0;
            $ret_clonado['id_accion_programa']=0;
        }
        $pdf->Cell(25,11,'',1,0,'C');
        $pdf->Cell(20,11,'',1,0,'C');
        $pdf->Cell(15,11,'',1,0,'C');
        $pdf->Cell(15,11,$pin,1,0,'C');
        $principio=0;
        $sumaY=3;
        if(isset($observaciones) and $observaciones!='NULL' and $observaciones!=null) {
            if(strlen($observaciones)>40) {
                $interacciones=ceil(strlen($observaciones)/40);
                for($x=0;$x<$interacciones;$x++) {
                    $texto=substr($observaciones,$principio,41);
                    $principio=$principio+41;
                    $pdf->Cell(50,3,utf8_decode(strtolower($texto)),'R',0);
                    $pdf->SetXY(230,$y+$sumaY);
                    $sumaY=$sumaY+3;
                }
                $pdf->SetXY(230,$y);
                $pdf->Cell(50,11,'','1',0);
            }
            else {
                $texto=$observaciones;
                $pdf->Cell(50,11,utf8_decode(strtolower($texto)),1,0);
            }
        }
        else {
            $texto='';
            $pdf->Cell(50,11,utf8_decode(strtolower($texto)),1,0);
        }
        
        $pdf->SetXY(128,$y+1);
        $pdf->Cell(2,2,'',1,0,'C');
        if($tipo_capacidad==1) {
            $pdf->Cell(-2,2,'X',0,0,'C');
        }
        $pdf->SetXY(132,$y+1);
        $pdf->Cell(2,2,'MB',0,0,'C');
        $pdf->SetXY(128,$y+4);
        $pdf->Cell(2,2,'',1,0,'C');
        if($tipo_capacidad==3) {
            $pdf->Cell(-2,2,'X',0,0,'C');
        }
        $pdf->SetXY(132,$y+4);
        $pdf->Cell(2,2,'GB',0,0,'C');
        $pdf->SetXY(128,$y+7);
        $pdf->Cell(2,2,'',1,0,'C');
        if($tipo_capacidad==4) {
            $pdf->Cell(-2,2,'X',0,0,'C');
        }
        $pdf->SetXY(132,$y+7);
        $pdf->Cell(2,2,'TB',0,0,'C');
        $pdf->SetXY(177,$y+1);
        $pdf->Cell(2,2,'',1,0,'C');
        if($ret_clonado['id_programa']==6) {
            $pdf->Cell(-2,2,'X',0,0,'C');
        }
        $pdf->SetXY(155,$y+1);
        $pdf->Cell(2,2,'GUYMAGER',0,0,'L');
        $pdf->SetXY(177,$y+4);
        $pdf->Cell(2,2,'',1,0,'C');
        if($ret_clonado['id_programa']==2) {
            $pdf->Cell(-2,2,'X',0,0,'C');
        }
        $pdf->SetXY(155,$y+4);
        $pdf->Cell(2,2,'CELEBRITE',0,0,'L');
        $pdf->SetXY(177,$y+7);
        $pdf->Cell(2,2,'',1,0,'C');
        if($ret_clonado['id_programa']==32) {
            $pdf->Cell(-2,2,'X',0,0,'C');
        }
        $pdf->SetXY(155,$y+7);
        $pdf->Cell(2,2,'TABLEAU',0,0,'L');
        $pdf->SetXY(197,$y+1);
        $pdf->Cell(2,2,'',1,0,'C');
        if($ret_clonado['id_accion_programa']==4) {
            $pdf->Cell(-2,2,'X',0,0,'C');
        }
        $pdf->SetXY(181,$y+1);
        $pdf->Cell(2,2,'E.FISICA',0,0,'L');
        $pdf->SetXY(197,$y+4);
        $pdf->Cell(2,2,'',1,0,'C');
        if($ret_clonado['id_accion_programa']==5) {
            $pdf->Cell(-2,2,'X',0,0,'C');
        }
        $pdf->SetXY(181,$y+4);
        $pdf->Cell(2,2,'SIST.ARCH.',0,0,'L');
        $pdf->SetXY(197,$y+7);
        $pdf->Cell(2,2,'',1,0,'C');
        if($ret_clonado['id_accion_programa']==6 or $ret_clonado['id_accion_programa']==7) {
            $pdf->Cell(-2,2,'X',0,0,'C');
        }
        $pdf->SetXY(181,$y+7);
        $pdf->Cell(2,2,'E.LOGICA',0,0,'L');
        $pdf->Image('img/radio.jpg',202.5,$y+1,2);
        $pdf->Image('img/radio.jpg',206.5,$y+1,2);
        $pdf->Image('img/radio.jpg',210.5,$y+1,2);
        $pdf->Image('img/radio.jpg',202.5,$y+4,2);
        $pdf->Image('img/radio.jpg',206.5,$y+4,2);
        $pdf->Image('img/radio.jpg',210.5,$y+4,2);
        $pdf->Image('img/radio.jpg',202.5,$y+7,2);
        $pdf->Image('img/radio.jpg',206.5,$y+7,2);
        $pdf->Image('img/radio.jpg',210.5,$y+7,2);
        $pdf->SetXY(200, $y);
        if(empty($patron) OR $patron=='' OR $patron=='null' or $patron==null) {
            
        }
        else {
            $longitud=strlen($patron)-1;
            for ($i = 0; $i <= $longitud; $i++) {
                $numero=substr($patron, $i, 1);
                if(is_numeric(substr($patron, $i, 1))) {
                    $numero_patron=substr($patron, $i, 1);
                    $segundo=substr($patron, 2, 1);
                    $penultimo=substr($patron, $longitud-3, 1);
                    $ultimo=substr($patron,$longitud-1,1);
                    $primero=substr($patron, 0, 1);
                    if($numero_patron==1) {
                        if($primero==1) {
                            $x1=203.5;
                            $y1=$y+2;
                            if($segundo==2 or $segundo==3) {
                                $pdf->Line(202, $y+3,203.5,$y+2);
                                $pdf->Line(202, $y+1,203.5,$y+2);
                            } else if($segundo==4 or $segundo==7) {
                                $pdf->Line(202.5, $y+0.5,203.5,$y+2);
                                $pdf->Line(204.5, $y+0.5,203.5,$y+2);
                            } else if($segundo==5 or $segundo==9) {
                                $pdf->Line(203, $y+0.5,203.5,$y+2);
                                $pdf->Line(202, $y+2 ,203.5,$y+2);
                            } else if($segundo==6) {
                                $pdf->Line(202, $y+0.5,203.5,$y+2);
                                $pdf->Line(201.5, $y+2 ,203.5,$y+2);
                            } else if($segundo==8) {
                                $pdf->Line(203, $y,203.5,$y+2);
                                $pdf->Line(201.5,$y+1 ,203.5,$y+2);
                            }
                        }else {
                            $pdf->Line($x1, $y1,203.5,$y+2);
                            $x1=203.5;
                            $y1=$y+2;
                            if($ultimo==1) {
                                if($penultimo==2 or $penultimo==3) {
                                    $pdf->Line(205, $y+3,203.5,$y+2);
                                    $pdf->Line(205, $y+1,203.5,$y+2);
                                }
                                if($penultimo==4 or $penultimo==7) {
                                    $pdf->Line(204.5, $y+3.5,203.5,$y+2);
                                    $pdf->Line(202.5, $y+3.5,203.5,$y+2);
                                }
                                if($penultimo==5) {
                                    $pdf->Line(205.5, $y+2.5,203.5,$y+2);
                                    $pdf->Line(204.5, $y+3.5,203.5,$y+2);
                                }
                                if($penultimo==6) {
                                    $pdf->Line(205, $y+3.5,203.5,$y+2);
                                    $pdf->Line(205.5, $y+2,203.5,$y+2);
                                }
                                if($penultimo==8) {
                                    $pdf->Line(205.5, $y+3.5,203.5,$y+2);
                                    $pdf->Line(204, $y+4,203.5,$y+2);
                                }
                                if($penultimo==9) {
                                    $pdf->Line(205.5, $y+2.5,203.5,$y+2);
                                    $pdf->Line(204.5, $y+4,203.5,$y+2);
                                }
                            }
                        }
                    }
                } else if($numero_patron==2) {
                    if($primero==2) {
                        $x1=207.5;
                        $y1=$y+2;
                        if($segundo==1) {
                            $pdf->Line(209, $y+3,207.5,$y+2);
                            $pdf->Line(209, $y+1,207.5,$y+2);
                        } else if($segundo==3) {
                            $pdf->Line(206, $y+3,207.5,$y+2);
                            $pdf->Line(206, $y+1,207.5,$y+2);
                        } else if($segundo==4) {
                            $pdf->Line(209, $y+2.5,207.5,$y+2);
                            $pdf->Line(208, $y+0.5,207.5,$y+2);
                        } else if($segundo==5 or $segundo==8) {
                            $pdf->Line(206, $y+0.5,207.5,$y+2);
                            $pdf->Line(208.5, $y+0.5,207.5,$y+2);
                        } else if($segundo==6) {
                            $pdf->Line(206, $y+1.5,207.5,$y+2);
                            $pdf->Line(207, $y+0.5,207.5,$y+2);
                        } else if($segundo==7) {
                            $pdf->Line(209, $y+1.5,207.5,$y+2);
                            $pdf->Line(207.5, $y+0.5,207.5,$y+2);
                        } else if($segundo==9) {
                            $pdf->Line(206, $y+1.5,207.5,$y+2);
                            $pdf->Line(207.5, $y+0.5,207.5,$y+2);
                        }
                    }
                    else {
                        $pdf->Line($x1, $y1,207.5,$y+2);
                        $x1=207.5;
                        $y1=$y+2;
                        if($ultimo==2) {
                            if($penultimo==1) {
                                $pdf->Line(206, $y+3,207.5,$y+2);
                                $pdf->Line(206, $y+1,207.5,$y+2);
                            }
                            if($penultimo==3) {
                                $pdf->Line(209, $y+3,207.5,$y+2);
                                $pdf->Line(209, $y+1,207.5,$y+2);
                            }
                            if($penultimo==4) {
                                $pdf->Line(206.5, $y+3.5,207.5,$y+2);
                                $pdf->Line(205.5, $y+2.5,207.5,$y+2);
                            }
                            if($penultimo==5 or $penultimo==8) {
                                $pdf->Line(206.5, $y+3.5,207.5,$y+2);
                                $pdf->Line(208.5, $y+3.5,207.5,$y+2);
                            }
                            if($penultimo==6) {
                                $pdf->Line(208, $y+3.5,207.5,$y+2);
                                $pdf->Line(209, $y+2,207.5,$y+2);
                            }
                            if($penultimo==7) {
                                $pdf->Line(208, $y+3.5,207.5,$y+2);
                                $pdf->Line(205.5, $y+2.5,207.5,$y+2);
                            }
                            if($penultimo==9) {
                                $pdf->Line(207.5, $y+3.5,207.5,$y+2);
                                $pdf->Line(209, $y+2,207.5,$y+2);
                            }
                        }
                    }
                } else if($numero_patron==3) {
                    if($primero==3) {
                        $x1=211.5;
                        $y1=$y+2;
                        if($segundo==1 or $segundo==2) {
                            $pdf->Line(213, $y+3,211.5,$y+2);
                            $pdf->Line(213, $y+1,211.5,$y+2);
                        } else if($segundo==4) {
                            $pdf->Line(212.5, $y+0.5,211.5,$y+2);
                            $pdf->Line(213.5, $y+2 ,211.5,$y+2);
                        } else if($segundo==5 or $segundo==7) {
                            $pdf->Line(212, $y+0.5,211.5,$y+2);
                            $pdf->Line(213, $y+2 ,211.5,$y+2);
                        } else if($segundo==6 or $segundo==9) {
                            $pdf->Line(210.5, $y+0.5,211.5,$y+2);
                            $pdf->Line(212.5, $y+0.5,211.5,$y+2);
                        } else if($segundo==8) {
                            $pdf->Line(211.5, $y,211.5,$y+2);
                            $pdf->Line(213, $y+1 ,211.5,$y+2);
                        }
                    }
                    else {
                        $pdf->Line($x1, $y1,211.5,$y+2);
                        $x1=211.5;
                        $y1=$y+2;
                        if($ultimo==3) {
                            if($penultimo==1 or $penultimo==2) {
                                $pdf->Line(210, $y+3,211.5,$y+2);
                                $pdf->Line(210, $y+1,211.5,$y+2);
                            }
                            if($penultimo==4) {
                                $pdf->Line(210, $y+1.5,211.5,$y+2);
                                $pdf->Line(210.5, $y+3.5,211.5,$y+2);
                            }
                            if($penultimo==5 or $penultimo==7) {
                                $pdf->Line(210.5, $y+3.5,211.5,$y+2);
                                $pdf->Line(209+.5, $y+2.5,211.5,$y+2);
                            }
                            if($penultimo==6 or $penultimo==9) {
                                $pdf->Line(210.5, $y+3.5,211.5,$y+2);
                                $pdf->Line(212.5, $y+3.5,211.5,$y+2);
                            }
                            if($penultimo==8) {
                                $pdf->Line(210, $y+2.5,211.5,$y+2);
                                $pdf->Line(211.5, $y+3.5,211.5,$y+2);
                            }
                        }
                    }
                } else if($numero_patron==4) {
                    if($primero==4) {
                        $x1=203.5;
                        $y1=$y+5;
                        if($segundo==1) {
                            $pdf->Line(202.5, $y+6.5,203.5,$y+5);
                            $pdf->Line(204.5, $y+6.5,203.5,$y+5);
                        } else if($segundo==2) {
                            $pdf->Line(203, $y+6.5,203.5,$y+5);
                            $pdf->Line(202, $y+5,203.5,$y+5);
                        } else if($segundo==3) {
                            $pdf->Line(202.5, $y+6,203.5,$y+5);
                            $pdf->Line(202, $y+4.5,203.5,$y+5);
                        } else if($segundo==5 or $segundo==6) {
                            $pdf->Line(202, $y+6,203.5,$y+5);
                            $pdf->Line(202, $y+4,203.5,$y+5);
                        } else if($segundo==7) {
                            $pdf->Line(202.5, $y+3.5,203.5,$y+5);
                            $pdf->Line(204.5, $y+3.5,203.5,$y+5);
                        } else if($segundo==8) {
                            $pdf->Line(203, $y+3.5,203.5,$y+5);
                            $pdf->Line(202, $y+5 ,203.5,$y+5);
                        } else if($segundo==9) {
                            $pdf->Line(202, $y+3.5,203.5,$y+5);
                            $pdf->Line(201.5, $y+5 ,203.5,$y+5);
                        }
                    }
                    else {
                        $pdf->Line($x1, $y1,203.5,$y+5);
                        $x1=203.5;
                        $y1=$y+5;
                        if($ultimo==4) {
                            if($penultimo==1) {
                                $pdf->Line(202.5, $y+3.5,203.5,$y+5);
                                $pdf->Line(204.5, $y+3.5,203.5,$y+5);
                            }
                            if($penultimo==2) {
                                $pdf->Line(205, $y+3,203.5,$y+5);
                                $pdf->Line(206, $y+4,203.5,$y+5);
                            }
                            if($penultimo==3) {
                                $pdf->Line(205.5, $y+5,203.5,$y+5);
                                $pdf->Line(205, $y+3.5,203.5,$y+5);
                            }
                            if($penultimo==5 or $penultimo==6) {
                                $pdf->Line(205, $y+6,203.5,$y+5);
                                $pdf->Line(205, $y+4,203.5,$y+5);
                            }
                            if($penultimo==7) {
                                $pdf->Line(202.5, $y+6.5,203.5,$y+5);
                                $pdf->Line(204+.5, $y+6.5,203.5,$y+5);
                            }
                            if($penultimo==8) {
                                $pdf->Line(205.5, $y+5.5,203.5,$y+5);
                                $pdf->Line(204, $y+6.5,203.5,$y+5);
                            }
                            if($penultimo==9) {
                                $pdf->Line(205, $y+6.5,203.5,$y+5);
                                $pdf->Line(205.5, $y+5,203.5,$y+5);
                            }
                        }
                    }
                }else if($numero_patron==5) {
                    if($primero==5) {
                        $x1=207.5;
                        $y1=$y+5;
                        if($segundo==1) {
                            $pdf->Line(208, $y+6.5,207.5,$y+5);
                            $pdf->Line(209, $y+5,207.5,$y+5);
                        } else if($segundo==2) {
                            $pdf->Line(206.5, $y+6.5,207.5,$y+5);
                            $pdf->Line(208.5, $y+6.5,207.5,$y+5);
                        } else if($segundo==3) {
                            $pdf->Line(207, $y+6.5,207.5,$y+5);
                            $pdf->Line(206, $y+5,207.5,$y+5);
                        } else if($segundo==4) {
                            $pdf->Line(209, $y+6,207.5,$y+5);
                            $pdf->Line(209, $y+4,207.5,$y+5);
                        } else if($segundo==6) {
                            $pdf->Line(206, $y+6,207.5,$y+5);
                            $pdf->Line(206, $y+4,207.5,$y+5);
                        } else if($segundo==7) {
                            $pdf->Line(208, $y+3.5,207.5,$y+5);
                            $pdf->Line(209, $y+5 ,207.5,$y+5);
                        } else if($segundo==8) {
                            $pdf->Line(206.5, $y+3.5,207.5,$y+5);
                            $pdf->Line(208.5, $y+3.5,207.5,$y+5);
                        } else if($segundo==9) {
                            $pdf->Line(207, $y+3.5,207.5,$y+5);
                            $pdf->Line(206, $y+5 ,207.5,$y+5);
                        }
                    }
                    else {
                        $pdf->Line($x1, $y1,207.5,$y+5);
                        $x1=207.5;
                        $y1=$y+5;
                        if($ultimo==5) {
                            if($penultimo==1) {
                                $pdf->Line(206.5, $y+3.5,207.5,$y+5);
                                $pdf->Line(205.5, $y+4.5 ,207.5,$y+5);
                            }
                            if($penultimo==2) {
                                $pdf->Line(208.5, $y+3.5,207.5,$y+5);
                                $pdf->Line(206.5, $y+3.5,207.5,$y+5);
                            }
                            if($penultimo==3) {
                                $pdf->Line(209, $y+3,207.5,$y+5);
                                $pdf->Line(210, $y+4,207.5,$y+5);
                            }
                            if($penultimo==4) {
                                $pdf->Line(205.5, $y+4,207.5,$y+5);
                                $pdf->Line(205.5, $y+6,207.5,$y+5);
                            }
                            if($penultimo==6) {
                                $pdf->Line(209.5, $y+4,207.5,$y+5);
                                $pdf->Line(209.5, $y+6,207.5,$y+5);
                            }
                            if($penultimo==7) {
                                $pdf->Line(205.5, $y+5.5,207.5,$y+5);
                                $pdf->Line(207, $y+6.5,207.5,$y+5);
                            }
                            if($penultimo==8) {
                                $pdf->Line(208.5, $y+6.5,207.5,$y+5);
                                $pdf->Line(206.5, $y+6.5,207.5,$y+5);
                            }
                            if($penultimo==9) {
                                $pdf->Line(209.5, $y+5.5,207.5,$y+5);
                                $pdf->Line(208, $y+6.5,207.5,$y+5);
                            }
                        }
                    }
                    
                } else if($numero_patron==6) {
                    if($primero==6) {
                        $x1=211.5;
                        $y1=$y+5;
                        if($segundo==1) {
                            $pdf->Line(212, $y+6.5,211.5,$y+5);
                            $pdf->Line(213, $y+4.5,211.5,$y+5);
                        } else if($segundo==2) {
                            $pdf->Line(212, $y+6.5,211.5,$y+5);
                            $pdf->Line(213, $y+5,211.5,$y+5);
                        } else if($segundo==3) {
                            $pdf->Line(210.5, $y+6.5,211.5,$y+5);
                            $pdf->Line(212.5, $y+6.5,211.5,$y+5);
                        } else if($segundo==4 or $segundo==5) {
                            $pdf->Line(213, $y+6,211.5,$y+5);
                            $pdf->Line(213, $y+4,211.5,$y+5);
                        } else if($segundo==7) {
                            $pdf->Line(212.5, $y+3.5,211.5,$y+5);
                            $pdf->Line(213, $y+5.5 ,211.5,$y+5);
                        } else if($segundo==8) {
                            $pdf->Line(212, $y+3.5,211.5,$y+5);
                            $pdf->Line(213, $y+5 ,211.5,$y+5);
                        } else if($segundo==9) {
                            $pdf->Line(210.5, $y+3.5,211.5,$y+5);
                            $pdf->Line(212.5, $y+3.5,211.5,$y+5);
                        }
                    }
                    else {
                        $pdf->Line($x1, $y1,211.5,$y+5);
                        $x1=211.5;
                        $y1=$y+5;
                        if($ultimo==6) {
                            if($penultimo==1) {
                                $pdf->Line(210, $y+3.5,211.5,$y+5);
                                $pdf->Line(209.5, $y+5 ,211.5,$y+5);
                            }
                            if($penultimo==2) {
                                $pdf->Line(210.5, $y+3.5,211.5,$y+5);
                                $pdf->Line(209.5, $y+4.5 ,211.5,$y+5);
                            }
                            if($penultimo==3) {
                                $pdf->Line(210.5, $y+3.5,211.5,$y+5);
                                $pdf->Line(212.5, $y+3.5,211.5,$y+5);
                            }
                            if($penultimo==4 or $penultimo==5) {
                                $pdf->Line(210, $y+6,211.5,$y+5);
                                $pdf->Line(210, $y+4,211.5,$y+5);
                            }
                            if($penultimo==7) {
                                $pdf->Line(209.5, $y+5,211.5,$y+5);
                                $pdf->Line(210.5, $y+6.5,211.5,$y+5);
                            }
                            if($penultimo==8) {
                                $pdf->Line(209.5, $y+5.5,211.5,$y+5);
                                $pdf->Line(211, $y+6.5,211.5,$y+5);
                            }
                            if($penultimo==9) {
                                $pdf->Line(210.5, $y+6.5,211.5,$y+5);
                                $pdf->Line(212.5, $y+6.5,211.5,$y+5);
                            }
                        }
                    }
                } else if($numero_patron==7) {
                    if($primero==7) {
                        $x1=203.5;
                        $y1=$y+8;
                        if($segundo==1 or $segundo==4) {
                            $pdf->Line(202.5, $y+9.5,203.5,$y+8);
                            $pdf->Line(204.5, $y+9.5,203.5,$y+8);
                        } else if($segundo==2) {
                            $pdf->Line(203.5, $y+9.5,203.5,$y+8);
                            $pdf->Line(202, $y+8.5,203.5,$y+8);
                        } else if($segundo==3 or $segundo==5) {
                            $pdf->Line(203, $y+9.5,203.5,$y+8);
                            $pdf->Line(202, $y+8,203.5,$y+8);
                        } else if($segundo==6) {
                            $pdf->Line(203, $y+9.5,203.5,$y+8);
                            $pdf->Line(202, $y+7.5,203.5,$y+8);
                        } else if($segundo==8 or $segundo==9) {
                            $pdf->Line(202, $y+9,203.5,$y+8);
                            $pdf->Line(202, $y+7,203.5,$y+8);
                        }
                    }
                    else {
                        $pdf->Line($x1, $y1,203.5,$y+8);
                        $x1=203.5;
                        $y1=$y+8;
                        if($ultimo==7) {
                            if($penultimo==1 or $penultimo==4) {
                                $pdf->Line(202.5, $y+6.5,203.5,$y+8);
                                $pdf->Line(204.5, $y+6.5,203.5,$y+8);
                            }
                            if($penultimo==2) {
                                $pdf->Line(204, $y+6,203.5,$y+8);
                                $pdf->Line(205.5, $y+7,203.5,$y+8);
                            }
                            if($penultimo==3 or $penultimo==5) {
                                $pdf->Line(205, $y+6,203.5,$y+8);
                                $pdf->Line(206, $y+7,203.5,$y+8);
                            }
                            if($penultimo==6) {
                                $pdf->Line(205.5, $y+8,203.5,$y+8);
                                $pdf->Line(205, $y+6.5,203.5,$y+8);
                            }
                            if($penultimo==8 or $penultimo==9) {
                                $pdf->Line(205.5, $y+7,203.5,$y+8);
                                $pdf->Line(205.5, $y+9,203.5,$y+8);
                            }
                        }
                    }
                } else if($numero_patron==8) {
                    if($primero==8) {
                        $x1=207.5;
                        $y1=$y+8;
                        if($segundo==1) {
                            $pdf->Line(207.5, $y+9.5,207.5,$y+8);
                            $pdf->Line(209, $y+9,207.5,$y+8);
                        } else if($segundo==2 or $segundo==5) {
                            $pdf->Line(206.5, $y+9.5,207.5,$y+8);
                            $pdf->Line(208.5, $y+9.5,207.5,$y+8);
                        } else if($segundo==3) {
                            $pdf->Line(207.5, $y+9.5,207.5,$y+8);
                            $pdf->Line(206, $y+8.5,207.5,$y+8);
                        } else if($segundo==4) {
                            $pdf->Line(208, $y+9.5,207.5,$y+8);
                            $pdf->Line(209, $y+8,207.5,$y+8);
                        } else if($segundo==6) {
                            $pdf->Line(207, $y+9.5,207.5,$y+8);
                            $pdf->Line(206, $y+8,207.5,$y+8);
                        } else if($segundo==7) {
                            $pdf->Line(209, $y+8.5,207.5,$y+8);
                            $pdf->Line(209, $y+7.5,207.5,$y+8);
                        } else if($segundo==9) {
                            $pdf->Line(206, $y+9,207.5,$y+8);
                            $pdf->Line(206, $y+7,207.5,$y+8);
                        }
                    }
                    else {
                        $pdf->Line($x1, $y1,207.5,$y+8);
                        $x1=207.5;
                        $y1=$y+8;
                        if($ultimo==8) {
                            if($penultimo==1) {
                                $pdf->Line(207.5, $y+6.5,207.5,$y+8);
                                $pdf->Line(206, $y+7.5 ,207.5,$y+8);
                            }
                            if($penultimo==2 or $penultimo==5) {
                                $pdf->Line(206.5, $y+6.5,207.5,$y+8);
                                $pdf->Line(208.5, $y+6.5,207.5,$y+8);
                            }
                            if($penultimo==3) {
                                $pdf->Line(208, $y+6,207.5,$y+8);
                                $pdf->Line(209.5, $y+7,207.5,$y+8);
                            }
                            if($penultimo==4) {
                                $pdf->Line(206.5, $y+6.5,207.5,$y+8);
                                $pdf->Line(205.5, $y+7.5 ,207.5,$y+8);
                            }
                            if($penultimo==6) {
                                $pdf->Line(209, $y+6,207.5,$y+8);
                                $pdf->Line(210, $y+7,207.5,$y+8);
                            }
                            if($penultimo==7) {
                                $pdf->Line(205.5, $y+9,207.5,$y+8);
                                $pdf->Line(205.5, $y+7,207.5,$y+8);
                            }
                            if($penultimo==9) {
                                $pdf->Line(209.5, $y+9,207.5,$y+8);
                                $pdf->Line(209.5, $y+7,207.5,$y+8);
                            }
                        }
                    }
                } else if($numero_patron==9) {
                    if($primero==9) {
                        $x1=211.5;
                        $y1=$y+8;
                        if($segundo==1 or $segundo==5) {
                            $pdf->Line(212, $y+9.5,211.5,$y+8);
                            $pdf->Line(213, $y+8,211.5,$y+8);
                        } else if($segundo==2) {
                            $pdf->Line(211.5, $y+9.5,211.5,$y+8);
                            $pdf->Line(213, $y+9,211.5,$y+8);
                        } else if($segundo==3 or $segundo==6) {
                            $pdf->Line(210.5, $y+9.5,211.5,$y+8);
                            $pdf->Line(212.5, $y+9.5,211.5,$y+8);
                        } else if($segundo==4) {
                            $pdf->Line(212.5, $y+9,211.5,$y+8);
                            $pdf->Line(213, $y+8,211.5,$y+8);
                        } else if($segundo==7 or $segundo==8 ) {
                            $pdf->Line(213, $y+8.5,211.5,$y+8);
                            $pdf->Line(213, $y+7.5,211.5,$y+8);
                        }
                    }
                    else {
                        $pdf->Line($x1, $y1,211.5,$y+8);
                        $x1=211.5;
                        $y1=$y+8;
                        if($ultimo==9) {
                            if($penultimo==1 or $penultimo==5) {
                                $pdf->Line(210.5, $y+6.5,211.5,$y+8);
                                $pdf->Line(209.5, $y+7.5 ,211.5,$y+8);
                            }
                            if($penultimo==2) {
                                $pdf->Line(211.5, $y+6.5,211.5,$y+8);
                                $pdf->Line(210, $y+7.5 ,211.5,$y+8);
                            }
                            if($penultimo==3 or $penultimo==6) {
                                $pdf->Line(210.5, $y+6.5,211.5,$y+8);
                                $pdf->Line(212.5, $y+6.5,211.5,$y+8);
                            }
                            if($penultimo==4) {
                                $pdf->Line(210.5, $y+6.5,211.5,$y+8);
                                $pdf->Line(209.5, $y+8.5,211.5,$y+8);
                            }
                            if($penultimo==7 or $penultimo==8) {
                                $pdf->Line(209.5, $y+9,211.5,$y+8);
                                $pdf->Line(209.5, $y+7,211.5,$y+8);
                            }
                        }
                    }
                }
            }
        }
        
        $n++;
        $y=$y+11;
        if($n==14) {
            $pdf->AddPage();
            $pdf->SetFont('Arial','B',8);
            $n=0;
            $y=10;
            $pdf->SetY($y);
            $pdf->Cell(20,5,'CASO',1,0,'C');
            $pdf->Cell(30,5,'FECHA',1,0,'C');
            $pdf->Cell(20,5,'DILIGENCIAS',1,0,'C');
            $pdf->Cell(40,5,'JUZGADO',1,0,'C');
            $pdf->Cell(60,5,'DETENIDO',1,0,'C');
            $pdf->Cell(100,5,'DIRECCION REGISTRO',1,0,'C');
            $pdf->Ln();
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(20,6,$ret['numero'].'_'.substr($ret['año'],0,2),1,0,'C');
            $pdf->Cell(30,6,$ret['fecha_alta_caso'],1,0,'C');
            if(isset($ret['id_diligencias'])) {
                $resultado_diligencias=mysqli_query($link, "select id_juzgado, numero, año from diligencias where id_diligencias=$ret[id_diligencias]");
                $ret_diligencias=mysqli_fetch_array($resultado_diligencias);
                $resultado_juzgado=mysqli_query($link, "select jurisdiccion, nombre, numero from juzgado where id_juzgado=$ret_diligencias[id_juzgado]");
                $ret_juzgado=mysqli_fetch_array($resultado_juzgado);
                $nombre=$ret_juzgado['nombre'];
                $trozos = explode(" ", $nombre);
                $acronimo="";
                for($i=0;$i<count($trozos);$i++) {
                    $acr=substr($trozos[$i], 0,1);
                    if(ctype_upper($acr)) {
                        $acronimo=$acronimo.$acr;
                    }
                }
                $pdf->Cell(20,6,$ret_diligencias['numero'].'/'.$ret_diligencias['año'],1,0,'C');
                $pdf->Cell(40,6,utf8_decode($acronimo.' '.$ret_juzgado['numero'].','.$ret_juzgado['jurisdiccion']),1,0,'C');
            }
            else {
                $pdf->Cell(20,6,'',1);
                $pdf->Cell(40,6,'',1);
            }
            
            if(mysqli_num_rows($resultado_sujeto)==0) {
                $id_sujeto=1;
                $pdf->Cell(60,6,'Sin Detenido',1,0,'C');
            }
            else {
                $id_sujeto=$line_sujeto['id_sujeto_activo'];
                $pdf->Cell(60,6,utf8_decode($line_sujeto['nombre'].' '.$line_sujeto['apellido1'].' '.$line_sujeto['apellido2']),1,0,'C');
            }
            $pdf->Cell(100,6,utf8_decode($ret_intervencion['direccion']),1,0,'C');
            $pdf->Ln();
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(20,8,'EVIDENCIA',1,0,'C');
            $pdf->Cell(30,8,'MARCA Y MODELO',1,0,'C');
            $pdf->Cell(60,8,'N/S - IMEI - ICC',1,0,'C');
            $pdf->SetFont('Arial','B',7);
            $pdf->Cell(15,8,'CAPACIDAD',1,0,'C');
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(20,5,'CLONADO',0,0,'C');
            $pdf->Cell(25,8,'SISTEMA',1,0,'C');
            $pdf->Cell(20,8,'EXTRACCION',1,0,'C');
            $pdf->Cell(15,8,'PATRON',1,0,'C');
            $pdf->Cell(15,8,'PIN',1,0,'C');
            $pdf->Cell(50,8,'OBSERVACIONES',1,0,'C');
            $pdf->SetXY(135,26);
            $pdf->Cell(10,3,'inicio',0,0,'C');
            $pdf->Cell(10,3,'fin',0,0,'C');
            $pdf->Ln();
            $y=29;
        }
        else {
            $pdf->Ln();
        }
    }
    while($n<14) {
        $pdf->SetY($y);
        $pdf->Cell(20,11,'',1,0,'C');
        $pdf->Cell(30,11,'',1,0,'C');
        $pdf->Cell(60,11,'',1,0,'C');
        $pdf->Cell(15,11,'',1,0,'C');
        $pdf->Cell(10,11,'',1,0,'C');
        $pdf->Cell(10,11,'',1,0,'C');
        $pdf->Cell(25,11,'',1,0,'C');
        $pdf->Cell(20,11,'',1,0,'C');
        $pdf->Cell(15,11,'',1,0,'C');
        $pdf->Cell(15,11,'',1,0,'C');
        $pdf->Cell(50,11,'',1,0,'C');
        $pdf->SetXY(128,$y+1);
        $pdf->SetFont('Arial','',7);
        $pdf->Cell(2,2,'',1,0,'C');
        $pdf->SetXY(132,$y+1);
        $pdf->Cell(2,2,'MB',0,0,'C');
        $pdf->SetXY(128,$y+4);
        $pdf->Cell(2,2,'',1,0,'C');
        $pdf->SetXY(132,$y+4);
        $pdf->Cell(2,2,'GB',0,0,'C');
        $pdf->SetXY(128,$y+7);
        $pdf->Cell(2,2,'',1,0,'C');
        $pdf->SetXY(132,$y+7);
        $pdf->Cell(2,2,'TB',0,0,'C');
        $pdf->SetXY(177,$y+1);
        $pdf->Cell(2,2,'',1,0,'C');
        $pdf->SetXY(155,$y+1);
        $pdf->Cell(2,2,'GUYMAGER',0,0,'L');
        $pdf->SetXY(177,$y+4);
        $pdf->Cell(2,2,'',1,0,'C');
        $pdf->SetXY(155,$y+4);
        $pdf->Cell(2,2,'CELEBRITE',0,0,'L');
        $pdf->SetXY(177,$y+7);
        $pdf->Cell(2,2,'',1,0,'C');
        $pdf->SetXY(155,$y+7);
        $pdf->Cell(2,2,'TABLEAU',0,0,'L');
        $pdf->SetXY(197,$y+1);
        $pdf->Cell(2,2,'',1,0,'C');
        $pdf->SetXY(181,$y+1);
        $pdf->Cell(2,2,'E.FISICA',0,0,'L');
        $pdf->SetXY(197,$y+4);
        $pdf->Cell(2,2,'',1,0,'C');
        $pdf->SetXY(181,$y+4);
        $pdf->Cell(2,2,'SIST.ARCH.',0,0,'L');
        $pdf->SetXY(197,$y+7);
        $pdf->Cell(2,2,'',1,0,'C');
        $pdf->SetXY(181,$y+7);
        $pdf->Cell(2,2,'E.LOGICA',0,0,'L');
        $pdf->Image('img/radio.jpg',202.5,$y+1,2);
        $pdf->Image('img/radio.jpg',206.5,$y+1,2);
        $pdf->Image('img/radio.jpg',210.5,$y+1,2);
        $pdf->Image('img/radio.jpg',202.5,$y+4,2);
        $pdf->Image('img/radio.jpg',206.5,$y+4,2);
        $pdf->Image('img/radio.jpg',210.5,$y+4,2);
        $pdf->Image('img/radio.jpg',202.5,$y+7,2);
        $pdf->Image('img/radio.jpg',206.5,$y+7,2);
        $pdf->Image('img/radio.jpg',210.5,$y+7,2);
        $n++;
        $y=$y+11;
        $pdf->Ln();
    }
}




$pdf->Output();

?>