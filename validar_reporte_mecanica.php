<?php
header('Content-Type: application/json');

/* FIREBASE CONFIG */
$firebaseURL = "https://registrocompactacioneroca-default-rtdb.firebaseio.com";
$auth = "64KDwSjgUkDpEMGcNryDylwJtGQX3XQsGbu4QxwI";

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
	$response = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	
	return $httpCode == 200;
}

/* PARAMETROS */
$usuario = $_GET['usuario'] ?? '';
$llave   = $_GET['llave'] ?? '';

if (empty($usuario) || empty($llave)) {
	echo json_encode(['success' => false, 'error' => 'Parámetros inválidos']);
	exit;
}

try {
	// Eliminar solo el reporte de Mecanicas/ReportesMecanicas
	$rutaReporte = "Mecanicas/ReportesMecanicas/$usuario/$llave";
	$resultadoReporte = firebaseDelete($rutaReporte);
	
	if ($resultadoReporte) {
		echo json_encode(['success' => true, 'message' => 'Reporte validado correctamente']);
	} else {
		echo json_encode(['success' => false, 'error' => 'Error al eliminar el reporte']);
	}
	
} catch (Exception $e) {
	echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
