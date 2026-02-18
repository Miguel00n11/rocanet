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
   OBTENER REPORTES DE RESPALDO
========================= */
$rutaFirebase = "Mecanicas/RespaldoMecanicas";
$usuarios = firebaseGet($rutaFirebase);
$reportesRespaldo = [];

if ($usuarios) {
	foreach ($usuarios as $usuario => $reportes) {
		if (!$reportes || !is_array($reportes)) continue;

		foreach ($reportes as $llave => $reporte) {
			if (!is_array($reporte)) continue;
			
			$reporte['usuario'] = $usuario;
			$reporte['llave'] = $llave;
			$reportesRespaldo[] = $reporte;
		}
	}
}

// Ordenar por fecha descendente (más reciente primero)
usort($reportesRespaldo, function($a, $b) {
	$fechaA = $a['fecha'] ?? '';
	$fechaB = $b['fecha'] ?? '';
	return strcmp($fechaB, $fechaA); // Orden descendente
});
?>

<style>
	.col-obras {
		min-width: 220px;
		max-width: 1000px;
		white-space: normal !important;
		word-break: break-word;
		overflow-wrap: anywhere;
		vertical-align: top;
	}

	/* Centrar verticalmente SOLO las columnas Editar y Ver */
	#datatableRespaldoMecanicas tbody td.dt-body-center {
		vertical-align: middle;
	}
</style>

<div id="content" class="app-content">
	<h1 class="page-header">Mecánica de suelos - Respaldo (Originales)</h1>

	<div class="card">
		<div class="card-header with-btn">
			REPORTES RESPALDADOS
			<div class="card-header-btn">
				<a href="#" data-toggle="card-collapse" class="btn"><iconify-icon icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
				<a href="#" data-toggle="card-expand" class="btn"><iconify-icon icon="material-symbols-light:fullscreen"></iconify-icon></a>
				<a href="#" data-toggle="card-remove" class="btn"><iconify-icon icon="material-symbols-light:close-rounded"></iconify-icon></a>
			</div>
		</div>
		<div class="card-body">

			<div class="table-responsive">
				<table id="datatableRespaldoMecanicas" class="table table-bordered table-striped table-hover nowrap w-100">
					<thead class="table-dark">
						<tr>
							<th class="text-center col-obras">Personal</th>
							<th class="text-center col-obras">Cliente</th>
							<th class="text-center col-obras">Obra</th>
							<th class="text-center col-obras">Fecha de muestreo</th>
							<th class="text-center">Acción</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($reportesRespaldo && count($reportesRespaldo) > 0): ?>
							<?php foreach ($reportesRespaldo as $r): ?>
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
									<td class='text-center col-obras'><?= htmlspecialchars($r['usuario'] ?? '') ?></td>
									<td class='text-center col-obras'><?= htmlspecialchars($r['cliente'] ?? '') ?></td>
									<td class='text-center col-obras'><?= htmlspecialchars($r['obra'] ?? '') ?></td>
									<td class='text-center col-obras' data-order="<?= $fechaOrden ?>"><?= htmlspecialchars($fechaOriginal) ?></td>
									<td class="text-center">
										<a href="#"
											class="btn btn-outline-theme btn-sm w-100px"
											onclick="
		window.open('validar_mecanica.php?usuario=<?= urlencode($r['usuario']) ?>&llave=<?= urlencode($r['llave']) ?>&tipo=Respaldo', '_blank');
		return false;
   ">
											<i class='fas fa-eye me-1'></i> Ver
										</a>

									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="5" class="text-center text-muted">
					No hay reportes respaldados
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
		const tableEl = document.querySelector('#datatableRespaldoMecanicas');
		if (tableEl && tableEl.querySelectorAll('tbody tr').length > 0) {
			// Solo inicializar DataTables si hay filas con datos
			const hasData = tableEl.querySelector('tbody tr td:not([colspan])') !== null;
			
			if (hasData) {
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
		}
	});
</script>


<?php include("pie.php"); ?>
