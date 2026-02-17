<?php
require_once __DIR__ . '/auth.php';
header('Content-Type: application/json');

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

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

$sqlDelete = "DELETE FROM ensayes WHERE id = ?";
$stmt = $conexion_rocanet->prepare($sqlDelete);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Eliminado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}

$stmt->close();
$conexion_rocanet->close();
?>
