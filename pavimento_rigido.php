<?php
require_once __DIR__ . '/auth.php';

include("cabeza.php");
include("conexion.php");

?>
<style>
	.col-obra {
		min-width: 220px;
		max-width: 1000px;
		white-space: normal !important;
		word-break: break-word;
		overflow-wrap: anywhere;
		vertical-align: top;
	}

	/* Centrar verticalmente SOLO las columnas Editar y Ver */
	#datatableDefault tbody td.dt-body-center {
		vertical-align: middle;
	}
</style>

<!-- BEGIN #content -->
<div id="content" class="app-content">
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="#">DISEÑO DE PAVIMENTOS</a></li>
		<li class="breadcrumb-item active">PAVIMENTO RÍGIDO</li>
	</ul>

	<h1 class="page-header">
		Diseño de Pavimento Rígido
		<small>Lista de diseños registrados</small>
	</h1>

	<div class="mb-3">
		<a href="nuevo_pavimento_rigido.php" class="btn btn-theme">
			<i class="fas fa-plus me-1"></i> Nuevo Diseño
		</a>
	</div>

	<div class="card">
		<div class="card-header">
			LISTA DE DISEÑOS DE PAVIMENTO RÍGIDO
		</div>
		<div class="card-body">
			<?php
			// Consulta para obtener los diseños de pavimento rígido
			$sql = "SELECT 
						pr.id_pavimento_rigido,
						pr.obra,
						pr.ubicacion,
						pr.cliente,
						pr.expediente,
						pr.fecha_estudio,
						pr.tipo_pavimento,
						pr.espesor_concreto,
						pr.base,
						pr.subrasante,
						pr.pedraplen,
						pr.fecha_registro
					FROM pavimento_rigido AS pr
					ORDER BY pr.id_pavimento_rigido DESC";
			
			$resultado = $conexion->query($sql);

			// Verificar si hay resultados
			if ($resultado && $resultado->num_rows > 0) {
				echo '<div class="table-responsive">';
				echo '<table id="tablaPavimentos" class="table table-striped table-bordered w-100">';
				echo '<thead class="table-dark">';
				echo '<tr>
						<th class="text-center" style="width: 30px;"></th>
						<th class="text-center">ID</th>
						<th class="text-center col-obra">OBRA</th>
						<th class="text-center">TIPO PAVIMENTO</th>
						<th class="text-center">ESPESOR CONCRETO</th>
						<th class="text-center">BASE</th>
						<th class="text-center">SUBRASANTE</th>
						<th class="text-center">PEDRAPLEN</th>
						<th class="text-center none">UBICACIÓN</th>
						<th class="text-center none">CLIENTE</th>
						<th class="text-center none">EXPEDIENTE</th>
						<th class="text-center none">FECHA ESTUDIO</th>
						<th class="text-center none">SONDEOS</th>
						<th class="text-center">Editar</th>
						<th class="text-center">Exportar</th>
						<th class="text-center">Eliminar</th>
					</tr>';
				echo '</thead>';
				echo '<tbody>';

				while ($fila = $resultado->fetch_assoc()) {
					// Obtener sondeos para este pavimento
					$id_pav = $fila['id_pavimento_rigido'];
					$sql_sondeos = "SELECT * FROM sondeos WHERE id_pavimento_rigido = $id_pav ORDER BY numero_sondeo";
					$res_sondeos = $conexion->query($sql_sondeos);
					
					$sondeos_html = '';
					if ($res_sondeos && $res_sondeos->num_rows > 0) {
						while ($sondeo = $res_sondeos->fetch_assoc()) {
							$sondeos_html .= "<div style='margin-bottom: 15px;'><strong>Sondeo #{$sondeo['numero_sondeo']}</strong><table style='width:100%; margin-top:5px; border:1px solid #ddd;'><thead style='background-color: #2d353c; color: white;'><tr><th style='padding:5px; width:25%;'>Espesores</th><th style='padding:5px; width:75%;'>Descripción</th></tr></thead><tbody>";
							
							// Obtener estratos
							$id_sondeo = $sondeo['id_sondeo'];
							$sql_estratos = "SELECT * FROM estratos WHERE id_sondeo = $id_sondeo ORDER BY id_estrato";
							$res_estratos = $conexion->query($sql_estratos);
							
							if ($res_estratos && $res_estratos->num_rows > 0) {
								while ($estrato = $res_estratos->fetch_assoc()) {
									$sondeos_html .= "<tr><td style='padding:5px; border:1px solid #ddd;'>" . htmlspecialchars($estrato['espesores']) . "</td><td style='padding:5px; border:1px solid #ddd;'>" . htmlspecialchars($estrato['descripcion']) . "</td></tr>";
								}
							} else {
								$sondeos_html .= "<tr><td colspan='2' style='padding:5px; text-align:center;'>No hay estratos</td></tr>";
							}
							
							$sondeos_html .= "</tbody></table></div>";
						}
					} else {
						$sondeos_html = 'No hay sondeos registrados';
					}
					
					echo "<tr data-id='{$fila['id_pavimento_rigido']}'>
							<td class='text-center details-control' style='cursor: pointer;'><i class='fas fa-plus-circle text-theme'></i></td>
							<td class='text-center'>{$fila['id_pavimento_rigido']}</td>
							<td class='text-center col-obra'>{$fila['obra']}</td>
							<td class='text-center'>{$fila['tipo_pavimento']}</td>
							<td class='text-center'>{$fila['espesor_concreto']}</td>
							<td class='text-center'>{$fila['base']}</td>
							<td class='text-center'>{$fila['subrasante']}</td>
							<td class='text-center'>{$fila['pedraplen']}</td>
							<td class='text-center'>{$fila['ubicacion']}</td>
							<td class='text-center'>{$fila['cliente']}</td>
							<td class='text-center'>{$fila['expediente']}</td>
							<td class='text-center'>{$fila['fecha_estudio']}</td>
							<td>$sondeos_html</td>
							<td class='text-center'>
								<a href='editar_pavimento_rigido.php?id={$fila['id_pavimento_rigido']}' 
								   class='btn btn-outline-theme btn-sm w-80px' 
								   target='_blank'>
									Editar
								</a>
							</td>
							<td class='text-center'>
								<a href='exportar_pavimento_excel.php?id={$fila['id_pavimento_rigido']}' 
								   class='btn btn-outline-success btn-sm w-80px'
								   onclick='return confirm(\"¿Está seguro de que desea descargar este diseño de pavimento en formato Excel?\");'>
									<i class='fas fa-file-excel me-1'></i>Exportar
								</a>
							</td>
							<td class='text-center'>
								<button onclick='eliminarDiseño({$fila['id_pavimento_rigido']})' 
								   class='btn btn-outline-danger btn-sm w-80px'>
									Eliminar
								</button>
							</td>
						</tr>";
				}

				echo '</tbody>';
				echo '</table>';
				echo '</div>';
			} else {
				echo '<div class="alert alert-info" role="alert">';
				echo '<i class="fas fa-info-circle me-2"></i>No se encontraron diseños de pavimento rígido registrados.';
				echo '</div>';
			}

			// Cerrar conexión
			$conexion->close();
			?>
		</div>
	</div>
</div>
<!-- END #content -->

<?php include("pie.php"); ?>

<script>
	let table;

	$(document).ready(function() {
		table = $('#tablaPavimentos').DataTable({
			responsive: true,
			language: {
				url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
			},
			order: [[1, 'desc']],
			columnDefs: [
				{ orderable: false, targets: [0, 13, 14, 15] }
			],
			pageLength: 25
		});
	});

	function eliminarDiseño(id) {
		if (confirm('¿Está seguro de que desea eliminar este diseño de pavimento? Esta acción no se puede deshacer.')) {
			window.location.href = 'eliminar_pavimento_rigido.php?id=' + id;
		}
	}
</script>
