<?php
require('fpdf.php');

class PDF extends FPDF
{

    function Header()
    {
        // Logo
        $this->Image('img/cabecera.png',6,5,210);
        // Arial bold 15
        /*$this->SetFont('Arial','B',15);
        // Movernos a la derecha
        $this->Cell(80);
        // Título
        $this->Cell(40,80,'ACTA DE CLONADO DE EFECTOS DIGITALES',0,0,'C');*/
        // Salto de línea
        $this->Ln(25);
    }
    
    // Pie de página
    function Footer()
    {
        // Posición: a 2,0 cm del final
        $this->SetY(-20);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Observaciones
        $this->SetX(10);
        
        $this->Cell(0,0,'CORREO ELECTRONICO:',0,2,'L');
        $this->Cell(0,5,utf8_decode('ciberterrorismo@dpg.mir.es'),0,0,'L');
        
        
        $this->SetX(90);
        // Número de página
        $this->Cell(0,0,utf8_decode('Página '.$this->PageNo().' de {nb}'));
        
        
        
        // Número de página
        $this->SetX(180);
        
        
        $this->Cell(0,0,utf8_decode('COMISARIA GENERAL DE INFORMACIÓN'),0,2,'R');
        $this->Cell(0,5,utf8_decode('C/ Julian Gonzalez Segador s/n'),0,2,'R');
        $this->Cell(0,0,'28080 - MADRID',0,2,'R');
        $this->Cell(0,5,'Telf: +34 XXX XXX XXX | +34 XXX XXX XXX',0,2,'R');
        $this->Cell(0,0,'Fax: +34 XXX XXX XXX',0,2,'R');
        
        
        
    }
    
function Justify($text, $w, $h)
{
    $tab_paragraphe = explode("\n", $text);
    $nb_paragraphe = count($tab_paragraphe);
    $j = 0;

    while ($j<$nb_paragraphe) {

        $paragraphe = $tab_paragraphe[$j];
        $tab_mot = explode(' ', $paragraphe);
        $nb_mot = count($tab_mot);

        // Handle strings longer than paragraph width
        $k=0;
        $l=0;
        while ($k<$nb_mot) {

            $len_mot = strlen ($tab_mot[$k]);
            if ($len_mot<($w-5) )
            {
                $tab_mot2[$l] = $tab_mot[$k];
                $l++;    
            } else {
                $m=0;
                $chaine_lettre='';
                while ($m<$len_mot) {

                    $lettre = substr($tab_mot[$k], $m, 1);
                    $len_chaine_lettre = $this->GetStringWidth($chaine_lettre.$lettre);

                    if ($len_chaine_lettre>($w-7)) {
                        $tab_mot2[$l] = $chaine_lettre . '-';
                        $chaine_lettre = $lettre;
                        $l++;
                    } else {
                        $chaine_lettre .= $lettre;
                    }
                    $m++;
                }
                if ($chaine_lettre) {
                    $tab_mot2[$l] = $chaine_lettre;
                    $l++;
                }

            }
            $k++;
        }

        // Justified lines
        $nb_mot = count($tab_mot2);
        $i=0;
        $ligne = '';
        while ($i<$nb_mot) {

            $mot = $tab_mot2[$i];
            $len_ligne = $this->GetStringWidth($ligne . ' ' . $mot);

            if ($len_ligne>($w-5)) {

                $len_ligne = $this->GetStringWidth($ligne);
                $nb_carac = strlen ($ligne);
                $ecart = (($w-2) - $len_ligne) / $nb_carac;
                $this->_out(sprintf('BT %.3F Tc ET',$ecart*$this->k));
                $this->MultiCell($w,$h,$ligne);
                $ligne = $mot;

            } else {

                if ($ligne)
                {
                    $ligne .= ' ' . $mot;
                } else {
                    $ligne = $mot;
                }

            }
            $i++;
        }

        // Last line
        $this->_out('BT 0 Tc ET');
        $this->MultiCell($w,$h,$ligne);
        $tab_mot = '';
        $tab_mot2 = '';
        $j++;
    }
}

// Le añade una especie de sangria al inicio del parrafo
function Justify_E($sep,$text, $w, $h)
{
    $tab_paragraphe = explode("\n", $text);
    $nb_paragraphe = count($tab_paragraphe);
    $j = 0;
    
    while ($j<$nb_paragraphe) {
        
        $paragraphe = $tab_paragraphe[$j];
        $tab_mot = explode(' ', $paragraphe);
        $nb_mot = count($tab_mot);
        
        // Handle strings longer than paragraph width
        $k=0;
        $l=0;
        while ($k<$nb_mot) {
            if ($sep==1 )
            {
                if ($k==0 )
                {
                    $tab_mot2[$l] = "           ";
                    $l++;
                    
                }
            }
            $len_mot = strlen ($tab_mot[$k]);
            if ($len_mot<($w-5) )
            {
                $tab_mot2[$l] = $tab_mot[$k];
                $l++;
            } else {
               $m=0;
                $chaine_lettre='';
                while ($m<$len_mot) {
                    
                    $lettre = substr($tab_mot[$k], $m, 1);
                    $len_chaine_lettre = $this->GetStringWidth($chaine_lettre.$lettre);
                    
                    if ($len_chaine_lettre>($w-7)) {
                        $tab_mot2[$l] = $chaine_lettre . '-';
                        $chaine_lettre = $lettre;
                        $l++;
                    } else {
                        $chaine_lettre .= $lettre;
                    }
                    $m++;
                }
                if ($chaine_lettre) {
                    $tab_mot2[$l] = $chaine_lettre;
                    $l++;
                }
                
            }
            $k++;
        }
        
        // Justified lines
        $nb_mot = count($tab_mot2);
        $i=0;
        $ligne = '';
        while ($i<$nb_mot) {
            
            $mot = $tab_mot2[$i];
            $len_ligne = $this->GetStringWidth($ligne . ' ' . $mot);
            
            if ($len_ligne>($w-5)) {
                
                $len_ligne = $this->GetStringWidth($ligne);
                $nb_carac = strlen ($ligne);
                $ecart = (($w-2) - $len_ligne) / $nb_carac;
                $this->_out(sprintf('BT %.3F Tc ET',$ecart*$this->k));
                $this->MultiCell($w,$h,$ligne);
                $ligne = $mot;
                
            } else {
                
                if ($ligne)
                {
                    $ligne .= ' ' . $mot;
                } else {
                    $ligne = $mot;
                }
                
            }
            $i++;
        }
        
        // Last line
        $this->_out('BT 0 Tc ET');
        $this->MultiCell($w,$h,$ligne);
        $tab_mot = '';
        $tab_mot2 = '';
        $j++;
    }
}

}
?>