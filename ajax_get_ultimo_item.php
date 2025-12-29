<?php
include("conexion.php");

$expediente = $_GET['expediente'] ?? '';

if ($expediente === '') {
    echo json_encode(['error' => 'Expediente vacío']);
    exit;
}

$sql = "
    SELECT MAX(i.item) AS ultimo_item
    FROM item i
    INNER JOIN reporte_concreto r 
        ON r.id_reporte_concreto = i.id_reporte_concreto
    WHERE r.expediente = '$expediente'
      AND r.meta_lab = 1
";

$res = $conexion->query($sql);
$row = $res->fetch_assoc();

echo json_encode([
    'ultimo_item' => (int)($row['ultimo_item'] ?? 0)
]);
