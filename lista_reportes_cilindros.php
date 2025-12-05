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
		Lista de reporte de los cilindros.
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

				// 2️⃣ Hacer la consulta
				if (!isset($_GET['expediente'])) {
					echo "No se seleccionó un expediente.<br><br>";
					exit; // Detiene la página
				}

				$expediente = $_GET['expediente'];
				$sql = "SELECT * FROM reporte_concreto WHERE expediente = '$expediente' ORDER BY reporte DESC";
				$resultado = $conexion->query($sql);

				// 3️⃣ Verificar resultados
				if ($resultado->num_rows > 0) {
					// Convertir el resultado a un arreglo asociativo
					$clientes = $resultado->fetch_all(MYSQLI_ASSOC);


					echo '<div class="table-responsive">';
					echo '<table class="table table-bordered table-striped table-hover">';
					echo '<thead class="table-dark">



						<tr>
							<th class="text-center">Reporte</th>
							<th class="text-center">Elemento</th>
							<th class="text-center">Ubicación</th>
							<th class="text-center">Fecha</th>
							<th class="text-center">f&#39;c [kgf/cm²]</th>
							<th class="text-center">Edad [d]</th>
							<th class="text-center">Eliminar</th>
							<th class="text-center">Editar</th>
							<th class="text-center">Ver</th>
						</tr>
					</thead>';
					echo '<tbody>';

					foreach ($clientes as $fila) {

						echo "<tr>
							<td class='text-center'>{$fila['reporte']}</td>
							<td class='text-center'>{$fila['elemento']}</td>
							<td class='text-center'>{$fila['ubicacion']}</td>
							<td class='text-center'>{$fila['fecha']}</td>
							<td class='text-center'>{$fila['fc']}</td>
							<td class='text-center'>{$fila['edad']}</td>
							<td class='text-center'>
								<a href='#modalEdit' data-bs-toggle='modal' class='btn btn-outline-theme btn-sm w-80px'>
									Elimiar
								</a>
							</td>
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