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
$exp_registro = "";
$localizacion = "";
$reporte = "";
$elemento = "";
$ubicacion = "";
$fc = "";
$edad = "";

$id_viga = $_POST['id_viga']
	?? $_GET['id_viga']
	?? null;


if (isset($_GET['exp_registro']) && isset($_GET['reporte'])) {

	$exp = $_GET['exp_registro'];
	$rep = $_GET['reporte'];
	$id_viga = $_GET['id_viga'];


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
	$sql = "SELECT * FROM vista_vigas_completa 
            WHERE exp_registro = '$exp' AND reporte = '$rep' 
            LIMIT 1";
	$res = $conexion->query($sql);

	if ($res->num_rows > 0) {
		$data = $res->fetch_assoc();

		// Llenar variables
		// $cliente       = $data['cliente'];
		// $id_cliente    = $data['id_cliente'];
		// $obra          = $data['obra'];
		$exp_registro    = $data['exp_registro'];
		// $localizacion  = $data['ubicacion'];
		$fecha       = $data['fecha_viga'];
		$fecha_recepcion       = $data['fecha_recepcion'];
		$reporte       = $data['reporte'];
		$elemento      = $data['elemento'];
		$ubicacion     = $data['ubicacion_viga'];
		$fc            = $data['fc_viga'];
		$edad          = $data['edad'];
		$revenimientop          = $data['revenimientop'];
		$revenimientor          = $data['revenimientor'];
		$tma          = $data['agregado'];
		$concretera          = $data['concretera_viga'];
		$temperatura          = $data['temperatura_viga'];
		$remision          = $data['remision_viga'];
		$volumen          = $data['volumen'];
		$hora_muestreo          = $data['hora_muestreo'];
		$hora_desmoldeo          = $data['hora_desmoldeo'];

		$muestreo          = $data['personal_muestreo'];
		$recibio          = $data['personal_recibio'];
		$observacion          = $data['observaciones_viga'];
		// $revisado_autorizado          = $data['revisado_autorizado'];
	}
	// ---------- ACTUALIZAR DATOS DE MUESTREO ----------

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		$fecha = $_POST['fecha'];
		$fecha_recepcion = $_POST['fecha_recepcion'];
		$elemento = $_POST['elemento'];
		$ubicacion = $_POST['ubicacion'];
		// $fc = $_POST['fc'];
		// $edad = $_POST['edad'];
		$revenimientop = $_POST['revenimientop'];
		$revenimientor = $_POST['revenimientor'];
		$tma = $_POST['agregado'];
		$concretera = $_POST['concretera'];
		$temperatura = $_POST['temperatura'];
		$remision = $_POST['remision'];
		$volumen = $_POST['volumen'];
		// $hora_muestreo = $_POST['hora_muestreo'];
		$hora_desmoldeo = $_POST['hora_desmoldeo'];
		$muestreo = $_POST['muestreo'];
		$recibio = $_POST['recibio'];
		$observacion = $_POST['observacion'];

		$sqlUpdate = "UPDATE vigas SET
		fecha = '$fecha',
		fecha_recepcion = '$fecha_recepcion',
		elemento = '$elemento',
		ubicacion = '$ubicacion',
		-- fc = '$fc',
		-- edad = '$edad',
		revenimientop = '$revenimientop',
		revenimientor = '$revenimientor',
		agregado = '$tma',
		concretera = '$concretera',
		temperatura = '$temperatura',
		remision = '$remision',
		volumen = '$volumen',
		-- hora_muestreo = '$hora_muestreo',
		hora_desmoldeo = '$hora_desmoldeo',
		muestreo = '$muestreo',
		recibio = '$recibio',
		observacion = '$observacion'
	WHERE id = '$id_viga'";


		$sqlUpdate = "UPDATE registros_vigas_campo_actualizado SET
		-- fecha = '$fecha',
		-- fecha_recepcion = '$fecha_recepcion',
		-- elemento = '$elemento',
		-- ubicacion = '$ubicacion',
		-- fc = '$fc',
		-- edad = '$edad',
		-- revenimientop = '$revenimientop',
		-- revenimientor = '$revenimientor',
		-- agregado = '$tma',
		-- concretera = '$concretera',
		-- temperatura = '$temperatura',
		-- remision = '$remision',
		-- volumen = '$volumen',
		hora_muestreo = '$hora_muestreo',
		-- hora_desmoldeo = '$hora_desmoldeo',
		-- muestreo = '$muestreo',
		-- recibio = '$recibio',
		-- observacion = '$observacion'
	WHERE id = '$id_viga'";



		// if ($conexion->query($sqlUpdate)) {
		// 	// 	echo "<script>alert('Datos de muestreo actualizados correctamente'); 
		// 	// window.location.href='captura_cilindros.php?exp_registro=$exp&reporte=$rep';</script>";
		// } else {
		// 	echo "Error: " . $conexion->error;
		// }
	}
}
// ---------- ACTUALIZAR ENSAYE DE ESPECÍMENES ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$id_viga = $_POST['id_viga'];

	for ($i = 1; $i <= 3; $i++) {

		$ensaye     = $_POST["ensaye$i"] ?? null;
		$edad       = $_POST["edad$i"] ?? null;
		$diametro   = $_POST["diametro$i"] ?? null;
		$diametroo  = $_POST["diametroo$i"] ?? null;
		$altura     = $_POST["altura$i"] ?? null;
		$alturaa    = $_POST["alturaa$i"] ?? null;
		$L_vigas    = $_POST["L_vigas$i"] ?? null;
		$carga      = $_POST["carga$i"] ?? null;
		$a_vigas    = $_POST["a_vigas$i"] ?? null;
		$fc         = $_POST["fc$i"] ?? null;
		$tiempo     = $_POST["tiempo_ensaye$i"] ?? null;
		$falla      = $_POST["falla$i"] ?? null;
		$flexo      = $_POST["flexometro$i"] ?? null;
		$prensa     = $_POST["prensa$i"] ?? null;
		$disp       = $_POST["dispositivo_viga$i"] ?? null;
		$laina      = $_POST["laina$i"] ?? null;

		$sql = "
        UPDATE vigas SET
            ensaye$i = '$ensaye',
            edad$i = '$edad',
            diametro$i = '$diametro',
            diametroo$i = '$diametroo',
            altura$i = '$altura',
            alturaa$i = '$alturaa',
            L_vigas$i = '$L_vigas',
            carga$i = '$carga',
            a_vigas$i = '$a_vigas',
            fc$i = '$fc',
            tiempo_ensaye$i = '$tiempo',
            falla$i = '$falla',
            flexometro$i = '$flexo',
            prensa$i = '$prensa',
            dispositivo_viga$i = '$disp',
            laina$i = '$laina'
        WHERE id = '$id_viga'
        ";

		$conexion->query($sql);
	}

	echo "<script>alert('Ensayes actualizados correctamente');</script>";
}




if (isset($_GET['exp_registro']) && isset($_GET['reporte'])) {

	$exp = $_GET['exp_registro'];
	$rep = $_GET['reporte'];
	$id_viga = $_GET['id_viga'];


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
		// $exp_registro    = $data['exp_registro'];
		$localizacion  = $data['localizacion'];
	}
}
if (isset($_GET['exp_registro']) && isset($_GET['reporte'])) {

	$exp = $_GET['exp_registro'];
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
		// $exp_registro    = $data['exp_registro'];
		// $localizacion  = $data['localizacion'];
	}
}
?>



<!-- BEGIN #content -->
<form method="POST">

	<!-- echo "<script>alert('Datos de muestreo actualizados correctamente'); </script>"; -->

	<input type="hidden" name="id_viga" value="<?= $id_viga ?>">

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
							<input type="text" class="form-control"
								value="<?= $cliente ?>" readonly
								placeholder="Nombre del cliente">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Id cliente <span class="text-danger"></label>
							<input type="number" class="form-control"
								value="<?= $id_cliente ?>" readonly
								placeholder="Id cliente">
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
								placeholder="Nombre de la obra">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Expediente <span class="text-danger"></label>
							<input type="number" class="form-control"
								value="<?= $exp_registro ?>" readonly
								placeholder="Numero de exp_registro">
						</div>
					</div>

					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Localización <span class="text-danger"></label>
							<input type="text" class="form-control"
								value="<?= $localizacion ?>" readonly
								placeholder="Localización">
						</div>
					</div>

					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Reporte <span class="text-danger"></label>
							<input type="number" class="form-control"
								value="<?= $reporte ?>" readonly
								placeholder="Numero de reporte">
						</div>
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
				ENSAYE A LA COMPRESIÓN DE ESPECÍMENES DE VIGAS DE CONCRETO
				<div class="card-header-btn">
					<a href="#" data-toggle="card-collapse" class="btn"><iconify-icon icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
					<a href="#" data-toggle="card-expand" class="btn"><iconify-icon icon="material-symbols-light:fullscreen"></iconify-icon></a>
					<a href="#" data-toggle="card-remove" class="btn"><iconify-icon icon="material-symbols-light:close-rounded"></iconify-icon></a>
				</div>
			</div>

			<div class="card-body">

				<?php
				$sql = "SELECT * FROM vista_vigas_completa 
                WHERE id_viga = '$id_viga'";
				$resultado = $conexion->query($sql);

				if ($resultado->num_rows > 0) {

					$clientes = $resultado->fetch_all(MYSQLI_ASSOC);

					echo '<div class="table-responsive">';
					echo '<table class="table table-bordered table-striped table-hover wide-table">';
					echo '
            <thead class="table-dark">
            <tr>
                <th>Item</th>
                <th>Fecha ensaye</th>
                <th>Edad [d]</th>
                <th>Lado 1 [cm]</th>
                <th>Lado 2 [cm]</th>
                <th>Altura 1 [cm]</th>
                <th>Altura 2 [cm]</th>
                <th>L [cm]</th>
                <th>Carga</th>
                <th>a [cm]</th>
                <th>MR [kgf/cm²]</th>
                <th>MR [%]</th>
                <th>Tiempo de ensaye [s]</th>
                <th>Tiempo de mínimo [s]</th>
                <th>Velocidad de aplicación [kgf/s]</th>
                <th>¿Cumple con la velocidad?</th>

                <th>Falla</th>
                <th>Flexómetro</th>
                <th>Prensa</th>

                <th>Dispositivo de viga</th>
                <th>Laina</th>
              
            </tr>
            </thead>';

					echo '<tbody>';

					for ($i = 1; $i <= 3; $i++) {

						foreach ($clientes as $fila) {

							$id        = $fila["item$i"];
							$ensayeRaw = $fila["ensaye$i"]; // dd/mm/yyyy
							$partes    = explode('/', $ensayeRaw);
							$ensaye    = $partes[2] . '-' . $partes[1] . '-' . $partes[0];

							$edad      = $fila["edad$i"];
							$diametro  = $fila["diametro$i"];
							$diametroo = $fila["diametroo$i"];
							$altura    = $fila["altura$i"];
							$alturaa   = $fila["alturaa$i"];
							$L_vigas   = $fila["L_vigas$i"];
							$carga     = $fila["carga$i"];
							$a_vigas   = $fila["a_vigas$i"];
							$resistencia        = $fila["resistencia$i"];
							$fc        = $fila["fc$i"];
							$tiempo    = $fila["tiempo_ensaye$i"];
							$falla     = $fila["falla$i"];
							$flexo     = $fila["flexometro$i"];
							$prensa    = $fila["prensa$i"];
							$disp      = $fila["dispositivo_viga$i"];
							$laina     = $fila["laina$i"];
							echo "
        <tr>
    <td><?= $id ?></td>

    <td><input type='date' class='form-control' name='ensaye<?= $i ?>' value='$ensaye'></td>
    <td><input type='text' class='form-control' name='edad<?= $i ?>' value='$edad'></td>

    <td><input type='text' class='form-control' name='diametro<?= $i ?>' value=' $diametro'></td>
    <td><input type='text' class='form-control' name='diametroo<?= $i ?>' value='$diametroo'></td>

    <td><input type='text' class='form-control' name='altura<?= $i ?>' value='$altura'></td>
    <td><input type='text' class='form-control' name='alturaa<?= $i ?>' value='$alturaa'></td>

    <td><input type='text' class='form-control' name='L_vigas<?= $i ?>' value='$L_vigas'></td>
    <td><input type='text' class='form-control' name='carga<?= $i ?>' value='$carga'></td>
    <td><input type='text' class='form-control' name='a_vigas<?= $i ?>' value='$a_vigas'></td>

    <td><input type='text' class='form-control' name='fc<?= $i ?>' value='$resistencia'></td>
    <td><input type='text' class='form-control' name='fc<?= $i ?>' value='$fc'></td>

    <td><input type='text' class='form-control' name='tiempo_ensaye<?= $i ?>' value='$tiempo'></td>
    <td><input type='text' class='form-control' value=''></td>
    <td><input type='text' class='form-control' value=''></td>
    <td><input type='text' class='form-control' value=''></td>

    
    <td><input type='text' class='form-control' name='falla<?= $i ?>' value=' $falla'></td>
    <td><input type='text' class='form-control' name='flexometro<?= $i ?>' value='$flexo '></td>
    <td><input type='text' class='form-control' name='prensa<?= $i ?>' value=' $prensa'></td>

    <td><input type='text' class='form-control' name='dispositivo_viga<?= $i ?>' value=' $disp '></td>
    <td><input type='text' class='form-control' name='laina<?= $i ?>' value=' $laina'></td>
</tr>";
						}
					}


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

			let campoFecha = document.querySelector(`input[name='ensaye1[${idItem}]']`);
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
</script>