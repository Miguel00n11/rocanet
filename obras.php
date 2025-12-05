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
		Lista de obras <small>Se muestrean todos los clientes</small>
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
				Selecciona un cliente.

				<?php
				// 2️⃣ Hacer la consulta
				$sql = "SELECT * FROM obras ORDER BY expediente DESC";
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
							<th>Expediente</th>
							<th>Obras</th>
							<th>Localización</th>
							<th>Cliente</th>
							<th>Acción</th>
						</tr>
					</thead>';
					echo '<tbody>';

					foreach ($clientes as $fila) {

						echo "<tr>
							<td>{$fila['expediente']}</td>
							<td>{$fila['obra']}</td>
							<td>{$fila['localizacion']}</td>
							<td>{$fila['cliente']}</td>
							<td class='text-center'>
                        <div class='dropdown'>
                            <button class='btn btn-outline-theme dropdown-toggle' type='button' data-bs-toggle='dropdown'>
                                <iconify-icon icon='mdi:check-circle'></iconify-icon> Seleccionar
                            </button>

                            <ul class='dropdown-menu'>
                                <li>
                                    <a class='dropdown-item' 
									href='lista_reportes_cilindros.php?expediente={$fila['expediente']}&obra={$fila['obra']}'
									>
                                        Lista Cilindros
                                    </a>
                                </li>
                                <li><hr class='dropdown-divider'></li>
                            </ul>
                        </div>
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