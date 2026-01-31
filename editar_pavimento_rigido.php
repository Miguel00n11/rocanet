<?php
require_once __DIR__ . '/auth.php';
include("cabeza.php");
include("conexion.php");

// Obtener el ID del diseño a editar
$id_pavimento = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_pavimento == 0) {
    echo "<script>alert('ID de diseño no válido'); window.location.href='pavimento_rigido.php';</script>";
    exit;
}

// Consultar datos del diseño
$sql_pavimento = "SELECT * FROM pavimento_rigido WHERE id_pavimento_rigido = ?";
$stmt = $conexion->prepare($sql_pavimento);
$stmt->bind_param("i", $id_pavimento);
$stmt->execute();
$resultado_pavimento = $stmt->get_result();

if ($resultado_pavimento->num_rows == 0) {
    echo "<script>alert('Diseño no encontrado'); window.location.href='pavimento_rigido.php';</script>";
    exit;
}

$pavimento = $resultado_pavimento->fetch_assoc();
$stmt->close();

// Consultar sondeos y estratos asociados
$sql_sondeos = "SELECT * FROM sondeos WHERE id_pavimento_rigido = ? ORDER BY numero_sondeo";
$stmt_sondeos = $conexion->prepare($sql_sondeos);
$stmt_sondeos->bind_param("i", $id_pavimento);
$stmt_sondeos->execute();
$resultado_sondeos = $stmt_sondeos->get_result();
$sondeos = $resultado_sondeos->fetch_all(MYSQLI_ASSOC);
$stmt_sondeos->close();

// Para cada sondeo, obtener sus estratos
foreach ($sondeos as &$sondeo) {
    $sql_estratos = "SELECT * FROM estratos WHERE id_sondeo = ? ORDER BY id_estrato";
    $stmt_estratos = $conexion->prepare($sql_estratos);
    $stmt_estratos->bind_param("i", $sondeo['id_sondeo']);
    $stmt_estratos->execute();
    $resultado_estratos = $stmt_estratos->get_result();
    $sondeo['estratos'] = $resultado_estratos->fetch_all(MYSQLI_ASSOC);
    $stmt_estratos->close();
}
unset($sondeo);
?>

<style>
	.sondeos-table {
		margin-top: 20px;
	}

	.sondeos-table th {
		background-color: #667eea;
		color: white;
		padding: 10px;
		text-align: center;
	}

	.sondeos-table td {
		padding: 8px;
	}

	.btn-remove-row {
		background-color: #dc3545;
		color: white;
		border: none;
		padding: 5px 10px;
		border-radius: 4px;
		cursor: pointer;
	}

	.btn-remove-row:hover {
		background-color: #c82333;
	}

	.btn-add-row {
		background-color: #28a745;
		color: white;
		border: none;
		padding: 8px 16px;
		border-radius: 4px;
		cursor: pointer;
		margin-top: 10px;
	}

	.btn-add-row:hover {
		background-color: #218838;
	}
</style>

<!-- BEGIN #content -->
<div id="content" class="app-content">
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="pavimento_rigido.php">DISEÑO DE PAVIMENTOS</a></li>
		<li class="breadcrumb-item active">EDITAR DISEÑO</li>
	</ul>

	<h1 class="page-header">
		Editar Diseño de Pavimento Rígido
		<small>Modificar información del diseño #<?= $id_pavimento ?></small>
	</h1>

	<form id="formPavimento" method="POST" action="actualizar_pavimento_rigido.php">
		<input type="hidden" name="id_pavimento_rigido" value="<?= $id_pavimento ?>">
		
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0">INFORMACIÓN GENERAL</h5>
			</div>
			<div class="card-body">
				<div class="row mb-3">
					<div class="col-md-8">
						<label for="obra" class="form-label">Proyecto *</label>
						<input type="text" class="form-control" id="obra" name="obra" 
							   value="<?= htmlspecialchars($pavimento['obra'] ?? '') ?>"
							   placeholder="Ingrese el nombre del proyecto" required>
					</div>
					<div class="col-md-4">
						<label for="tipo_pavimento" class="form-label">Tipo de Pavimento *</label>
						<select class="form-select" id="tipo_pavimento" name="tipo_pavimento" required>
							<option value="">Seleccione...</option>
							<option value="CONCRETO RÍGIDO" <?= ($pavimento['tipo_pavimento'] ?? '') == 'CONCRETO RÍGIDO' ? 'selected' : '' ?>>CONCRETO RÍGIDO</option>
							<option value="CONCRETO RIGIDO CON PIEDRA AHOGADA" <?= ($pavimento['tipo_pavimento'] ?? '') == 'CONCRETO RIGIDO CON PIEDRA AHOGADA' ? 'selected' : '' ?>>CONCRETO RIGIDO CON PIEDRA AHOGADA</option>
							<option value="PAVIMENTO FLEXIBLE" <?= ($pavimento['tipo_pavimento'] ?? '') == 'PAVIMENTO FLEXIBLE' ? 'selected' : '' ?>>PAVIMENTO FLEXIBLE</option>
						</select>
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-md-6">
						<label for="ubicacion" class="form-label">Ubicación</label>
						<input type="text" class="form-control" id="ubicacion" name="ubicacion" 
							   value="<?= htmlspecialchars($pavimento['ubicacion'] ?? '') ?>"
							   placeholder="Ingrese la ubicación">
					</div>
					<div class="col-md-6">
						<label for="cliente" class="form-label">Cliente</label>
						<input type="text" class="form-control" id="cliente" name="cliente" 
							   value="<?= htmlspecialchars($pavimento['cliente'] ?? '') ?>"
							   placeholder="Ingrese el nombre del cliente">
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-md-6">
						<label for="expediente" class="form-label">Expediente</label>
						<input type="text" class="form-control" id="expediente" name="expediente" 
							   value="<?= htmlspecialchars($pavimento['expediente'] ?? '') ?>"
							   placeholder="Ingrese el número de expediente">
					</div>
					<div class="col-md-6">
						<label for="fecha_estudio" class="form-label">Fecha de Realización</label>
						<input type="date" class="form-control" id="fecha_estudio" name="fecha_estudio" 
							   value="<?= htmlspecialchars($pavimento['fecha_estudio'] ?? '') ?>">
					</div>
				</div>
			</div>
		</div>

		<div class="card mt-3">
			<div class="card-header">
				<h5 class="mb-0">CARACTERÍSTICAS DEL PAVIMENTO</h5>
			</div>
			<div class="card-body">
				<div class="row mb-3">
					<div class="col-md-3">
						<label for="espesor_concreto" class="form-label">Espesor Concreto</label>
						<input type="text" class="form-control" id="espesor_concreto" name="espesor_concreto" 
							   value="<?= htmlspecialchars($pavimento['espesor_concreto'] ?? '') ?>" placeholder="Ej: 20 cm">
					</div>
					<div class="col-md-3">
						<label for="base" class="form-label">Base</label>
						<input type="text" class="form-control" id="base" name="base" 
							   value="<?= htmlspecialchars($pavimento['base'] ?? '') ?>" placeholder="Ej: 15 cm">
					</div>
					<div class="col-md-3">
						<label for="subrasante" class="form-label">Subrasante</label>
						<input type="text" class="form-control" id="subrasante" name="subrasante" 
							   value="<?= htmlspecialchars($pavimento['subrasante'] ?? '') ?>" placeholder="Ej: 10 cm">
					</div>
					<div class="col-md-3">
						<label for="pedraplen" class="form-label">Pedraplen</label>
						<input type="text" class="form-control" id="pedraplen" name="pedraplen" 
							   value="<?= htmlspecialchars($pavimento['pedraplen'] ?? '') ?>" placeholder="Ej: 30 cm">
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-md-3">
						<label for="municipio" class="form-label">Municipio</label>
						<input type="text" class="form-control" id="municipio" name="municipio" 
							   value="<?= htmlspecialchars($pavimento['municipio'] ?? '') ?>" placeholder="Ingrese municipio">
					</div>
					<div class="col-md-3">
						<label for="po" class="form-label">Po</label>
						<input type="number" step="0.0001" class="form-control" id="po" name="po" 
							   value="<?= htmlspecialchars($pavimento['po'] ?? '') ?>" placeholder="Ej: 4.5">
					</div>
					<div class="col-md-3">
						<label for="pt" class="form-label">Pt (Clasificación)</label>
						<select class="form-select" id="pt" name="pt">
							<option value="">Seleccione...</option>
							<option value="2.5" <?= ($pavimento['pt'] ?? '') == '2.5' ? 'selected' : '' ?>>2.5 - Autopistas</option>
							<option value="2.0" <?= ($pavimento['pt'] ?? '') == '2.0' || ($pavimento['pt'] ?? '') == '2' ? 'selected' : '' ?>>2.0 - Carreteras / Zona industrial</option>
							<option value="1.8" <?= ($pavimento['pt'] ?? '') == '1.8' ? 'selected' : '' ?>>1.8 - Urbana principal</option>
							<option value="1.5" <?= ($pavimento['pt'] ?? '') == '1.5' ? 'selected' : '' ?>>1.5 - Urbana secundaria</option>
						</select>
					</div>
					<div class="col-md-3">
						<label for="zr" class="form-label">Zr (Confiabilidad)</label>
						<select class="form-select" id="zr" name="zr">
							<option value="">Seleccione...</option>
							<option value="95" <?= ($pavimento['zr'] ?? '') == '95' ? 'selected' : '' ?>>95% - Autopistas</option>
							<option value="90" <?= ($pavimento['zr'] ?? '') == '90' ? 'selected' : '' ?>>90%</option>
							<option value="85" <?= ($pavimento['zr'] ?? '') == '85' ? 'selected' : '' ?>>85%</option>
							<option value="80" <?= ($pavimento['zr'] ?? '') == '80' ? 'selected' : '' ?>>80% - Carreteras</option>
							<option value="75" <?= ($pavimento['zr'] ?? '') == '75' ? 'selected' : '' ?>>75%</option>
							<option value="70" <?= ($pavimento['zr'] ?? '') == '70' ? 'selected' : '' ?>>70% - Rurales / Industriales</option>
							<option value="65" <?= ($pavimento['zr'] ?? '') == '65' ? 'selected' : '' ?>>65% - Industriales</option>
							<option value="60" <?= ($pavimento['zr'] ?? '') == '60' ? 'selected' : '' ?>>60% - Urbanas principales</option>
							<option value="50" <?= ($pavimento['zr'] ?? '') == '50' ? 'selected' : '' ?>>50% - Urbanas Secundarias</option>
						</select>
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-md-3">
						<label for="so" class="form-label">So</label>
						<input type="number" step="0.0001" class="form-control" id="so" name="so" 
							   value="<?= htmlspecialchars($pavimento['so'] ?? '') ?>" placeholder="Ej: 0.35">
					</div>
					<div class="col-md-3">
						<label for="esals" class="form-label">Esal's</label>
						<input type="number" step="0.0001" class="form-control" id="esals" name="esals" 
							   value="<?= htmlspecialchars($pavimento['esals'] ?? '') ?>" placeholder="Ej: 1500000">
					</div>
					<div class="col-md-3">
						<label for="coeficiente_carga" class="form-label">
							Coeficiente de Carga
							<i class="fas fa-question-circle text-info ms-1" 
							   data-bs-toggle="modal" 
							   data-bs-target="#modalCoeficiente"
							   style="cursor: pointer;">
							</i>
						</label>
						<input type="number" step="0.1" class="form-control" id="coeficiente_carga" name="coeficiente_carga" 
							   value="<?= htmlspecialchars($pavimento['coeficiente_carga'] ?? '') ?>" placeholder="Ej: 3.2">
					</div>
					<div class="col-md-3">
						<label for="mr" class="form-label">
							MR
							<i class="fas fa-question-circle text-info ms-1" 
							   data-bs-toggle="modal" 
							   data-bs-target="#modalMR"
							   style="cursor: pointer;">
							</i>
						</label>
						<input type="number" step="0.0001" class="form-control" id="mr" name="mr" 
							   value="<?= htmlspecialchars($pavimento['mr'] ?? '') ?>" placeholder="Ej: 700">
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-md-3">
						<label for="cd" class="form-label">
							Cd
							<i class="fas fa-question-circle text-info ms-1" 
							   data-bs-toggle="modal" 
							   data-bs-target="#modalCd"
							   style="cursor: pointer;">
							</i>
						</label>
						<input type="number" step="0.01" class="form-control" id="cd" name="cd" 
							   value="<?= htmlspecialchars($pavimento['cd'] ?? '') ?>" placeholder="Ej: 1.0">
					</div>
					<div class="col-md-3">
						<label for="k_infinito" class="form-label">K infinito</label>
						<input type="number" step="0.0001" class="form-control" id="k_infinito" name="k_infinito" 
							   value="<?= htmlspecialchars($pavimento['k_infinito'] ?? '') ?>" placeholder="Ej: 150">
					</div>
					<div class="col-md-3">
						<label for="k_tabla" class="form-label">K tabla</label>
						<input type="number" step="0.0001" class="form-control" id="k_tabla" name="k_tabla" 
							   value="<?= htmlspecialchars($pavimento['k_tabla'] ?? '') ?>" placeholder="Ej: 100">
					</div>
					<div class="col-md-3">
						<label for="ancho_vialidad" class="form-label">Ancho de Vialidad</label>
						<input type="number" step="0.01" class="form-control" id="ancho_vialidad" name="ancho_vialidad" 
							   value="<?= htmlspecialchars($pavimento['ancho_vialidad'] ?? '') ?>" placeholder="Ej: 7.5">
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-md-3">
						<label for="numero_franjas" class="form-label">Número de Franjas</label>
						<input type="number" class="form-control" id="numero_franjas" name="numero_franjas" 
							   value="<?= htmlspecialchars($pavimento['numero_franjas'] ?? '') ?>" placeholder="Ej: 2">
					</div>
					<div class="col-md-3">
						<label for="dimension_x" class="form-label">Dimensión en X</label>
						<input type="number" step="0.01" class="form-control" id="dimension_x" name="dimension_x" 
							   value="<?= htmlspecialchars($pavimento['dimension_x'] ?? '') ?>" placeholder="Ej: 4.0">
					</div>
					<div class="col-md-3">
						<label for="dimension_y" class="form-label">Dimensión en Y</label>
						<input type="number" step="0.01" class="form-control" id="dimension_y" name="dimension_y" 
							   value="<?= htmlspecialchars($pavimento['dimension_y'] ?? '') ?>" placeholder="Ej: 4.5">
					</div>
					<div class="col-md-3">
						<label for="diametro_pasajuntas" class="form-label">
							Diámetro de Pasajuntas
							<i class="fas fa-question-circle text-info ms-1" 
							   data-bs-toggle="modal" 
							   data-bs-target="#modalPasajuntas"
							   style="cursor: pointer;">
							</i>
						</label>
						<select class="form-select" id="diametro_pasajuntas" name="diametro_pasajuntas">
							<option value="">Seleccione...</option>
							<option value="0.75" <?= ($pavimento['diametro_pasajuntas'] ?? '') == '0.75' ? 'selected' : '' ?>>3/4" (19 mm)</option>
							<option value="1" <?= ($pavimento['diametro_pasajuntas'] ?? '') == '1' ? 'selected' : '' ?>>1" (25 mm)</option>
							<option value="1.25" <?= ($pavimento['diametro_pasajuntas'] ?? '') == '1.25' ? 'selected' : '' ?>>1 1/4" (32 mm)</option>
							<option value="1.5" <?= ($pavimento['diametro_pasajuntas'] ?? '') == '1.5' ? 'selected' : '' ?>>1 1/2" (38 mm)</option>
						</select>
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-md-3">
						<label for="diametro_barras_amarre" class="form-label">Diámetro de Barras de Amarre</label>
						<select class="form-select" id="diametro_barras_amarre" name="diametro_barras_amarre">
							<option value="">Seleccione...</option>
							<option value="0.5" <?= ($pavimento['diametro_barras_amarre'] ?? '') == '0.5' ? 'selected' : '' ?>>1/2"</option>
							<option value="0.75" <?= ($pavimento['diametro_barras_amarre'] ?? '') == '0.75' ? 'selected' : '' ?>>3/4"</option>
							<option value="1" <?= ($pavimento['diametro_barras_amarre'] ?? '') == '1' ? 'selected' : '' ?>>1"</option>
							<option value="1.25" <?= ($pavimento['diametro_barras_amarre'] ?? '') == '1.25' ? 'selected' : '' ?>>1 1/4"</option>
						</select>
					</div>
				</div>
			</div>
		</div>

		<div class="card mt-3">
			<div class="card-header d-flex justify-content-between align-items-center">
				<h5 class="mb-0">SONDEOS</h5>
				<button type="button" class="btn-add-row" onclick="agregarSondeo()">
					<i class="fas fa-plus me-1"></i> Agregar Sondeo
				</button>
			</div>
			<div class="card-body" id="sondeosContainer">
				<?php if (count($sondeos) > 0): ?>
					<?php foreach ($sondeos as $index => $sondeo): ?>
						<div class="sondeo-item mb-3" data-sondeo="<?= $sondeo['numero_sondeo'] ?>">
							<input type="hidden" name="sondeo[<?= $sondeo['numero_sondeo'] ?>][id_sondeo]" value="<?= $sondeo['id_sondeo'] ?>">
							<div class="card">
								<div class="card-header d-flex justify-content-between align-items-center bg-light">
									<h6 class="mb-0">Sondeo #<span class="numero-sondeo"><?= $sondeo['numero_sondeo'] ?></span></h6>
									<div>
										<button type="button" class="btn btn-sm btn-success me-2" onclick="agregarEstrato(<?= $sondeo['numero_sondeo'] ?>)">
											<i class="fas fa-plus"></i> Agregar Estrato
										</button>
										<button type="button" class="btn btn-sm btn-danger" onclick="eliminarSondeo(<?= $sondeo['numero_sondeo'] ?>)">
											<i class="fas fa-trash"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-bordered table-sm estratos-table">
											<thead>
												<tr>
													<th style="width: 25%;">Espesores</th>
													<th style="width: 70%;">Descripción</th>
													<th style="width: 5%;">Acciones</th>
												</tr>
											</thead>
											<tbody class="estratos-list" data-sondeo="<?= $sondeo['numero_sondeo'] ?>">
												<?php if (count($sondeo['estratos']) > 0): ?>
													<?php foreach ($sondeo['estratos'] as $estrato): ?>
														<tr>
															<input type="hidden" name="sondeo[<?= $sondeo['numero_sondeo'] ?>][id_estrato][]" value="<?= $estrato['id_estrato'] ?>">
															<td><input type="text" class="form-control form-control-sm" name="sondeo[<?= $sondeo['numero_sondeo'] ?>][espesores][]" 
																	   value="<?= htmlspecialchars($estrato['espesores'] ?? '') ?>" placeholder="20 cm"></td>
															<td><input type="text" class="form-control form-control-sm" name="sondeo[<?= $sondeo['numero_sondeo'] ?>][descripcion][]" 
															   value="<?= htmlspecialchars($estrato['descripcion'] ?? '') ?>" list="listaMateriales" placeholder="Seleccione o escriba la descripción"></td>
															<td class="text-center">
																<button type="button" class="btn btn-sm btn-danger btn-remove-estrato" onclick="eliminarEstrato(this, <?= $sondeo['numero_sondeo'] ?>)">
																	<i class="fas fa-trash"></i>
																</button>
															</td>
														</tr>
													<?php endforeach; ?>
												<?php else: ?>
													<tr>
														<td><input type="text" class="form-control form-control-sm" name="sondeo[<?= $sondeo['numero_sondeo'] ?>][espesores][]" placeholder="20 cm"></td>
															<td><input type="text" class="form-control form-control-sm" name="sondeo[<?= $sondeo['numero_sondeo'] ?>][descripcion][]" list="listaMateriales" placeholder="Seleccione o escriba la descripción"></td>
														<td class="text-center">
															<button type="button" class="btn btn-sm btn-danger btn-remove-estrato" onclick="eliminarEstrato(this, <?= $sondeo['numero_sondeo'] ?>)" disabled>
																<i class="fas fa-trash"></i>
															</button>
														</td>
													</tr>
												<?php endif; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="sondeo-item mb-3" data-sondeo="1">
						<div class="card">
							<div class="card-header d-flex justify-content-between align-items-center bg-light">
								<h6 class="mb-0">Sondeo #<span class="numero-sondeo">1</span></h6>
								<div>
									<button type="button" class="btn btn-sm btn-success me-2" onclick="agregarEstrato(1)">
										<i class="fas fa-plus"></i> Agregar Estrato
									</button>
									<button type="button" class="btn btn-sm btn-danger" onclick="eliminarSondeo(1)" disabled>
										<i class="fas fa-trash"></i>
									</button>
								</div>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-bordered table-sm estratos-table">
										<thead>
											<tr>
												<th style="width: 25%;">Espesores</th>
												<th style="width: 70%;">Descripción</th>
												<th style="width: 5%;">Acciones</th>
											</tr>
										</thead>
										<tbody class="estratos-list" data-sondeo="1">
											<tr>
												<td><input type="text" class="form-control form-control-sm" name="sondeo[1][espesores][]" placeholder="20 cm"></td>
															<td><input type="text" class="form-control form-control-sm" name="sondeo[1][descripcion][]" list="listaMateriales" placeholder="Seleccione o escriba la descripción"></td>
												<td class="text-center">
													<button type="button" class="btn btn-sm btn-danger btn-remove-estrato" onclick="eliminarEstrato(this, 1)" disabled>
														<i class="fas fa-trash"></i>
													</button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- Datalist con opciones predefinidas -->
		<datalist id="listaMateriales">
			<option value="BOLEO">
			<option value="PIEDRA">
			<option value="ROCA">
			<option value="ROCA EMPACADA">
			<option value="CAPA VEGETAL">
			<option value="RELLENO NO CONTROLADO">
			<option value="MATERIAL DE BANCO">
			<option value="ARCILLA DE ALTA PLASTICIDAD">
			<option value="ARCILLA DE MEDIA PLASTICIDAD">
			<option value="ARCILLA DE BAJA PLASTICIDAD">
			<option value="LIMO DE ALTA PLASTICIDAD">
			<option value="LIMO DE MEDIA PLASTICIDAD">
			<option value="LIMO DE BAJA PLASTICIDAD">
			<option value="ARENA ARCILLOSA">
			<option value="ARENA LIMOSA">
			<option value="ARENA BIEN GRADUADA">
			<option value="GRAVA ARCILLOSA">
			<option value="GRAVA ARENOSA">
			<option value="GRAVA MAL GRADUADA">
		</datalist>

		<div class="card mt-3">
			<div class="card-body text-end">
				<a href="pavimento_rigido.php" class="btn btn-secondary me-2">
					<i class="fas fa-times me-1"></i> Cancelar
				</a>
				<button type="submit" class="btn btn-theme">
					<i class="fas fa-save me-1"></i> Actualizar Diseño
				</button>
			</div>
		</div>
	</form>

	<!-- Modal para Tabla de Coeficiente de Carga -->
	<div class="modal fade" id="modalCoeficiente" tabindex="-1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Tabla de Referencia - Coeficiente de Carga</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<table class="table table-bordered table-hover">
						<thead class="table-dark">
							<tr>
								<th rowspan="2" class="text-center align-middle">Millones de ESAL's</th>
								<th colspan="2" class="text-center">Trabazón de Agregados</th>
								<th colspan="2" class="text-center">Con Pasajuntas</th>
							</tr>
							<tr>
								<th class="text-center">Con soporte Lateral</th>
								<th class="text-center">Sin soporte Lateral</th>
								<th class="text-center">Con soporte Lateral</th>
								<th class="text-center">Sin soporte Lateral</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Menos de 0.3</td>
								<td class="text-center">2.8</td>
								<td class="text-center">3.2</td>
								<td class="text-center" rowspan="6">2.7</td>
								<td class="text-center" rowspan="6">3.2</td>
							</tr>
							<tr>
								<td>0.3 a 1.0</td>
								<td class="text-center">3.0</td>
								<td class="text-center">3.4</td>
							</tr>
							<tr>
								<td>1.0 a 3.0</td>
								<td class="text-center">3.1</td>
								<td class="text-center">3.6</td>
							</tr>
							<tr>
								<td>3 a 10</td>
								<td class="text-center">3.2</td>
								<td class="text-center">3.8</td>
							</tr>
							<tr>
								<td>10 a 30</td>
								<td class="text-center">3.4</td>
								<td class="text-center">4.1</td>
							</tr>
							<tr>
								<td>Más de 30</td>
								<td class="text-center">3.6</td>
								<td class="text-center">4.3</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal para Tabla de MR -->
	<div class="modal fade" id="modalMR" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Tabla de Referencia - Módulo de Ruptura (MR)</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<table class="table table-bordered table-hover">
						<thead class="table-dark">
							<tr>
								<th class="text-center">Pavimento</th>
								<th colspan="2" class="text-center">Módulo de Ruptura</th>
							</tr>
							<tr>
								<th></th>
								<th class="text-center">Kg/cm²</th>
								<th class="text-center">psi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Autopistas</td>
								<td class="text-center">48.00</td>
								<td class="text-center">682.7</td>
							</tr>
							<tr>
								<td>Carreteras</td>
								<td class="text-center">48.00</td>
								<td class="text-center">682.7</td>
							</tr>
							<tr>
								<td>Zonas industriales</td>
								<td class="text-center">45.00</td>
								<td class="text-center">640.1</td>
							</tr>
							<tr>
								<td>Urbanas principales</td>
								<td class="text-center">45.00</td>
								<td class="text-center">640.1</td>
							</tr>
							<tr>
								<td>Urbanas secundarias</td>
								<td class="text-center">42.00</td>
								<td class="text-center">597.4</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal para Tabla de Cd -->
	<div class="modal fade" id="modalCd" tabindex="-1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Tabla de Referencia - Coeficiente de Drenaje (Cd)</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<table class="table table-bordered table-hover">
						<thead class="table-dark">
							<tr>
								<th class="text-center">Calidad del Drenaje</th>
								<th class="text-center">Menos de 1 %</th>
								<th class="text-center">1 - 5 %</th>
								<th class="text-center">5 - 25 %</th>
								<th class="text-center">Más de 25 %</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><strong>Excelente</strong></td>
								<td class="text-center">1.25 - 1.20</td>
								<td class="text-center">1.20 - 1.15</td>
								<td class="text-center">1.10</td>
								<td class="text-center">1.10</td>
							</tr>
							<tr>
								<td><strong>Bueno</strong></td>
								<td class="text-center">1.20 - 1.15</td>
								<td class="text-center">1.10</td>
								<td class="text-center">1.05</td>
								<td class="text-center">1.00</td>
							</tr>
							<tr>
								<td><strong>Regular</strong></td>
								<td class="text-center">1.10</td>
								<td class="text-center">1.05</td>
								<td class="text-center">1.00</td>
								<td class="text-center">0.90</td>
							</tr>
							<tr>
								<td><strong>Pobre</strong></td>
								<td class="text-center">1.05</td>
								<td class="text-center">1.00</td>
								<td class="text-center">0.90 - 0.80</td>
								<td class="text-center">0.80</td>
							</tr>
							<tr>
								<td><strong>Muy Pobre</strong></td>
								<td class="text-center">1.00</td>
								<td class="text-center">0.90 - 0.80</td>
								<td class="text-center">0.80 - 0.70</td>
								<td class="text-center">0.70</td>
							</tr>
						</tbody>
					</table>
					<p class="text-muted small mb-0"><em>Nota: Los valores corresponden al porcentaje de tiempo que el pavimento está expuesto a niveles de humedad próximos a saturación.</em></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal para Tabla de Pasajuntas -->
	<div class="modal fade" id="modalPasajuntas" tabindex="-1">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Tabla de Referencia - Especificaciones de Pasajuntas y Barras</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<table class="table table-bordered table-hover table-sm">
						<thead class="table-dark">
							<tr>
								<th rowspan="2" class="text-center align-middle">Espesor de Losa (cm)</th>
								<th rowspan="2" class="text-center align-middle">Espesor de Losa (in)</th>
								<th rowspan="2" class="text-center align-middle">Diámetro (mm)</th>
								<th rowspan="2" class="text-center align-middle">Diámetro (in)</th>
								<th colspan="2" class="text-center">Barras Pasajuntas</th>
								<th colspan="2" class="text-center">Barras Pasajuntas Separación</th>
							</tr>
							<tr>
								<th class="text-center">Longitud (cm)</th>
								<th class="text-center">Longitud (in)</th>
								<th class="text-center">(cm)</th>
								<th class="text-center">(in)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-center">13 a 15</td>
								<td class="text-center">5 a 6</td>
								<td class="text-center">19</td>
								<td class="text-center">3/4"</td>
								<td class="text-center">41</td>
								<td class="text-center">16</td>
								<td class="text-center">30</td>
								<td class="text-center">12</td>
							</tr>
							<tr>
								<td class="text-center">15 a 20</td>
								<td class="text-center">6 a 8</td>
								<td class="text-center">25</td>
								<td class="text-center">1"</td>
								<td class="text-center">45</td>
								<td class="text-center">18</td>
								<td class="text-center">30</td>
								<td class="text-center">12</td>
							</tr>
							<tr class="table-success">
								<td class="text-center"><strong>20 a 30</strong></td>
								<td class="text-center"><strong>8 a 12</strong></td>
								<td class="text-center"><strong>32</strong></td>
								<td class="text-center"><strong>1 1/4"</strong></td>
								<td class="text-center"><strong>45</strong></td>
								<td class="text-center"><strong>18</strong></td>
								<td class="text-center"><strong>30</strong></td>
								<td class="text-center"><strong>12</strong></td>
							</tr>
							<tr>
								<td class="text-center">30 a 43</td>
								<td class="text-center">12 a 17</td>
								<td class="text-center">38</td>
								<td class="text-center">1 1/2"</td>
								<td class="text-center">51</td>
								<td class="text-center">20</td>
								<td class="text-center">38</td>
								<td class="text-center">15</td>
							</tr>
							<tr>
								<td class="text-center">43 a 50</td>
								<td class="text-center">17 a 20</td>
								<td class="text-center">45</td>
								<td class="text-center">1 3/4"</td>
								<td class="text-center">56</td>
								<td class="text-center">22</td>
								<td class="text-center">46</td>
								<td class="text-center">18</td>
							</tr>
						</tbody>
					</table>
					<p class="text-muted small mb-0"><em>Nota: La fila resaltada (20 a 30 cm) es la configuración más común para pavimentos rígidos.</em></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END #content -->

<script>
	let contadorSondeos = <?= count($sondeos) > 0 ? max(array_column($sondeos, 'numero_sondeo')) : 1 ?>;

	// Inicializar popovers
	$(document).ready(function() {
		var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
		var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
			return new bootstrap.Popover(popoverTriggerEl, {
				container: 'body'
			});
		});
	});

	function agregarSondeo() {
		contadorSondeos++;
		const container = document.getElementById('sondeosContainer');
		const nuevoSondeo = document.createElement('div');
		nuevoSondeo.className = 'sondeo-item mb-3';
		nuevoSondeo.setAttribute('data-sondeo', contadorSondeos);
		nuevoSondeo.innerHTML = `
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center bg-light">
					<h6 class="mb-0">Sondeo #<span class="numero-sondeo">${contadorSondeos}</span></h6>
					<div>
						<button type="button" class="btn btn-sm btn-success me-2" onclick="agregarEstrato(${contadorSondeos})">
							<i class="fas fa-plus"></i> Agregar Estrato
						</button>
						<button type="button" class="btn btn-sm btn-danger" onclick="eliminarSondeo(${contadorSondeos})">
							<i class="fas fa-trash"></i>
						</button>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered table-sm estratos-table">
							<thead>
								<tr>
									<th style="width: 25%;">Espesores</th>
									<th style="width: 70%;">Descripción</th>
									<th style="width: 5%;">Acciones</th>
								</tr>
							</thead>
							<tbody class="estratos-list" data-sondeo="${contadorSondeos}">
								<tr>
									<td><input type="text" class="form-control form-control-sm" name="sondeo[${contadorSondeos}][espesores][]" placeholder="20 cm"></td>
										<td><input type="text" class="form-control form-control-sm" name="sondeo[${contadorSondeos}][descripcion][]" list="listaMateriales" placeholder="Seleccione o escriba la descripción"></td>
									<td class="text-center">
										<button type="button" class="btn btn-sm btn-danger btn-remove-estrato" onclick="eliminarEstrato(this, ${contadorSondeos})" disabled>
											<i class="fas fa-trash"></i>
										</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		`;
		container.appendChild(nuevoSondeo);
		actualizarBotonesEliminarSondeos();
	}

	function eliminarSondeo(numeroSondeo) {
		const sondeo = document.querySelector(`.sondeo-item[data-sondeo="${numeroSondeo}"]`);
		if (sondeo) {
			sondeo.remove();
			renumerarSondeos();
			actualizarBotonesEliminarSondeos();
		}
	}

	function renumerarSondeos() {
		const sondeos = document.querySelectorAll('.sondeo-item');
		sondeos.forEach((sondeo, index) => {
			const nuevoNumero = index + 1;
			const numeroActual = sondeo.getAttribute('data-sondeo');
			sondeo.setAttribute('data-sondeo', nuevoNumero);
			sondeo.querySelector('.numero-sondeo').textContent = nuevoNumero;
			
			// Actualizar hidden input de id_sondeo si existe
			const hiddenIdSondeo = sondeo.querySelector('input[name^="sondeo["][name$="[id_sondeo]"]');
			if (hiddenIdSondeo) {
				const oldName = hiddenIdSondeo.getAttribute('name');
				hiddenIdSondeo.setAttribute('name', oldName.replace(/sondeo\[\d+\]/, `sondeo[${nuevoNumero}]`));
			}
			
			// Actualizar atributos de botones
			const btnAgregar = sondeo.querySelector('button[onclick^="agregarEstrato"]');
			btnAgregar.setAttribute('onclick', `agregarEstrato(${nuevoNumero})`);
			
			const btnEliminar = sondeo.querySelector('button[onclick^="eliminarSondeo"]');
			btnEliminar.setAttribute('onclick', `eliminarSondeo(${nuevoNumero})`);
			
			// Actualizar tbody data-sondeo
			const tbody = sondeo.querySelector('.estratos-list');
			tbody.setAttribute('data-sondeo', nuevoNumero);
			
			// Actualizar nombres de inputs (incluyendo hidden id_estrato)
			const rows = tbody.querySelectorAll('tr');
			rows.forEach(row => {
				const inputs = row.querySelectorAll('input');
				inputs.forEach(input => {
					const name = input.getAttribute('name');
					const newName = name.replace(/sondeo\[\d+\]/, `sondeo[${nuevoNumero}]`);
					input.setAttribute('name', newName);
				});
				
				// Actualizar botón de eliminar estrato
				const btn = row.querySelector('.btn-remove-estrato');
				if (btn) {
					const onclick = btn.getAttribute('onclick');
					const newOnclick = onclick.replace(/eliminarEstrato\(this,\s*\d+\)/, `eliminarEstrato(this, ${nuevoNumero})`);
					btn.setAttribute('onclick', newOnclick);
				}
			});
		});
		contadorSondeos = sondeos.length;
	}

	function agregarEstrato(numeroSondeo) {
		const tbody = document.querySelector(`.estratos-list[data-sondeo="${numeroSondeo}"]`);
		const nuevaFila = document.createElement('tr');
		nuevaFila.innerHTML = `
			<td><input type="text" class="form-control form-control-sm" name="sondeo[${numeroSondeo}][espesores][]" placeholder="20 cm"></td>
			<td><input type="text" class="form-control form-control-sm" name="sondeo[${numeroSondeo}][descripcion][]" list="listaMateriales" placeholder="Seleccione o escriba la descripción"></td>
			<td class="text-center">
				<button type="button" class="btn btn-sm btn-danger btn-remove-estrato" onclick="eliminarEstrato(this, ${numeroSondeo})">
					<i class="fas fa-trash"></i>
				</button>
			</td>
		`;
		tbody.appendChild(nuevaFila);
		actualizarBotonesEliminarEstratos(numeroSondeo);
	}

	function eliminarEstrato(boton, numeroSondeo) {
		const fila = boton.closest('tr');
		fila.remove();
		actualizarBotonesEliminarEstratos(numeroSondeo);
	}

	function actualizarBotonesEliminarEstratos(numeroSondeo) {
		const tbody = document.querySelector(`.estratos-list[data-sondeo="${numeroSondeo}"]`);
		const botones = tbody.querySelectorAll('.btn-remove-estrato');
		botones.forEach(boton => {
			boton.disabled = (botones.length === 1);
		});
	}

	function actualizarBotonesEliminarSondeos() {
		const sondeos = document.querySelectorAll('.sondeo-item');
		sondeos.forEach(sondeo => {
			const btnEliminar = sondeo.querySelector('button[onclick^="eliminarSondeo"]');
			btnEliminar.disabled = (sondeos.length === 1);
		});
	}

	// Inicializar botones al cargar
	document.addEventListener('DOMContentLoaded', function() {
		actualizarBotonesEliminarSondeos();
		const sondeos = document.querySelectorAll('.sondeo-item');
		sondeos.forEach(sondeo => {
			const numeroSondeo = sondeo.getAttribute('data-sondeo');
			actualizarBotonesEliminarEstratos(numeroSondeo);
		});
	});

	// Validar formulario antes de enviar
	document.getElementById('formPavimento').addEventListener('submit', function(e) {
		const expediente = document.getElementById('expediente').value;
		if (!expediente) {
			e.preventDefault();
			alert('Por favor seleccione una obra');
			return false;
		}
	});

	// Inicializar botones al cargar
	actualizarBotonesEliminar();
</script>

<?php 
$conexion->close();
include("pie.php"); 
?>
