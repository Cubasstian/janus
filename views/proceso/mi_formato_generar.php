<?php
// Ejemplo de generador de PDF personalizado siguiendo el patrón del sistema
require_once "controllers/procesos.php";
require_once __DIR__ . "/../../libs/pdf_emcali.php";

session_start();

// Obtener datos del proceso
$obj = new procesos();
$idProceso = $parametros[0]; // ID viene por URL
$datos = ['id' => $idProceso];
$proceso = $obj->getProcesoDetalle($datos);

if(!$proceso['ejecuto'] || !count($proceso['data'])){
    echo "Error: Proceso no encontrado";
    exit;
}

$datosP = $proceso['data'][0];

// Crear PDF con la clase base del sistema
$pdf = new PDF_Emcali();
$pdf->title = 'MI FORMATO PERSONALIZADO';

// Logo
$logoPath = __DIR__.'/../../img/logoEmcali.png';
if(file_exists($logoPath)){
    $pdf->logoPath = $logoPath;
}

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);

// **AQUÍ IRÍA TU FORMATO CONVERTIDO DE EXCEL**

// Ejemplo de estructura típica:
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0, 8, utf8_decode('TÍTULO DEL DOCUMENTO'), 0, 1, 'C');
$pdf->Ln(5);

// Tabla de información
$pdf->SetFont('Arial','B',9);
$pdf->Cell(40, 6, utf8_decode('Campo 1:'), 1, 0, 'L');
$pdf->SetFont('Arial','',9);
$pdf->Cell(60, 6, utf8_decode($datosP['campo1'] ?? ''), 1, 0, 'L');
$pdf->SetFont('Arial','B',9);
$pdf->Cell(40, 6, utf8_decode('Campo 2:'), 1, 0, 'L');
$pdf->SetFont('Arial','',9);
$pdf->Cell(50, 6, utf8_decode($datosP['campo2'] ?? ''), 1, 1, 'L');

// Más campos según tu formato...

$pdf->Output();
?>
