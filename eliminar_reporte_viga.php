<?php
include("conexion.php");

if (!isset($_GET['item1'])) {
    echo "<script>alert('item1 no recibido'); window.close();</script>";
    exit;
}

$item1 = (int) $_GET['item1'];

$conexion->begin_transaction();

try {

    // 1️⃣ Eliminar registros vigas
    $sql1 = "DELETE FROM vigas 
             WHERE item1 = ?";
    $stmt1 = $conexion->prepare($sql1);
    $stmt1->bind_param("i", $item1);
    $stmt1->execute();
    $stmt1->close();

    // 2️⃣ Eliminar reporte campo actualizado vigas
    $sql = "DELETE FROM registros_vigas_campo_actualizado WHERE id_especimen1 = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $item1);
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
