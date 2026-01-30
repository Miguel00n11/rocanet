<?php
require_once __DIR__ . '/auth.php';
include("conexion.php");

header('Content-Type: application/json');

$id_pavimento = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_pavimento == 0) {
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

// Primero obtener información del pavimento
$sql_pavimento = "SELECT cliente, ubicacion, expediente, fecha_estudio FROM pavimento_rigido WHERE id_pavimento_rigido = ?";
$stmt_pav = $conexion->prepare($sql_pavimento);
$stmt_pav->bind_param("i", $id_pavimento);
$stmt_pav->execute();
$resultado_pav = $stmt_pav->get_result();
$info_pavimento = $resultado_pav->fetch_assoc();
$stmt_pav->close();

// Consultar sondeos y estratos
$sql_sondeos = "SELECT * FROM sondeos WHERE id_pavimento_rigido = ? ORDER BY numero_sondeo";
$stmt = $conexion->prepare($sql_sondeos);
$stmt->bind_param("i", $id_pavimento);
$stmt->execute();
$resultado = $stmt->get_result();
$sondeos = [];

while ($sondeo = $resultado->fetch_assoc()) {
    // Para cada sondeo, obtener sus estratos
    $sql_estratos = "SELECT * FROM estratos WHERE id_sondeo = ? ORDER BY id_estrato";
    $stmt_estratos = $conexion->prepare($sql_estratos);
    $stmt_estratos->bind_param("i", $sondeo['id_sondeo']);
    $stmt_estratos->execute();
    $resultado_estratos = $stmt_estratos->get_result();
    
    $estratos = [];
    while ($estrato = $resultado_estratos->fetch_assoc()) {
        $estratos[] = $estrato;
    }
    $stmt_estratos->close();
    
    $sondeo['estratos'] = $estratos;
    $sondeos[] = $sondeo;
}

$stmt->close();
$conexion->close();

// Devolver datos con información del pavimento
echo json_encode([
    'pavimento' => $info_pavimento,
    'sondeos' => $sondeos
]);
?>
