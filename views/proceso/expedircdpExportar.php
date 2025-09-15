<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
require_once "controllers/solicitudes.php";
require_once "vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

$obj = new solicitudes();
$datos = [
	'vigencia' => 0
];
$respuesta = $obj->getExportCDP($datos);

$excel = new Spreadsheet();
$hojaActiva = $excel->getActiveSheet();
$hojaActiva->setTitle("CDP");

//Fila 1
$hojaActiva->setCellValue('A1', 'TIPO DOCUMENTO');
$hojaActiva->setCellValue('B1', 'FECHA DOCUMENTO');
$hojaActiva->setCellValue('C1', 'FECHA RESERVA');
$hojaActiva->setCellValue('D1', 'CLASE DE DOCUMENTO');
$hojaActiva->setCellValue('E1', 'MONEDA');
$hojaActiva->setCellValue('F1', 'SOCIEDAD');
$hojaActiva->setCellValue('G1', 'TEXTO CABECERA');
$hojaActiva->setCellValue('H1', 'POSICION PRESUPUESTARIA');
$hojaActiva->setCellValue('I1', 'TEXTO POSICION');
$hojaActiva->setCellValue('J1', 'CENTRO GESTOR');
$hojaActiva->setCellValue('K1', 'FONDO');
$hojaActiva->setCellValue('L1', 'IMPORTE MONEDA DOC');
$hojaActiva->setCellValue('M1', 'IMPORTE MONEDA LOCAL');
$hojaActiva->setCellValue('N1', 'PERIODO');
$hojaActiva->setCellValue('O1', 'AREA FUNCIONAL');
$hojaActiva->setCellValue('P1', 'FECHA VENC');
$hojaActiva->setCellValue('Q1', 'CUENTA (MMTO E INVERSION)');
$hojaActiva->setCellValue('R1', 'ORDEN (MMTO)');
$hojaActiva->setCellValue('S1', 'PEP (INVERSION)');
$hojaActiva->setCellValue('T1', 'REFERENCIA');
$hojaActiva->setCellValue('U1', 'REFERENCIA 2 (DIAS)');
$hojaActiva->setCellValue('V1', 'REFERENCIA 3');

//Fila 2
$hojaActiva->setCellValue('A2', 'BLTPY');
$hojaActiva->setCellValue('B2', 'BLDAT');
$hojaActiva->setCellValue('C2', 'BUDAT');
$hojaActiva->setCellValue('D2', 'BLART');
$hojaActiva->setCellValue('E2', 'WAERS');
$hojaActiva->setCellValue('F2', 'BUKRS');
$hojaActiva->setCellValue('G2', 'KTEXT');
$hojaActiva->setCellValue('H2', 'FIPOS');
$hojaActiva->setCellValue('I2', 'TEXTO');
$hojaActiva->setCellValue('J2', 'FISTL');
$hojaActiva->setCellValue('K2', 'GEBER');
$hojaActiva->setCellValue('L2', 'WRBTR');
$hojaActiva->setCellValue('M2', 'DMBTR');
$hojaActiva->setCellValue('N2', 'BUDGET_PD');
$hojaActiva->setCellValue('O2', 'FKBER');
$hojaActiva->setCellValue('P2', 'FDATK');
$hojaActiva->setCellValue('Q2', 'SAKNR');
$hojaActiva->setCellValue('R2', 'AUFNR');
$hojaActiva->setCellValue('S2', 'PS_PSP_PNR');
$hojaActiva->setCellValue('T2', 'XBLNR');
$hojaActiva->setCellValue('U2', 'FMRE_XBLNR2');
$hojaActiva->setCellValue('V2', 'FMRE_XBLNR3');

$imputacion = [];
for($i = 0; $i < count($respuesta['data']); $i++){
	$hojaActiva->setCellValue('A'.($i+3), 30);
	$hojaActiva->setCellValue('B'.($i+3), date("d.m.Y"));
	$hojaActiva->setCellValue('C'.($i+3), date("d.m.Y"));
	$hojaActiva->setCellValue('D'.($i+3), 'PS');
	$hojaActiva->setCellValue('E'.($i+3), 'COP');
	$hojaActiva->setCellValue('F'.($i+3), 1000);
	$hojaActiva->setCellValue('G'.($i+3), $respuesta['data'][$i]['nombre']);
	$imputacion = explode('.', $respuesta['data'][$i]['imputacion']);	
	$hojaActiva->setCellValueExplicit('H'.($i+3), $imputacion[5].'.'.$imputacion[6],DataType::TYPE_STRING);
	$hojaActiva->setCellValue('I'.($i+3), $respuesta['data'][$i]['nombre']);
	$hojaActiva->setCellValue('J'.($i+3), $imputacion[0]);
	$hojaActiva->setCellValue('K'.($i+3), $imputacion[2]);
	$hojaActiva->setCellValue('L'.($i+3), $respuesta['data'][$i]['valor']);
	$hojaActiva->setCellValue('M'.($i+3), $respuesta['data'][$i]['valor']);
	$hojaActiva->setCellValue('N'.($i+3), 0);
	$hojaActiva->setCellValue('O'.($i+3), $imputacion[1]);
	$hojaActiva->setCellValue('P'.($i+3), date('d.m.Y', strtotime('+120 days')));
	$hojaActiva->setCellValue('Q'.($i+3), '');
	$hojaActiva->setCellValue('R'.($i+3), '');
	$hojaActiva->setCellValue('S'.($i+3), '');
	$hojaActiva->setCellValue('T'.($i+3), '074');
	$hojaActiva->setCellValue('U'.($i+3), '120');
	$hojaActiva->setCellValue('V'.($i+3), '');
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Solicitudes_CDP.xlsx"');
$writer = new Xlsx($excel);
$writer->save('php://output');