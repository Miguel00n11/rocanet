<?php
include("conexion.php");
$expediente = $_GET['expediente'] ?? '';
$sql = "SELECT IFNULL(MAX(reporte),0) FROM `reportes` WHERE expediente=$expediente;";
$res = $conexion->query($sql);

if ($res && $row = $res->fetch_assoc()) {
    echo json_encode([
        'ultimo_item' => intval($row['ultimo_item'])
    ]);
} else {
    echo json_encode([
        'ultimo_item' => 0
    ]);
}