<?php
require_once __DIR__ . '/auth.php';

include("conexion_estructural.php");

$id_viga = $_GET['id'] ?? '';

if (empty($id_viga)) {
    die('ID de viga no proporcionado');
}

// Eliminar de la base de datos
$sql = "DELETE FROM diseno_viga_simplemente_armada WHERE id_viga = $id_viga";

if ($conexion->query($sql) === TRUE) {
    header('Location: diseno_viga_simplemente_armada.php?msg=deleted');
    exit;
} else {
    die('Error al eliminar: ' . $conexion->error);
}
?>