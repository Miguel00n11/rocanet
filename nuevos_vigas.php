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
require_once __DIR__ . '/auth.php';

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
function firebaseDelete($ruta)
{
	global $firebaseURL, $auth;

	$segmentos = explode('/', $ruta);
	$segmentos = array_map('rawurlencode', $segmentos);
	$rutaSegura = implode('/', $segmentos);

	$url = "$firebaseURL/$rutaSegura.json?auth=$auth";

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_exec($ch);
	curl_close($ch);
}



/* PARAMETROS (equivalente al constructor) */
// $usuario = $_GET['usuario'];
$usuario = $_GET['usuario'] ?? ''; // NO urldecode
$llave   = $_GET['llave'] ?? '';
$expediente   = $_GET['expediente'] ?? '';

$ruta = "Vigas/Reportes/$usuario/$llave";
$reporte = firebaseGet($ruta);

if ($reporte === null) {
	die("NO SE ENCONTRÓ EL REPORTE<br>Ruta lógica: $ruta");
}

$cliente = $reporte['cliente'] ?? 'SIN CLIENTE';





/* VARIABLES DEL REPORTE */
$cliente = "";
$id_cliente = "";
$obra = $reporte['obra'] ?? 'SIN OBRA';
// $expediente = $reporte['expediente'] ?? 'SIN OBRA';
$localizacion = $reporte['localizacion'] ?? 'SIN LOCALIZACION';
// $reporte = "";
$fecha = date('Y-m-d');
$elemento = "";
$ubicacion = "";
$fc = "";
$edad = "";
$edad_muestreo = $reporte['edad'] ?? 'SIN edad';
$revenimiento_dis = $reporte['revenimientoDis'] ?? 'SIN revenimientop';
$revenimiento_r1 = $reporte['revenimientoR1'] ?? 'SIN revenimiento1';
$revenimiento_r2 = $reporte['revenimientoR2'] ?? 'SIN revenimiento2';
$tma = $reporte['tma'] ?? 'SIN tma';
$aditivo = $reporte['aditivo'] ?? 'SIN aditivo';
$olla = $reporte['olla'] ?? 'SIN olla';
$carretilla = $reporte['carretilla'] ?? 'SIN carretilla';
$cono = $reporte['cono'] ?? 'SIN cono';
$cucharon = $reporte['cucharon'] ?? 'SIN cucharon';
$enrasador = $reporte['enrasador'] ?? 'SIN enrasador';
$flexometro = $reporte['flexometro'] ?? 'SIN flexometro';
$termometro = $reporte['termometro'] ?? 'SIN termometro';
$varilla = $reporte['varilla'] ?? 'SIN varilla';
$mazo = $reporte['mazo'] ?? 'SIN mazo';
$placa = $reporte['placa'] ?? 'SIN placa';
$proporciones = $reporte['proporciones'] ?? 'SIN proporciones';
$estado_molde1 = $reporte['estadoMolde1'] ?? 'SIN estado molde1';
$estado_molde2 = $reporte['estadoMolde2'] ?? 'SIN estado molde2';
$estado_molde3 = $reporte['estadoMolde3'] ?? 'SIN estado molde3';
$estado_molde4 = "";
$item4 = 0;
$id_especimen1 = $reporte['idEspecimen1'] ?? 'SIN id especimen1';
$id_especimen2 = $reporte['idEspecimen2'] ?? 'SIN id especimen2';
$id_especimen3 = $reporte['idEspecimen3'] ?? 'SIN id especimen3';
$molde1 = $reporte['molde1'] ?? 'SIN molde 1';
$molde2 = $reporte['molde2'] ?? 'SIN molde 2';
$molde3 = $reporte['molde3'] ?? 'SIN molde 3';
$molde4 = 0;
$concretera = $reporte['concretera'] ?? 'SIN concretera';
$temperatura = $reporte['temperatura'] ?? 'SIN temperatura';
$remision = "";
$tipo_muestreo = $reporte['tipoMuestreo'] ?? 'SIN tipo_muestreo';
$tipo_resistencia = $reporte['tipoResistencia'] ?? 'SIN tipo_resistencia';
$validado = 0 ?? 'SIN validado';
$volumen = "";
$volumen_total = $reporte['volumenTotal'] ?? 'SIN volumen total';
$numero_reporte = $reporte['numeroReporte'] ?? 'SIN numero reporte';
$muestra = $reporte['muestra'] ?? 'SIN muestra';
$hora_muestreo = $reporte['horaMuestreo'] ?? 'SIN hora muestreo';
$hora_llegada = $reporte['horaLLegada'] ?? 'SIN hora llegada';
$hora_salida = $reporte['horaSalida'] ?? 'SIN hora salida';
$muestreo = "";
$recibio = "Jesús Miguel Rodríguez Ortega"; // Valor por defecto
$revisado_autorizado = "Cristina Andrea Rodríguez Ortega"; // Valor por defecto
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

// echo "<script>
// 	alert('CLIENTE: ' + " . json_encode($fecha) . ");
// </script>";


$id_reporte_concreto = $_POST['id_reporte_concreto']
	?? $_GET['id_reporte_concreto']
	?? null;



// ---------- agregar ENSAYE DE ESPECÍMENES ----------

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {

	$expediente = $_POST['expediente'];
	$reporte    = $_POST['reporte'];

	

	if (empty($_POST['item'])) {
		die("No hay vigas para guardar");
	}

	$conexion->begin_transaction();

	echo "<script>
	alert('CLIENTE: ' + " . $elemento . ");
</script>";

	$item   = [null, null, null];
	$edad   = [null, null, null];
	$ensaye = [null, null, null];
	$carga  = [null, null, null];
	$falla  = [null, null, null];

	$i = 0;
	foreach ($_POST['item'] as $id) {
		if ($i >= 3) break;

		$item[$i]   = $id;
		$edad[$i]   = $_POST['edad_item'][$id] ?? null;

		$fechaEnsayeRaw = $_POST['fecha_ensaye'][$id] ?? null;

		if ($fechaEnsayeRaw) {
			$dt = DateTime::createFromFormat('Y-m-d', $fechaEnsayeRaw);
			$ensaye[$i] = $dt ? $dt->format('d/m/Y') : null;
		} else {
			$ensaye[$i] = null;
		}

		$carga[$i]  = $_POST['carga'][$id] ?? null;
		$falla[$i]  = $_POST['falla'][$id] ?? null;

		$i++;
	}

	try {

		// =========================
		// 1️⃣ INSERT REPORTE (MASTER)
		// =========================
		$sqlInsertReporte = "
		INSERT INTO registros_vigas_campo_actualizado (
			id_reporte_concreto, obra, fecha, aditivo, carretilla, cliente, concretera,
			cono, cucharon, edad, elemento_colado, enrasador,
			estado_molde1, estado_molde2, estado_molde3, estado_molde4,
			exp, fc, flexometro, hora_llegada, hora_muestreo, hora_salida,
			id_especimen1, id_especimen2, id_especimen3, id_especimen4,
			localizacion, mazo,
			molde1, molde2, molde3, molde4,
			muestra, numero_reporte, observaciones, personal, placa,
			proporciones, remision,
			revenimiento_dis, revenimiento_r1, revenimiento_r2,
			revisado, temperatura, termometro, tipo_muestreo,
			tipo_resistencia, tma, ubicacion, validado, varilla,
			volumen_total, volumen_muestra, olla,llave
		) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
		)";



		$stmt = $conexion->prepare($sqlInsertReporte);

		$stmt->bind_param(
			"issssssssssssssssssssssssssssssssssssssssssssssssssssss",
			$_POST['id_reporte_concreto'],
			$_POST['obra'],
			$_POST['fecha'],
			$aditivo,
			$carretilla,
			$_POST['cliente'],
			$_POST['concretera'],
			$cono,
			$cucharon,
			$_POST['edad_muestreo'], //10
			$_POST['elemento_colado'],
			$enrasador,
			$estado_molde1,
			$estado_molde2,
			$estado_molde3,
			$estado_molde4,
			$expediente,
			$_POST['fc'],
			$flexometro,
			$hora_llegada, //20
			$_POST['hora_muestreo'],
			$hora_salida,
			$item[0],
			$item[1],
			$item[2],
			$item4,
			$_POST['localizacion'],
			$mazo,
			$molde1,
			$molde2, //30
			$molde3,
			$molde4,
			$muestra,
			$_POST['reporte'],
			$_POST['observacion'],
			$muestreo,
			$placa,
			$proporciones,
			$_POST['remision'],
			$_POST['revenimiento_dis'], //40
			$_POST['revenimiento_r1'],
			$revenimiento_r2,
			$_POST['revisado_autorizado'],
			$_POST['temperatura'],
			$termometro,
			$tipo_muestreo,
			$tipo_resistencia,
			$_POST['tma'],
			$_POST['ubicacion'],
			$validado, //50
			$varilla,
			$volumen_total,
			$volumen,
			$olla,
			$llave
		);



		if (!$stmt->execute()) {
			die("ERROR SQL MASTER: " . $stmt->error);
		}
		// $stmt->execute();
		$id_reporte_concreto = $conexion->insert_id;

		// =========================
		// 2️⃣ INSERT CILINDROS (DETAIL)
		// =========================
		$sql = "
		INSERT INTO vigas (
			expediente, idcliente, reporte, elemento, ubicacion,
			fc, revenimientop, revenimientor, concretera, remision,
			fecha, edad, volumen, temperatura, tma, agregado,

			item1, item2, item3,
			edad1, edad2, edad3,
			ensaye1, ensaye2, ensaye3,
			carga1, carga2, carga3,
			falla1, falla2, falla3,

			personal_muestreo, personal_recibio,
			observaciones, fecha_recepcion, hora_desmoldeo
		) VALUES (
			?,?,?,?,?,
			?,?,?,?,?,
			?,?,?,?,?,
			?,?,?,
			?,?,?,
			?,?,?,
			?,?,?,
			?,?,?,
			?,?,?,?,?,?
		)";
		$stmt = $conexion->prepare($sql);

		$stmt->bind_param(
			"iisssssssssissssssssssssssssssssssss", //35
			$expediente,
			$id_cliente,
			$reporte,
			$_POST['elemento_colado'],
			$_POST['ubicacion'],
			$_POST['fc'],
			$_POST['revenimiento_dis'],
			$_POST['revenimiento_r1'],
			$_POST['concretera'],
			$_POST['remision'],
			$fecha,
			$_POST['edad_muestreo'],
			$_POST['volumen'],
			$_POST['temperatura'],
			$_POST['tma'],
			$_POST['tma'],

			$item[0],
			$item[1],
			$item[2],
			$edad[0],
			$edad[1],
			$edad[2],
			$ensaye[0],
			$ensaye[1],
			$ensaye[2],
			$carga[0],
			$carga[1],
			$carga[2],
			$falla[0],
			$falla[1],
			$falla[2],

			$_POST['muestreo'],
			$_POST['recibio'],
			$_POST['observacion'],
			$_POST['fecha_recepcion'],
			$_POST['hora_desmoldeo']
		);

		if (!$stmt->execute()) {
			die("ERROR SQL MASTER: " . $stmt->error);
		}
		// $stmt->execute();
		$id_reporte_concreto = $conexion->insert_id;
		// =========================
		// 3️⃣ COMMIT
		// =========================
		$conexion->commit();

		// =========================
		// 4️⃣ BORRAR REPORTE EN FIREBASE
		// =========================
		// firebaseDelete($ruta);

		echo "<script>
            alert('Reporte y vigas guardados correctamente');
            window.close();
        </script>";
		exit;
	} catch (Exception $e) {
		$conexion->rollback();
		die("Error al guardar el reporte: " . $e->getMessage());
	}
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
			Nuevo concreto de vigas <small></small>
		</h1>


		<div class="card">
			<div class="card-header with-btn">
				DATOS GENERALES
				<div class="card-header-btn">
					<!-- <a href="#" data-toggle="card-collapse" class="btn"><iconify-icon icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
					<a href="#" data-toggle="card-expand" class="btn"><iconify-icon icon="material-symbols-light:fullscreen"></iconify-icon></a>
					<a href="#" data-toggle="card-remove" class="btn"><iconify-icon icon="material-symbols-light:close-rounded"></iconify-icon></a> -->
				</div>
			</div>
			<div class="card-body pb-2">
				<div class="row">
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Cliente <span class="text-danger"></label>
							<input type="text" id="cliente" class="form-control" name="cliente" value="<?= $cliente ?>" readonly
								placeholder="Nombre del cliente">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Id cliente <span class="text-danger"></label>
							<input type="number" id="id_cliente" class="form-control" name="id_cliente" value="<?= $id_cliente ?>" readonly
								placeholder="Id cliente">
							<!-- <div class="input-group">
								<label class="input-group-text" for="datepicker-component"><i class="fa fa-calendar"></i></label>
							</div> -->
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Obra <span class="text-danger"></label>
							<input type="text" id="obra" class="form-control" name="obra" value="<?= $obra ?>" readonly
								placeholder="Nombre de la obra">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Expediente <span class="text-danger"></label>
							<input type="number" id="expediente" class="form-control" name="expediente" value="<?= $expediente ?>" readonly
								placeholder="Numero de expediente">
						</div>
					</div>

					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Localización <span class="text-danger"></label>
							<input type="text" id="localizacion" class="form-control" name="localizacion" value="<?= $localizacion ?>" readonly
								placeholder="Localización">
						</div>
					</div>

					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Reporte <span class="text-danger"></label>
							<input type="number" id="reporte" class="form-control" name="reporte" value="<?= $reporte ?>" readonly
								placeholder="Numero de reporte">
						</div>
					</div>
					<div class="col-xl-6">
						<label class="form-label">Seleccionar expediente *</label>
						<select class="form-select" id="seleccionar_exp" name="seleccionar_exp" disabled>
							<option value="">Seleccionar expediente</option>
							<?php foreach ($expLista as $p): ?>
								<option value="<?= $p['expediente'] ?>"
									<?= ($p['expediente'] == $expediente) ? 'selected' : '' ?>>
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
					<!-- <a href="#" data-toggle="card-collapse" class="btn"><iconify-icon icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
					<a href="#" data-toggle="card-expand" class="btn"><iconify-icon icon="material-symbols-light:fullscreen"></iconify-icon></a>
					<a href="#" data-toggle="card-remove" class="btn"><iconify-icon icon="material-symbols-light:close-rounded"></iconify-icon></a> -->
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
							<input type="text" class="form-control" name="elemento_colado" value="<?= $elemento ?>">
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
						<input type="number" class="form-control" name="edad_muestreo" value="<?= $edad_muestreo ?>">
					</div>

					<div class="col-xl-3">
						<label class="form-label">Rev. Proy. *</label>
						<input type="number" class="form-control" name="revenimiento_dis" value="<?= $revenimiento_dis ?>">
					</div>

					<div class="col-xl-3">
						<label class="form-label">Rev. Real *</label>
						<input type="number" class="form-control" name="revenimiento_r1" value="<?= $revenimiento_r1 ?>">
					</div>

					<!-- </div> -->



					<div class="col-xl-2">
						<label class="form-label">T.M.A. [mm] *</label>
						<input type="text" class="form-control" name="tma" value="<?= $tma ?>">
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

					<div class="col-xl-6">
						<label class="form-label">Revisado y autorizado por: *</label>
						<select class="form-select" name="revisado_autorizado" data-live-search="true">
							<option value="<?= $revisado_autorizado ?>" selected><?= $revisado_autorizado ?></option>
							<?php foreach ($personalLista as $p): ?>
								<option value="<?= $p['Nombre'] ?>"><?= $p['Nombre'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>

				</div>



			</div>
		</div>


		<div class="card">
			<!-- <div class="card-header with-btn">
				ENSAYE A LA COMPRESIÓN DE ESPECÍMENES CILÍNDRICOS DE CONCRETO
				<div class="card-header-btn">
					<a href="#" data-toggle="card-collapse" class="btn"><iconify-icon icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
					<a href="#" data-toggle="card-expand" class="btn"><iconify-icon icon="material-symbols-light:fullscreen"></iconify-icon></a>
					<a href="#" data-toggle="card-remove" class="btn"><iconify-icon icon="material-symbols-light:close-rounded"></iconify-icon></a>
				</div>
			</div> -->

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
										<!-- <th>Diametro 1 [cm]</th>
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
										<th>Capturó</th> -->
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
	const personalLista = <?= json_encode($personalLista, JSON_UNESCAPED_UNICODE) ?>;

	function generarOpcionesPersonal() {
		let opciones = `<option value=""></option>`;
		personalLista.forEach(p => {
			opciones += `<option value="${p.Nombre}">${p.Nombre}</option>`;
		});
		return opciones;
	}
</script>

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


	// === ACTUALIZAR VIGAS SEGÚN LA EDAD DE MUESTREO ===
	function actualizarVigas() {

		let edad = document.querySelector("input[name='edad_muestreo']").value;

		let edades = document.querySelectorAll("input[name^='edad_item']");
		let tolerancias = document.querySelectorAll("input[name^='tolerancia']");

		if (edades.length === 0) return;

		switch (edad) {

			case "1":
				edades.forEach(e => e.value = 1);
				tolerancias.forEach(t => t.value = 0.5);
				break;

			case "3":
				edades.forEach(e => e.value = 3);
				tolerancias.forEach(t => t.value = 2);
				break;

			case "5":
				edades[0].value = 1;
				edades[1].value = 3;
				edades[2].value = 5;

				tolerancias[0].value = 0.5;
				tolerancias[1].value = 2;
				tolerancias[2].value = 2;
				break;

			case "7":
				edades[0].value = 3;
				edades[1].value = 5;
				edades[2].value = 7;

				tolerancias[0].value = 2;
				tolerancias[1].value = 2;
				tolerancias[2].value = 6;
				break;

			case "14":
				edades[0].value = 5;
				edades[1].value = 7;
				edades[2].value = 14;

				tolerancias[0].value = 2;
				tolerancias[1].value = 6;
				tolerancias[2].value = 12;
				break;

			case "28":
				edades[0].value = 7;
				edades[1].value = 14;
				edades[2].value = 28;

				tolerancias[0].value = 6;
				tolerancias[1].value = 12;
				tolerancias[2].value = 20;
				break;
		}
		actualizarFechasEnsaye();
	}



	// Ejecutar cuando cambie la edad del muestreo
	document.querySelector("input[name='edad_muestreo']").addEventListener("input", actualizarVigas);


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
		fetch(`ajax_get_expediente_viga.php?expediente=${expediente}`)
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
				return fetch(`ajax_get_ultimo_viga.php?expediente=${expediente}`);
			})
			.then(r => r.json())
			.then(data => {

				if (data.error) {
					alert(data.error);
					return;
				}

				reconstruirVigas(data.ultimo_item);
			})
			.catch(err => console.error(err));
	});



	function reconstruirVigas(ultimoItem) {

		const tbody = document.querySelector("table tbody");
		if (!tbody) return;

		tbody.innerHTML = "";
		let opcionesPersonal = generarOpcionesPersonal();
		for (let i = 1; i <= 3; i++) {

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

                <td><input type="text" class="form-control diametro1" data-id="${id}" name="diametro1[${id}]" hidden></td>
                <td><input type="text" class="form-control diametro2" data-id="${id}" name="diametro2[${id}]" hidden></td>

                <td><input type="text" class="form-control" name="altura1[${id}]" hidden></td>
                <td><input type="text" class="form-control" name="altura2[${id}]" hidden></td>

                <td>
                    <select class="form-select" name="condicion_especimen[${id}]" hidden>
                        <option></option>
                        <option>Bien</option>
                        <option>Mal</option>
                    </select> 
                </td>

                <td><input type="text" class="form-control" name="flexometro[${id}]" value="9" hidden></td>
                <td><input type="text" class="form-control" name="escuadra[${id}]" value="4" hidden></td>
                <td><input type="text" class="form-control" name="compas[${id}]" value="1" hidden></td>

                <td><input type="text" class="form-control" name="prensa[${id}]" hidden></td>
                <td><input type="time" class="form-control" name="hora_ensaye[${id}]" hidden></td>

                <td><input type="text" class="form-control carga" data-id="${id}" name="carga[${id}]" hidden></td>

                <td><span class="fc_res" id="fc_res_${id}" hidden>—</span></td>

                <td><input type="text" class="form-control" name="tiempo_ensaye[${id}]" hidden></td>

                <td>
                    <select class="form-select" name="falla[${id}]" hidden>
                        <option></option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                    </select>
                </td>

                <td><input type="text" class="form-control" name="observaciones[${id}]" hidden></td>
<td>
    <select class="form-select" name="persona_ensayo[${id}]" hidden>
        ${opcionesPersonal}
    </select>
</td>

<td>
    <select class="form-select" name="persona_capturo[${id}]" hidden>
        ${opcionesPersonal}
    </select>
</td>
            </tr>
        `);
		}

		actualizarVigas();
		// 2️⃣ YA existen edad_item → calcular fechas
		actualizarFechasEnsaye();
	}
</script>
<script>
window.addEventListener("DOMContentLoaded", function () {
    const selectExp = document.getElementById("seleccionar_exp");

    if (selectExp && selectExp.value) {
        // Disparar el mismo evento que si el usuario lo seleccionara
        selectExp.dispatchEvent(new Event("change"));
    }
});
</script>
