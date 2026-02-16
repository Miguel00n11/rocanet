<?php
require_once __DIR__ . '/auth.php';
include("conexion.php");

// Obtener el ID del diseño a exportar
$id_pavimento = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_pavimento == 0) {
    echo "<script>alert('ID de diseño no válido'); window.close();</script>";
    exit;
}

// Consultar datos del diseño
$sql_pavimento = "SELECT * FROM pavimento_rigido WHERE id_pavimento_rigido = ?";
$stmt = $conexion->prepare($sql_pavimento);
$stmt->bind_param("i", $id_pavimento);
$stmt->execute();
$resultado_pavimento = $stmt->get_result();

if ($resultado_pavimento->num_rows == 0) {
    echo "<script>alert('Diseño no encontrado'); window.close();</script>";
    exit;
}

$pavimento = $resultado_pavimento->fetch_assoc();
$stmt->close();

// Consultar sondeos y estratos asociados
$sql_sondeos = "SELECT * FROM sondeos WHERE id_pavimento_rigido = ? ORDER BY numero_sondeo";
$stmt_sondeos = $conexion->prepare($sql_sondeos);
$stmt_sondeos->bind_param("i", $id_pavimento);
$stmt_sondeos->execute();
$resultado_sondeos = $stmt_sondeos->get_result();
$sondeos = $resultado_sondeos->fetch_all(MYSQLI_ASSOC);
$stmt_sondeos->close();

// Para cada sondeo, obtener sus estratos
foreach ($sondeos as &$sondeo) {
    $sql_estratos = "SELECT * FROM estratos WHERE id_sondeo = ? ORDER BY id_estrato";
    $stmt_estratos = $conexion->prepare($sql_estratos);
    $stmt_estratos->bind_param("i", $sondeo['id_sondeo']);
    $stmt_estratos->execute();
    $resultado_estratos = $stmt_estratos->get_result();
    $sondeo['estratos'] = $resultado_estratos->fetch_all(MYSQLI_ASSOC);
    $stmt_estratos->close();
}
unset($sondeo);

$conexion->close();

// Configurar encabezados para descarga de Excel
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="Diseño_Pavimento_' . $id_pavimento . '_' . date('Ymd_His') . '.xls"');
header('Pragma: no-cache');
header('Expires: 0');

// Iniciar salida HTML que Excel interpretará
echo "\xEF\xBB\xBF"; // BOM para UTF-8
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4a5568;
            color: white;
            font-weight: bold;
        }
        .section-header {
            background-color: #667eea;
            color: white;
            font-weight: bold;
            font-size: 14pt;
        }
        .subsection-header {
            background-color: #cbd5e0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>DISEÑO DE PAVIMENTO RÍGIDO</h1>
    <h2>ID: <?= htmlspecialchars($pavimento['id_pavimento_rigido']) ?></h2>
    <p><strong>Fecha de exportación:</strong> <?= date('d/m/Y H:i:s') ?></p>
    
    <br>
    
    <!-- INFORMACIÓN GENERAL -->
    <table>
        <tr>
            <th colspan="2" class="section-header">INFORMACIÓN GENERAL</th>
        </tr>
        <tr>
            <th style="width: 30%;">Proyecto</th>
            <td><?= htmlspecialchars($pavimento['obra'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Tipo de Pavimento</th>
            <td><?= htmlspecialchars($pavimento['tipo_pavimento'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Ubicación</th>
            <td><?= htmlspecialchars($pavimento['ubicacion'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Cliente</th>
            <td><?= htmlspecialchars($pavimento['cliente'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Expediente</th>
            <td><?= htmlspecialchars($pavimento['expediente'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Fecha de Estudio</th>
            <td><?= htmlspecialchars($pavimento['fecha_estudio'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Fecha de Registro</th>
            <td><?= htmlspecialchars($pavimento['fecha_registro'] ?? '') ?></td>
        </tr>
    </table>
    
    <br><br>
    
    <!-- CARACTERÍSTICAS DEL PAVIMENTO -->
    <table>
        <tr>
            <th colspan="4" class="section-header">CARACTERÍSTICAS DEL PAVIMENTO</th>
        </tr>
        <tr>
            <th style="width: 25%;">Espesor Concreto</th>
            <td style="width: 25%;"><?= htmlspecialchars($pavimento['espesor_concreto'] ?? '') ?></td>
            <th style="width: 25%;">Base</th>
            <td style="width: 25%;"><?= htmlspecialchars($pavimento['base'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Subrasante</th>
            <td><?= htmlspecialchars($pavimento['subrasante'] ?? '') ?></td>
            <th>Pedraplen</th>
            <td><?= htmlspecialchars($pavimento['pedraplen'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Municipio</th>
            <td><?= htmlspecialchars($pavimento['municipio'] ?? '') ?></td>
            <th>Po</th>
            <td><?= htmlspecialchars($pavimento['po'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Pt</th>
            <td><?= htmlspecialchars($pavimento['pt'] ?? '') ?></td>
            <th>Zr (Confiabilidad)</th>
            <td><?= htmlspecialchars($pavimento['zr'] ?? '') ?> %</td>
        </tr>
        <tr>
            <th>So</th>
            <td><?= htmlspecialchars($pavimento['so'] ?? '') ?></td>
            <th>Esal's</th>
            <td><?= htmlspecialchars($pavimento['esals'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Coeficiente de Carga</th>
            <td><?= htmlspecialchars($pavimento['coeficiente_carga'] ?? '') ?></td>
            <th>MR (Módulo de Ruptura)</th>
            <td><?= htmlspecialchars($pavimento['mr'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Cd (Coef. Drenaje)</th>
            <td><?= htmlspecialchars($pavimento['cd'] ?? '') ?></td>
            <th>K infinito</th>
            <td><?= htmlspecialchars($pavimento['k_infinito'] ?? '') ?></td>
        </tr>
        <tr>
            <th>K tabla</th>
            <td><?= htmlspecialchars($pavimento['k_tabla'] ?? '') ?></td>
            <th>Ancho de Vialidad</th>
            <td><?= htmlspecialchars($pavimento['ancho_vialidad'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Número de Franjas</th>
            <td><?= htmlspecialchars($pavimento['numero_franjas'] ?? '') ?></td>
            <th>Dimensión en X</th>
            <td><?= htmlspecialchars($pavimento['dimension_x'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Dimensión en Y</th>
            <td><?= htmlspecialchars($pavimento['dimension_y'] ?? '') ?></td>
            <th>Diámetro de Pasajuntas</th>
            <td><?= htmlspecialchars($pavimento['diametro_pasajuntas'] ?? '') ?> "</td>
        </tr>
        <tr>
            <th>Diámetro Barras de Amarre</th>
            <td><?= htmlspecialchars($pavimento['diametro_barras_amarre'] ?? '') ?> "</td>
            <th></th>
            <td></td>
        </tr>
    </table>
    
    <br><br>
    
    <!-- SONDEOS -->
    <?php if (count($sondeos) > 0): ?>
        <table>
            <tr>
                <th colspan="3" class="section-header">SONDEOS</th>
            </tr>
            <?php foreach ($sondeos as $sondeo): ?>
                <tr>
                    <th colspan="3" class="subsection-header">Sondeo #<?= htmlspecialchars($sondeo['numero_sondeo']) ?></th>
                </tr>
                <tr>
                    <th style="width: 25%;">Espesores</th>
                    <th style="width: 75%;" colspan="2">Descripción</th>
                </tr>
                <?php if (count($sondeo['estratos']) > 0): ?>
                    <?php foreach ($sondeo['estratos'] as $estrato): ?>
                        <tr>
                            <td><?= htmlspecialchars($estrato['espesores']) ?></td>
                            <td colspan="2"><?= htmlspecialchars($estrato['descripcion']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">No hay estratos registrados</td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <table>
            <tr>
                <th class="section-header">SONDEOS</th>
            </tr>
            <tr>
                <td style="text-align: center;">No hay sondeos registrados</td>
            </tr>
        </table>
    <?php endif; ?>
    
</body>
</html>
