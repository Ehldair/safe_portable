<?php

session_start();

#imprimimos las variables que estas enviando para saber si estan llegando completas


$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

$longitud=$_SESSION['patron'];
$inicio=strlen($longitud)-4;
$fin=strlen($longitud)-2;
$x1='0';
$y1='0';
$x2='0';
$y2='0';

$numero1=substr($_SESSION['patron'],$inicio,1);
$numero2=substr($_SESSION['patron'],$fin,1);


if($numero1==1 or $numero2==1) {
    if($numero2!=1){
        $x1='085';
        $y1='010';
    }
    else {
        $x2='085';
        $y2='010';
    }
} 
if ($numero1==2 or $numero2==2) {
    if($numero2!=2){
        $x1='125';
        $y1='010';
    }
    else {
        $x2='125';
        $y2='010';
    }
}
if ($numero1==3 or $numero2==3) {
    if($numero2!=3){
        $x1='165';
        $y1='010';
    }
    else {
        $x2='165';
        $y2='010';
    }
} 
if ($numero1==4 or $numero2==4) {
    if($numero2!=4){
        $x1='085';
        $y1='035';
    }
    else {
        $x2='085';
        $y2='035';
    }
} 
if ($numero1==5 or $numero2==5) {
    if($numero2!=5){
        $x1='125';
        $y1='035';
    }
    else {
        $x2='125';
        $y2='035';
    }
} 
if ($numero1==6 or $numero2==6) {
    if($numero2!=6){
        $x1='165';
        $y1='035';
    }
    else {
        $x2='165';
        $y2='035';
    }
} 
if ($numero1==7 or $numero2==7) {
    if($numero2!=7){
        $x1='085';
        $y1='060';
    }
    else {
        $x2='085';
        $y2='060';
    }
} 
if ($numero1==8 or $numero2==8) {
    if($numero2!=8){
        $x1='125';
        $y1='060';
    }
    else {
        $x2='125';
        $y2='060';
    }
} 
if ($numero1==9 or $numero2==9) {
    if($numero2!=9){
        $x1='165';
        $y1='060';
    }
    else {
        $x2='165';
        $y2='060';
    }
}


echo $x1."-".$y1."-".$x2."-".$y2;





mysqli_close($link);

?>