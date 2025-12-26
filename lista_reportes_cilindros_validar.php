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
	<?php $obra = $_GET['obra']; ?>
	<h1 class="page-header">
		Lista de reporte de los cilindros para validar.
		<small><?= $obra ?></small>
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
				$sql = "SELECT item, i.id_reporte_concreto, i.reporte, edad_item, carga, fc, fecha_ensaye, r.expediente, r.fecha, c.cliente, o.obra, o.localizacion,
				 c.idcliente, hora_desmoldeo,hora_muestreo,hora_ensaye, fecha_recepcion, condicion_especimen,observaciones,escuadra 
				 FROM item AS i 
				JOIN reporte_concreto AS r ON i.id_reporte_concreto=r.id_reporte_concreto 
				JOIN obras AS o ON r.expediente=o.expediente 
				JOIN clientes AS c ON o.cliente=c.idcliente 
				ORDER BY id_item DESC LIMIT 500";
				$resultado = $conexion->query($sql);

				// 3️⃣ Verificar resultados
				if ($resultado->num_rows > 0) {
					// Convertir el resultado a un arreglo asociativo
					$clientes = $resultado->fetch_all(MYSQLI_ASSOC);


					echo '<div class="table-responsive">';
					echo '<table class="table table-bordered table-striped table-hover">';
					echo '<thead class="table-dark">



						<tr>
							<th class="text-center">Item</th>
							<th class="text-center">Expediente</th>
							<th class="text-center">Reporte</th>
							<th class="text-center">Obra</th>
							<th class="text-center">Edad [d]</th>
							<th class="text-center">Fecha de muestreo</th>
							<th class="text-center">Fecha de ensaye</th>
							<th class="text-center">Hora de ensaye</th>
							<th class="text-center">Hora de muestreo</th>
							<th class="text-center">Hora de desmoldeo</th>
							<th class="text-center">Condición del especimen</th>
							<th class="text-center">Carga</th>
							<th class="text-center">Cliente</th>
							<th class="text-center">Editar</th>
							<th class="text-center">Ver</th>
						</tr>
					</thead>';
					echo '<tbody>';

					foreach ($clientes as $fila) {

						echo "<tr>
							<td class='text-center'>{$fila['item']}</td>
							<td class='text-center'>{$fila['expediente']}</td>
							<td class='text-center'>{$fila['reporte']}</td>
							<td class='text-center'>{$fila['obra']}</td>
							<td class='text-center'>{$fila['edad_item']}</td>
							<td class='text-center'>{$fila['fecha']}</td>
							<td class='text-center'>{$fila['fecha_ensaye']}</td>
							<td class='text-center'>{$fila['hora_ensaye']}</td>
							<td class='text-center'>{$fila['hora_muestreo']}</td>
							<td class='text-center'>{$fila['hora_desmoldeo']}</td>
							<td class='text-center'>{$fila['condicion_especimen']}</td>
							<td class='text-center'>{$fila['carga']}</td>
							<td class='text-center'>{$fila['cliente']}</td>
								<td class='text-center'>
								<a href='captura_cilindros.php?expediente={$fila['expediente']}&reporte={$fila['reporte']}&id_reporte_concreto={$fila['id_reporte_concreto']}'
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