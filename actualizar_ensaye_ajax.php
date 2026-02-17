<?php
require_once __DIR__ . '/auth.php';
header('Content-Type: application/json');

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$fecha = $_POST['fecha'] ?? '';
$ensaye = $_POST['ensaye'] ?? '';
$cantidad = $_POST['cantidad'] ?? 0;
$pu = $_POST['pu'] ?? 0;
$observaciones = $_POST['observaciones'] ?? '';

if ($id == 0) {
    echo json_encode(['success' => false, 'message' => 'ID no válido']);
    exit;
}

// Conectar a la base de datos ali3d_rocanet
$conexion_rocanet = new mysqli("localhost", "root", "", "ali3d_rocanet");
if ($conexion_rocanet->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión']);
    exit;
}
$conexion_rocanet->set_charset("utf8");

$sqlUpdate = "UPDATE ensayes SET fecha = ?, ensaye = ?, cantidad = ?, pu = ?, observaciones = ? WHERE id = ?";
$stmt = $conexion_rocanet->prepare($sqlUpdate);
$stmt->bind_param("ssissi", $fecha, $ensaye, $cantidad, $pu, $observaciones, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Actualizado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}

$stmt->close();
$conexion_rocanet->close();
?>
