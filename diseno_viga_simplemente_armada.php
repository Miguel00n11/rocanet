<?php
require_once __DIR__ . '/auth.php';

include("cabeza.php");
include("conexion_estructural.php");

?>

<!-- BEGIN #content -->
<div id="content" class="app-content">
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="#">DISEÑO ESTRUCTURAL</a></li>
		<li class="breadcrumb-item active">VIGAS SIMPLEMENTE ARMADAS</li>
	</ul>

	<h1 class="page-header">
		Diseño de Viga Simplemente Armada
		<small>Lista de diseños registrados</small>
	</h1>

	<div class="mb-3">
		<a href="nuevo_diseno_viga_simplemente_armada.php" class="btn btn-theme">
			<i class="fas fa-plus me-1"></i> Nuevo Diseño
		</a>
	</div>

	<div class="card">
		<div class="card-header">
			LISTA DE DISEÑOS DE VIGAS SIMPLEMENTE ARMADAS
		</div>
		<div class="card-body">
			<?php
			// Consulta para obtener los diseños de vigas
			$sql = "SELECT 
						id_viga,
						codigo_diseno,
						obra,
						ancho_viga,
						altura_viga,
						fc,
						fy,
						recubrimiento_inferior,
						mu,
						fecha_registro
					FROM diseno_viga_simplemente_armada
					ORDER BY id_viga DESC LIMIT 100";
			
			$resultado = $conexion->query($sql);

			// Verificar si hay resultados
			if ($resultado && $resultado->num_rows > 0) {
				echo '<div class="table-responsive">';
				echo '<table id="tablaVigas" class="table table-striped table-bordered w-100">';
				echo '<thead class="table-dark">';
				echo '<tr>
						<th class="text-center" style="width: 30px;"></th>
						<th class="text-center">ID</th>
						<th class="text-center">CÓDIGO DE DISEÑO</th>
						<th class="text-center">OBRA</th>
						<th class="text-center">ANCHO (cm)</th>
						<th class="text-center">ALTURA (cm)</th>
						<th class="text-center">f\'c (kgf/cm²)</th>
						<th class="text-center">fy (kgf/cm²)</th>
						<th class="text-center">RECUBRIMIENTO (cm)</th>
						<th class="text-center">Mu (tonf*m)</th>
						<th class="text-center">FECHA REGISTRO</th>
						<th class="text-center">Editar</th>
						<th class="text-center">Memoria</th>
						<th class="text-center">Eliminar</th>
					</tr>';
				echo '</thead>';
				echo '<tbody>';

				while ($fila = $resultado->fetch_assoc()) {
					echo "<tr data-id='{$fila['id_viga']}'>
							<td class='text-center'><i class='fas fa-bars' style='cursor: pointer;'></i></td>
							<td class='text-center'>{$fila['id_viga']}</td>
							<td class='text-center'>{$fila['codigo_diseno']}</td>
							<td class='text-center'>{$fila['obra']}</td>
							<td class='text-center'>{$fila['ancho_viga']}</td>
							<td class='text-center'>{$fila['altura_viga']}</td>
							<td class='text-center'>{$fila['fc']}</td>
							<td class='text-center'>{$fila['fy']}</td>
							<td class='text-center'>{$fila['recubrimiento_inferior']}</td>
							<td class='text-center'>{$fila['mu']}</td>
							<td class='text-center'>{$fila['fecha_registro']}</td>
							<td class='text-center'>
								<a href='editar_diseno_viga_simplemente_armada.php?id={$fila['id_viga']}' 
								   class='btn btn-outline-theme btn-sm w-80px' 
								   target='_blank'>
									Editar
								</a>
							</td>
							<td class='text-center'>								<a href='exportar_memoria_calculo_viga.php?id={$fila['id_viga']}' 
								   class='btn btn-outline-info btn-sm w-80px'>
									<i class='fas fa-file-pdf me-1'></i>Memoria
								</a>
							</td>
							<td class='text-center'>								<a href='eliminar_diseno_viga_simplemente_armada.php?id={$fila['id_viga']}' 
								   class='btn btn-outline-danger btn-sm w-80px'
								   onclick='return confirm(\"¿Está seguro de que desea eliminar este diseño?\");'>
									<i class='fas fa-trash me-1'></i>Eliminar
								</a>
							</td>
						</tr>";
				}

				echo '</tbody>';
				echo '</table>';
				echo '</div>';
			} else {
				echo '<div class="alert alert-info">No hay diseños registrados aún.</div>';
			}
			?>
		</div>
	</div>
</div>
<!-- END #content -->

<?php
include 'pie.php';
?>