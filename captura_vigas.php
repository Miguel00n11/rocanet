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
$mr = "";
$edad = "";

$id_viga = $_POST['id_viga']
	?? $_GET['id_viga']
	?? null;

$item1 = $_POST['id_especimen1']
	?? $_GET['id_especimen1']
	?? null;


if (isset($_GET['exp_registro']) && isset($_GET['reporte'])) {

	$exp = $_GET['exp_registro'];
	$rep = $_GET['reporte'];
	$id_viga = $_GET['id_viga'];
	$item1 = $_GET['item1'];


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
		$mr            = $data['fc_viga'];
		// $mr            = $data['fc'];
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

	// if ($_SERVER['REQUEST_METHOD'] === 'POST') {



	// 	// $sqlCampo = "
	//     // UPDATE registros_vigas_campo_actualizado SET
	//     //     hora_muestreo = '$hora_muestreo'
	//     // WHERE id_especimen1 = '$id_viga'"
	// 	// ;
	// echo "<script>alert($muestreo);</script>";

	// 	// $conexion->query($sqlVigas);
	// 	// $conexion->query($sqlCampo);
	// }
}
// ---------- ACTUALIZAR ENSAYE DE ESPECÍMENES ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


	$id_viga = $_POST['id_viga'] ?? null;
	// $item1 = $_POST['id_especimen1'] ?? null;

	$fecha = $_POST['fecha'];
	// $fecha_recepcion = $_POST['fecha_recepcion'];
	$elemento = $_POST['elemento'];
	$mr = $_POST['fc'];
	$ubicacion = $_POST['ubicacion'];
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

	$sqlVigas = "UPDATE registros_vigas_campo_actualizado SET
            fecha = '$fecha',
         
			
            elemento_colado = '$elemento',
            fc = '$mr',
            ubicacion = '$ubicacion',
            revenimiento_dis = '$revenimientop',
            revenimiento_r1 = '$revenimientor',
            tma = '$tma',
            concretera = '$concretera',
            temperatura = '$temperatura',
            remision = '$remision',
            volumen_muestra = '$volumen',
            hora_muestreo = '$hora_muestreo',
			
            personal = '$muestreo',
			
            observaciones = '$observacion'
        WHERE id_especimen1 = '$item1'
    ";
	// $conexion->query($sqlCampo);

	// echo "<script>alert('$item1');</script>";
	$conexion->query($sqlVigas);

	if ($conexion->affected_rows === 0) {
		error_log("No se actualizó ningún registro en registros_vigas_campo_actualizado para id_especimen1 = $item1");
	}




	for ($i = 1; $i <= 3; $i++) {

		$id_viga = $_POST['id_viga'] ?? null;

		$ensaye = $_POST["ensaye$i"] ?? '';

		if (!empty($ensaye)) {
			$fechaObj = DateTime::createFromFormat('Y-m-d', $ensaye);
			$ensaye = $fechaObj ? $fechaObj->format('d/m/Y') : $ensaye;
		}
		$tma       = $_POST["agregado"] ?? '';
		$fecha       = $_POST["fecha"] ?? '';
		$fecha_recepcion       = $_POST["fecha_recepcion"] ?? '';
		$edad       = $_POST["edad$i"] ?? '';
		$diametro   = $_POST["diametro$i"] ?? '';
		$diametroo  = $_POST["diametroo$i"] ?? '';
		$altura     = $_POST["altura$i"] ?? '';
		$alturaa    = $_POST["alturaa$i"] ?? '';
		$L_vigas    = $_POST["L_vigas$i"] ?? '';
		$carga      = $_POST["carga$i"] ?? '';
		$a_vigas    = $_POST["a_vigas$i"] ?? '';
		$resistencia         = $_POST["resistencia$i"] ?? '';
		$fc         = $_POST["fc$i"] ?? '';
		$tiempo     = $_POST["tiempo_ensaye$i"] ?? '';
		$falla      = $_POST["falla$i"] ?? '';
		$flexo      = $_POST["flexometro$i"] ?? '';
		$prensa     = $_POST["prensa$i"] ?? '';
		$disp       = $_POST["dispositivo_viga$i"] ?? '';
		$laina      = $_POST["laina$i"] ?? '';
		// echo "<script>alert('i=$i | id_viga=$id_viga | ensaye=$ensaye');</script>";

		$sql = "UPDATE vigas SET
                fecha = '$fecha',
                fecha_recepcion = '$fecha_recepcion',
                elemento = '$elemento',
                ubicacion = '$ubicacion',
                fc = '$mr',
                edad = '$edad',
                revenimientop = '$revenimientop',
                revenimientor = '$revenimientor',
                agregado = '$tma',
                concretera = '$concretera',
                temperatura = '$temperatura',
                remision = '$remision',
                volumen = '$volumen',
                hora_desmoldeo = '$hora_desmoldeo',
                personal_muestreo = '$muestreo',
                personal_recibio = '$recibio',
                observaciones = '$observacion',
				ensaye$i = '$ensaye',
                edad$i = '$edad',
                diametro$i = '$diametro',
                diametroo$i = '$diametroo',
                altura$i = '$altura',
                alturaa$i = '$alturaa',
                L_vigas$i = '$L_vigas',
                carga$i = '$carga',
                a_vigas$i = '$a_vigas',
                resistencia$i = '$resistencia',
                fc$i = '$fc',
                tiempo_ensaye$i = '$tiempo',
                falla$i = '$falla',
                flexometro$i = '$flexo',
                prensa$i = '$prensa',
                dispositivo_viga$i = '$disp',
                laina$i = '$laina'
            WHERE id = '$id_viga'
        ";
		// echo "<script>alert('$mr');</script>";

		$conexion->query($sql);
	}
	echo "<script>
        alert('Ensaye de viga actualizado correctamente');
        window.close();
    </script>";
	exit;

	// echo "<script>alert('Datos guardados correctamente');</script>";
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
			Captura de vigas <small></small>
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
						<input type="number" class="form-control" name="fc" value="<?= $mr ?>">
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
					<!-- <a href="#" data-toggle="card-collapse" class="btn"><iconify-icon icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
					<a href="#" data-toggle="card-expand" class="btn"><iconify-icon icon="material-symbols-light:fullscreen"></iconify-icon></a>
					<a href="#" data-toggle="card-remove" class="btn"><iconify-icon icon="material-symbols-light:close-rounded"></iconify-icon></a> -->
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
  

    <td><input type='text' class='form-control' name='id<?= $i ?>' value='$id' readonly></td>
    <td><input type='date' class='form-control' name='ensaye$i' value='$ensaye'></td>
    <td><input type='text' class='form-control' name='edad$i' value='$edad'></td>

    <td><input type='text' class='form-control' name='diametro$i' value='$diametro'></td>
    <td><input type='text' class='form-control' name='diametroo$i' value='$diametroo'></td>

    <td><input type='text' class='form-control' name='altura$i' value='$altura'></td>
    <td><input type='text' class='form-control' name='alturaa$i' value='$alturaa'></td>

    <td><input type='text' class='form-control' name='L_vigas$i' value='$L_vigas'></td>
    <td><input type='text' class='form-control' name='carga$i' value='$carga'></td>
    <td><input type='text' class='form-control' name='a_vigas$i' value='$a_vigas'></td>

    <td><input type='text' class='form-control' name='resistencia$i' value='$resistencia' readonly></td>
    <td><input type='text' class='form-control' name='fc$i' value='$fc' readonly></td>

    <td><input type='text' class='form-control' name='tiempo_ensaye$i' value='$tiempo'></td>
    <td><input type='text' class='form-control' name='tiempo_minimo$i' readonly></td>
    <td><input type='text' class='form-control' name='velocidad$i' readonly></td>
    <td><input type='text' class='form-control' name='cumple$i' readonly></td>


    <td><input type='text' class='form-control' name='falla$i' value='$falla' readonly></td>
    <td><input type='text' class='form-control' name='flexometro$i' value='$flexo'></td>
    <td><input type='text' class='form-control' name='prensa$i' value='$prensa'></td>

    <td><input type='text' class='form-control' name='dispositivo_viga$i' value='$disp'></td>
    <td><input type='text' class='form-control' name='laina$i' value='$laina'></td>
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

		const fechaBaseInput = document.querySelector("input[name='fecha']");
		if (!fechaBaseInput || !fechaBaseInput.value) return;

		// Fecha base (muestreo)
		const partes = fechaBaseInput.value.split("-");
		const fechaBase = new Date(
			parseInt(partes[0]),
			parseInt(partes[1]) - 1,
			parseInt(partes[2])
		);

		for (let i = 1; i <= 3; i++) {

			const edadInput = document.querySelector(`input[name='edad${i}']`);
			const ensayeInput = document.querySelector(`input[name='ensaye${i}']`);

			if (!edadInput || !ensayeInput) continue;

			const edad = parseInt(edadInput.value);
			if (isNaN(edad)) continue;

			const nuevaFecha = new Date(fechaBase);
			nuevaFecha.setDate(nuevaFecha.getDate() + edad);

			const yyyy = nuevaFecha.getFullYear();
			const mm = String(nuevaFecha.getMonth() + 1).padStart(2, '0');
			const dd = String(nuevaFecha.getDate()).padStart(2, '0');

			ensayeInput.value = `${yyyy}-${mm}-${dd}`;
		}
	}

	function actualizarFechaDesdeEdad(edadInput) {

		const fila = edadInput.closest("tr");
		if (!fila) return;

		const fechaBaseInput = document.querySelector("input[name='fecha']");
		if (!fechaBaseInput || !fechaBaseInput.value) return;

		const edad = parseInt(edadInput.value);
		if (isNaN(edad)) return;

		// Obtener número de ensaye (1,2,3)
		const match = edadInput.name.match(/edad(\d+)/);
		if (!match) return;

		const i = match[1];

		const ensayeInput = fila.querySelector(`input[name='ensaye${i}']`);
		if (!ensayeInput) return;

		const [y, m, d] = fechaBaseInput.value.split("-");
		const fechaBase = new Date(y, m - 1, d);

		const nuevaFecha = new Date(fechaBase);
		nuevaFecha.setDate(nuevaFecha.getDate() + edad);

		const yyyy = nuevaFecha.getFullYear();
		const mm = String(nuevaFecha.getMonth() + 1).padStart(2, '0');
		const dd = String(nuevaFecha.getDate()).padStart(2, '0');

		ensayeInput.value = `${yyyy}-${mm}-${dd}`;
	}


	// === ACTUALIZAR CILINDROS SEGÚN LA EDAD DE MUESTREO ===
	// function actualizarVigas() {
	// 	let edad = document.querySelector("input[name='edad']").value;

	// 	let edades = document.querySelectorAll("input[name^='edad']");
	// 	let tolerancias = document.querySelectorAll("input[name^='tolerancia']");

	// 	if (edades.length < 4) return;

	// 	switch (edad) {
	// 		case "1":
	// 			edades[0].value = edades[1].value = edades[2].value = edades[3].value = 1;
	// 			tolerancias[0].value = tolerancias[1].value = tolerancias[2].value = tolerancias[3].value = 0.5;
	// 			break;

	// 		case "3":
	// 			edades[0].value = edades[1].value = edades[2].value = edades[3].value = 3;
	// 			tolerancias[0].value = tolerancias[1].value = tolerancias[2].value = tolerancias[3].value = 2;
	// 			break;

	// 		case "5":
	// 			edades[0].value = 1;
	// 			edades[1].value = 3;
	// 			edades[2].value = edades[3].value = 5;
	// 			tolerancias[0].value = 0.5;
	// 			tolerancias[1].value = 2;
	// 			tolerancias[2].value = tolerancias[3].value = 2;
	// 			break;

	// 		case "7":
	// 			edades[0].value = 3;
	// 			edades[1].value = 5;
	// 			edades[2].value = edades[3].value = 7;
	// 			tolerancias[0].value = 2;
	// 			tolerancias[1].value = 2;
	// 			tolerancias[2].value = tolerancias[3].value = 6;
	// 			break;

	// 		case "14":
	// 			edades[0].value = 5;
	// 			edades[1].value = 7;
	// 			edades[2].value = edades[3].value = 14;
	// 			tolerancias[0].value = 2;
	// 			tolerancias[1].value = 6;
	// 			tolerancias[2].value = tolerancias[3].value = 12;
	// 			break;

	// 		case "28":
	// 			edades[0].value = 7;
	// 			edades[1].value = 14;
	// 			edades[2].value = edades[3].value = 28;
	// 			tolerancias[0].value = 6;
	// 			tolerancias[1].value = 12;
	// 			tolerancias[2].value = tolerancias[3].value = 20;
	// 			break;
	// 	}

	// 	// Recalcular f'c
	// 	document.querySelectorAll(".fc_res").forEach(span => {
	// 		let itemID = span.id.replace("fc_res_", "");
	// 		calcularFC(itemID);
	// 	});

	// 	// 👈 Nueva línea: actualizar fechas automáticamente
	// 	actualizarFechasEnsaye();
	// }
	function actualizarEdadesVigas() {

		const edadMuestreo = parseInt(
			document.querySelector("input[name='edad']").value
		);

		if (!edadMuestreo) return;

		let edades = [];

		switch (edadMuestreo) {
			case 1:
				edades = [1, 1, 1];
				break;
			case 3:
				edades = [3, 3, 3];
				break;
			case 5:
				edades = [1, 3, 5];
				break;
			case 7:
				edades = [3, 5, 7];
				break;
			case 14:
				edades = [5, 7, 14];
				break;
			case 28:
				edades = [7, 14, 28];
				break;
			default:
				return;
		}

		for (let i = 1; i <= 3; i++) {
			let inputEdad = document.querySelector(`input[name='edad${i}']`);
			if (inputEdad) {
				inputEdad.value = edades[i - 1];
			}
		}

		// 🔁 Recalcular fechas de ensaye si ya tienes esa función
		actualizarFechasEnsaye();
	}

	document.addEventListener("input", function(e) {
		if (e.target.name && e.target.name.match(/^edad[1-3]$/)) {
			actualizarFechaDesdeEdad(e.target);
		}
	});

	function calcularMRDesdeFila(fila, i) {

		const get = name => {
			const input = fila.querySelector(`input[name='${name}${i}']`);
			return input ? parseFloat(input.value) || 0 : 0;
		};

		// Datos geométricos
		const altura1 = get("altura");
		const altura2 = get("alturaa");
		const lado1 = get("diametro");
		const lado2 = get("diametroo");

		const carga = get("carga");
		const L = get("L_vigas");
		const a = get("a_vigas");

		const fcDisenio =
			parseFloat(document.querySelector("input[name='fc']").value) || 0;

		if (!altura1 || !altura2 || !lado1 || !lado2 || !carga || !fcDisenio) return;

		// Promedios
		const alturaProm = (altura1 + altura2) / 2;
		const ladoProm = (lado1 + lado2) / 2;

		const area = alturaProm * ladoProm;
		if (area <= 0 || alturaProm <= 0) return;

		let resistencia;

		if (a === 0) {
			resistencia = (carga * L) / (area * alturaProm);
		} else {
			resistencia = (3 * carga * a) / (area * alturaProm);
		}

		resistencia = Math.round(resistencia * 100) / 100;

		const mrPorcentaje =
			Math.round((resistencia * 100 / fcDisenio) * 100) / 100;

		// Pintar resultados del MISMO ensaye
		fila.querySelector(`input[name='resistencia${i}']`).value = resistencia;
		fila.querySelector(`input[name='fc${i}']`).value = mrPorcentaje;
		fila.querySelector(`input[name='falla${i}']`).value = (a !== 0) ? "2" : "1";


		// ===============================
		// 🔥 TIEMPO MÍNIMO = MR × 6
		// ===============================
		const tiempoMinimo = Math.round(resistencia * 6 * 100) / 100;

		const tiempoMinInput =
			fila.querySelector(`input[name='tiempo_minimo${i}']`);

		if (tiempoMinInput) {
			tiempoMinInput.value = tiempoMinimo;
		}


		// ===============================
		// ⚡ VELOCIDAD DE APLICACIÓN
		// Vel = MR / tiempo * 60
		// ===============================
		const tiempoEnsayeInput =
			fila.querySelector(`input[name='tiempo_ensaye${i}']`);

		const velocidadInput =
			fila.querySelector(`input[name='velocidad${i}']`);

		const tiempoEnsaye =
			tiempoEnsayeInput ? parseFloat(tiempoEnsayeInput.value) : 0;

		if (velocidadInput && tiempoEnsaye > 0) {
			const velocidad = Math.round((resistencia / tiempoEnsaye) * 60 * 100) / 100;
			velocidadInput.value = velocidad;
		} else if (velocidadInput) {
			velocidadInput.value = "";
		}


		// ===============================
		// ✅ VALIDAR CUMPLE VELOCIDAD
		// ===============================
		const cumpleInput =
			fila.querySelector(`input[name='cumple${i}']`);

		if (cumpleInput && tiempoEnsaye > 0 && tiempoMinInput) {

			const tiempoMin = parseFloat(tiempoMinInput.value);

			if (!isNaN(tiempoMin)) {
				if (tiempoEnsaye > tiempoMin) {
					cumpleInput.value = "CUMPLE";
					cumpleInput.classList.remove("is-invalid");
					cumpleInput.classList.add("is-valid");
				} else {
					cumpleInput.value = "NO CUMPLE";
					cumpleInput.classList.remove("is-valid");
					cumpleInput.classList.add("is-invalid");
				}
			}
		} else if (cumpleInput) {
			cumpleInput.value = "";
			cumpleInput.classList.remove("is-valid", "is-invalid");
		}

	}

	document.addEventListener("input", function(e) {

		const fila = e.target.closest("tr");
		if (!fila) return;

		const match = e.target.name.match(
			/(altura|alturaa|diametro|diametroo|carga|L_vigas|a_vigas|tiempo_ensaye)(\d)/

		);

		if (!match) return;

		const i = match[2];
		calcularMRDesdeFila(fila, i);
	});
	document.querySelector("input[name='fc']").addEventListener("input", function() {

		const filas = document.querySelectorAll("tbody tr");

		filas.forEach(fila => {
			for (let i = 1; i <= 3; i++) {
				calcularMRDesdeFila(fila, i);
			}
		});

	});



	// Ejecutar cuando cambie la edad del muestreo
	document.querySelector("input[name='edad']").addEventListener("input", actualizarEdadesVigas);


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
	document.querySelector("input[name='edad']").addEventListener("input", actualizarEdadesVigas);
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


	// ===============================
	// 🔁 RECALCULAR TODO AL CARGAR
	// ===============================
	window.addEventListener("DOMContentLoaded", function() {

		const filas = document.querySelectorAll("tbody tr");

		filas.forEach(fila => {
			for (let i = 1; i <= 3; i++) {
				calcularMRDesdeFila(fila, i);
			}
		});
	});
</script>