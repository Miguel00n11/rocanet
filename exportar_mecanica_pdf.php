<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

/* FIREBASE CONFIG */
$firebaseURL = "https://registrocompactacioneroca-default-rtdb.firebaseio.com";
$auth = "64KDwSjgUkDpEMGcNryDylwJtGQX3XQsGbu4QxwI";

function firebaseGet($ruta)
{
	global $firebaseURL, $auth;
	$segmentos = explode('/', $ruta);
	$segmentos = array_map('rawurlencode', $segmentos);
	$rutaSegura = implode('/', $segmentos);
	$url = "$firebaseURL/$rutaSegura.json?auth=$auth";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	curl_close($ch);
	return json_decode($response, true);
}

/* PARAMETROS */
$usuario = $_GET['usuario'] ?? '';
$llave   = $_GET['llave'] ?? '';

if (empty($usuario) || empty($llave)) {
    die('Parámetros inválidos');
}

$ruta = "Mecanicas/ReportesMecanicas/$usuario/$llave";
$reporte = firebaseGet($ruta);

if ($reporte === null) {
	die("NO SE ENCONTRÓ EL REPORTE");
}

// Obtener las imágenes
$rutaImagenes = "ImagenesMecanicas/$usuario/$llave";
$imagenesData = firebaseGet($rutaImagenes);
$listaImagenes = [];

if ($imagenesData && is_array($imagenesData)) {
	foreach ($imagenesData as $key => $url) {
		if (is_string($url)) {
			$listaImagenes[] = $url;
		}
	}
}

/* VARIABLES DEL REPORTE */
$cliente = $reporte['cliente'] ?? '';
$obra = $reporte['obra'] ?? '';
$localizacion = $reporte['localizacion'] ?? '';
$fecha = $reporte['fecha'] ?? '';
$hora = $reporte['hora'] ?? '';
$atencion = $reporte['atencion'] ?? '';
$latitud = $reporte['latitud'] ?? '';
$longitud = $reporte['longitud'] ?? '';
$numeroReporte = $reporte['numeroReporte'] ?? '';
$sondeo_num = $reporte['sondeo_num'] ?? '';
$ubicacion = $reporte['ubicacion'] ?? '';
$naf = $reporte['naf'] ?? false;
$profundidad_naf = $reporte['profundidad_naf'] ?? '';
$profundidad_muestreo = $reporte['profundidad_muestreo'] ?? '';
$personal = $reporte['personal'] ?? '';
$listaEstratos = $reporte['listaEstratos'] ?? [];

// Generar HTML para PDF
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<style>
		body {
			font-family: Arial, sans-serif;
			font-size: 12px;
			line-height: 1.6;
		}
		h1 {
			text-align: center;
			font-size: 18px;
			margin-bottom: 20px;
		}
		h2 {
			font-size: 14px;
			margin-top: 20px;
			margin-bottom: 10px;
			border-bottom: 2px solid #333;
			padding-bottom: 5px;
		}
		table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 20px;
		}
		table th, table td {
			border: 1px solid #333;
			padding: 8px;
			text-align: left;
		}
		table th {
			background-color: #e0e0e0;
			font-weight: bold;
		}
		.info-table td:first-child {
			font-weight: bold;
			width: 35%;
		}
		.center {
			text-align: center;
		}
		.imagen-container {
			margin-bottom: 20px;
			page-break-inside: avoid;
		}
		.imagen-container img {
			max-width: 100%;
			height: auto;
			border: 1px solid #ccc;
		}
	</style>
</head>
<body>
	<h1>REPORTE DE MECÁNICA DE SUELOS</h1>

	<h2>INFORMACIÓN GENERAL</h2>
	<table class="info-table">
		<tr>
			<td>Llave:</td>
			<td><?= htmlspecialchars($llave) ?></td>
		</tr>
		<tr>
			<td>Cliente:</td>
			<td><?= htmlspecialchars($cliente) ?></td>
		</tr>
		<tr>
			<td>Obra:</td>
			<td><?= htmlspecialchars($obra) ?></td>
		</tr>
		<tr>
			<td>Localización:</td>
			<td><?= htmlspecialchars($localizacion) ?></td>
		</tr>
	</table>

	<h2>DATOS DEL SONDEO</h2>
	<table class="info-table">
		<tr>
			<td>Número de Reporte:</td>
			<td><?= htmlspecialchars($numeroReporte) ?></td>
		</tr>
		<tr>
			<td>Fecha:</td>
			<td><?= htmlspecialchars($fecha) ?></td>
		</tr>
		<tr>
			<td>Hora:</td>
			<td><?= htmlspecialchars($hora) ?></td>
		</tr>
		<tr>
			<td>Atención:</td>
			<td><?= htmlspecialchars($atencion) ?></td>
		</tr>
		<tr>
			<td>Latitud:</td>
			<td><?= htmlspecialchars($latitud) ?></td>
		</tr>
		<tr>
			<td>Longitud:</td>
			<td><?= htmlspecialchars($longitud) ?></td>
		</tr>
		<tr>
			<td>Número de Sondeo:</td>
			<td><?= htmlspecialchars($sondeo_num) ?></td>
		</tr>
		<tr>
			<td>Ubicación:</td>
			<td><?= htmlspecialchars($ubicacion) ?></td>
		</tr>
		<tr>
			<td>NAF:</td>
			<td><?= $naf ? 'Sí' : 'No' ?></td>
		</tr>
		<tr>
			<td>Profundidad NAF:</td>
			<td><?= htmlspecialchars($profundidad_naf) ?></td>
		</tr>
		<tr>
			<td>Profundidad de Muestreo:</td>
			<td><?= htmlspecialchars($profundidad_muestreo) ?></td>
		</tr>
		<tr>
			<td>Personal:</td>
			<td><?= htmlspecialchars($personal) ?></td>
		</tr>
	</table>

	<?php if (!empty($listaEstratos)): ?>
	<h2>LISTA DE ESTRATOS</h2>
	<table>
		<thead>
			<tr>
				<th class="center">#</th>
				<th>Clasificación Visual</th>
				<th>Prof. Inicio</th>
				<th>Prof. Final</th>
				<th>Prof. Muestreo</th>
				<th>Tipo Muestreo</th>
				<th>Observaciones</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($listaEstratos as $index => $estrato): ?>
			<tr>
				<td class="center"><?= $index + 1 ?></td>
				<td><?= htmlspecialchars($estrato['clasificacion_visual'] ?? '') ?></td>
				<td><?= htmlspecialchars($estrato['profundidad_inicio'] ?? '') ?></td>
				<td><?= htmlspecialchars($estrato['profundidad_final'] ?? '') ?></td>
				<td><?= htmlspecialchars($estrato['profundidad_muestreo'] ?? '') ?></td>
				<td><?= htmlspecialchars($estrato['tipo_muestreo'] ?? '') ?></td>
				<td><?= htmlspecialchars($estrato['observaciones'] ?? '') ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>

	<?php if (!empty($listaImagenes)): ?>
	<h2>IMÁGENES</h2>
	<?php foreach ($listaImagenes as $index => $imagenUrl): ?>
		<div class="imagen-container">
			<p><strong>Imagen <?= $index + 1 ?>:</strong></p>
			<?php
			// Intentar descargar la imagen y convertirla a base64
			$imageData = @file_get_contents($imagenUrl);
			if ($imageData !== false) {
				$base64 = base64_encode($imageData);
				// Detectar tipo MIME
				$finfo = new finfo(FILEINFO_MIME_TYPE);
				$mimeType = $finfo->buffer($imageData);
				if (!$mimeType) {
					$mimeType = 'image/jpeg';
				}
				echo '<img src="data:' . $mimeType . ';base64,' . $base64 . '">';
			} else {
				echo '<p style="color: #999;">No se pudo cargar la imagen desde: ' . htmlspecialchars($imagenUrl) . '</p>';
			}
			?>
		</div>
	<?php endforeach; ?>
	<?php endif; ?>
</body>
</html>
<?php
$html = ob_get_clean();

// Generar PDF con Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$filename = 'Mecanica_' . $sondeo_num . '_' . date('Ymd_His') . '.pdf';
$dompdf->stream($filename, ['Attachment' => true]);
exit;
