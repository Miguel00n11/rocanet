<?php
require_once __DIR__ . '/auth.php';
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $obra = $_POST['obra'] ?? null;
    $tipo_pavimento = $_POST['tipo_pavimento'] ?? null;
    $espesor_concreto = $_POST['espesor_concreto'] ?? '';
    $base = $_POST['base'] ?? '';
    $subrasante = $_POST['subrasante'] ?? '';
    $pedraplen = $_POST['pedraplen'] ?? '';

    // Validar que se haya ingresado una obra y tipo
    if (!$obra || !$tipo_pavimento) {
        echo "<script>alert('Debe ingresar el nombre de la obra y seleccionar el tipo de pavimento'); window.history.back();</script>";
        exit;
    }

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // Insertar el diseño de pavimento rígido
        $stmt = $conexion->prepare("INSERT INTO pavimento_rigido 
            (obra, tipo_pavimento, espesor_concreto, base, subrasante, pedraplen) 
            VALUES (?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("ssssss", 
            $obra,
            $tipo_pavimento, 
            $espesor_concreto, 
            $base, 
            $subrasante, 
            $pedraplen
        );

        if (!$stmt->execute()) {
            throw new Exception("Error al insertar el diseño de pavimento");
        }

        $id_pavimento_rigido = $conexion->insert_id;
        $stmt->close();

        // Insertar sondeos y sus estratos
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
            alert('Diseño de pavimento guardado exitosamente');
            window.location.href = 'pavimento_rigido.php';
        </script>";

    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conexion->rollback();
        echo "<script>
            alert('Error al guardar: " . addslashes($e->getMessage()) . "');
            window.history.back();
        </script>";
    }

    $conexion->close();
} else {
    header("Location: nuevo_pavimento_rigido.php");
    exit;
}
?>
