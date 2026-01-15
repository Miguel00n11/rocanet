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

// Verificar si vienen datos por GET (editar)
$cliente = "";
$id_cliente = "";
$obra = "";
$expediente = "";
$localizacion = "";
$reporte = "";
$capa = "";
$tramo = "";
$comproyecto = "";
$mvsm = "";
$humoptima = "";
$edad = "";
$personal = "";
$observaciones = "";

$id = $_POST['id']
	?? $_GET['id']
	?? null;

$idReporte = $id; // ← este es el id_reporte_compactacion

if (isset($_GET['expediente']) && isset($_GET['reporte'])) {

	$exp = $_GET['expediente'];
	$rep = $_GET['reporte'];
	$id = $_GET['id'];


	// Obtener lista de personal
	$sqlPersonal = "SELECT ID, Nombre FROM personal ORDER BY Nombre ASC";
	$resPersonal = $conexion_forta->query($sqlPersonal);
	// Guardar resultados en un arreglo
	$personalLista = [];
	while ($row = $resPersonal->fetch_assoc()) {
		$personalLista[] = $row;
	}
	// ----

	// Consulta del registro
	$sql = "SELECT * FROM reportes 
            WHERE expediente = '$exp' AND reporte = '$rep' 
            LIMIT 1";
	$res = $conexion->query($sql);



	if ($res->num_rows > 0) {
		$data = $res->fetch_assoc();

		// Llenar variables
		// $cliente       = $data['cliente'];
		// $id_cliente    = $data['id_cliente'];
		// $obra          = $data['obra'];
		$expediente    = $data['expediente'];
		// $localizacion  = $data['ubicacion'];
		$reporte       = $data['reporte'];

		$fecha       = $data['fecha'];
		$capa      = $data['capa'];
		$tramo     = $data['tramo'];
		$comproyecto            = $data['comproyecto'];
		$mvsm            = $data['mvsm'];
		$humoptima            = $data['humoptima'];
		$subtramo          = $data['subtramo'];

		// $revisado_autorizado          = $data['revisado_autorizado'];
	}

	// Consulta del registro
	$sql_campo = "SELECT * FROM registros_compactacion_campo 
            WHERE exp = '$exp' AND reporte = '$rep' 
            LIMIT 1";
	$res_campo = $conexion->query($sql_campo);

	if ($res_campo->num_rows > 0) {
		$data = $res_campo->fetch_assoc();

		$personal          = $data['personal'];
		$observaciones          = $data['observaciones'];
	}
	// ---------- ACTUALIZAR DATOS DE MUESTREO ----------

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		$fecha = $_POST['fecha'];
		$capa = $_POST['capa'];
		$tramo = $_POST['tramo'];
		$comproyecto = $_POST['comproyecto'];
		$mvsm = $_POST['mvsm'];
		$humoptima = $_POST['humoptima'];
		$subtramo = $_POST['subtramo'];
		$personal = $_POST['personal'] ?? '';
		$observaciones = $_POST['observaciones'] ?? '';

		$sqlUpdateCampo = "
        UPDATE registros_compactacion_campo SET

			id_reporte_compactacion='$idReporte',
            fecha='$fecha',
            capa='$capa',
            tramo='$tramo',
            comproyecto='$comproyecto',
            mvsm='$mvsm',
            humoptima='$humoptima',
            subtramo='$subtramo',
            personal='$personal',
            observaciones='$observaciones'
        WHERE exp='$exp'
          AND reporte='$rep'
          AND id_reporte_compactacion=" . (int)$idReporte;

		if (!$conexion->query($sqlUpdateCampo)) {
			echo "Error registros_compactacion_campo: " . $conexion->error;
		}
	}





	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item'])) {

		foreach ($_POST['item'] as $idItem) {

			$cala = $_POST['cala'][$idItem] ?? '';
			$estacion = $_POST['estacion'][$idItem] ?? '';
			$prof = $_POST['prof'][$idItem] ?? '';
			$humedad = $_POST['humedad'][$idItem] ?? '';
			$mvsl = $_POST['mvsl'][$idItem] ?? 0;
			$compactacion = $_POST['compactacion'][$idItem] ?? 0;

			/* ---------- TABLA compactaciones ---------- */
			$sql1 = "
        UPDATE compactaciones SET
            estacion='$estacion',
            prof='$prof',
            humedad='$humedad',
            mvsl=" . (float)$mvsl . ",
            compactacion=" . (float)$compactacion . "
        WHERE id=" . (int)$idItem . "
          AND id_reporte_compactacion=" . (int)$idReporte;

			if (!$conexion->query($sql1)) {
				echo "Error compactaciones: " . $conexion->error;
			}

			/* ---------- TABLA registros_calas_campo ---------- */
			$sql2 = "
        UPDATE registros_calas_campo SET
            estacion='$estacion',
            profundidad='$prof',
            humedad_lugar='$humedad',
            mvsl=" . (float)$mvsl . ",
            compactacion_cala=" . (float)$compactacion . "
        WHERE id_reporte_compactacion=" . (int)$idReporte . "
          AND cala=" . (int)$cala;

			if (!$conexion->query($sql2)) {
				echo "Error registros_calas_campo: " . $conexion->error;
			}
		}
	}




	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sqlUpdate = "
    UPDATE reportes SET
        fecha='$fecha',
        capa='$capa',
        tramo='$tramo',
        comproyecto='$comproyecto',
        mvsm='$mvsm',
        humoptima='$humoptima',
        subtramo='$subtramo'
    WHERE expediente='$exp'
      AND reporte='$rep'";

    if ($conexion->query($sqlUpdate)) {
        echo "<script>
            alert('Datos actualizados correctamente');
            window.close();
        </script>";
    } else {
        echo "Error reportes: " . $conexion->error;
    }
}

}
// ---------- ACTUALIZAR CALAS ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item'])) {

	foreach ($_POST['item'] as $idItem) {

		$cala = $_POST['cala'][$idItem] ?? '';
		$estacion = $_POST['estacion'][$idItem] ?? '';
		$prof = $_POST['prof'][$idItem] ?? '';
		$humedad = $_POST['humedad'][$idItem] ?? '';
		$mvsl = $_POST['mvsl'][$idItem] ?? 0;
		$compactacion = $_POST['compactacion'][$idItem] ?? 0;

		// compactaciones
		$sql1 = "
        UPDATE compactaciones SET
            cala='$cala',
            estacion='$estacion',
            prof='$prof',
            humedad='$humedad',
            mvsl=" . (float)$mvsl . ",
            compactacion=" . (float)$compactacion . "
        WHERE id=" . (int)$idItem;

		if (!$conexion->query($sql1)) {
			echo "Error compactaciones: " . $conexion->error;
		}

		// registros_calas_campo
		$sql2 = "
        UPDATE registros_calas_campo SET
            cala='$cala',
            estacion='$estacion',
            profundidad='$prof',
            humedad_lugar='$humedad',
            mvsl=" . (float)$mvsl . ",
            compactacion_cala=" . (float)$compactacion . "
        WHERE id_reporte_compactacion=" . (int)$idReporte . " AND cala=" . (int)$cala;

		if (!$conexion->query($sql2)) {
			echo "Error calas: " . $conexion->error;
		}
	}
}





if (isset($_GET['expediente']) && isset($_GET['reporte'])) {

	$exp = $_GET['expediente'];
	$rep = $_GET['reporte'];
	$id = $_GET['id'];


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

	<input type="hidden" name="id" value="<?= $id ?>">
	<input type="hidden" name="calas_eliminadas" id="calas_eliminadas">

	<div id="content" class="app-content">
		<ul class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">LAYOUT</a></li>
			<li class="breadcrumb-item active">STARTER PAGE</li>
		</ul>

		<h1 class="page-header">
			Captura de compactaciones <small></small>
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
							<input type="text" class="form-control"
								value="<?= $cliente ?>" readonly
								placeholder="Nombre del cliente"
								tabindex='-1'>
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Id cliente <span class="text-danger"></label>
							<input type="number" class="form-control"
								value="<?= $id_cliente ?>" readonly
								placeholder="Id cliente"
								tabindex='-1'>
							<!-- <div class="input-group">
								<label class="input-group-text" for="datepicker-component"><i class="fa fa-calendar"></i></label>
							</div> -->
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Obra <span class="text-danger"></label>
							<input type="text" class="form-control"
								value="<?= $obra ?>" readonly
								placeholder="Nombre de la obra"
								tabindex='-1'>
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Expediente <span class="text-danger"></label>
							<input type="number" class="form-control"
								value="<?= $expediente ?>" readonly
								placeholder="Numero de expediente"
								tabindex='-1'>
						</div>
					</div>

					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Localización <span class="text-danger"></label>
							<input type="text" class="form-control"
								value="<?= $localizacion ?>" readonly
								placeholder="Localización"
								tabindex='-1'>
						</div>
					</div>

					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Reporte <span class="text-danger"></label>
							<input type="number" class="form-control"
								value="<?= $reporte ?>" readonly
								placeholder="Numero de reporte"
								tabindex='-1'>
						</div>
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
						<label class="form-label">Subtramo *</label>
						<input type="text" class="form-control" name="subtramo" value="<?= $subtramo ?>">
					</div>

					<!-- <div class="row"> -->

					<div class="col-xl-3">
						<label class="form-label">Compactación*</label>
						<input type="number" class="form-control" name="comproyecto" value="<?= $comproyecto ?>">
					</div>
					<div class="col-xl-3">
						<label class="form-label">M.V.S.M.</label>
						<input type="number" class="form-control" name="mvsm" value="<?= $mvsm ?>">
					</div>
					<div class="col-xl-3">
						<label class="form-label">Humedad Óptima</label>
						<input type="text" class="form-control" name="humoptima" value="<?= $humoptima ?>">
					</div>

					<div class="col-xl-3">
						<div class="mb-3">
							<label class="form-label">Fecha de muestreo *</label>
							<input type="date" class="form-control" name="fecha" value="<?= $fecha ?>">
						</div>
					</div>



					<div class="col-xl-6">
						<label class="form-label">Personal *</label>
						<select class="form-select" name="personal" data-live-search="true">
							<option value="<?= $personal ?>" selected><?= $personal ?></option>
							<?php foreach ($personalLista as $p): ?>
								<option value="<?= $p['Nombre'] ?>"><?= $p['Nombre'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>


					<div class="col-xl-6">
						<label class="form-label">Observaciones *</label>
						<input type="text" class="form-control" name="observaciones" value="<?= $observaciones ?>">
					</div>

				</div>



			</div>
		</div>


		<div class="card">
			<div class="card-header with-btn">
				RESULTADOS DE LAS CALAS DE COMPACTACIÓN
				<div class="card-header-btn">
					<!-- <a href="#" data-toggle="card-collapse" class="btn"><iconify-icon icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
					<a href="#" data-toggle="card-expand" class="btn"><iconify-icon icon="material-symbols-light:fullscreen"></iconify-icon></a>
					<a href="#" data-toggle="card-remove" class="btn"><iconify-icon icon="material-symbols-light:close-rounded"></iconify-icon></a> -->
				</div>
			</div>

			<div class="card-body">

				<?php
				$sql = "SELECT * FROM compactaciones 
                WHERE id_reporte_compactacion = '$id'
                ORDER BY cala ASC";
				$resultado = $conexion->query($sql);

				if ($resultado->num_rows > 0) {

					$clientes = $resultado->fetch_all(MYSQLI_ASSOC);

					echo '<div class="table-responsive">';
					echo '<table class="table table-bordered table-striped table-hover wide-table">';
					echo '
            <thead class="table-dark">
            <tr>
                <th>Cala</th>
                <th>Estación</th>
                <th>Profundidad</th>
                <th>Humedad de lugar</th>
                <th>MVSM</th>
                <th>Compactación</th>
            </tr>
            </thead>';

					echo '<tbody>';

					// $contadorCala++;

					foreach ($clientes as $fila) {

						$idFila = $fila['id'];

						echo "
    <tr id='fila-{$idFila}'>
            <input type='hidden' name='item[]' value='{$idFila}'>
			
		    <td style='text-align:center;'>
            <input type='hidden' class='form-control'
			name='cala[{$idFila}]' 
			value='{$fila['cala']}' readonly >
			{$fila['cala']}
        </td>

        <td>
            <input type='text' class='form-control'
                   name='estacion[{$idFila}]'
                   value='{$fila['estacion']}'>
        </td>

        <td>
            <input type='text' class='form-control text-center'
                   name='prof[{$idFila}]'
                   value='{$fila['prof']}'>
        </td>

        <td>
            <input type='text' class='form-control text-center'
                   name='humedad[{$idFila}]'
                   value='{$fila['humedad']}'>
        </td>

        <!-- MVSL -->
        <td>
            <input type='text'
                   class='form-control mvsl text-center'
                   data-id='{$idFila}'
                   name='mvsl[{$idFila}]'
                   value='{$fila['mvsl']}'>
        </td>

        <!-- COMPACTACIÓN -->
        <td>
            <input type='text'
                   class='form-control text-center'
                   name='compactacion[{$idFila}]'
                   value='{$fila['compactacion']}'
                   readonly
					tabindex='-1'>
				   
        </td>
		
		
			  
		
    </tr>";
						// $contadorCala++;
					}
					//  <td class='text-center'>
					//         <button type='hidden'
					//                 class='btn btn-danger btn-sm btn-eliminar-cala'
					//                 data-id='{$idFila}'
					// 				tabindex='-1'>
					//             Eliminar
					//         </button>
					//     </td>

					echo "</tbody></table></div>";
				} else {
					echo "No se encontraron registros.";
				}

				?>
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
	function calcularCompactacion(id) {

		// MVSM del proyecto
		let mvsmProyecto = parseFloat(
			document.querySelector("input[name='mvsm']").value
		);

		// MVSM de la cala
		let mvslInput = document.querySelector(
			"input[name='mvsl[" + id + "]']"
		);
		let mvsl = parseFloat(mvslInput.value);

		// Campo donde se escribe la compactación
		let compactacionInput = document.querySelector(
			"input[name='compactacion[" + id + "]']"
		);

		if (isNaN(mvsmProyecto) || mvsmProyecto <= 0 || isNaN(mvsl)) {
			compactacionInput.value = "";
			return;
		}

		let compactacion = (mvsl / mvsmProyecto) * 100;
		compactacionInput.value = compactacion.toFixed(2);
	}

	// Detectar cambios en MVSM de cada fila
	document.addEventListener("input", function(e) {
		if (e.target.classList.contains("mvsl")) {
			let id = e.target.dataset.id;
			calcularCompactacion(id);
		}
	});

	// Recalcular todo si cambia el MVSM del proyecto
	document.querySelector("input[name='mvsm']")
		.addEventListener("input", function() {

			document.querySelectorAll(".mvsl").forEach(input => {
				let id = input.dataset.id;
				calcularCompactacion(id);
			});
		});
</script>
<script>
	let calasEliminadas = [];

	document.addEventListener("click", function(e) {

		if (!e.target.classList.contains("btn-eliminar-cala")) return;

		const id = e.target.dataset.id;

		if (!confirm("¿Deseas eliminar esta cala?  " + id)) return;

		// Quitar fila del DOM (igual que listaCalas.Remove)
		const fila = document.getElementById("fila-" + id);
		if (fila) fila.remove();

		// Guardar ID eliminado
		calasEliminadas.push(id);
		document.getElementById("calas_eliminadas").value =
			calasEliminadas.join(",");
	});
</script>