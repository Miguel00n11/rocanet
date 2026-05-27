<?php
include 'cabeza.php';

// Procesar el formulario si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_diseno = $_POST['codigo_diseno'] ?? '';
    $ancho_viga = $_POST['ancho_viga'] ?? '';
    $altura_viga = $_POST['altura_viga'] ?? '';
    $fc = $_POST['fc'] ?? '';
    $fy = $_POST['fy'] ?? '';
    $recubrimiento_inferior = $_POST['recubrimiento_inferior'] ?? '';
    $mu = $_POST['mu'] ?? '';

    // Aquí se pueden agregar cálculos para el diseño de la viga
    // Por ahora, solo mostrar los datos ingresados

    echo "<div class='container-fluid'>";
    echo "<h1 class='page-header'>Resultados del Diseño de Viga Simplemente Armada</h1>";
    echo "<div class='row'>";
    echo "<div class='col-md-12'>";
    echo "<div class='panel panel-default'>";
    echo "<div class='panel-heading'><h4 class='panel-title'>Datos Ingresados</h4></div>";
    echo "<div class='panel-body'>";
    echo "<p><strong>Código de Diseño:</strong> $codigo_diseno</p>";
    echo "<p><strong>Ancho de la Viga (b):</strong> $ancho_viga cm</p>";
    echo "<p><strong>Altura de la Viga (h):</strong> $altura_viga cm</p>";
    echo "<p><strong>f'c:</strong> $fc MPa</p>";
    echo "<p><strong>fy:</strong> $fy MPa</p>";
    echo "<p><strong>Recubrimiento Inferior:</strong> $recubrimiento_inferior cm</p>";
    echo "<p><strong>Mu:</strong> $mu kN·m</p>";
    echo "<p><em>Los cálculos del diseño se implementarán próximamente.</em></p>";
    echo "</div></div></div></div></div>";
} else {
    // Si no se envió el formulario, redirigir o mostrar error
    header('Location: diseno_viga_simplemente_armada.php');
    exit;
}

include 'pie.php';
?>