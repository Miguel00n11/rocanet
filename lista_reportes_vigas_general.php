<?php

include("cabeza.php");
include("conexion.php");


?>


<!-- BEGIN #content -->
<div id="content" class="app-content">
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="#">LAYOUT</a></li>
		<li class="breadcrumb-item active">STARTER PAGE</li>
	</ul>

	<h1 class="page-header">
		Lista de reporte de los vigas.
	</h1>

	<div class="card">

		<div class="card-header with-btn">
			CARD HEADER
			<div class="card-header-btn">
				<a href="#" data-toggle="card-collapse" class="btn"><iconify-icon icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
				<a href="#" data-toggle="card-expand" class="btn"><iconify-icon icon="material-symbols-light:fullscreen"></iconify-icon></a>
				<a href="#" data-toggle="card-remove" class="btn"><iconify-icon icon="material-symbols-light:close-rounded"></iconify-icon></a>
			</div>
		</div>
		<div class="card-body">
			<p>
				Start build your page here

				Selecciona un cliente.

				<?php



				// $expediente = $_GET['expediente'];
				$sql = "SELECT *
				 FROM vista_vigas_completa AS i 
				ORDER BY id_viga DESC LIMIT 500";
				$resultado = $conexion->query($sql);

				// 3️⃣ Verificar resultados
				if ($resultado->num_rows > 0) {
					// Convertir el resultado a un arreglo asociativo
					$clientes = $resultado->fetch_all(MYSQLI_ASSOC);


					echo '<div class="table-responsive">';
					echo '<table class="table table-bordered table-striped table-hover">';
					echo '<thead class="table-dark">



						<tr>
							<th class="text-center">Expediente</th>
							<th class="text-center">Cliente</th>
							<th class="text-center">Obra</th>
							<th class="text-center">Localización</th>
							<th class="text-center">Reporte</th>
							<th class="text-center">Edad [d]</th>
							<th class="text-center">Remisión</th>
							<th class="text-center">Fecha de muestreo</th>

							<th class="text-center">Id item 1</th>
							<th class="text-center">Fecha ensaye 1</th>
							<th class="text-center">Carga 1</th>

							<th class="text-center">Id item 2</th>
							<th class="text-center">Fecha ensaye 2</th>
							<th class="text-center">Carga 2</th>
							
							<th class="text-center">Id item 3</th>
							<th class="text-center">Fecha ensaye 3</th>
							<th class="text-center">Carga 3</th>
							


							<th class="text-center">Editar</th>
							<th class="text-center">Ver</th>
						</tr>
					</thead>';
					echo '<tbody>';

					foreach ($clientes as $fila) {

						echo "<tr>
							<td class='text-center'>{$fila['exp_registro']}</td>
							<td class='text-center'>{$fila['cliente']}</td>
							<td class='text-center'>{$fila['obra']}</td>
							<td class='text-center'>{$fila['localizacion']}</td>
							<td class='text-center'>{$fila['reporte']}</td>
							<td class='text-center'>{$fila['edad']}</td>
							<td class='text-center'>{$fila['remision_viga']}</td>
							<td class='text-center'>{$fila['fecha_viga']}</td>
							<td class='text-center'>{$fila['item1']}</td>
							<td class='text-center'>{$fila['ensaye1']}</td>
							<td class='text-center'>{$fila['carga1']}</td>

							<td class='text-center'>{$fila['item2']}</td>
							<td class='text-center'>{$fila['ensaye2']}</td>
							<td class='text-center'>{$fila['carga2']}</td>

							<td class='text-center'>{$fila['item3']}</td>
							<td class='text-center'>{$fila['ensaye3']}</td>
							<td class='text-center'>{$fila['carga3']}</td>
							

								<td class='text-center'>
								<a href='captura_vigas.php?exp_registro={$fila['exp_registro']}&reporte={$fila['reporte']}&id_viga={$fila['id_viga']}&item1={$fila['item1']}'
  	 								class='btn btn-outline-theme btn-sm w-80px'
									target='_blank'>
									
   									Editar
								</a>
								


							</td>
								<td class='text-center'>
								<a href='#modalEdit' data-bs-toggle='modal' class='btn btn-outline-theme btn-sm w-80px'>
									Reporte
								</a>
							</td>
						</tr>";
					}

					echo '</tbody>';
					echo '</table>';
					echo '</div>';
				} else {
					echo "No se encontraron clientes.";
				}

				// 5️⃣ Cerrar conexión
				$conexion->close();
				?>

			</p>
		</div>
	</div>
</div>
<!-- END #content -->



<?php include("pie.php"); ?>