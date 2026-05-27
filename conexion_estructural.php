<?php
// Configuración de conexión
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$nombre_bd = "Analisis_estructural";

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $contrasena, $nombre_bd);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Establecer charset para caracteres especiales
$conexion->set_charset("utf8");
?>
