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
							<th class="text-center col-obras">Fecha</th>
							<th class="text-center">Acción</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($reportesPendientes): ?>
							<?php foreach ($reportesPendientes as $r): ?>
								<tr>
									<td class='text-center col-obras'><?= $r['usuario'] ?? '' ?></td>
									<td class='text-center col-obras'><?= $r['cliente'] ?? '' ?></td>
									<td class='text-center col-obras'><?= $r['obra'] ?? '' ?></td>
									<td class='text-center col-obras'><?= $r['fecha'] ?? '' ?></td>
									<td class="text-center">
										<a href="#"
											class="btn btn-outline-theme btn-sm w-100px"
											onclick="
		window.open('validar_mecanica.php?usuario=<?= urlencode($r['usuario']) ?>&llave=<?= urlencode($r['llave']) ?>&tipo=Mecanicas', '_blank');
		return false;
   ">
											<i class='fas fa-check me-1'></i> Validar
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
		const tableEl = document.querySelector('#datatableValidarMecanicas');
		if (tableEl) {
			new DataTable(tableEl, {
				autoWidth: false,
				responsive: true,
				order: [[3, 'desc']], // Ordenar por fecha descendente
				columnDefs: [{
					targets: 4, // Columna Acción
					orderable: false,
					searchable: false,
					responsivePriority: 1,
					className: 'dt-body-center'
				}],
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
