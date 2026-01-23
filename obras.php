<?php

include("cabeza.php");
include("conexion.php");

?>

<style>
	.col-obras {
		/* width: 220px; */
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
					echo '<table id="datatableObras" class="table table-bordered table-striped table-hover nowrap w-100">';
					echo '<thead class="table-dark">
						<tr>
							<th class="text-center col-obras">Expediente</th>
							<th class="text-center col-obras">Obras</th>
							<th class="text-center col-obras">Localización</th>
							<th class="text-center col-obras">Cliente</th>
							<th class="text-center">Editar</th>
							<th class="text-center">Nuevo</th>
						</tr>
					</thead>';
					echo '<tbody>';

					foreach ($clientes as $fila) {

						echo "<tr>
							<td class='text-center col-obras'>{$fila['expediente']}</td>
							<td class='text-center col-obras'>{$fila['obra']}</td>
							<td class='text-center col-obras'>{$fila['localizacion']}</td>
							<td class='text-center col-obras'>{$fila['cliente']}</td>
							<td class='text-center'>
                        <div class='dropdown'>
                            <button class='btn btn-outline-theme dropdown-toggle' type='button' data-bs-toggle='dropdown'>
									<i class='far fa-lg fa-fw me-2 fa-edit'></i>
								LISTA
                            </button>

                            <ul class='dropdown-menu'>
                                <li>
                                    <a class='dropdown-item' 
									href='lista_reportes_cilindros.php?expediente={$fila['expediente']}&obra={$fila['obra']}' target='_blank'
									>
                                        Lista Cilindros
                                    </a>
									 <a class='dropdown-item' 
									href='lista_reportes_vigas.php?expediente={$fila['expediente']}&obra={$fila['obra']}' target='_blank'
									>
                                        Lista vigas
                                    </a>
									<a class='dropdown-item' 
									href='lista_reportes_compactaciones.php?expediente={$fila['expediente']}&obra={$fila['obra']}' target='_blank'
									>
                                        Lista Compactaciones
                                    </a>
                                </li>
                                <li><hr class='dropdown-divider'></li>
                            </ul>
                        </div>
                    </td>
					<td class='text-center'>
                        <div class='dropdown'>
                            <button class='btn btn-outline-theme dropdown-toggle' type='button' data-bs-toggle='dropdown'>
								<i class='fas fa-lg fa-fw me-2 fa-plus'></i> Agregar
                            </button>

                            <ul class='dropdown-menu'>
                                <li>
                                    <a class='dropdown-item' 
									href='nuevos_cilindros.php?expediente={$fila['expediente']}' target='_blank'
									>
                                        Nuevo cilindro
                                    </a>
									<a class='dropdown-item' 
									href='nuevos_vigas.php?expediente={$fila['expediente']}' target='_blank'
									>
										Nueva viga
                                    </a>
									<a class='dropdown-item' 
									href='nuevos_compactaciones.php?expediente={$fila['expediente']}' target='_blank'
									>
                                        Nueva compactación
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