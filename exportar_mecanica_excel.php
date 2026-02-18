<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/* FIREBASE CONFIG */
$firebaseURL = "https://registrocompactacioneroca-default-rtdb.firebaseio.com";
$auth = "64KDwSjgUkDpEMGcNryDylwJtGQX3XQsGbu4QxwI";

function firebaseGet($ruta)
{
	global $firebaseURL, $auth;
	$segmentos = explode('/', $ruta);
	$segmentos = array_map('rawurlencode', $segmentos);
	$rutaSegura = implode('/', $segmentos);
	$url = "$firebaseURL/$rutaSegura.json?auth=$auth";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	curl_close($ch);
	return json_decode($response, true);
}

/* PARAMETROS */
$usuario = $_GET['usuario'] ?? '';
$llave   = $_GET['llave'] ?? '';

if (empty($usuario) || empty($llave)) {
    die('Parámetros inválidos');
}

$ruta = "Mecanicas/ReportesMecanicas/$usuario/$llave";
$reporte = firebaseGet($ruta);

if ($reporte === null) {
	die("NO SE ENCONTRÓ EL REPORTE");
}

// Obtener las imágenes
$rutaImagenes = "ImagenesMecanicas/$usuario/$llave";
$imagenesData = firebaseGet($rutaImagenes);
$listaImagenes = [];

if ($imagenesData && is_array($imagenesData)) {
	foreach ($imagenesData as $key => $url) {
		if (is_string($url)) {
			$listaImagenes[] = $url;
		}
	}
}

/* VARIABLES DEL REPORTE */
$cliente = $reporte['cliente'] ?? '';
$obra = $reporte['obra'] ?? '';
$localizacion = $reporte['localizacion'] ?? '';
$fecha = $reporte['fecha'] ?? '';
$hora = $reporte['hora'] ?? '';
$atencion = $reporte['atencion'] ?? '';
$latitud = $reporte['latitud'] ?? '';
$longitud = $reporte['longitud'] ?? '';
$numeroReporte = $reporte['numeroReporte'] ?? '';
$sondeo_num = $reporte['sondeo_num'] ?? '';
$ubicacion = $reporte['ubicacion'] ?? '';
$naf = $reporte['naf'] ?? false;
$profundidad_naf = $reporte['profundidad_naf'] ?? '';
$profundidad_muestreo = $reporte['profundidad_muestreo'] ?? '';
$personal = $reporte['personal'] ?? '';
$listaEstratos = $reporte['listaEstratos'] ?? [];

// Crear Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Título
$sheet->setCellValue('A1', 'REPORTE DE MECÁNICA DE SUELOS');
$sheet->mergeCells('A1:D1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$row = 3;

// INFORMACIÓN GENERAL
$sheet->setCellValue("A$row", 'INFORMACIÓN GENERAL');
$sheet->getStyle("A$row")->getFont()->setBold(true)->setSize(14);
$row++;

$sheet->setCellValue("A$row", 'Llave:');
$sheet->setCellValue("B$row", $llave);
$sheet->getStyle("A$row")->getFont()->setBold(true);
$row++;

$sheet->setCellValue("A$row", 'Cliente:');
$sheet->setCellValue("B$row", $cliente);
$sheet->getStyle("A$row")->getFont()->setBold(true);
$row++;

$sheet->setCellValue("A$row", 'Obra:');
$sheet->setCellValue("B$row", $obra);
$sheet->getStyle("A$row")->getFont()->setBold(true);
$row++;

$sheet->setCellValue("A$row", 'Localización:');
$sheet->setCellValue("B$row", $localizacion);
$sheet->getStyle("A$row")->getFont()->setBold(true);
$row += 2;

// DATOS DEL SONDEO
$sheet->setCellValue("A$row", 'DATOS DEL SONDEO');
$sheet->getStyle("A$row")->getFont()->setBold(true)->setSize(14);
$row++;

$campos = [
	'Número de Reporte:' => $numeroReporte,
	'Fecha:' => $fecha,
	'Hora:' => $hora,
	'Atención:' => $atencion,
	'Latitud:' => $latitud,
	'Longitud:' => $longitud,
	'Número de Sondeo:' => $sondeo_num,
	'Ubicación:' => $ubicacion,
	'NAF:' => ($naf ? 'Sí' : 'No'),
	'Profundidad NAF:' => $profundidad_naf,
	'Profundidad de Muestreo:' => $profundidad_muestreo,
	'Personal:' => $personal
];

foreach ($campos as $campo => $valor) {
	$sheet->setCellValue("A$row", $campo);
	$sheet->setCellValue("B$row", $valor);
	$sheet->getStyle("A$row")->getFont()->setBold(true);
	$row++;
}

$row++;

// LISTA DE ESTRATOS
if (!empty($listaEstratos)) {
	$sheet->setCellValue("A$row", 'LISTA DE ESTRATOS');
	$sheet->getStyle("A$row")->getFont()->setBold(true)->setSize(14);
	$row++;

	// Encabezados
	$headers = ['#', 'Clasificación Visual', 'Prof. Inicio', 'Prof. Final', 'Prof. Muestreo', 'Tipo Muestreo', 'Observaciones'];
	$col = 'A';
	foreach ($headers as $header) {
		$sheet->setCellValue($col . $row, $header);
		$sheet->getStyle($col . $row)->getFont()->setBold(true);
		$sheet->getStyle($col . $row)->getFill()
			->setFillType(Fill::FILL_SOLID)
			->getStartColor()->setARGB('FFE0E0E0');
		$col++;
	}
	$row++;

	// Datos
	$index = 1;
	foreach ($listaEstratos as $estrato) {
		$sheet->setCellValue("A$row", $index++);
		$sheet->setCellValue("B$row", $estrato['clasificacion_visual'] ?? '');
		$sheet->setCellValue("C$row", $estrato['profundidad_inicio'] ?? '');
		$sheet->setCellValue("D$row", $estrato['profundidad_final'] ?? '');
		$sheet->setCellValue("E$row", $estrato['profundidad_muestreo'] ?? '');
		$sheet->setCellValue("F$row", $estrato['tipo_muestreo'] ?? '');
		$sheet->setCellValue("G$row", $estrato['observaciones'] ?? '');
		$row++;
	}
	$row++;
}

// IMÁGENES
if (!empty($listaImagenes)) {
	$sheet->setCellValue("A$row", 'IMÁGENES');
	$sheet->getStyle("A$row")->getFont()->setBold(true)->setSize(14);
	$row++;

	foreach ($listaImagenes as $index => $imagenUrl) {
		$sheet->setCellValue("A$row", 'Imagen ' . ($index + 1) . ':');
		$sheet->setCellValue("B$row", $imagenUrl);
		$sheet->getStyle("B$row")->getFont()->getColor()->setARGB('FF0000FF');
		$sheet->getStyle("B$row")->getFont()->setUnderline(true);
		$row++;
	}
}

// Ajustar ancho de columnas
$sheet->getColumnDimension('A')->setWidth(25);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(30);

// Generar archivo Excel
$filename = 'Mecanica_' . $sondeo_num . '_' . date('Ymd_His') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
