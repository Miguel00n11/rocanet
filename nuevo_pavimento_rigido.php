<?php
require_once __DIR__ . '/auth.php';
include("cabeza.php");
include("conexion.php");
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
		<li class="breadcrumb-item active">NUEVO DISEÑO</li>
	</ul>

	<h1 class="page-header">
		Nuevo Diseño de Pavimento Rígido
		<small>Agregar información del diseño</small>
	</h1>

	<form id="formPavimento" method="POST" action="guardar_pavimento_rigido.php">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0">INFORMACIÓN GENERAL</h5>
			</div>
			<div class="card-body">
				<div class="row mb-3">
					<div class="col-md-8">
						<label for="obra" class="form-label">Proyecto *</label>
						<input type="text" class="form-control" id="obra" name="obra" 
							   placeholder="Ingrese el nombre del proyecto" required>
					</div>
					<div class="col-md-4">
						<label for="tipo_pavimento" class="form-label">Tipo de Pavimento *</label>
						<select class="form-select" id="tipo_pavimento" name="tipo_pavimento" required>
							<option value="">Seleccione...</option>
							<option value="CONCRETO RÍGIDO">CONCRETO RÍGIDO</option>
							<option value="CONCRETO RIGIDO CON PIEDRA AHOGADA">CONCRETO RIGIDO CON PIEDRA AHOGADA</option>
							<option value="PAVIMENTO FLEXIBLE">PAVIMENTO FLEXIBLE</option>
						</select>
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-md-6">
						<label for="ubicacion" class="form-label">Ubicación</label>
						<input type="text" class="form-control" id="ubicacion" name="ubicacion" 
							   placeholder="Ingrese la ubicación">
					</div>
					<div class="col-md-6">
						<label for="cliente" class="form-label">Cliente</label>
						<input type="text" class="form-control" id="cliente" name="cliente" 
							   placeholder="Ingrese el nombre del cliente">
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-md-6">
						<label for="expediente" class="form-label">Expediente</label>
						<input type="text" class="form-control" id="expediente" name="expediente" 
							   placeholder="Ingrese el número de expediente">
					</div>
					<div class="col-md-6">
						<label for="fecha_estudio" class="form-label">Fecha de Realización</label>
						<input type="date" class="form-control" id="fecha_estudio" name="fecha_estudio">
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
						<input type="text" class="form-control" id="espesor_concreto" name="espesor_concreto" placeholder="Ej: 20 cm">
					</div>
					<div class="col-md-3">
						<label for="base" class="form-label">Base</label>
						<input type="text" class="form-control" id="base" name="base" placeholder="Ej: 15 cm">
					</div>
					<div class="col-md-3">
						<label for="subrasante" class="form-label">Subrasante</label>
						<input type="text" class="form-control" id="subrasante" name="subrasante" placeholder="Ej: 10 cm">
					</div>
					<div class="col-md-3">
						<label for="pedraplen" class="form-label">Pedraplen</label>
						<input type="text" class="form-control" id="pedraplen" name="pedraplen" placeholder="Ej: 30 cm">
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-md-3">
						<label for="municipio" class="form-label">Municipio</label>
						<input type="text" class="form-control" id="municipio" name="municipio" placeholder="Ingrese municipio">
					</div>
					<div class="col-md-3">
						<label for="po" class="form-label">Po</label>
						<input type="number" step="0.0001" class="form-control" id="po" name="po" placeholder="Ej: 4.5">
					</div>
					<div class="col-md-3">
						<label for="pt" class="form-label">Pt</label>
						<input type="number" step="0.0001" class="form-control" id="pt" name="pt" placeholder="Ej: 2.5">
					</div>
					<div class="col-md-3">
						<label for="zr" class="form-label">Zr (Confiabilidad)</label>
						<select class="form-select" id="zr" name="zr">
							<option value="">Seleccione...</option>
							<option value="95">95% - Autopistas</option>
							<option value="90">90%</option>
							<option value="85">85%</option>
							<option value="80">80% - Carreteras</option>
							<option value="75">75%</option>
							<option value="70">70% - Rurales / Industriales</option>
							<option value="65">65% - Industriales</option>
							<option value="60">60% - Urbanas principales</option>
							<option value="50">50% - Urbanas Secundarias</option>
						</select>
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-md-3">
						<label for="so" class="form-label">So</label>
						<input type="number" step="0.0001" class="form-control" id="so" name="so" placeholder="Ej: 0.35">
					</div>
					<div class="col-md-3">
						<label for="esals" class="form-label">Esal's</label>
						<input type="number" step="0.0001" class="form-control" id="esals" name="esals" placeholder="Ej: 1500000">
					</div>
					<div class="col-md-3">
						<label for="coeficiente_carga" class="form-label">Coeficiente de Carga</label>
						<input type="number" step="0.0001" class="form-control" id="coeficiente_carga" name="coeficiente_carga" placeholder="Ej: 0.75">
					</div>
					<div class="col-md-3">
						<label for="mr" class="form-label">MR</label>
						<input type="number" step="0.0001" class="form-control" id="mr" name="mr" placeholder="Ej: 700">
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
				<!-- Los sondeos se agregarán aquí dinámicamente -->
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
											<td>
												<input type="text" class="form-control form-control-sm" name="sondeo[1][descripcion][]" 
													   list="listaMateriales" placeholder="Seleccione o escriba la descripción">
											</td>
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
			<option value="MATERIAL DE RELLENO">
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
					<i class="fas fa-save me-1"></i> Guardar Diseño
				</button>
			</div>
		</div>
	</form>
</div>
<!-- END #content -->

<script>
	let contadorSondeos = 1;

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
			
			// Actualizar atributos de botones
			const btnAgregar = sondeo.querySelector('button[onclick^="agregarEstrato"]');
			btnAgregar.setAttribute('onclick', `agregarEstrato(${nuevoNumero})`);
			
			const btnEliminar = sondeo.querySelector('button[onclick^="eliminarSondeo"]');
			btnEliminar.setAttribute('onclick', `eliminarSondeo(${nuevoNumero})`);
			
			// Actualizar tbody data-sondeo
			const tbody = sondeo.querySelector('.estratos-list');
			tbody.setAttribute('data-sondeo', nuevoNumero);
			
			// Actualizar nombres de inputs
			const inputs = tbody.querySelectorAll('input');
			inputs.forEach(input => {
				const name = input.getAttribute('name');
				const newName = name.replace(/sondeo\[\d+\]/, `sondeo[${nuevoNumero}]`);
				input.setAttribute('name', newName);
			});
			
			// Actualizar botones de eliminar estrato
			const btnsEliminarEstrato = tbody.querySelectorAll('.btn-remove-estrato');
			btnsEliminarEstrato.forEach(btn => {
				const onclick = btn.getAttribute('onclick');
				const newOnclick = onclick.replace(/eliminarEstrato\(this,\s*\d+\)/, `eliminarEstrato(this, ${nuevoNumero})`);
				btn.setAttribute('onclick', newOnclick);
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

	// Validar formulario antes de enviar
	document.getElementById('formPavimento').addEventListener('submit', function(e) {
		const expediente = document.getElementById('expediente').value;
		if (!expediente) {
			e.preventDefault();
			alert('Por favor seleccione una obra');
			return false;
		}
	});
</script>

<?php include("pie.php"); ?>
