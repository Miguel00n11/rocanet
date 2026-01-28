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
					<div class="col-md-12">
						<label for="expediente" class="form-label">Obra / Expediente *</label>
						<input type="text" class="form-control" id="expediente" name="expediente" 
							   placeholder="Ingrese el nombre de la obra o expediente" required>
					</div>
				</div>

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
											<td><input type="text" class="form-control form-control-sm" name="sondeo[1][descripcion][]" placeholder="Descripción del estrato"></td>
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
									<td><input type="text" class="form-control form-control-sm" name="sondeo[${contadorSondeos}][descripcion][]" placeholder="Descripción del estrato"></td>
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
			<td><input type="text" class="form-control form-control-sm" name="sondeo[${numeroSondeo}][descripcion][]" placeholder="Descripción del estrato"></td>
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
