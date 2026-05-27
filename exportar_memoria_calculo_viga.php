<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

include("conexion_estructural.php");

$id_viga = $_GET['id'] ?? '';

if (empty($id_viga)) {
    die('ID de viga no proporcionado');
}

// Obtener datos de la viga
$sql = "SELECT * FROM diseno_viga_simplemente_armada WHERE id_viga = $id_viga";
$resultado = $conexion->query($sql);

if (!$resultado || $resultado->num_rows === 0) {
    die('Viga no encontrada');
}

$viga = $resultado->fetch_assoc();

// Convertir unidades y datos
$b = floatval($viga['ancho_viga']);           // cm
$h = floatval($viga['altura_viga']);          // cm
$fc = floatval($viga['fc']);                  // kgf/cm²
$fy = floatval($viga['fy']);                  // kgf/cm²
$recubrimiento = floatval($viga['recubrimiento_inferior']); // cm
$mu = floatval($viga['mu']) * 100000;         // tonf·m a kgf·cm
$codigo = $viga['codigo_diseno'];             // Norma

// Calcular f''c (resistencia efectiva del concreto)
$fcc = 0.85 * $fc;                           // f''c = 0.85 × f'c

// Calcular d (distancia al acero) - Peralte efectivo
$d = $h - $recubrimiento; // Peralte total - recubrimiento

// Cálculos según la norma mexicana
// BETA 1 (depende de f'c)
if ($fc <= 280) {
    $beta1 = 0.85;
} elseif ($fc <= 350) {
    $beta1 = 0.80;
} elseif ($fc <= 420) {
    $beta1 = 0.75;
} else {
    $beta1 = 0.70;
}

// Cuantía de acero balanceado (ρb) - Usando f''c para unidades técnicas
$rho_b = $beta1 * $fcc / $fy * (6000 / (6000 + $fy));

// Cuantía máxima (75% de ρb)
$rho_max = 0.75 * $rho_b;

// Cuantía mínima
$rho_min = 0.7 * sqrt($fc) / $fy;

// Índice de refuerzo (fr = ρ * fy)
$fr_max = $rho_max * $fy;
$fr_min = $rho_min * $fy;

// Momento resistente MR (con factor de resistencia φ = 0.9 para flexión)
$phi = 0.9;
$mr = $phi * $rho_max * $fy * $b * pow($d, 2) * (1 - 0.59 * $rho_max * $fy / $fc);

// Crear el archivo Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Memoria de Cálculo');

// Estilos
$headerFill = new Fill();
$headerFill->setFillType(Fill::FILL_SOLID);
$headerFill->getStartColor()->setARGB('FF4472C4');

$headerFont = new Font();
$headerFont->setBold(true);
$headerFont->setSize(12);
$headerFont->setColor($headerFont = new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));

$subheaderFill = new Fill();
$subheaderFill->setFillType(Fill::FILL_SOLID);
$subheaderFill->getStartColor()->setARGB('FFD9E1F2');

$centerAlignment = new Alignment();
$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
$centerAlignment->setVertical(Alignment::VERTICAL_CENTER);

// Ancho de columnas
$sheet->getColumnDimension('A')->setWidth(35);
$sheet->getColumnDimension('B')->setWidth(20);

// TÍTULO
$sheet->mergeCells('A1:B1');
$cell = $sheet->getCell('A1');
$cell->setValue('MEMORIA DE CÁLCULO - DISEÑO DE VIGA SIMPLEMENTE ARMADA');
$cell->getStyle()->getFont()->setBold(true)->setSize(14);
$cell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->getRowDimension(1)->setRowHeight(25);

// SECCIÓN 1: DATOS DE ENTRADA
$row = 3;
$sheet->mergeCells("A$row:B$row");
$cell = $sheet->getCell("A$row");
$cell->setValue('1. DATOS DE ENTRADA');
$cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4472C4');
$cell->getStyle()->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
$row++;

// Datos de entrada
$datos = [
    'Código de Diseño' => $codigo,
    'Ancho de viga (b)' => "$b cm",
    'Altura de viga (h)' => "$h cm",
    'Recubrimiento' => "$recubrimiento cm",
    'Distancia al acero (d)' => number_format($d, 2) . " cm",
    "Resistencia del concreto (f'c)" => "$fc kgf/cm²",
    "Resistencia efectiva del concreto (f''c = 0.85×f'c)" => number_format($fcc, 2) . " kgf/cm²",
    "Resistencia del acero (fy)" => "$fy kgf/cm²",
    'Momento Último (Mu)' => number_format($mu, 2) . " kgf·cm",
];

foreach ($datos as $label => $valor) {
    $sheet->getCell("A$row")->setValue($label);
    $sheet->getCell("B$row")->setValue($valor);
    $sheet->getCell("A$row")->getStyle()->getFont()->setBold(true);
    $row++;
}

// SECCIÓN 2: CÁLCULOS
$row += 1;
$sheet->mergeCells("A$row:B$row");
$cell = $sheet->getCell("A$row");
$cell->setValue('2. CÁLCULOS');
$cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4472C4');
$cell->getStyle()->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
$row++;

// Cálculos
$calculos = [
    'β₁ (Beta 1)' => number_format($beta1, 4),
    'f\'\'c (Resistencia efectiva)' => number_format($fcc, 2) . " kgf/cm²",
    'ρb (Cuantía Balanceada)' => number_format($rho_b, 6) . ' = β1 × f\'\'c/fy × (6000/(6000+fy))',
    'ρmax (Cuantía Máxima)' => number_format($rho_max, 6),
    'ρmin (Cuantía Mínima)' => number_format($rho_min, 6),
    'fr máximo (Índice de Refuerzo)' => number_format($fr_max, 2) . " kgf/cm²",
    'fr mínimo (Índice de Refuerzo)' => number_format($fr_min, 2) . " kgf/cm²",
    'MR (Momento Resistente)' => number_format($mr, 2) . " kgf·cm",
];

foreach ($calculos as $label => $valor) {
    $sheet->getCell("A$row")->setValue($label);
    $sheet->getCell("B$row")->setValue($valor);
    $sheet->getCell("A$row")->getStyle()->getFont()->setBold(true);
    $sheet->getCell("B$row")->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    $row++;
}

// SECCIÓN 3: VERIFICACIÓN
$row += 1;
$sheet->mergeCells("A$row:B$row");
$cell = $sheet->getCell("A$row");
$cell->setValue('3. VERIFICACIÓN');
$cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4472C4');
$cell->getStyle()->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
$row++;

// Verificación
$verificacion_mr = ($mr >= $mu) ? 'CUMPLE' : 'NO CUMPLE';
$color_mr = ($mr >= $mu) ? 'FF00B050' : 'FFFF0000';

$sheet->getCell("A$row")->setValue('Mu vs MR');
$sheet->getCell("B$row")->setValue($verificacion_mr);
$sheet->getCell("B$row")->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($color_mr);
$sheet->getCell("B$row")->getStyle()->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
$row++;

$sheet->getCell("A$row")->setValue('MR requerido (Mu)');
$sheet->getCell("B$row")->setValue(number_format($mu, 2) . " kgf·cm");
$row++;

$sheet->getCell("A$row")->setValue('MR disponible (MR)');
$sheet->getCell("B$row")->setValue(number_format($mr, 2) . " kgf·cm");
$row++;

// SECCIÓN 4: NOTAS
$row += 1;
$sheet->mergeCells("A$row:B$row");
$cell = $sheet->getCell("A$row");
$cell->setValue('4. NOTAS Y REFERENCIAS');
$cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4472C4');
$cell->getStyle()->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
$row++;

$notas = [
    'Norma de diseño: ' . $codigo,
    'Factor de resistencia φ = 0.9 (Flexión)',
    'Unidades técnicas: f\'c y fy en kgf/cm²',
    'f\'\'c = 0.85 × f\'c (Resistencia efectiva del concreto)',
    'Fórmula ρb = β1 × f\'\'c/fy × (6000/(6000+fy)) para unidades técnicas',
    'Las fórmulas utilizadas están de acuerdo con la norma mexicana',
    'Este documento es una memoria de cálculo preliminar'
];

foreach ($notas as $nota) {
    $sheet->mergeCells("A$row:B$row");
    $sheet->getCell("A$row")->setValue($nota);
    $sheet->getCell("A$row")->getStyle()->getAlignment()->setWrapText(true);
    $sheet->getRowDimension($row)->setRowHeight(30);
    $row++;
}

// Generar archivo
$filename = 'Memoria_Calculo_Viga_' . $viga['codigo_diseno'] . '_' . date('Ymd_His') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>