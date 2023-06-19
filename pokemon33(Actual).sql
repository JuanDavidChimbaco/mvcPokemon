-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para pokemon33
CREATE DATABASE IF NOT EXISTS `pokemon33` /*!40100 DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `pokemon33`;

-- Volcando estructura para tabla pokemon33.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombreCat` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- Volcando datos para la tabla pokemon33.categorias: ~20 rows (aproximadamente)
REPLACE INTO `categorias` (`id`, `nombreCat`) VALUES
	(1, 'normal'),
	(2, 'fighting'),
	(3, 'flying'),
	(4, 'poison'),
	(5, 'ground'),
	(6, 'rock'),
	(7, 'bug'),
	(8, 'ghost'),
	(9, 'steel'),
	(10, 'fire'),
	(11, 'water'),
	(12, 'grass'),
	(13, 'electric'),
	(14, 'psychic'),
	(15, 'ice'),
	(16, 'dragon'),
	(17, 'dark'),
	(18, 'fairy'),
	(19, 'unknown'),
	(20, 'shadow');

-- Volcando estructura para tabla pokemon33.compras
CREATE TABLE IF NOT EXISTS `compras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigoCom` int NOT NULL DEFAULT '0',
  `fechaCom` date NOT NULL,
  `estado` enum('A','I') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'A',
  `usuarioCreacion` int NOT NULL DEFAULT '0',
  `fechaCreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuarioModificacion` int NOT NULL DEFAULT '0',
  `fechaModificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigoCom` (`codigoCom`),
  KEY `FK_compras_usuarios` (`usuarioCreacion`),
  KEY `FK_compras_usuarios_2` (`usuarioModificacion`),
  CONSTRAINT `FK_compras_usuarios` FOREIGN KEY (`usuarioCreacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_compras_usuarios_2` FOREIGN KEY (`usuarioModificacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla pokemon33.compras: ~0 rows (aproximadamente)

-- Volcando estructura para tabla pokemon33.comprod
CREATE TABLE IF NOT EXISTS `comprod` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idCom` int NOT NULL DEFAULT '0',
  `idPro` int NOT NULL DEFAULT '0',
  `cantidadProCom` int NOT NULL DEFAULT '0',
  `estado` enum('A','I') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'A',
  `usuarioCreacion` int NOT NULL DEFAULT '0',
  `fechaCreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuarioModificacion` int NOT NULL DEFAULT '0',
  `fechaModificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_comprod_compras` (`idCom`),
  KEY `FK_comprod_productos` (`idPro`),
  KEY `FK_comprod_usuarios` (`usuarioCreacion`),
  KEY `FK_comprod_usuarios_2` (`usuarioModificacion`),
  CONSTRAINT `FK_comprod_compras` FOREIGN KEY (`idCom`) REFERENCES `compras` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_comprod_productos` FOREIGN KEY (`idPro`) REFERENCES `productos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_comprod_usuarios` FOREIGN KEY (`usuarioCreacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_comprod_usuarios_2` FOREIGN KEY (`usuarioModificacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla pokemon33.comprod: ~0 rows (aproximadamente)

-- Volcando estructura para tabla pokemon33.impuestos
CREATE TABLE IF NOT EXISTS `impuestos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombreImp` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '0',
  `porcentaje` int NOT NULL DEFAULT '0',
  `estado` enum('A','I') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'A',
  `usuarioCreacion` int NOT NULL DEFAULT '0',
  `fechaCreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuarioModificacion` int NOT NULL DEFAULT '0',
  `fechaModificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_impuestos_usuarios` (`usuarioCreacion`),
  KEY `FK_impuestos_usuarios_2` (`usuarioModificacion`),
  CONSTRAINT `FK_impuestos_usuarios` FOREIGN KEY (`usuarioCreacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_impuestos_usuarios_2` FOREIGN KEY (`usuarioModificacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla pokemon33.impuestos: ~3 rows (aproximadamente)
REPLACE INTO `impuestos` (`id`, `nombreImp`, `porcentaje`, `estado`, `usuarioCreacion`, `fechaCreacion`, `usuarioModificacion`, `fechaModificacion`) VALUES
	(4, 'Renta ', 30, 'A', 1, '2023-06-13 02:37:17', 1, '2023-06-15 04:48:06'),
	(8, 'Tributación de dividendos', 10, 'A', 1, '2023-06-13 23:59:01', 1, '2023-06-13 23:59:01'),
	(12, 'Iva', 35, 'A', 1, '2023-06-14 23:12:45', 1, '2023-06-14 23:12:45');

-- Volcando estructura para tabla pokemon33.pedidos
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigoPed` int NOT NULL DEFAULT '0',
  `fechaPed` date NOT NULL,
  `idUsu` int NOT NULL DEFAULT '0',
  `nombre` varchar(30) NOT NULL,
  `direccion` varchar(30) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `totalPed` int NOT NULL,
  `formaPago` enum('Transferencia','ContraEntrega','Consignacion') DEFAULT NULL,
  `fechaEnvPedido` date NOT NULL,
  `estadoPedido` enum('Pendiente','Enviado','Cancelado','Recibido') DEFAULT NULL,
  `estado` enum('A','I') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'A',
  `usuarioCreacion` int NOT NULL DEFAULT '0',
  `fechaCreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuarioModificacion` int NOT NULL DEFAULT '0',
  `fechaModificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigoPed` (`codigoPed`),
  KEY `idUsu` (`idUsu`),
  KEY `usuarioCreacion` (`usuarioCreacion`),
  KEY `usuarioModificacion` (`usuarioModificacion`),
  CONSTRAINT `FK_pedidos_usuarios` FOREIGN KEY (`idUsu`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_pedidos_usuarios_2` FOREIGN KEY (`usuarioCreacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_pedidos_usuarios_3` FOREIGN KEY (`usuarioModificacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla pokemon33.pedidos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla pokemon33.pedprod
CREATE TABLE IF NOT EXISTS `pedprod` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idPed` int DEFAULT NULL,
  `idPro` int DEFAULT NULL,
  `cantidadPro` int DEFAULT NULL,
  `estado` enum('A','I') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'A',
  `usuarioCreacion` int NOT NULL DEFAULT '0',
  `fechaCreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuarioModificacion` int NOT NULL DEFAULT '0',
  `fechaModificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idPed` (`idPed`),
  KEY `idPro` (`idPro`),
  KEY `usuarioCreacion` (`usuarioCreacion`),
  KEY `usuarioModificacion` (`usuarioModificacion`),
  CONSTRAINT `FK_pedprod_pedidos` FOREIGN KEY (`idPed`) REFERENCES `pedidos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_pedprod_productos` FOREIGN KEY (`idPro`) REFERENCES `productos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_pedprod_usuarios` FOREIGN KEY (`usuarioCreacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_pedprod_usuarios_2` FOREIGN KEY (`usuarioModificacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla pokemon33.pedprod: ~0 rows (aproximadamente)

-- Volcando estructura para tabla pokemon33.productos
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombrePro` varchar(30) NOT NULL DEFAULT '0',
  `precioPro` int NOT NULL DEFAULT '0',
  `cantidadPro` int NOT NULL DEFAULT '0',
  `categoria` int NOT NULL DEFAULT '0',
  `urlFoto` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `estado` enum('A','I') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'A',
  `usuarioCreacion` int NOT NULL,
  `fechaCreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuarioModificacion` int NOT NULL,
  `fechaModificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_productos_usuarios` (`usuarioCreacion`),
  KEY `FK_productos_usuarios_2` (`usuarioModificacion`),
  KEY `FK_productos_categorias` (`categoria`),
  CONSTRAINT `FK_productos_categorias` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_productos_usuarios` FOREIGN KEY (`usuarioCreacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_productos_usuarios_2` FOREIGN KEY (`usuarioModificacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla pokemon33.productos: ~19 rows (aproximadamente)
REPLACE INTO `productos` (`id`, `nombrePro`, `precioPro`, `cantidadPro`, `categoria`, `urlFoto`, `estado`, `usuarioCreacion`, `fechaCreacion`, `usuarioModificacion`, `fechaModificacion`) VALUES
	(12, 'bulbasaur', 6400, 7, 12, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/1.png', 'A', 1, '2023-06-15 02:55:25', 1, '2023-06-15 02:55:25'),
	(13, 'ivysaur', 14200, 4, 12, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/2.png', 'A', 1, '2023-06-15 02:57:25', 1, '2023-06-15 02:57:25'),
	(14, 'venusaur', 26300, 5, 12, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/3.png', 'A', 1, '2023-06-15 03:01:30', 1, '2023-06-15 03:01:30'),
	(15, 'charmander', 6200, 4, 10, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/4.png', 'A', 1, '2023-06-15 03:04:03', 1, '2023-06-15 03:04:03'),
	(16, 'charmeleon', 14200, 7, 10, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/5.png', 'A', 1, '2023-06-16 22:58:11', 1, '2023-06-16 22:58:11'),
	(17, 'charizard', 26700, 7, 16, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/6.png', 'A', 1, '2023-06-16 22:59:00', 1, '2023-06-16 22:59:00'),
	(18, 'squirtle', 6300, 5, 11, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/7.png', 'A', 1, '2023-06-16 22:59:49', 1, '2023-06-16 22:59:49'),
	(19, 'wartortle', 14200, 9, 11, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/8.png', 'A', 1, '2023-06-16 23:00:36', 1, '2023-06-16 23:00:36'),
	(20, 'blastoise', 26500, 6, 11, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/9.png', 'A', 1, '2023-06-16 23:01:10', 1, '2023-06-16 23:01:10'),
	(21, 'caterpie', 3900, 4, 7, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/10.png', 'A', 1, '2023-06-16 23:01:51', 1, '2023-06-16 23:01:51'),
	(24, 'pikachu', 11200, 45, 13, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/25.png', 'A', 1, '2023-06-17 00:34:50', 1, '2023-06-17 00:34:50'),
	(26, 'treecko', 6200, 2, 12, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/252.png', 'A', 1, '2023-06-17 22:27:32', 1, '2023-06-17 22:27:32'),
	(27, 'aron', 6600, 9, 9, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/304.png', 'A', 1, '2023-06-17 22:27:57', 1, '2023-06-17 22:27:57'),
	(28, 'ledyba', 5300, 6, 7, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/165.png', 'A', 1, '2023-06-17 22:28:04', 1, '2023-06-17 22:28:04'),
	(29, 'uxie', 29000, 9, 14, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/480.png', 'A', 1, '2023-06-17 22:28:15', 1, '2023-06-17 22:28:15'),
	(30, 'latias', 30000, 2, 16, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/380.png', 'A', 1, '2023-06-17 22:28:29', 1, '2023-06-17 22:28:29'),
	(31, 'pheromosa', 28500, 2, 7, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/795.png', 'A', 1, '2023-06-17 22:29:07', 1, '2023-06-17 22:29:07'),
	(32, 'araquanid', 15900, 5, 11, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/752.png', 'A', 1, '2023-06-17 22:30:43', 1, '2023-06-17 22:30:43'),
	(33, 'raboot', 14700, 8, 10, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/814.png', 'A', 1, '2023-06-17 22:35:20', 1, '2023-06-17 22:35:20');

-- Volcando estructura para tabla pokemon33.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombreRol` varchar(30) NOT NULL DEFAULT '0',
  `estado` enum('A','I') NOT NULL DEFAULT 'A',
  `usuarioCreacion` int DEFAULT NULL,
  `fechaCreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuarioModificacion` int DEFAULT NULL,
  `fechaModificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuarioCreacion` (`usuarioCreacion`),
  KEY `usuarioModificacion` (`usuarioModificacion`),
  CONSTRAINT `FK_roles_usuarios` FOREIGN KEY (`usuarioCreacion`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `FK_roles_usuarios_2` FOREIGN KEY (`usuarioModificacion`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla pokemon33.roles: ~3 rows (aproximadamente)
REPLACE INTO `roles` (`id`, `nombreRol`, `estado`, `usuarioCreacion`, `fechaCreacion`, `usuarioModificacion`, `fechaModificacion`) VALUES
	(1, 'Administrador', 'A', 1, '2023-06-15 03:29:19', 1, '2023-06-15 03:29:19'),
	(4, 'userTest1', 'A', 1, '2023-06-15 03:59:38', 1, '2023-06-15 09:30:26'),
	(5, 'userTest2', 'A', 1, '2023-06-15 04:30:17', 1, '2023-06-17 19:47:37');

-- Volcando estructura para tabla pokemon33.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipoDoc` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `identificacion` int NOT NULL,
  `nombre` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `apellido` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `correo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `pass` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `direccion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `telefono` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `genero` enum('M','F') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `idRol` int DEFAULT '0',
  `estado` enum('A','I') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'A',
  `usuarioCreacion` int DEFAULT NULL,
  `fechaCreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuarioModificacion` int DEFAULT NULL,
  `fechaModificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identificacion` (`identificacion`),
  KEY `FK_usuarios_roles` (`idRol`),
  KEY `FK_usuarios_usuarios` (`usuarioCreacion`),
  KEY `FK_usuarios_usuarios_2` (`usuarioModificacion`),
  CONSTRAINT `FK_usuarios_roles` FOREIGN KEY (`idRol`) REFERENCES `roles` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_usuarios_usuarios` FOREIGN KEY (`usuarioCreacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_usuarios_usuarios_2` FOREIGN KEY (`usuarioModificacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- Volcando datos para la tabla pokemon33.usuarios: ~1 rows (aproximadamente)
REPLACE INTO `usuarios` (`id`, `tipoDoc`, `identificacion`, `nombre`, `apellido`, `correo`, `pass`, `direccion`, `telefono`, `genero`, `idRol`, `estado`, `usuarioCreacion`, `fechaCreacion`, `usuarioModificacion`, `fechaModificacion`) VALUES
	(1, 'ID', 1000001, 'Admin', 'Admin', 'admin@mail.com', '1234', 'Calle 1', '123456789', 'M', 1, 'A', 1, '2023-06-15 03:33:06', 1, '2023-06-15 03:33:06'),
	(2, 'DNI', 1000002, 'Juan', 'Chimbaco', 'user@mail.com', '1234', 'Carrera 1', '987654321', 'M', 4, 'A', NULL, '2023-06-17 23:01:51', NULL, '2023-06-17 23:01:51');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
