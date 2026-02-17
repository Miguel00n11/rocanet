<?php
require_once __DIR__ . '/auth.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$expediente = isset($_GET['expediente']) ? intval($_GET['expediente']) : 0;

if ($id == 0) {
    echo "<div class='alert alert-danger'>ID no válido</div>";
    exit;
}

// Conectar a la base de datos ali3d_rocanet
$conexion_rocanet = new mysqli("localhost", "root", "", "ali3d_rocanet");
if ($conexion_rocanet->connect_error) {
    die("Error de conexión a ali3d_rocanet: " . $conexion_rocanet->connect_error);
}
$conexion_rocanet->set_charset("utf8");

// Obtener datos del ensaye
$sqlEnsaye = "SELECT * FROM ensayes WHERE id = ?";
$stmt = $conexion_rocanet->prepare($sqlEnsaye);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$ensaye = $result->fetch_assoc();
$stmt->close();

if (!$ensaye) {
    echo "<div class='alert alert-danger'>Ensaye no encontrado</div>";
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

$ensayesList = [
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
?>

<form id="formEditarEnsaye">
    <input type="hidden" name="id" value="<?= $ensaye['id'] ?>">
    <input type="hidden" name="expediente" value="<?= $ensaye['expediente'] ?>">
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Expediente *</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($ensaye['expediente']) ?>" readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Fecha *</label>
                <input type="date" name="fecha" class="form-control" value="<?= htmlspecialchars($ensaye['fecha']) ?>" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Obra</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($ensaye['obra']) ?>" readonly>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Ensaye *</label>
                <select name="ensaye" class="form-select" required>
                    <option value="">Seleccione un ensaye...</option>
                    <?php foreach ($ensayesList as $tipo): ?>
                        <option value="<?= htmlspecialchars($tipo) ?>" <?= $ensaye['ensaye'] === $tipo ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tipo) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label">Cantidad *</label>
                <input type="number" name="cantidad" class="form-control" value="<?= htmlspecialchars($ensaye['cantidad']) ?>" step="1" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label">P.U.</label>
                <input type="number" name="pu" class="form-control" value="<?= htmlspecialchars($ensaye['pu']) ?>" step="0.01">
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label">Subtotal</label>
                <input type="text" class="form-control" value="$<?= number_format($ensaye['cantidad'] * $ensaye['pu'], 2) ?>" readonly>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="3"><?= htmlspecialchars($ensaye['observaciones']) ?></textarea>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times"></i> Cancelar
        </button>
        <button type="button" class="btn btn-primary" onclick="guardarEdicion(); return false;">
            <i class="fas fa-save"></i> Guardar cambios
        </button>
    </div>
</form>
