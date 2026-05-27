<?php
require_once __DIR__ . '/auth.php';

include("cabeza.php");
include("conexion_estructural.php");

// Procesar el formulario si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_diseno = $_POST['codigo_diseno'] ?? '';
    $obra = $_POST['obra'] ?? '';
    $ancho_viga = $_POST['ancho_viga'] ?? '';
    $altura_viga = $_POST['altura_viga'] ?? '';
    $fc = $_POST['fc'] ?? '';
    $fy = $_POST['fy'] ?? '';
    $recubrimiento_inferior = $_POST['recubrimiento_inferior'] ?? '';
    $mu = $_POST['mu'] ?? '';

    // Insertar en la base de datos
    $sql = "INSERT INTO diseno_viga_simplemente_armada 
            (codigo_diseno, obra, ancho_viga, altura_viga, fc, fy, recubrimiento_inferior, mu, fecha_registro) 
            VALUES ('$codigo_diseno', '$obra', '$ancho_viga', '$altura_viga', '$fc', '$fy', '$recubrimiento_inferior', '$mu', NOW())";

    if ($conexion->query($sql) === TRUE) {
        $id_insertado = $conexion->insert_id;
        echo '<div class="alert alert-success" role="alert">Diseño guardado exitosamente. Redirigiendo...</div>';
        echo '<script>setTimeout(() => { window.location.href = "diseno_viga_simplemente_armada.php"; }, 2000);</script>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error al guardar: ' . $conexion->error . '</div>';
    }
}
?>

<!-- BEGIN #content -->
<div id="content" class="app-content">
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="diseno_viga_simplemente_armada.php">VIGAS SIMPLEMENTE ARMADAS</a></li>
		<li class="breadcrumb-item active">NUEVO DISEÑO</li>
	</ul>

	<h1 class="page-header">
		Nuevo Diseño de Viga Simplemente Armada
	</h1>

	<div class="row">
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					FORMULARIO DE DISEÑO
				</div>
				<div class="card-body">
					<form id="form-diseno-viga" method="post" action="">
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="codigo_diseno" class="form-label">Código de Diseño *</label>
								<select class="form-control" id="codigo_diseno" name="codigo_diseno" required>
									<option value="">-- Seleccione una opción --</option>
									<option value="NTC CDMX 2017">NTC CDMX 2017</option>
									<option value="NTC CDMX 2023">NTC CDMX 2023</option>
								</select>
							</div>
							<div class="col-md-6">
								<label for="obra" class="form-label">Obra</label>
								<input type="text" class="form-control" id="obra" name="obra">
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-6">
								<label for="ancho_viga" class="form-label">Ancho de la Viga (b) [cm] *</label>
								<input type="number" step="0.01" class="form-control" id="ancho_viga" name="ancho_viga" required oninput="dibujarViga()">
							</div>
							<div class="col-md-6">
								<label for="altura_viga" class="form-label">Altura de la Viga (h) [cm] *</label>
								<input type="number" step="0.01" class="form-control" id="altura_viga" name="altura_viga" required oninput="dibujarViga()">
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-6">
								<label for="fc" class="form-label">Resistencia del Concreto (f'c) [kgf/cm²] *</label>
								<input type="number" step="0.01" class="form-control" id="fc" name="fc" required>
							</div>
							<div class="col-md-6">
								<label for="fy" class="form-label">Resistencia del Acero (fy) [kgf/cm²] *</label>
								<input type="number" step="0.01" class="form-control" id="fy" name="fy" required>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-6">
								<label for="recubrimiento_inferior" class="form-label">Recubrimiento Inferior [cm] *</label>
								<input type="number" step="0.01" class="form-control" id="recubrimiento_inferior" name="recubrimiento_inferior" required oninput="dibujarViga()">
							</div>
							<div class="col-md-6">
								<label for="mu" class="form-label">Momento Último (Mu) [tonf*m] *</label>
								<input type="number" step="0.01" class="form-control" id="mu" name="mu" required>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<button type="submit" class="btn btn-primary">
									<i class="fas fa-save me-2"></i>Guardar Diseño
								</button>
								<a href="diseno_viga_simplemente_armada.php" class="btn btn-secondary">
									<i class="fas fa-times me-2"></i>Cancelar
								</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					VISUALIZACIÓN DE LA VIGA
				</div>
				<div class="card-body" style="background-color: #f5f5f5; text-align: center; overflow-x: auto;">
					<canvas id="canvasViga" width="550" height="450" style="border: 1px solid #ddd; background-color: white;"></canvas>
					<p class="small text-muted mt-2">Haz clic en las cotas para editarlas. El dibujo se actualiza automáticamente</p>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END #content -->

<script>
let cotasAreas = {}; // Almacenar áreas de cotas para detección de clics

function dibujarViga() {
	const canvas = document.getElementById('canvasViga');
	if (!canvas || !canvas.getContext) {
		console.error('Canvas no encontrado');
		return;
	}

	const ctx = canvas.getContext('2d');
	const ancho = parseFloat(document.getElementById('ancho_viga').value) || 0;
	const altura = parseFloat(document.getElementById('altura_viga').value) || 0;
	const recubrimiento = parseFloat(document.getElementById('recubrimiento_inferior').value) || 0;

	// Limpiar canvas
	ctx.fillStyle = '#f5f5f5';
	ctx.fillRect(0, 0, canvas.width, canvas.height);

	cotasAreas = {}; // Resetear áreas

	if (ancho <= 0 || altura <= 0) {
		ctx.fillStyle = '#999';
		ctx.font = '14px Arial';
		ctx.textAlign = 'center';
		ctx.fillText('Ingrese ancho y altura para visualizar', canvas.width / 2, canvas.height / 2);
		return;
	}

	// Escala para que la viga quepa en el canvas
	const margen = 50;
	const escalaX = (canvas.width - 2 * margen) / ancho;
	const escalaY = (canvas.height - 2 * margen - 60) / altura;
	const escala = Math.min(escalaX, escalaY);

	const x0 = (canvas.width - ancho * escala) / 2;
	const y0 = (canvas.height - altura * escala) / 2;

	// Dibujar la viga (rectángulo principal)
	ctx.fillStyle = '#e8f4f8';
	ctx.strokeStyle = '#333';
	ctx.lineWidth = 2;
	ctx.fillRect(x0, y0, ancho * escala, altura * escala);
	ctx.strokeRect(x0, y0, ancho * escala, altura * escala);

	// Dibujar acero de refuerzo inferior
	ctx.fillStyle = '#ff6b6b';
	const posAceroInferior = y0 + altura * escala - (recubrimiento * escala);
	ctx.beginPath();
	ctx.arc(x0 + 20, posAceroInferior, 5, 0, 2 * Math.PI);
	ctx.fill();
	ctx.beginPath();
	ctx.arc(x0 + ancho * escala - 20, posAceroInferior, 5, 0, 2 * Math.PI);
	ctx.fill();

	// Dibujar líneas de cota - Ancho (horizontal)
	ctx.strokeStyle = '#666';
	ctx.lineWidth = 1;
	ctx.setLineDash([5, 5]);
	
	// Línea de cota horizontal inferior
	const cotaY = y0 + altura * escala + 30;
	ctx.beginPath();
	ctx.moveTo(x0, y0 + altura * escala + 15);
	ctx.lineTo(x0, cotaY);
	ctx.stroke();
	
	ctx.beginPath();
	ctx.moveTo(x0 + ancho * escala, y0 + altura * escala + 15);
	ctx.lineTo(x0 + ancho * escala, cotaY);
	ctx.stroke();

	// Línea de cota horizontal
	ctx.beginPath();
	ctx.moveTo(x0, cotaY);
	ctx.lineTo(x0 + ancho * escala, cotaY);
	ctx.stroke();

	// Puntas de flecha horizontal
	ctx.setLineDash([]);
	ctx.beginPath();
	ctx.moveTo(x0, cotaY);
	ctx.lineTo(x0 - 5, cotaY - 3);
	ctx.lineTo(x0 - 5, cotaY + 3);
	ctx.closePath();
	ctx.fill();

	ctx.beginPath();
	ctx.moveTo(x0 + ancho * escala, cotaY);
	ctx.lineTo(x0 + ancho * escala + 5, cotaY - 3);
	ctx.lineTo(x0 + ancho * escala + 5, cotaY + 3);
	ctx.closePath();
	ctx.fill();

	// Texto de cota horizontal - CLICKEABLE
	const textAncho = 'b = ' + ancho + ' cm';
	const textAnchoX = (x0 + x0 + ancho * escala) / 2;
	const textAnchoY = cotaY + 20;
	
	ctx.fillStyle = '#333';
	ctx.font = 'bold 13px Arial';
	ctx.textAlign = 'center';
	ctx.fillText(textAncho, textAnchoX, textAnchoY);
	
	// Guardar área de cota de ancho
	const metricasAncho = ctx.measureText(textAncho);
	cotasAreas.ancho = {
		x: textAnchoX - metricasAncho.width / 2 - 5,
		y: textAnchoY - 12,
		width: metricasAncho.width + 10,
		height: 20,
		tipo: 'ancho'
	};

	// Línea de cota vertical
	const cotaX = x0 - 30;
	ctx.strokeStyle = '#666';
	ctx.lineWidth = 1;
	ctx.setLineDash([5, 5]);

	ctx.beginPath();
	ctx.moveTo(x0 - 15, y0);
	ctx.lineTo(cotaX, y0);
	ctx.stroke();

	ctx.beginPath();
	ctx.moveTo(x0 - 15, y0 + altura * escala);
	ctx.lineTo(cotaX, y0 + altura * escala);
	ctx.stroke();

	// Línea vertical de cota
	ctx.beginPath();
	ctx.moveTo(cotaX, y0);
	ctx.lineTo(cotaX, y0 + altura * escala);
	ctx.stroke();

	// Puntas de flecha vertical
	ctx.setLineDash([]);
	ctx.beginPath();
	ctx.moveTo(cotaX, y0);
	ctx.lineTo(cotaX - 3, y0 - 5);
	ctx.lineTo(cotaX + 3, y0 - 5);
	ctx.closePath();
	ctx.fill();

	ctx.beginPath();
	ctx.moveTo(cotaX, y0 + altura * escala);
	ctx.lineTo(cotaX - 3, y0 + altura * escala + 5);
	ctx.lineTo(cotaX + 3, y0 + altura * escala + 5);
	ctx.closePath();
	ctx.fill();

	// Texto de cota vertical - CLICKEABLE
	const textAltura = 'h = ' + altura + ' cm';
	const textAlturaX = cotaX - 25;
	const textAlturaY = (y0 + y0 + altura * escala) / 2;
	
	ctx.fillStyle = '#333';
	ctx.font = 'bold 13px Arial';
	ctx.textAlign = 'center';
	ctx.save();
	ctx.translate(textAlturaX, textAlturaY);
	ctx.rotate(-Math.PI / 2);
	ctx.fillText(textAltura, 0, 0);
	ctx.restore();

	// Guardar área de cota de altura
	cotasAreas.altura = {
		x: textAlturaX - 50,
		y: textAlturaY - 10,
		width: 100,
		height: 20,
		tipo: 'altura'
	};

	// Dibujar línea de recubrimiento
	if (recubrimiento > 0 && recubrimiento < altura) {
		ctx.setLineDash([3, 3]);
		ctx.strokeStyle = '#ff6b6b';
		ctx.lineWidth = 1;
		const lineRecub = y0 + altura * escala - (recubrimiento * escala);
		ctx.beginPath();
		ctx.moveTo(x0, lineRecub);
		ctx.lineTo(x0 + ancho * escala, lineRecub);
		ctx.stroke();

		// Etiqueta de recubrimiento - CLICKEABLE
		ctx.setLineDash([]);
		ctx.fillStyle = '#ff6b6b';
		ctx.font = '11px Arial';
		ctx.textAlign = 'left';
		const textRecub = 'r = ' + recubrimiento + ' cm';
		ctx.fillText(textRecub, x0 + ancho * escala + 5, lineRecub);

		// Guardar área de cota de recubrimiento
		const metricasRecub = ctx.measureText(textRecub);
		cotasAreas.recubrimiento = {
			x: x0 + ancho * escala + 5,
			y: lineRecub - 12,
			width: metricasRecub.width + 5,
			height: 15,
			tipo: 'recubrimiento'
		};
	}

	ctx.setLineDash([]);
}

function editarCota(tipo) {
	let prompt_text = '';
	let current_value = 0;
	let input_id = '';

	if (tipo === 'ancho') {
		prompt_text = 'Ingrese el nuevo ancho de la viga (b) en cm:';
		current_value = parseFloat(document.getElementById('ancho_viga').value);
		input_id = 'ancho_viga';
	} else if (tipo === 'altura') {
		prompt_text = 'Ingrese la nueva altura de la viga (h) en cm:';
		current_value = parseFloat(document.getElementById('altura_viga').value);
		input_id = 'altura_viga';
	} else if (tipo === 'recubrimiento') {
		prompt_text = 'Ingrese el nuevo recubrimiento inferior (r) en cm:';
		current_value = parseFloat(document.getElementById('recubrimiento_inferior').value);
		input_id = 'recubrimiento_inferior';
	}

	const nuevo_valor = prompt(prompt_text, current_value);
	
	if (nuevo_valor !== null && nuevo_valor !== '') {
		const valor = parseFloat(nuevo_valor);
		if (!isNaN(valor) && valor > 0) {
			document.getElementById(input_id).value = valor;
			dibujarViga();
		} else {
			alert('Por favor ingrese un valor numérico válido y mayor a 0');
		}
	}
}

function manejarClickCanvas(event) {
	const canvas = document.getElementById('canvasViga');
	const rect = canvas.getBoundingClientRect();
	const x = event.clientX - rect.left;
	const y = event.clientY - rect.top;

	// Verificar si el clic está en alguna cota
	for (let cota in cotasAreas) {
		const area = cotasAreas[cota];
		if (x >= area.x && x <= area.x + area.width && 
		    y >= area.y && y <= area.y + area.height) {
			editarCota(area.tipo);
			break;
		}
	}
}

// Dibujar viga inicial cuando carga la página
window.addEventListener('load', function() {
	dibujarViga();
	document.getElementById('canvasViga').addEventListener('click', manejarClickCanvas);
	document.getElementById('canvasViga').style.cursor = 'pointer';
});
</script>

<?php
include 'pie.php';
?>