<?php
require_once __DIR__ . '/auth.php';
include("conexion.php");

if (isset($_GET['id'])) {
    $id_pavimento = intval($_GET['id']);
    
    // Iniciar transacción
    $conexion->begin_transaction();
    
    try {
        // Eliminar el diseño de pavimento (los sondeos y estratos se eliminan automáticamente por CASCADE)
        $stmt_pavimento = $conexion->prepare("DELETE FROM pavimento_rigido WHERE id_pavimento_rigido = ?");
        $stmt_pavimento->bind_param("i", $id_pavimento);
        $stmt_pavimento->execute();
        $stmt_pavimento->close();
        
        // Confirmar transacción
        $conexion->commit();
        
        echo "<script>
            alert('Diseño de pavimento eliminado exitosamente');
            window.location.href = 'pavimento_rigido.php';
        </script>";
        
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conexion->rollback();
        echo "<script>
            alert('Error al eliminar el diseño: " . addslashes($e->getMessage()) . "');
            window.history.back();
        </script>";
    }
    
    $conexion->close();
} else {
    header("Location: pavimento_rigido.php");
    exit;
}
?>
