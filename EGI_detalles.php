<?php // content="text/plain; charset=utf-8"
session_start();

$link = mysqli_connect("localhost", "root", ".google.", "safe");

if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}




if(isset($_POST["caso"])) {
    $caso=$_POST["caso"];
    echo $caso;
}
else {
    $caso="CACA";
}

if(isset($_POST["intervencion"])) {
    $intervencion= $_POST["intervencion"];
}
else {
    
    $intervencion="falllllllo0000ooooo";
}

if(isset($_POST["cabecera"])) {
    $cabecera= $_POST["cabecera"];

}


require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_bar.php');



$datay1=array(13,8,19,7,17,6);
$datay2=array(4,5,2,7,5,25);

// Create the graph.
$graph = new Graph(650,450);

$graph->SetScale('textlin');
$graph->SetMarginColor('white');


// Setup title
$graph->title->Set($caso);
//$graph->title->Set('Estadística General por Intervencion');

// Create the first bar
$bplot = new BarPlot($datay1);
$bplot->SetFillGradient('AntiqueWhite2','AntiqueWhite4:0.8',GRAD_VERT);
$bplot->SetColor('darkred');

// Create the second bar
$bplot2 = new BarPlot($datay2);
$bplot2->SetFillGradient('olivedrab1','olivedrab4',GRAD_VERT);
$bplot2->SetColor('darkgreen');

// And join them in an accumulated bar
$accbplot = new AccBarPlot(array($bplot,$bplot2));
$accbplot->SetColor('red');
$accbplot->SetWeight(1);
$graph->Add($accbplot);

/*
$image = "img/imagen1_EGI.jpg";
if(file_exists($image)){
    unlink($image);
}

$graph->img->Stream($image);
$graph->Stroke($image);
$contentType = 'image/png';

//echo "data:$contentType;base64;" . base64_encode($image);
echo $image;
*/

//$graph->Stroke();

$imagen= $graph->Stroke();

echo '<img src="data:image/png;' .($imagen).'">';
 

/*ob_start();                        // start buffering
$graph->img->Stream();             // print data to buffer
$image_data = ob_get_contents();   // retrieve buffer contents
ob_end_clean();                    // stop buffer
$contentType = 'image/png';

echo "data:$contentType;base64;" . base64_encode($image_data);

//<img src="data:image/png;base64,' + data + '" />*/


?>

  
