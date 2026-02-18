<style>
	.wide-table th,
	.wide-table td {
		white-space: nowrap;
		min-width: 120px;
	}

	.wide-table th:first-child,
	.wide-table td:first-child {
		min-width: 60px;
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
$personalLista = [];
while ($row = $resPersonal->fetch_assoc()) {
	$personalLista[] = $row;
}

// Obtener lista de obras
$sqlObra = "SELECT * FROM obras JOIN clientes ON obras.cliente=clientes.idcliente ORDER BY id_expediente DESC";
$resObra = $conexion->query($sqlObra);
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

/* PARAMETROS */
$usuario = $_GET['usuario'] ?? '';
$llave   = $_GET['llave'] ?? '';
$tipo    = $_GET['tipo'] ?? 'Mecanicas'; // Mecanicas o Respaldo

// Determinar la ruta según el tipo
if ($tipo === 'Respaldo') {
	$ruta = "Mecanicas/RespaldoMecanicas/$usuario/$llave";
	$soloConsulta = true;
} else {
	$ruta = "Mecanicas/ReportesMecanicas/$usuario/$llave";
	$soloConsulta = false;
}

// Las imágenes siempre están en ImagenesMecanicas
$rutaImagenes = "ImagenesMecanicas/$usuario/$llave";

$reporte = firebaseGet($ruta);

if ($reporte === null) {
	die("NO SE ENCONTRÓ EL REPORTE<br>Ruta lógica: $ruta");
}

// Obtener las imágenes (siempre desde ImagenesMecanicas)
$imagenesData = firebaseGet($rutaImagenes);
$listaImagenes = [];

if ($imagenesData && is_array($imagenesData)) {
	foreach ($imagenesData as $key => $url) {
		if (is_string($url)) {
			$listaImagenes[] = $url;
		}
	}
}

/* VARIABLES DEL REPORTE */
$llaveReporte = $llave;
$atencion = $reporte['atencion'] ?? '';
$cliente = $reporte['cliente'] ?? '';
$id_cliente = $reporte['idcliente'] ?? '';
$obra = $reporte['obra'] ?? '';
$expediente = $reporte['expediente'] ?? '';
$localizacion = $reporte['localizacion'] ?? '';
$fecha = $reporte['fecha'] ?? '';
$hora = $reporte['hora'] ?? '';
$latitud = $reporte['latitud'] ?? '';
$longitud = $reporte['longitud'] ?? '';
$naf = $reporte['naf'] ?? false;
$numeroReporte = $reporte['numeroReporte'] ?? '';
$personal = $reporte['personal'] ?? '';
$profundidad_muestreo = $reporte['profundidad_muestreo'] ?? '';
$profundidad_naf = $reporte['profundidad_naf'] ?? '';
$sondeo_num = $reporte['sondeo_num'] ?? '';
$ubicacion = $reporte['ubicacion'] ?? '';
$listaEstratos = $reporte['listaEstratos'] ?? [];

// Convertir fecha de DD/MM/YYYY a YYYY-MM-DD para input type="date"
if (!empty($fecha) && preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $fecha, $matches)) {
	$fecha = $matches[3] . '-' . $matches[2] . '-' . $matches[1]; // YYYY-MM-DD
}
?>

<!-- BEGIN #content -->
<div id="content" class="app-content">
	<h1 class="page-header">Validar Mecánica de Suelos</h1>

	<form method="POST" action="">

		<div class="card">
			<div class="card-header with-btn">
				INFORMACIÓN GENERAL
				<div class="card-header-btn">
					<a href="#" data-toggle="card-collapse" class="btn"></a>
					<a href="#" data-toggle="card-expand" class="btn"></a>
				</div>
			</div>
			<div class="card-body pb-2">
				<div class="row">
					<div class="col-xl-12">
						<div class="mb-3">
							<label class="form-label">Llave del Reporte</label>
							<input type="text" class="form-control" name="llave" value="<?= $llaveReporte ?>" readonly>
						</div>
					</div>
					<div class="col-xl-12">
						<div class="mb-3">
							<label class="form-label">Cliente</label>
							<input type="text" class="form-control" name="cliente" value="<?= $cliente ?>">
						</div>
					</div>
					<div class="col-xl-12">
						<div class="mb-3">
							<label class="form-label">Obra</label>
							<input type="text" class="form-control" name="obra" value="<?= $obra ?>">
						</div>
					</div>
					<div class="col-xl-12">
						<div class="mb-3">
							<label class="form-label">Localización</label>
							<input type="text" class="form-control" name="localizacion" value="<?= $localizacion ?>">
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header with-btn">
				DATOS DEL SONDEO
				<div class="card-header-btn">
					<a href="#" data-toggle="card-collapse" class="btn"></a>
					<a href="#" data-toggle="card-expand" class="btn"></a>
				</div>
			</div>
			<div class="card-body pb-2">
				<div class="row">
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Número de Reporte</label>
							<input type="text" class="form-control" name="numeroReporte" value="<?= $numeroReporte ?>">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Fecha</label>
							<input type="date" class="form-control" name="fecha" value="<?= $fecha ?>">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Hora</label>
							<input type="time" class="form-control" name="hora" value="<?= $hora ?>">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Atención</label>
							<input type="text" class="form-control" name="atencion" value="<?= $atencion ?>">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Latitud</label>
							<input type="text" class="form-control" name="latitud" value="<?= $latitud ?>">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Longitud</label>
							<input type="text" class="form-control" name="longitud" value="<?= $longitud ?>">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Número de Sondeo</label>
							<input type="text" class="form-control" name="sondeo_num" value="<?= $sondeo_num ?>">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Ubicación</label>
							<input type="text" class="form-control" name="ubicacion" value="<?= $ubicacion ?>">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">NAF (Nivel de Agua Freática)</label>
							<select class="form-select" name="naf">
								<option value="1" <?= $naf ? 'selected' : '' ?>>Sí</option>
								<option value="0" <?= !$naf ? 'selected' : '' ?>>No</option>
							</select>
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Profundidad NAF</label>
							<input type="text" class="form-control" name="profundidad_naf" value="<?= $profundidad_naf ?>">
						</div>
					</div>
					<div class="col-xl-6">
						<div class="mb-3">
							<label class="form-label">Profundidad de Muestreo</label>
							<input type="text" class="form-control" name="profundidad_muestreo" value="<?= $profundidad_muestreo ?>">
						</div>
					</div>
					<div class="col-xl-6">
						<label class="form-label">Personal</label>
						<select class="form-select" name="personal" data-live-search="true">
							<option value="<?= $personal ?>" selected><?= $personal ?></option>
							<?php foreach ($personalLista as $p): ?>
								<option value="<?= $p['Nombre'] ?>"><?= $p['Nombre'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header with-btn">
				LISTA DE ESTRATOS
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
								<th>#</th>
								<th>Clasificación Visual</th>
								<th>Profundidad Inicio</th>
								<th>Profundidad Final</th>
								<th>Profundidad Muestreo</th>
								<th>Tipo Muestreo</th>
								<th>Observaciones</th>
							</tr>
						</thead>
						<tbody id="tbody-estratos">
							<!-- JS INSERTA AQUÍ LOS ESTRATOS -->
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header with-btn">
				IMÁGENES
				<div class="card-header-btn">
					<a href="#" data-toggle="card-collapse" class="btn"></a>
					<a href="#" data-toggle="card-expand" class="btn"></a>
				</div>
			</div>
			<div class="card-body">
				<div class="row" id="contenedor-imagenes">
					<!-- JS INSERTA AQUÍ LAS IMÁGENES -->
				</div>
			</div>
		</div>

		<button type="button" class="btn btn-success btn-sm w-180px" onclick="exportarExcel()">
			<i class="fa fa-file-excel me-1"></i> Exportar a Excel
		</button>
		
		<button type="button" class="btn btn-danger btn-sm w-180px ms-2" onclick="exportarPDF()">
			<i class="fa fa-file-pdf me-1"></i> Exportar a PDF
		</button>
		
		<?php if (!$soloConsulta): ?>
		<button type="button" class="btn btn-primary btn-sm w-180px ms-2" onclick="validarReporte()">
			<i class="fa fa-check me-1"></i> Validar
		</button>
		<?php endif; ?>

	</form>

</div>
<!-- END #content -->

<script>
	const listaEstratos = <?= json_encode($listaEstratos ?? [], JSON_UNESCAPED_UNICODE) ?>;
	const listaImagenes = <?= json_encode($listaImagenes ?? [], JSON_UNESCAPED_UNICODE) ?>;

	// Cargar estratos
	function cargarEstratos() {
		const tbody = document.getElementById("tbody-estratos");
		tbody.innerHTML = "";

		if (!listaEstratos.length) {
			tbody.innerHTML = `
				<tr>
					<td colspan="7" class="text-center text-muted">
						No hay estratos registrados
					</td>
				</tr>`;
			return;
		}

		listaEstratos.forEach((estrato, index) => {
			const fila = document.createElement("tr");
			fila.innerHTML = `
				<td class="text-center">${index + 1}</td>
				<td><input type="text" class="form-control" name="clasificacion_visual[]" value="${estrato.clasificacion_visual ?? ''}"></td>
				<td><input type="text" class="form-control" name="profundidad_inicio[]" value="${estrato.profundidad_inicio ?? ''}"></td>
				<td><input type="text" class="form-control" name="profundidad_final[]" value="${estrato.profundidad_final ?? ''}"></td>
				<td><input type="text" class="form-control" name="profundidad_muestreo_estrato[]" value="${estrato.profundidad_muestreo ?? ''}"></td>
				<td><input type="text" class="form-control" name="tipo_muestreo[]" value="${estrato.tipo_muestreo ?? ''}"></td>
				<td><input type="text" class="form-control" name="observaciones[]" value="${estrato.observaciones ?? ''}"></td>
			`;
			tbody.appendChild(fila);
		});
	}

	// Cargar imágenes
	function cargarImagenes() {
		const contenedor = document.getElementById("contenedor-imagenes");
		contenedor.innerHTML = "";

		if (!listaImagenes.length) {
			contenedor.innerHTML = `
				<div class="col-12 text-center text-muted">
					No hay imágenes registradas
				</div>`;
			return;
		}

		listaImagenes.forEach((imagen, index) => {
			const col = document.createElement("div");
			col.className = "col-md-4 col-sm-6 mb-3";
			col.innerHTML = `
				<div class="card">
					<img src="${imagen}" class="card-img-top" alt="Imagen ${index + 1}" style="max-height: 300px; object-fit: cover;">
					<div class="card-body text-center">
						<small>Imagen ${index + 1}</small>
					</div>
				</div>
			`;
			contenedor.appendChild(col);
		});
	}

	// Cargar automáticamente al abrir la página
	document.addEventListener("DOMContentLoaded", function() {
		cargarEstratos();
		cargarImagenes();
	});

	// Función para exportar a Excel
	function exportarExcel() {
		const usuario = "<?= $usuario ?>";
		const llave = "<?= $llave ?>";
		window.open(`exportar_mecanica_excel.php?usuario=${usuario}&llave=${llave}`, '_blank');
	}

	// Función para exportar a PDF
	function exportarPDF() {
		const usuario = "<?= $usuario ?>";
		const llave = "<?= $llave ?>";
		window.open(`exportar_mecanica_pdf.php?usuario=${usuario}&llave=${llave}`, '_blank');
	}

	// Función para validar reporte
	function validarReporte() {
		if (!confirm('¿Está seguro de validar este reporte? Se eliminará de la lista de pendientes.')) {
			return;
		}
		
		const usuario = "<?= $usuario ?>";
		const llave = "<?= $llave ?>";
		
		fetch(`validar_reporte_mecanica.php?usuario=${usuario}&llave=${llave}`)
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					alert('Reporte validado correctamente');
					window.close();
				} else {
					alert('Error al validar: ' + (data.error || 'Error desconocido'));
				}
			})
			.catch(error => {
				alert('Error de conexión: ' + error);
			});
	}

	// Función para validar reporte
	function validarReporte() {
		if (!confirm('¿Está seguro de validar este reporte? Se eliminará de la lista de pendientes.')) {
			return;
		}
		
		const usuario = "<?= $usuario ?>";
		const llave = "<?= $llave ?>";
		
		fetch(`validar_reporte_mecanica.php?usuario=${usuario}&llave=${llave}`)
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					alert('Reporte validado correctamente');
					window.close();
				} else {
					alert('Error al validar: ' + (data.error || 'Error desconocido'));
				}
			})
			.catch(error => {
				alert('Error de conexión: ' + error);
			});
	}

	// Autocompletar expediente
	document.getElementById("seleccionar_exp").addEventListener("change", function() {
		const expediente = this.value;
		if (!expediente) return;

		fetch(`ajax_get_expediente.php?expediente=${expediente}`)
			.then(r => r.json())
			.then(data => {
				if (data.error) {
					alert(data.error);
					return;
				}
				document.querySelector('[name="expediente"]').value = data.expediente;
				document.querySelector('[name="obra"]').value = data.obra;
				document.querySelector('[name="localizacion"]').value = data.localizacion;
				document.querySelector('[name="cliente"]').value = data.cliente;
				document.querySelector('[name="id_cliente"]').value = data.idcliente;
			});
	});
</script>

<?php include("pie.php"); ?>
