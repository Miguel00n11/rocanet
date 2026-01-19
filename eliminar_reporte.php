<?php
include("conexion.php");

if (!isset($_GET['id_reporte_concreto'])) {
    echo "<script>alert('ID no recibido'); window.close();</script>";
    exit;
}

$idReporte = (int) $_GET['id_reporte_concreto'];

$conexion->begin_transaction();

try {

    // // 1️⃣ Eliminar registros hijos
    // $sql1 = "DELETE FROM registros_concreto_campo_actualizado 
    //          WHERE id_reporte_concreto = ?";
    // $stmt1 = $conexion->prepare($sql1);
    // $stmt1->bind_param("i", $idReporte);
    // $stmt1->execute();
    // $stmt1->close();

    // 2️⃣ Eliminar reporte principal
    $sql = "DELETE FROM reporte_concreto WHERE id_reporte_concreto = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idReporte);
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
