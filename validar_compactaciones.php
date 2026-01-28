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
$usuario = $_GET['usuario']; // NO urldecode
$llave   = $_GET['llave'] ?? '';
$atencion = $_POST['atencion']
	?? $_GET['atencion']
	?? '';


$ruta = "Compactaciones/Reportes/$usuario/$llave";
$reporte = firebaseGet($ruta);

if ($reporte === null) {
	die("NO SE ENCONTRÓ EL REPORTE<br>Ruta lógica: $ruta");
}

$cliente = $reporte['cliente'] ?? 'SIN CLIENTE';





/* VARIABLES DEL REPORTE */
$cliente = "";
$id_cliente = "";
$obra = $reporte['obra'] ?? 'SIN OBRA';
$expediente = $reporte['expediente'] ?? 'SIN OBRA';
$localizacion = $reporte['localizacion'] ?? 'SIN LOCALIZACION';
// $reporte = "";

$capa = $reporte['capa'] ?? 'SIN Capa';
$tramo = $reporte['tramo'] ?? 'SIN Tramo';
$subtramo = $reporte['subTramo'] ?? 'SIN Subtramo';
// $fc = $reporte['fc'] ?? 'SIN fc';
$humedad_optima = $reporte['humedad'] ?? 'SIN humedad';
$fecha = $reporte['fecha'] ?? 'SIN FECHA';
$compactacion_proyecto = $reporte['compactacion'] ?? 'SIN compactacion_proyecto';
$mvsm = $reporte['mvsm'] ?? 'SIN mvsm';

$muestreo = $reporte['personal'] ?? 'SIN personal';
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


$id_reporte_compactacion = $_POST['id_reporte_compactacion']
	?? $_GET['id_reporte_compactacion']
	?? null;



// ---------- agregar reporte compactacion ----------

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['cala'])) {


	$expediente = $_POST['expediente'];
	$reporte    = $_POST['reporte'];

	if (empty($_POST['cala'])) {
		die("No hay calas para guardar");
	}

	$conexion->begin_transaction();

	// 	echo "<script>
	// 	alert('CLIENTE: ' + " . $elemento . ");
	// </script>";
	try {

		// =========================
		// 1️⃣ INSERT REPORTE reportes
		// =========================
		$sqlInsertReporte = "
            INSERT INTO reportes (
                expediente, reporte, fecha, localizacion, capa,
                comproyecto, tramo, mvsm, subtramo,
                humoptima
            ) VALUES (
                ?,?,?,?,?,?,?,?,?,?
            )
        ";

		$stmtReporte  = $conexion->prepare($sqlInsertReporte);
		$stmtReporte->bind_param(
			"sissssssss",
			$expediente,
			$reporte,
			$_POST['fecha'],
			$_POST['localizacion'],
			$_POST['capa'],
			$_POST['compactacion_proyecto'],
			$_POST['tramo'],
			$_POST['mvsm'],
			$_POST['subtramo'],
			$_POST['humedad_optima']
		);
		$stmtReporte->execute();
		// 👉 ESTE es el ID que usarás para las calas
		$id_reporte_compactacion = $conexion->insert_id;

		// =========================
		// 1️⃣ INSERT REPORTE registros_compactacion_campo
		// =========================
		$sqlInsertCampo = "
    INSERT INTO registros_compactacion_campo (
        exp, reporte, fecha, localizacion, capa,
        comproyecto, tramo, mvsm, subtramo, humoptima,
		obra, cliente, atencion, id_reporte_compactacion,
		personal, observaciones
    ) VALUES (
        ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
    )
";

		$stmtCampo = $conexion->prepare($sqlInsertCampo);
		$stmtCampo->bind_param(
			"sissssssssssssss",
			$expediente,
			$reporte,
			$_POST['fecha'],
			$_POST['localizacion'],
			$_POST['capa'],
			$_POST['compactacion_proyecto'],
			$_POST['tramo'],
			$_POST['mvsm'],
			$_POST['subtramo'],
			$_POST['humedad_optima'],
			$_POST['obra'],
			$_POST['cliente'],
			$atencion,
			$id_reporte_compactacion,
			$_POST['muestreo'],
			$_POST['observacion']
		);

		$stmtCampo->execute();

		// =========================
		// 2️⃣ INSERT calas compactaciones
		// =========================
		$sqlInsertItem = "
            INSERT INTO compactaciones (
                id_reporte_compactacion, expediente, reporte,
                cala, estacion, prof,
                mvsl, humedad, compactacion
            ) VALUES (
                ?,?,?,?,?,?,?,?,?
            )
        ";

		$stmtItem = $conexion->prepare($sqlInsertItem);

		foreach ($_POST['cala'] as $index => $valor) {

			$stmtItem->bind_param(
				"iisssssss",
				$id_reporte_compactacion,
				$expediente,                  // ✅ string, no array
				$reporte,
				$_POST['cala'][$index],
				$_POST['estacion'][$index],
				$_POST['prof'][$index],
				$_POST['mvsl'][$index],       // ✅ bien escrito
				$_POST['humedad'][$index],
				$_POST['compactacion'][$index]
			);

			$stmtItem->execute();
		}
		// =========================
		// 2️⃣ INSERT registros calas campo
		// =========================
		$sqlInsertItem = "
            INSERT INTO registros_calas_campo (
                id_reporte_compactacion,
                cala, estacion, profundidad,
                mvsl, humedad_lugar, compactacion_cala
            ) VALUES (
                ?,?,?,?,?,?,?
            )
        ";

		$stmtItem_calas = $conexion->prepare($sqlInsertItem);

		foreach ($_POST['cala'] as $index => $valor) {

			$stmtItem_calas->bind_param(
				"iisssss",
				$id_reporte_compactacion,
				$_POST['cala'][$index],
				$_POST['estacion'][$index],
				$_POST['prof'][$index],
				$_POST['mvsl'][$index],       // ✅ bien escrito
				$_POST['humedad'][$index],
				$_POST['compactacion'][$index]
			);

			$stmtItem_calas->execute();
		}


		// =========================
		// 3️⃣ COMMIT
		// =========================
		$conexion->commit();

		// =========================
		// 4️⃣ BORRAR REPORTE EN FIREBASE
		// =========================
		// firebaseDelete($ruta);

		echo "<script>
            alert('Reporte y calas guardados correctamente $elemento');
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
	$id_reporte_compactacion = $_GET['id_reporte_compactacion'];


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
	}
}
?>



<!-- BEGIN #content -->
<form method="POST">

	<input type="hidden" name="id_reporte_compactacion" value="<?= $id_reporte_compactacion ?>">



	<div id="content" class="app-content">
		<ul class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">LAYOUT</a></li>
			<li class="breadcrumb-item active">STARTER PAGE</li>
		</ul>

		<h1 class="page-header">
			Validación de compactaciones <small></small>
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
				DATOS DE LA COMPACTACIÓN
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
							<label class="form-label">Capa *</label>
							<input type="text" class="form-control" name="capa" value="<?= $capa ?>">
						</div>
					</div>

					<div class="col-xl-3">
						<div class="mb-3">
							<label class="form-label">Tramo *</label>
							<input type="text" class="form-control" name="tramo" value="<?= $tramo ?>">
						</div>
					</div>

					<div class="col-xl-3">
						<div class="mb-3">
							<label class="form-label">Subtramo *</label>
							<input type="text" class="form-control" name="subtramo" value="<?= $subtramo ?>">
						</div>
					</div>
					<div class="col-xl-3">
						<div class="mb-3">
							<label class="form-label">Compactación *</label>
							<input type="text" class="form-control" name="compactacion_proyecto" value="<?= $compactacion_proyecto ?>">
						</div>
					</div>
					<div class="col-xl-3">
						<div class="mb-3">
							<label class="form-label">M.V.S.M. *</label>
							<input type="text" class="form-control" name="mvsm" value="<?= $mvsm ?>">
						</div>
					</div>
					<div class="col-xl-3">
						<div class="mb-3">
							<label class="form-label">Humedad óptima *</label>
							<input type="text" class="form-control" name="humedad_optima" value="<?= $humedad_optima ?>">
						</div>
					</div>
					<div class="col-xl-3">
						<div class="mb-3">
							<label class="form-label">Fecha *</label>
							<input type="date" class="form-control" name="fecha" value="<?= $fecha ?>">
						</div>
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
						<label class="form-label">Observaciones *</label>
						<input type="text" class="form-control" name="observacion" value="<?= $observacion ?>">
					</div>
					<!-- <div class="row"> -->




				</div>



			</div>
		</div>


		<div class="card">


			<div class="card-body">

				<div class="card">
					<div class="card-header with-btn">
						RESULTADOS DE LAS CALAS DE COMPACTACIÓN
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
										<th>Cala</th>
										<th>Estación</th>
										<th>Profundidad</th>
										<th>Humedad de lugar</th>
										<th>MVSL</th>
										<th>Compactación</th>

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

		<script>
			const calasFirebase = <?= json_encode($reporte['listaCalas'] ?? [], JSON_UNESCAPED_UNICODE) ?>;
		</script>
		<script>
			function cargarCalas() {

				const tbody = document.querySelector("table tbody");
				tbody.innerHTML = "";

				if (!calasFirebase.length) {
					tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted">
                    No hay calas registradas
                </td>
            </tr>`;
					return;
				}

				calasFirebase.forEach((cala, index) => {

					const fila = document.createElement("tr");

					fila.innerHTML = `
			<td class="text-center">${index + 1}</td>
            <td><input type="text" class="form-control" name="cala[]" value="${cala.cala+=1 ?? ''}"></td>
            <td><input type="text" class="form-control" name="estacion[]" value="${cala.estacion ?? ''}"></td>
            <td><input type="text" class="form-control text-center" name="prof[]" value="${cala.prof ?? ''}"></td>
            <td><input type="text" class="form-control text-center" name="humedad[]" value="${cala.humedad ?? ''}"></td>
   <!-- MVSL -->
    <td>
        <input type="text"
               class="form-control mvsl text-center"
               data-index="${index}"
               name="mvsl[]"
               value="${cala.mvsl ?? ''}">
    </td>

    <!-- COMPACTACIÓN -->
    <td>
        <input type="text"
               class="form-control text-center compactacion"
               name="compactacion[]"
               readonly>
    </td>
        `;

					tbody.appendChild(fila);
				});
				// 🔥 CALCULAR TODAS AL CARGAR
				document.querySelectorAll(".mvsl").forEach(input => {
					calcularCompactacionPorIndice(input.dataset.index);
				});
			}

			// 🚀 Cargar automáticamente al abrir la página
			document.addEventListener("DOMContentLoaded", cargarCalas);
		</script>

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
	document.getElementById("seleccionar_exp").addEventListener("change", function() {

		const expediente = this.value;
		if (!expediente) return;

		// 1️⃣ Cargar datos generales del expediente
		fetch(`ajax_get_expediente_compactacion.php?expediente=${expediente}`)
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
			})
			.then(r => r.json())
			.then(data => {

				if (data.error) {
					alert(data.error);
					return;
				}

			})
			.catch(err => console.error(err));
	});
</script>
<script>
	function calcularCompactacionPorIndice(index) {

		// MVSM del proyecto
		const mvsmInput = document.querySelector("input[name='mvsm']");
		if (!mvsmInput) return;

		const mvsmProyecto = parseFloat(mvsmInput.value);

		// MVSL de la cala
		const mvslInput = document.querySelector(
			`.mvsl[data-index='${index}']`
		);
		const mvsl = parseFloat(mvslInput.value);

		// Campo compactación (misma fila)
		const compactacionInput = mvslInput
			.closest("tr")
			.querySelector(".compactacion");

		if (isNaN(mvsmProyecto) || mvsmProyecto <= 0 || isNaN(mvsl)) {
			compactacionInput.value = "";
			return;
		}

		const compactacion = (mvsl / mvsmProyecto) * 100;
		compactacionInput.value = compactacion.toFixed(2);
	}

	// 🔁 Detectar cambios en MVSL
	document.addEventListener("input", function(e) {
		if (e.target.classList.contains("mvsl")) {
			const index = e.target.dataset.index;
			calcularCompactacionPorIndice(index);
		}
	});

	// 🔁 Recalcular todo si cambia el MVSM del proyecto
	document.querySelector("input[name='mvsm']")
		?.addEventListener("input", function() {

			document.querySelectorAll(".mvsl").forEach(input => {
				calcularCompactacionPorIndice(input.dataset.index);
			});
		});
</script>