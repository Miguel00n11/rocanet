<?php
require_once __DIR__ . '/auth.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$expediente = isset($_GET['expediente']) ? intval($_GET['expediente']) : 0;

if ($id == 0) {
    echo "<script>alert('ID no válido'); window.close();</script>";
    exit;
}

// Conectar a la base de datos ali3d_rocanet
$conexion_rocanet = new mysqli("localhost", "root", "", "ali3d_rocanet");
if ($conexion_rocanet->connect_error) {
    die("Error de conexión a ali3d_rocanet: " . $conexion_rocanet->connect_error);
}
$conexion_rocanet->set_charset("utf8");

// Eliminar el registro
$sqlDelete = "DELETE FROM ensayes WHERE id = ?";
$stmt = $conexion_rocanet->prepare($sqlDelete);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    $conexion_rocanet->close();
    echo "<script>
        alert('Ensaye eliminado correctamente');
        window.location.href = 'consultar_cuenta.php?expediente=$expediente';
    </script>";
    exit;
} else {
    $stmt->close();
    $conexion_rocanet->close();
    echo "<script>
        alert('Error al eliminar: " . addslashes($stmt->error) . "');
        window.location.href = 'consultar_cuenta.php?expediente=$expediente';
    </script>";
    exit;
}
?>
