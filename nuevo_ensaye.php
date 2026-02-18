<?php
require_once __DIR__ . '/auth.php';
include("cabeza.php");
include("conexion.php");

$expediente = isset($_GET['expediente']) ? intval($_GET['expediente']) : 0;

if ($expediente == 0) {
    echo "<script>alert('Expediente no válido'); window.close();</script>";
    exit;
}

// Obtener información de la obra
$sqlObra = "SELECT o.obra, o.cliente, c.cliente as nombre_cliente 
            FROM obras o 
            LEFT JOIN clientes c ON o.cliente = c.idcliente 
            WHERE o.expediente = ?";
$stmtObra = $conexion->prepare($sqlObra);
$stmtObra->bind_param("i", $expediente);
$stmtObra->execute();
$resultObra = $stmtObra->get_result();
$obra = $resultObra->fetch_assoc();
$stmtObra->close();

if (!$obra) {
    echo "<script>alert('Obra no encontrada'); window.close();</script>";
    exit;
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conectar a la base de datos ali3d_rocanet
    $conexion_rocanet = new mysqli("localhost", "root", "", "ali3d_rocanet");
    if ($conexion_rocanet->connect_error) {
        die("Error de conexión a ali3d_rocanet: " . $conexion_rocanet->connect_error);
    }
    $conexion_rocanet->set_charset("utf8");
    
    $fecha = $_POST['fecha'];
    $ensaye = $_POST['ensaye'];
    $cantidad = $_POST['cantidad'];
    $pu = $_POST['pu'];
    $observaciones = $_POST['observaciones'];
    $id_precio = 1823; // Default
    
    $sqlInsert = "INSERT INTO ensayes (cliente, idcliente, obra, expediente, ensaye, cantidad, pu, fecha, observaciones, id_precio, id_factura) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)";
    $stmt = $conexion_rocanet->prepare($sqlInsert);
    $stmt->bind_param(
        "ssissssssi",
        $obra['nombre_cliente'],
        $obra['cliente'],
        $obra['obra'],
        $expediente,
        $ensaye,
        $cantidad,
        $pu,
        $fecha,
        $observaciones,
        $id_precio
    );
    
    if ($stmt->execute()) {
        $stmt->close();
        $conexion_rocanet->close();
        $conexion->close();
        echo "<script>
            alert('Ensaye creado correctamente');
            window.location.href = 'consultar_cuenta.php?expediente=$expediente';
        </script>";
        exit;
    } else {
        echo "<script>alert('Error al crear: " . addslashes($stmt->error) . "');</script>";
        $stmt->close();
    }
    $conexion_rocanet->close();
}

$conexion->close();

$ensayesList = [
    'Compactacion',
    'Cala Adicional',
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

<!-- BEGIN #content -->
<div id="content" class="app-content">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="consultar_cuenta.php?expediente=<?= $expediente ?>">Consultar cuenta</a></li>
        <li class="breadcrumb-item active">Nuevo ensaye</li>
    </ul>

    <h1 class="page-header">
        Nuevo ensaye - Expediente <?= $expediente ?>
    </h1>

    <form method="POST">
        <div class="card">
            <div class="card-header with-btn">
                INFORMACIÓN DEL NUEVO ENSAYE
                <div class="card-header-btn">
                    <button type="button" onclick="window.location.href='consultar_cuenta.php?expediente=<?= $expediente ?>'" class="btn btn-sm btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </div>
            <div class="card-body pb-2">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Expediente</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($expediente) ?>" readonly>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Cliente</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($obra['nombre_cliente']) ?>" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xl-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Obra</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($obra['obra']) ?>" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fecha *</label>
                            <input type="date" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ensaye *</label>
                            <select class="form-select" name="ensaye" required>
                                <option value="">Seleccione un ensaye...</option>
                                <?php foreach ($ensayesList as $tipo): ?>
                                    <option value="<?= htmlspecialchars($tipo) ?>"><?= htmlspecialchars($tipo) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xl-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Cantidad *</label>
                            <input type="number" name="cantidad" class="form-control" value="1" min="0" step="1" required>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">P.U.</label>
                            <input type="number" name="pu" class="form-control" value="0" step="0.01">
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Subtotal</label>
                            <input type="text" class="form-control" value="$0.00" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xl-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="button" onclick="window.location.href='consultar_cuenta.php?expediente=<?= $expediente ?>'" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar ensaye
                </button>
            </div>
        </div>
    </form>
</div>
<!-- END #content -->

<?php include("pie.php"); ?>

<script>
// Calcular subtotal automáticamente
$(document).ready(function() {
    function calcularSubtotal() {
        var cantidad = parseFloat($('input[name="cantidad"]').val()) || 0;
        var pu = parseFloat($('input[name="pu"]').val()) || 0;
        var subtotal = cantidad * pu;
        $('input[readonly][value^="$"]').val('$' + subtotal.toFixed(2));
    }
    
    $('input[name="cantidad"], input[name="pu"]').on('input', calcularSubtotal);
});
</script>
