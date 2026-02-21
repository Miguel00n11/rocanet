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
$rutaFirebase = "Mecanicas/ReportesMecanicas";
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
	#datatableValidarMecanicas tbody td.dt-body-center {
		vertical-align: middle;
	}
</style>

<div id="content" class="app-content">
	<h1 class="page-header">Mecánica de suelos pendientes de validar</h1>

	<div class="card">
		<div class="card-header with-btn">
			MECÁNICAS DE SUELOS
			<div class="card-header-btn">
				<a href="#" data-toggle="card-collapse" class="btn"><iconify-icon icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
				<a href="#" data-toggle="card-expand" class="btn"><iconify-icon icon="material-symbols-light:fullscreen"></iconify-icon></a>
				<a href="#" data-toggle="card-remove" class="btn"><iconify-icon icon="material-symbols-light:close-rounded"></iconify-icon></a>
			</div>
		</div>
		<div class="card-body">

			<div class="table-responsive">
				<table id="datatableValidarMecanicas" class="table table-bordered table-striped table-hover nowrap w-100">
					<thead class="table-dark">
						<tr>
							<th class="text-center col-obras">Personal</th>
							<th class="text-center col-obras">Cliente</th>
							<th class="text-center col-obras">Obra</th>
							<th class="text-center col-obras">Fecha de muestreo</th>
							<th class="text-center">Exportar</th>
							<th class="text-center">Acción</th>
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
									<td class='text-center col-obras'><?= $r['usuario'] ?? '' ?></td>
									<td class='text-center col-obras'><?= $r['cliente'] ?? '' ?></td>
									<td class='text-center col-obras'><?= $r['obra'] ?? '' ?></td>
									<td class='text-center col-obras' data-order="<?= $fechaOrden ?>"><?= $fechaOriginal ?></td>
									<td class="text-center">
										<div class='dropdown'>
											<button class='btn btn-outline-theme dropdown-toggle' type='button' data-bs-toggle='dropdown'>
												<i class='fas fa-file-export me-1'></i> Exportar
											</button>

											<ul class='dropdown-menu'>
												<li>
													<a class='dropdown-item' href="exportar_mecanica_excel.php?usuario=<?= urlencode($r['usuario']) ?>&llave=<?= urlencode($r['llave']) ?>&tipo=Mecanicas" target='_blank'>
														Excel
													</a>
													<a class='dropdown-item' href="exportar_mecanica_pdf.php?usuario=<?= urlencode($r['usuario']) ?>&llave=<?= urlencode($r['llave']) ?>&tipo=Mecanicas" target='_blank'>
														PDF
													</a>
												</li>
												<li><hr class='dropdown-divider'></li>
											</ul>
										</div>
									</td>
									<td class="text-center">
										<a href="validar_mecanica.php?usuario=<?= urlencode($r['usuario']) ?>&llave=<?= urlencode($r['llave']) ?>&tipo=Mecanicas"
											class="btn btn-outline-theme btn-sm w-100px">
											<i class='fas fa-check me-1'></i> Validar
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
	document.addEventListener('DOMContentLoaded', function() {
		const tableEl = document.querySelector('#datatableValidarMecanicas');
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
					targets: [4, 5], // Columnas Exportar y Acción
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
