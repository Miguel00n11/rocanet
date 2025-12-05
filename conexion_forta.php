<?php
// Configuración de la base de datos
// $servidor = "calidadroca.com";
// $usuario = "ali3d_holas";
// $contrasena = "holas";
// $nombre_bd = "ali3d_sistemas";

// Configuración de la base de datos
$servidor = "fortastudio.com";
$usuario = "fortastudio_ali3d";
$contrasena = "rumores3d";
$nombre_bd = "fortastudio_roca";

// Crear conexión
$conexion_forta = new mysqli($servidor, $usuario, $contrasena, $nombre_bd);

// Verificar conexión
if ($conexion_forta->connect_error) {
    die("Error de conexión: " . $conexion_forta->connect_error);
}

// Establecer charset para caracteres especiales
$conexion_forta->set_charset("utf8");
?>