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
  `nombreImp` varchar(30) NOT NULL DEFAULT '0',
  `porcentaje` int NOT NULL DEFAULT '0',
  `estado` enum('A','I') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'A',
  `usuarioCreacion` int NOT NULL DEFAULT '0',
  `fechaCreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuarioModificacion` int NOT NULL DEFAULT '0',
  `fechaModificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla pokemon33.impuestos: ~3 rows (aproximadamente)
INSERT INTO `impuestos` (`id`, `nombreImp`, `porcentaje`, `estado`, `usuarioCreacion`, `fechaCreacion`, `usuarioModificacion`, `fechaModificacion`) VALUES
	(3, 'Iva', 19, 'A', 6, '2023-06-13 02:01:31', 6, '2023-06-13 02:01:31'),
	(4, 'Renta', 35, 'A', 6, '2023-06-13 02:37:17', 6, '2023-06-13 02:37:17'),
	(5, 'Ganancia ocasional', 10, 'I', 6, '2023-06-13 02:39:28', 6, '2023-06-13 02:39:28');

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
  `descripPro` varchar(50) NOT NULL DEFAULT '0',
  `estado` enum('A','I') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'A',
  `usuarioCreacion` int NOT NULL,
  `fechaCreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuarioModificacion` int NOT NULL,
  `fechaModificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_productos_usuarios` (`usuarioCreacion`),
  KEY `FK_productos_usuarios_2` (`usuarioModificacion`),
  CONSTRAINT `FK_productos_usuarios` FOREIGN KEY (`usuarioCreacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_productos_usuarios_2` FOREIGN KEY (`usuarioModificacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla pokemon33.productos: ~3 rows (aproximadamente)
INSERT INTO `productos` (`id`, `nombrePro`, `precioPro`, `cantidadPro`, `descripPro`, `estado`, `usuarioCreacion`, `fechaCreacion`, `usuarioModificacion`, `fechaModificacion`) VALUES
	(4, 'Carro', 150000, 2, 'Bugati X2', 'A', 6, '2023-06-07 00:45:42', 6, '2023-06-07 00:45:42'),
	(5, 'PC', 500000, 1, 'HP Pavilon 480', 'A', 6, '2023-06-07 00:47:17', 6, '2023-06-07 00:47:17'),
	(10, 'TV', 1500000, 5, 'Smart Tv Samsung', 'A', 6, '2023-06-07 22:29:28', 6, '2023-06-07 22:29:28');

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
  CONSTRAINT `FK_roles_usuarios` FOREIGN KEY (`usuarioCreacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_roles_usuarios_2` FOREIGN KEY (`usuarioModificacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla pokemon33.roles: ~3 rows (aproximadamente)
INSERT INTO `roles` (`id`, `nombreRol`, `estado`, `usuarioCreacion`, `fechaCreacion`, `usuarioModificacion`, `fechaModificacion`) VALUES
	(33, 'Administrador', 'A', 6, '2023-06-06 22:21:20', 6, '2023-06-06 22:21:20'),
	(34, 'usuario', 'A', 6, '2023-06-06 22:21:30', 6, '2023-06-06 22:21:30'),
	(35, 'Chef', 'I', NULL, '2023-06-11 18:54:40', NULL, '2023-06-11 18:54:40');

-- Volcando estructura para tabla pokemon33.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipoDoc` varchar(50) NOT NULL DEFAULT '',
  `identificacion` int NOT NULL,
  `nombre` varchar(30) NOT NULL DEFAULT '',
  `apellido` varchar(30) NOT NULL DEFAULT '',
  `correo` varchar(50) NOT NULL DEFAULT '',
  `pass` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `direccion` varchar(50) NOT NULL DEFAULT '',
  `telefono` varchar(50) NOT NULL DEFAULT '',
  `genero` enum('M','F') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `idRol` int NOT NULL DEFAULT '0',
  `estado` enum('A','I') NOT NULL DEFAULT 'A',
  `usuarioCreacion` int DEFAULT NULL,
  `fechaCreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuarioModificacion` int DEFAULT NULL,
  `fechaModificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identificacion` (`identificacion`),
  KEY `idRol` (`idRol`),
  KEY `usuarioCreacion` (`usuarioCreacion`),
  KEY `usuarioModificacion` (`usuarioModificacion`),
  CONSTRAINT `FK2_usuarios_usuarios` FOREIGN KEY (`usuarioCreacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_usuarios_roles` FOREIGN KEY (`idRol`) REFERENCES `roles` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_usuarios_usuarios2` FOREIGN KEY (`usuarioModificacion`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla pokemon33.usuarios: ~3 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `tipoDoc`, `identificacion`, `nombre`, `apellido`, `correo`, `pass`, `direccion`, `telefono`, `genero`, `idRol`, `estado`, `usuarioCreacion`, `fechaCreacion`, `usuarioModificacion`, `fechaModificacion`) VALUES
	(6, 'CC', 1, 'David', 'Herrera', 'admin@mail.com', '1234', 'Carrera 9', '123456789', 'M', 33, 'A', 6, '2023-06-06 22:23:35', 6, '2023-06-06 22:23:35'),
	(7, 'CC', 2, 'jamy', 'jonson', 'user@mail.com', '1234', 'Av 45', '1234567', 'F', 34, 'A', NULL, '2023-06-11 17:10:57', NULL, '2023-06-11 17:10:57'),
	(17, 'CC', 3, 'james', 'jonson', 'user@mail.com', '1234', 'Calle 8', '3453454', 'M', 35, 'A', NULL, '2023-06-12 23:13:29', NULL, '2023-06-12 23:13:29');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
