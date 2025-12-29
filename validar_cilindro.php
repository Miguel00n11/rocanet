<style>
	/* Evita que las columnas se encojan demasiado */
	.wide-table th,
	.wide-table td {
		white-space: nowrap;
		min-width: 120px;
		/* Ajusta según lo necesario */
	}

	/* Columna de item (más pequeña) */
	.wide-table th:first-child,
	.wide-table td:first-child {
		min-width: 60px;
		text-align: center;
	}

	/* Columna de f´c (más pequeña) */
	.wide-table th:last-child,
	.wide-table td:last-child {
		min-width: 80px;
		text-align: center;
	}
</style>

<?php

include("cabeza.php");
include("conexion.php");
include("conexion_forta.php");

// Obtener lista de personal
$sqlPersonal = "SELECT ID, Nombre FROM personal ORDER BY Nombre ASC";
$resPersonal = $conexion_forta->query($sqlPersonal);
// Guardar resultados en un arreglo
$personalLista = [];
while ($row = $resPersonal->fetch_assoc()) {
	$personalLista[] = $row;
}

// Obtener lista de obras
$sqlObra = "SELECT * FROM obras JOIN clientes ON obras.cliente=clientes.idcliente ORDER BY id_expediente DESC";
$resObra = $conexion->query($sqlObra);
// Guardar resultados en un arreglo
$expLista = [];
while ($row = $resObra->fetch_assoc()) {
	$expLista[] = $row;
}


/* FIREBASE CONFIG */
$firebaseURL = "https://registrocompactacioneroca-default-rtdb.firebaseio.com";
$auth = "64KDwSjgUkDpEMGcNryDylwJtGQX3XQsGbu4QxwI";

function firebaseGet($ruta)
{
	global $firebaseURL, $auth;

	$segmentos = explode('/', $ruta);
	$segmentos = array_map('rawurlencode', $segmentos);

	$rutaSegura = implode('/', $segmentos);

	$url = "$firebaseURL/$rutaSegura.json?auth=$auth";

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	curl_close($ch);

	return json_decode($response, true);
}


/* PARAMETROS (equivalente al constructor) */
// $usuario = $_GET['usuario'];
$usuario = $_GET['usuario']; // NO urldecode
$llave   = $_GET['llave'] ?? '';

$ruta = "Cilindros/Reportes/$usuario/$llave";
$reporte = firebaseGet($ruta);

if ($reporte === null) {
	die("NO SE ENCONTRÓ EL REPORTE<br>Ruta lógica: $ruta");
}

$cliente = $reporte['cliente'] ?? 'SIN CLIENTE';





/* VARIABLES DEL REPORTE */
$cliente = "";
$id_cliente = "";
$obra = $reporte['obra'] ?? 'SIN OBRA';
// $expediente = $reporte['obra'] ?? 'SIN OBRA';
$localizacion = $reporte['localizacion'] ?? 'SIN LOCALIZACION';
// $reporte = "";
$fecha = $reporte['fecha'] ?? 'SIN FECHA';
$elemento = $reporte['elementoColado'] ?? 'SIN ELEMENTO';
$ubicacion = $reporte['ubicacion'] ?? 'SIN UBICACION';
$fc = $reporte['fc'] ?? 'SIN fc';
$edad = $reporte['edad'] ?? 'SIN edad';
$revenimientop = $reporte['revenimientoDis'] ?? 'SIN revenimientop';
$revenimientor = $reporte['revenimientoR1'] ?? 'SIN revenimiento1';
$tma = $reporte['tma'] ?? 'SIN tma';
$concretera = $reporte['concretera'] ?? 'SIN concretera';
$temperatura = $reporte['temperatura'] ?? 'SIN temperatura';
$remision = $reporte['remision'] ?? 'SIN remision';
$volumen = $reporte['volumenMuestra'] ?? 'SIN volumen muestra';
$hora_muestreo = $reporte['horaMuestreo'] ?? 'SIN hora muestreo';
$hora_desmoldeo = "";
$muestreo = $reporte['personal'] ?? 'SIN personal';
$recibio = "";
$observacion = $reporte['observaciones'] ?? 'SIN observaciones';

// Convertir fecha de Firebase (dd/mm/yyyy) a yyyy-mm-dd
if ($fecha !== 'SIN FECHA' && strpos($fecha, '/') !== false) {
	[$d, $m, $y] = explode('/', $fecha);
	$fecha = "$y-$m-$d";
}

// Crear fecha de recepción = fecha de muestreo + 1 día
$fechaObj = new DateTime($fecha);
$fechaObj->modify('+1 day');
$fecha_recepcion = $fechaObj->format('Y-m-d');

echo "<script>
	alert('CLIENTE: ' + " . json_encode($fecha) . ");
</script>";


$id_reporte_concreto = $_POST['id_reporte_concreto']
	?? $_GET['id_reporte_concreto']
	?? null;


if (isset($_GET['expediente']) && isset($_GET['reporte'])) {

	$exp = $_GET['expediente'];
	$rep = $_GET['reporte'];
	$id_reporte_concreto = $_GET['id_reporte_concreto'];





	// ----

	// // Consulta del registro
	// $sql = "SELECT * FROM reporte_concreto 
	//         WHERE expediente = '$exp' AND reporte = '$rep' 
	//         LIMIT 1";
	// $res = $conexion->query($sql);

	// if ($res->num_rows > 0) {
	// 	$data = $res->fetch_assoc();

	// 	// Llenar variables
	// 	// $cliente       = $data['cliente'];
	// 	// $id_cliente    = $data['id_cliente'];
	// 	// $obra          = $data['obra'];
	// 	$expediente    = $data['expediente'];
	// 	// $localizacion  = $data['ubicacion'];
	// 	$fecha       = $data['fecha'];
	// 	$fecha_recepcion       = $data['fecha_recepcion'];
	// 	$reporte       = $data['reporte'];
	// 	$elemento      = $data['elemento'];
	// 	$ubicacion     = $data['ubicacion'];
	// 	$fc            = $data['fc'];
	// 	$edad          = $data['edad'];
	// 	$revenimientop          = $data['revenimientop'];
	// 	$revenimientor          = $data['revenimientor'];
	// 	$tma          = $data['agregado'];
	// 	$concretera          = $data['concretera'];
	// 	$temperatura          = $data['temperatura'];
	// 	$remision          = $data['remision'];
	// 	$volumen          = $data['volumen'];
	// 	$hora_muestreo          = $data['hora_muestreo'];
	// 	$hora_desmoldeo          = $data['hora_desmoldeo'];

	// 	$muestreo          = $data['muestreo'];
	// 	$recibio          = $data['recibio'];
	// 	$observacion          = $data['observacion'];
	// 	// $revisado_autorizado          = $data['revisado_autorizado'];
	// }
	// ---------- ACTUALIZAR DATOS DE MUESTREO ----------

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		$fecha = $_POST['fecha'];
		$fecha_recepcion = $_POST['fecha_recepcion'];
		$elemento = $_POST['elemento'];
		$ubicacion = $_POST['ubicacion'];
		$fc = $_POST['fc'];
		$edad = $_POST['edad'];
		$revenimientop = $_POST['revenimientop'];
		$revenimientor = $_POST['revenimientor'];
		$tma = $_POST['agregado'];
		$concretera = $_POST['concretera'];
		$temperatura = $_POST['temperatura'];
		$remision = $_POST['remision'];
		$volumen = $_POST['volumen'];
		$hora_muestreo = $_POST['hora_muestreo'];
		$hora_desmoldeo = $_POST['hora_desmoldeo'];
		$muestreo = $_POST['muestreo'];
		$recibio = $_POST['recibio'];
		$observacion = $_POST['observacion'];

		$sqlUpdate = "UPDATE reporte_concreto SET
		fecha = '$fecha',
		fecha_recepcion = '$fecha_recepcion',
		elemento = '$elemento',
		ubicacion = '$ubicacion',
		fc = '$fc',
		edad = '$edad',
		revenimientop = '$revenimientop',
		revenimientor = '$revenimientor',
		agregado = '$tma',
		concretera = '$concretera',
		temperatura = '$temperatura',
		remision = '$remision',
		volumen = '$volumen',
		hora_muestreo = '$hora_muestreo',
		hora_desmoldeo = '$hora_desmoldeo',
		muestreo = '$muestreo',
		recibio = '$recibio',
		observacion = '$observacion'
	WHERE expediente = '$exp' AND reporte = '$rep'";

		if ($conexion->query($sqlUpdate)) {
			// 	echo "<script>alert('Datos de muestreo actualizados correctamente'); 
			// window.location.href='captura_cilindros.php?expediente=$exp&reporte=$rep';</script>";
		} else {
			echo "Error: " . $conexion->error;
		}
	}
}
// ---------- ACTUALIZAR ENSAYE DE ESPECÍMENES ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item'])) {

	$id_reporte_concreto = $_POST['id_reporte_concreto'];

	foreach ($_POST['item'] as $idItem) {

		$fecha_ensaye      = $_POST['fecha_ensaye'][$idItem];
		$edad_item         = $_POST['edad_item'][$idItem];
		$tolerancia        = $_POST['tolerancia'][$idItem];
		$diametro1         = $_POST['diametro1'][$idItem];
		$diametro2         = $_POST['diametro2'][$idItem];
		$altura1           = $_POST['altura1'][$idItem];
		$altura2           = $_POST['altura2'][$idItem];
		$condicion         = $_POST['condicion_especimen'][$idItem];
		$flexometro        = $_POST['flexometro'][$idItem];
		$escuadra          = $_POST['escuadra'][$idItem];
		$compas            = $_POST['compas'][$idItem];
		$prensa            = $_POST['prensa'][$idItem];
		$hora_ensaye       = $_POST['hora_ensaye'][$idItem];
		$carga             = $_POST['carga'][$idItem];
		$tiempo_ensaye     = $_POST['tiempo_ensaye'][$idItem];
		// $velocidad         = $_POST['velocidad'][$idItem];
		// $cumple_velocidad  = $_POST['cumple_velocidad'][$idItem];
		$falla             = $_POST['falla'][$idItem];
		$observaciones     = $_POST['observaciones'][$idItem];
		$persona_ensayo    = $_POST['persona_ensayo'][$idItem];
		$persona_capturo   = $_POST['persona_capturo'][$idItem];

		$sqlUpdateItem = "UPDATE item SET
    fecha_ensaye = '$fecha_ensaye',
    edad_item = '$edad_item',
    tolerancia = '$tolerancia',
    diametro1 = '$diametro1',
    diametro2 = '$diametro2',
    altura1 = '$altura1',
    altura2 = '$altura2',
    condicion_especimen = '$condicion',
    flexometro = '$flexometro',
    escuadra = '$escuadra',
    compas = '$compas',
    prensa = '$prensa',
    hora_ensaye = '$hora_ensaye',
    carga = '$carga',
    tiempo_ensaye = '$tiempo_ensaye',
    falla = '$falla',
    observaciones = '$observaciones',
    persona_ensayo = '$persona_ensayo',
    persona_capturo = '$persona_capturo'
WHERE item = '$idItem' AND id_reporte_concreto = '$id_reporte_concreto'
";


		$conexion->query($sqlUpdateItem);
	}
	echo "<script>
        alert('Ensaye actualizado correctamente');
        window.close();
    </script>";
	exit;
	// echo "<script>alert('Ensaye actualizado correctamente');location.reload();</script>";
}



if (isset($_GET['expediente']) && isset($_GET['reporte'])) {

	$exp = $_GET['expediente'];
	$rep = $_GET['reporte'];
	$id_reporte_concreto = $_GET['id_reporte_concreto'];


	// Consulta del registro
	$sql = "SELECT * FROM obras 
            WHERE expediente = '$exp' 
            LIMIT 1";
	$res = $conexion->query($sql);

	if ($res->num_rows > 0) {
		$data = $res->fetch_assoc();

		// Llenar variables
		// $cliente       = $data['cliente'];
		$id_cliente    = $data['cliente'];
		$obra          = $data['obra'];
		// $expediente    = $data['expediente'];
		$localizacion  = $data['localizacion'];
	}
}
if (isset($_GET['expediente']) && isset($_GET['reporte'])) {

	$exp = $_GET['expediente'];
	$rep = $_GET['reporte'];

	// Consulta del registro
	$sql = "SELECT * FROM clientes 
            WHERE idcliente = '$id_cliente' 
            LIMIT 1";
	$res = $conexion->query($sql);

	if ($res->num_rows > 0) {
		$data = $res->fetch_assoc();

		// Llenar variables
		$cliente       = $data['cliente'];
		// $id_cliente    = $data['id_cliente'];
		// $obra          = $data['obra'];
		// $expediente    = $data['expediente'];
		// $localizacion  = $data['localizacion'];
	}
}
?>



<!-- BEGIN #content -->
<form method="POST">

	<input type="hidden" name="id_reporte_concreto" value="<?= $id_reporte_concreto ?>">

	<div id="content" class="app-content">
		<ul class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">LAYOUT</a></li>
			<li class="breadcrumb-item active">STARTER PAGE</li>
		</ul>

		<h1 class="page-header">
			Captura de cilindros <small></small>
		</h1>


		<div class="card">
			<div class="card-header with-btn">
				DATOS GENERALES
				<div class="card-header-btn">
					<a href="#" data-toggle="card-collapse" class="btn"><iconify-icon icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
					<a href="#" data-toggle="card-expand" class="btn"><iconify-icon icon="material-symbols-light:fullscreen"></iconify-icon></a>
					<a href="#" data-toggle="card-remove" class="btn"><iconify-icon icon="material-symbols-light:close-rounded"></iconify-icon></a>
				</div>
			</div>
			<div class="card-body pb-2">
				<div class="row">
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Cliente <span class="text-danger"></label>
							<input type="text" id="cliente" class="form-control" value="<?= $cliente ?>" readonly
								placeholder="Nombre del cliente">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Id cliente <span class="text-danger"></label>
							<input type="number" id="id_cliente" class="form-control" value="<?= $id_cliente ?>" readonly
								placeholder="Id cliente">
							<!-- <div class="input-group">
								<label class="input-group-text" for="datepicker-component"><i class="fa fa-calendar"></i></label>
							</div> -->
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Obra <span class="text-danger"></label>
							<input type="text" id="obra" class="form-control" value="<?= $obra ?>" readonly
								placeholder="Nombre de la obra">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Expediente <span class="text-danger"></label>
							<input type="number" id="expediente" class="form-control" value="<?= $expediente ?>" readonly
								placeholder="Numero de expediente">
						</div>
					</div>

					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Localización <span class="text-danger"></label>
							<input type="text" id="localizacion" class="form-control" value="<?= $localizacion ?>" readonly
								placeholder="Localización">
						</div>
					</div>

					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Reporte <span class="text-danger"></label>
							<input type="number" id="reporte" class="form-control" value="<?= $reporte ?>" readonly
								placeholder="Numero de reporte">
						</div>
					</div>
					<div class="col-xl-6">
						<label class="form-label">Seleccionar expediente *</label>
						<select class="form-select" id="seleccionar_exp" name="seleccionar_exp">
							<option value="">Seleccionar expediente</option>
							<?php foreach ($expLista as $p): ?>
								<option value="<?= $p['expediente'] ?>">
									<?= $p['expediente'] ?> – <?= $p['obra'] ?>
								</option>
							<?php endforeach; ?>
						</select>

					</div>





				</div>
			</div>
		</div>



		<div class="card">
			<div class="card-header with-btn">
				DATOS DE MUESTREO DEL CONCRETO FRESCO
				<div class="card-header-btn">
					<a href="#" data-toggle="card-collapse" class="btn"><iconify-icon icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
					<a href="#" data-toggle="card-expand" class="btn"><iconify-icon icon="material-symbols-light:fullscreen"></iconify-icon></a>
					<a href="#" data-toggle="card-remove" class="btn"><iconify-icon icon="material-symbols-light:close-rounded"></iconify-icon></a>
				</div>
			</div>
			<div class="card-body pb-2">

				<div class="row">

					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Fecha de muestreo *</label>
							<input type="date" class="form-control" name="fecha" value="<?= $fecha ?>">
						</div>
					</div>

					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Fecha de recepción *</label>
							<input type="date" class="form-control" name="fecha_recepcion" value="<?= $fecha_recepcion ?>">
						</div>
					</div>

					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Elemento *</label>
							<input type="text" class="form-control" name="elemento" value="<?= $elemento ?>">
						</div>
					</div>

					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Ubicación *</label>
							<input type="text" class="form-control" name="ubicacion" value="<?= $ubicacion ?>">
						</div>
					</div>

					<!-- <div class="row"> -->

					<div class="col-xl-3">
						<label class="form-label">f'c *</label>
						<input type="number" class="form-control" name="fc" value="<?= $fc ?>">
					</div>

					<div class="col-xl-3">
						<label class="form-label">Edad *</label>
						<input type="number" class="form-control" name="edad" value="<?= $edad ?>">
					</div>

					<div class="col-xl-3">
						<label class="form-label">Rev. Proy. *</label>
						<input type="number" class="form-control" name="revenimientop" value="<?= $revenimientop ?>">
					</div>

					<div class="col-xl-3">
						<label class="form-label">Rev. Real *</label>
						<input type="number" class="form-control" name="revenimientor" value="<?= $revenimientor ?>">
					</div>

					<!-- </div> -->



					<div class="col-xl-2">
						<label class="form-label">T.M.A. [mm] *</label>
						<input type="text" class="form-control" name="agregado" value="<?= $tma ?>">
					</div>
					<div class="col-xl-4">
						<label class="form-label">Concretera *</label>
						<input type="text" class="form-control" name="concretera" value="<?= $concretera ?>">
					</div>

					<div class="col-xl-2">
						<label class="form-label">Temperatura *</label>
						<input type="number" class="form-control" name="temperatura" value="<?= $temperatura ?>">
					</div>

					<div class="col-xl-2">
						<label class="form-label">Remisión *</label>
						<input type="text" class="form-control" name="remision" value="<?= $remision ?>">
					</div>

					<div class="col-xl-2">
						<label class="form-label">Volumen *</label>
						<input type="text" class="form-control" name="volumen" value="<?= $volumen ?>">
					</div>

					<div class="col-xl-6">
						<label class="form-label">Hora de muestreo *</label>
						<input type="time" class="form-control" name="hora_muestreo" value="<?= $hora_muestreo ?>">
					</div>
					<div class="col-xl-6">
						<label class="form-label">Hora de desmoldeo *</label>
						<input type="time" class="form-control" name="hora_desmoldeo" value="<?= $hora_desmoldeo ?>">
					</div>

					<div class="col-xl-6">
						<label class="form-label">Muestreó especímenes *</label>
						<select class="form-select" name="muestreo" data-live-search="true">
							<option value="<?= $muestreo ?>" selected><?= $muestreo ?></option>
							<?php foreach ($personalLista as $p): ?>
								<option value="<?= $p['Nombre'] ?>"><?= $p['Nombre'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="col-xl-6">
						<label class="form-label">Recibió especímenes *</label>
						<select class="form-select" name="recibio" data-live-search="true">
							<option value="<?= $recibio ?>" selected><?= $recibio ?></option>
							<?php foreach ($personalLista as $p): ?>
								<option value="<?= $p['Nombre'] ?>"><?= $p['Nombre'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="col-xl-12">
						<label class="form-label">Observaciones *</label>
						<input type="text" class="form-control" name="observacion" value="<?= $observacion ?>">
					</div>

				</div>



			</div>
		</div>


		<div class="card">
			<div class="card-header with-btn">
				ENSAYE A LA COMPRESIÓN DE ESPECÍMENES CILÍNDRICOS DE CONCRETO
				<div class="card-header-btn">
					<a href="#" data-toggle="card-collapse" class="btn"><iconify-icon icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
					<a href="#" data-toggle="card-expand" class="btn"><iconify-icon icon="material-symbols-light:fullscreen"></iconify-icon></a>
					<a href="#" data-toggle="card-remove" class="btn"><iconify-icon icon="material-symbols-light:close-rounded"></iconify-icon></a>
				</div>
			</div>

			<div class="card-body">

				<div class="card">
					<div class="card-header with-btn">
						ENSAYE A LA COMPRESIÓN DE ESPECÍMENES CILÍNDRICOS DE CONCRETO
						<div class="card-header-btn">
							<a href="#" data-toggle="card-collapse" class="btn"></a>
							<a href="#" data-toggle="card-expand" class="btn"></a>
						</div>
					</div>

					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover wide-table">
								<thead class="table-dark">
									<tr>
										<th>Item</th>
										<th>Fecha ensaye</th>
										<th>Edad [d]</th>
										<th>Tolerancia [h]</th>
										<th>Diametro 1 [cm]</th>
										<th>Diametro 2 [cm]</th>
										<th>Altura 1 [cm]</th>
										<th>Altura 2 [cm]</th>
										<th>Condición</th>
										<th>Flexómetro</th>
										<th>Escuadra</th>
										<th>Compás</th>
										<th>Prensa</th>
										<th>Hora ensaye</th>
										<th>Carga</th>
										<th>f´c %</th>
										<th>Tiempo [s]</th>
										<th>Falla</th>
										<th>Observaciones</th>
										<th>Ensayó</th>
										<th>Capturó</th>
									</tr>
								</thead>
								<tbody>
									<!-- JS INSERTA AQUÍ LOS 4 CILINDROS -->
								</tbody>
							</table>
						</div>
					</div>
				</div>

			</div>
		</div>




		<button type="submit" class="btn btn-outline-theme btn-sm w-180px">
			Guardar cambios
		</button>

</form> <!-- AQUÍ SE CIERRA EL FORMULARIO -->

</div>
</div>
<!-- END #content -->



<?php include("pie.php"); ?>

<script>
	function actualizarFechasEnsaye() {

		const fechaMuestreo = document.querySelector("input[name='fecha']").value;
		if (!fechaMuestreo) return;

		// Crear fecha en zona local sin conversión UTC
		let partes = fechaMuestreo.split("-");
		let fechaBase = new Date(partes[0], partes[1] - 1, partes[2]);

		document.querySelectorAll("input[name^='edad_item']").forEach(input => {

			let idItem = input.name.match(/\[(.*?)\]/)[1];
			let edad = parseInt(input.value) || 0;

			let nuevaFecha = new Date(fechaBase);
			nuevaFecha.setDate(nuevaFecha.getDate() + edad);

			let yyyy = nuevaFecha.getFullYear();
			let mm = String(nuevaFecha.getMonth() + 1).padStart(2, '0');
			let dd = String(nuevaFecha.getDate()).padStart(2, '0');

			let fechaFormateada = `${yyyy}-${mm}-${dd}`;

			let campoFecha = document.querySelector(`input[name='fecha_ensaye[${idItem}]']`);
			if (campoFecha) campoFecha.value = fechaFormateada;
		});
	}


	// === ACTUALIZAR CILINDROS SEGÚN LA EDAD DE MUESTREO ===
	function actualizarCilindros() {
		let edad = document.querySelector("input[name='edad']").value;

		let edades = document.querySelectorAll("input[name^='edad_item']");
		let tolerancias = document.querySelectorAll("input[name^='tolerancia']");

		if (edades.length < 4) return;

		switch (edad) {
			case "1":
				edades[0].value = edades[1].value = edades[2].value = edades[3].value = 1;
				tolerancias[0].value = tolerancias[1].value = tolerancias[2].value = tolerancias[3].value = 0.5;
				break;

			case "3":
				edades[0].value = edades[1].value = edades[2].value = edades[3].value = 3;
				tolerancias[0].value = tolerancias[1].value = tolerancias[2].value = tolerancias[3].value = 2;
				break;

			case "5":
				edades[0].value = 1;
				edades[1].value = 3;
				edades[2].value = edades[3].value = 5;
				tolerancias[0].value = 0.5;
				tolerancias[1].value = 2;
				tolerancias[2].value = tolerancias[3].value = 2;
				break;

			case "7":
				edades[0].value = 3;
				edades[1].value = 5;
				edades[2].value = edades[3].value = 7;
				tolerancias[0].value = 2;
				tolerancias[1].value = 2;
				tolerancias[2].value = tolerancias[3].value = 6;
				break;

			case "14":
				edades[0].value = 5;
				edades[1].value = 7;
				edades[2].value = edades[3].value = 14;
				tolerancias[0].value = 2;
				tolerancias[1].value = 6;
				tolerancias[2].value = tolerancias[3].value = 12;
				break;

			case "28":
				edades[0].value = 7;
				edades[1].value = 14;
				edades[2].value = edades[3].value = 28;
				tolerancias[0].value = 6;
				tolerancias[1].value = 12;
				tolerancias[2].value = tolerancias[3].value = 20;
				break;
		}

		// Recalcular f'c
		document.querySelectorAll(".fc_res").forEach(span => {
			let itemID = span.id.replace("fc_res_", "");
			calcularFC(itemID);
		});

		// 👈 Nueva línea: actualizar fechas automáticamente
		actualizarFechasEnsaye();
	}


	// Ejecutar cuando cambie la edad del muestreo
	document.querySelector("input[name='edad']").addEventListener("input", actualizarCilindros);


	// Cálculo en tiempo real de porcentaje f'c respecto al f'c de diseño
	function calcularFC(id) {

		// Obtener f'c establecido en datos de muestreo
		let fc_establecido = parseFloat(document.querySelector("input[name='fc']").value) || 0;

		let d1 = parseFloat(document.querySelector("input[name='diametro1[" + id + "]']").value) || 0;
		let d2 = parseFloat(document.querySelector("input[name='diametro2[" + id + "]']").value) || 0;
		let carga = parseFloat(document.querySelector("input[name='carga[" + id + "]']").value) || 0;

		if (d1 <= 0 || d2 <= 0 || carga <= 0 || fc_establecido <= 0) {
			document.getElementById("fc_res_" + id).innerText = "—";
			return;
		}

		// diámetro promedio
		let d_prom = (d1 + d2) / 2;

		// cm → m
		let d_m = d_prom;

		// Área
		let area = Math.PI * Math.pow(d_m / 2, 2);

		// resistencia real en MPa
		let fc_real = carga / area;

		// MPa → kg/cm²
		fc_real = fc_real;

		// PORCENTAJE respecto al f'c establecido
		let porcentaje = (fc_real / fc_establecido) * 100;

		document.getElementById("fc_res_" + id).innerText = porcentaje.toFixed(1) + "%";
	}

	// Detectar cambios en Ø1, Ø2, carga o f'c establecido
	document.addEventListener("input", function(e) {
		if (e.target.classList.contains("diametro1") ||
			e.target.classList.contains("diametro2") ||
			e.target.classList.contains("carga") ||
			e.target.name === "edad" ||
			e.target.name === "fc") {

			let id = e.target.dataset.id;
			if (id) {
				calcularFC(id);
			}

			// Si cambia el f’c de diseño, recalcular todos
			if (e.target.name === "fc") {
				document.querySelectorAll(".fc_res").forEach(span => {
					let itemID = span.id.replace("fc_res_", "");
					calcularFC(itemID);
				});
			}

		}
	});

	// --- Calcular todos los f'c al cargar la página ---
	window.addEventListener("DOMContentLoaded", function() {
		document.querySelectorAll(".fc_res").forEach(span => {
			let itemID = span.id.replace("fc_res_", "");
			calcularFC(itemID);
		});
	});

	// Ejecutar cuando cambie la edad del muestreo
	document.querySelector("input[name='edad']").addEventListener("input", actualizarCilindros);
	// Cuando cambia la fecha de muestreo, recalcular fechas de ensaye
	document.querySelector("input[name='fecha']").addEventListener("change", actualizarFechasEnsaye);

	// Cuando cambia cada edad individual
	document.addEventListener("input", function(e) {
		if (e.target.name && e.target.name.startsWith("edad_item[")) {
			actualizarFechasEnsaye(); // ✔ corregido
		}
	});

	// Actualizar fechas al cargar la página
	window.addEventListener("DOMContentLoaded", actualizarFechasEnsaye); // ✔ corregido

	document.getElementById("seleccionar_exp").addEventListener("change", function() {

		const expediente = this.value;
		if (!expediente) return;

		// 1️⃣ Cargar datos generales del expediente
		fetch(`ajax_get_expediente.php?expediente=${expediente}`)
			.then(r => r.json())
			.then(data => {

				if (data.error) {
					alert(data.error);
					return;
				}

				document.getElementById("expediente").value = data.expediente;
				document.getElementById("obra").value = data.obra;
				document.getElementById("localizacion").value = data.localizacion;
				document.getElementById("cliente").value = data.cliente;
				document.getElementById("id_cliente").value = data.idcliente;
				document.getElementById("reporte").value = data.siguiente_reporte;

				// 2️⃣ Consultar último item REAL
				return fetch(`ajax_get_ultimo_item.php?expediente=${expediente}`);
			})
			.then(r => r.json())
			.then(data => {

				if (data.error) {
					alert(data.error);
					return;
				}

				reconstruirCilindros(data.ultimo_item);
			})
			.catch(err => console.error(err));
	});

	function reconstruirCilindros(ultimoItem) {

		const tbody = document.querySelector("table tbody");
		if (!tbody) return;

		tbody.innerHTML = "";

		for (let i = 1; i <= 4; i++) {

			let id = ultimoItem + i;

			tbody.insertAdjacentHTML("beforeend", `
            <tr>
                <td>
                    <input type="hidden" name="item[]" value="${id}">
                    ${id}
                </td>

                <td><input type="date" class="form-control" name="fecha_ensaye[${id}]"></td>
                <td><input type="text" class="form-control" name="edad_item[${id}]"></td>
                <td><input type="text" class="form-control" name="tolerancia[${id}]"></td>

                <td><input type="text" class="form-control diametro1" data-id="${id}" name="diametro1[${id}]"></td>
                <td><input type="text" class="form-control diametro2" data-id="${id}" name="diametro2[${id}]"></td>

                <td><input type="text" class="form-control" name="altura1[${id}]"></td>
                <td><input type="text" class="form-control" name="altura2[${id}]"></td>

                <td>
                    <select class="form-select" name="condicion_especimen[${id}]">
                        <option></option>
                        <option>Bien</option>
                        <option>Mal</option>
                    </select>
                </td>

                <td><input type="text" class="form-control" name="flexometro[${id}]" value="9"></td>
                <td><input type="text" class="form-control" name="escuadra[${id}]" value="4"></td>
                <td><input type="text" class="form-control" name="compas[${id}]" value="1"></td>

                <td><input type="text" class="form-control" name="prensa[${id}]"></td>
                <td><input type="time" class="form-control" name="hora_ensaye[${id}]"></td>

                <td><input type="text" class="form-control carga" data-id="${id}" name="carga[${id}]"></td>

                <td><span class="fc_res" id="fc_res_${id}">—</span></td>

                <td><input type="text" class="form-control" name="tiempo_ensaye[${id}]"></td>

                <td>
                    <select class="form-select" name="falla[${id}]">
                        <option></option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                    </select>
                </td>

                <td><input type="text" class="form-control" name="observaciones[${id}]"></td>
                <td><select class="form-select" name="persona_ensayo[${id}]"></select></td>
                <td><select class="form-select" name="persona_capturo[${id}]"></select></td>
            </tr>
        `);
		}

		actualizarCilindros();
	}
</script>