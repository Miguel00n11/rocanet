<?php
header('Content-Type: application/json');

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

function firebasePut($ruta, $datos)
{
	global $firebaseURL, $auth;
	$segmentos = explode('/', $ruta);
	$segmentos = array_map('rawurlencode', $segmentos);
	$rutaSegura = implode('/', $segmentos);
	$url = "$firebaseURL/$rutaSegura.json?auth=$auth";
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	$response = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	
	return $httpCode == 200;
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
	// 1. Leer el reporte actual
	$rutaReporte = "Mecanicas/ReportesMecanicas/$usuario/$llave";
	$reporte = firebaseGet($rutaReporte);
	
	if ($reporte === null) {
		echo json_encode(['success' => false, 'error' => 'Reporte no encontrado']);
		exit;
	}
	
	// 2. Guardar en ReporteActualizadoMecanicas
	$rutaActualizado = "Mecanicas/ReporteActualizadoMecanicas/$usuario/$llave";
	$resultadoGuardado = firebasePut($rutaActualizado, $reporte);
	
	if (!$resultadoGuardado) {
		echo json_encode(['success' => false, 'error' => 'Error al guardar el reporte actualizado']);
		exit;
	}
	
	// 3. Eliminar de ReportesMecanicas
	$resultadoEliminado = firebaseDelete($rutaReporte);
	
	if ($resultadoEliminado) {
		echo json_encode(['success' => true, 'message' => 'Reporte validado correctamente']);
	} else {
		echo json_encode(['success' => false, 'error' => 'Error al eliminar el reporte original']);
	}
	
} catch (Exception $e) {
	echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
