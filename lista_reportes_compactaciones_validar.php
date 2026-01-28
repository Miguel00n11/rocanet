<?php
require_once __DIR__ . '/auth.php';

include("cabeza.php");
include("conexion.php");

/* =========================
   FIREBASE CONFIG
========================= */
$firebaseURL = "https://registrocompactacioneroca-default-rtdb.firebaseio.com";
$auth = "64KDwSjgUkDpEMGcNryDylwJtGQX3XQsGbu4QxwI";

function firebaseGet($ruta)
{
	global $firebaseURL, $auth;
	$url = "$firebaseURL/$ruta.json?auth=$auth";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	curl_close($ch);
	return json_decode($response, true);
}

/* =========================
   OBTENER REPORTES NO VALIDADOS
========================= */
$rutaFirebase = "Compactaciones/Reportes";
$usuarios = firebaseGet($rutaFirebase);
$reportesPendientes = [];

if ($usuarios) {
	foreach ($usuarios as $usuario => $reportes) {
		if (!$reportes) continue;

		foreach ($reportes as $llave => $reporte) {
			if (!empty($reporte['validado'])) continue;

			$reporte['usuario'] = $usuario;
			$reporte['llave'] = $llave;
			$reportesPendientes[] = $reporte;
		}
	}
}
?>

<div id="content" class="app-content">
	<h1 class="page-header">Compactaciones pendientes de validar</h1>

	<div class="card">
		<div class="card-body">

			<div class="table-responsive">
				<table class="table table-bordered table-striped">
					<thead class="table-dark">
						<tr>
							<th>Personal</th>
							<th>Cliente</th>
							<th>Obra</th>
							<th>Fecha</th>
							<th>Cliente</th>
							<th>Acción</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($reportesPendientes): ?>
							<?php foreach ($reportesPendientes as $r): ?>
								<tr>
									<td><?= $r['personal'] ?? '' ?></td>
									<td><?= $r['cliente'] ?? '' ?></td>
									<td><?= $r['obra'] ?? '' ?></td>
									<td><?= $r['fecha'] ?? '' ?></td>
									<td><?= $r['cliente'] ?? '' ?></td>
									<td class="text-center">
										<a href="#"
											class="btn btn-success btn-sm"
											onclick="
		window.open('validar_compactaciones.php?usuario=<?= urlencode($r['usuario']) ?>&llave=<?= urlencode($r['llave']) ?>&atencion=<?= urlencode($r['atencion']) ?>&tipo=Compactaciones', '_blank');
		return false;
   ">
											Validar
										</a>

									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="6" class="text-center text-muted">
									No hay reportes pendientes
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

		</div>
	</div>
</div>
<script>
	function mostrarUsuario(btn) {

		const usuario = btn.getAttribute('data-usuario');
		const url = btn.getAttribute('data-url');

		const confirmar = confirm(
			"ANTES DE VALIDAR\n\n" +
			"USUARIO QUE VALIDA:\n" +
			usuario + "\n\n" +
			"¿Deseas continuar?"
		);

		if (confirmar) {
			window.open(url, '_blank');
		}

		return false; // evita navegación automática
	}
</script>


<?php include("pie.php"); ?>