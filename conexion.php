<?php
// Configuración de la base de datos
// $servidor = "calidadroca.com";
// $usuario = "ali3d_holas";
// $contrasena = "holas";
// $nombre_bd = "ali3d_sistemas";

// Configuración de la base de datos
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$nombre_bd = "ali3d_sistemas";

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $contrasena, $nombre_bd);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Establecer charset para caracteres especiales
$conexion->set_charset("utf8");
?>