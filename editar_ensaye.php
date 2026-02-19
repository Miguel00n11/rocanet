<?php
require_once __DIR__ . '/auth.php';
include("cabeza.php");

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

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'];
    $ensaye = $_POST['ensaye'];
    $cantidad = $_POST['cantidad'];
    $pu = $_POST['pu'];
    $observaciones = $_POST['observaciones'];
    
    $sqlUpdate = "UPDATE ensayes SET fecha = ?, ensaye = ?, cantidad = ?, pu = ?, observaciones = ? WHERE id = ?";
    $stmt = $conexion_rocanet->prepare($sqlUpdate);
    $stmt->bind_param("ssissi", $fecha, $ensaye, $cantidad, $pu, $observaciones, $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conexion_rocanet->close();
        echo "<script>
            alert('Ensaye actualizado correctamente');
            window.location.href = 'consultar_cuenta.php?expediente=$expediente';
        </script>";
        exit;
    } else {
        echo "<script>alert('Error al actualizar: " . addslashes($stmt->error) . "');</script>";
    }
    $stmt->close();
}

// Obtener datos del ensaye
$sqlEnsaye = "SELECT * FROM ensayes WHERE id = ?";
$stmt = $conexion_rocanet->prepare($sqlEnsaye);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$ensaye = $result->fetch_assoc();
$stmt->close();

if (!$ensaye) {
    echo "<script>alert('Ensaye no encontrado'); window.close();</script>";
    exit;
}

// Si el campo obra está vacío, obtenerlo de la base de datos principal
if (empty($ensaye['obra']) && !empty($ensaye['expediente'])) {
    include("conexion.php");
    $sqlObra = "SELECT obra FROM obras WHERE expediente = ?";
    $stmtObra = $conexion->prepare($sqlObra);
    $stmtObra->bind_param("i", $ensaye['expediente']);
    $stmtObra->execute();
    $resultObra = $stmtObra->get_result();
    if ($obraData = $resultObra->fetch_assoc()) {
        $ensaye['obra'] = $obraData['obra'];
    }
    $stmtObra->close();
    $conexion->close();
}

$conexion_rocanet->close();
?>

<!-- BEGIN #content -->
<div id="content" class="app-content">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" onclick="window.close()">Consultar cuenta</a></li>
        <li class="breadcrumb-item active">Editar ensaye</li>
    </ul>

    <h1 class="page-header">
        Editar ensaye #<?= $id ?>
    </h1>

    <form method="POST">
        <div class="card">
            <div class="card-header with-btn">
                EDITAR INFORMACIÓN DEL ENSAYE
                <div class="card-header-btn">
                    <button type="button" onclick="window.close()" class="btn btn-sm btn-secondary">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
            <div class="card-body pb-2">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fecha *</label>
                            <input type="date" class="form-control" name="fecha" value="<?= htmlspecialchars($ensaye['fecha']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ensaye *</label>
                            <select class="form-select" name="ensaye" required>
                                <?php 
                                $ensayeActual = htmlspecialchars($ensaye['ensaye']);
                                $ensayesDisponibles = [
                                    'Compactacion',
                                    'Caja Adicional',
                                    'Concreto (Cilindros)',
                                    'Revenimiento',
                                    'Vigas',
                                    'Mortero',
                                    'Prefabricado',
                                    'Calidad Terraceria',
                                    'Calidad Carpeta Asfaltica',
                                    'Calidad Emulsion',
                                    'Calidad Sello',
                                    'Tension Acero',
                                    'Mecanica de Suelos (Triaxial)',
                                    'Diseño de Pavimento',
                                    'Presencia en obra',
                                    'Visita cancelada insitu',
                                    'Descuento',
                                    'MONTO CONTRATADO'
                                ];
                                
                                // Si el ensaye actual no está en la lista, agregarlo
                                if (!in_array($ensayeActual, $ensayesDisponibles) && !empty($ensayeActual)) {
                                    array_unshift($ensayesDisponibles, $ensayeActual);
                                }
                                
                                foreach ($ensayesDisponibles as $opcion):
                                ?>
                                    <option value="<?= htmlspecialchars($opcion) ?>" <?= $opcion == $ensayeActual ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($opcion) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Cantidad *</label>
                            <input type="number" class="form-control" name="cantidad" value="<?= htmlspecialchars($ensaye['cantidad']) ?>" required min="0" step="1">
                        </div>
                    </div>
                    
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Precio Unitario (P.U.)</label>
                            <input type="number" class="form-control" name="pu" value="<?= htmlspecialchars($ensaye['pu']) ?>" step="0.01" min="0">
                        </div>
                    </div>
                    
                    <div class="col-xl-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Observaciones</label>
                            <textarea class="form-control" name="observaciones" rows="3"><?= htmlspecialchars($ensaye['observaciones']) ?></textarea>
                        </div>
                    </div>
                    
                    <div class="col-xl-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Cliente (solo lectura)</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($ensaye['cliente']) ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Expediente (solo lectura)</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($ensaye['expediente']) ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Obra (solo lectura)</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($ensaye['obra'] ?? 'Sin obra registrada') ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary btn-lg me-2">
                <i class="fas fa-save"></i> Guardar cambios
            </button>
            <button type="button" onclick="window.location.href='consultar_cuenta.php?expediente=<?= $expediente ?>'" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i> Cancelar
            </button>
        </div>
    </form>
</div>
<!-- END #content -->

<!-- DEBUG: Mostrar datos del ensaye -->
<?php if (isset($_GET['debug'])): ?>
    <script>
        console.log('Datos del ensaye:', <?= json_encode($ensaye) ?>);
    </script>
<?php endif; ?>

<?php include("pie.php"); ?>
