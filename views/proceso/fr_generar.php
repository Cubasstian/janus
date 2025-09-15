<?php
require_once "controllers/procesos.php";
require_once "controllers/necesidadesImputaciones.php";
require_once "vendor/fpdf186/fpdf.php";
session_start();

//Datos de la solicitud
$obj = new procesos();
$datos = [				
			'id' => $parametros[0]
		];
$proceso = $obj->getFR($datos);

/*print_r($proceso);
exit();*/
class PDF extends FPDF
{
    function Footer()
    {
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Print centered page number
        $this->Cell(40,5,utf8_decode('Generó:'));
		$this->Cell(90,5,$_SESSION['usuario']['login'],0,1);
		$this->Cell(40,3,utf8_decode('Fecha de generación:'));
		$this->Cell(90,3,date("Y-m-d H:i:s"),0,1);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',9);

//Encabezado
$pdf->Image('dist/img/logoEmcali.png', 10, 12, -200);
$pdf->Cell(20,20,'',1);
$pdf->Cell(170,6,'FICHA DE REQUERIMIENTO',1,1,'C');
$pdf->Cell(20,20,'',0);
$pdf->Cell(170,6,utf8_decode('PARA CONTRATOS DE PRESTACIÓN DE SERVICIOS PROFESIONALES Y DE APOYO A LA GESTÓN'),1,1,'C');
$pdf->Cell(20,20,'',0);
$pdf->Cell(85,8,utf8_decode('CÓDIGO: 404P02F002'),1,0,'C');
$pdf->Cell(85,8,utf8_decode('VERSIÓN: 2'),1,1,'C');

$pdf->Ln(2);

$pdf->SetFont('Arial','B',7);
$pdf->Cell(190,5,utf8_decode('INFORMACIÓN BASE DEL REQUERIMIENTO'),1,1,'C');

$pdf->SetFont('Arial','',6);
$pdf->Cell(30,5,'FECHA DE SOLICITUD',1,0,'C');
$pdf->Cell(40,5,'No. FICHA REQUERIMIENTO',1,0,'C');
$pdf->Cell(20,5,'No. SOLPED',1,0,'C');
$pdf->Cell(35,5,utf8_decode('NÚMERO DEL PROCESO'),1,0,'C');
$pdf->Cell(40,5,'QUIEN REALIZA LA SOLICITUD',1,0,'C');
$pdf->Cell(25,5,'CARGO',1,1,'C');

$pdf->Cell(30,5,date('d-m-Y'),1,0,'C');
$pdf->Cell(40,5,'FR-200-'.$proceso['data'][0]['consecutivo_fr'].'-'.date('Y'),1,0,'C');
$pdf->Cell(20,5,$proceso['data'][0]['solped'],1,0,'C');
$pdf->Cell(35,5,'200-IP-'.$proceso['data'][0]['consecutivo_ip'].'-'.date('Y'),1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(25,5,'',1,1,'C');

$pdf->Ln(2);

$pdf->Cell(30,5,'CENTRO COSTO',1,0,'C');
$pdf->Cell(40,5,utf8_decode('CATEGOIRA CÓDIGOS UNSPSC'),1,0,'C');
$pdf->Cell(20,5,utf8_decode('CÓDIGO PACC'),1,0,'C');
$pdf->Cell(35,5,utf8_decode('DESCRIPCIÓN EN EL PACC'),1,0,'C');
$pdf->Cell(65,5,utf8_decode('IDENTIFICACIÓN DEL OBJETIVO'),1,1,'C');

$pdf->Cell(30,5,$proceso['data'][0]['ceco'],1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(20,5,$proceso['data'][0]['pacc'],1,0,'C');
$pdf->Cell(35,5,'',1,0,'C');
$pdf->Cell(65,5,'',1,1,'C');

$pdf->Ln(2);

$pdf->SetFont('Arial','B',7);
$pdf->Cell(190,5,utf8_decode('INFORMACIÓN FINANCIERA'),1,1,'C');
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->SetFont('Arial','',6);
$pdf->MultiCell(60,4,utf8_decode("IMPUTACIÓN PRESUPUESTAL\n "),1,'C');
$pdf->SetXY($x + 60, $y);
$pdf->MultiCell(45,4,"No. CERITICADO DE\nDISPONIBILIDAD PRESUPUESTAL",1,'C');
$pdf->SetXY($x + 60 + 45, $y);
$pdf->MultiCell(45,4,"VALOR CERTIFICADO DE\nDISPONIBILIDAD PRESUPUESTAL",1,'C');
$pdf->SetXY($x + 60 + 45 + 45, $y);
$pdf->MultiCell(40,4,utf8_decode("VALOR DEL PRESUPUESTO\nPARA LA CONTRATACIÓN"),1,'C');

$pdf->Cell(60,4,'',1,0,'C');
$pdf->Cell(45,4,'',1,0,'C');
$pdf->Cell(45,4,'',1,0,'C');
$pdf->Cell(40,4,'',1,1,'C');

$pdf->Cell(60,4,'',1,0,'C');
$pdf->Cell(45,4,'',1,0,'C');
$pdf->Cell(45,4,'',1,0,'C');
$pdf->Cell(40,4,'',1,1,'C');

$pdf->Cell(60,4,'',1,0,'C');
$pdf->Cell(45,4,'',1,0,'C');
$pdf->Cell(45,4,'',1,0,'C');
$pdf->Cell(40,4,'',1,1,'C');

$pdf->Cell(105,4,'TOTAL',1,0,'R');
$pdf->Cell(45,4,'',1,0,'C');
$pdf->Cell(40,4,'',1,1,'C');

$pdf->Ln(2);

$pdf->SetFont('Arial','B',7);
$pdf->Cell(190,5,utf8_decode('INFORMACIÓN AMPLIADA DEL REQUERIMIENTO'),1,1,'C');
$pdf->SetFont('Arial','',6);
$pdf->Cell(190,5,utf8_decode('JUSTIFICACIÓN DE LA NECESIDAD'),1,1);
$pdf->MultiCell(190,3,utf8_decode($proceso['data'][0]['justificacion']),1,'J');

$pdf->Output();
?>