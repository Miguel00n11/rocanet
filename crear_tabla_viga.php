<?php
// Configuración de conexión
$servidor = "localhost";
$usuario = "root";
$contrasena = "";

// Conectar al servidor sin especificar base de datos
$conexion = new mysqli($servidor, $usuario, $contrasena);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Nombre de la nueva base de datos
$nombre_bd = "Analisis_estructural";

// Crear la base de datos si no existe
$sql_create_db = "CREATE DATABASE IF NOT EXISTS `$nombre_bd`";
if ($conexion->query($sql_create_db) === TRUE) {
    echo "✓ Base de datos '$nombre_bd' creada o ya existe.<br>";
} else {
    echo "✗ Error al crear la base de datos: " . $conexion->error . "<br>";
    $conexion->close();
    exit;
}

// Seleccionar la base de datos
$conexion->select_db($nombre_bd);

// SQL para crear la tabla
$sql = "CREATE TABLE IF NOT EXISTS diseno_viga_simplemente_armada (
    id_viga INT AUTO_INCREMENT PRIMARY KEY,
    codigo_diseno VARCHAR(100) NOT NULL,
    obra VARCHAR(255),
    ancho_viga DECIMAL(10, 2),
    altura_viga DECIMAL(10, 2),
    fc DECIMAL(10, 2),
    fy DECIMAL(10, 2),
    recubrimiento_inferior DECIMAL(10, 2),
    mu DECIMAL(10, 2),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conexion->query($sql) === TRUE) {
    echo "✓ Tabla 'diseno_viga_simplemente_armada' creada exitosamente en la base de datos '$nombre_bd'.";
} else {
    echo "✗ Error al crear la tabla: " . $conexion->error;
}

$conexion->close();
?>
