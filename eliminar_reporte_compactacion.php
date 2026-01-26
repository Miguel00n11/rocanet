<?php

include("conexion.php");

if (!isset($_GET['id'])) {
    echo "<script>alert('id no recibido'); window.close();</script>";
    exit;
}
if (!isset($_GET['reporte'])) {
    echo "<script>alert('reporte no recibido'); window.close();</script>";
    exit;
}


$id = (int) $_GET['id'];
$reporte = $_GET['reporte'];
$conexion->begin_transaction();

try {

    // 1️⃣ Eliminar reportes compactacion
    $sql1 = "DELETE FROM reportes 
             WHERE id = ?";
    $stmt1 = $conexion->prepare($sql1);
    $stmt1->bind_param("i", $id);
    $stmt1->execute();
    $stmt1->close();

    // 2️⃣ Eliminar compactaciones  
    $sql = "DELETE FROM compactaciones WHERE id_reporte_compactacion = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

        // 3 Eliminar registros_compactacion_campo campo actualizado 
    $sql = "DELETE FROM registros_compactacion_campo WHERE exp = ? AND reporte = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $id, $reporte);
    $stmt->execute();
    $stmt->close();

            // 4 Eliminar registros_calas_campo 
    $sql = "DELETE FROM registros_calas_campo WHERE id_reporte_compactacion = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $conexion->commit();

    echo "
    <script>
        window.close();
    </script>
    ";
    exit;
} catch (Exception $e) {

    $conexion->rollback();
    echo "
    <script>
        alert('Error al eliminar');
        window.close();
    </script>
    ";
}
