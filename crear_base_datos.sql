-- Crear base de datos ali3d_rocanet
CREATE DATABASE IF NOT EXISTS `ali3d_rocanet` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Usar la base de datos
USE `ali3d_rocanet`;

-- Crear tabla con la estructura especificada
CREATE TABLE IF NOT EXISTS `pavimento_rigido` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cliente` varchar(200) CHARACTER SET latin2 COLLATE latin2_general_ci DEFAULT NULL,
  `idcliente` varchar(20) CHARACTER SET armscii8 COLLATE armscii8_general_ci DEFAULT NULL,
  `obra` varchar(300) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `expediente` int(10) DEFAULT NULL,
  `ensaye` varchar(100) CHARACTER SET latin2 COLLATE latin2_general_ci DEFAULT NULL,
  `cantidad` varchar(10) CHARACTER SET armscii8 COLLATE armscii8_general_ci DEFAULT NULL,
  `pu` varchar(10) CHARACTER SET armscii8 COLLATE armscii8_general_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `observaciones` varchar(200) CHARACTER SET armscii8 COLLATE armscii8_general_ci DEFAULT NULL,
  `id_precio` int(11) DEFAULT NULL,
  `id_factura` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
