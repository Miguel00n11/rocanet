<?php
include("conexion.php");

if (!isset($_GET['id_reporte_concreto'])) {
	die("ID no válido");
}

$id = intval($_GET['id_reporte_concreto']);
$expediente = $_GET['expediente'] ?? '';

$conexion->begin_transaction();

try {

	$sql1 = "DELETE FROM reporte_concreto WHERE id_reporte_concreto = ?";
	$stmt1 = $conexion->prepare($sql1);
	$stmt1->bind_param("i", $id);
	$stmt1->execute();

	$sql2 = "DELETE FROM registros_concreto_campo_actualizado WHERE id_reporte_concreto = ?";
	$stmt2 = $conexion->prepare($sql2);
	$stmt2->bind_param("i", $id);
	$stmt2->execute();

	$conexion->commit();

	$stmt1->close();
	$stmt2->close();
	$conexion->close();

	header("Location: lista_reporte_cilindros.php?expediente=$expediente");
	exit;

} catch (Exception $e) {
	$conexion->rollback();
	die("Error al eliminar");
}
?>