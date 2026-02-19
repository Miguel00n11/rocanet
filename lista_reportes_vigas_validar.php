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
$rutaFirebase = "Vigas/Reportes";
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

// Ordenar por fecha descendente (más reciente primero)
usort($reportesPendientes, function($a, $b) {
	$fechaA = $a['fecha'] ?? '';
	$fechaB = $b['fecha'] ?? '';
	return strcmp($fechaB, $fechaA); // Orden descendente
});
?>

<div id="content" class="app-content">
	<h1 class="page-header">Vigas pendientes de validar</h1>

	<div class="card">
		<div class="card-body">

			<style>
				.col-obra {
					min-width: 220px;
					word-break: break-word;
				}
			</style>

			<div class="table-responsive">
				<table id="datatableValidarVigas" class="table text-nowrap w-100">
					<thead class="table-dark">
						<tr>
							<th>Personal</th>
							<th>Cliente</th>
							<th>Obra</th>
							<th>Fecha</th>
							<th>Acción</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($reportesPendientes): ?>
							<?php foreach ($reportesPendientes as $r): ?>
								<?php 
								// Convertir fecha para ordenamiento
								$fechaOriginal = $r['fecha'] ?? '';
								$fechaOrden = $fechaOriginal; // Por defecto usa el original
								
								// Si la fecha está en formato DD/MM/YYYY, convertir a YYYY-MM-DD para ordenar
								if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $fechaOriginal, $matches)) {
									$fechaOrden = $matches[3] . '-' . $matches[2] . '-' . $matches[1];
								}
								?>
								<tr>
									<td class='text-center'><?= $r['usuario'] ?? '' ?></td>
									<td class='text-center'><?= $r['cliente'] ?? '' ?></td>
									<td class='text-center col-obra'><?= $r['obra'] ?? '' ?></td>
									<td class='text-center' data-order="<?= $fechaOrden ?>"><?= $fechaOriginal ?></td>
									<td class="text-center">
										<a href="#"
											class="btn btn-outline-theme btn-sm w-80px"
											onclick="
		window.open('validar_viga.php?usuario=<?= urlencode($r['usuario']) ?>&llave=<?= urlencode($r['llave']) ?>&tipo=Vigas', '_blank');
		return false;
   ">
											Validar
										</a>

									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="5" class="text-center text-muted">
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
	document.addEventListener('DOMContentLoaded', function() {
		const tableEl = document.querySelector('#datatableValidarVigas');
		if (tableEl) {
			new DataTable(tableEl, {
				autoWidth: false,
				responsive: true,
				order: [[3, 'desc']], // Ordenar por fecha descendente
				columnControl: [
					'order',
					['search', 'spacer', 'orderAsc', 'orderDesc', 'orderClear']
				],
				columnDefs: [{
					targets: [0, 1, 2, 3],
					columnControl: [
						'order',
						['search', 'spacer', 'orderAsc', 'orderDesc', 'orderClear']
					]
				},
				{
					targets: 4, // Columna Acción
					orderable: false,
					searchable: false,
					responsivePriority: 1,
					className: 'dt-body-center'
				}],
				ordering: {
					indicators: false
				},
				language: {
					search: "Buscar:",
					lengthMenu: "Mostrar _MENU_ registros",
					info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
					infoEmpty: "Mostrando 0 a 0 de 0 registros",
					infoFiltered: "(filtrado de _MAX_ registros totales)",
					zeroRecords: "No se encontraron coincidencias",
					paginate: {
						next: "Siguiente",
						previous: "Anterior"
					}
				}
			});
		}
	});
</script>

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