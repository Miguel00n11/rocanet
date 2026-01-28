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

	<div class="card">
		<div class="card-header with-btn">
			LISTA DE DISEÑOS DE PAVIMENTO RÍGIDO
			<div class="card-header-btn">
				<a href="nuevo_pavimento_rigido.php" class="btn btn-success btn-sm" target="_blank">
					<i class="fas fa-plus me-1"></i> Nuevo Diseño
				</a>
			</div>
		</div>
		<div class="card-body">
			<?php
			// Consulta para obtener los diseños de pavimento rígido sin duplicados
			$sql = "SELECT DISTINCT
						pr.id_pavimento_rigido,
						pr.expediente,
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
						<th class="text-center">ID</th>
						<th class="text-center col-obra">OBRA / EXPEDIENTE</th>
						<th class="text-center">ESPESOR CONCRETO</th>
						<th class="text-center">BASE</th>
						<th class="text-center">SUBRASANTE</th>
						<th class="text-center">PEDRAPLEN</th>
						<th class="text-center">FECHA REGISTRO</th>
						<th class="text-center">Editar</th>
						<th class="text-center">Ver</th>
						<th class="text-center">Eliminar</th>
					</tr>';
				echo '</thead>';
				echo '<tbody>';

				while ($fila = $resultado->fetch_assoc()) {
					echo "<tr>
							<td class='text-center'>{$fila['id_pavimento_rigido']}</td>
							<td class='text-center col-obra'>{$fila['expediente']}</td>
							<td class='text-center'>{$fila['espesor_concreto']}</td>
							<td class='text-center'>{$fila['base']}</td>
							<td class='text-center'>{$fila['subrasante']}</td>
							<td class='text-center'>{$fila['pedraplen']}</td>
							<td class='text-center'>{$fila['fecha_registro']}</td>
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

<script>
	$(document).ready(function() {
		$('#tablaPavimentos').DataTable({
			responsive: true,
			language: {
				url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
			},
			order: [[0, 'desc']],
			columnDefs: [
				{ orderable: false, targets: [7, 8, 9] } // Deshabilitar orden en columnas de acción
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

<?php include("pie.php"); ?>
