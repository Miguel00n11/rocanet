<?php
require_once __DIR__ . '/auth.php';
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $id_pavimento_rigido = $_POST['id_pavimento_rigido'] ?? 0;
    $obra = $_POST['obra'] ?? '';
    $tipo_pavimento = $_POST['tipo_pavimento'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';
    $cliente = $_POST['cliente'] ?? '';
    $expediente = $_POST['expediente'] ?? '';
    $fecha_estudio = $_POST['fecha_estudio'] ?? '';
    $espesor_concreto = $_POST['espesor_concreto'] ?? '';
    $base = $_POST['base'] ?? '';
    $subrasante = $_POST['subrasante'] ?? '';
    $pedraplen = $_POST['pedraplen'] ?? '';
    $municipio = $_POST['municipio'] ?? '';
    $po = $_POST['po'] ?? null;
    $pt = $_POST['pt'] ?? null;
    $zr = $_POST['zr'] ?? null;
    $so = $_POST['so'] ?? null;
    $esals = $_POST['esals'] ?? null;
    $coeficiente_carga = $_POST['coeficiente_carga'] ?? null;
    $mr = $_POST['mr'] ?? null;

    // Validar que se haya ingresado una obra y tipo
    if (empty($obra) || empty($tipo_pavimento) || $id_pavimento_rigido == 0) {
        echo "<script>alert('Datos inválidos'); window.history.back();</script>";
        exit;
    }

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // Actualizar el diseño de pavimento rígido
        $stmt = $conexion->prepare("UPDATE pavimento_rigido 
            SET obra = ?,
                tipo_pavimento = ?,
                ubicacion = ?,
                cliente = ?,
                expediente = ?,
                fecha_estudio = ?,
                espesor_concreto = ?, 
                base = ?, 
                subrasante = ?, 
                pedraplen = ?,
                municipio = ?,
                po = ?,
                pt = ?,
                zr = ?,
                so = ?,
                esals = ?,
                coeficiente_carga = ?,
                mr = ?
            WHERE id_pavimento_rigido = ?");
        
        $stmt->bind_param("sssssssssssdddddddi", 
            $obra,
            $tipo_pavimento,
            $ubicacion,
            $cliente,
            $expediente,
            $fecha_estudio,
            $espesor_concreto, 
            $base, 
            $subrasante, 
            $pedraplen,
            $municipio,
            $po,
            $pt,
            $zr,
            $so,
            $esals,
            $coeficiente_carga,
            $mr,
            $id_pavimento_rigido
        );

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar el diseño de pavimento");
        }
        $stmt->close();

        // Eliminar sondeos y estratos existentes para reinsertarlos
        $stmt_delete = $conexion->prepare("DELETE FROM sondeos WHERE id_pavimento_rigido = ?");
        $stmt_delete->bind_param("i", $id_pavimento_rigido);
        $stmt_delete->execute();
        $stmt_delete->close();

        // Insertar sondeos y estratos actualizados
        if (isset($_POST['sondeo']) && is_array($_POST['sondeo'])) {
            foreach ($_POST['sondeo'] as $numeroSondeo => $datosSondeo) {
                // Insertar el sondeo
                $stmt_sondeo = $conexion->prepare("INSERT INTO sondeos (id_pavimento_rigido, numero_sondeo) VALUES (?, ?)");
                $stmt_sondeo->bind_param("ii", $id_pavimento_rigido, $numeroSondeo);
                
                if (!$stmt_sondeo->execute()) {
                    throw new Exception("Error al insertar sondeo");
                }
                
                $id_sondeo = $conexion->insert_id;
                $stmt_sondeo->close();

                // Insertar estratos del sondeo
                if (isset($datosSondeo['espesores']) && is_array($datosSondeo['espesores'])) {
                    $espesores = $datosSondeo['espesores'];
                    $descripciones = $datosSondeo['descripcion'];

                    $stmt_estrato = $conexion->prepare("INSERT INTO estratos (id_sondeo, espesores, descripcion) VALUES (?, ?, ?)");

                    for ($i = 0; $i < count($espesores); $i++) {
                        $espesor = $espesores[$i] ?? '';
                        $descripcion = $descripciones[$i] ?? '';

                        // Solo insertar si al menos un campo tiene datos
                        if ($espesor || $descripcion) {
                            $stmt_estrato->bind_param("iss", $id_sondeo, $espesor, $descripcion);
                            
                            if (!$stmt_estrato->execute()) {
                                throw new Exception("Error al insertar estrato");
                            }
                        }
                    }

                    $stmt_estrato->close();
                }
            }
        }

        // Confirmar transacción
        $conexion->commit();

        // Redirigir a la lista con mensaje de éxito
        echo "<script>
            alert('Diseño de pavimento actualizado exitosamente');
            window.opener.location.reload();
            window.close();
        </script>";

    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conexion->rollback();
        echo "<script>
            alert('Error al actualizar: " . addslashes($e->getMessage()) . "');
            window.history.back();
        </script>";
    }

    $conexion->close();
} else {
    header("Location: pavimento_rigido.php");
    exit;
}
?>
