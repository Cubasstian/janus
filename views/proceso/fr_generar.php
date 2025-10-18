<?php
require_once "controllers/procesos.php";
require_once "controllers/necesidadesImputaciones.php";
require_once __DIR__ . "/../../libs/pdf_emcali.php";
// Config PDF
$cfgPdf = [];
$cfgPath = __DIR__ . '/../../config/pdf.php';
if(file_exists($cfgPath)){
	$tmp = include $cfgPath;
	if(is_array($tmp)) $cfgPdf = $tmp;
}
session_start();

//Datos de la solicitud
$obj = new procesos();
$datos = [				
			'id' => $parametros[0]
		];
$proceso = $obj->getFR($datos);

/*print_r($proceso);
exit();*/
// Usar helper reutilizable con encabezado/pie estándar

$pdf = new PDF_Emcali();
$pdf->title = 'FICHA DE REQUERIMIENTO';
// Resolver ruta de logo relativa a este archivo
$logoPath = __DIR__.'/../../img/logoEmcali.png';
if(file_exists($logoPath)){
    $pdf->logoPath = $logoPath;
}
if(isset($_SESSION['usuario']['login'])){
	$pdf->showUserInFooter = true;
	$pdf->userLabel = (string)$_SESSION['usuario']['login'];
}
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',9);

// Sello de Aprobado (configurable)
if(isset($proceso['data'][0]['estado'])){
	$estado = (int)$proceso['data'][0]['estado'];
	$minEstado = isset($cfgPdf['stamps']['fr_aprobado_min_estado']) ? (int)$cfgPdf['stamps']['fr_aprobado_min_estado'] : 13;
	if($estado >= $minEstado){
		$box = isset($cfgPdf['stamps']['box']) ? $cfgPdf['stamps']['box'] : ['x'=>135,'y'=>20,'w'=>65,'h'=>18];
		$color = isset($cfgPdf['stamps']['color']) ? $cfgPdf['stamps']['color'] : ['r'=>0,'g'=>128,'b'=>0];
		$tcolor = isset($cfgPdf['stamps']['textColor']) ? $cfgPdf['stamps']['textColor'] : ['r'=>0,'g'=>100,'b'=>0];
		$title = isset($cfgPdf['stamps']['title']) ? (string)$cfgPdf['stamps']['title'] : 'APROBADO';
		$pdf->SetDrawColor($color['r'], $color['g'], $color['b']);
		$pdf->SetTextColor($tcolor['r'], $tcolor['g'], $tcolor['b']);
		$pdf->Rect($box['x'], $box['y'], $box['w'], $box['h']);
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY($box['x'], $box['y']+2);
		$pdf->Cell($box['w'],6,utf8_decode($title),0,2,'C');
		$pdf->SetFont('Arial','',8);
		$usr = isset($_SESSION['usuario']['login']) ? (string)$_SESSION['usuario']['login'] : '';
		$pdf->Cell($box['w'],4,utf8_decode('Fecha: ').date('Y-m-d'),0,2,'C');
		if($usr!=='') $pdf->Cell($box['w'],4,utf8_decode('Usuario: ').$usr,0,2,'C');
		$pdf->SetTextColor(0,0,0);
	}
}

// Encabezado (texto principal)
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

// Firmas y nota
$pdf->Ln(10);
$colW = 90; $rowH = 8;
$pdf->SetFont('Arial','',10);
$pdf->Cell($colW, $rowH, '', 'T', 0, 'C');
$pdf->Cell(10, $rowH, '');
$pdf->Cell($colW, $rowH, '', 'T', 1, 'C');
$pdf->SetFont('Arial','B',10);
$pdf->Cell($colW, 6, utf8_decode('Solicitante'), 0, 0, 'C');
$pdf->Cell(10, 6, '');
$pdf->Cell($colW, 6, utf8_decode('Vo. Bo. Gerencia'), 0, 1, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial','I',8);
$pdf->MultiCell(0,4,utf8_decode('Nota: La presente ficha de requerimiento sirve como insumo para la contratación y no constituye por sí misma una obligación contractual.'));

$pdf->Output();
?>
