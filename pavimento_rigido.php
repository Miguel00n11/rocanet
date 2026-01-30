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
			// Consulta para obtener los diseños de pavimento rígido sin duplicados
			$sql = "SELECT DISTINCT
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
						<th class="text-center">UBICACIÓN</th>
						<th class="text-center">CLIENTE</th>
						<th class="text-center">EXPEDIENTE</th>
						<th class="text-center">FECHA ESTUDIO</th>
						<th class="text-center">TIPO PAVIMENTO</th>
						<th class="text-center">ESPESOR CONCRETO</th>
						<th class="text-center">BASE</th>
						<th class="text-center">SUBRASANTE</th>
						<th class="text-center">PEDRAPLEN</th>

						<th class="text-center">Editar</th>
						<th class="text-center">Ver</th>
						<th class="text-center">Eliminar</th>
					</tr>';
				echo '</thead>';
				echo '<tbody>';

				while ($fila = $resultado->fetch_assoc()) {
					echo "<tr data-id='{$fila['id_pavimento_rigido']}'>
							<td class='text-center details-control' style='cursor: pointer;'><i class='fas fa-plus-circle text-theme'></i></td>
							<td class='text-center'>{$fila['id_pavimento_rigido']}</td>
						<td class='text-center col-obra'>{$fila['obra']}</td>
						<td class='text-center'>{$fila['ubicacion']}</td>
						<td class='text-center'>{$fila['cliente']}</td>
						<td class='text-center'>{$fila['expediente']}</td>
						<td class='text-center'>{$fila['fecha_estudio']}</td>
						<td class='text-center'>{$fila['tipo_pavimento']}</td>
						<td class='text-center'>{$fila['espesor_concreto']}</td>
						<td class='text-center'>{$fila['base']}</td>
						<td class='text-center'>{$fila['subrasante']}</td>
							<td class='text-center'>{$fila['pedraplen']}</td>

							<td class='text-center'>
								<a href='editar_pavimento_rigido.php?id={$fila['id_pavimento_rigido']}' 
								   class='btn btn-outline-theme btn-sm w-80px' 
								   target='_blank'>
									Editar
								</a>
							</td>
							<td class='text-center'>
								<a href='#modalVer' 
								   data-bs-toggle='modal' 
								   class='btn btn-outline-theme btn-sm w-80px'>
									Ver
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
			responsive: {
				details: false
			},
			language: {
				url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
			},
			order: [[1, 'desc']],
			columnDefs: [
				{ orderable: false, targets: [0, 12, 13, 14] }
			],
			pageLength: 25
		});

		// Manejar clic en la columna de detalles
		$('#tablaPavimentos tbody').on('click', 'td.details-control', function () {
			const tr = $(this).closest('tr');
			const row = table.row(tr);
			const icon = $(this).find('i');
			const idPavimento = tr.data('id');

			if (row.child.isShown()) {
				// Cerrar la fila
				row.child.hide();
				tr.removeClass('shown');
				icon.removeClass('fa-minus-circle').addClass('fa-plus-circle');
			} else {
				// Abrir la fila y cargar datos
				icon.removeClass('fa-plus-circle').addClass('fa-spinner fa-spin');
				
				$.ajax({
					url: 'ajax_get_sondeos_estratos.php',
					type: 'GET',
					data: { id: idPavimento },
					dataType: 'json',
					success: function(sondeos) {
						const content = formatSondeosEstratos(sondeos);
						row.child(content).show();
						tr.addClass('shown');
						icon.removeClass('fa-spinner fa-spin').addClass('fa-minus-circle');
					},
					error: function() {
						alert('Error al cargar los datos');
						icon.removeClass('fa-spinner fa-spin').addClass('fa-plus-circle');
					}
				});
			}
		});
	});

	function formatSondeosEstratos(sondeos) {
		if (!sondeos || sondeos.length === 0) {
			return '<div class="p-3 text-center text-muted">No hay sondeos registrados</div>';
		}

		let html = '<div class="p-3" style="background-color: #f8f9fa;">';
		
		sondeos.forEach(sondeo => {
			html += `
				<div class="card mb-3">
					<div class="card-header" style="background-color: #2d353c; color: white;">
						<strong>Sondeo #${sondeo.numero_sondeo}</strong>
					</div>
					<div class="card-body">`;
			
			if (sondeo.estratos && sondeo.estratos.length > 0) {
				html += `
					<table class="table table-sm table-bordered mb-0">
						<thead style="background-color: #2d353c; color: white;">
							<tr>
								<th style="width: 25%;">Espesores</th>
								<th style="width: 75%;">Descripción</th>
							</tr>
						</thead>
						<tbody>`;
				
				sondeo.estratos.forEach(estrato => {
					html += `
						<tr>
							<td style="color: #000;">${estrato.espesores || '-'}</td>
							<td style="color: #000;">${estrato.descripcion || '-'}</td>
						</tr>`;
				});
				
				html += `
						</tbody>
					</table>`;
			} else {
				html += '<p class="text-muted mb-0">No hay estratos registrados</p>';
			}
			
			html += `
					</div>
				</div>`;
		});
		
		html += '</div>';
		return html;
	}

	function eliminarDiseño(id) {
		if (confirm('¿Está seguro de que desea eliminar este diseño de pavimento? Esta acción no se puede deshacer.')) {
			window.location.href = 'eliminar_pavimento_rigido.php?id=' + id;
		}
	}
</script>
