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
$tipo    = $_GET['tipo'] ?? 'Mecanicas';

if (empty($usuario) || empty($llave)) {
    die('Parámetros inválidos');
}

// Determinar la ruta según el tipo
if ($tipo === 'Respaldo') {
	$ruta = "Mecanicas/RespaldoMecanicas/$usuario/$llave";
} elseif ($tipo === 'Actualizado') {
	$ruta = "Mecanicas/ReporteActualizadoMecanicas/$usuario/$llave";
} else {
	$ruta = "Mecanicas/ReportesMecanicas/$usuario/$llave";
}

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
			font-size: 9px;
			margin: 15px;
		}
		table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 8px;
		}
		table td, table th {
			border: 1px solid #000;
			padding: 4px;
			font-size: 8px;
			vertical-align: middle;
		}
		.header-gray {
			background-color: #b0b0b0;
			font-weight: bold;
			text-align: center;
		}
		.section-title {
			background-color: #d0d0d0;
			font-weight: bold;
			text-align: center;
			padding: 4px;
		}
		.label-cell {
			background-color: #d0d0d0;
			font-weight: bold;
		}
		.center {
			text-align: center;
		}
		.small-text {
			font-size: 7px;
		}
	</style>
</head>
<body>
	<!-- HEADER -->
	<table>
		<tr>
			<td rowspan="5" style="width: 30%; background-color: white; text-align: center; vertical-align: middle;">
				<?php
				$logoPath = __DIR__ . '/assets/img/logo_roca.png';
				if (file_exists($logoPath)) {
					$logoData = file_get_contents($logoPath);
					$logoBase64 = base64_encode($logoData);
					echo '<img src="data:image/png;base64,' . $logoBase64 . '" style="max-width: 90%; height: auto; max-height: 70px;">';
				} else {
					echo '<strong style="font-size: 16px; color: #4a7fb8;">ROCA</strong><br>';
					echo '<span class="small-text" style="color: #666;">Laboratorio<br>Control de Calidad</span>';
				}
				?>
			</td>
			<td colspan="3" class="header-gray" style="padding: 2px;">Datos de control</td>
		</tr>
		<tr>
			<td colspan="3" style="text-align: center; padding: 2px;">Nombre del formato</td>
		</tr>
		<tr>
			<td colspan="3" style="text-align: center; padding: 2px;"><strong>Reporte de muestreo</strong></td>
		</tr>
		<tr>
			<td class="header-gray" style="text-align: center; width: 23.33%; padding: 2px;">Código del formato</td>
			<td class="header-gray" style="text-align: center; width: 23.33%; padding: 2px;">Revisión</td>
			<td class="header-gray" style="text-align: center; width: 23.33%; padding: 2px;">Fecha de sondeo</td>
		</tr>
		<tr>
			<td style="text-align: center; padding: 2px;"><strong>F1-PR21</strong></td>
			<td style="text-align: center; padding: 2px;"><strong>00</strong></td>
			<td style="text-align: center; padding: 2px;"><strong><?= htmlspecialchars($fecha) ?></strong></td>
		</tr>
	</table>

	<!-- DATOS DE OBRA -->
	<table>
		<tr>
			<td colspan="4" class="section-title">Datos de obra</td>
		</tr>
		<tr>
			<td class="label-cell" style="width: 15%;">Cliente:</td>
			<td colspan="3"><?= htmlspecialchars($cliente) ?></td>
		</tr>
		<tr>
			<td class="label-cell">Obra:</td>
			<td colspan="3"><?= htmlspecialchars($obra) ?></td>
		</tr>
		<tr>
			<td class="label-cell">Localización:</td>
			<td colspan="3"><?= htmlspecialchars($localizacion) ?></td>
		</tr>
		<tr>
			<td class="label-cell">En atención:</td>
			<td style="width: 35%;"><?= htmlspecialchars($atencion) ?></td>
			<td class="label-cell" style="width: 15%;">Expediente:</td>
			<td style="width: 35%;"></td>
		</tr>
	</table>

	<!-- DATOS DEL SONDEO -->
	<table>
		<tr>
			<td colspan="4" class="section-title">Datos del sondeo</td>
		</tr>
		<tr>
			<td class="label-cell" style="width: 20%;">Sondeo Núm.:</td>
			<td class="center" style="width: 30%;"><?= htmlspecialchars($sondeo_num) ?></td>
			<td class="label-cell" style="width: 25%;">Profundidad del sondeo [cm]:</td>
			<td class="center" style="width: 25%;"><?= htmlspecialchars($profundidad_muestreo) ?></td>
		</tr>
		<tr>
			<td class="label-cell">Ubicación:</td>
			<td class="center"><?= htmlspecialchars($ubicacion) ?></td>
			<td class="label-cell">Hora de muestreo:</td>
			<td class="center"><?= htmlspecialchars($hora) ?></td>
		</tr>
		<tr>
			<td class="label-cell">NAF:</td>
			<td class="center"><?= $naf ? 'true' : 'false' ?></td>
			<td class="label-cell">Profundidad NAF [cm]:</td>
			<td class="center"><?= htmlspecialchars($profundidad_naf ?: '---') ?></td>
		</tr>
		<tr>
			<td class="label-cell">Latitud:</td>
			<td class="center"><?= htmlspecialchars($latitud) ?></td>
			<td class="label-cell">Longitud:</td>
			<td class="center"><?= htmlspecialchars($longitud) ?></td>
		</tr>
	</table>

	<!-- Datos del estrato muestreado -->
	<?php if (!empty($listaEstratos)): ?>
	<table>
		<tr>
			<td colspan="8" class="section-title">Datos del estrato muestreado</td>
		</tr>
		<tr class="header-gray">
			<th rowspan="2" class="center" style="width: 8%;">Numero de<br>estrato</th>
			<th rowspan="2" class="center" style="width: 12%;">Tipo de<br>muestreo</th>
			<th colspan="2" class="center">Profundidad [cm]</th>
			<th rowspan="2" class="center" style="width: 15%;">Profundidad del<br>muestreo [cm]</th>
			<th rowspan="2" class="center" style="width: 12%;">Espesor del<br>estrato [cm]</th>
			<th rowspan="2" class="center" style="width: 15%;">Clasificación<br>visual</th>
			<th rowspan="2" class="center" style="width: 20%;">Observaciones</th>
		</tr>
		<tr class="header-gray">
			<th class="center" style="width: 9%;">Inicio</th>
			<th class="center" style="width: 9%;">Final</th>
		</tr>
		<?php foreach ($listaEstratos as $index => $estrato): 
			$espesor = $estrato['espesor_estrato'] ?? '';
			$inicioRaw = $estrato['profundidad_inicio'] ?? '';
			$finalRaw = $estrato['profundidad_final'] ?? '';
			if ($espesor === '' && $inicioRaw !== '' && $finalRaw !== '') {
				$inicio = floatval(str_replace(',', '.', $inicioRaw));
				$final = floatval(str_replace(',', '.', $finalRaw));
				$espesor = $final - $inicio;
			}
		?>
		<tr>
			<td class="center"><?= $index + 1 ?></td>
			<td class="center"><?= htmlspecialchars($estrato['tipo_muestreo'] ?? '') ?></td>
			<td class="center"><?= htmlspecialchars($estrato['profundidad_inicio'] ?? '') ?></td>
			<td class="center"><?= htmlspecialchars($estrato['profundidad_final'] ?? '') ?></td>
			<td class="center"><?= htmlspecialchars($estrato['profundidad_muestreo'] ?? '') ?></td>
			<td class="center"><?= $espesor === '' ? '' : htmlspecialchars($espesor) ?></td>
			<td class="center"><?= htmlspecialchars($estrato['clasificacion_visual'] ?? '') ?></td>
			<td><?= htmlspecialchars($estrato['observaciones'] ?? '') ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>

	<!-- IMÁGENES DEL SONDEO -->
	<?php if (!empty($listaImagenes)): ?>
	<table>
		<tr>
			<td colspan="3" class="section-title">Imágenes del sondeo</td>
		</tr>
		<tr>
			<?php 
			for ($i = 0; $i < 3; $i++): 
				if ($i < count($listaImagenes)):
					// Descargar imagen de Firebase
					$imageData = @file_get_contents($listaImagenes[$i]);
					if ($imageData !== false):
						$base64 = base64_encode($imageData);
						// Detectar tipo de imagen por extensión o asumir JPEG
						$ext = strtolower(pathinfo($listaImagenes[$i], PATHINFO_EXTENSION));
						$mimeType = 'image/jpeg';
						if ($ext == 'png') $mimeType = 'image/png';
						if ($ext == 'gif') $mimeType = 'image/gif';
						if ($ext == 'webp') $mimeType = 'image/webp';
			?>
			<td class="center" style="width: 33.33%; padding: 5px;">
				<img src="data:<?= $mimeType ?>;base64,<?= $base64 ?>" style="max-width: 100%; max-height: 140px;">
			</td>
			<?php 
					else:
			?>
			<td class="center" style="width: 33.33%; height: 150px; padding: 5px;">
				<div style="width: 100%; height: 140px; border: 1px dashed #999; display: flex; align-items: center; justify-content: center;">
					<span class="small-text" style="color: #666;">Error al cargar imagen</span>
				</div>
			</td>
			<?php 
					endif;
				else:
			?>
			<td style="width: 33.33%;"></td>
			<?php 
				endif;
			endfor; 
			?>
		</tr>
	</table>
	<?php endif; ?>

	<!-- FOOTER -->
	<table>
		<tr>
			<td colspan="2" class="center section-title">
				Norma de referencia: NMX-C-467-ONNCCE-2019, Métodos de muestreo
			</td>
		</tr>
		<tr>
			<td class="center" style="width: 50%; height: 40px; vertical-align: bottom;">
				<?= htmlspecialchars($personal) ?><br>
				<span class="small-text">Nombre de muestreador</span>
			</td>
			<td class="center" style="width: 50%; vertical-align: bottom;">
				<br>
				<span class="small-text">Nombre de quien autoriza</span>
			</td>
		</tr>
	</table>
</body>
</html>
<?php
$html = ob_get_clean();

// Generar PDF con Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('chroot', __DIR__);
$options->set('enable_php', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$filename = 'Mecanica_' . $sondeo_num . '_' . date('Ymd_His') . '.pdf';
$dompdf->stream($filename, ['Attachment' => true]);
exit;
