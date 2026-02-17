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
$sqlObra = "SELECT o.obra, o.cliente, c.cliente as nombre_cliente, o.localizacion 
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

// Conectar a la base de datos ali3d_rocanet
$conexion_rocanet = new mysqli("localhost", "root", "", "ali3d_rocanet");
if ($conexion_rocanet->connect_error) {
    die("Error de conexión a ali3d_rocanet: " . $conexion_rocanet->connect_error);
}
$conexion_rocanet->set_charset("utf8");

// Consultar ensayes del expediente
$sqlEnsayes = "SELECT * FROM ensayes WHERE expediente = ? ORDER BY fecha DESC, id DESC";
$stmtEnsayes = $conexion_rocanet->prepare($sqlEnsayes);
$stmtEnsayes->bind_param("i", $expediente);
$stmtEnsayes->execute();
$resultEnsayes = $stmtEnsayes->get_result();
$ensayes = $resultEnsayes->fetch_all(MYSQLI_ASSOC);
$stmtEnsayes->close();

$conexion_rocanet->close();
$conexion->close();
?>

<style>
    .info-header {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .table-responsive {
        overflow-x: auto;
    }
    @media print {
        .no-print {
            display: none;
        }
    }
</style>

<!-- BEGIN #content -->
<div id="content" class="app-content">
    <ul class="breadcrumb no-print">
        <li class="breadcrumb-item"><a href="obras.php">Obras</a></li>
        <li class="breadcrumb-item active">Consultar cuenta</li>
    </ul>

    <h1 class="page-header">
        Consultar cuenta - Expediente <?= $expediente ?>
    </h1>

    <div class="card">
        <div class="card-header with-btn">
            INFORMACIÓN DE LA OBRA
            <div class="card-header-btn no-print">
                <button onclick="window.print()" class="btn btn-sm btn-success">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <button onclick="window.close()" class="btn btn-sm btn-secondary">
                    <i class="fas fa-times"></i> Cerrar
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
                <div class="col-xl-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Obra</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($obra['obra']) ?>" readonly>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Localización</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($obra['localizacion']) ?>" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header with-btn">
            ENSAYES REGISTRADOS
            <div class="card-header-btn">
                <!-- <a href="#" data-toggle="card-collapse" class="btn"><iconify-icon icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
                <a href="#" data-toggle="card-expand" class="btn"><iconify-icon icon="material-symbols-light:fullscreen"></iconify-icon></a>
                <a href="#" data-toggle="card-remove" class="btn"><iconify-icon icon="material-symbols-light:close-rounded"></iconify-icon></a> -->
            </div>
        </div>
        <div class="card-body">
            <?php if (count($ensayes) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="tablaEnsayes">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Fecha</th>
                                <th class="text-center">Ensaye</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">P.U.</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center none">Observaciones</th>
                                <th class="text-center none">ID Precio</th>
                                <th class="text-center none">ID Factura</th>
                                <th class="text-center">Editar</th>
                                <th class="text-center">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            foreach ($ensayes as $ensaye): 
                                $cantidad = floatval($ensaye['cantidad']);
                                $pu = floatval($ensaye['pu']);
                                $subtotal = $cantidad * $pu;
                                $total += $subtotal;
                            ?>
                                <tr>
                                    <td class="text-center"><?= htmlspecialchars($ensaye['id']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($ensaye['fecha']) ?></td>
                                    <td><?= htmlspecialchars($ensaye['ensaye']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($ensaye['cantidad']) ?></td>
                                    <td class="text-end">$<?= number_format($pu, 2) ?></td>
                                    <td class="text-end">$<?= number_format($subtotal, 2) ?></td>
                                    <td><?= htmlspecialchars($ensaye['observaciones']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($ensaye['id_precio'] ?? '-') ?></td>
                                    <td class="text-center"><?= htmlspecialchars($ensaye['id_factura'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <a href='editar_ensaye.php?id=<?= $ensaye['id'] ?>&expediente=<?= $expediente ?>' 
                                           class='btn btn-outline-theme btn-sm w-80px'>
                                            Editar
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href='eliminar_ensaye.php?id=<?= $ensaye['id'] ?>&expediente=<?= $expediente ?>' 
                                           class='btn btn-outline-danger btn-sm w-80px'
                                           onclick='return confirm("¿Está seguro de eliminar este ensaye? Esta acción no se puede deshacer.");'>
                                            Eliminar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-info">
                                <td colspan="5" class="text-end"><strong>TOTAL:</strong></td>
                                <td class="text-end"><strong>$<?= number_format($total, 2) ?></strong></td>
                                <td colspan="5"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-3">
                    <p><strong>Total de registros:</strong> <?= count($ensayes) ?></p>
                    <p><strong>Total general:</strong> $<?= number_format($total, 2) ?></p>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No hay ensayes registrados para este expediente.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- END #content -->

<?php include("pie.php"); ?>

<script>
$(document).ready(function() {
    <?php if (count($ensayes) > 0): ?>
    $('#tablaEnsayes').DataTable({
        responsive: true,
        language: {
            "decimal": "",
            "emptyTable": "No hay datos disponibles en la tabla",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "No se encontraron registros coincidentes",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "aria": {
                "sortAscending": ": activar para ordenar la columna ascendente",
                "sortDescending": ": activar para ordenar la columna descendente"
            }
        },
        order: [[1, 'desc']], // Ordenar por fecha descendente
        pageLength: 25,
        columnDefs: [
            { responsivePriority: 1, targets: 0 }, // ID
            { responsivePriority: 2, targets: 1 }, // Fecha
            { responsivePriority: 3, targets: 2 }, // Ensaye
            { responsivePriority: 4, targets: 9 }, // Editar
            { responsivePriority: 5, targets: 10 } // Eliminar
        ]
    });
    <?php endif; ?>
});

</script>
