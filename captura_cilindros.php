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
$elemento = "";
$ubicacion = "";
$fc = "";
$edad = "";

$id_reporte_concreto = $_POST['id_reporte_concreto']
	?? $_GET['id_reporte_concreto']
	?? null;


if (isset($_GET['expediente']) && isset($_GET['reporte'])) {

	$exp = $_GET['expediente'];
	$rep = $_GET['reporte'];
	$id_reporte_concreto = $_GET['id_reporte_concreto'];


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
	$sql = "SELECT * FROM reporte_concreto 
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
		$fecha       = $data['fecha'];
		$fecha_recepcion       = $data['fecha_recepcion'];
		$reporte       = $data['reporte'];
		$elemento      = $data['elemento'];
		$ubicacion     = $data['ubicacion'];
		$fc            = $data['fc'];
		$edad          = $data['edad'];
		$revenimientop          = $data['revenimientop'];
		$revenimientor          = $data['revenimientor'];
		$hora_muestreo          = $data['hora_muestreo'];
		$concretera          = $data['concretera'];
		$temperatura          = $data['temperatura'];
		$remision          = $data['remision'];
		$volumen          = $data['volumen'];
		$muestreo          = $data['muestreo'];
		$recibio          = $data['recibio'];
		$observacion          = $data['observacion'];
		// $revisado_autorizado          = $data['revisado_autorizado'];
	}
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
		$hora_muestreo = $_POST['hora_muestreo'];
		$concretera = $_POST['concretera'];
		$temperatura = $_POST['temperatura'];
		$remision = $_POST['remision'];
		$volumen = $_POST['volumen'];
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
		hora_muestreo = '$hora_muestreo',
		concretera = '$concretera',
		temperatura = '$temperatura',
		remision = '$remision',
		volumen = '$volumen',
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
							<label class="form-label">Cliente <span class="text-danger">*</span></label>
							<input type="text" class="form-control"
								value="<?= $cliente ?>"
								placeholder="Nombre del cliente">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Id cliente <span class="text-danger">*</span></label>
							<input type="number" class="form-control"
								value="<?= $id_cliente ?>"
								placeholder="Id cliente">
							<!-- <div class="input-group">
								<label class="input-group-text" for="datepicker-component"><i class="fa fa-calendar"></i></label>
							</div> -->
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Obra <span class="text-danger">*</span></label>
							<input type="text" class="form-control"
								value="<?= $obra ?>"
								placeholder="Nombre de la obra">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Expediente <span class="text-danger">*</span></label>
							<input type="number" class="form-control"
								value="<?= $expediente ?>"
								placeholder="Numero de expediente">
						</div>
					</div>

					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Localización <span class="text-danger">*</span></label>
							<input type="text" class="form-control"
								value="<?= $localizacion ?>"
								placeholder="Localización">
						</div>
					</div>

					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Reporte <span class="text-danger">*</span></label>
							<input type="number" class="form-control"
								value="<?= $reporte ?>"
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

					<div class="row">

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

					</div>

					<div class="col-xl-2">
						<label class="form-label">Hora de muestreo *</label>
						<input type="time" class="form-control" name="hora_muestreo" value="<?= $hora_muestreo ?>">
					</div>

					<div class="col-xl-2">
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
						<label class="form-label">Muestreó especímenes *</label>
						<select class="form-control selectpicker" name="muestreo" data-live-search="true">
							<option value="<?= $muestreo ?>" selected><?= $muestreo ?></option>
							<?php foreach ($personalLista as $p): ?>
								<option value="<?= $p['Nombre'] ?>"><?= $p['Nombre'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="col-xl-6">
						<label class="form-label">Recibió especímenes *</label>
						<select class="form-control selectpicker" name="recibio" data-live-search="true">
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
			<div class="card-header with-btn">ENSAYE A LA COMPRESIÓN DE ESPECÍMENES CILÍNDRICOS DE CONCRETO
				<div class="card-header-btn">
					<a href="#" data-toggle="card-collapse" class="btn"><iconify-icon icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
					<a href="#" data-toggle="card-expand" class="btn"><iconify-icon icon="material-symbols-light:fullscreen"></iconify-icon></a>
					<a href="#" data-toggle="card-remove" class="btn"><iconify-icon icon="material-symbols-light:close-rounded"></iconify-icon></a>

				</div>
			</div>
			<div class="card-body">
				<p>
					<?php
					// 2️⃣ Hacer la consulta
					$sql = "SELECT * FROM item 
					WHERE id_reporte_concreto = '$id_reporte_concreto'  
					ORDER BY item ASC";
					$resultado = $conexion->query($sql);
					// $id_reporte_concreto = $_GET['id_reporte_concreto'];


					// 3️⃣ Verificar resultados
					if ($resultado->num_rows > 0) {
						// Convertir el resultado a un arreglo asociativo
						$clientes = $resultado->fetch_all(MYSQLI_ASSOC);


						echo '<div class="table-responsive">';
						echo '<table class="table table-bordered table-striped table-hover">';
						echo '<thead class="table-dark">
<tr>
    <th>Item</th>
    <th>Fecha ensaye</th>
    <th>Edad</th>
    <th>Tolerancia</th>
    <th>Ø1</th>
    <th>Ø2</th>
    <th>Altura 1</th>
    <th>Altura 2</th>
    <th>Condición</th>
    <th>Flexómetro</th>
    <th>Escuadra</th>
    <th>Compás</th>
    <th>Prensa</th>
    <th>Hora ensaye</th>
    <th>Carga</th>
    <th>Tiempo</th>

    <th>Falla</th>
    <th>Observaciones</th>
    <th>Ensayó</th>
    <th>Capturó</th>
    <th>f´c</th>
</tr>
</thead>';
						echo '<tbody>';

						foreach ($clientes as $fila) {

							$id = $fila['item'];

							echo "<tr>

    <td>
        <input type='hidden' name='item[]' value='$id'>
        $id
    </td>

    <td><input type='date' class='form-control' name='fecha_ensaye[$id]' value='{$fila['fecha_ensaye']}'></td>

    <td><input type='text' class='form-control' name='edad_item[$id]' value='{$fila['edad_item']}'></td>

    <td><input type='text' class='form-control' name='tolerancia[$id]' value='{$fila['tolerancia']}'></td>

    <td><input type='text' step='0.01' class='form-control diametro1' data-id='$id' name='diametro1[$id]' value='{$fila['diametro1']}'></td>


    <td><input type='text' step='0.01' class='form-control diametro2' data-id='$id' name='diametro2[$id]' value='{$fila['diametro2']}'></td>


    <td><input type='text' step='0.01' class='form-control' name='altura1[$id]' value='{$fila['altura1']}'></td>

    <td><input type='text' step='0.01' class='form-control' name='altura2[$id]' value='{$fila['altura2']}'></td>

    <td><input type='text' class='form-control' name='condicion_especimen[$id]' value='{$fila['condicion_especimen']}'></td>

    <td><input type='text' class='form-control' name='flexometro[$id]' value='{$fila['flexometro']}'></td>

    <td><input type='text' class='form-control' name='escuadra[$id]' value='{$fila['escuadra']}'></td>

    <td><input type='text' class='form-control' name='compas[$id]' value='{$fila['compas']}'></td>

    <td><input type='text' class='form-control' name='prensa[$id]' value='{$fila['prensa']}'></td>

    <td><input type='time' class='form-control' name='hora_ensaye[$id]' value='{$fila['hora_ensaye']}'></td>


    <td><input type='text' class='form-control carga' data-id='$id' name='carga[$id]' value='{$fila['carga']}'></td>


    <td><input type='text' class='form-control' name='tiempo_ensaye[$id]' value='{$fila['tiempo_ensaye']}'></td>

    <td><input type='text' class='form-control' name='falla[$id]' value='{$fila['falla']}'></td>

    <td><input type='text' class='form-control' name='observaciones[$id]' value='{$fila['observaciones']}'></td>

<td>
<select class='form-control selectpicker' name='persona_ensayo[$id]' data-live-search='true'>
    <option value='{$fila['persona_ensayo']}' selected>{$fila['persona_ensayo']}</option>";
							foreach ($personalLista as $p) {
								echo "<option value='{$p['Nombre']}'>{$p['Nombre']}</option>";
							}
							echo "</select>
</td>


<td>
<select class='form-control selectpicker' name='persona_capturo[$id]' data-live-search='true'>
    <option value='{$fila['persona_capturo']}' selected>{$fila['persona_capturo']}</option>";
							foreach ($personalLista as $p) {
								echo "<option value='{$p['Nombre']}'>{$p['Nombre']}</option>";
							}
							echo "</select>
</td>


<td>
    <span class='fc_res' id='fc_res_$id'>
        {$fila['fc_res']}
    </span>
</td>



    </tr>";
						}

						echo "</tbody></table></div>";
					} else {
						echo "No se encontraron clientes.";
					}

					// 5️⃣ Cerrar conexión
					$conexion->close();
					?>

				</p>
			</div>
		</div>


		<button type="submit" class="btn btn-outline-theme btn-sm w-180px">
			Guardar cambios
		</button>

		<a href='captura_cilindros.php' class='btn btn-outline-theme btn-sm w-180px'>
			Aceptar
		</a>

		<a href='captura_cilindros.php' class='btn btn-outline-theme btn-sm w-180px'>
			Cancelar
		</a>
</form> <!-- AQUÍ SE CIERRA EL FORMULARIO -->

</div>
</div>
<!-- END #content -->



<?php include("pie.php"); ?>

<script>
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
    let d_m = d_prom ;

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
</script>





