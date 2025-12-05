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
				Lista de todos los cilindros <small>Aqui demuestra el listado de todos los cilindros</small>
			</h1>
			
			<div class="card">
				<div class="card-header with-btn">
					Items de cilindros
					<div class="card-header-btn">
						<a href="#" data-toggle="card-collapse" class="btn"><iconify-icon icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
						<a href="#" data-toggle="card-expand" class="btn"><iconify-icon icon="material-symbols-light:fullscreen"></iconify-icon></a>
						<a href="#" data-toggle="card-remove" class="btn"><iconify-icon icon="material-symbols-light:close-rounded"></iconify-icon></a>
					</div>
				</div>
				<div class="card-body">
					<p>
						Start build your page heres
						hola

						<?php
				// 2️⃣ Hacer la consulta
				$sql = "SELECT id, idcliente, cliente, representante, email, telefono, nextel, direccion, RFC, estatus FROM clientes";
				$resultado = $conexion->query($sql);

				// 3️⃣ Verificar resultados
				if ($resultado->num_rows > 0) {
					// Convertir el resultado a un arreglo asociativo
					$clientes = $resultado->fetch_all(MYSQLI_ASSOC);

					// 4️⃣ Recorrer con foreach
					// foreach ($clientes as $fila) {
					// 	echo "<b>ID:</b> " . $fila['id'] . "<br>";
					// 	echo "<b>Cliente:</b> " . $fila['cliente'] . "<br>";
					// 	echo "<b>Representante:</b> " . $fila['representante'] . "<br>";
					// 	echo "<b>Email:</b> " . $fila['email'] . "<br>";
					// 	echo "<b>Teléfono:</b> " . $fila['telefono'] . "<br>";
					// 	echo "<b>RFC:</b> " . $fila['RFC'] . "<br>";
					// 	echo "<b>Estatus:</b> " . $fila['estatus'] . "<br>";
					// 	echo "<hr>";
					// }
					echo '<div class="table-responsive">';
					echo '<table class="table table-bordered table-striped table-hover">';
					echo '<thead class="table-dark">
						<tr>
						<th>ID</th>
							<th>ID Cliente</th>
							<th>Cliente</th>
							<th>Representante</th>
							<th>Email</th>
							<th>Teléfono</th>
							<th>Nextel</th>
							<th>Dirección</th>
							<th>RFC</th>
							<th>Estatus</th>
							<th>Seleccionar</th>
						</tr>
					</thead>';
					echo '<tbody>';

					foreach ($clientes as $fila) {
						
						echo "<tr>
							<td>{$fila['id']}</td>
							<td>{$fila['idcliente']}</td>
							<td>{$fila['cliente']}</td>
							<td>{$fila['representante']}</td>
							<td>{$fila['email']}</td>
							<td>{$fila['telefono']}</td>
							<td>{$fila['nextel']}</td>
							<td>{$fila['direccion']}</td>
							<td>{$fila['RFC']}</td>
							<td>{$fila['estatus']}</td>
							<td class='text-center'>
			<button class='btn btn-outline-theme' onclick=\"alert('Seleccionaste al cliente: {$fila['cliente']}');\">
				<iconify-icon icon='mdi:check-circle'></iconify-icon> Seleccionar
			</button>
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
