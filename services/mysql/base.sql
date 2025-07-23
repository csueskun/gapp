-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 13-04-2025 a las 20:56:43
-- Versión del servidor: 5.7.20
-- Versión de PHP: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "-05:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pedidos_gapp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consulta_licencia`
--

CREATE TABLE `consulta_licencia` (
  `id` int(11) NOT NULL,
  `fecha_consulta` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nombre_empresa` varchar(200) NOT NULL,
  `codigo_empresa` varchar(10) NOT NULL,
  `uuid` varchar(100) NOT NULL DEFAULT '''''',
  `empresa_id` int(11) DEFAULT NULL,
  `licencia_hasta` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hsoft`
--

CREATE TABLE `hsoft` (
  `id` int(11) NOT NULL,
  `variable` varchar(10) NOT NULL,
  `valor` varchar(254) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `hsoft`
--

INSERT INTO `hsoft` (`id`, `variable`, `valor`) VALUES
(1, 'dd', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `licencias`
--

CREATE TABLE `licencias` (
  `id` int(11) NOT NULL,
  `empresa` varchar(200) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `licencia_vence` tinyint(1) NOT NULL DEFAULT '1',
  `licencia_hasta` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uuid` varchar(200) NOT NULL DEFAULT '''''',
  `representante` varchar(200) NOT NULL DEFAULT '''''',
  `telefono` varchar(50) NOT NULL,
  `correo` varchar(80) NOT NULL,
  `tipo_documento` varchar(10) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `vinculado_desde` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_adicional`
--

CREATE TABLE `gapp_adicional` (
  `id` int(10) NOT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `descripcion` varchar(60) DEFAULT NULL,
  `valor` decimal(14,2) NOT NULL,
  `cantidad` decimal(7,2) DEFAULT '0.00',
  `producto_id` int(10) DEFAULT NULL,
  `tipo_producto_id` int(10) DEFAULT NULL,
  `ingrediente_id` int(10) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `tamano` varchar(10) NOT NULL,
  `images` varchar(20) NOT NULL DEFAULT '''adicional.png''',
  `grupo` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_combo`
--

CREATE TABLE `gapp_combo` (
  `id` int(11) NOT NULL,
  `cantidad` decimal(12,2) DEFAULT NULL,
  `precio` decimal(12,2) NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `nombre` varchar(200) DEFAULT NULL,
  `estado` int(1) DEFAULT '1',
  `imagen` varchar(45) NOT NULL DEFAULT 'producto.jpg'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_combo_producto`
--

CREATE TABLE `gapp_combo_producto` (
  `id` int(10) NOT NULL,
  `combo_id` int(10) NOT NULL,
  `producto_id` int(10) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `cantidad` decimal(7,2) DEFAULT NULL,
  `tamano` varchar(10) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_config`
--

CREATE TABLE `gapp_config` (
  `id` int(10) NOT NULL,
  `codigo` varchar(15) DEFAULT NULL,
  `descripcion` varchar(60) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `tabla` varchar(20) DEFAULT NULL,
  `valor` decimal(14,2) DEFAULT NULL,
  `valor_alf` varchar(30) DEFAULT NULL,
  `cantidad_mesas` smallint(4) DEFAULT NULL,
  `mesas` text,
  `impresora` varchar(45) DEFAULT NULL,
  `encabezado_comanda` varchar(200) DEFAULT NULL,
  `encabezado_pos` varchar(200) DEFAULT NULL,
  `impresora_comanda` varchar(45) DEFAULT NULL,
  `pie_pos` varchar(200) DEFAULT NULL,
  `num_impresora` tinyint(4) DEFAULT '32',
  `num_impresora_comanda` tinyint(4) DEFAULT '32',
  `servicio_impresion` varchar(80) DEFAULT NULL,
  `iva` decimal(14,2) DEFAULT '0.00',
  `impcon` decimal(14,2) DEFAULT '0.00',
  `valida_inventario` int(1) DEFAULT NULL,
  `turno` int(4) NOT NULL DEFAULT '1',
  `turno_limite` int(4) NOT NULL DEFAULT '99',
  `subtotales_factura` int(1) NOT NULL DEFAULT '1',
  `impresora2` varchar(10) NOT NULL,
  `num_impresora2` int(4) NOT NULL,
  `impresora3` varchar(45) DEFAULT NULL,
  `num_impresora3` tinyint(4) DEFAULT NULL,
  `propina` float(4,2) NOT NULL DEFAULT '0.00',
  `fvcodprefijo` varchar(4) DEFAULT '0',
  `pie_prefactura` varchar(200) DEFAULT '',
  `cajero_borra` int(1) NOT NULL,
  `mesero_borra` tinyint(4) NOT NULL,
  `dia_operativo` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `gapp_config`
--

INSERT INTO `gapp_config` (`id`, `codigo`, `descripcion`, `created_at`, `updated_at`, `tabla`, `valor`, `valor_alf`, `cantidad_mesas`, `mesas`, `impresora`, `encabezado_comanda`, `encabezado_pos`, `impresora_comanda`, `pie_pos`, `num_impresora`, `num_impresora_comanda`, `servicio_impresion`, `iva`, `impcon`, `valida_inventario`, `turno`, `turno_limite`, `subtotales_factura`, `impresora2`, `num_impresora2`, `impresora3`, `num_impresora3`, `propina`, `fvcodprefijo`, `pie_prefactura`, `cajero_borra`, `mesero_borra`, `dia_operativo`) VALUES
(5, NULL, NULL, '2017-01-30 16:33:25', '2025-04-13 18:15:40', NULL, NULL, NULL, 23, '[{\"mesa\":\"1\",\"disponible\":true},{\"mesa\":\"2\",\"disponible\":true},{\"mesa\":\"3\",\"disponible\":true},{\"mesa\":\"4\",\"disponible\":true},{\"mesa\":\"5\",\"disponible\":true},{\"mesa\":\"6\",\"disponible\":true},{\"mesa\":\"7\",\"disponible\":true},{\"mesa\":\"8\",\"disponible\":true},{\"mesa\":\"9\",\"disponible\":true},{\"mesa\":\"10\",\"disponible\":true},{\"mesa\":\"11\",\"disponible\":true},{\"mesa\":\"12\",\"disponible\":true},{\"mesa\":\"13\",\"disponible\":true},{\"mesa\":\"14\",\"disponible\":true},{\"mesa\":\"15\",\"disponible\":true},{\"mesa\":\"16\",\"disponible\":true},{\"mesa\":\"17\",\"disponible\":true},{\"mesa\":\"18\",\"disponible\":true},{\"mesa\":\"19\",\"disponible\":true},{\"mesa\":\"20\",\"disponible\":true},{\"mesa\":\"21\",\"disponible\":true},{\"mesa\":\"22\",\"disponible\":true},{\"mesa\":\"23\",\"disponible\":true}]', 'FACTURA', '===================\r\nCOCINA\r\n===================', 'pedidos Y BOCADOS J.R.\r\n\r\nTicket de venta', 'COCINA', 'FACEBOOK:Restaurante pedidos y \r\nINSTAGRAM:@pedidos', 32, 32, 'http://localhost/HSPrint', '0.00', '0.00', NULL, 3, 99, 0, '', 32, '', 32, 0.00, '00', '', 1, 0, '2025-04-13 12:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_detalle_documento`
--

CREATE TABLE `gapp_detalle_documento` (
  `documento_id` int(10) DEFAULT NULL,
  `producto_id` int(10) DEFAULT NULL,
  `ingrediente_id` int(11) DEFAULT NULL,
  `cantidad` decimal(14,2) DEFAULT NULL,
  `valor` decimal(14,2) DEFAULT NULL,
  `total` decimal(14,2) DEFAULT NULL,
  `detalle` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `id` int(10) NOT NULL,
  `impco` decimal(14,2) DEFAULT NULL,
  `iva` decimal(14,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_documento`
--

CREATE TABLE `gapp_documento` (
  `id` int(10) NOT NULL,
  `tipodoc` varchar(2) NOT NULL,
  `tipoie` varchar(1) DEFAULT NULL,
  `numdoc` varchar(10) NOT NULL,
  `mesa_id` int(10) NOT NULL,
  `pedido_id` int(10) DEFAULT NULL,
  `total` decimal(14,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `observacion` varchar(100) DEFAULT NULL,
  `codprefijo` varchar(4) DEFAULT '00',
  `atendido_por` int(11) DEFAULT NULL,
  `fecha_anulado` timestamp NULL DEFAULT NULL,
  `total_iva` decimal(14,2) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `tercero_id` int(10) DEFAULT NULL,
  `paga_efectivo` decimal(14,2) DEFAULT NULL,
  `paga_transferencia` decimal(10,0) DEFAULT NULL,
  `paga_debito` decimal(14,2) DEFAULT NULL,
  `paga_credito` decimal(14,2) DEFAULT NULL,
  `num_documento` varchar(14) DEFAULT NULL,
  `banco` int(11) DEFAULT NULL,
  `debe` int(1) DEFAULT NULL,
  `descuento` decimal(14,2) DEFAULT NULL,
  `iva` decimal(14,2) DEFAULT NULL,
  `impco` decimal(14,2) DEFAULT NULL,
  `tipopago` char(1) DEFAULT NULL,
  `caja_id` int(10) DEFAULT '1',
  `justificacion_anula` text,
  `paga_plataforma` decimal(10,0) DEFAULT NULL,
  `paga_puntos` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Estructura de tabla para la tabla `gapp_documento_formapago`
--

CREATE TABLE `gapp_documento_formapago` (
  `id` int(11) NOT NULL,
  `documento_id` int(11) NOT NULL,
  `formapago_id` int(11) NOT NULL,
  `valorpago` double(15,2) NOT NULL,
  `plazo` int(3) DEFAULT NULL,
  `fecha_vence` datetime DEFAULT NULL,
  `tipodoc` char(2) DEFAULT NULL,
  `nrodocumento` varchar(8) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_formapago`
--

CREATE TABLE `gapp_formapago` (
  `id` int(11) NOT NULL,
  `codigo` varchar(4) NOT NULL,
  `descripcion` varchar(40) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_ingrediente`
--

CREATE TABLE `gapp_ingrediente` (
  `id` int(10) NOT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `descripcion` varchar(60) NOT NULL,
  `unidad` varchar(5) DEFAULT 'grs',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `imagen` varchar(45) NOT NULL DEFAULT 'ingrediente.jpg',
  `visible` int(1) DEFAULT '1',
  `grupo` varchar(60) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `gapp_ingrediente`
--

INSERT INTO `gapp_ingrediente` (`id`, `codigo`, `descripcion`, `unidad`, `created_at`, `updated_at`, `imagen`, `visible`, `grupo`) VALUES
(1, NULL, 'SALCHICHA AHUMADA MAC POLLO', 'und', '2019-04-30 17:19:07', '2019-08-24 23:15:28', '1566706528.jpg', 1, ''),
(3, NULL, 'QUESO RAYADO', 'und', '2019-04-30 17:20:01', '2021-04-19 19:06:57', '1618877217.jpg', 1, ''),
(4, NULL, 'QUESO AMERICANO', 'und', '2019-04-30 17:20:23', '2019-11-29 09:43:04', '1563623045.png', 1, '2. QUESOS'),
(5, NULL, 'PAN BIMBO PERRO', 'und', '2019-04-30 17:22:41', '2019-05-02 20:04:34', '1556845474.png', 1, ''),
(6, NULL, 'PAN OREGANO', 'und', '2019-04-30 17:22:59', '2019-08-25 19:01:24', '1566777684.png', 1, ''),
(7, NULL, 'PAN TORTILLA', 'und', '2019-04-30 17:23:08', '2019-05-02 19:06:14', '1556736973.jpg', 1, ''),
(8, NULL, 'PAPA FOSFORITO', 'und', '2019-04-30 17:24:37', '2021-04-19 19:05:54', '1618877154.jpg', 1, ''),
(9, NULL, 'SALCHICHA AMERICANA', 'und', '2019-04-30 17:34:06', '2021-04-19 19:06:09', '1618877169.jpg', 1, ''),
(10, NULL, 'PECHUGA FILETE', 'und', '2019-04-30 17:44:39', '2025-01-22 20:54:59', '1566775421.jpg', 1, ''),
(12, NULL, 'TOCINETA', 'und', '2019-04-30 17:44:52', '2021-04-19 17:55:18', '1618872918.jpg', 1, 'EMBUTIDO'),
(14, NULL, 'CARNE TROZOS', 'und', '2019-04-30 17:45:21', '2025-01-24 17:12:36', '1618875736.jpg', 1, ''),
(15, NULL, 'CERDO TROZOS', 'und', '2019-04-30 17:45:27', '2019-09-27 06:46:22', '1569584782.png', 1, ''),
(16, NULL, 'PICO E GALLO', 'und', '2019-05-01 08:40:27', '2019-09-27 06:49:45', '1569584971.jpg', 1, 'VERDURAS'),
(17, NULL, 'CHIMICHURRI', 'und', '2019-05-01 08:40:55', '2019-08-25 18:40:34', '1566776434.jpg', 1, ''),
(19, NULL, 'QUESO CHEDDAR', 'und', '2019-05-01 08:41:14', '2021-04-19 17:51:32', '1618872692.jpg', 1, ''),
(20, NULL, 'SALCHICHA ALEMANA', 'und', '2019-05-01 08:42:04', '2019-08-24 23:15:56', '1566706556.jpg', 1, ''),
(21, NULL, 'CHORIZO CERDO', 'und', '2019-05-01 08:42:13', '2020-01-30 21:50:30', '1564197607.png', 1, 'EMBUTIDO'),
(23, NULL, 'CARNE MOLIDA', 'und', '2019-05-01 13:04:04', '2020-01-30 21:41:13', '1565557902.png', 1, 'PROTEINA'),
(24, NULL, 'PAN BRIOCHE', 'und', '2019-05-01 16:07:50', '2021-04-19 18:43:44', '1618875824.jpg', 1, ''),
(25, NULL, 'ESPINACA', 'und', '2019-05-01 16:08:09', '2019-07-26 21:52:45', '1564195965.png', 1, ''),
(28, NULL, 'PAPA FRANCESA', 'und', '2019-05-01 16:35:26', '2025-01-24 17:09:38', '1618875554.jpg', 1, 'COSEPAN'),
(29, NULL, 'PAN BIMBO CAMPESINO', 'und', '2019-05-02 19:11:36', '2019-08-25 19:02:30', '1566777750.png', 1, ''),
(30, NULL, 'CROQUETA DE CARNE', 'und', '2019-05-02 21:49:42', '2021-04-19 17:59:19', '1618872874.jpg', 1, 'PROTEINA'),
(31, NULL, 'SALSA MOSTAZA', 'und', '2019-05-04 19:48:59', '2021-04-19 17:28:04', '1618871284.jpg', 1, 'SALSAS'),
(32, NULL, 'SALSA PIÑA', 'und', '2019-05-04 19:49:34', '2021-04-19 19:05:05', '1618877105.jpg', 1, 'SALSAS'),
(33, NULL, 'SALSA TOMATE', 'und', '2019-05-04 19:50:07', '2021-04-19 17:28:25', '1618871305.jpg', 1, 'SALSAS'),
(34, NULL, 'SALSA TARTARA', 'und', '2019-05-04 19:50:39', '2021-04-19 19:07:29', '1618877249.jpg', 1, 'SALSAS'),
(35, NULL, 'POLLO DESMECHADO', 'und', '2019-05-10 21:54:02', '2021-04-18 19:56:27', '1566775391.png', 1, ''),
(37, NULL, 'VEGETALES', 'und', '2019-05-15 20:00:13', '2021-03-16 22:21:02', '1564198164.png', 1, ''),
(38, NULL, 'SALSAS', 'und', '2019-05-15 20:00:40', '2021-04-18 20:18:54', '1564198103.png', 1, 'SALSAS'),
(39, NULL, 'PARA LLEVAR', 'und', '2019-05-15 20:01:10', '2019-05-15 20:06:38', '1557968458.png', 1, ''),
(40, NULL, 'PAN OREGANO 30CM', 'und', '2019-05-16 17:23:54', '2019-08-25 19:01:55', '1566777709.png', 1, ''),
(41, NULL, 'DIVIDIDO EN DOS', 'und', '2019-05-23 19:22:36', '2020-06-09 22:20:19', '1591759197.png', 1, 'ETIQUETAS'),
(70, 'V001', 'PIMENTON', 'und', '2019-03-01 17:42:40', '2019-06-23 07:07:54', '1552845992.png', 1, ''),
(73, 'P002', 'POLLO', 'und', '2019-03-01 17:49:12', '2020-01-30 21:41:36', '1551480539.png', 1, 'PROTEINA'),
(76, 'P003', 'PESCADO', 'kg', '2019-03-01 17:50:29', '2019-03-17 13:40:52', '1551480614.png', 1, ''),
(78, 'I003', 'QUESO', 'gr', '2019-03-01 17:51:28', '2023-11-29 18:19:31', '1551480674.png', 1, '1. BASE'),
(79, 'I004', 'CHAMPIÑONES', 'kg', '2019-03-01 17:51:46', '2019-03-17 13:40:04', '1551480693.png', 1, ''),
(81, 'I005', 'HUEVO FRITO', 'und', '2019-03-01 17:52:40', '2021-04-19 18:39:45', '1618875576.jpg', 1, ''),
(82, 'P005', 'CHULETA', 'kg', '2019-03-01 17:53:04', '2019-03-17 13:02:29', '1552845740.png', 1, ''),
(83, 'V006', 'TOMATE', 'kg', '2019-03-01 17:53:33', '2021-04-19 19:05:37', '1618877137.jpg', 1, 'VEGETALES'),
(84, 'F002', 'PIÑA', 'gr', '2019-03-01 17:54:02', '2023-11-29 18:19:14', '1551480829.png', 1, ''),
(85, NULL, 'SALSA NAPOLITANA', 'kg', '2019-03-01 22:13:55', '2019-05-10 19:27:03', '1557534423.png', 1, ''),
(86, NULL, 'JAMON', 'und', '2019-03-01 22:14:13', '2021-04-19 18:50:04', '1618876204.jpg', 1, ''),
(87, NULL, 'SALCHICON', 'kg', '2019-03-01 22:19:05', '2019-03-17 13:07:01', '1552846021.png', 1, ''),
(88, NULL, 'CEVERONI', 'kg', '2019-03-01 22:19:33', '2019-08-25 18:26:57', '1566775617.jpg', 1, ''),
(89, NULL, 'CABANO', 'und', '2019-03-01 22:19:44', '2020-01-30 21:42:22', '1552845690.png', 1, 'EMBUTIDO'),
(90, NULL, 'SALAMI', 'und', '2019-03-01 22:23:06', '2019-08-25 18:26:20', '1566775580.jpg', 1, ''),
(94, NULL, 'OREGANO', 'kg', '2019-03-01 23:01:54', '2019-03-17 13:09:08', '1552846148.png', 1, ''),
(95, NULL, 'TOCINETA AHUMADA', 'und', '2019-03-01 23:05:28', '2020-01-30 21:49:42', '1552845782.png', 1, 'EMBUTIDO'),
(96, NULL, 'MAIZ', 'kg', '2019-03-01 23:05:34', '2019-03-01 23:05:34', '1552165779.png', 1, ''),
(98, NULL, 'QUESO MOZARELLA', 'kg', '2019-03-02 22:02:19', '2019-11-29 09:44:11', '1552792847.png', 1, '2. QUESOS'),
(99, NULL, 'MADURO', 'kg', '2019-03-02 22:02:59', '2021-04-19 18:43:24', '1618875803.jpg', 1, ''),
(100, NULL, 'TACOS', 'kg', '2019-03-02 22:03:37', '2019-06-15 18:33:58', '1560641638.png', 1, ''),
(102, NULL, 'LENGUA', 'kg', '2019-03-04 17:02:05', '2019-09-27 06:50:15', '1569585009.png', 1, 'PROTEINA'),
(103, NULL, 'ARROZ', 'kg', '2019-03-04 17:02:20', '2023-06-27 22:18:07', '1687922287.png', 1, ''),
(104, NULL, 'PATACON', 'kg', '2019-03-04 17:02:27', '2019-03-04 17:02:27', '1552258686.png', 1, ''),
(105, NULL, 'PAPA COCIDA', 'kg', '2019-03-04 17:02:38', '2025-01-24 21:55:45', '1552257997.png', 1, 'COSEPAN'),
(109, NULL, 'MAZORCA', 'kg', '2019-03-04 17:16:27', '2019-03-17 13:34:39', '1552847679.png', 1, ''),
(110, NULL, 'PLATANO', 'kg', '2019-03-04 17:17:03', '2020-06-09 22:28:32', '1591759697.jpg', 1, 'VERDURA'),
(112, NULL, 'LECHUGA', 'kg', '2019-03-11 21:28:44', '2021-04-19 18:50:24', '1618876224.jpg', 1, 'VEGETALES'),
(113, NULL, 'CERDO', 'kg', '2019-03-16 22:06:13', '2019-03-17 13:01:50', '1552845710.png', 1, ''),
(114, NULL, 'PAN HAMBURGUESA', 'und', '2019-03-16 22:07:01', '2019-08-25 19:00:10', '1566777607.png', 0, ''),
(116, NULL, 'SALCHICHA ZENU', 'und', '2019-03-16 22:10:26', '2021-04-19 19:06:24', '1618877184.jpg', 1, 'EMBUTIDO'),
(118, NULL, 'PAN PARA PERRO', 'und', '2019-04-09 19:28:53', '2019-08-25 18:58:59', '1566777537.png', 0, ''),
(119, NULL, 'TARTARA', 'gr', '2019-04-09 19:34:21', '2020-03-30 11:23:52', '1585585432.png', 1, ''),
(120, NULL, 'MOSTAZA', 'gr', '2019-04-09 19:34:51', '2019-09-27 07:00:46', '1569585646.png', 1, ''),
(122, NULL, 'HUEVO CODORNIZ', 'und', '2019-04-09 19:35:32', '2019-07-26 21:52:21', '1564195941.jpg', 1, ''),
(123, NULL, 'VASITO GASEOSA', 'und', '2019-04-09 19:39:41', '2019-07-26 22:30:23', '1564198223.jpg', 1, ''),
(124, NULL, 'ICOPOR', 'und', '2019-04-24 17:10:01', '2019-08-25 18:25:30', '1566775530.jpg', 1, ''),
(125, NULL, 'DOMICILIO', 'und', '2019-04-24 17:10:19', '2020-06-09 22:21:01', '1591759250.png', 1, 'SERVICIO'),
(127, NULL, 'PEPERONI', 'und', '2019-06-22 18:21:09', '2025-01-27 17:26:29', 'ingrediente.jpg', 0, ''),
(128, NULL, 'QUESO DOBLE  CREMA', 'gr', '2019-06-22 18:25:11', '2021-04-19 18:17:04', '1618872816.jpg', 1, '2. QUESOS'),
(129, NULL, 'QUESO PARMESANO', 'und', '2019-06-22 18:25:40', '2019-07-20 06:46:29', '1563623189.png', 1, ''),
(130, NULL, 'CEREZAS', 'und', '2019-06-22 18:31:51', '2019-09-27 06:46:50', '1569584810.png', 1, ''),
(132, NULL, 'DURAZNOS', 'und', '2019-06-22 18:32:06', '2019-11-29 09:45:58', '1569584841.png', 1, '5. FRUTA'),
(134, NULL, 'SALSA BECHAMEL', 'und', '2019-06-23 07:05:37', '2019-09-27 07:01:54', '1566777508.png', 1, 'SALSAS'),
(136, NULL, 'SALSA MIEL MOSTAZA', 'und', '2019-06-23 07:35:13', '2019-09-27 07:03:27', '1564197978.png', 1, 'SALSAS'),
(137, NULL, 'JALAPEÑO', 'und', '2019-06-23 15:35:07', '2019-07-26 22:19:04', '1564197544.png', 1, ''),
(139, NULL, 'ATUN', 'und', '2019-06-23 15:40:08', '2020-01-30 21:41:57', '1564195758.png', 1, 'PROTEINA'),
(141, NULL, 'PAN TAJADO', 'und', '2019-06-23 16:05:41', '2019-08-25 18:59:34', '1566777574.png', 1, ''),
(142, NULL, 'sopa', 'und', '2019-07-01 22:17:10', '2020-02-15 18:48:16', '1581810496.jpg', 1, ''),
(144, NULL, 'JAMON PIERNA', 'gr', '2019-07-05 18:39:19', '2019-09-27 06:59:44', '1569585580.png', 1, ''),
(145, NULL, 'MASA PIZZA', 'und', '2019-07-06 17:39:47', '2020-06-09 22:24:52', '1591759482.jpg', 1, 'BASE'),
(146, NULL, 'mojarra', 'und', '2019-07-10 08:59:23', '2019-07-26 22:20:29', '1564197629.png', 1, ''),
(147, NULL, 'ENSALADA', 'und', '2019-07-10 09:03:19', '2025-01-24 17:09:12', '1564197855.jpg', 1, 'VEGETALES'),
(148, NULL, 'CROTONES', 'und', '2019-07-20 06:40:22', '2019-08-25 18:36:48', '1566776208.jpg', 1, ''),
(149, NULL, 'postre', 'und', '2019-07-26 22:44:20', '2019-07-26 22:44:20', 'ingrediente.jpg', 1, ''),
(150, NULL, 'reinapepiada', 'gr', '2019-07-27 16:52:30', '2019-11-11 22:06:19', '1573527979.jpg', 1, ''),
(151, NULL, 'carne mechada', 'gr', '2019-07-27 16:52:39', '2020-01-30 21:40:56', '1565557881.png', 1, 'PROTEINA'),
(152, NULL, 'CROQUETA POLLO', 'gr', '2019-07-27 16:52:47', '2021-03-29 19:12:48', '1566775465.png', 1, ''),
(155, NULL, 'SALMON', 'grs', '2019-08-25 18:37:23', '2019-08-25 18:37:23', 'ingrediente.jpg', 1, ''),
(156, NULL, 'HELADO CHOCOLATE', 'und', '2019-10-31 18:48:26', '2020-01-16 21:42:10', '1579228921.jpg', 1, ''),
(157, NULL, 'HELADO CHICLE', 'und', '2019-10-31 18:48:32', '2020-01-16 21:42:48', '1579228959.jpg', 1, ''),
(159, NULL, 'MIGA DE PAN', 'gr', '2019-11-12 12:45:46', '2019-11-12 12:46:59', 'ingrediente.jpg', 0, ''),
(160, NULL, 'CEBOLLA ', 'gr', '2019-11-12 12:46:10', '2021-04-19 16:39:03', '1580438729.png', 1, 'VEGETALES'),
(161, NULL, 'HELADO DURAZNO', 'und', '2019-11-12 20:05:48', '2020-01-16 22:06:41', '1579230393.jpg', 1, ''),
(162, NULL, 'PEPITAS DULCES', 'gr', '2019-11-19 18:27:21', '2019-11-19 18:27:21', 'ingrediente.jpg', 1, NULL),
(163, NULL, 'COCO RAYADO', 'gr', '2019-11-19 18:27:32', '2020-01-16 22:04:55', '1579230291.jpg', 1, ''),
(164, NULL, 'NUTELLA', 'gr', '2019-11-19 18:27:39', '2020-01-16 22:05:08', '1579230308.jpg', 1, '3 UNTA'),
(166, NULL, 'HELADO VAINILLA', 'gr', '2019-11-19 18:27:52', '2020-01-16 22:07:27', '1579230371.jpg', 1, 'HELADO'),
(167, NULL, 'HELADO FRESA', 'gr', '2019-11-19 18:28:00', '2020-01-16 22:07:04', '1579230326.jpg', 1, 'HELADO'),
(168, NULL, 'MANI TRITURADO', 'gr', '2019-11-19 18:28:15', '2019-11-19 18:30:15', 'ingrediente.jpg', 1, '2 RELLENO'),
(169, NULL, 'GALLETA OREO', 'gr', '2019-11-19 18:28:23', '2019-11-19 18:29:49', 'ingrediente.jpg', 1, 'TOPPINGS'),
(170, NULL, 'FRESA', 'gr', '2019-11-19 18:31:40', '2020-01-30 21:48:57', '1580438937.png', 1, '5. FRUTA'),
(172, NULL, 'MANGO ', 'grs', '2019-11-19 18:32:34', '2019-11-19 18:32:34', 'ingrediente.jpg', 1, '5 FRUTA'),
(173, NULL, 'TROPICAL', 'grs', '2019-11-19 18:33:15', '2019-11-19 18:33:15', 'ingrediente.jpg', 1, '7 SALSAS'),
(174, NULL, 'LECHE CONDENSADA', 'grs', '2019-11-19 18:33:35', '2019-11-19 18:33:35', 'ingrediente.jpg', 1, '7 SALSAS'),
(176, NULL, 'WAFLE CONO', 'und', '2019-11-26 18:23:50', '2020-03-30 11:50:51', '1585586986.png', 1, ''),
(177, NULL, 'WAFLE PLATO', 'und', '2019-11-26 18:24:00', '2020-03-30 11:50:18', '1585587008.jpg', 1, ''),
(178, NULL, 'CONO', 'und', '2020-01-16 21:30:19', '2020-01-16 22:08:03', '1579230481.jpg', 1, ''),
(179, NULL, 'PLATO', 'und', '2020-01-16 21:30:26', '2020-01-16 22:08:45', '1579230519.png', 1, ''),
(180, NULL, 'PRINCIPIO', 'gr', '2020-02-15 18:48:51', '2020-02-15 18:48:51', 'ingrediente.jpg', 1, NULL),
(181, NULL, 'COSTILLA AUMADA', 'kg', '2020-06-06 11:39:02', '2020-07-27 18:33:49', '1595892829.png', 1, 'PROTEINA'),
(182, NULL, 'RAIZ CHINA ', 'gr', '2020-06-06 11:40:23', '2020-06-09 23:02:25', '1591761736.PNG', 1, 'VEGETALES'),
(183, NULL, 'CEBOLLIN', 'gr', '2020-06-06 11:41:46', '2020-06-22 22:03:58', '1592881438.png', 1, 'VEGETALES'),
(184, NULL, 'LOMO DE CERDO', 'kg', '2020-06-06 11:43:53', '2020-06-26 18:13:08', '1593213188.jpg', 1, 'PROTEINA'),
(186, NULL, 'COSTILLA DE CERDO', 'gr', '2020-06-06 12:07:48', '2020-07-27 18:33:26', '1595892806.png', 1, 'PROTEINA'),
(187, NULL, 'CHICHARON', 'gr', '2020-06-06 12:46:42', '2020-06-22 22:04:29', '1592881469.png', 1, 'CARNES'),
(188, NULL, 'CHORIZO DE TERNERA ', 'gr', '2020-06-06 12:47:45', '2020-07-27 19:35:08', '1595896508.jpg', 1, 'EMBUTIDO'),
(189, NULL, 'SALCHICHA RANCHERA ', 'gr', '2020-06-06 13:22:51', '2020-06-09 22:26:34', '1591759564.jpg', 1, 'EMBUTIDO'),
(192, NULL, 'queso paisa', 'gr', '2020-06-06 15:59:03', '2020-06-09 22:27:21', '1591759632.png', 1, 'BASE'),
(194, NULL, 'longaniza', 'gr', '2020-06-06 16:00:51', '2020-06-09 22:23:03', '1591759370.jpg', 1, 'EMBUTIDO'),
(195, NULL, 'BEILYS', 'gr', '2020-08-13 21:16:58', '2020-08-13 21:26:34', '1597371990.jpg', 1, ''),
(196, NULL, 'SNICKER', 'gr', '2020-08-13 21:23:46', '2020-08-13 21:26:13', '1597371970.png', 1, ''),
(197, NULL, 'Socle', 'kg', '2021-03-07 03:04:30', '2021-03-07 03:04:30', 'ingrediente.jpg', 1, NULL),
(199, NULL, 'CON AGUA', 'gr', '2021-03-18 06:12:24', '2021-03-18 06:18:39', 'ingrediente.jpg', 1, '1 COMBO'),
(200, NULL, 'CON GASEOSA', 'gr', '2021-03-18 06:12:35', '2021-03-18 06:18:57', 'ingrediente.jpg', 1, '1 COMBO'),
(201, NULL, 'CON CERVEZA', 'gr', '2021-03-18 06:12:47', '2021-03-18 06:19:17', 'ingrediente.jpg', 1, '2 COMBO'),
(202, NULL, 'CON MR.TEA', 'gr', '2021-03-18 06:12:59', '2021-03-18 06:19:47', 'ingrediente.jpg', 1, '2 COMBO'),
(203, NULL, 'PORTOBELLO', 'gr', '2021-03-18 07:21:33', '2021-03-18 07:21:33', 'ingrediente.jpg', 1, NULL),
(204, NULL, 'AGUACATE', 'gr', '2021-03-18 10:41:31', '2021-04-18 22:15:03', '1618802095.png', 1, 'VEGETALES'),
(205, NULL, 'SALSA MAYONESA', 'gr', '2021-03-29 20:28:45', '2021-04-19 17:27:48', '1618871268.jpg', 1, 'SALSAS'),
(206, NULL, 'SALSA DE LA CASA', 'gr', '2021-04-15 17:30:10', '2021-04-18 22:19:09', '1618802349.png', 1, 'SALSAS'),
(207, NULL, 'SALSA BBQ', 'gr', '2021-04-15 18:06:00', '2021-04-18 22:19:57', '1618802397.png', 1, 'SALSAS'),
(208, NULL, 'GUACAMOLE', 'gr', '2021-04-15 18:34:53', '2021-04-19 18:42:38', '1618875756.jpg', 1, ''),
(209, NULL, 'CARNE FILETE', 'und', '2021-04-18 20:06:36', '2025-01-24 12:44:29', 'ingrediente.jpg', 1, ''),
(210, NULL, 'CEBOLLA CARAMELIZADA', 'gr', '2021-04-19 17:11:03', '2021-04-19 17:51:10', '1618872664.jpg', 1, ''),
(211, NULL, 'Fondue de queso con champiñones', 'gr', '2021-04-19 17:17:49', '2021-04-19 17:17:49', 'ingrediente.jpg', 1, NULL),
(212, NULL, 'TRIPLE QUESO', 'gr', '2021-04-19 17:30:36', '2021-04-19 17:30:36', 'ingrediente.jpg', 1, NULL),
(213, NULL, 'DOBLE FILETE DE POLLO', 'gr', '2021-04-19 17:35:12', '2021-04-19 17:35:12', 'ingrediente.jpg', 1, NULL),
(214, NULL, 'FILETE DE POLLO', 'gr', '2021-04-19 17:38:15', '2021-04-19 17:38:15', 'ingrediente.jpg', 1, NULL),
(215, NULL, 'DOBLE CROQUETA DE CARNE', 'gr', '2021-04-19 17:43:34', '2021-04-19 18:44:01', '1618875841.jpg', 1, ''),
(216, NULL, 'POLLO DESMECHADO BAÑADO EN LA SALSA DE LA CASA', 'gr', '2021-04-19 17:47:15', '2021-04-19 17:47:15', 'ingrediente.jpg', 1, NULL),
(217, NULL, 'CARNE DE HAMBURGUESA', 'gr', '2021-04-19 18:09:22', '2021-04-19 18:09:22', 'ingrediente.jpg', 1, NULL),
(218, NULL, 'CARNE EN VARA', 'gr', '2021-04-19 18:09:49', '2021-04-19 18:41:57', '1618875716.jpg', 1, ''),
(219, NULL, 'CHORIZO', 'gr', '2021-04-19 18:12:56', '2021-04-19 18:40:41', '1618875639.jpg', 1, ''),
(220, NULL, 'POLLO DESMECHADO EN SALSA', 'gr', '2021-04-19 18:19:21', '2021-04-19 18:19:21', 'ingrediente.jpg', 1, NULL),
(221, NULL, 'FONDUE DE QUESO CHEDDAR', 'gr', '2021-04-19 18:20:47', '2021-04-19 18:43:05', '1618875783.jpg', 1, ''),
(222, NULL, 'DOBLE CARNE EN VARA', 'gr', '2021-04-19 18:22:16', '2021-04-19 18:22:16', 'ingrediente.jpg', 1, NULL),
(223, NULL, 'QUESO FUNDIDO CON CHAMPIÑONES', 'gr', '2021-04-19 18:59:54', '2021-04-19 18:59:54', 'ingrediente.jpg', 1, NULL),
(224, NULL, 'POLLO TROZOS', 'gr', '2021-04-19 19:00:57', '2025-01-24 17:12:58', 'ingrediente.jpg', 1, ''),
(225, NULL, 'PAPAS FRITAS', 'gr', '2021-04-19 19:13:27', '2021-04-19 19:13:27', 'ingrediente.jpg', 1, NULL),
(226, NULL, 'CEBOLLA CARAMELIZADA', 'gr', '2021-04-21 16:38:48', '2021-04-21 16:38:48', 'ingrediente.jpg', 1, NULL),
(227, NULL, 'DOBLE QUESO CREMA', 'gr', '2021-04-21 17:21:47', '2021-04-21 17:21:47', 'ingrediente.jpg', 1, NULL),
(228, NULL, 'SALCHICHA', 'gr', '2021-04-21 17:53:18', '2021-04-21 17:53:18', 'ingrediente.jpg', 1, NULL),
(229, NULL, 'HIELO', 'gr', '2022-05-01 17:04:36', '2022-05-01 17:04:36', 'ingrediente.jpg', 1, NULL),
(230, NULL, 'NARANJA', 'ml', '2022-05-01 17:05:15', '2022-05-01 17:05:15', 'ingrediente.jpg', 1, NULL),
(231, NULL, 'AZUCAR', 'gr', '2022-05-01 17:05:29', '2022-05-01 17:05:29', 'ingrediente.jpg', 1, NULL),
(232, NULL, 'JARRA', 'und', '2022-05-01 17:07:35', '2022-05-01 17:07:35', 'ingrediente.jpg', 1, NULL),
(233, NULL, 'MASA PIZZA', 'gr', '2022-09-30 09:21:47', '2022-09-30 09:21:47', 'ingrediente.jpg', 1, NULL),
(234, NULL, 'RAPI YUCA', 'und', '2025-01-22 20:53:39', '2025-01-22 20:53:39', 'ingrediente.jpg', 1, NULL),
(235, NULL, 'YUCA', 'und', '2025-01-24 12:41:15', '2025-01-24 17:09:52', 'ingrediente.jpg', 1, 'COSEPAN'),
(236, NULL, 'PICHAQUE', 'und', '2025-01-24 12:41:59', '2025-01-24 17:08:52', 'ingrediente.jpg', 1, 'VEGETALES'),
(237, NULL, 'CHULETA AHUMADA', 'und', '2025-01-24 12:47:20', '2025-01-24 12:47:20', 'ingrediente.jpg', 1, NULL),
(238, NULL, 'CARNE CHURRASCO', 'und', '2025-01-24 12:49:06', '2025-01-24 12:49:06', 'ingrediente.jpg', 1, NULL),
(239, NULL, 'CARNE CHURRASQUITO', 'und', '2025-01-24 12:49:20', '2025-01-24 12:49:20', 'ingrediente.jpg', 1, NULL),
(240, NULL, 'CHINCHULLA', 'und', '2025-01-24 12:51:31', '2025-01-24 12:51:31', 'ingrediente.jpg', 1, NULL),
(241, NULL, 'SOPA SANCOCHO', 'und', '2025-01-24 12:53:35', '2025-01-24 12:53:35', 'ingrediente.jpg', 1, NULL),
(242, NULL, 'CACHAMA', 'und', '2025-01-24 12:59:12', '2025-01-24 12:59:12', 'ingrediente.jpg', 1, NULL),
(243, NULL, 'MORCILLA', 'und', '2025-01-24 13:28:42', '2025-01-24 13:28:42', 'ingrediente.jpg', 1, NULL),
(244, NULL, 'VEGETALES', 'gr', '2025-01-24 21:56:05', '2025-02-01 11:38:07', 'ingrediente.jpg', 1, 'VEGETALES'),
(245, NULL, 'AREPA ASADA', 'und', '2025-01-24 22:01:12', '2025-01-24 22:01:12', 'ingrediente.jpg', 1, NULL),
(246, NULL, 'TODO', 'und', '2025-01-27 19:57:52', '2025-01-27 19:57:52', 'ingrediente.jpg', 1, NULL),
(247, NULL, 'SIN VEGETALES', 'und', '2025-01-27 20:03:26', '2025-01-27 20:03:26', 'ingrediente.jpg', 1, NULL),
(248, NULL, 'VEGETALES NO', 'und', '2025-01-27 20:10:14', '2025-01-27 20:10:14', 'ingrediente.jpg', 1, NULL),
(249, NULL, 'MITAD', 'und', '2025-01-27 20:56:51', '2025-01-27 20:56:51', 'ingrediente.jpg', 1, NULL),
(250, NULL, 'CILANTRO', 'und', '2025-01-27 21:18:19', '2025-01-27 21:18:19', 'ingrediente.jpg', 1, NULL),
(251, NULL, 'AL VAPOR', 'und', '2025-02-02 10:28:01', '2025-02-02 10:28:01', 'ingrediente.jpg', 1, NULL),
(252, NULL, 'caldo', 'und', '2025-03-02 22:59:26', '2025-03-02 22:59:26', 'ingrediente.jpg', 1, NULL),
(253, NULL, 'bagre tajada', 'und', '2025-03-02 23:00:35', '2025-03-02 23:00:35', 'ingrediente.jpg', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_pedido`
--

CREATE TABLE `gapp_pedido` (
  `id` int(10) NOT NULL,
  `fecha` datetime NOT NULL,
  `mesa_id` int(4) NOT NULL,
  `total` decimal(14,2) DEFAULT NULL,
  `obs` text,
  `tipopedido` varchar(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `comanda` tinyint(4) DEFAULT '0',
  `programado` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `entregado` datetime DEFAULT NULL,
  `tercero_id` int(10) DEFAULT NULL,
  `banco` int(10) DEFAULT NULL,
  `num_documento` varchar(10) DEFAULT NULL,
  `paga_credito` decimal(10,0) DEFAULT NULL,
  `paga_debito` decimal(10,0) DEFAULT NULL,
  `paga_efectivo` decimal(10,0) DEFAULT NULL,
  `paga_transferencia` decimal(10,0) DEFAULT NULL,
  `propina` decimal(14,2) DEFAULT '0.00',
  `turno` int(3) DEFAULT NULL,
  `caja_id` int(10) DEFAULT '1',
  `paga_plataforma` decimal(10,0) DEFAULT NULL,
  `impreso` int(1) DEFAULT NULL,
  `prefacturado` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_producto`
--

CREATE TABLE `gapp_producto` (
  `id` int(10) NOT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `descripcion` varchar(100) NOT NULL,
  `detalle` varchar(500) DEFAULT NULL,
  `tipo_producto_id` int(10) NOT NULL,
  `observacion` varchar(500) DEFAULT NULL,
  `valor` decimal(14,2) NOT NULL,
  `impcomanda` varchar(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `imagen` varchar(45) NOT NULL DEFAULT 'producto.jpg',
  `estado` int(1) DEFAULT '1',
  `iva` decimal(4,2) DEFAULT NULL,
  `grupo` varchar(100) DEFAULT NULL,
  `bodega` int(4) DEFAULT NULL,
  `unidad` varchar(4) DEFAULT NULL,
  `marca` int(4) DEFAULT NULL,
  `terminado` int(1) DEFAULT '0',
  `compuesto` int(1) DEFAULT '0',
  `impco` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `gapp_producto`
--

INSERT INTO `gapp_producto` (`id`, `codigo`, `descripcion`, `detalle`, `tipo_producto_id`, `observacion`, `valor`, `impcomanda`, `created_at`, `updated_at`, `imagen`, `estado`, `iva`, `grupo`, `bodega`, `unidad`, `marca`, `terminado`, `compuesto`, `impco`) VALUES
(1, NULL, 'PECHUGA', 'PECHUGA - 250GR DE FILETE DE PECHUGA ', 1, NULL, '0.00', '0', '2025-01-22 20:54:37', '2025-02-01 13:44:48', '1738435488.jpg', 1, '0.00', 'Bandejas', NULL, NULL, NULL, 0, 10, '0.00'),
(2, NULL, 'CARNE ASADA', '200GRS DE CARNE', 1, NULL, '0.00', '1', '2025-01-24 12:43:38', '2025-02-01 13:45:09', '1738435509.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 9, '0.00'),
(3, NULL, 'CARNE OREADA', 'CARNE OREADA', 1, NULL, '0.00', '1', '2025-01-24 12:45:22', '2025-02-01 13:45:27', '1738435527.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 10, '0.00'),
(4, NULL, 'LOMO DE CERDO', '220 GRAMOS DE LOMO DE ECRDO', 1, NULL, '0.00', '1', '2025-01-24 12:46:12', '2025-02-02 10:30:56', '1738435707.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 10, '0.00'),
(5, NULL, 'CHULETA AHUMADA', '220 GR DE CHULETA AHUMADA', 1, NULL, '0.00', '1', '2025-01-24 12:47:29', '2025-02-02 10:31:13', '1738435575.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 10, '0.00'),
(6, NULL, 'CHULETA FRESCA', '220 CHULETA FRESCA', 1, NULL, '0.00', '1', '2025-01-24 12:48:11', '2025-02-02 10:31:44', '1738435596.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 10, '0.00'),
(7, NULL, 'CHURRASQUITO', '220 GR DE CHURRASCO', 1, NULL, '0.00', '1', '2025-01-24 12:49:28', '2025-02-02 10:31:56', '1738435617.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 10, '0.00'),
(8, NULL, 'CHURRASCO', '320 GR DE CHURRASCO', 1, NULL, '0.00', '1', '2025-01-24 12:49:50', '2025-02-02 10:32:12', '1738435639.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 10, '0.00'),
(9, NULL, 'CHINCHULLA', '220 GR CHINCHULLA', 1, NULL, '0.00', '1', '2025-01-24 12:51:50', '2025-02-02 10:32:31', '1738435672.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 10, '0.00'),
(10, NULL, 'SANCOCHO 1P', 'SANCOCHO 1P', 3, NULL, '0.00', '1', '2025-01-24 12:53:46', '2025-02-02 10:23:13', '1738437195.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 8, '0.00'),
(11, NULL, 'SANCOCHO  2P', 'SANCOCHO', 3, NULL, '0.00', '1', '2025-01-24 12:55:09', '2025-02-02 10:23:35', 'producto.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 9, '0.00'),
(12, NULL, 'CACHAMA FRITA', '600 GR APROX CACHAMA FRITA', 3, NULL, '0.00', '1', '2025-01-24 13:00:09', '2025-02-02 10:23:45', '1738437211.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 9, '0.00'),
(13, NULL, 'MOJARRA FRITA', '600 GR MOJARRA', 3, NULL, '0.00', '1', '2025-01-24 13:01:01', '2025-02-02 10:24:00', 'producto.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 9, '0.00'),
(14, NULL, 'POKER LITRO', 'POKER', 2, NULL, '0.00', '1', '2025-01-24 13:01:31', '2025-02-01 14:14:58', '1738437298.jpg', 1, '0.00', '', NULL, NULL, NULL, 1, 0, '0.00'),
(15, NULL, 'MILLER LITE 300ML', 'MILLER LITE 300ML', 2, NULL, '0.00', '1', '2025-01-24 13:01:58', '2025-01-27 21:52:20', 'producto.jpg', 3, '0.00', '', NULL, NULL, NULL, 1, 0, '0.00'),
(16, NULL, 'BUDWEISER LATA', 'BUDWEISER LATA', 2, NULL, '0.00', '1', '2025-01-24 13:02:26', '2025-02-01 14:15:14', '1738437314.jpg', 1, '0.00', '', NULL, NULL, NULL, 1, 0, '0.00'),
(17, NULL, 'ANDINA LATA', 'ANDINA LATA', 2, NULL, '0.00', '1', '2025-01-24 13:04:45', '2025-02-01 14:15:36', '1738437336.jpg', 1, '0.00', '', NULL, NULL, NULL, 1, 0, '0.00'),
(18, NULL, 'AGUILA LATA', 'AGUILA LATA', 2, NULL, '0.00', '1', '2025-01-24 13:05:16', '2025-02-01 14:15:57', '1738437357.jpg', 1, '0.00', '', NULL, NULL, NULL, 1, 0, '0.00'),
(19, NULL, 'CERVEZA SOL', 'CERVEZA SOL', 2, NULL, '0.00', '1', '2025-01-24 13:05:46', '2025-01-27 21:52:25', 'producto.jpg', 3, '0.00', '', NULL, NULL, NULL, 1, 0, '0.00'),
(20, NULL, 'COLA Y POLA', 'COLA Y POLA', 2, NULL, '0.00', '1', '2025-01-24 13:06:58', '2025-02-01 14:16:11', '1738437371.jpg', 1, '0.00', '', NULL, NULL, NULL, 1, 0, '0.00'),
(21, NULL, 'CORONA BOTELLA', 'CORONA BOTELLA', 2, NULL, '0.00', '1', '2025-01-24 13:07:26', '2025-02-01 14:16:25', '1738437385.jpg', 1, '0.00', '', NULL, NULL, NULL, 1, 0, '0.00'),
(22, NULL, 'HEINEKEN', 'HEINEKEN', 2, NULL, '0.00', '1', '2025-01-24 13:07:53', '2025-02-01 14:16:58', '1738437418.jpg', 1, '0.00', '', NULL, NULL, NULL, 1, 0, '0.00'),
(23, NULL, 'SPEED MAX', 'SPEED MAX', 2, NULL, '0.00', '1', '2025-01-24 13:08:16', '2025-02-01 14:21:58', '1738437718.png', 1, '0.00', '', NULL, NULL, NULL, 1, 0, '0.00'),
(24, NULL, 'SODA NO RET.', 'SODA NO RET.', 2, NULL, '0.00', '1', '2025-01-24 13:09:41', '2025-02-01 14:26:31', '1738437991.jpg', 1, '0.00', '', NULL, NULL, NULL, 1, 0, '0.00'),
(25, NULL, 'MR TEA 500', 'MR TE', 2, NULL, '0.00', '1', '2025-01-24 13:10:19', '2025-02-01 14:26:53', '1738438013.jpg', 1, '0.00', '', NULL, NULL, NULL, 1, 0, '0.00'),
(26, NULL, 'JUGO HIT PET 500', 'JUGO HIT PET 500', 2, NULL, '0.00', '1', '2025-01-24 13:11:00', '2025-02-01 14:27:46', '1738438066.jpg', 1, '0.00', '', NULL, NULL, NULL, 1, 0, '0.00'),
(27, NULL, 'POSTOBON LITRO', 'POSTOBON LITRO', 2, NULL, '0.00', '1', '2025-01-24 13:11:25', '2025-02-01 14:28:09', '1738438089.jpg', 1, '0.00', '', NULL, NULL, NULL, 1, 0, '0.00'),
(28, NULL, 'COCACOLA LITRO', 'COCACOLA LITRO', 2, NULL, '0.00', '1', '2025-01-24 13:11:56', '2025-02-01 14:28:29', '1738438109.jpg', 1, '0.00', '', NULL, NULL, NULL, 1, 0, '0.00'),
(29, NULL, 'CHORIZO DE CERDO', 'CHORIZO DE CERDO', 4, NULL, '0.00', '1', '2025-01-24 13:28:07', '2025-02-01 13:40:45', '1738435245.png', 1, '0.00', '', NULL, NULL, NULL, 0, 8, '0.00'),
(30, NULL, 'MORCILLA', 'MORCILLA', 4, NULL, '0.00', '1', '2025-01-24 13:29:01', '2025-02-01 13:41:11', '1738435271.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 8, '0.00'),
(31, NULL, 'PINCHO MIXTO', 'PINCHO MIXTO', 5, NULL, '0.00', '1', '2025-01-24 17:12:14', '2025-02-01 13:43:47', '1738435427.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 10, '0.00'),
(32, NULL, 'PINCHO CARNE', 'PINCHO CARNE', 5, NULL, '0.00', '1', '2025-01-24 17:14:26', '2025-02-01 13:44:00', '1738435440.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 10, '0.00'),
(33, NULL, 'PINCHO POLLO', 'PINCHO POLLO', 5, NULL, '0.00', '1', '2025-01-24 17:15:01', '2025-02-01 13:44:12', '1738435452.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 10, '0.00'),
(34, NULL, 'SALCHIPAPAS 1P ', 'SALCHIPAPA 1P', 7, NULL, '0.00', '1', '2025-01-24 17:18:37', '2025-02-01 13:52:00', '1738435920.jpg', 1, '0.00', 'SALCHIPAPAS', NULL, NULL, NULL, 0, 0, '0.00'),
(35, NULL, 'SALCHIPOLLO 2P', 'SALCHIPOLLO 2P', 7, NULL, '0.00', '1', '2025-01-24 17:18:53', '2025-02-01 13:52:27', '1738435947.jpg', 1, '0.00', 'SALCHIPAPAS', NULL, NULL, NULL, 0, 0, '0.00'),
(36, NULL, 'CHORIPAPA 1P', 'CHORIIPAPA 1P', 7, NULL, '0.00', '1', '2025-01-24 17:20:24', '2025-01-27 20:44:33', 'producto.jpg', 1, '0.00', 'SALCHIPAPAS', NULL, NULL, NULL, 0, 0, '0.00'),
(37, NULL, 'SALCHICARNE 2P', 'SALCHICARNE A2 P', 7, NULL, '0.00', '1', '2025-01-24 17:21:40', '2025-01-27 20:48:43', 'producto.jpg', 1, '0.00', 'SALCHIPAPAS', NULL, NULL, NULL, 0, 0, '0.00'),
(38, NULL, 'PICADA 1P', 'PICADA 1P', 6, NULL, '0.00', '1', '2025-01-24 17:25:42', '2025-02-01 13:50:49', '1738435849.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 10, '0.00'),
(39, NULL, 'PICADA 2P', 'PICADA 1P', 6, NULL, '0.00', '1', '2025-01-24 17:26:00', '2025-02-01 14:47:36', '1738439256.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 10, '0.00'),
(40, NULL, 'PARRILLADA', 'PARRILADA 1P', 6, NULL, '0.00', '1', '2025-01-24 17:26:32', '2025-02-01 13:49:47', '1738435787.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 10, '0.00'),
(41, NULL, 'ICOPOR PEQUEÑO', 'ICOPEQ', 11, NULL, '0.00', '1', '2025-01-24 22:29:48', '2025-04-13 17:02:58', '1744581778.jpg', 1, '0.00', 'ENTRADAS', NULL, NULL, NULL, 0, 0, '0.00'),
(42, NULL, 'ICOPOR GR', 'ICOGR', 11, NULL, '0.00', '1', '2025-01-24 22:30:11', '2025-04-13 17:03:21', '1744581801.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(43, NULL, 'Arep. mixta', 'Carne, Pollo , Salchicha, chorizo', 12, NULL, '0.00', '1', '2025-01-27 17:32:10', '2025-04-13 17:05:11', '1744581911.png', 1, '0.00', 'Arepas', NULL, NULL, NULL, 0, 0, '0.00'),
(44, NULL, 'Arep. Carne y aguacate', 'Carne con aguacate', 12, NULL, '0.00', '1', '2025-01-27 17:35:31', '2025-01-27 18:31:53', 'producto.jpg', 1, '0.00', 'AREPAS', NULL, NULL, NULL, 0, 0, '0.00'),
(45, NULL, 'Arep.pollo y queso', 'pollo, queso', 12, NULL, '0.00', '1', '2025-01-27 18:00:21', '2025-01-27 18:41:56', 'producto.jpg', 1, '0.00', 'AREPAS', NULL, NULL, NULL, 0, 0, '0.00'),
(46, NULL, 'Arep.Carne desm', 'carne desmechada', 12, NULL, '0.00', '1', '2025-01-27 18:03:36', '2025-01-27 18:42:27', 'producto.jpg', 1, '0.00', 'Arepas', NULL, NULL, NULL, 0, 0, '0.00'),
(47, NULL, 'Arepa Pollo', 'pollo', 12, NULL, '0.00', '1', '2025-01-27 18:06:36', '2025-01-27 18:22:10', 'producto.jpg', 1, '0.00', 'Arepas', NULL, NULL, NULL, 0, 0, '0.00'),
(48, NULL, 'Arepa de queso', 'queso', 12, NULL, '0.00', '1', '2025-01-27 18:10:02', '2025-01-27 18:10:02', 'producto.jpg', 1, '0.00', 'Arepas', NULL, NULL, NULL, 0, 0, '0.00'),
(49, NULL, 'Arep', 'pollo, queso', 12, NULL, '0.00', '1', '2025-01-27 18:24:28', '2025-01-27 18:48:59', 'producto.jpg', 3, '0.00', 'Arepas', NULL, NULL, NULL, 0, 0, '0.00'),
(50, NULL, 'Arep.carne y queso', 'Carne, queso', 12, NULL, '0.00', '1', '2025-01-27 18:25:55', '2025-01-27 18:41:02', 'producto.jpg', 1, '0.00', 'Arepas', NULL, NULL, NULL, 0, 0, '0.00'),
(51, NULL, 'Arep.Porc aguacate', 'Aguacate', 12, NULL, '0.00', '1', '2025-01-27 18:28:55', '2025-01-27 18:46:34', 'producto.jpg', 1, '0.00', 'AREPAS', NULL, NULL, NULL, 0, 0, '0.00'),
(52, NULL, 'POSTOBON P400ML', 'GASEOSA', 2, NULL, '0.00', '1', '2025-01-27 19:20:08', '2025-02-01 14:29:07', '1738438147.jpg', 1, '0.00', 'BEBIDAS', NULL, NULL, NULL, 0, 0, '0.00'),
(53, NULL, 'COCA-COLA P400ML', 'GASEOSA', 2, NULL, '0.00', '1', '2025-01-27 19:20:59', '2025-02-01 14:29:22', '1738438162.png', 1, '0.00', 'BEBIDAS', NULL, NULL, NULL, 0, 0, '0.00'),
(54, NULL, 'AGUA MANZ P600ML', 'AGUA SABORIZADA', 2, NULL, '0.00', '1', '2025-01-27 19:22:53', '2025-02-01 14:30:02', '1738438202.jpg', 1, '0.00', 'BEBIDAS', NULL, NULL, NULL, 0, 0, '0.00'),
(55, NULL, 'AGUA P 600ML', 'AGUA', 2, NULL, '0.00', '1', '2025-01-27 19:24:46', '2025-02-01 14:30:23', '1738438223.jpg', 1, '0.00', 'BEBIDAS', NULL, NULL, NULL, 0, 0, '0.00'),
(56, NULL, 'AGUA P300ML', 'AGUA PQ', 2, NULL, '0.00', '1', '2025-01-27 19:25:34', '2025-02-01 14:30:41', '1738438241.jpg', 1, '0.00', 'BEBIDAS', NULL, NULL, NULL, 0, 0, '0.00'),
(57, NULL, 'AGUA LITRO', 'AGUA LITRO', 2, NULL, '0.00', '1', '2025-01-27 19:26:38', '2025-02-01 14:31:01', '1738438261.png', 1, '0.00', 'BEBIDAS', NULL, NULL, NULL, 0, 0, '0.00'),
(58, NULL, 'JUGO HIT CAJITA', 'JUGO CARTON', 2, NULL, '0.00', '1', '2025-01-27 19:27:41', '2025-02-01 14:31:27', '1738438287.png', 1, '0.00', 'BEBIDA', NULL, NULL, NULL, 0, 0, '0.00'),
(59, NULL, 'COCA-COLA 2LT RETORNABLE', 'COCA-COLA', 2, NULL, '0.00', '1', '2025-01-27 19:29:33', '2025-02-01 14:31:47', '1738438307.jpg', 1, '0.00', 'BEBIDAS', NULL, NULL, NULL, 0, 0, '0.00'),
(60, NULL, 'COCA-COLA 1.5LT', 'COCA-COLA', 2, NULL, '0.00', '1', '2025-01-27 19:34:59', '2025-02-01 14:32:06', '1738438326.jpg', 1, '0.00', 'BEBIDAS', NULL, NULL, NULL, 0, 0, '0.00'),
(61, NULL, 'AGUA MANZ 1.5LT', 'AGUA MANZANA', 2, NULL, '0.00', '1', '2025-01-27 19:36:02', '2025-02-01 14:32:28', '1738438348.jpg', 1, '0.00', 'BEBIDAS', NULL, NULL, NULL, 0, 0, '0.00'),
(62, NULL, 'POSTOBON 1.5 LT', 'GASEOSA', 2, NULL, '0.00', '1', '2025-01-27 19:37:00', '2025-02-01 14:33:54', '1738438434.jpg', 1, '0.00', 'BEBIDAS', NULL, NULL, NULL, 0, 0, '0.00'),
(63, NULL, 'POSTOBON 2.5LT', 'GASEOSA FAMILIAR', 2, NULL, '0.00', '1', '2025-01-27 19:37:55', '2025-02-01 14:34:20', '1738438460.jpg', 1, '0.00', 'BEBIDAS', NULL, NULL, NULL, 0, 0, '0.00'),
(64, NULL, 'PONY LATA', 'PONY MALTA', 2, NULL, '0.00', '1', '2025-01-27 19:38:40', '2025-02-01 14:34:42', '1738438482.jpg', 1, '0.00', 'BEBIDAS', NULL, NULL, NULL, 0, 0, '0.00'),
(65, NULL, 'GATORADE', 'GATORADE', 2, NULL, '0.00', '1', '2025-01-27 19:39:41', '2025-02-01 14:34:59', '1738438499.jpg', 1, '0.00', 'BEBIDAS', NULL, NULL, NULL, 0, 0, '0.00'),
(66, NULL, 'JUGO HIT 1.5 LT', 'JUGO HIT', 2, NULL, '0.00', '1', '2025-01-27 19:40:39', '2025-02-01 14:35:16', '1738438516.jpg', 1, '0.00', 'BEBIDAS', NULL, NULL, NULL, 0, 0, '0.00'),
(67, NULL, 'MR TEA 1.5 LT', 'MR TEA FAMILIAR', 2, NULL, '0.00', '1', '2025-01-27 19:41:35', '2025-02-01 14:35:39', '1738438539.jpg', 1, '0.00', 'BEBIDAS', NULL, NULL, NULL, 0, 0, '0.00'),
(68, NULL, 'CHORIZO PF	', 'CHORIZO DE CERDO	', 4, NULL, '0.00', '1', '2025-01-27 19:47:39', '2025-02-01 13:41:30', '1738435290.jpg', 1, '0.00', 'ENTRADAS', NULL, NULL, NULL, 0, 3, '0.00'),
(69, NULL, 'SALCHI 1P SIN VEG', 'SIN CEB\n', 7, NULL, '0.00', '1', '2025-01-27 20:25:52', '2025-01-27 20:32:52', 'producto.jpg', 3, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(70, NULL, 'HAM MIXTA', 'HAM MIXTA', 10, NULL, '0.00', '1', '2025-01-27 21:08:27', '2025-04-13 17:00:42', '1744581642.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(71, NULL, 'HAM SENCILLA', 'HAM SENCILLA', 10, NULL, '0.00', '1', '2025-01-27 21:11:43', '2025-04-13 17:01:14', '1744581674.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(72, NULL, 'HAM DOBLE CARNE', 'HAM DOBLE CARNE', 10, NULL, '0.00', '1', '2025-01-27 21:14:15', '2025-04-13 17:02:00', '1744581720.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(73, NULL, 'BAGRE EN AGUA', 'BAGRE EN AGUA', 8, NULL, '0.00', '1', '2025-01-27 21:16:46', '2025-02-02 10:35:04', '1738436246.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 4, '0.00'),
(74, NULL, 'BAGRE EN LECHE', 'BAGRE EN LECHE', 8, NULL, '0.00', '1', '2025-01-27 21:19:52', '2025-02-02 10:35:13', '1738436269.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 4, '0.00'),
(75, NULL, ' CALDO HUEVO AGUA', ' CALDO HUEVO AGUA', 8, NULL, '0.00', '1', '2025-01-27 21:21:56', '2025-02-02 10:35:25', '1738436332.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 5, '0.00'),
(76, NULL, ' CALDO HUEVO LECHE', ' CALDO HUEVO LECHE', 8, NULL, '0.00', '1', '2025-01-27 21:22:38', '2025-02-02 10:35:39', '1738436366.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 4, '0.00'),
(77, NULL, ' CALDO COSTILLA', ' CALDO COSTILLA', 8, NULL, '0.00', '1', '2025-01-27 21:23:14', '2025-02-02 10:35:49', '1738436411.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 4, '0.00'),
(78, NULL, 'ADIC.SOPA', 'SOPA', 9, NULL, '0.00', '1', '2025-01-27 21:25:17', '2025-02-01 14:00:58', '1738436458.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(79, NULL, 'ADIC.PICHAQUE', 'PICHAQUE', 9, NULL, '0.00', '1', '2025-01-27 21:26:55', '2025-02-01 14:05:55', '1738436755.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(80, NULL, 'ADIC.AL VAPOR', 'AL VAPOR', 9, NULL, '0.00', '1', '2025-01-27 21:27:15', '2025-02-01 14:06:47', '1738436807.png', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(81, NULL, 'ADIC.PAPA COCIDA', 'PAPA COCIDA', 9, NULL, '0.00', '1', '2025-01-27 21:28:13', '2025-02-01 14:08:04', '1738436884.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(82, NULL, 'ADIC.YUCA', 'YUCA', 9, NULL, '0.00', '1', '2025-01-27 21:28:39', '2025-02-01 14:08:17', '1738436897.png', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(83, NULL, 'ADIC.PAPA FRANCESA', 'PAPA FRANCESA', 9, NULL, '0.00', '1', '2025-01-27 21:29:03', '2025-02-01 14:08:42', '1738436922.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(84, NULL, 'ADIC.ARROZ', 'AD. ARROZ', 9, NULL, '0.00', '1', '2025-01-27 21:29:37', '2025-02-01 14:09:04', '1738436944.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(85, NULL, 'ADIC.AREPA', 'AD. AREPA', 9, NULL, '0.00', '1', '2025-01-27 21:30:25', '2025-02-01 14:09:21', '1738436961.png', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(86, NULL, 'ADIC. CODORNIZ', 'ADIC. CODOR', 9, NULL, '0.00', '1', '2025-01-27 21:31:23', '2025-02-01 14:09:37', '1738436977.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(87, NULL, 'ADIC. CHORIZO', 'ADIC. CHORIZO', 9, NULL, '0.00', '1', '2025-01-27 21:31:46', '2025-02-01 14:09:56', '1738436996.png', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(88, NULL, 'ADIC HUEVO GALL', 'ADIC HUEVO GALL', 9, NULL, '0.00', '1', '2025-01-27 21:32:31', '2025-02-01 14:10:10', '1738437010.png', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(89, NULL, 'ADIC. GALLINA', 'ADIC. GALLINA', 9, NULL, '0.00', '1', '2025-01-27 21:32:59', '2025-02-01 14:10:26', '1738437026.jpg', 1, '0.00', 'ADICIONALES', NULL, NULL, NULL, 0, 0, '0.00'),
(90, NULL, 'Combo hamburguesa', 'Combo hamburguesa', 13, NULL, '0.00', '1', '2025-01-29 19:09:40', '2025-01-29 19:15:07', 'producto.jpg', 3, '0.00', '', NULL, NULL, NULL, 0, 9, '0.00'),
(91, NULL, 'Cmbo hamburguesa', '2 hamburguesas ', 13, NULL, '0.00', '1', '2025-01-29 19:19:08', '2025-02-01 13:39:58', '1738435134.jpg', 1, '0.00', 'COMBO', NULL, NULL, NULL, 0, 0, '0.00'),
(92, NULL, 'Arep. POLLO y aguacate', 'Arep. POLLO y aguacate', 12, NULL, '0.00', '1', '2025-01-29 20:19:41', '2025-01-29 20:19:41', '', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(93, NULL, 'POSTOBON PERSO 350', 'POSTOBON PERSO 350', 2, NULL, '0.00', '1', '2025-01-29 20:20:25', '2025-02-01 14:35:55', '1738438555.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(94, NULL, 'COCACOLA PERSO 350', 'COCACOLA PERSO 350', 2, NULL, '0.00', '1', '2025-01-29 20:21:07', '2025-02-01 14:36:17', '1738438577.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(95, NULL, 'Bebe postobon', 'Bebe', 2, NULL, '0.00', '1', '2025-01-29 20:48:14', '2025-02-01 14:36:42', '1738438602.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00'),
(96, NULL, 'ADIC.QUESO', 'ADIC. QUESO', 12, NULL, '0.00', '1', '2025-01-29 22:36:55', '2025-01-29 22:36:55', 'producto.jpg', 1, '0.00', 'AREPAS', NULL, NULL, NULL, 0, 0, '0.00'),
(97, NULL, 'ADIC.PATACON', 'PATACON', 9, NULL, '0.00', '1', '2025-01-29 22:38:16', '2025-02-01 14:10:38', '1738437038.jpg', 1, '0.00', 'ADICIONALES', NULL, NULL, NULL, 0, 0, '0.00'),
(98, NULL, 'ADIC. ENSALADA', 'ENSALADA', 9, NULL, '0.00', '1', '2025-01-29 22:39:28', '2025-02-01 14:11:05', '1738437065.png', 1, '0.00', 'ADICIONALES', NULL, NULL, NULL, 0, 0, '0.00'),
(99, NULL, 'ADIC. CHINCHULLA', 'CHINCHULLA', 9, NULL, '0.00', '1', '2025-01-29 22:41:04', '2025-02-01 14:11:31', '1738437091.jpg', 1, '0.00', 'ADICIONALES', NULL, NULL, NULL, 0, 0, '0.00'),
(100, NULL, 'ADIC. SALCHIICHA', 'SALCHIICHA', 12, NULL, '0.00', '1', '2025-01-29 22:45:23', '2025-01-29 22:45:23', 'producto.jpg', 1, '0.00', 'AREPAS', NULL, NULL, NULL, 0, 0, '0.00'),
(101, NULL, 'arepa nueva', 'arepa nueva', 12, NULL, '0.00', '1', '2025-03-02 22:33:05', '2025-03-02 22:33:05', 'producto.jpg', 1, '0.00', '', NULL, NULL, NULL, 0, 0, '0.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_producto_ingrediente`
--

CREATE TABLE `gapp_producto_ingrediente` (
  `id` int(10) NOT NULL,
  `producto_id` int(10) NOT NULL,
  `ingrediente_id` int(10) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `tamano` varchar(20) DEFAULT NULL,
  `cantidad` decimal(7,2) DEFAULT NULL,
  `visible` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `gapp_producto_ingrediente`
--

INSERT INTO `gapp_producto_ingrediente` (`id`, `producto_id`, `ingrediente_id`, `created_at`, `updated_at`, `tamano`, `cantidad`, `visible`) VALUES
(7, 1, 10, '2025-01-22 20:54:37', '2025-01-22 20:54:37', 'unico', '1.00', NULL),
(8, 1, 10, '2025-01-22 20:54:37', '2025-02-02 10:29:24', 'grande', '0.00', NULL),
(9, 1, 10, '2025-01-22 20:54:37', '2025-02-02 10:29:24', 'extrag', '0.00', NULL),
(10, 1, 10, '2025-01-22 20:54:37', '2025-02-02 10:29:24', 'mediano', '0.00', NULL),
(11, 1, 10, '2025-01-22 20:54:37', '2025-02-02 10:29:24', 'pequeno', '0.00', NULL),
(12, 1, 10, '2025-01-22 20:54:37', '2025-02-02 10:29:24', 'porcion', '0.00', NULL),
(25, 3, 209, '2025-01-24 12:45:23', '2025-01-24 12:45:23', 'unico', '1.00', NULL),
(26, 3, 209, '2025-01-24 12:45:23', '2025-03-02 22:28:57', 'grande', '0.00', NULL),
(27, 3, 209, '2025-01-24 12:45:23', '2025-03-02 22:28:57', 'extrag', '0.00', NULL),
(28, 3, 209, '2025-01-24 12:45:23', '2025-03-02 22:28:57', 'mediano', '0.00', NULL),
(29, 3, 209, '2025-01-24 12:45:23', '2025-03-02 22:28:57', 'pequeno', '0.00', NULL),
(30, 3, 209, '2025-01-24 12:45:23', '2025-03-02 22:28:57', 'porcion', '0.00', NULL),
(31, 4, 113, '2025-01-24 12:46:12', '2025-01-24 12:46:12', 'unico', '1.00', NULL),
(32, 4, 113, '2025-01-24 12:46:12', '2025-02-02 10:30:56', 'grande', '0.00', NULL),
(33, 4, 113, '2025-01-24 12:46:12', '2025-02-02 10:30:56', 'extrag', '0.00', NULL),
(34, 4, 113, '2025-01-24 12:46:12', '2025-02-02 10:30:56', 'mediano', '0.00', NULL),
(35, 4, 113, '2025-01-24 12:46:12', '2025-02-02 10:30:56', 'pequeno', '0.00', NULL),
(36, 4, 113, '2025-01-24 12:46:12', '2025-02-02 10:30:56', 'porcion', '0.00', NULL),
(37, 5, 237, '2025-01-24 12:47:29', '2025-01-24 12:47:29', 'unico', '1.00', NULL),
(38, 5, 237, '2025-01-24 12:47:29', '2025-02-02 10:31:13', 'grande', '0.00', NULL),
(39, 5, 237, '2025-01-24 12:47:29', '2025-02-02 10:31:13', 'extrag', '0.00', NULL),
(40, 5, 237, '2025-01-24 12:47:29', '2025-02-02 10:31:13', 'mediano', '0.00', NULL),
(41, 5, 237, '2025-01-24 12:47:29', '2025-02-02 10:31:13', 'pequeno', '0.00', NULL),
(42, 5, 237, '2025-01-24 12:47:29', '2025-02-02 10:31:13', 'porcion', '0.00', NULL),
(43, 6, 82, '2025-01-24 12:48:11', '2025-01-24 12:48:11', 'unico', '1.00', NULL),
(44, 6, 82, '2025-01-24 12:48:11', '2025-02-02 10:31:44', 'grande', '0.00', NULL),
(45, 6, 82, '2025-01-24 12:48:11', '2025-02-02 10:31:44', 'extrag', '0.00', NULL),
(46, 6, 82, '2025-01-24 12:48:11', '2025-02-02 10:31:44', 'mediano', '0.00', NULL),
(47, 6, 82, '2025-01-24 12:48:11', '2025-02-02 10:31:44', 'pequeno', '0.00', NULL),
(48, 6, 82, '2025-01-24 12:48:11', '2025-02-02 10:31:44', 'porcion', '0.00', NULL),
(49, 7, 239, '2025-01-24 12:49:28', '2025-01-24 12:49:28', 'unico', '1.00', NULL),
(50, 7, 239, '2025-01-24 12:49:28', '2025-02-02 10:31:56', 'grande', '0.00', NULL),
(51, 7, 239, '2025-01-24 12:49:28', '2025-02-02 10:31:56', 'extrag', '0.00', NULL),
(52, 7, 239, '2025-01-24 12:49:28', '2025-02-02 10:31:56', 'mediano', '0.00', NULL),
(53, 7, 239, '2025-01-24 12:49:28', '2025-02-02 10:31:56', 'pequeno', '0.00', NULL),
(54, 7, 239, '2025-01-24 12:49:28', '2025-02-02 10:31:56', 'porcion', '0.00', NULL),
(61, 8, 238, '2025-01-24 12:50:14', '2025-01-24 12:50:14', 'unico', '1.00', NULL),
(62, 8, 238, '2025-01-24 12:50:14', '2025-02-02 10:32:19', 'grande', '0.00', NULL),
(63, 8, 238, '2025-01-24 12:50:14', '2025-02-02 10:32:19', 'extrag', '0.00', NULL),
(64, 8, 238, '2025-01-24 12:50:14', '2025-02-02 10:32:19', 'mediano', '0.00', NULL),
(65, 8, 238, '2025-01-24 12:50:14', '2025-02-02 10:32:19', 'pequeno', '0.00', NULL),
(66, 8, 238, '2025-01-24 12:50:14', '2025-02-02 10:32:19', 'porcion', '0.00', NULL),
(67, 10, 241, '2025-01-24 12:53:46', '2025-01-24 12:53:46', 'unico', '1.00', NULL),
(68, 10, 241, '2025-01-24 12:53:46', '2025-02-02 10:37:46', 'grande', '0.00', NULL),
(69, 10, 241, '2025-01-24 12:53:46', '2025-02-02 10:37:46', 'extrag', '0.00', NULL),
(70, 10, 241, '2025-01-24 12:53:46', '2025-02-02 10:37:46', 'mediano', '0.00', NULL),
(71, 10, 241, '2025-01-24 12:53:46', '2025-02-02 10:37:46', 'pequeno', '0.00', NULL),
(72, 10, 241, '2025-01-24 12:53:46', '2025-02-02 10:37:46', 'porcion', '0.00', NULL),
(73, 11, 241, '2025-01-24 12:55:09', '2025-01-24 12:55:25', 'unico', '2.00', NULL),
(74, 11, 241, '2025-01-24 12:55:09', '2025-02-02 10:37:55', 'grande', '0.00', NULL),
(75, 11, 241, '2025-01-24 12:55:09', '2025-02-02 10:37:55', 'extrag', '0.00', NULL),
(76, 11, 241, '2025-01-24 12:55:09', '2025-02-02 10:37:55', 'mediano', '0.00', NULL),
(77, 11, 241, '2025-01-24 12:55:09', '2025-02-02 10:37:55', 'pequeno', '0.00', NULL),
(78, 11, 241, '2025-01-24 12:55:09', '2025-02-02 10:37:55', 'porcion', '0.00', NULL),
(79, 11, 103, '2025-01-24 12:56:58', '2025-01-24 12:56:58', 'unico', '1.00', NULL),
(80, 11, 103, '2025-01-24 12:56:58', '2025-02-02 10:37:55', 'grande', '0.00', NULL),
(81, 11, 103, '2025-01-24 12:56:58', '2025-02-02 10:37:55', 'extrag', '0.00', NULL),
(82, 11, 103, '2025-01-24 12:56:58', '2025-02-02 10:37:55', 'mediano', '0.00', NULL),
(83, 11, 103, '2025-01-24 12:56:58', '2025-02-02 10:37:55', 'pequeno', '0.00', NULL),
(84, 11, 103, '2025-01-24 12:56:58', '2025-02-02 10:37:55', 'porcion', '0.00', NULL),
(85, 11, 105, '2025-01-24 12:56:58', '2025-01-24 12:56:58', 'unico', '1.00', NULL),
(86, 11, 105, '2025-01-24 12:56:58', '2025-02-02 10:37:55', 'grande', '0.00', NULL),
(87, 11, 105, '2025-01-24 12:56:58', '2025-02-02 10:37:55', 'extrag', '0.00', NULL),
(88, 11, 105, '2025-01-24 12:56:58', '2025-02-02 10:37:55', 'mediano', '0.00', NULL),
(89, 11, 105, '2025-01-24 12:56:58', '2025-02-02 10:37:55', 'pequeno', '0.00', NULL),
(90, 11, 105, '2025-01-24 12:56:58', '2025-02-02 10:37:55', 'porcion', '0.00', NULL),
(91, 11, 235, '2025-01-24 12:56:58', '2025-01-24 12:56:58', 'unico', '1.00', NULL),
(92, 11, 235, '2025-01-24 12:56:58', '2025-02-02 10:37:55', 'grande', '0.00', NULL),
(93, 11, 235, '2025-01-24 12:56:58', '2025-02-02 10:37:55', 'extrag', '0.00', NULL),
(94, 11, 235, '2025-01-24 12:56:58', '2025-02-02 10:37:55', 'mediano', '0.00', NULL),
(95, 11, 235, '2025-01-24 12:56:58', '2025-02-02 10:37:55', 'pequeno', '0.00', NULL),
(96, 11, 235, '2025-01-24 12:56:58', '2025-02-02 10:37:55', 'porcion', '0.00', NULL),
(97, 10, 103, '2025-01-24 12:57:37', '2025-01-24 12:57:37', 'unico', '1.00', NULL),
(98, 10, 103, '2025-01-24 12:57:37', '2025-02-02 10:37:46', 'grande', '0.00', NULL),
(99, 10, 103, '2025-01-24 12:57:37', '2025-02-02 10:37:46', 'extrag', '0.00', NULL),
(100, 10, 103, '2025-01-24 12:57:37', '2025-02-02 10:37:46', 'mediano', '0.00', NULL),
(101, 10, 103, '2025-01-24 12:57:37', '2025-02-02 10:37:46', 'pequeno', '0.00', NULL),
(102, 10, 103, '2025-01-24 12:57:37', '2025-02-02 10:37:46', 'porcion', '0.00', NULL),
(103, 10, 105, '2025-01-24 12:57:37', '2025-01-24 12:57:37', 'unico', '1.00', NULL),
(104, 10, 105, '2025-01-24 12:57:37', '2025-02-02 10:37:46', 'grande', '0.00', NULL),
(105, 10, 105, '2025-01-24 12:57:37', '2025-02-02 10:37:46', 'extrag', '0.00', NULL),
(106, 10, 105, '2025-01-24 12:57:37', '2025-02-02 10:37:46', 'mediano', '0.00', NULL),
(107, 10, 105, '2025-01-24 12:57:37', '2025-02-02 10:37:46', 'pequeno', '0.00', NULL),
(108, 10, 105, '2025-01-24 12:57:37', '2025-02-02 10:37:46', 'porcion', '0.00', NULL),
(109, 10, 235, '2025-01-24 12:57:37', '2025-01-24 12:57:37', 'unico', '1.00', NULL),
(110, 10, 235, '2025-01-24 12:57:37', '2025-02-02 10:37:46', 'grande', '0.00', NULL),
(111, 10, 235, '2025-01-24 12:57:37', '2025-02-02 10:37:46', 'extrag', '0.00', NULL),
(112, 10, 235, '2025-01-24 12:57:37', '2025-02-02 10:37:46', 'mediano', '0.00', NULL),
(113, 10, 235, '2025-01-24 12:57:37', '2025-02-02 10:37:46', 'pequeno', '0.00', NULL),
(114, 10, 235, '2025-01-24 12:57:37', '2025-02-02 10:37:46', 'porcion', '0.00', NULL),
(115, 12, 242, '2025-01-24 13:00:09', '2025-01-24 13:00:09', 'unico', '1.00', NULL),
(116, 12, 242, '2025-01-24 13:00:09', '2025-02-02 10:36:51', 'grande', '0.00', NULL),
(117, 12, 242, '2025-01-24 13:00:09', '2025-02-02 10:36:51', 'extrag', '0.00', NULL),
(118, 12, 242, '2025-01-24 13:00:09', '2025-02-02 10:36:51', 'mediano', '0.00', NULL),
(119, 12, 242, '2025-01-24 13:00:09', '2025-02-02 10:36:51', 'pequeno', '0.00', NULL),
(120, 12, 242, '2025-01-24 13:00:09', '2025-02-02 10:36:51', 'porcion', '0.00', NULL),
(121, 13, 146, '2025-01-24 13:01:01', '2025-01-24 13:01:01', 'unico', '1.00', NULL),
(122, 13, 146, '2025-01-24 13:01:01', '2025-02-02 10:37:19', 'grande', '0.00', NULL),
(123, 13, 146, '2025-01-24 13:01:01', '2025-02-02 10:37:19', 'extrag', '0.00', NULL),
(124, 13, 146, '2025-01-24 13:01:01', '2025-02-02 10:37:19', 'mediano', '0.00', NULL),
(125, 13, 146, '2025-01-24 13:01:01', '2025-02-02 10:37:19', 'pequeno', '0.00', NULL),
(126, 13, 146, '2025-01-24 13:01:01', '2025-02-02 10:37:19', 'porcion', '0.00', NULL),
(127, 29, 21, '2025-01-24 13:28:07', '2025-01-24 13:28:07', 'unico', '1.00', NULL),
(128, 29, 21, '2025-01-24 13:28:07', '2025-02-01 13:40:49', 'grande', '0.00', NULL),
(129, 29, 21, '2025-01-24 13:28:07', '2025-02-01 13:40:49', 'extrag', '0.00', NULL),
(130, 29, 21, '2025-01-24 13:28:07', '2025-02-01 13:40:49', 'mediano', '0.00', NULL),
(131, 29, 21, '2025-01-24 13:28:07', '2025-02-01 13:40:49', 'pequeno', '0.00', NULL),
(132, 29, 21, '2025-01-24 13:28:07', '2025-02-01 13:40:49', 'porcion', '0.00', NULL),
(133, 29, 105, '2025-01-24 13:28:07', '2025-01-24 13:28:07', 'unico', '1.00', NULL),
(134, 29, 105, '2025-01-24 13:28:07', '2025-02-01 13:40:49', 'grande', '0.00', NULL),
(135, 29, 105, '2025-01-24 13:28:07', '2025-02-01 13:40:49', 'extrag', '0.00', NULL),
(136, 29, 105, '2025-01-24 13:28:07', '2025-02-01 13:40:49', 'mediano', '0.00', NULL),
(137, 29, 105, '2025-01-24 13:28:07', '2025-02-01 13:40:49', 'pequeno', '0.00', NULL),
(138, 29, 105, '2025-01-24 13:28:07', '2025-02-01 13:40:49', 'porcion', '0.00', NULL),
(139, 30, 105, '2025-01-24 13:29:01', '2025-01-24 13:29:01', 'unico', '0.00', NULL),
(140, 30, 105, '2025-01-24 13:29:01', '2025-02-01 13:41:15', 'grande', '0.00', NULL),
(141, 30, 105, '2025-01-24 13:29:01', '2025-02-01 13:41:15', 'extrag', '0.00', NULL),
(142, 30, 105, '2025-01-24 13:29:01', '2025-02-01 13:41:15', 'mediano', '0.00', NULL),
(143, 30, 105, '2025-01-24 13:29:01', '2025-02-01 13:41:15', 'pequeno', '0.00', NULL),
(144, 30, 105, '2025-01-24 13:29:01', '2025-02-01 13:41:15', 'porcion', '0.00', NULL),
(145, 30, 243, '2025-01-24 13:29:01', '2025-01-24 13:29:01', 'unico', '1.00', NULL),
(146, 30, 243, '2025-01-24 13:29:01', '2025-02-01 13:41:15', 'grande', '0.00', NULL),
(147, 30, 243, '2025-01-24 13:29:01', '2025-02-01 13:41:15', 'extrag', '0.00', NULL),
(148, 30, 243, '2025-01-24 13:29:01', '2025-02-01 13:41:15', 'mediano', '0.00', NULL),
(149, 30, 243, '2025-01-24 13:29:01', '2025-02-01 13:41:15', 'pequeno', '0.00', NULL),
(150, 30, 243, '2025-01-24 13:29:01', '2025-02-01 13:41:15', 'porcion', '0.00', NULL),
(151, 9, 240, '2025-01-24 17:05:43', '2025-01-24 17:05:43', 'unico', '1.00', NULL),
(152, 9, 240, '2025-01-24 17:05:43', '2025-02-02 10:32:31', 'grande', '0.00', NULL),
(153, 9, 240, '2025-01-24 17:05:43', '2025-02-02 10:32:31', 'extrag', '0.00', NULL),
(154, 9, 240, '2025-01-24 17:05:43', '2025-02-02 10:32:31', 'mediano', '0.00', NULL),
(155, 9, 240, '2025-01-24 17:05:43', '2025-02-02 10:32:31', 'pequeno', '0.00', NULL),
(156, 9, 240, '2025-01-24 17:05:43', '2025-02-02 10:32:31', 'porcion', '0.00', NULL),
(169, 31, 235, '2025-01-24 17:14:05', '2025-01-24 17:14:05', 'unico', '1.00', NULL),
(170, 31, 235, '2025-01-24 17:14:05', '2025-03-02 22:31:15', 'grande', '0.00', NULL),
(171, 31, 235, '2025-01-24 17:14:05', '2025-03-02 22:31:15', 'extrag', '0.00', NULL),
(172, 31, 235, '2025-01-24 17:14:05', '2025-03-02 22:31:15', 'mediano', '0.00', NULL),
(173, 31, 235, '2025-01-24 17:14:05', '2025-03-02 22:31:15', 'pequeno', '0.00', NULL),
(174, 31, 235, '2025-01-24 17:14:05', '2025-03-02 22:31:15', 'porcion', '0.00', NULL),
(175, 32, 14, '2025-01-24 17:14:26', '2025-01-24 17:14:45', 'unico', '6.00', NULL),
(176, 32, 14, '2025-01-24 17:14:26', '2025-02-01 13:44:03', 'grande', '0.00', NULL),
(177, 32, 14, '2025-01-24 17:14:26', '2025-02-01 13:44:03', 'extrag', '0.00', NULL),
(178, 32, 14, '2025-01-24 17:14:26', '2025-02-01 13:44:03', 'mediano', '0.00', NULL),
(179, 32, 14, '2025-01-24 17:14:26', '2025-02-01 13:44:03', 'pequeno', '0.00', NULL),
(180, 32, 14, '2025-01-24 17:14:26', '2025-02-01 13:44:03', 'porcion', '0.00', NULL),
(187, 32, 235, '2025-01-24 17:14:26', '2025-01-24 17:14:26', 'unico', '1.00', NULL),
(188, 32, 235, '2025-01-24 17:14:26', '2025-02-01 13:44:03', 'grande', '0.00', NULL),
(189, 32, 235, '2025-01-24 17:14:26', '2025-02-01 13:44:03', 'extrag', '0.00', NULL),
(190, 32, 235, '2025-01-24 17:14:26', '2025-02-01 13:44:03', 'mediano', '0.00', NULL),
(191, 32, 235, '2025-01-24 17:14:26', '2025-02-01 13:44:03', 'pequeno', '0.00', NULL),
(192, 32, 235, '2025-01-24 17:14:26', '2025-02-01 13:44:03', 'porcion', '0.00', NULL),
(199, 33, 235, '2025-01-24 17:15:01', '2025-01-24 17:15:01', 'unico', '1.00', NULL),
(200, 33, 235, '2025-01-24 17:15:01', '2025-02-01 13:44:16', 'grande', '0.00', NULL),
(201, 33, 235, '2025-01-24 17:15:01', '2025-02-01 13:44:16', 'extrag', '0.00', NULL),
(202, 33, 235, '2025-01-24 17:15:01', '2025-02-01 13:44:16', 'mediano', '0.00', NULL),
(203, 33, 235, '2025-01-24 17:15:01', '2025-02-01 13:44:16', 'pequeno', '0.00', NULL),
(204, 33, 235, '2025-01-24 17:15:01', '2025-02-01 13:44:16', 'porcion', '0.00', NULL),
(217, 34, 122, '2025-01-24 17:18:37', '2025-01-24 17:18:37', 'unico', '1.00', NULL),
(218, 34, 122, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'grande', '0.00', NULL),
(219, 34, 122, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'extrag', '0.00', NULL),
(220, 34, 122, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'mediano', '0.00', NULL),
(221, 34, 122, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'pequeno', '0.00', NULL),
(222, 34, 122, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'porcion', '0.00', NULL),
(223, 34, 96, '2025-01-24 17:18:37', '2025-01-24 17:18:37', 'unico', '1.00', NULL),
(224, 34, 96, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'grande', '0.00', NULL),
(225, 34, 96, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'extrag', '0.00', NULL),
(226, 34, 96, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'mediano', '0.00', NULL),
(227, 34, 96, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'pequeno', '0.00', NULL),
(228, 34, 96, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'porcion', '0.00', NULL),
(247, 34, 34, '2025-01-24 17:18:37', '2025-01-24 17:18:37', 'unico', '0.00', NULL),
(248, 34, 34, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'grande', '0.00', NULL),
(249, 34, 34, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'extrag', '0.00', NULL),
(250, 34, 34, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'mediano', '0.00', NULL),
(251, 34, 34, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'pequeno', '0.00', NULL),
(252, 34, 34, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'porcion', '0.00', NULL),
(253, 34, 33, '2025-01-24 17:18:37', '2025-01-24 17:18:37', 'unico', '0.00', NULL),
(254, 34, 33, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'grande', '0.00', NULL),
(255, 34, 33, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'extrag', '0.00', NULL),
(256, 34, 33, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'mediano', '0.00', NULL),
(257, 34, 33, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'pequeno', '0.00', NULL),
(258, 34, 33, '2025-01-24 17:18:37', '2025-02-01 13:52:04', 'porcion', '0.00', NULL),
(271, 35, 160, '2025-01-24 17:18:53', '2025-01-24 17:18:53', 'unico', '0.00', NULL),
(272, 35, 160, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'grande', '0.00', NULL),
(273, 35, 160, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'extrag', '0.00', NULL),
(274, 35, 160, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'mediano', '0.00', NULL),
(275, 35, 160, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'pequeno', '0.00', NULL),
(276, 35, 160, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'porcion', '0.00', NULL),
(277, 35, 122, '2025-01-24 17:18:53', '2025-01-24 17:18:53', 'unico', '1.00', NULL),
(278, 35, 122, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'grande', '0.00', NULL),
(279, 35, 122, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'extrag', '0.00', NULL),
(280, 35, 122, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'mediano', '0.00', NULL),
(281, 35, 122, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'pequeno', '0.00', NULL),
(282, 35, 122, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'porcion', '0.00', NULL),
(283, 35, 96, '2025-01-24 17:18:53', '2025-01-24 17:18:53', 'unico', '1.00', NULL),
(284, 35, 96, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'grande', '0.00', NULL),
(285, 35, 96, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'extrag', '0.00', NULL),
(286, 35, 96, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'mediano', '0.00', NULL),
(287, 35, 96, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'pequeno', '0.00', NULL),
(288, 35, 96, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'porcion', '0.00', NULL),
(307, 35, 34, '2025-01-24 17:18:53', '2025-01-24 17:18:53', 'unico', '0.00', NULL),
(308, 35, 34, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'grande', '0.00', NULL),
(309, 35, 34, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'extrag', '0.00', NULL),
(310, 35, 34, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'mediano', '0.00', NULL),
(311, 35, 34, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'pequeno', '0.00', NULL),
(312, 35, 34, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'porcion', '0.00', NULL),
(313, 35, 33, '2025-01-24 17:18:53', '2025-01-24 17:18:53', 'unico', '0.00', NULL),
(314, 35, 33, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'grande', '0.00', NULL),
(315, 35, 33, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'extrag', '0.00', NULL),
(316, 35, 33, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'mediano', '0.00', NULL),
(317, 35, 33, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'pequeno', '0.00', NULL),
(318, 35, 33, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'porcion', '0.00', NULL),
(319, 35, 83, '2025-01-24 17:18:53', '2025-01-24 17:18:53', 'unico', '0.00', NULL),
(320, 35, 83, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'grande', '0.00', NULL),
(321, 35, 83, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'extrag', '0.00', NULL),
(322, 35, 83, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'mediano', '0.00', NULL),
(323, 35, 83, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'pequeno', '0.00', NULL),
(324, 35, 83, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'porcion', '0.00', NULL),
(325, 35, 37, '2025-01-24 17:18:53', '2025-01-24 17:18:53', 'unico', '0.00', NULL),
(326, 35, 37, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'grande', '0.00', NULL),
(327, 35, 37, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'extrag', '0.00', NULL),
(328, 35, 37, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'mediano', '0.00', NULL),
(329, 35, 37, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'pequeno', '0.00', NULL),
(330, 35, 37, '2025-01-24 17:18:53', '2025-02-01 13:52:31', 'porcion', '0.00', NULL),
(331, 35, 12, '2025-01-24 17:19:21', '2025-01-24 17:19:21', 'unico', '0.00', NULL),
(332, 35, 12, '2025-01-24 17:19:21', '2025-02-01 13:52:31', 'grande', '0.00', NULL),
(333, 35, 12, '2025-01-24 17:19:21', '2025-02-01 13:52:31', 'extrag', '0.00', NULL),
(334, 35, 12, '2025-01-24 17:19:21', '2025-02-01 13:52:31', 'mediano', '0.00', NULL),
(335, 35, 12, '2025-01-24 17:19:21', '2025-02-01 13:52:31', 'pequeno', '0.00', NULL),
(336, 35, 12, '2025-01-24 17:19:21', '2025-02-01 13:52:31', 'porcion', '0.00', NULL),
(337, 34, 12, '2025-01-24 17:19:41', '2025-01-24 17:19:41', 'unico', '0.00', NULL),
(338, 34, 12, '2025-01-24 17:19:41', '2025-02-01 13:52:04', 'grande', '0.00', NULL),
(339, 34, 12, '2025-01-24 17:19:41', '2025-02-01 13:52:04', 'extrag', '0.00', NULL),
(340, 34, 12, '2025-01-24 17:19:41', '2025-02-01 13:52:04', 'mediano', '0.00', NULL),
(341, 34, 12, '2025-01-24 17:19:41', '2025-02-01 13:52:04', 'pequeno', '0.00', NULL),
(342, 34, 12, '2025-01-24 17:19:41', '2025-02-01 13:52:04', 'porcion', '0.00', NULL),
(343, 36, 160, '2025-01-24 17:20:24', '2025-01-24 17:20:24', 'unico', '0.00', NULL),
(344, 36, 160, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'grande', '0.00', NULL),
(345, 36, 160, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'extrag', '0.00', NULL),
(346, 36, 160, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'mediano', '0.00', NULL),
(347, 36, 160, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'pequeno', '0.00', NULL),
(348, 36, 160, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'porcion', '0.00', NULL),
(349, 36, 122, '2025-01-24 17:20:24', '2025-01-24 17:20:24', 'unico', '1.00', NULL),
(350, 36, 122, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'grande', '0.00', NULL),
(351, 36, 122, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'extrag', '0.00', NULL),
(352, 36, 122, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'mediano', '0.00', NULL),
(353, 36, 122, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'pequeno', '0.00', NULL),
(354, 36, 122, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'porcion', '0.00', NULL),
(355, 36, 96, '2025-01-24 17:20:24', '2025-01-24 17:20:24', 'unico', '1.00', NULL),
(356, 36, 96, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'grande', '0.00', NULL),
(357, 36, 96, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'extrag', '0.00', NULL),
(358, 36, 96, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'mediano', '0.00', NULL),
(359, 36, 96, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'pequeno', '0.00', NULL),
(360, 36, 96, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'porcion', '0.00', NULL),
(361, 36, 28, '2025-01-24 17:20:24', '2025-01-24 17:20:24', 'unico', '1.00', NULL),
(362, 36, 28, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'grande', '0.00', NULL),
(363, 36, 28, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'extrag', '0.00', NULL),
(364, 36, 28, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'mediano', '0.00', NULL),
(365, 36, 28, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'pequeno', '0.00', NULL),
(366, 36, 28, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'porcion', '0.00', NULL),
(379, 36, 34, '2025-01-24 17:20:24', '2025-01-24 17:20:24', 'unico', '0.00', NULL),
(380, 36, 34, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'grande', '0.00', NULL),
(381, 36, 34, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'extrag', '0.00', NULL),
(382, 36, 34, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'mediano', '0.00', NULL),
(383, 36, 34, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'pequeno', '0.00', NULL),
(384, 36, 34, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'porcion', '0.00', NULL),
(385, 36, 33, '2025-01-24 17:20:24', '2025-01-24 17:20:24', 'unico', '0.00', NULL),
(386, 36, 33, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'grande', '0.00', NULL),
(387, 36, 33, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'extrag', '0.00', NULL),
(388, 36, 33, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'mediano', '0.00', NULL),
(389, 36, 33, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'pequeno', '0.00', NULL),
(390, 36, 33, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'porcion', '0.00', NULL),
(391, 36, 83, '2025-01-24 17:20:24', '2025-01-24 17:20:24', 'unico', '0.00', NULL),
(392, 36, 83, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'grande', '0.00', NULL),
(393, 36, 83, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'extrag', '0.00', NULL),
(394, 36, 83, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'mediano', '0.00', NULL),
(395, 36, 83, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'pequeno', '0.00', NULL),
(396, 36, 83, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'porcion', '0.00', NULL),
(397, 36, 37, '2025-01-24 17:20:24', '2025-01-24 17:20:24', 'unico', '0.00', NULL),
(398, 36, 37, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'grande', '0.00', NULL),
(399, 36, 37, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'extrag', '0.00', NULL),
(400, 36, 37, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'mediano', '0.00', NULL),
(401, 36, 37, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'pequeno', '0.00', NULL),
(402, 36, 37, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'porcion', '0.00', NULL),
(403, 36, 12, '2025-01-24 17:20:24', '2025-01-24 17:20:24', 'unico', '0.00', NULL),
(404, 36, 12, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'grande', '0.00', NULL),
(405, 36, 12, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'extrag', '0.00', NULL),
(406, 36, 12, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'mediano', '0.00', NULL),
(407, 36, 12, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'pequeno', '0.00', NULL),
(408, 36, 12, '2025-01-24 17:20:24', '2025-01-29 20:24:03', 'porcion', '0.00', NULL),
(409, 36, 21, '2025-01-24 17:21:14', '2025-01-24 17:21:14', 'unico', '1.00', NULL),
(410, 36, 21, '2025-01-24 17:21:14', '2025-01-29 20:24:03', 'grande', '0.00', NULL),
(411, 36, 21, '2025-01-24 17:21:14', '2025-01-29 20:24:03', 'extrag', '0.00', NULL),
(412, 36, 21, '2025-01-24 17:21:14', '2025-01-29 20:24:03', 'mediano', '0.00', NULL),
(413, 36, 21, '2025-01-24 17:21:14', '2025-01-29 20:24:03', 'pequeno', '0.00', NULL),
(414, 36, 21, '2025-01-24 17:21:14', '2025-01-29 20:24:03', 'porcion', '0.00', NULL),
(415, 37, 160, '2025-01-24 17:21:40', '2025-01-24 17:21:40', 'unico', '0.00', NULL),
(416, 37, 160, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'grande', '0.00', NULL),
(417, 37, 160, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'extrag', '0.00', NULL),
(418, 37, 160, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'mediano', '0.00', NULL),
(419, 37, 160, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'pequeno', '0.00', NULL),
(420, 37, 160, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'porcion', '0.00', NULL),
(421, 37, 122, '2025-01-24 17:21:40', '2025-01-24 17:21:40', 'unico', '1.00', NULL),
(422, 37, 122, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'grande', '0.00', NULL),
(423, 37, 122, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'extrag', '0.00', NULL),
(424, 37, 122, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'mediano', '0.00', NULL),
(425, 37, 122, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'pequeno', '0.00', NULL),
(426, 37, 122, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'porcion', '0.00', NULL),
(427, 37, 96, '2025-01-24 17:21:40', '2025-01-24 17:21:40', 'unico', '1.00', NULL),
(428, 37, 96, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'grande', '0.00', NULL),
(429, 37, 96, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'extrag', '0.00', NULL),
(430, 37, 96, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'mediano', '0.00', NULL),
(431, 37, 96, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'pequeno', '0.00', NULL),
(432, 37, 96, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'porcion', '0.00', NULL),
(433, 37, 28, '2025-01-24 17:21:40', '2025-01-24 17:21:40', 'unico', '1.00', NULL),
(434, 37, 28, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'grande', '0.00', NULL),
(435, 37, 28, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'extrag', '0.00', NULL),
(436, 37, 28, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'mediano', '0.00', NULL),
(437, 37, 28, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'pequeno', '0.00', NULL),
(438, 37, 28, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'porcion', '0.00', NULL),
(445, 37, 34, '2025-01-24 17:21:40', '2025-01-24 17:21:40', 'unico', '0.00', NULL),
(446, 37, 34, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'grande', '0.00', NULL),
(447, 37, 34, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'extrag', '0.00', NULL),
(448, 37, 34, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'mediano', '0.00', NULL),
(449, 37, 34, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'pequeno', '0.00', NULL),
(450, 37, 34, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'porcion', '0.00', NULL),
(451, 37, 33, '2025-01-24 17:21:40', '2025-01-24 17:21:40', 'unico', '0.00', NULL),
(452, 37, 33, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'grande', '0.00', NULL),
(453, 37, 33, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'extrag', '0.00', NULL),
(454, 37, 33, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'mediano', '0.00', NULL),
(455, 37, 33, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'pequeno', '0.00', NULL),
(456, 37, 33, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'porcion', '0.00', NULL),
(457, 37, 83, '2025-01-24 17:21:40', '2025-01-24 17:21:40', 'unico', '0.00', NULL),
(458, 37, 83, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'grande', '0.00', NULL),
(459, 37, 83, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'extrag', '0.00', NULL),
(460, 37, 83, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'mediano', '0.00', NULL),
(461, 37, 83, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'pequeno', '0.00', NULL),
(462, 37, 83, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'porcion', '0.00', NULL),
(463, 37, 37, '2025-01-24 17:21:40', '2025-01-24 17:21:40', 'unico', '0.00', NULL),
(464, 37, 37, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'grande', '0.00', NULL),
(465, 37, 37, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'extrag', '0.00', NULL),
(466, 37, 37, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'mediano', '0.00', NULL),
(467, 37, 37, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'pequeno', '0.00', NULL),
(468, 37, 37, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'porcion', '0.00', NULL),
(469, 37, 12, '2025-01-24 17:21:40', '2025-01-24 17:24:09', 'unico', '1.00', NULL),
(470, 37, 12, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'grande', '0.00', NULL),
(471, 37, 12, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'extrag', '0.00', NULL),
(472, 37, 12, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'mediano', '0.00', NULL),
(473, 37, 12, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'pequeno', '0.00', NULL),
(474, 37, 12, '2025-01-24 17:21:40', '2025-01-29 20:24:11', 'porcion', '0.00', NULL),
(487, 38, 14, '2025-01-24 17:25:42', '2025-01-24 17:25:42', 'unico', '1.00', NULL),
(488, 38, 14, '2025-01-24 17:25:42', '2025-02-01 13:50:52', 'grande', '0.00', NULL),
(489, 38, 14, '2025-01-24 17:25:42', '2025-02-01 13:50:52', 'extrag', '0.00', NULL),
(490, 38, 14, '2025-01-24 17:25:42', '2025-02-01 13:50:52', 'mediano', '0.00', NULL),
(491, 38, 14, '2025-01-24 17:25:42', '2025-02-01 13:50:52', 'pequeno', '0.00', NULL),
(492, 38, 14, '2025-01-24 17:25:42', '2025-02-01 13:50:52', 'porcion', '0.00', NULL),
(493, 38, 21, '2025-01-24 17:25:42', '2025-01-24 17:25:42', 'unico', '1.00', NULL),
(494, 38, 21, '2025-01-24 17:25:42', '2025-02-01 13:50:52', 'grande', '0.00', NULL),
(495, 38, 21, '2025-01-24 17:25:42', '2025-02-01 13:50:52', 'extrag', '0.00', NULL),
(496, 38, 21, '2025-01-24 17:25:42', '2025-02-01 13:50:52', 'mediano', '0.00', NULL),
(497, 38, 21, '2025-01-24 17:25:42', '2025-02-01 13:50:52', 'pequeno', '0.00', NULL),
(498, 38, 21, '2025-01-24 17:25:42', '2025-02-01 13:50:52', 'porcion', '0.00', NULL),
(505, 39, 14, '2025-01-24 17:26:00', '2025-01-24 17:26:00', 'unico', '1.00', NULL),
(506, 39, 14, '2025-01-24 17:26:00', '2025-02-01 15:07:32', 'grande', '0.00', NULL),
(507, 39, 14, '2025-01-24 17:26:00', '2025-02-01 15:07:32', 'extrag', '0.00', NULL),
(508, 39, 14, '2025-01-24 17:26:00', '2025-02-01 15:07:32', 'mediano', '0.00', NULL),
(509, 39, 14, '2025-01-24 17:26:00', '2025-02-01 15:07:32', 'pequeno', '0.00', NULL),
(510, 39, 14, '2025-01-24 17:26:00', '2025-02-01 15:07:32', 'porcion', '0.00', NULL),
(523, 40, 14, '2025-01-24 17:26:32', '2025-01-24 17:26:32', 'unico', '1.00', NULL),
(524, 40, 14, '2025-01-24 17:26:32', '2025-02-01 13:49:51', 'grande', '0.00', NULL),
(525, 40, 14, '2025-01-24 17:26:32', '2025-02-01 13:49:51', 'extrag', '0.00', NULL),
(526, 40, 14, '2025-01-24 17:26:32', '2025-02-01 13:49:51', 'mediano', '0.00', NULL),
(527, 40, 14, '2025-01-24 17:26:32', '2025-02-01 13:49:51', 'pequeno', '0.00', NULL),
(528, 40, 14, '2025-01-24 17:26:32', '2025-02-01 13:49:51', 'porcion', '0.00', NULL),
(547, 40, 240, '2025-01-24 17:28:18', '2025-01-24 17:28:18', 'unico', '1.00', NULL),
(548, 40, 240, '2025-01-24 17:28:18', '2025-02-01 13:49:51', 'grande', '0.00', NULL),
(549, 40, 240, '2025-01-24 17:28:18', '2025-02-01 13:49:51', 'extrag', '0.00', NULL),
(550, 40, 240, '2025-01-24 17:28:18', '2025-02-01 13:49:51', 'mediano', '0.00', NULL),
(551, 40, 240, '2025-01-24 17:28:18', '2025-02-01 13:49:51', 'pequeno', '0.00', NULL),
(552, 40, 240, '2025-01-24 17:28:18', '2025-02-01 13:49:51', 'porcion', '0.00', NULL),
(553, 40, 243, '2025-01-24 17:28:18', '2025-01-24 17:28:18', 'unico', '1.00', NULL),
(554, 40, 243, '2025-01-24 17:28:18', '2025-02-01 13:49:51', 'grande', '0.00', NULL),
(555, 40, 243, '2025-01-24 17:28:18', '2025-02-01 13:49:51', 'extrag', '0.00', NULL),
(556, 40, 243, '2025-01-24 17:28:18', '2025-02-01 13:49:51', 'mediano', '0.00', NULL),
(557, 40, 243, '2025-01-24 17:28:18', '2025-02-01 13:49:51', 'pequeno', '0.00', NULL),
(558, 40, 243, '2025-01-24 17:28:18', '2025-02-01 13:49:51', 'porcion', '0.00', NULL),
(559, 40, 224, '2025-01-24 17:28:18', '2025-01-24 17:28:18', 'unico', '1.00', NULL),
(560, 40, 224, '2025-01-24 17:28:18', '2025-02-01 13:49:51', 'grande', '0.00', NULL),
(561, 40, 224, '2025-01-24 17:28:18', '2025-02-01 13:49:51', 'extrag', '0.00', NULL),
(562, 40, 224, '2025-01-24 17:28:18', '2025-02-01 13:49:51', 'mediano', '0.00', NULL),
(563, 40, 224, '2025-01-24 17:28:18', '2025-02-01 13:49:51', 'pequeno', '0.00', NULL),
(564, 40, 224, '2025-01-24 17:28:18', '2025-02-01 13:49:51', 'porcion', '0.00', NULL),
(577, 1, 147, '2025-01-24 21:52:47', '2025-01-24 21:52:47', 'unico', '0.00', NULL),
(578, 1, 147, '2025-01-24 21:52:47', '2025-02-02 10:29:24', 'grande', '0.00', NULL),
(579, 1, 147, '2025-01-24 21:52:47', '2025-02-02 10:29:24', 'extrag', '0.00', NULL),
(580, 1, 147, '2025-01-24 21:52:47', '2025-02-02 10:29:24', 'mediano', '0.00', NULL),
(581, 1, 147, '2025-01-24 21:52:47', '2025-02-02 10:29:24', 'pequeno', '0.00', NULL),
(582, 1, 147, '2025-01-24 21:52:47', '2025-02-02 10:29:24', 'porcion', '0.00', NULL),
(631, 1, 204, '2025-01-24 22:21:26', '2025-01-24 22:21:26', 'unico', '0.00', NULL),
(632, 1, 204, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'grande', '0.00', NULL),
(633, 1, 204, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'extrag', '0.00', NULL),
(634, 1, 204, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'mediano', '0.00', NULL),
(635, 1, 204, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'pequeno', '0.00', NULL),
(636, 1, 204, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'porcion', '0.00', NULL),
(637, 1, 244, '2025-01-24 22:21:26', '2025-01-24 22:21:26', 'unico', '0.00', NULL),
(638, 1, 244, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'grande', '0.00', NULL),
(639, 1, 244, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'extrag', '0.00', NULL),
(640, 1, 244, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'mediano', '0.00', NULL),
(641, 1, 244, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'pequeno', '0.00', NULL),
(642, 1, 244, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'porcion', '0.00', NULL),
(643, 1, 245, '2025-01-24 22:21:26', '2025-01-24 22:21:26', 'unico', '0.00', NULL),
(644, 1, 245, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'grande', '0.00', NULL),
(645, 1, 245, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'extrag', '0.00', NULL),
(646, 1, 245, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'mediano', '0.00', NULL),
(647, 1, 245, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'pequeno', '0.00', NULL),
(648, 1, 245, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'porcion', '0.00', NULL),
(649, 1, 103, '2025-01-24 22:21:26', '2025-01-24 22:21:26', 'unico', '0.00', NULL),
(650, 1, 103, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'grande', '0.00', NULL),
(651, 1, 103, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'extrag', '0.00', NULL),
(652, 1, 103, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'mediano', '0.00', NULL),
(653, 1, 103, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'pequeno', '0.00', NULL),
(654, 1, 103, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'porcion', '0.00', NULL),
(655, 1, 105, '2025-01-24 22:21:26', '2025-01-24 22:21:26', 'unico', '0.00', NULL),
(656, 1, 105, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'grande', '0.00', NULL),
(657, 1, 105, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'extrag', '0.00', NULL),
(658, 1, 105, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'mediano', '0.00', NULL),
(659, 1, 105, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'pequeno', '0.00', NULL),
(660, 1, 105, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'porcion', '0.00', NULL),
(661, 1, 236, '2025-01-24 22:21:26', '2025-01-24 22:21:26', 'unico', '0.00', NULL),
(662, 1, 236, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'grande', '0.00', NULL),
(663, 1, 236, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'extrag', '0.00', NULL),
(664, 1, 236, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'mediano', '0.00', NULL),
(665, 1, 236, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'pequeno', '0.00', NULL),
(666, 1, 236, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'porcion', '0.00', NULL),
(667, 1, 235, '2025-01-24 22:21:26', '2025-01-24 22:21:26', 'unico', '0.00', NULL),
(668, 1, 235, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'grande', '0.00', NULL),
(669, 1, 235, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'extrag', '0.00', NULL),
(670, 1, 235, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'mediano', '0.00', NULL),
(671, 1, 235, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'pequeno', '0.00', NULL),
(672, 1, 235, '2025-01-24 22:21:26', '2025-02-02 10:29:24', 'porcion', '0.00', NULL),
(673, 2, 204, '2025-01-27 16:47:27', '2025-01-27 16:47:27', 'unico', '0.00', NULL),
(674, 2, 204, '2025-01-27 16:47:27', '2025-02-02 10:29:33', 'grande', '0.00', NULL),
(675, 2, 204, '2025-01-27 16:47:27', '2025-02-02 10:29:33', 'extrag', '0.00', NULL),
(676, 2, 204, '2025-01-27 16:47:27', '2025-02-02 10:29:33', 'mediano', '0.00', NULL),
(677, 2, 204, '2025-01-27 16:47:27', '2025-02-02 10:29:33', 'pequeno', '0.00', NULL),
(678, 2, 204, '2025-01-27 16:47:27', '2025-02-02 10:29:33', 'porcion', '0.00', NULL),
(679, 2, 244, '2025-01-27 16:47:27', '2025-01-27 16:47:27', 'unico', '0.00', NULL),
(680, 2, 244, '2025-01-27 16:47:27', '2025-02-02 10:29:33', 'grande', '0.00', NULL),
(681, 2, 244, '2025-01-27 16:47:27', '2025-02-02 10:29:33', 'extrag', '0.00', NULL),
(682, 2, 244, '2025-01-27 16:47:27', '2025-02-02 10:29:33', 'mediano', '0.00', NULL),
(683, 2, 244, '2025-01-27 16:47:27', '2025-02-02 10:29:33', 'pequeno', '0.00', NULL),
(684, 2, 244, '2025-01-27 16:47:27', '2025-02-02 10:29:33', 'porcion', '0.00', NULL),
(685, 2, 245, '2025-01-27 16:47:27', '2025-01-27 16:47:27', 'unico', '0.00', NULL),
(686, 2, 245, '2025-01-27 16:47:27', '2025-02-02 10:29:33', 'grande', '0.00', NULL),
(687, 2, 245, '2025-01-27 16:47:27', '2025-02-02 10:29:33', 'extrag', '0.00', NULL),
(688, 2, 245, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'mediano', '0.00', NULL),
(689, 2, 245, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'pequeno', '0.00', NULL),
(690, 2, 245, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'porcion', '0.00', NULL),
(691, 2, 103, '2025-01-27 16:47:28', '2025-01-27 16:47:28', 'unico', '0.00', NULL),
(692, 2, 103, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'grande', '0.00', NULL),
(693, 2, 103, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'extrag', '0.00', NULL),
(694, 2, 103, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'mediano', '0.00', NULL),
(695, 2, 103, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'pequeno', '0.00', NULL),
(696, 2, 103, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'porcion', '0.00', NULL),
(697, 2, 147, '2025-01-27 16:47:28', '2025-01-27 16:47:28', 'unico', '0.00', NULL),
(698, 2, 147, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'grande', '0.00', NULL),
(699, 2, 147, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'extrag', '0.00', NULL),
(700, 2, 147, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'mediano', '0.00', NULL),
(701, 2, 147, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'pequeno', '0.00', NULL),
(702, 2, 147, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'porcion', '0.00', NULL),
(703, 2, 105, '2025-01-27 16:47:28', '2025-01-27 16:47:28', 'unico', '0.00', NULL),
(704, 2, 105, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'grande', '0.00', NULL),
(705, 2, 105, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'extrag', '0.00', NULL),
(706, 2, 105, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'mediano', '0.00', NULL),
(707, 2, 105, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'pequeno', '0.00', NULL),
(708, 2, 105, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'porcion', '0.00', NULL),
(709, 2, 225, '2025-01-27 16:47:28', '2025-01-27 16:47:28', 'unico', '0.00', NULL),
(710, 2, 225, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'grande', '0.00', NULL),
(711, 2, 225, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'extrag', '0.00', NULL),
(712, 2, 225, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'mediano', '0.00', NULL),
(713, 2, 225, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'pequeno', '0.00', NULL),
(714, 2, 225, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'porcion', '0.00', NULL),
(715, 2, 236, '2025-01-27 16:47:28', '2025-01-27 16:47:28', 'unico', '0.00', NULL),
(716, 2, 236, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'grande', '0.00', NULL),
(717, 2, 236, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'extrag', '0.00', NULL),
(718, 2, 236, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'mediano', '0.00', NULL),
(719, 2, 236, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'pequeno', '0.00', NULL),
(720, 2, 236, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'porcion', '0.00', NULL),
(721, 2, 235, '2025-01-27 16:47:28', '2025-01-27 16:47:28', 'unico', '0.00', NULL),
(722, 2, 235, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'grande', '0.00', NULL),
(723, 2, 235, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'extrag', '0.00', NULL),
(724, 2, 235, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'mediano', '0.00', NULL),
(725, 2, 235, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'pequeno', '0.00', NULL),
(726, 2, 235, '2025-01-27 16:47:28', '2025-02-02 10:29:33', 'porcion', '0.00', NULL),
(727, 29, 244, '2025-01-27 19:44:38', '2025-01-27 19:44:38', 'unico', '0.00', NULL),
(728, 29, 244, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'grande', '0.00', NULL),
(729, 29, 244, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'extrag', '0.00', NULL),
(730, 29, 244, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'mediano', '0.00', NULL),
(731, 29, 244, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'pequeno', '0.00', NULL),
(732, 29, 244, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'porcion', '0.00', NULL),
(733, 29, 245, '2025-01-27 19:44:38', '2025-01-27 19:44:38', 'unico', '0.00', NULL),
(734, 29, 245, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'grande', '0.00', NULL),
(735, 29, 245, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'extrag', '0.00', NULL),
(736, 29, 245, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'mediano', '0.00', NULL),
(737, 29, 245, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'pequeno', '0.00', NULL),
(738, 29, 245, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'porcion', '0.00', NULL),
(739, 29, 147, '2025-01-27 19:44:38', '2025-01-27 19:44:38', 'unico', '0.00', NULL),
(740, 29, 147, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'grande', '0.00', NULL),
(741, 29, 147, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'extrag', '0.00', NULL),
(742, 29, 147, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'mediano', '0.00', NULL),
(743, 29, 147, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'pequeno', '0.00', NULL),
(744, 29, 147, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'porcion', '0.00', NULL),
(745, 29, 236, '2025-01-27 19:44:38', '2025-01-27 19:44:38', 'unico', '0.00', NULL),
(746, 29, 236, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'grande', '0.00', NULL),
(747, 29, 236, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'extrag', '0.00', NULL),
(748, 29, 236, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'mediano', '0.00', NULL),
(749, 29, 236, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'pequeno', '0.00', NULL),
(750, 29, 236, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'porcion', '0.00', NULL),
(751, 29, 235, '2025-01-27 19:44:38', '2025-01-27 19:44:38', 'unico', '0.00', NULL),
(752, 29, 235, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'grande', '0.00', NULL),
(753, 29, 235, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'extrag', '0.00', NULL),
(754, 29, 235, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'mediano', '0.00', NULL),
(755, 29, 235, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'pequeno', '0.00', NULL),
(756, 29, 235, '2025-01-27 19:44:38', '2025-02-01 13:40:49', 'porcion', '0.00', NULL),
(757, 68, 204, '2025-01-27 19:47:39', '2025-01-27 19:47:39', 'unico', '0.00', NULL),
(758, 68, 204, '2025-01-27 19:47:39', '2025-02-01 13:41:33', 'grande', '0.00', NULL),
(759, 68, 204, '2025-01-27 19:47:39', '2025-02-01 13:41:33', 'extrag', '0.00', NULL),
(760, 68, 204, '2025-01-27 19:47:39', '2025-02-01 13:41:33', 'mediano', '0.00', NULL),
(761, 68, 204, '2025-01-27 19:47:39', '2025-02-01 13:41:33', 'pequeno', '0.00', NULL),
(762, 68, 204, '2025-01-27 19:47:39', '2025-02-01 13:41:33', 'porcion', '0.00', NULL),
(763, 68, 147, '2025-01-27 19:47:39', '2025-01-27 19:47:39', 'unico', '0.00', NULL),
(764, 68, 147, '2025-01-27 19:47:39', '2025-02-01 13:41:33', 'grande', '0.00', NULL),
(765, 68, 147, '2025-01-27 19:47:39', '2025-02-01 13:41:33', 'extrag', '0.00', NULL),
(766, 68, 147, '2025-01-27 19:47:39', '2025-02-01 13:41:33', 'mediano', '0.00', NULL),
(767, 68, 147, '2025-01-27 19:47:39', '2025-02-01 13:41:33', 'pequeno', '0.00', NULL),
(768, 68, 147, '2025-01-27 19:47:39', '2025-02-01 13:41:33', 'porcion', '0.00', NULL),
(769, 68, 236, '2025-01-27 19:47:39', '2025-01-27 19:47:39', 'unico', '0.00', NULL),
(770, 68, 236, '2025-01-27 19:47:39', '2025-02-01 13:41:33', 'grande', '0.00', NULL),
(771, 68, 236, '2025-01-27 19:47:39', '2025-02-01 13:41:33', 'extrag', '0.00', NULL),
(772, 68, 236, '2025-01-27 19:47:39', '2025-02-01 13:41:33', 'mediano', '0.00', NULL),
(773, 68, 236, '2025-01-27 19:47:39', '2025-02-01 13:41:33', 'pequeno', '0.00', NULL),
(774, 68, 236, '2025-01-27 19:47:39', '2025-02-01 13:41:33', 'porcion', '0.00', NULL),
(775, 30, 204, '2025-01-27 19:48:15', '2025-01-27 19:48:15', 'unico', '0.00', NULL),
(776, 30, 204, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'grande', '0.00', NULL),
(777, 30, 204, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'extrag', '0.00', NULL),
(778, 30, 204, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'mediano', '0.00', NULL),
(779, 30, 204, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'pequeno', '0.00', NULL),
(780, 30, 204, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'porcion', '0.00', NULL),
(781, 30, 244, '2025-01-27 19:48:15', '2025-01-27 19:48:15', 'unico', '0.00', NULL),
(782, 30, 244, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'grande', '0.00', NULL),
(783, 30, 244, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'extrag', '0.00', NULL),
(784, 30, 244, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'mediano', '0.00', NULL),
(785, 30, 244, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'pequeno', '0.00', NULL),
(786, 30, 244, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'porcion', '0.00', NULL),
(787, 30, 245, '2025-01-27 19:48:15', '2025-01-27 19:48:15', 'unico', '0.00', NULL),
(788, 30, 245, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'grande', '0.00', NULL),
(789, 30, 245, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'extrag', '0.00', NULL),
(790, 30, 245, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'mediano', '0.00', NULL),
(791, 30, 245, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'pequeno', '0.00', NULL),
(792, 30, 245, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'porcion', '0.00', NULL),
(793, 30, 147, '2025-01-27 19:48:15', '2025-01-27 19:48:15', 'unico', '0.00', NULL),
(794, 30, 147, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'grande', '0.00', NULL),
(795, 30, 147, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'extrag', '0.00', NULL),
(796, 30, 147, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'mediano', '0.00', NULL),
(797, 30, 147, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'pequeno', '0.00', NULL),
(798, 30, 147, '2025-01-27 19:48:15', '2025-02-01 13:41:15', 'porcion', '0.00', NULL),
(799, 30, 236, '2025-01-27 19:48:16', '2025-01-27 19:48:16', 'unico', '0.00', NULL),
(800, 30, 236, '2025-01-27 19:48:16', '2025-02-01 13:41:15', 'grande', '0.00', NULL),
(801, 30, 236, '2025-01-27 19:48:16', '2025-02-01 13:41:15', 'extrag', '0.00', NULL),
(802, 30, 236, '2025-01-27 19:48:16', '2025-02-01 13:41:15', 'mediano', '0.00', NULL),
(803, 30, 236, '2025-01-27 19:48:16', '2025-02-01 13:41:15', 'pequeno', '0.00', NULL),
(804, 30, 236, '2025-01-27 19:48:16', '2025-02-01 13:41:15', 'porcion', '0.00', NULL),
(805, 30, 235, '2025-01-27 19:48:16', '2025-01-27 19:48:16', 'unico', '0.00', NULL),
(806, 30, 235, '2025-01-27 19:48:16', '2025-02-01 13:41:15', 'grande', '0.00', NULL),
(807, 30, 235, '2025-01-27 19:48:16', '2025-02-01 13:41:15', 'extrag', '0.00', NULL),
(808, 30, 235, '2025-01-27 19:48:16', '2025-02-01 13:41:15', 'mediano', '0.00', NULL),
(809, 30, 235, '2025-01-27 19:48:16', '2025-02-01 13:41:15', 'pequeno', '0.00', NULL),
(810, 30, 235, '2025-01-27 19:48:16', '2025-02-01 13:41:15', 'porcion', '0.00', NULL),
(811, 31, 204, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'unico', '1.00', NULL),
(812, 31, 204, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'grande', '0.00', NULL),
(813, 31, 204, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'extrag', '0.00', NULL),
(814, 31, 204, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'mediano', '0.00', NULL),
(815, 31, 204, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'pequeno', '0.00', NULL),
(816, 31, 204, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'porcion', '0.00', NULL),
(817, 31, 244, '2025-01-27 19:49:34', '2025-01-27 19:49:34', 'unico', '0.00', NULL),
(818, 31, 244, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'grande', '0.00', NULL),
(819, 31, 244, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'extrag', '0.00', NULL),
(820, 31, 244, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'mediano', '0.00', NULL),
(821, 31, 244, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'pequeno', '0.00', NULL),
(822, 31, 244, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'porcion', '0.00', NULL),
(823, 31, 245, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'unico', '1.00', NULL),
(824, 31, 245, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'grande', '0.00', NULL),
(825, 31, 245, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'extrag', '0.00', NULL),
(826, 31, 245, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'mediano', '0.00', NULL),
(827, 31, 245, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'pequeno', '0.00', NULL),
(828, 31, 245, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'porcion', '0.00', NULL),
(829, 31, 147, '2025-01-27 19:49:34', '2025-01-27 19:49:34', 'unico', '0.00', NULL),
(830, 31, 147, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'grande', '0.00', NULL);
INSERT INTO `gapp_producto_ingrediente` (`id`, `producto_id`, `ingrediente_id`, `created_at`, `updated_at`, `tamano`, `cantidad`, `visible`) VALUES
(831, 31, 147, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'extrag', '0.00', NULL),
(832, 31, 147, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'mediano', '0.00', NULL),
(833, 31, 147, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'pequeno', '0.00', NULL),
(834, 31, 147, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'porcion', '0.00', NULL),
(835, 31, 105, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'unico', '3.00', NULL),
(836, 31, 105, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'grande', '0.00', NULL),
(837, 31, 105, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'extrag', '0.00', NULL),
(838, 31, 105, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'mediano', '0.00', NULL),
(839, 31, 105, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'pequeno', '0.00', NULL),
(840, 31, 105, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'porcion', '0.00', NULL),
(841, 31, 28, '2025-01-27 19:49:34', '2025-01-27 19:49:34', 'unico', '0.00', NULL),
(842, 31, 28, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'grande', '0.00', NULL),
(843, 31, 28, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'extrag', '0.00', NULL),
(844, 31, 28, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'mediano', '0.00', NULL),
(845, 31, 28, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'pequeno', '0.00', NULL),
(846, 31, 28, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'porcion', '0.00', NULL),
(847, 31, 236, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'unico', '1.00', NULL),
(848, 31, 236, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'grande', '0.00', NULL),
(849, 31, 236, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'extrag', '0.00', NULL),
(850, 31, 236, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'mediano', '0.00', NULL),
(851, 31, 236, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'pequeno', '0.00', NULL),
(852, 31, 236, '2025-01-27 19:49:34', '2025-03-02 22:31:15', 'porcion', '0.00', NULL),
(853, 33, 204, '2025-01-27 19:51:09', '2025-01-27 19:51:09', 'unico', '0.00', NULL),
(854, 33, 204, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'grande', '0.00', NULL),
(855, 33, 204, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'extrag', '0.00', NULL),
(856, 33, 204, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'mediano', '0.00', NULL),
(857, 33, 204, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'pequeno', '0.00', NULL),
(858, 33, 204, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'porcion', '0.00', NULL),
(859, 33, 244, '2025-01-27 19:51:09', '2025-01-27 19:51:09', 'unico', '0.00', NULL),
(860, 33, 244, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'grande', '0.00', NULL),
(861, 33, 244, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'extrag', '0.00', NULL),
(862, 33, 244, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'mediano', '0.00', NULL),
(863, 33, 244, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'pequeno', '0.00', NULL),
(864, 33, 244, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'porcion', '0.00', NULL),
(865, 33, 245, '2025-01-27 19:51:09', '2025-01-27 19:51:09', 'unico', '0.00', NULL),
(866, 33, 245, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'grande', '0.00', NULL),
(867, 33, 245, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'extrag', '0.00', NULL),
(868, 33, 245, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'mediano', '0.00', NULL),
(869, 33, 245, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'pequeno', '0.00', NULL),
(870, 33, 245, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'porcion', '0.00', NULL),
(871, 33, 147, '2025-01-27 19:51:09', '2025-01-27 19:51:09', 'unico', '0.00', NULL),
(872, 33, 147, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'grande', '0.00', NULL),
(873, 33, 147, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'extrag', '0.00', NULL),
(874, 33, 147, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'mediano', '0.00', NULL),
(875, 33, 147, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'pequeno', '0.00', NULL),
(876, 33, 147, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'porcion', '0.00', NULL),
(877, 33, 105, '2025-01-27 19:51:09', '2025-01-27 19:51:09', 'unico', '0.00', NULL),
(878, 33, 105, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'grande', '0.00', NULL),
(879, 33, 105, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'extrag', '0.00', NULL),
(880, 33, 105, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'mediano', '0.00', NULL),
(881, 33, 105, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'pequeno', '0.00', NULL),
(882, 33, 105, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'porcion', '0.00', NULL),
(883, 33, 28, '2025-01-27 19:51:09', '2025-01-27 19:51:09', 'unico', '0.00', NULL),
(884, 33, 28, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'grande', '0.00', NULL),
(885, 33, 28, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'extrag', '0.00', NULL),
(886, 33, 28, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'mediano', '0.00', NULL),
(887, 33, 28, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'pequeno', '0.00', NULL),
(888, 33, 28, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'porcion', '0.00', NULL),
(889, 33, 236, '2025-01-27 19:51:09', '2025-01-27 19:51:09', 'unico', '0.00', NULL),
(890, 33, 236, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'grande', '0.00', NULL),
(891, 33, 236, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'extrag', '0.00', NULL),
(892, 33, 236, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'mediano', '0.00', NULL),
(893, 33, 236, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'pequeno', '0.00', NULL),
(894, 33, 236, '2025-01-27 19:51:09', '2025-02-01 13:44:16', 'porcion', '0.00', NULL),
(895, 34, 78, '2025-01-27 19:55:14', '2025-01-27 19:55:14', 'unico', '0.00', NULL),
(896, 34, 78, '2025-01-27 19:55:14', '2025-02-01 13:52:04', 'grande', '0.00', NULL),
(897, 34, 78, '2025-01-27 19:55:14', '2025-02-01 13:52:04', 'extrag', '0.00', NULL),
(898, 34, 78, '2025-01-27 19:55:14', '2025-02-01 13:52:04', 'mediano', '0.00', NULL),
(899, 34, 78, '2025-01-27 19:55:14', '2025-02-01 13:52:04', 'pequeno', '0.00', NULL),
(900, 34, 78, '2025-01-27 19:55:14', '2025-02-01 13:52:04', 'porcion', '0.00', NULL),
(901, 34, 246, '2025-01-27 19:58:38', '2025-01-27 19:58:38', 'unico', '0.00', NULL),
(902, 34, 246, '2025-01-27 19:58:38', '2025-02-01 13:52:04', 'grande', '0.00', NULL),
(903, 34, 246, '2025-01-27 19:58:38', '2025-02-01 13:52:04', 'extrag', '0.00', NULL),
(904, 34, 246, '2025-01-27 19:58:38', '2025-02-01 13:52:04', 'mediano', '0.00', NULL),
(905, 34, 246, '2025-01-27 19:58:38', '2025-02-01 13:52:04', 'pequeno', '0.00', NULL),
(906, 34, 246, '2025-01-27 19:58:38', '2025-02-01 13:52:04', 'porcion', '0.00', NULL),
(925, 34, 160, '2025-01-27 20:23:36', '2025-01-27 20:23:36', 'unico', '0.00', NULL),
(926, 34, 160, '2025-01-27 20:23:36', '2025-02-01 13:52:04', 'grande', '0.00', NULL),
(927, 34, 160, '2025-01-27 20:23:36', '2025-02-01 13:52:04', 'extrag', '0.00', NULL),
(928, 34, 160, '2025-01-27 20:23:36', '2025-02-01 13:52:04', 'mediano', '0.00', NULL),
(929, 34, 160, '2025-01-27 20:23:36', '2025-02-01 13:52:04', 'pequeno', '0.00', NULL),
(930, 34, 160, '2025-01-27 20:23:36', '2025-02-01 13:52:04', 'porcion', '0.00', NULL),
(931, 69, 247, '2025-01-27 20:25:52', '2025-01-27 20:25:52', 'unico', '0.00', NULL),
(932, 69, 247, '2025-01-27 20:25:52', '2025-01-27 20:25:52', 'grande', '0.00', NULL),
(933, 69, 247, '2025-01-27 20:25:52', '2025-01-27 20:25:52', 'extrag', '0.00', NULL),
(934, 69, 247, '2025-01-27 20:25:52', '2025-01-27 20:25:52', 'mediano', '0.00', NULL),
(935, 69, 247, '2025-01-27 20:25:52', '2025-01-27 20:25:52', 'pequeno', '0.00', NULL),
(936, 69, 247, '2025-01-27 20:25:52', '2025-01-27 20:25:52', 'porcion', '0.00', NULL),
(937, 34, 37, '2025-01-27 20:33:34', '2025-01-27 20:33:34', 'unico', '0.00', NULL),
(938, 34, 37, '2025-01-27 20:33:34', '2025-02-01 13:52:04', 'grande', '0.00', NULL),
(939, 34, 37, '2025-01-27 20:33:34', '2025-02-01 13:52:04', 'extrag', '0.00', NULL),
(940, 34, 37, '2025-01-27 20:33:34', '2025-02-01 13:52:04', 'mediano', '0.00', NULL),
(941, 34, 37, '2025-01-27 20:33:34', '2025-02-01 13:52:04', 'pequeno', '0.00', NULL),
(942, 34, 37, '2025-01-27 20:33:34', '2025-02-01 13:52:04', 'porcion', '0.00', NULL),
(943, 36, 78, '2025-01-27 20:44:33', '2025-01-27 20:44:33', 'unico', '0.00', NULL),
(944, 36, 78, '2025-01-27 20:44:33', '2025-01-29 20:24:03', 'grande', '0.00', NULL),
(945, 36, 78, '2025-01-27 20:44:33', '2025-01-29 20:24:03', 'extrag', '0.00', NULL),
(946, 36, 78, '2025-01-27 20:44:33', '2025-01-29 20:24:03', 'mediano', '0.00', NULL),
(947, 36, 78, '2025-01-27 20:44:33', '2025-01-29 20:24:03', 'pequeno', '0.00', NULL),
(948, 36, 78, '2025-01-27 20:44:33', '2025-01-29 20:24:03', 'porcion', '0.00', NULL),
(949, 37, 78, '2025-01-27 20:48:48', '2025-01-27 20:48:48', 'unico', '0.00', NULL),
(950, 37, 78, '2025-01-27 20:48:48', '2025-01-29 20:24:11', 'grande', '0.00', NULL),
(951, 37, 78, '2025-01-27 20:48:48', '2025-01-29 20:24:11', 'extrag', '0.00', NULL),
(952, 37, 78, '2025-01-27 20:48:48', '2025-01-29 20:24:11', 'mediano', '0.00', NULL),
(953, 37, 78, '2025-01-27 20:48:48', '2025-01-29 20:24:11', 'pequeno', '0.00', NULL),
(954, 37, 78, '2025-01-27 20:48:48', '2025-01-29 20:24:11', 'porcion', '0.00', NULL),
(955, 38, 244, '2025-01-27 20:52:44', '2025-01-27 20:52:44', 'unico', '0.00', NULL),
(956, 38, 244, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'grande', '0.00', NULL),
(957, 38, 244, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'extrag', '0.00', NULL),
(958, 38, 244, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'mediano', '0.00', NULL),
(959, 38, 244, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'pequeno', '0.00', NULL),
(960, 38, 244, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'porcion', '0.00', NULL),
(961, 38, 147, '2025-01-27 20:52:44', '2025-01-27 20:52:44', 'unico', '0.00', NULL),
(962, 38, 147, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'grande', '0.00', NULL),
(963, 38, 147, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'extrag', '0.00', NULL),
(964, 38, 147, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'mediano', '0.00', NULL),
(965, 38, 147, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'pequeno', '0.00', NULL),
(966, 38, 147, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'porcion', '0.00', NULL),
(967, 38, 105, '2025-01-27 20:52:44', '2025-01-27 20:52:44', 'unico', '0.00', NULL),
(968, 38, 105, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'grande', '0.00', NULL),
(969, 38, 105, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'extrag', '0.00', NULL),
(970, 38, 105, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'mediano', '0.00', NULL),
(971, 38, 105, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'pequeno', '0.00', NULL),
(972, 38, 105, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'porcion', '0.00', NULL),
(973, 38, 28, '2025-01-27 20:52:44', '2025-01-27 20:52:44', 'unico', '0.00', NULL),
(974, 38, 28, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'grande', '0.00', NULL),
(975, 38, 28, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'extrag', '0.00', NULL),
(976, 38, 28, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'mediano', '0.00', NULL),
(977, 38, 28, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'pequeno', '0.00', NULL),
(978, 38, 28, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'porcion', '0.00', NULL),
(979, 38, 236, '2025-01-27 20:52:44', '2025-01-27 20:52:44', 'unico', '0.00', NULL),
(980, 38, 236, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'grande', '0.00', NULL),
(981, 38, 236, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'extrag', '0.00', NULL),
(982, 38, 236, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'mediano', '0.00', NULL),
(983, 38, 236, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'pequeno', '0.00', NULL),
(984, 38, 236, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'porcion', '0.00', NULL),
(985, 38, 235, '2025-01-27 20:52:44', '2025-01-27 20:52:44', 'unico', '0.00', NULL),
(986, 38, 235, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'grande', '0.00', NULL),
(987, 38, 235, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'extrag', '0.00', NULL),
(988, 38, 235, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'mediano', '0.00', NULL),
(989, 38, 235, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'pequeno', '0.00', NULL),
(990, 38, 235, '2025-01-27 20:52:44', '2025-02-01 13:50:52', 'porcion', '0.00', NULL),
(991, 39, 240, '2025-01-27 20:59:37', '2025-01-27 20:59:37', 'unico', '0.00', NULL),
(992, 39, 240, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'grande', '0.00', NULL),
(993, 39, 240, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'extrag', '0.00', NULL),
(994, 39, 240, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'mediano', '0.00', NULL),
(995, 39, 240, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'pequeno', '0.00', NULL),
(996, 39, 240, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'porcion', '0.00', NULL),
(997, 39, 219, '2025-01-27 20:59:37', '2025-01-27 20:59:37', 'unico', '0.00', NULL),
(998, 39, 219, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'grande', '0.00', NULL),
(999, 39, 219, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'extrag', '0.00', NULL),
(1000, 39, 219, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'mediano', '0.00', NULL),
(1001, 39, 219, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'pequeno', '0.00', NULL),
(1002, 39, 219, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'porcion', '0.00', NULL),
(1003, 39, 147, '2025-01-27 20:59:37', '2025-01-27 20:59:37', 'unico', '0.00', NULL),
(1004, 39, 147, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'grande', '0.00', NULL),
(1005, 39, 147, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'extrag', '0.00', NULL),
(1006, 39, 147, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'mediano', '0.00', NULL),
(1007, 39, 147, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'pequeno', '0.00', NULL),
(1008, 39, 147, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'porcion', '0.00', NULL),
(1009, 39, 249, '2025-01-27 20:59:37', '2025-01-27 20:59:37', 'unico', '0.00', NULL),
(1010, 39, 249, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'grande', '0.00', NULL),
(1011, 39, 249, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'extrag', '0.00', NULL),
(1012, 39, 249, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'mediano', '0.00', NULL),
(1013, 39, 249, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'pequeno', '0.00', NULL),
(1014, 39, 249, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'porcion', '0.00', NULL),
(1015, 39, 105, '2025-01-27 20:59:37', '2025-01-27 20:59:37', 'unico', '0.00', NULL),
(1016, 39, 105, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'grande', '0.00', NULL),
(1017, 39, 105, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'extrag', '0.00', NULL),
(1018, 39, 105, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'mediano', '0.00', NULL),
(1019, 39, 105, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'pequeno', '0.00', NULL),
(1020, 39, 105, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'porcion', '0.00', NULL),
(1021, 39, 28, '2025-01-27 20:59:37', '2025-01-27 20:59:37', 'unico', '0.00', NULL),
(1022, 39, 28, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'grande', '0.00', NULL),
(1023, 39, 28, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'extrag', '0.00', NULL),
(1024, 39, 28, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'mediano', '0.00', NULL),
(1025, 39, 28, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'pequeno', '0.00', NULL),
(1026, 39, 28, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'porcion', '0.00', NULL),
(1027, 39, 236, '2025-01-27 20:59:37', '2025-01-27 20:59:37', 'unico', '0.00', NULL),
(1028, 39, 236, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'grande', '0.00', NULL),
(1029, 39, 236, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'extrag', '0.00', NULL),
(1030, 39, 236, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'mediano', '0.00', NULL),
(1031, 39, 236, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'pequeno', '0.00', NULL),
(1032, 39, 236, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'porcion', '0.00', NULL),
(1033, 39, 73, '2025-01-27 20:59:37', '2025-01-27 20:59:37', 'unico', '0.00', NULL),
(1034, 39, 73, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'grande', '0.00', NULL),
(1035, 39, 73, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'extrag', '0.00', NULL),
(1036, 39, 73, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'mediano', '0.00', NULL),
(1037, 39, 73, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'pequeno', '0.00', NULL),
(1038, 39, 73, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'porcion', '0.00', NULL),
(1039, 39, 235, '2025-01-27 20:59:37', '2025-01-27 20:59:37', 'unico', '0.00', NULL),
(1040, 39, 235, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'grande', '0.00', NULL),
(1041, 39, 235, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'extrag', '0.00', NULL),
(1042, 39, 235, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'mediano', '0.00', NULL),
(1043, 39, 235, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'pequeno', '0.00', NULL),
(1044, 39, 235, '2025-01-27 20:59:37', '2025-02-01 15:07:32', 'porcion', '0.00', NULL),
(1045, 40, 204, '2025-01-27 21:02:34', '2025-01-27 21:02:34', 'unico', '0.00', NULL),
(1046, 40, 204, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'grande', '0.00', NULL),
(1047, 40, 204, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'extrag', '0.00', NULL),
(1048, 40, 204, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'mediano', '0.00', NULL),
(1049, 40, 204, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'pequeno', '0.00', NULL),
(1050, 40, 204, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'porcion', '0.00', NULL),
(1051, 40, 244, '2025-01-27 21:02:34', '2025-01-27 21:02:34', 'unico', '0.00', NULL),
(1052, 40, 244, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'grande', '0.00', NULL),
(1053, 40, 244, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'extrag', '0.00', NULL),
(1054, 40, 244, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'mediano', '0.00', NULL),
(1055, 40, 244, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'pequeno', '0.00', NULL),
(1056, 40, 244, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'porcion', '0.00', NULL),
(1057, 40, 219, '2025-01-27 21:02:34', '2025-01-27 21:02:34', 'unico', '0.00', NULL),
(1058, 40, 219, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'grande', '0.00', NULL),
(1059, 40, 219, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'extrag', '0.00', NULL),
(1060, 40, 219, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'mediano', '0.00', NULL),
(1061, 40, 219, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'pequeno', '0.00', NULL),
(1062, 40, 219, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'porcion', '0.00', NULL),
(1063, 40, 147, '2025-01-27 21:02:34', '2025-01-27 21:02:34', 'unico', '0.00', NULL),
(1064, 40, 147, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'grande', '0.00', NULL),
(1065, 40, 147, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'extrag', '0.00', NULL),
(1066, 40, 147, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'mediano', '0.00', NULL),
(1067, 40, 147, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'pequeno', '0.00', NULL),
(1068, 40, 147, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'porcion', '0.00', NULL),
(1069, 40, 249, '2025-01-27 21:02:34', '2025-01-27 21:02:34', 'unico', '0.00', NULL),
(1070, 40, 249, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'grande', '0.00', NULL),
(1071, 40, 249, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'extrag', '0.00', NULL),
(1072, 40, 249, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'mediano', '0.00', NULL),
(1073, 40, 249, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'pequeno', '0.00', NULL),
(1074, 40, 249, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'porcion', '0.00', NULL),
(1075, 40, 28, '2025-01-27 21:02:34', '2025-01-27 21:02:34', 'unico', '0.00', NULL),
(1076, 40, 28, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'grande', '0.00', NULL),
(1077, 40, 28, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'extrag', '0.00', NULL),
(1078, 40, 28, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'mediano', '0.00', NULL),
(1079, 40, 28, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'pequeno', '0.00', NULL),
(1080, 40, 28, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'porcion', '0.00', NULL),
(1081, 40, 236, '2025-01-27 21:02:34', '2025-01-27 21:02:34', 'unico', '0.00', NULL),
(1082, 40, 236, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'grande', '0.00', NULL),
(1083, 40, 236, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'extrag', '0.00', NULL),
(1084, 40, 236, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'mediano', '0.00', NULL),
(1085, 40, 236, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'pequeno', '0.00', NULL),
(1086, 40, 236, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'porcion', '0.00', NULL),
(1087, 40, 73, '2025-01-27 21:02:34', '2025-01-27 21:02:34', 'unico', '0.00', NULL),
(1088, 40, 73, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'grande', '0.00', NULL),
(1089, 40, 73, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'extrag', '0.00', NULL),
(1090, 40, 73, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'mediano', '0.00', NULL),
(1091, 40, 73, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'pequeno', '0.00', NULL),
(1092, 40, 73, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'porcion', '0.00', NULL),
(1093, 40, 235, '2025-01-27 21:02:34', '2025-01-27 21:02:34', 'unico', '0.00', NULL),
(1094, 40, 235, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'grande', '0.00', NULL),
(1095, 40, 235, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'extrag', '0.00', NULL),
(1096, 40, 235, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'mediano', '0.00', NULL),
(1097, 40, 235, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'pequeno', '0.00', NULL),
(1098, 40, 235, '2025-01-27 21:02:34', '2025-02-01 13:49:51', 'porcion', '0.00', NULL),
(1099, 70, 217, '2025-01-27 21:08:27', '2025-01-27 21:08:27', 'unico', '0.00', NULL),
(1100, 70, 217, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'grande', '0.00', NULL),
(1101, 70, 217, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'extrag', '0.00', NULL),
(1102, 70, 217, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'mediano', '0.00', NULL),
(1103, 70, 217, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'pequeno', '0.00', NULL),
(1104, 70, 217, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'porcion', '0.00', NULL),
(1105, 70, 160, '2025-01-27 21:08:27', '2025-01-27 21:08:27', 'unico', '0.00', NULL),
(1106, 70, 160, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'grande', '0.00', NULL),
(1107, 70, 160, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'extrag', '0.00', NULL),
(1108, 70, 160, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'mediano', '0.00', NULL),
(1109, 70, 160, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'pequeno', '0.00', NULL),
(1110, 70, 160, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'porcion', '0.00', NULL),
(1111, 70, 112, '2025-01-27 21:08:27', '2025-01-27 21:08:27', 'unico', '0.00', NULL),
(1112, 70, 112, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'grande', '0.00', NULL),
(1113, 70, 112, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'extrag', '0.00', NULL),
(1114, 70, 112, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'mediano', '0.00', NULL),
(1115, 70, 112, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'pequeno', '0.00', NULL),
(1116, 70, 112, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'porcion', '0.00', NULL),
(1117, 70, 24, '2025-01-27 21:08:27', '2025-01-27 21:08:27', 'unico', '0.00', NULL),
(1118, 70, 24, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'grande', '0.00', NULL),
(1119, 70, 24, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'extrag', '0.00', NULL),
(1120, 70, 24, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'mediano', '0.00', NULL),
(1121, 70, 24, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'pequeno', '0.00', NULL),
(1122, 70, 24, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'porcion', '0.00', NULL),
(1123, 70, 28, '2025-01-27 21:08:27', '2025-01-27 21:08:27', 'unico', '0.00', NULL),
(1124, 70, 28, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'grande', '0.00', NULL),
(1125, 70, 28, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'extrag', '0.00', NULL),
(1126, 70, 28, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'mediano', '0.00', NULL),
(1127, 70, 28, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'pequeno', '0.00', NULL),
(1128, 70, 28, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'porcion', '0.00', NULL),
(1129, 70, 73, '2025-01-27 21:08:27', '2025-01-27 21:08:27', 'unico', '0.00', NULL),
(1130, 70, 73, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'grande', '0.00', NULL),
(1131, 70, 73, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'extrag', '0.00', NULL),
(1132, 70, 73, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'mediano', '0.00', NULL),
(1133, 70, 73, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'pequeno', '0.00', NULL),
(1134, 70, 73, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'porcion', '0.00', NULL),
(1135, 70, 19, '2025-01-27 21:08:27', '2025-01-27 21:08:27', 'unico', '0.00', NULL),
(1136, 70, 19, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'grande', '0.00', NULL),
(1137, 70, 19, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'extrag', '0.00', NULL),
(1138, 70, 19, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'mediano', '0.00', NULL),
(1139, 70, 19, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'pequeno', '0.00', NULL),
(1140, 70, 19, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'porcion', '0.00', NULL),
(1141, 70, 31, '2025-01-27 21:08:27', '2025-01-27 21:08:27', 'unico', '0.00', NULL),
(1142, 70, 31, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'grande', '0.00', NULL),
(1143, 70, 31, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'extrag', '0.00', NULL),
(1144, 70, 31, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'mediano', '0.00', NULL),
(1145, 70, 31, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'pequeno', '0.00', NULL),
(1146, 70, 31, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'porcion', '0.00', NULL),
(1147, 70, 32, '2025-01-27 21:08:27', '2025-01-27 21:08:27', 'unico', '0.00', NULL),
(1148, 70, 32, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'grande', '0.00', NULL),
(1149, 70, 32, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'extrag', '0.00', NULL),
(1150, 70, 32, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'mediano', '0.00', NULL),
(1151, 70, 32, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'pequeno', '0.00', NULL),
(1152, 70, 32, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'porcion', '0.00', NULL),
(1153, 70, 33, '2025-01-27 21:08:27', '2025-01-27 21:08:27', 'unico', '0.00', NULL),
(1154, 70, 33, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'grande', '0.00', NULL),
(1155, 70, 33, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'extrag', '0.00', NULL),
(1156, 70, 33, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'mediano', '0.00', NULL),
(1157, 70, 33, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'pequeno', '0.00', NULL),
(1158, 70, 33, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'porcion', '0.00', NULL),
(1159, 70, 38, '2025-01-27 21:08:27', '2025-01-27 21:08:27', 'unico', '0.00', NULL),
(1160, 70, 38, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'grande', '0.00', NULL),
(1161, 70, 38, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'extrag', '0.00', NULL),
(1162, 70, 38, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'mediano', '0.00', NULL),
(1163, 70, 38, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'pequeno', '0.00', NULL),
(1164, 70, 38, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'porcion', '0.00', NULL),
(1165, 70, 119, '2025-01-27 21:08:27', '2025-01-27 21:08:27', 'unico', '0.00', NULL),
(1166, 70, 119, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'grande', '0.00', NULL),
(1167, 70, 119, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'extrag', '0.00', NULL),
(1168, 70, 119, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'mediano', '0.00', NULL),
(1169, 70, 119, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'pequeno', '0.00', NULL),
(1170, 70, 119, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'porcion', '0.00', NULL),
(1171, 70, 12, '2025-01-27 21:08:27', '2025-01-27 21:08:27', 'unico', '0.00', NULL),
(1172, 70, 12, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'grande', '0.00', NULL),
(1173, 70, 12, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'extrag', '0.00', NULL),
(1174, 70, 12, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'mediano', '0.00', NULL),
(1175, 70, 12, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'pequeno', '0.00', NULL),
(1176, 70, 12, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'porcion', '0.00', NULL),
(1177, 70, 83, '2025-01-27 21:08:27', '2025-01-27 21:08:27', 'unico', '0.00', NULL),
(1178, 70, 83, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'grande', '0.00', NULL),
(1179, 70, 83, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'extrag', '0.00', NULL),
(1180, 70, 83, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'mediano', '0.00', NULL),
(1181, 70, 83, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'pequeno', '0.00', NULL),
(1182, 70, 83, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'porcion', '0.00', NULL),
(1183, 70, 37, '2025-01-27 21:08:27', '2025-01-27 21:08:27', 'unico', '0.00', NULL),
(1184, 70, 37, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'grande', '0.00', NULL),
(1185, 70, 37, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'extrag', '0.00', NULL),
(1186, 70, 37, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'mediano', '0.00', NULL),
(1187, 70, 37, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'pequeno', '0.00', NULL),
(1188, 70, 37, '2025-01-27 21:08:27', '2025-04-13 17:00:50', 'porcion', '0.00', NULL),
(1189, 71, 217, '2025-01-27 21:11:43', '2025-01-27 21:11:43', 'unico', '0.00', NULL),
(1190, 71, 217, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'grande', '0.00', NULL),
(1191, 71, 217, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'extrag', '0.00', NULL),
(1192, 71, 217, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'mediano', '0.00', NULL),
(1193, 71, 217, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'pequeno', '0.00', NULL),
(1194, 71, 217, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'porcion', '0.00', NULL),
(1195, 71, 160, '2025-01-27 21:11:43', '2025-01-27 21:11:43', 'unico', '0.00', NULL),
(1196, 71, 160, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'grande', '0.00', NULL),
(1197, 71, 160, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'extrag', '0.00', NULL),
(1198, 71, 160, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'mediano', '0.00', NULL),
(1199, 71, 160, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'pequeno', '0.00', NULL),
(1200, 71, 160, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'porcion', '0.00', NULL),
(1201, 71, 112, '2025-01-27 21:11:43', '2025-01-27 21:11:43', 'unico', '0.00', NULL),
(1202, 71, 112, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'grande', '0.00', NULL),
(1203, 71, 112, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'extrag', '0.00', NULL),
(1204, 71, 112, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'mediano', '0.00', NULL),
(1205, 71, 112, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'pequeno', '0.00', NULL),
(1206, 71, 112, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'porcion', '0.00', NULL),
(1207, 71, 24, '2025-01-27 21:11:43', '2025-01-27 21:11:43', 'unico', '0.00', NULL),
(1208, 71, 24, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'grande', '0.00', NULL),
(1209, 71, 24, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'extrag', '0.00', NULL),
(1210, 71, 24, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'mediano', '0.00', NULL),
(1211, 71, 24, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'pequeno', '0.00', NULL),
(1212, 71, 24, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'porcion', '0.00', NULL),
(1213, 71, 28, '2025-01-27 21:11:43', '2025-01-27 21:11:43', 'unico', '0.00', NULL),
(1214, 71, 28, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'grande', '0.00', NULL),
(1215, 71, 28, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'extrag', '0.00', NULL),
(1216, 71, 28, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'mediano', '0.00', NULL),
(1217, 71, 28, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'pequeno', '0.00', NULL),
(1218, 71, 28, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'porcion', '0.00', NULL),
(1219, 71, 73, '2025-01-27 21:11:43', '2025-01-27 21:11:43', 'unico', '0.00', NULL),
(1220, 71, 73, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'grande', '0.00', NULL),
(1221, 71, 73, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'extrag', '0.00', NULL),
(1222, 71, 73, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'mediano', '0.00', NULL),
(1223, 71, 73, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'pequeno', '0.00', NULL),
(1224, 71, 73, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'porcion', '0.00', NULL),
(1225, 71, 19, '2025-01-27 21:11:43', '2025-01-27 21:11:43', 'unico', '0.00', NULL),
(1226, 71, 19, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'grande', '0.00', NULL),
(1227, 71, 19, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'extrag', '0.00', NULL),
(1228, 71, 19, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'mediano', '0.00', NULL),
(1229, 71, 19, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'pequeno', '0.00', NULL),
(1230, 71, 19, '2025-01-27 21:11:43', '2025-04-13 17:01:18', 'porcion', '0.00', NULL),
(1231, 71, 31, '2025-01-27 21:11:43', '2025-01-27 21:11:43', 'unico', '0.00', NULL),
(1232, 71, 31, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'grande', '0.00', NULL),
(1233, 71, 31, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'extrag', '0.00', NULL),
(1234, 71, 31, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'mediano', '0.00', NULL),
(1235, 71, 31, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'pequeno', '0.00', NULL),
(1236, 71, 31, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'porcion', '0.00', NULL),
(1237, 71, 32, '2025-01-27 21:11:44', '2025-01-27 21:11:44', 'unico', '0.00', NULL),
(1238, 71, 32, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'grande', '0.00', NULL),
(1239, 71, 32, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'extrag', '0.00', NULL),
(1240, 71, 32, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'mediano', '0.00', NULL),
(1241, 71, 32, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'pequeno', '0.00', NULL),
(1242, 71, 32, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'porcion', '0.00', NULL),
(1243, 71, 33, '2025-01-27 21:11:44', '2025-01-27 21:11:44', 'unico', '0.00', NULL),
(1244, 71, 33, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'grande', '0.00', NULL),
(1245, 71, 33, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'extrag', '0.00', NULL),
(1246, 71, 33, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'mediano', '0.00', NULL),
(1247, 71, 33, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'pequeno', '0.00', NULL),
(1248, 71, 33, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'porcion', '0.00', NULL),
(1249, 71, 38, '2025-01-27 21:11:44', '2025-01-27 21:11:44', 'unico', '0.00', NULL),
(1250, 71, 38, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'grande', '0.00', NULL),
(1251, 71, 38, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'extrag', '0.00', NULL),
(1252, 71, 38, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'mediano', '0.00', NULL),
(1253, 71, 38, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'pequeno', '0.00', NULL),
(1254, 71, 38, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'porcion', '0.00', NULL),
(1255, 71, 119, '2025-01-27 21:11:44', '2025-01-27 21:11:44', 'unico', '0.00', NULL),
(1256, 71, 119, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'grande', '0.00', NULL),
(1257, 71, 119, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'extrag', '0.00', NULL),
(1258, 71, 119, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'mediano', '0.00', NULL),
(1259, 71, 119, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'pequeno', '0.00', NULL),
(1260, 71, 119, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'porcion', '0.00', NULL),
(1261, 71, 12, '2025-01-27 21:11:44', '2025-01-27 21:11:44', 'unico', '0.00', NULL),
(1262, 71, 12, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'grande', '0.00', NULL),
(1263, 71, 12, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'extrag', '0.00', NULL),
(1264, 71, 12, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'mediano', '0.00', NULL),
(1265, 71, 12, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'pequeno', '0.00', NULL),
(1266, 71, 12, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'porcion', '0.00', NULL),
(1267, 71, 83, '2025-01-27 21:11:44', '2025-01-27 21:11:44', 'unico', '0.00', NULL),
(1268, 71, 83, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'grande', '0.00', NULL),
(1269, 71, 83, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'extrag', '0.00', NULL),
(1270, 71, 83, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'mediano', '0.00', NULL),
(1271, 71, 83, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'pequeno', '0.00', NULL),
(1272, 71, 83, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'porcion', '0.00', NULL),
(1273, 71, 37, '2025-01-27 21:11:44', '2025-01-27 21:11:44', 'unico', '0.00', NULL),
(1274, 71, 37, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'grande', '0.00', NULL),
(1275, 71, 37, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'extrag', '0.00', NULL),
(1276, 71, 37, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'mediano', '0.00', NULL),
(1277, 71, 37, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'pequeno', '0.00', NULL),
(1278, 71, 37, '2025-01-27 21:11:44', '2025-04-13 17:01:18', 'porcion', '0.00', NULL),
(1279, 72, 217, '2025-01-27 21:14:15', '2025-01-27 21:14:15', 'unico', '0.00', NULL),
(1280, 72, 217, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'grande', '0.00', NULL),
(1281, 72, 217, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'extrag', '0.00', NULL),
(1282, 72, 217, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'mediano', '0.00', NULL),
(1283, 72, 217, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'pequeno', '0.00', NULL),
(1284, 72, 217, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'porcion', '0.00', NULL),
(1285, 72, 160, '2025-01-27 21:14:15', '2025-01-27 21:14:15', 'unico', '0.00', NULL),
(1286, 72, 160, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'grande', '0.00', NULL),
(1287, 72, 160, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'extrag', '0.00', NULL),
(1288, 72, 160, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'mediano', '0.00', NULL),
(1289, 72, 160, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'pequeno', '0.00', NULL),
(1290, 72, 160, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'porcion', '0.00', NULL),
(1291, 72, 112, '2025-01-27 21:14:15', '2025-01-27 21:14:15', 'unico', '0.00', NULL),
(1292, 72, 112, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'grande', '0.00', NULL),
(1293, 72, 112, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'extrag', '0.00', NULL),
(1294, 72, 112, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'mediano', '0.00', NULL),
(1295, 72, 112, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'pequeno', '0.00', NULL),
(1296, 72, 112, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'porcion', '0.00', NULL),
(1297, 72, 24, '2025-01-27 21:14:15', '2025-01-27 21:14:15', 'unico', '0.00', NULL),
(1298, 72, 24, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'grande', '0.00', NULL),
(1299, 72, 24, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'extrag', '0.00', NULL),
(1300, 72, 24, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'mediano', '0.00', NULL),
(1301, 72, 24, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'pequeno', '0.00', NULL),
(1302, 72, 24, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'porcion', '0.00', NULL),
(1303, 72, 28, '2025-01-27 21:14:15', '2025-01-27 21:14:15', 'unico', '0.00', NULL),
(1304, 72, 28, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'grande', '0.00', NULL),
(1305, 72, 28, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'extrag', '0.00', NULL),
(1306, 72, 28, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'mediano', '0.00', NULL),
(1307, 72, 28, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'pequeno', '0.00', NULL),
(1308, 72, 28, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'porcion', '0.00', NULL),
(1309, 72, 19, '2025-01-27 21:14:15', '2025-01-27 21:14:15', 'unico', '0.00', NULL),
(1310, 72, 19, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'grande', '0.00', NULL),
(1311, 72, 19, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'extrag', '0.00', NULL),
(1312, 72, 19, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'mediano', '0.00', NULL),
(1313, 72, 19, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'pequeno', '0.00', NULL),
(1314, 72, 19, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'porcion', '0.00', NULL),
(1315, 72, 31, '2025-01-27 21:14:15', '2025-01-27 21:14:15', 'unico', '0.00', NULL),
(1316, 72, 31, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'grande', '0.00', NULL),
(1317, 72, 31, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'extrag', '0.00', NULL),
(1318, 72, 31, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'mediano', '0.00', NULL),
(1319, 72, 31, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'pequeno', '0.00', NULL),
(1320, 72, 31, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'porcion', '0.00', NULL),
(1321, 72, 32, '2025-01-27 21:14:15', '2025-01-27 21:14:15', 'unico', '0.00', NULL),
(1322, 72, 32, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'grande', '0.00', NULL),
(1323, 72, 32, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'extrag', '0.00', NULL),
(1324, 72, 32, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'mediano', '0.00', NULL),
(1325, 72, 32, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'pequeno', '0.00', NULL),
(1326, 72, 32, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'porcion', '0.00', NULL),
(1327, 72, 34, '2025-01-27 21:14:15', '2025-01-27 21:14:15', 'unico', '0.00', NULL),
(1328, 72, 34, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'grande', '0.00', NULL),
(1329, 72, 34, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'extrag', '0.00', NULL),
(1330, 72, 34, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'mediano', '0.00', NULL),
(1331, 72, 34, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'pequeno', '0.00', NULL),
(1332, 72, 34, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'porcion', '0.00', NULL),
(1333, 72, 33, '2025-01-27 21:14:15', '2025-01-27 21:14:15', 'unico', '0.00', NULL),
(1334, 72, 33, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'grande', '0.00', NULL),
(1335, 72, 33, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'extrag', '0.00', NULL),
(1336, 72, 33, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'mediano', '0.00', NULL),
(1337, 72, 33, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'pequeno', '0.00', NULL),
(1338, 72, 33, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'porcion', '0.00', NULL),
(1339, 72, 38, '2025-01-27 21:14:15', '2025-01-27 21:14:15', 'unico', '0.00', NULL),
(1340, 72, 38, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'grande', '0.00', NULL),
(1341, 72, 38, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'extrag', '0.00', NULL),
(1342, 72, 38, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'mediano', '0.00', NULL),
(1343, 72, 38, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'pequeno', '0.00', NULL),
(1344, 72, 38, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'porcion', '0.00', NULL),
(1345, 72, 12, '2025-01-27 21:14:15', '2025-01-27 21:14:15', 'unico', '0.00', NULL),
(1346, 72, 12, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'grande', '0.00', NULL),
(1347, 72, 12, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'extrag', '0.00', NULL),
(1348, 72, 12, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'mediano', '0.00', NULL),
(1349, 72, 12, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'pequeno', '0.00', NULL),
(1350, 72, 12, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'porcion', '0.00', NULL),
(1351, 72, 83, '2025-01-27 21:14:15', '2025-01-27 21:14:15', 'unico', '0.00', NULL),
(1352, 72, 83, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'grande', '0.00', NULL),
(1353, 72, 83, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'extrag', '0.00', NULL),
(1354, 72, 83, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'mediano', '0.00', NULL),
(1355, 72, 83, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'pequeno', '0.00', NULL),
(1356, 72, 83, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'porcion', '0.00', NULL),
(1357, 72, 37, '2025-01-27 21:14:15', '2025-01-27 21:14:15', 'unico', '0.00', NULL),
(1358, 72, 37, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'grande', '0.00', NULL),
(1359, 72, 37, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'extrag', '0.00', NULL),
(1360, 72, 37, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'mediano', '0.00', NULL),
(1361, 72, 37, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'pequeno', '0.00', NULL),
(1362, 72, 37, '2025-01-27 21:14:15', '2025-04-13 17:02:05', 'porcion', '0.00', NULL),
(1363, 73, 245, '2025-01-27 21:16:46', '2025-01-27 21:16:46', 'unico', '0.00', NULL),
(1364, 73, 245, '2025-01-27 21:16:46', '2025-03-02 22:59:34', 'grande', '0.00', NULL),
(1365, 73, 245, '2025-01-27 21:16:46', '2025-03-02 22:59:34', 'extrag', '0.00', NULL),
(1366, 73, 245, '2025-01-27 21:16:46', '2025-03-02 22:59:34', 'mediano', '0.00', NULL),
(1367, 73, 245, '2025-01-27 21:16:46', '2025-03-02 22:59:34', 'pequeno', '0.00', NULL),
(1368, 73, 245, '2025-01-27 21:16:46', '2025-03-02 22:59:34', 'porcion', '0.00', NULL),
(1369, 73, 147, '2025-01-27 21:16:46', '2025-01-27 21:16:46', 'unico', '0.00', NULL),
(1370, 73, 147, '2025-01-27 21:16:46', '2025-03-02 22:59:34', 'grande', '0.00', NULL),
(1371, 73, 147, '2025-01-27 21:16:46', '2025-03-02 22:59:34', 'extrag', '0.00', NULL),
(1372, 73, 147, '2025-01-27 21:16:46', '2025-03-02 22:59:35', 'mediano', '0.00', NULL),
(1373, 73, 147, '2025-01-27 21:16:46', '2025-03-02 22:59:35', 'pequeno', '0.00', NULL),
(1374, 73, 147, '2025-01-27 21:16:46', '2025-03-02 22:59:35', 'porcion', '0.00', NULL),
(1375, 73, 235, '2025-01-27 21:16:46', '2025-01-27 21:16:46', 'unico', '0.00', NULL),
(1376, 73, 235, '2025-01-27 21:16:46', '2025-03-02 22:59:35', 'grande', '0.00', NULL),
(1377, 73, 235, '2025-01-27 21:16:46', '2025-03-02 22:59:35', 'extrag', '0.00', NULL),
(1378, 73, 235, '2025-01-27 21:16:46', '2025-03-02 22:59:35', 'mediano', '0.00', NULL),
(1379, 73, 235, '2025-01-27 21:16:46', '2025-03-02 22:59:35', 'pequeno', '0.00', NULL),
(1380, 73, 235, '2025-01-27 21:16:46', '2025-03-02 22:59:35', 'porcion', '0.00', NULL),
(1381, 74, 245, '2025-01-27 21:19:52', '2025-01-27 21:19:52', 'unico', '0.00', NULL),
(1382, 74, 245, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'grande', '0.00', NULL),
(1383, 74, 245, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'extrag', '0.00', NULL),
(1384, 74, 245, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'mediano', '0.00', NULL),
(1385, 74, 245, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'pequeno', '0.00', NULL),
(1386, 74, 245, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'porcion', '0.00', NULL),
(1387, 74, 250, '2025-01-27 21:19:52', '2025-01-27 21:19:52', 'unico', '0.00', NULL),
(1388, 74, 250, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'grande', '0.00', NULL),
(1389, 74, 250, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'extrag', '0.00', NULL),
(1390, 74, 250, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'mediano', '0.00', NULL),
(1391, 74, 250, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'pequeno', '0.00', NULL),
(1392, 74, 250, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'porcion', '0.00', NULL),
(1393, 74, 147, '2025-01-27 21:19:52', '2025-01-27 21:19:52', 'unico', '0.00', NULL),
(1394, 74, 147, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'grande', '0.00', NULL),
(1395, 74, 147, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'extrag', '0.00', NULL),
(1396, 74, 147, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'mediano', '0.00', NULL),
(1397, 74, 147, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'pequeno', '0.00', NULL),
(1398, 74, 147, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'porcion', '0.00', NULL),
(1399, 74, 235, '2025-01-27 21:19:52', '2025-01-27 21:19:52', 'unico', '0.00', NULL),
(1400, 74, 235, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'grande', '0.00', NULL),
(1401, 74, 235, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'extrag', '0.00', NULL),
(1402, 74, 235, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'mediano', '0.00', NULL),
(1403, 74, 235, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'pequeno', '0.00', NULL),
(1404, 74, 235, '2025-01-27 21:19:52', '2025-03-02 23:00:42', 'porcion', '0.00', NULL),
(1405, 75, 245, '2025-01-27 21:21:56', '2025-01-27 21:21:56', 'unico', '0.00', NULL),
(1406, 75, 245, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'grande', '0.00', NULL),
(1407, 75, 245, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'extrag', '0.00', NULL),
(1408, 75, 245, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'mediano', '0.00', NULL),
(1409, 75, 245, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'pequeno', '0.00', NULL),
(1410, 75, 245, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'porcion', '0.00', NULL),
(1411, 75, 250, '2025-01-27 21:21:56', '2025-01-27 21:21:56', 'unico', '0.00', NULL),
(1412, 75, 250, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'grande', '0.00', NULL),
(1413, 75, 250, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'extrag', '0.00', NULL),
(1414, 75, 250, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'mediano', '0.00', NULL),
(1415, 75, 250, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'pequeno', '0.00', NULL),
(1416, 75, 250, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'porcion', '0.00', NULL),
(1417, 75, 147, '2025-01-27 21:21:56', '2025-01-27 21:21:56', 'unico', '0.00', NULL),
(1418, 75, 147, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'grande', '0.00', NULL),
(1419, 75, 147, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'extrag', '0.00', NULL),
(1420, 75, 147, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'mediano', '0.00', NULL),
(1421, 75, 147, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'pequeno', '0.00', NULL),
(1422, 75, 147, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'porcion', '0.00', NULL),
(1423, 75, 235, '2025-01-27 21:21:56', '2025-01-27 21:21:56', 'unico', '0.00', NULL),
(1424, 75, 235, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'grande', '0.00', NULL),
(1425, 75, 235, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'extrag', '0.00', NULL),
(1426, 75, 235, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'mediano', '0.00', NULL),
(1427, 75, 235, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'pequeno', '0.00', NULL),
(1428, 75, 235, '2025-01-27 21:21:56', '2025-02-02 10:35:25', 'porcion', '0.00', NULL),
(1429, 76, 245, '2025-01-27 21:22:38', '2025-01-27 21:22:38', 'unico', '0.00', NULL),
(1430, 76, 245, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'grande', '0.00', NULL),
(1431, 76, 245, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'extrag', '0.00', NULL),
(1432, 76, 245, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'mediano', '0.00', NULL),
(1433, 76, 245, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'pequeno', '0.00', NULL),
(1434, 76, 245, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'porcion', '0.00', NULL),
(1435, 76, 250, '2025-01-27 21:22:38', '2025-01-27 21:22:38', 'unico', '0.00', NULL),
(1436, 76, 250, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'grande', '0.00', NULL),
(1437, 76, 250, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'extrag', '0.00', NULL);
INSERT INTO `gapp_producto_ingrediente` (`id`, `producto_id`, `ingrediente_id`, `created_at`, `updated_at`, `tamano`, `cantidad`, `visible`) VALUES
(1438, 76, 250, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'mediano', '0.00', NULL),
(1439, 76, 250, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'pequeno', '0.00', NULL),
(1440, 76, 250, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'porcion', '0.00', NULL),
(1441, 76, 147, '2025-01-27 21:22:38', '2025-01-27 21:22:38', 'unico', '0.00', NULL),
(1442, 76, 147, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'grande', '0.00', NULL),
(1443, 76, 147, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'extrag', '0.00', NULL),
(1444, 76, 147, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'mediano', '0.00', NULL),
(1445, 76, 147, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'pequeno', '0.00', NULL),
(1446, 76, 147, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'porcion', '0.00', NULL),
(1447, 76, 235, '2025-01-27 21:22:38', '2025-01-27 21:22:38', 'unico', '0.00', NULL),
(1448, 76, 235, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'grande', '0.00', NULL),
(1449, 76, 235, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'extrag', '0.00', NULL),
(1450, 76, 235, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'mediano', '0.00', NULL),
(1451, 76, 235, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'pequeno', '0.00', NULL),
(1452, 76, 235, '2025-01-27 21:22:38', '2025-02-02 10:35:39', 'porcion', '0.00', NULL),
(1453, 77, 245, '2025-01-27 21:23:14', '2025-01-27 21:23:14', 'unico', '0.00', NULL),
(1454, 77, 245, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'grande', '0.00', NULL),
(1455, 77, 245, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'extrag', '0.00', NULL),
(1456, 77, 245, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'mediano', '0.00', NULL),
(1457, 77, 245, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'pequeno', '0.00', NULL),
(1458, 77, 245, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'porcion', '0.00', NULL),
(1459, 77, 250, '2025-01-27 21:23:14', '2025-01-27 21:23:14', 'unico', '0.00', NULL),
(1460, 77, 250, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'grande', '0.00', NULL),
(1461, 77, 250, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'extrag', '0.00', NULL),
(1462, 77, 250, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'mediano', '0.00', NULL),
(1463, 77, 250, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'pequeno', '0.00', NULL),
(1464, 77, 250, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'porcion', '0.00', NULL),
(1465, 77, 147, '2025-01-27 21:23:14', '2025-01-27 21:23:14', 'unico', '0.00', NULL),
(1466, 77, 147, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'grande', '0.00', NULL),
(1467, 77, 147, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'extrag', '0.00', NULL),
(1468, 77, 147, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'mediano', '0.00', NULL),
(1469, 77, 147, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'pequeno', '0.00', NULL),
(1470, 77, 147, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'porcion', '0.00', NULL),
(1471, 77, 235, '2025-01-27 21:23:14', '2025-01-27 21:23:14', 'unico', '0.00', NULL),
(1472, 77, 235, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'grande', '0.00', NULL),
(1473, 77, 235, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'extrag', '0.00', NULL),
(1474, 77, 235, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'mediano', '0.00', NULL),
(1475, 77, 235, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'pequeno', '0.00', NULL),
(1476, 77, 235, '2025-01-27 21:23:14', '2025-02-02 10:35:49', 'porcion', '0.00', NULL),
(1477, 10, 204, '2025-01-27 21:35:58', '2025-01-27 21:35:58', 'unico', '0.00', NULL),
(1478, 10, 204, '2025-01-27 21:35:58', '2025-02-02 10:37:46', 'grande', '0.00', NULL),
(1479, 10, 204, '2025-01-27 21:35:58', '2025-02-02 10:37:46', 'extrag', '0.00', NULL),
(1480, 10, 204, '2025-01-27 21:35:58', '2025-02-02 10:37:46', 'mediano', '0.00', NULL),
(1481, 10, 204, '2025-01-27 21:35:58', '2025-02-02 10:37:46', 'pequeno', '0.00', NULL),
(1482, 10, 204, '2025-01-27 21:35:58', '2025-02-02 10:37:46', 'porcion', '0.00', NULL),
(1489, 10, 147, '2025-01-27 21:35:58', '2025-01-27 21:35:58', 'unico', '0.00', NULL),
(1490, 10, 147, '2025-01-27 21:35:58', '2025-02-02 10:37:46', 'grande', '0.00', NULL),
(1491, 10, 147, '2025-01-27 21:35:58', '2025-02-02 10:37:46', 'extrag', '0.00', NULL),
(1492, 10, 147, '2025-01-27 21:35:58', '2025-02-02 10:37:46', 'mediano', '0.00', NULL),
(1493, 10, 147, '2025-01-27 21:35:58', '2025-02-02 10:37:46', 'pequeno', '0.00', NULL),
(1494, 10, 147, '2025-01-27 21:35:58', '2025-02-02 10:37:46', 'porcion', '0.00', NULL),
(1495, 10, 236, '2025-01-27 21:35:58', '2025-01-27 21:35:58', 'unico', '0.00', NULL),
(1496, 10, 236, '2025-01-27 21:35:58', '2025-02-02 10:37:46', 'grande', '0.00', NULL),
(1497, 10, 236, '2025-01-27 21:35:58', '2025-02-02 10:37:46', 'extrag', '0.00', NULL),
(1498, 10, 236, '2025-01-27 21:35:58', '2025-02-02 10:37:46', 'mediano', '0.00', NULL),
(1499, 10, 236, '2025-01-27 21:35:58', '2025-02-02 10:37:46', 'pequeno', '0.00', NULL),
(1500, 10, 236, '2025-01-27 21:35:58', '2025-02-02 10:37:46', 'porcion', '0.00', NULL),
(1501, 11, 204, '2025-01-27 21:38:02', '2025-01-27 21:38:02', 'unico', '0.00', NULL),
(1502, 11, 204, '2025-01-27 21:38:02', '2025-02-02 10:37:55', 'grande', '0.00', NULL),
(1503, 11, 204, '2025-01-27 21:38:02', '2025-02-02 10:37:55', 'extrag', '0.00', NULL),
(1504, 11, 204, '2025-01-27 21:38:02', '2025-02-02 10:37:55', 'mediano', '0.00', NULL),
(1505, 11, 204, '2025-01-27 21:38:02', '2025-02-02 10:37:55', 'pequeno', '0.00', NULL),
(1506, 11, 204, '2025-01-27 21:38:02', '2025-02-02 10:37:55', 'porcion', '0.00', NULL),
(1513, 11, 147, '2025-01-27 21:38:02', '2025-01-27 21:38:02', 'unico', '0.00', NULL),
(1514, 11, 147, '2025-01-27 21:38:02', '2025-02-02 10:37:55', 'grande', '0.00', NULL),
(1515, 11, 147, '2025-01-27 21:38:03', '2025-02-02 10:37:55', 'extrag', '0.00', NULL),
(1516, 11, 147, '2025-01-27 21:38:03', '2025-02-02 10:37:55', 'mediano', '0.00', NULL),
(1517, 11, 147, '2025-01-27 21:38:03', '2025-02-02 10:37:55', 'pequeno', '0.00', NULL),
(1518, 11, 147, '2025-01-27 21:38:03', '2025-02-02 10:37:55', 'porcion', '0.00', NULL),
(1519, 11, 236, '2025-01-27 21:38:03', '2025-01-27 21:38:03', 'unico', '0.00', NULL),
(1520, 11, 236, '2025-01-27 21:38:03', '2025-02-02 10:37:55', 'grande', '0.00', NULL),
(1521, 11, 236, '2025-01-27 21:38:03', '2025-02-02 10:37:55', 'extrag', '0.00', NULL),
(1522, 11, 236, '2025-01-27 21:38:03', '2025-02-02 10:37:55', 'mediano', '0.00', NULL),
(1523, 11, 236, '2025-01-27 21:38:03', '2025-02-02 10:37:55', 'pequeno', '0.00', NULL),
(1524, 11, 236, '2025-01-27 21:38:03', '2025-02-02 10:37:55', 'porcion', '0.00', NULL),
(1525, 12, 204, '2025-01-27 21:40:02', '2025-01-27 21:40:02', 'unico', '0.00', NULL),
(1526, 12, 204, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'grande', '0.00', NULL),
(1527, 12, 204, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'extrag', '0.00', NULL),
(1528, 12, 204, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'mediano', '0.00', NULL),
(1529, 12, 204, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'pequeno', '0.00', NULL),
(1530, 12, 204, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'porcion', '0.00', NULL),
(1537, 12, 103, '2025-01-27 21:40:02', '2025-01-27 21:40:02', 'unico', '0.00', NULL),
(1538, 12, 103, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'grande', '0.00', NULL),
(1539, 12, 103, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'extrag', '0.00', NULL),
(1540, 12, 103, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'mediano', '0.00', NULL),
(1541, 12, 103, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'pequeno', '0.00', NULL),
(1542, 12, 103, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'porcion', '0.00', NULL),
(1543, 12, 147, '2025-01-27 21:40:02', '2025-01-27 21:40:02', 'unico', '0.00', NULL),
(1544, 12, 147, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'grande', '0.00', NULL),
(1545, 12, 147, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'extrag', '0.00', NULL),
(1546, 12, 147, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'mediano', '0.00', NULL),
(1547, 12, 147, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'pequeno', '0.00', NULL),
(1548, 12, 147, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'porcion', '0.00', NULL),
(1549, 12, 28, '2025-01-27 21:40:02', '2025-01-27 21:40:02', 'unico', '0.00', NULL),
(1550, 12, 28, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'grande', '0.00', NULL),
(1551, 12, 28, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'extrag', '0.00', NULL),
(1552, 12, 28, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'mediano', '0.00', NULL),
(1553, 12, 28, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'pequeno', '0.00', NULL),
(1554, 12, 28, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'porcion', '0.00', NULL),
(1555, 12, 104, '2025-01-27 21:40:02', '2025-01-27 21:40:02', 'unico', '0.00', NULL),
(1556, 12, 104, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'grande', '0.00', NULL),
(1557, 12, 104, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'extrag', '0.00', NULL),
(1558, 12, 104, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'mediano', '0.00', NULL),
(1559, 12, 104, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'pequeno', '0.00', NULL),
(1560, 12, 104, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'porcion', '0.00', NULL),
(1561, 12, 236, '2025-01-27 21:40:02', '2025-01-27 21:40:02', 'unico', '0.00', NULL),
(1562, 12, 236, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'grande', '0.00', NULL),
(1563, 12, 236, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'extrag', '0.00', NULL),
(1564, 12, 236, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'mediano', '0.00', NULL),
(1565, 12, 236, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'pequeno', '0.00', NULL),
(1566, 12, 236, '2025-01-27 21:40:02', '2025-02-02 10:36:51', 'porcion', '0.00', NULL),
(1567, 13, 204, '2025-01-27 21:41:05', '2025-01-27 21:41:05', 'unico', '0.00', NULL),
(1568, 13, 204, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'grande', '0.00', NULL),
(1569, 13, 204, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'extrag', '0.00', NULL),
(1570, 13, 204, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'mediano', '0.00', NULL),
(1571, 13, 204, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'pequeno', '0.00', NULL),
(1572, 13, 204, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'porcion', '0.00', NULL),
(1579, 13, 103, '2025-01-27 21:41:05', '2025-01-27 21:41:05', 'unico', '0.00', NULL),
(1580, 13, 103, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'grande', '0.00', NULL),
(1581, 13, 103, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'extrag', '0.00', NULL),
(1582, 13, 103, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'mediano', '0.00', NULL),
(1583, 13, 103, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'pequeno', '0.00', NULL),
(1584, 13, 103, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'porcion', '0.00', NULL),
(1585, 13, 147, '2025-01-27 21:41:05', '2025-01-27 21:41:05', 'unico', '0.00', NULL),
(1586, 13, 147, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'grande', '0.00', NULL),
(1587, 13, 147, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'extrag', '0.00', NULL),
(1588, 13, 147, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'mediano', '0.00', NULL),
(1589, 13, 147, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'pequeno', '0.00', NULL),
(1590, 13, 147, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'porcion', '0.00', NULL),
(1591, 13, 28, '2025-01-27 21:41:05', '2025-01-27 21:41:05', 'unico', '0.00', NULL),
(1592, 13, 28, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'grande', '0.00', NULL),
(1593, 13, 28, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'extrag', '0.00', NULL),
(1594, 13, 28, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'mediano', '0.00', NULL),
(1595, 13, 28, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'pequeno', '0.00', NULL),
(1596, 13, 28, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'porcion', '0.00', NULL),
(1597, 13, 104, '2025-01-27 21:41:05', '2025-01-27 21:41:05', 'unico', '0.00', NULL),
(1598, 13, 104, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'grande', '0.00', NULL),
(1599, 13, 104, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'extrag', '0.00', NULL),
(1600, 13, 104, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'mediano', '0.00', NULL),
(1601, 13, 104, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'pequeno', '0.00', NULL),
(1602, 13, 104, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'porcion', '0.00', NULL),
(1603, 13, 236, '2025-01-27 21:41:05', '2025-01-27 21:41:05', 'unico', '0.00', NULL),
(1604, 13, 236, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'grande', '0.00', NULL),
(1605, 13, 236, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'extrag', '0.00', NULL),
(1606, 13, 236, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'mediano', '0.00', NULL),
(1607, 13, 236, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'pequeno', '0.00', NULL),
(1608, 13, 236, '2025-01-27 21:41:05', '2025-02-02 10:37:19', 'porcion', '0.00', NULL),
(1609, 4, 204, '2025-01-27 21:43:10', '2025-01-27 21:43:10', 'unico', '0.00', NULL),
(1610, 4, 204, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'grande', '0.00', NULL),
(1611, 4, 204, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'extrag', '0.00', NULL),
(1612, 4, 204, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'mediano', '0.00', NULL),
(1613, 4, 204, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'pequeno', '0.00', NULL),
(1614, 4, 204, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'porcion', '0.00', NULL),
(1615, 4, 244, '2025-01-27 21:43:10', '2025-01-27 21:43:10', 'unico', '0.00', NULL),
(1616, 4, 244, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'grande', '0.00', NULL),
(1617, 4, 244, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'extrag', '0.00', NULL),
(1618, 4, 244, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'mediano', '0.00', NULL),
(1619, 4, 244, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'pequeno', '0.00', NULL),
(1620, 4, 244, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'porcion', '0.00', NULL),
(1621, 4, 245, '2025-01-27 21:43:10', '2025-01-27 21:43:10', 'unico', '0.00', NULL),
(1622, 4, 245, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'grande', '0.00', NULL),
(1623, 4, 245, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'extrag', '0.00', NULL),
(1624, 4, 245, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'mediano', '0.00', NULL),
(1625, 4, 245, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'pequeno', '0.00', NULL),
(1626, 4, 245, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'porcion', '0.00', NULL),
(1627, 4, 103, '2025-01-27 21:43:10', '2025-01-27 21:43:10', 'unico', '0.00', NULL),
(1628, 4, 103, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'grande', '0.00', NULL),
(1629, 4, 103, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'extrag', '0.00', NULL),
(1630, 4, 103, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'mediano', '0.00', NULL),
(1631, 4, 103, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'pequeno', '0.00', NULL),
(1632, 4, 103, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'porcion', '0.00', NULL),
(1633, 4, 147, '2025-01-27 21:43:10', '2025-01-27 21:43:10', 'unico', '0.00', NULL),
(1634, 4, 147, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'grande', '0.00', NULL),
(1635, 4, 147, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'extrag', '0.00', NULL),
(1636, 4, 147, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'mediano', '0.00', NULL),
(1637, 4, 147, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'pequeno', '0.00', NULL),
(1638, 4, 147, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'porcion', '0.00', NULL),
(1639, 4, 105, '2025-01-27 21:43:10', '2025-01-27 21:43:10', 'unico', '0.00', NULL),
(1640, 4, 105, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'grande', '0.00', NULL),
(1641, 4, 105, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'extrag', '0.00', NULL),
(1642, 4, 105, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'mediano', '0.00', NULL),
(1643, 4, 105, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'pequeno', '0.00', NULL),
(1644, 4, 105, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'porcion', '0.00', NULL),
(1645, 4, 28, '2025-01-27 21:43:10', '2025-01-27 21:43:10', 'unico', '0.00', NULL),
(1646, 4, 28, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'grande', '0.00', NULL),
(1647, 4, 28, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'extrag', '0.00', NULL),
(1648, 4, 28, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'mediano', '0.00', NULL),
(1649, 4, 28, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'pequeno', '0.00', NULL),
(1650, 4, 28, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'porcion', '0.00', NULL),
(1651, 4, 236, '2025-01-27 21:43:10', '2025-01-27 21:43:10', 'unico', '0.00', NULL),
(1652, 4, 236, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'grande', '0.00', NULL),
(1653, 4, 236, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'extrag', '0.00', NULL),
(1654, 4, 236, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'mediano', '0.00', NULL),
(1655, 4, 236, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'pequeno', '0.00', NULL),
(1656, 4, 236, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'porcion', '0.00', NULL),
(1657, 4, 235, '2025-01-27 21:43:10', '2025-01-27 21:43:10', 'unico', '0.00', NULL),
(1658, 4, 235, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'grande', '0.00', NULL),
(1659, 4, 235, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'extrag', '0.00', NULL),
(1660, 4, 235, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'mediano', '0.00', NULL),
(1661, 4, 235, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'pequeno', '0.00', NULL),
(1662, 4, 235, '2025-01-27 21:43:10', '2025-02-02 10:30:56', 'porcion', '0.00', NULL),
(1663, 5, 204, '2025-01-27 21:44:37', '2025-01-27 21:44:37', 'unico', '0.00', NULL),
(1664, 5, 204, '2025-01-27 21:44:37', '2025-02-02 10:31:13', 'grande', '0.00', NULL),
(1665, 5, 204, '2025-01-27 21:44:37', '2025-02-02 10:31:13', 'extrag', '0.00', NULL),
(1666, 5, 204, '2025-01-27 21:44:37', '2025-02-02 10:31:13', 'mediano', '0.00', NULL),
(1667, 5, 204, '2025-01-27 21:44:37', '2025-02-02 10:31:13', 'pequeno', '0.00', NULL),
(1668, 5, 204, '2025-01-27 21:44:37', '2025-02-02 10:31:13', 'porcion', '0.00', NULL),
(1669, 5, 244, '2025-01-27 21:44:37', '2025-01-27 21:44:37', 'unico', '0.00', NULL),
(1670, 5, 244, '2025-01-27 21:44:37', '2025-02-02 10:31:14', 'grande', '0.00', NULL),
(1671, 5, 244, '2025-01-27 21:44:37', '2025-02-02 10:31:14', 'extrag', '0.00', NULL),
(1672, 5, 244, '2025-01-27 21:44:37', '2025-02-02 10:31:14', 'mediano', '0.00', NULL),
(1673, 5, 244, '2025-01-27 21:44:37', '2025-02-02 10:31:14', 'pequeno', '0.00', NULL),
(1674, 5, 244, '2025-01-27 21:44:37', '2025-02-02 10:31:14', 'porcion', '0.00', NULL),
(1675, 5, 245, '2025-01-27 21:44:37', '2025-01-27 21:44:37', 'unico', '0.00', NULL),
(1676, 5, 245, '2025-01-27 21:44:37', '2025-02-02 10:31:13', 'grande', '0.00', NULL),
(1677, 5, 245, '2025-01-27 21:44:37', '2025-02-02 10:31:13', 'extrag', '0.00', NULL),
(1678, 5, 245, '2025-01-27 21:44:37', '2025-02-02 10:31:13', 'mediano', '0.00', NULL),
(1679, 5, 245, '2025-01-27 21:44:37', '2025-02-02 10:31:13', 'pequeno', '0.00', NULL),
(1680, 5, 245, '2025-01-27 21:44:37', '2025-02-02 10:31:13', 'porcion', '0.00', NULL),
(1681, 5, 103, '2025-01-27 21:44:37', '2025-01-27 21:44:37', 'unico', '0.00', NULL),
(1682, 5, 103, '2025-01-27 21:44:37', '2025-02-02 10:31:13', 'grande', '0.00', NULL),
(1683, 5, 103, '2025-01-27 21:44:37', '2025-02-02 10:31:13', 'extrag', '0.00', NULL),
(1684, 5, 103, '2025-01-27 21:44:37', '2025-02-02 10:31:13', 'mediano', '0.00', NULL),
(1685, 5, 103, '2025-01-27 21:44:37', '2025-02-02 10:31:13', 'pequeno', '0.00', NULL),
(1686, 5, 103, '2025-01-27 21:44:37', '2025-02-02 10:31:13', 'porcion', '0.00', NULL),
(1687, 5, 147, '2025-01-27 21:44:38', '2025-01-27 21:44:38', 'unico', '0.00', NULL),
(1688, 5, 147, '2025-01-27 21:44:38', '2025-02-02 10:31:13', 'grande', '0.00', NULL),
(1689, 5, 147, '2025-01-27 21:44:38', '2025-02-02 10:31:13', 'extrag', '0.00', NULL),
(1690, 5, 147, '2025-01-27 21:44:38', '2025-02-02 10:31:13', 'mediano', '0.00', NULL),
(1691, 5, 147, '2025-01-27 21:44:38', '2025-02-02 10:31:13', 'pequeno', '0.00', NULL),
(1692, 5, 147, '2025-01-27 21:44:38', '2025-02-02 10:31:13', 'porcion', '0.00', NULL),
(1693, 5, 105, '2025-01-27 21:44:38', '2025-01-27 21:44:38', 'unico', '0.00', NULL),
(1694, 5, 105, '2025-01-27 21:44:38', '2025-02-02 10:31:13', 'grande', '0.00', NULL),
(1695, 5, 105, '2025-01-27 21:44:38', '2025-02-02 10:31:13', 'extrag', '0.00', NULL),
(1696, 5, 105, '2025-01-27 21:44:38', '2025-02-02 10:31:13', 'mediano', '0.00', NULL),
(1697, 5, 105, '2025-01-27 21:44:38', '2025-02-02 10:31:13', 'pequeno', '0.00', NULL),
(1698, 5, 105, '2025-01-27 21:44:38', '2025-02-02 10:31:14', 'porcion', '0.00', NULL),
(1699, 5, 28, '2025-01-27 21:44:38', '2025-01-27 21:44:38', 'unico', '0.00', NULL),
(1700, 5, 28, '2025-01-27 21:44:38', '2025-02-02 10:31:14', 'grande', '0.00', NULL),
(1701, 5, 28, '2025-01-27 21:44:38', '2025-02-02 10:31:14', 'extrag', '0.00', NULL),
(1702, 5, 28, '2025-01-27 21:44:38', '2025-02-02 10:31:14', 'mediano', '0.00', NULL),
(1703, 5, 28, '2025-01-27 21:44:38', '2025-02-02 10:31:14', 'pequeno', '0.00', NULL),
(1704, 5, 28, '2025-01-27 21:44:38', '2025-02-02 10:31:14', 'porcion', '0.00', NULL),
(1705, 5, 236, '2025-01-27 21:44:38', '2025-01-27 21:44:38', 'unico', '0.00', NULL),
(1706, 5, 236, '2025-01-27 21:44:38', '2025-02-02 10:31:14', 'grande', '0.00', NULL),
(1707, 5, 236, '2025-01-27 21:44:38', '2025-02-02 10:31:14', 'extrag', '0.00', NULL),
(1708, 5, 236, '2025-01-27 21:44:38', '2025-02-02 10:31:14', 'mediano', '0.00', NULL),
(1709, 5, 236, '2025-01-27 21:44:38', '2025-02-02 10:31:14', 'pequeno', '0.00', NULL),
(1710, 5, 236, '2025-01-27 21:44:38', '2025-02-02 10:31:14', 'porcion', '0.00', NULL),
(1711, 5, 235, '2025-01-27 21:44:38', '2025-01-27 21:44:38', 'unico', '0.00', NULL),
(1712, 5, 235, '2025-01-27 21:44:38', '2025-02-02 10:31:14', 'grande', '0.00', NULL),
(1713, 5, 235, '2025-01-27 21:44:38', '2025-02-02 10:31:14', 'extrag', '0.00', NULL),
(1714, 5, 235, '2025-01-27 21:44:38', '2025-02-02 10:31:14', 'mediano', '0.00', NULL),
(1715, 5, 235, '2025-01-27 21:44:38', '2025-02-02 10:31:14', 'pequeno', '0.00', NULL),
(1716, 5, 235, '2025-01-27 21:44:38', '2025-02-02 10:31:14', 'porcion', '0.00', NULL),
(1717, 6, 204, '2025-01-27 21:46:07', '2025-01-27 21:46:07', 'unico', '0.00', NULL),
(1718, 6, 204, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'grande', '0.00', NULL),
(1719, 6, 204, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'extrag', '0.00', NULL),
(1720, 6, 204, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'mediano', '0.00', NULL),
(1721, 6, 204, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'pequeno', '0.00', NULL),
(1722, 6, 204, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'porcion', '0.00', NULL),
(1723, 6, 244, '2025-01-27 21:46:07', '2025-01-27 21:46:07', 'unico', '0.00', NULL),
(1724, 6, 244, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'grande', '0.00', NULL),
(1725, 6, 244, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'extrag', '0.00', NULL),
(1726, 6, 244, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'mediano', '0.00', NULL),
(1727, 6, 244, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'pequeno', '0.00', NULL),
(1728, 6, 244, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'porcion', '0.00', NULL),
(1729, 6, 245, '2025-01-27 21:46:07', '2025-01-27 21:46:07', 'unico', '0.00', NULL),
(1730, 6, 245, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'grande', '0.00', NULL),
(1731, 6, 245, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'extrag', '0.00', NULL),
(1732, 6, 245, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'mediano', '0.00', NULL),
(1733, 6, 245, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'pequeno', '0.00', NULL),
(1734, 6, 245, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'porcion', '0.00', NULL),
(1735, 6, 103, '2025-01-27 21:46:07', '2025-01-27 21:46:07', 'unico', '0.00', NULL),
(1736, 6, 103, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'grande', '0.00', NULL),
(1737, 6, 103, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'extrag', '0.00', NULL),
(1738, 6, 103, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'mediano', '0.00', NULL),
(1739, 6, 103, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'pequeno', '0.00', NULL),
(1740, 6, 103, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'porcion', '0.00', NULL),
(1741, 6, 147, '2025-01-27 21:46:07', '2025-01-27 21:46:07', 'unico', '0.00', NULL),
(1742, 6, 147, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'grande', '0.00', NULL),
(1743, 6, 147, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'extrag', '0.00', NULL),
(1744, 6, 147, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'mediano', '0.00', NULL),
(1745, 6, 147, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'pequeno', '0.00', NULL),
(1746, 6, 147, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'porcion', '0.00', NULL),
(1747, 6, 105, '2025-01-27 21:46:07', '2025-01-27 21:46:07', 'unico', '0.00', NULL),
(1748, 6, 105, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'grande', '0.00', NULL),
(1749, 6, 105, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'extrag', '0.00', NULL),
(1750, 6, 105, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'mediano', '0.00', NULL),
(1751, 6, 105, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'pequeno', '0.00', NULL),
(1752, 6, 105, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'porcion', '0.00', NULL),
(1753, 6, 28, '2025-01-27 21:46:07', '2025-01-27 21:46:07', 'unico', '0.00', NULL),
(1754, 6, 28, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'grande', '0.00', NULL),
(1755, 6, 28, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'extrag', '0.00', NULL),
(1756, 6, 28, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'mediano', '0.00', NULL),
(1757, 6, 28, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'pequeno', '0.00', NULL),
(1758, 6, 28, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'porcion', '0.00', NULL),
(1759, 6, 236, '2025-01-27 21:46:07', '2025-01-27 21:46:07', 'unico', '0.00', NULL),
(1760, 6, 236, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'grande', '0.00', NULL),
(1761, 6, 236, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'extrag', '0.00', NULL),
(1762, 6, 236, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'mediano', '0.00', NULL),
(1763, 6, 236, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'pequeno', '0.00', NULL),
(1764, 6, 236, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'porcion', '0.00', NULL),
(1765, 6, 235, '2025-01-27 21:46:07', '2025-01-27 21:46:07', 'unico', '0.00', NULL),
(1766, 6, 235, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'grande', '0.00', NULL),
(1767, 6, 235, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'extrag', '0.00', NULL),
(1768, 6, 235, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'mediano', '0.00', NULL),
(1769, 6, 235, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'pequeno', '0.00', NULL),
(1770, 6, 235, '2025-01-27 21:46:07', '2025-02-02 10:31:44', 'porcion', '0.00', NULL),
(1771, 7, 204, '2025-01-27 21:47:25', '2025-01-27 21:47:25', 'unico', '0.00', NULL),
(1772, 7, 204, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'grande', '0.00', NULL),
(1773, 7, 204, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'extrag', '0.00', NULL),
(1774, 7, 204, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'mediano', '0.00', NULL),
(1775, 7, 204, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'pequeno', '0.00', NULL),
(1776, 7, 204, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'porcion', '0.00', NULL),
(1777, 7, 244, '2025-01-27 21:47:25', '2025-01-27 21:47:25', 'unico', '0.00', NULL),
(1778, 7, 244, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'grande', '0.00', NULL),
(1779, 7, 244, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'extrag', '0.00', NULL),
(1780, 7, 244, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'mediano', '0.00', NULL),
(1781, 7, 244, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'pequeno', '0.00', NULL),
(1782, 7, 244, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'porcion', '0.00', NULL),
(1783, 7, 245, '2025-01-27 21:47:25', '2025-01-27 21:47:25', 'unico', '0.00', NULL),
(1784, 7, 245, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'grande', '0.00', NULL),
(1785, 7, 245, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'extrag', '0.00', NULL),
(1786, 7, 245, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'mediano', '0.00', NULL),
(1787, 7, 245, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'pequeno', '0.00', NULL),
(1788, 7, 245, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'porcion', '0.00', NULL),
(1789, 7, 103, '2025-01-27 21:47:25', '2025-01-27 21:47:25', 'unico', '0.00', NULL),
(1790, 7, 103, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'grande', '0.00', NULL),
(1791, 7, 103, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'extrag', '0.00', NULL),
(1792, 7, 103, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'mediano', '0.00', NULL),
(1793, 7, 103, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'pequeno', '0.00', NULL),
(1794, 7, 103, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'porcion', '0.00', NULL),
(1795, 7, 147, '2025-01-27 21:47:25', '2025-01-27 21:47:25', 'unico', '0.00', NULL),
(1796, 7, 147, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'grande', '0.00', NULL),
(1797, 7, 147, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'extrag', '0.00', NULL),
(1798, 7, 147, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'mediano', '0.00', NULL),
(1799, 7, 147, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'pequeno', '0.00', NULL),
(1800, 7, 147, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'porcion', '0.00', NULL),
(1801, 7, 105, '2025-01-27 21:47:25', '2025-01-27 21:47:25', 'unico', '0.00', NULL),
(1802, 7, 105, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'grande', '0.00', NULL),
(1803, 7, 105, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'extrag', '0.00', NULL),
(1804, 7, 105, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'mediano', '0.00', NULL),
(1805, 7, 105, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'pequeno', '0.00', NULL),
(1806, 7, 105, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'porcion', '0.00', NULL),
(1807, 7, 28, '2025-01-27 21:47:25', '2025-01-27 21:47:25', 'unico', '0.00', NULL),
(1808, 7, 28, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'grande', '0.00', NULL),
(1809, 7, 28, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'extrag', '0.00', NULL),
(1810, 7, 28, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'mediano', '0.00', NULL),
(1811, 7, 28, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'pequeno', '0.00', NULL),
(1812, 7, 28, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'porcion', '0.00', NULL),
(1813, 7, 236, '2025-01-27 21:47:25', '2025-01-27 21:47:25', 'unico', '0.00', NULL),
(1814, 7, 236, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'grande', '0.00', NULL),
(1815, 7, 236, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'extrag', '0.00', NULL),
(1816, 7, 236, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'mediano', '0.00', NULL),
(1817, 7, 236, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'pequeno', '0.00', NULL),
(1818, 7, 236, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'porcion', '0.00', NULL),
(1819, 7, 235, '2025-01-27 21:47:25', '2025-01-27 21:47:25', 'unico', '0.00', NULL),
(1820, 7, 235, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'grande', '0.00', NULL),
(1821, 7, 235, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'extrag', '0.00', NULL),
(1822, 7, 235, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'mediano', '0.00', NULL),
(1823, 7, 235, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'pequeno', '0.00', NULL),
(1824, 7, 235, '2025-01-27 21:47:25', '2025-02-02 10:31:56', 'porcion', '0.00', NULL),
(1825, 8, 204, '2025-01-27 21:48:06', '2025-01-27 21:48:06', 'unico', '0.00', NULL),
(1826, 8, 204, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'grande', '0.00', NULL),
(1827, 8, 204, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'extrag', '0.00', NULL),
(1828, 8, 204, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'mediano', '0.00', NULL),
(1829, 8, 204, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'pequeno', '0.00', NULL),
(1830, 8, 204, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'porcion', '0.00', NULL),
(1831, 8, 244, '2025-01-27 21:48:06', '2025-01-27 21:48:06', 'unico', '0.00', NULL),
(1832, 8, 244, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'grande', '0.00', NULL),
(1833, 8, 244, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'extrag', '0.00', NULL),
(1834, 8, 244, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'mediano', '0.00', NULL),
(1835, 8, 244, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'pequeno', '0.00', NULL),
(1836, 8, 244, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'porcion', '0.00', NULL),
(1837, 8, 245, '2025-01-27 21:48:06', '2025-01-27 21:48:06', 'unico', '0.00', NULL),
(1838, 8, 245, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'grande', '0.00', NULL),
(1839, 8, 245, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'extrag', '0.00', NULL),
(1840, 8, 245, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'mediano', '0.00', NULL),
(1841, 8, 245, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'pequeno', '0.00', NULL),
(1842, 8, 245, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'porcion', '0.00', NULL),
(1843, 8, 103, '2025-01-27 21:48:06', '2025-01-27 21:48:06', 'unico', '0.00', NULL),
(1844, 8, 103, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'grande', '0.00', NULL),
(1845, 8, 103, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'extrag', '0.00', NULL),
(1846, 8, 103, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'mediano', '0.00', NULL),
(1847, 8, 103, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'pequeno', '0.00', NULL),
(1848, 8, 103, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'porcion', '0.00', NULL),
(1849, 8, 147, '2025-01-27 21:48:06', '2025-01-27 21:48:06', 'unico', '0.00', NULL),
(1850, 8, 147, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'grande', '0.00', NULL),
(1851, 8, 147, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'extrag', '0.00', NULL),
(1852, 8, 147, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'mediano', '0.00', NULL),
(1853, 8, 147, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'pequeno', '0.00', NULL),
(1854, 8, 147, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'porcion', '0.00', NULL),
(1855, 8, 105, '2025-01-27 21:48:06', '2025-01-27 21:48:06', 'unico', '0.00', NULL),
(1856, 8, 105, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'grande', '0.00', NULL),
(1857, 8, 105, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'extrag', '0.00', NULL),
(1858, 8, 105, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'mediano', '0.00', NULL),
(1859, 8, 105, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'pequeno', '0.00', NULL),
(1860, 8, 105, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'porcion', '0.00', NULL),
(1861, 8, 28, '2025-01-27 21:48:06', '2025-01-27 21:48:06', 'unico', '0.00', NULL),
(1862, 8, 28, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'grande', '0.00', NULL),
(1863, 8, 28, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'extrag', '0.00', NULL),
(1864, 8, 28, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'mediano', '0.00', NULL),
(1865, 8, 28, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'pequeno', '0.00', NULL),
(1866, 8, 28, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'porcion', '0.00', NULL),
(1867, 8, 236, '2025-01-27 21:48:06', '2025-01-27 21:48:06', 'unico', '0.00', NULL),
(1868, 8, 236, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'grande', '0.00', NULL),
(1869, 8, 236, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'extrag', '0.00', NULL),
(1870, 8, 236, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'mediano', '0.00', NULL),
(1871, 8, 236, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'pequeno', '0.00', NULL),
(1872, 8, 236, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'porcion', '0.00', NULL),
(1873, 8, 235, '2025-01-27 21:48:06', '2025-01-27 21:48:06', 'unico', '0.00', NULL),
(1874, 8, 235, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'grande', '0.00', NULL),
(1875, 8, 235, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'extrag', '0.00', NULL),
(1876, 8, 235, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'mediano', '0.00', NULL),
(1877, 8, 235, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'pequeno', '0.00', NULL),
(1878, 8, 235, '2025-01-27 21:48:06', '2025-02-02 10:32:19', 'porcion', '0.00', NULL),
(1879, 9, 204, '2025-01-27 21:48:36', '2025-01-27 21:48:36', 'unico', '0.00', NULL),
(1880, 9, 204, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'grande', '0.00', NULL),
(1881, 9, 204, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'extrag', '0.00', NULL),
(1882, 9, 204, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'mediano', '0.00', NULL),
(1883, 9, 204, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'pequeno', '0.00', NULL),
(1884, 9, 204, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'porcion', '0.00', NULL),
(1885, 9, 244, '2025-01-27 21:48:36', '2025-01-27 21:48:36', 'unico', '0.00', NULL),
(1886, 9, 244, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'grande', '0.00', NULL),
(1887, 9, 244, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'extrag', '0.00', NULL),
(1888, 9, 244, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'mediano', '0.00', NULL),
(1889, 9, 244, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'pequeno', '0.00', NULL),
(1890, 9, 244, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'porcion', '0.00', NULL),
(1891, 9, 245, '2025-01-27 21:48:36', '2025-01-27 21:48:36', 'unico', '0.00', NULL),
(1892, 9, 245, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'grande', '0.00', NULL),
(1893, 9, 245, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'extrag', '0.00', NULL),
(1894, 9, 245, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'mediano', '0.00', NULL),
(1895, 9, 245, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'pequeno', '0.00', NULL),
(1896, 9, 245, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'porcion', '0.00', NULL),
(1897, 9, 103, '2025-01-27 21:48:36', '2025-01-27 21:48:36', 'unico', '0.00', NULL),
(1898, 9, 103, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'grande', '0.00', NULL),
(1899, 9, 103, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'extrag', '0.00', NULL),
(1900, 9, 103, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'mediano', '0.00', NULL),
(1901, 9, 103, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'pequeno', '0.00', NULL),
(1902, 9, 103, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'porcion', '0.00', NULL),
(1903, 9, 147, '2025-01-27 21:48:36', '2025-01-27 21:48:36', 'unico', '0.00', NULL),
(1904, 9, 147, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'grande', '0.00', NULL),
(1905, 9, 147, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'extrag', '0.00', NULL),
(1906, 9, 147, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'mediano', '0.00', NULL),
(1907, 9, 147, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'pequeno', '0.00', NULL),
(1908, 9, 147, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'porcion', '0.00', NULL),
(1909, 9, 105, '2025-01-27 21:48:36', '2025-01-27 21:48:36', 'unico', '0.00', NULL),
(1910, 9, 105, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'grande', '0.00', NULL),
(1911, 9, 105, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'extrag', '0.00', NULL),
(1912, 9, 105, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'mediano', '0.00', NULL),
(1913, 9, 105, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'pequeno', '0.00', NULL),
(1914, 9, 105, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'porcion', '0.00', NULL),
(1915, 9, 28, '2025-01-27 21:48:36', '2025-01-27 21:48:36', 'unico', '0.00', NULL),
(1916, 9, 28, '2025-01-27 21:48:36', '2025-02-02 10:32:31', 'grande', '0.00', NULL),
(1917, 9, 28, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'extrag', '0.00', NULL),
(1918, 9, 28, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'mediano', '0.00', NULL),
(1919, 9, 28, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'pequeno', '0.00', NULL),
(1920, 9, 28, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'porcion', '0.00', NULL),
(1921, 9, 236, '2025-01-27 21:48:36', '2025-01-27 21:48:36', 'unico', '0.00', NULL),
(1922, 9, 236, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'grande', '0.00', NULL),
(1923, 9, 236, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'extrag', '0.00', NULL),
(1924, 9, 236, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'mediano', '0.00', NULL),
(1925, 9, 236, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'pequeno', '0.00', NULL),
(1926, 9, 236, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'porcion', '0.00', NULL),
(1927, 9, 235, '2025-01-27 21:48:36', '2025-01-27 21:48:36', 'unico', '0.00', NULL),
(1928, 9, 235, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'grande', '0.00', NULL),
(1929, 9, 235, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'extrag', '0.00', NULL),
(1930, 9, 235, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'mediano', '0.00', NULL),
(1931, 9, 235, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'pequeno', '0.00', NULL),
(1932, 9, 235, '2025-01-27 21:48:36', '2025-02-02 10:32:32', 'porcion', '0.00', NULL),
(1933, 1, 28, '2025-01-29 18:06:58', '2025-01-29 18:06:58', 'unico', '0.00', NULL),
(1934, 1, 28, '2025-01-29 18:06:58', '2025-02-02 10:29:24', 'grande', '0.00', NULL),
(1935, 1, 28, '2025-01-29 18:06:58', '2025-02-02 10:29:24', 'extrag', '0.00', NULL),
(1936, 1, 28, '2025-01-29 18:06:59', '2025-02-02 10:29:24', 'mediano', '0.00', NULL),
(1937, 1, 28, '2025-01-29 18:06:59', '2025-02-02 10:29:24', 'pequeno', '0.00', NULL),
(1938, 1, 28, '2025-01-29 18:06:59', '2025-02-02 10:29:24', 'porcion', '0.00', NULL),
(1939, 91, 160, '2025-01-29 19:19:08', '2025-01-29 19:19:08', 'unico', '0.00', NULL),
(1940, 91, 160, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'grande', '0.00', NULL),
(1941, 91, 160, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'extrag', '0.00', NULL),
(1942, 91, 160, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'mediano', '0.00', NULL),
(1943, 91, 160, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'pequeno', '0.00', NULL),
(1944, 91, 160, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'porcion', '0.00', NULL),
(1945, 91, 112, '2025-01-29 19:19:08', '2025-01-29 19:19:08', 'unico', '0.00', NULL),
(1946, 91, 112, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'grande', '0.00', NULL),
(1947, 91, 112, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'extrag', '0.00', NULL),
(1948, 91, 112, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'mediano', '0.00', NULL),
(1949, 91, 112, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'pequeno', '0.00', NULL),
(1950, 91, 112, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'porcion', '0.00', NULL),
(1951, 91, 24, '2025-01-29 19:19:08', '2025-01-29 19:19:08', 'unico', '0.00', NULL),
(1952, 91, 24, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'grande', '0.00', NULL),
(1953, 91, 24, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'extrag', '0.00', NULL),
(1954, 91, 24, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'mediano', '0.00', NULL),
(1955, 91, 24, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'pequeno', '0.00', NULL),
(1956, 91, 24, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'porcion', '0.00', NULL),
(1957, 91, 28, '2025-01-29 19:19:08', '2025-01-29 19:19:08', 'unico', '0.00', NULL),
(1958, 91, 28, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'grande', '0.00', NULL),
(1959, 91, 28, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'extrag', '0.00', NULL),
(1960, 91, 28, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'mediano', '0.00', NULL),
(1961, 91, 28, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'pequeno', '0.00', NULL),
(1962, 91, 28, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'porcion', '0.00', NULL),
(1963, 91, 19, '2025-01-29 19:19:08', '2025-01-29 19:19:08', 'unico', '0.00', NULL),
(1964, 91, 19, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'grande', '0.00', NULL),
(1965, 91, 19, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'extrag', '0.00', NULL),
(1966, 91, 19, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'mediano', '0.00', NULL),
(1967, 91, 19, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'pequeno', '0.00', NULL),
(1968, 91, 19, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'porcion', '0.00', NULL),
(1969, 91, 31, '2025-01-29 19:19:08', '2025-01-29 19:19:08', 'unico', '0.00', NULL),
(1970, 91, 31, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'grande', '0.00', NULL),
(1971, 91, 31, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'extrag', '0.00', NULL),
(1972, 91, 31, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'mediano', '0.00', NULL),
(1973, 91, 31, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'pequeno', '0.00', NULL),
(1974, 91, 31, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'porcion', '0.00', NULL),
(1975, 91, 32, '2025-01-29 19:19:08', '2025-01-29 19:19:08', 'unico', '0.00', NULL),
(1976, 91, 32, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'grande', '0.00', NULL),
(1977, 91, 32, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'extrag', '0.00', NULL),
(1978, 91, 32, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'mediano', '0.00', NULL),
(1979, 91, 32, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'pequeno', '0.00', NULL),
(1980, 91, 32, '2025-01-29 19:19:08', '2025-02-01 13:39:58', 'porcion', '0.00', NULL),
(1981, 91, 33, '2025-01-29 19:19:08', '2025-01-29 19:19:08', 'unico', '0.00', NULL),
(1982, 91, 33, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'grande', '0.00', NULL),
(1983, 91, 33, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'extrag', '0.00', NULL),
(1984, 91, 33, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'mediano', '0.00', NULL),
(1985, 91, 33, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'pequeno', '0.00', NULL),
(1986, 91, 33, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'porcion', '0.00', NULL),
(1987, 91, 38, '2025-01-29 19:19:09', '2025-01-29 19:19:09', 'unico', '0.00', NULL),
(1988, 91, 38, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'grande', '0.00', NULL),
(1989, 91, 38, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'extrag', '0.00', NULL),
(1990, 91, 38, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'mediano', '0.00', NULL),
(1991, 91, 38, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'pequeno', '0.00', NULL),
(1992, 91, 38, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'porcion', '0.00', NULL),
(1993, 91, 119, '2025-01-29 19:19:09', '2025-01-29 19:19:09', 'unico', '0.00', NULL),
(1994, 91, 119, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'grande', '0.00', NULL),
(1995, 91, 119, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'extrag', '0.00', NULL),
(1996, 91, 119, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'mediano', '0.00', NULL),
(1997, 91, 119, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'pequeno', '0.00', NULL),
(1998, 91, 119, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'porcion', '0.00', NULL),
(1999, 91, 12, '2025-01-29 19:19:09', '2025-01-29 19:19:09', 'unico', '0.00', NULL),
(2000, 91, 12, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'grande', '0.00', NULL),
(2001, 91, 12, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'extrag', '0.00', NULL),
(2002, 91, 12, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'mediano', '0.00', NULL),
(2003, 91, 12, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'pequeno', '0.00', NULL),
(2004, 91, 12, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'porcion', '0.00', NULL),
(2005, 91, 83, '2025-01-29 19:19:09', '2025-01-29 19:19:09', 'unico', '0.00', NULL),
(2006, 91, 83, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'grande', '0.00', NULL),
(2007, 91, 83, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'extrag', '0.00', NULL),
(2008, 91, 83, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'mediano', '0.00', NULL),
(2009, 91, 83, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'pequeno', '0.00', NULL),
(2010, 91, 83, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'porcion', '0.00', NULL),
(2011, 91, 37, '2025-01-29 19:19:09', '2025-01-29 19:19:09', 'unico', '0.00', NULL),
(2012, 91, 37, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'grande', '0.00', NULL),
(2013, 91, 37, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'extrag', '0.00', NULL),
(2014, 91, 37, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'mediano', '0.00', NULL),
(2015, 91, 37, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'pequeno', '0.00', NULL),
(2016, 91, 37, '2025-01-29 19:19:09', '2025-02-01 13:39:58', 'porcion', '0.00', NULL),
(2017, 3, 204, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'unico', '1.00', NULL),
(2018, 3, 204, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'grande', '0.00', NULL),
(2019, 3, 204, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'extrag', '0.00', NULL),
(2020, 3, 204, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'mediano', '0.00', NULL),
(2021, 3, 204, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'pequeno', '0.00', NULL),
(2022, 3, 204, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'porcion', '0.00', NULL),
(2023, 3, 244, '2025-01-29 19:23:27', '2025-01-29 19:23:27', 'unico', '0.00', NULL),
(2024, 3, 244, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'grande', '0.00', NULL),
(2025, 3, 244, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'extrag', '0.00', NULL),
(2026, 3, 244, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'mediano', '0.00', NULL),
(2027, 3, 244, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'pequeno', '0.00', NULL),
(2028, 3, 244, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'porcion', '0.00', NULL),
(2029, 3, 245, '2025-01-29 19:23:27', '2025-01-29 19:23:27', 'unico', '0.00', NULL),
(2030, 3, 245, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'grande', '0.00', NULL),
(2031, 3, 245, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'extrag', '0.00', NULL),
(2032, 3, 245, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'mediano', '0.00', NULL),
(2033, 3, 245, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'pequeno', '0.00', NULL),
(2034, 3, 245, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'porcion', '0.00', NULL),
(2035, 3, 103, '2025-01-29 19:23:27', '2025-01-29 19:23:27', 'unico', '0.00', NULL),
(2036, 3, 103, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'grande', '0.00', NULL),
(2037, 3, 103, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'extrag', '0.00', NULL),
(2038, 3, 103, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'mediano', '0.00', NULL),
(2039, 3, 103, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'pequeno', '0.00', NULL),
(2040, 3, 103, '2025-01-29 19:23:27', '2025-03-02 22:28:57', 'porcion', '0.00', NULL),
(2041, 3, 147, '2025-01-29 19:23:28', '2025-01-29 19:23:28', 'unico', '0.00', NULL),
(2042, 3, 147, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'grande', '0.00', NULL),
(2043, 3, 147, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'extrag', '0.00', NULL),
(2044, 3, 147, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'mediano', '0.00', NULL),
(2045, 3, 147, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'pequeno', '0.00', NULL),
(2046, 3, 147, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'porcion', '0.00', NULL),
(2047, 3, 28, '2025-01-29 19:23:28', '2025-01-29 19:23:28', 'unico', '0.00', NULL),
(2048, 3, 28, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'grande', '0.00', NULL),
(2049, 3, 28, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'extrag', '0.00', NULL),
(2050, 3, 28, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'mediano', '0.00', NULL);
INSERT INTO `gapp_producto_ingrediente` (`id`, `producto_id`, `ingrediente_id`, `created_at`, `updated_at`, `tamano`, `cantidad`, `visible`) VALUES
(2051, 3, 28, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'pequeno', '0.00', NULL),
(2052, 3, 28, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'porcion', '0.00', NULL),
(2053, 3, 236, '2025-01-29 19:23:28', '2025-01-29 19:23:28', 'unico', '0.00', NULL),
(2054, 3, 236, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'grande', '0.00', NULL),
(2055, 3, 236, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'extrag', '0.00', NULL),
(2056, 3, 236, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'mediano', '0.00', NULL),
(2057, 3, 236, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'pequeno', '0.00', NULL),
(2058, 3, 236, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'porcion', '0.00', NULL),
(2059, 3, 235, '2025-01-29 19:23:28', '2025-01-29 19:23:28', 'unico', '0.00', NULL),
(2060, 3, 235, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'grande', '0.00', NULL),
(2061, 3, 235, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'extrag', '0.00', NULL),
(2062, 3, 235, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'mediano', '0.00', NULL),
(2063, 3, 235, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'pequeno', '0.00', NULL),
(2064, 3, 235, '2025-01-29 19:23:28', '2025-03-02 22:28:57', 'porcion', '0.00', NULL),
(2065, 35, 38, '2025-01-29 20:23:41', '2025-01-29 20:23:41', 'unico', '0.00', NULL),
(2066, 35, 38, '2025-01-29 20:23:41', '2025-02-01 13:52:31', 'grande', '0.00', NULL),
(2067, 35, 38, '2025-01-29 20:23:41', '2025-02-01 13:52:31', 'extrag', '0.00', NULL),
(2068, 35, 38, '2025-01-29 20:23:41', '2025-02-01 13:52:31', 'mediano', '0.00', NULL),
(2069, 35, 38, '2025-01-29 20:23:41', '2025-02-01 13:52:31', 'pequeno', '0.00', NULL),
(2070, 35, 38, '2025-01-29 20:23:41', '2025-02-01 13:52:31', 'porcion', '0.00', NULL),
(2071, 34, 38, '2025-01-29 20:23:49', '2025-01-29 20:23:49', 'unico', '0.00', NULL),
(2072, 34, 38, '2025-01-29 20:23:49', '2025-02-01 13:52:04', 'grande', '0.00', NULL),
(2073, 34, 38, '2025-01-29 20:23:49', '2025-02-01 13:52:04', 'extrag', '0.00', NULL),
(2074, 34, 38, '2025-01-29 20:23:49', '2025-02-01 13:52:04', 'mediano', '0.00', NULL),
(2075, 34, 38, '2025-01-29 20:23:49', '2025-02-01 13:52:04', 'pequeno', '0.00', NULL),
(2076, 34, 38, '2025-01-29 20:23:49', '2025-02-01 13:52:04', 'porcion', '0.00', NULL),
(2077, 36, 38, '2025-01-29 20:24:03', '2025-01-29 20:24:03', 'unico', '0.00', NULL),
(2078, 36, 38, '2025-01-29 20:24:03', '2025-01-29 20:24:03', 'grande', '0.00', NULL),
(2079, 36, 38, '2025-01-29 20:24:03', '2025-01-29 20:24:03', 'extrag', '0.00', NULL),
(2080, 36, 38, '2025-01-29 20:24:03', '2025-01-29 20:24:03', 'mediano', '0.00', NULL),
(2081, 36, 38, '2025-01-29 20:24:03', '2025-01-29 20:24:03', 'pequeno', '0.00', NULL),
(2082, 36, 38, '2025-01-29 20:24:03', '2025-01-29 20:24:03', 'porcion', '0.00', NULL),
(2083, 37, 38, '2025-01-29 20:24:11', '2025-01-29 20:24:11', 'unico', '0.00', NULL),
(2084, 37, 38, '2025-01-29 20:24:11', '2025-01-29 20:24:11', 'grande', '0.00', NULL),
(2085, 37, 38, '2025-01-29 20:24:11', '2025-01-29 20:24:11', 'extrag', '0.00', NULL),
(2086, 37, 38, '2025-01-29 20:24:11', '2025-01-29 20:24:11', 'mediano', '0.00', NULL),
(2087, 37, 38, '2025-01-29 20:24:11', '2025-01-29 20:24:11', 'pequeno', '0.00', NULL),
(2088, 37, 38, '2025-01-29 20:24:11', '2025-01-29 20:24:11', 'porcion', '0.00', NULL),
(2089, 3, 105, '2025-01-29 22:46:15', '2025-01-29 22:46:15', 'unico', '0.00', NULL),
(2090, 3, 105, '2025-01-29 22:46:15', '2025-03-02 22:28:57', 'grande', '0.00', NULL),
(2091, 3, 105, '2025-01-29 22:46:15', '2025-03-02 22:28:57', 'extrag', '0.00', NULL),
(2092, 3, 105, '2025-01-29 22:46:15', '2025-03-02 22:28:57', 'mediano', '0.00', NULL),
(2093, 3, 105, '2025-01-29 22:46:15', '2025-03-02 22:28:57', 'pequeno', '0.00', NULL),
(2094, 3, 105, '2025-01-29 22:46:15', '2025-03-02 22:28:57', 'porcion', '0.00', NULL),
(2095, 32, 204, '2025-01-30 20:12:42', '2025-01-30 20:12:42', 'unico', '0.00', NULL),
(2096, 32, 204, '2025-01-30 20:12:42', '2025-02-01 13:44:03', 'grande', '0.00', NULL),
(2097, 32, 204, '2025-01-30 20:12:42', '2025-02-01 13:44:03', 'extrag', '0.00', NULL),
(2098, 32, 204, '2025-01-30 20:12:42', '2025-02-01 13:44:03', 'mediano', '0.00', NULL),
(2099, 32, 204, '2025-01-30 20:12:42', '2025-02-01 13:44:03', 'pequeno', '0.00', NULL),
(2100, 32, 204, '2025-01-30 20:12:42', '2025-02-01 13:44:03', 'porcion', '0.00', NULL),
(2101, 32, 244, '2025-01-30 20:12:42', '2025-01-30 20:12:42', 'unico', '0.00', NULL),
(2102, 32, 244, '2025-01-30 20:12:42', '2025-02-01 13:44:03', 'grande', '0.00', NULL),
(2103, 32, 244, '2025-01-30 20:12:42', '2025-02-01 13:44:03', 'extrag', '0.00', NULL),
(2104, 32, 244, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'mediano', '0.00', NULL),
(2105, 32, 244, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'pequeno', '0.00', NULL),
(2106, 32, 244, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'porcion', '0.00', NULL),
(2107, 32, 245, '2025-01-30 20:12:43', '2025-01-30 20:12:43', 'unico', '0.00', NULL),
(2108, 32, 245, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'grande', '0.00', NULL),
(2109, 32, 245, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'extrag', '0.00', NULL),
(2110, 32, 245, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'mediano', '0.00', NULL),
(2111, 32, 245, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'pequeno', '0.00', NULL),
(2112, 32, 245, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'porcion', '0.00', NULL),
(2113, 32, 147, '2025-01-30 20:12:43', '2025-01-30 20:12:43', 'unico', '0.00', NULL),
(2114, 32, 147, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'grande', '0.00', NULL),
(2115, 32, 147, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'extrag', '0.00', NULL),
(2116, 32, 147, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'mediano', '0.00', NULL),
(2117, 32, 147, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'pequeno', '0.00', NULL),
(2118, 32, 147, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'porcion', '0.00', NULL),
(2119, 32, 105, '2025-01-30 20:12:43', '2025-01-30 20:12:43', 'unico', '0.00', NULL),
(2120, 32, 105, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'grande', '0.00', NULL),
(2121, 32, 105, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'extrag', '0.00', NULL),
(2122, 32, 105, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'mediano', '0.00', NULL),
(2123, 32, 105, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'pequeno', '0.00', NULL),
(2124, 32, 105, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'porcion', '0.00', NULL),
(2125, 32, 28, '2025-01-30 20:12:43', '2025-01-30 20:12:43', 'unico', '0.00', NULL),
(2126, 32, 28, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'grande', '0.00', NULL),
(2127, 32, 28, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'extrag', '0.00', NULL),
(2128, 32, 28, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'mediano', '0.00', NULL),
(2129, 32, 28, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'pequeno', '0.00', NULL),
(2130, 32, 28, '2025-01-30 20:12:43', '2025-02-01 13:44:03', 'porcion', '0.00', NULL),
(2131, 68, 219, '2025-02-01 11:35:53', '2025-02-01 11:35:53', 'unico', '0.00', NULL),
(2132, 68, 219, '2025-02-01 11:35:53', '2025-02-01 13:41:33', 'grande', '0.00', NULL),
(2133, 68, 219, '2025-02-01 11:35:53', '2025-02-01 13:41:33', 'extrag', '0.00', NULL),
(2134, 68, 219, '2025-02-01 11:35:53', '2025-02-01 13:41:33', 'mediano', '0.00', NULL),
(2135, 68, 219, '2025-02-01 11:35:53', '2025-02-01 13:41:33', 'pequeno', '0.00', NULL),
(2136, 68, 219, '2025-02-01 11:35:53', '2025-02-01 13:41:33', 'porcion', '0.00', NULL),
(2137, 10, 251, '2025-02-02 10:28:10', '2025-02-02 10:28:10', 'unico', '0.00', NULL),
(2138, 10, 251, '2025-02-02 10:28:10', '2025-02-02 10:37:46', 'grande', '0.00', NULL),
(2139, 10, 251, '2025-02-02 10:28:10', '2025-02-02 10:37:46', 'extrag', '0.00', NULL),
(2140, 10, 251, '2025-02-02 10:28:10', '2025-02-02 10:37:46', 'mediano', '0.00', NULL),
(2141, 10, 251, '2025-02-02 10:28:10', '2025-02-02 10:37:46', 'pequeno', '0.00', NULL),
(2142, 10, 251, '2025-02-02 10:28:10', '2025-02-02 10:37:46', 'porcion', '0.00', NULL),
(2143, 11, 251, '2025-02-02 10:28:29', '2025-02-02 10:28:29', 'unico', '0.00', NULL),
(2144, 11, 251, '2025-02-02 10:28:29', '2025-02-02 10:37:55', 'grande', '0.00', NULL),
(2145, 11, 251, '2025-02-02 10:28:29', '2025-02-02 10:37:55', 'extrag', '0.00', NULL),
(2146, 11, 251, '2025-02-02 10:28:29', '2025-02-02 10:37:55', 'mediano', '0.00', NULL),
(2147, 11, 251, '2025-02-02 10:28:29', '2025-02-02 10:37:55', 'pequeno', '0.00', NULL),
(2148, 11, 251, '2025-02-02 10:28:29', '2025-02-02 10:37:55', 'porcion', '0.00', NULL),
(2149, 12, 251, '2025-02-02 10:28:40', '2025-02-02 10:28:40', 'unico', '0.00', NULL),
(2150, 12, 251, '2025-02-02 10:28:40', '2025-02-02 10:36:51', 'grande', '0.00', NULL),
(2151, 12, 251, '2025-02-02 10:28:40', '2025-02-02 10:36:51', 'extrag', '0.00', NULL),
(2152, 12, 251, '2025-02-02 10:28:40', '2025-02-02 10:36:51', 'mediano', '0.00', NULL),
(2153, 12, 251, '2025-02-02 10:28:40', '2025-02-02 10:36:51', 'pequeno', '0.00', NULL),
(2154, 12, 251, '2025-02-02 10:28:40', '2025-02-02 10:36:51', 'porcion', '0.00', NULL),
(2155, 13, 251, '2025-02-02 10:28:49', '2025-02-02 10:28:49', 'unico', '0.00', NULL),
(2156, 13, 251, '2025-02-02 10:28:49', '2025-02-02 10:37:19', 'grande', '0.00', NULL),
(2157, 13, 251, '2025-02-02 10:28:49', '2025-02-02 10:37:19', 'extrag', '0.00', NULL),
(2158, 13, 251, '2025-02-02 10:28:49', '2025-02-02 10:37:19', 'mediano', '0.00', NULL),
(2159, 13, 251, '2025-02-02 10:28:49', '2025-02-02 10:37:19', 'pequeno', '0.00', NULL),
(2160, 13, 251, '2025-02-02 10:28:49', '2025-02-02 10:37:19', 'porcion', '0.00', NULL),
(2161, 1, 251, '2025-02-02 10:29:24', '2025-02-02 10:29:24', 'unico', '0.00', NULL),
(2162, 1, 251, '2025-02-02 10:29:24', '2025-02-02 10:29:24', 'grande', '0.00', NULL),
(2163, 1, 251, '2025-02-02 10:29:24', '2025-02-02 10:29:24', 'extrag', '0.00', NULL),
(2164, 1, 251, '2025-02-02 10:29:24', '2025-02-02 10:29:24', 'mediano', '0.00', NULL),
(2165, 1, 251, '2025-02-02 10:29:24', '2025-02-02 10:29:24', 'pequeno', '0.00', NULL),
(2166, 1, 251, '2025-02-02 10:29:24', '2025-02-02 10:29:24', 'porcion', '0.00', NULL),
(2167, 2, 251, '2025-02-02 10:29:33', '2025-02-02 10:29:33', 'unico', '0.00', NULL),
(2168, 2, 251, '2025-02-02 10:29:33', '2025-02-02 10:29:33', 'grande', '0.00', NULL),
(2169, 2, 251, '2025-02-02 10:29:33', '2025-02-02 10:29:33', 'extrag', '0.00', NULL),
(2170, 2, 251, '2025-02-02 10:29:33', '2025-02-02 10:29:33', 'mediano', '0.00', NULL),
(2171, 2, 251, '2025-02-02 10:29:33', '2025-02-02 10:29:33', 'pequeno', '0.00', NULL),
(2172, 2, 251, '2025-02-02 10:29:33', '2025-02-02 10:29:33', 'porcion', '0.00', NULL),
(2173, 3, 251, '2025-02-02 10:29:43', '2025-02-02 10:29:43', 'unico', '0.00', NULL),
(2174, 3, 251, '2025-02-02 10:29:43', '2025-03-02 22:28:57', 'grande', '0.00', NULL),
(2175, 3, 251, '2025-02-02 10:29:43', '2025-03-02 22:28:57', 'extrag', '0.00', NULL),
(2176, 3, 251, '2025-02-02 10:29:43', '2025-03-02 22:28:57', 'mediano', '0.00', NULL),
(2177, 3, 251, '2025-02-02 10:29:43', '2025-03-02 22:28:57', 'pequeno', '0.00', NULL),
(2178, 3, 251, '2025-02-02 10:29:43', '2025-03-02 22:28:57', 'porcion', '0.00', NULL),
(2179, 4, 251, '2025-02-02 10:30:24', '2025-02-02 10:30:24', 'unico', '0.00', NULL),
(2180, 4, 251, '2025-02-02 10:30:24', '2025-02-02 10:30:56', 'grande', '0.00', NULL),
(2181, 4, 251, '2025-02-02 10:30:24', '2025-02-02 10:30:56', 'extrag', '0.00', NULL),
(2182, 4, 251, '2025-02-02 10:30:24', '2025-02-02 10:30:56', 'mediano', '0.00', NULL),
(2183, 4, 251, '2025-02-02 10:30:24', '2025-02-02 10:30:56', 'pequeno', '0.00', NULL),
(2184, 4, 251, '2025-02-02 10:30:24', '2025-02-02 10:30:56', 'porcion', '0.00', NULL),
(2185, 5, 251, '2025-02-02 10:31:13', '2025-02-02 10:31:13', 'unico', '0.00', NULL),
(2186, 5, 251, '2025-02-02 10:31:13', '2025-02-02 10:31:13', 'grande', '0.00', NULL),
(2187, 5, 251, '2025-02-02 10:31:13', '2025-02-02 10:31:13', 'extrag', '0.00', NULL),
(2188, 5, 251, '2025-02-02 10:31:13', '2025-02-02 10:31:13', 'mediano', '0.00', NULL),
(2189, 5, 251, '2025-02-02 10:31:13', '2025-02-02 10:31:13', 'pequeno', '0.00', NULL),
(2190, 5, 251, '2025-02-02 10:31:13', '2025-02-02 10:31:13', 'porcion', '0.00', NULL),
(2191, 7, 251, '2025-02-02 10:31:56', '2025-02-02 10:31:56', 'unico', '0.00', NULL),
(2192, 7, 251, '2025-02-02 10:31:56', '2025-02-02 10:31:56', 'grande', '0.00', NULL),
(2193, 7, 251, '2025-02-02 10:31:56', '2025-02-02 10:31:56', 'extrag', '0.00', NULL),
(2194, 7, 251, '2025-02-02 10:31:56', '2025-02-02 10:31:56', 'mediano', '0.00', NULL),
(2195, 7, 251, '2025-02-02 10:31:56', '2025-02-02 10:31:56', 'pequeno', '0.00', NULL),
(2196, 7, 251, '2025-02-02 10:31:56', '2025-02-02 10:31:56', 'porcion', '0.00', NULL),
(2197, 8, 251, '2025-02-02 10:32:12', '2025-02-02 10:32:12', 'unico', '0.00', NULL),
(2198, 8, 251, '2025-02-02 10:32:12', '2025-02-02 10:32:19', 'grande', '0.00', NULL),
(2199, 8, 251, '2025-02-02 10:32:12', '2025-02-02 10:32:19', 'extrag', '0.00', NULL),
(2200, 8, 251, '2025-02-02 10:32:12', '2025-02-02 10:32:19', 'mediano', '0.00', NULL),
(2201, 8, 251, '2025-02-02 10:32:12', '2025-02-02 10:32:19', 'pequeno', '0.00', NULL),
(2202, 8, 251, '2025-02-02 10:32:12', '2025-02-02 10:32:19', 'porcion', '0.00', NULL),
(2203, 9, 251, '2025-02-02 10:32:31', '2025-02-02 10:32:31', 'unico', '0.00', NULL),
(2204, 9, 251, '2025-02-02 10:32:31', '2025-02-02 10:32:31', 'grande', '0.00', NULL),
(2205, 9, 251, '2025-02-02 10:32:31', '2025-02-02 10:32:31', 'extrag', '0.00', NULL),
(2206, 9, 251, '2025-02-02 10:32:31', '2025-02-02 10:32:31', 'mediano', '0.00', NULL),
(2207, 9, 251, '2025-02-02 10:32:31', '2025-02-02 10:32:31', 'pequeno', '0.00', NULL),
(2208, 9, 251, '2025-02-02 10:32:31', '2025-02-02 10:32:31', 'porcion', '0.00', NULL),
(2209, 78, 142, '2025-03-02 22:37:59', '2025-03-02 22:37:59', 'unico', '1.00', NULL),
(2210, 78, 142, '2025-03-02 22:37:59', '2025-03-02 22:37:59', 'grande', '0.00', NULL),
(2211, 78, 142, '2025-03-02 22:37:59', '2025-03-02 22:37:59', 'extrag', '0.00', NULL),
(2212, 78, 142, '2025-03-02 22:37:59', '2025-03-02 22:37:59', 'mediano', '0.00', NULL),
(2213, 78, 142, '2025-03-02 22:37:59', '2025-03-02 22:37:59', 'pequeno', '0.00', NULL),
(2214, 78, 142, '2025-03-02 22:37:59', '2025-03-02 22:37:59', 'porcion', '0.00', NULL),
(2215, 43, 245, '2025-03-02 22:56:12', '2025-03-02 22:56:12', 'unico', '1.00', NULL),
(2216, 43, 245, '2025-03-02 22:56:12', '2025-04-13 17:05:14', 'grande', '0.00', NULL),
(2217, 43, 245, '2025-03-02 22:56:12', '2025-04-13 17:05:14', 'extrag', '0.00', NULL),
(2218, 43, 245, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'mediano', '0.00', NULL),
(2219, 43, 245, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'pequeno', '0.00', NULL),
(2220, 43, 245, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'porcion', '0.00', NULL),
(2221, 43, 151, '2025-03-02 22:56:12', '2025-03-02 22:56:12', 'unico', '1.00', NULL),
(2222, 43, 151, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'grande', '0.00', NULL),
(2223, 43, 151, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'extrag', '0.00', NULL),
(2224, 43, 151, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'mediano', '0.00', NULL),
(2225, 43, 151, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'pequeno', '0.00', NULL),
(2226, 43, 151, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'porcion', '0.00', NULL),
(2227, 43, 219, '2025-03-02 22:56:12', '2025-03-02 22:56:12', 'unico', '1.00', NULL),
(2228, 43, 219, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'grande', '0.00', NULL),
(2229, 43, 219, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'extrag', '0.00', NULL),
(2230, 43, 219, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'mediano', '0.00', NULL),
(2231, 43, 219, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'pequeno', '0.00', NULL),
(2232, 43, 219, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'porcion', '0.00', NULL),
(2233, 43, 35, '2025-03-02 22:56:12', '2025-03-02 22:56:12', 'unico', '1.00', NULL),
(2234, 43, 35, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'grande', '0.00', NULL),
(2235, 43, 35, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'extrag', '0.00', NULL),
(2236, 43, 35, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'mediano', '0.00', NULL),
(2237, 43, 35, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'pequeno', '0.00', NULL),
(2238, 43, 35, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'porcion', '0.00', NULL),
(2239, 43, 228, '2025-03-02 22:56:12', '2025-03-02 22:56:12', 'unico', '1.00', NULL),
(2240, 43, 228, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'grande', '0.00', NULL),
(2241, 43, 228, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'extrag', '0.00', NULL),
(2242, 43, 228, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'mediano', '0.00', NULL),
(2243, 43, 228, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'pequeno', '0.00', NULL),
(2244, 43, 228, '2025-03-02 22:56:12', '2025-04-13 17:05:15', 'porcion', '0.00', NULL),
(2245, 44, 204, '2025-03-02 22:56:41', '2025-03-02 22:56:41', 'unico', '0.00', NULL),
(2246, 44, 204, '2025-03-02 22:56:41', '2025-03-02 22:56:56', 'grande', '0.00', NULL),
(2247, 44, 204, '2025-03-02 22:56:41', '2025-03-02 22:56:56', 'extrag', '0.00', NULL),
(2248, 44, 204, '2025-03-02 22:56:41', '2025-03-02 22:56:56', 'mediano', '0.00', NULL),
(2249, 44, 204, '2025-03-02 22:56:41', '2025-03-02 22:56:56', 'pequeno', '0.00', NULL),
(2250, 44, 204, '2025-03-02 22:56:41', '2025-03-02 22:56:56', 'porcion', '0.00', NULL),
(2251, 44, 245, '2025-03-02 22:56:41', '2025-03-02 22:56:41', 'unico', '1.00', NULL),
(2252, 44, 245, '2025-03-02 22:56:41', '2025-03-02 22:56:56', 'grande', '0.00', NULL),
(2253, 44, 245, '2025-03-02 22:56:41', '2025-03-02 22:56:56', 'extrag', '0.00', NULL),
(2254, 44, 245, '2025-03-02 22:56:41', '2025-03-02 22:56:56', 'mediano', '0.00', NULL),
(2255, 44, 245, '2025-03-02 22:56:41', '2025-03-02 22:56:56', 'pequeno', '0.00', NULL),
(2256, 44, 245, '2025-03-02 22:56:41', '2025-03-02 22:56:56', 'porcion', '0.00', NULL),
(2257, 44, 151, '2025-03-02 22:56:41', '2025-03-02 22:56:41', 'unico', '0.00', NULL),
(2258, 44, 151, '2025-03-02 22:56:41', '2025-03-02 22:56:56', 'grande', '0.00', NULL),
(2259, 44, 151, '2025-03-02 22:56:41', '2025-03-02 22:56:56', 'extrag', '0.00', NULL),
(2260, 44, 151, '2025-03-02 22:56:41', '2025-03-02 22:56:56', 'mediano', '0.00', NULL),
(2261, 44, 151, '2025-03-02 22:56:41', '2025-03-02 22:56:56', 'pequeno', '0.00', NULL),
(2262, 44, 151, '2025-03-02 22:56:41', '2025-03-02 22:56:56', 'porcion', '0.00', NULL),
(2263, 73, 252, '2025-03-02 22:59:35', '2025-03-02 22:59:35', 'unico', '1.00', NULL),
(2264, 73, 252, '2025-03-02 22:59:35', '2025-03-02 22:59:35', 'grande', '0.00', NULL),
(2265, 73, 252, '2025-03-02 22:59:35', '2025-03-02 22:59:35', 'extrag', '0.00', NULL),
(2266, 73, 252, '2025-03-02 22:59:35', '2025-03-02 22:59:35', 'mediano', '0.00', NULL),
(2267, 73, 252, '2025-03-02 22:59:35', '2025-03-02 22:59:35', 'pequeno', '0.00', NULL),
(2268, 73, 252, '2025-03-02 22:59:35', '2025-03-02 22:59:35', 'porcion', '0.00', NULL),
(2269, 74, 253, '2025-03-02 23:00:42', '2025-03-02 23:00:42', 'unico', '1.00', NULL),
(2270, 74, 253, '2025-03-02 23:00:42', '2025-03-02 23:00:42', 'grande', '0.00', NULL),
(2271, 74, 253, '2025-03-02 23:00:42', '2025-03-02 23:00:42', 'extrag', '0.00', NULL),
(2272, 74, 253, '2025-03-02 23:00:42', '2025-03-02 23:00:42', 'mediano', '0.00', NULL),
(2273, 74, 253, '2025-03-02 23:00:42', '2025-03-02 23:00:42', 'pequeno', '0.00', NULL),
(2274, 74, 253, '2025-03-02 23:00:42', '2025-03-02 23:00:42', 'porcion', '0.00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_producto_pedido`
--

CREATE TABLE `gapp_producto_pedido` (
  `id` int(10) NOT NULL,
  `pedido_id` int(10) NOT NULL,
  `producto_id` int(10) NOT NULL,
  `cant` int(5) DEFAULT '1',
  `valor` decimal(14,2) DEFAULT NULL,
  `total` decimal(14,2) DEFAULT NULL,
  `obs` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `comanda` tinyint(4) DEFAULT '0',
  `preparado` datetime DEFAULT NULL,
  `combo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `entregado` tinyint(4) NOT NULL DEFAULT '0',
  `comanda2` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_producto_pedido_adicional`
--

CREATE TABLE `gapp_producto_pedido_adicional` (
  `id` int(10) NOT NULL,
  `producto_pedido_id` int(10) NOT NULL,
  `adicional_id` int(10) NOT NULL,
  `cant` int(5) DEFAULT NULL,
  `valor` decimal(14,2) DEFAULT NULL,
  `total` decimal(14,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `cambio` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_producto_pedido_documento`
--

CREATE TABLE `gapp_producto_pedido_documento` (
  `id` int(10) NOT NULL,
  `producto_id` int(10) NOT NULL,
  `cant` int(5) NOT NULL,
  `valor` decimal(14,2) NOT NULL,
  `total` decimal(14,2) NOT NULL,
  `producto_pedido_id` int(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_producto_pedido_ingrediente`
--

CREATE TABLE `gapp_producto_pedido_ingrediente` (
  `id` int(10) NOT NULL,
  `producto_pedido_id` int(10) NOT NULL,
  `ingrediente_id` int(10) NOT NULL,
  `cant` int(5) DEFAULT NULL,
  `valor` decimal(14,2) DEFAULT NULL,
  `total` decimal(14,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_producto_sabor`
--

CREATE TABLE `gapp_producto_sabor` (
  `id` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `producto_id` int(11) NOT NULL,
  `sabor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_producto_tamano`
--

CREATE TABLE `gapp_producto_tamano` (
  `id` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `tamano` varchar(10) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `valor` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `gapp_producto_tamano`
--

INSERT INTO `gapp_producto_tamano` (`id`, `updated_at`, `created_at`, `tamano`, `producto_id`, `valor`) VALUES
(1, '2025-01-22 20:54:37', '2025-01-22 20:54:37', 'unico', 1, 17000),
(2, '2025-01-24 12:43:38', '2025-01-24 12:43:38', 'unico', 2, 17000),
(3, '2025-01-24 12:45:23', '2025-01-24 12:45:23', 'unico', 3, 19000),
(4, '2025-01-24 12:46:12', '2025-01-24 12:46:12', 'unico', 4, 17000),
(5, '2025-01-24 12:47:29', '2025-01-24 12:47:29', 'unico', 5, 17000),
(6, '2025-01-24 12:48:11', '2025-01-24 12:48:11', 'unico', 6, 17000),
(7, '2025-01-24 12:49:28', '2025-01-24 12:49:28', 'unico', 7, 21000),
(8, '2025-01-24 12:50:14', '2025-01-24 12:49:50', 'unico', 8, 28000),
(9, '2025-01-24 12:51:50', '2025-01-24 12:51:50', 'unico', 9, 17000),
(10, '2025-01-24 12:53:46', '2025-01-24 12:53:46', 'unico', 10, 20000),
(11, '2025-01-24 12:55:25', '2025-01-24 12:55:09', 'unico', 11, 33000),
(12, '2025-01-24 13:00:09', '2025-01-24 13:00:09', 'unico', 12, 20000),
(13, '2025-01-24 13:01:01', '2025-01-24 13:01:01', 'unico', 13, 25000),
(14, '2025-01-24 13:01:31', '2025-01-24 13:01:31', 'unico', 14, 7000),
(15, '2025-01-24 13:01:58', '2025-01-24 13:01:58', 'unico', 15, 3500),
(16, '2025-01-24 13:02:26', '2025-01-24 13:02:26', 'unico', 16, 3500),
(17, '2025-01-24 13:04:45', '2025-01-24 13:04:45', 'unico', 17, 3500),
(18, '2025-01-24 13:06:09', '2025-01-24 13:05:16', 'unico', 18, 4500),
(19, '2025-01-24 13:05:46', '2025-01-24 13:05:46', 'unico', 19, 3000),
(20, '2025-01-24 13:06:59', '2025-01-24 13:06:59', 'unico', 20, 4000),
(21, '2025-01-24 13:07:26', '2025-01-24 13:07:26', 'unico', 21, 6000),
(22, '2025-01-24 13:07:53', '2025-01-24 13:07:53', 'unico', 22, 3500),
(23, '2025-01-24 13:08:16', '2025-01-24 13:08:16', 'unico', 23, 2500),
(24, '2025-01-24 13:09:41', '2025-01-24 13:09:41', 'unico', 24, 3000),
(25, '2025-01-24 13:10:19', '2025-01-24 13:10:19', 'unico', 25, 3500),
(26, '2025-01-24 13:11:00', '2025-01-24 13:11:00', 'unico', 26, 3500),
(27, '2025-01-24 13:11:25', '2025-01-24 13:11:25', 'unico', 27, 4000),
(28, '2025-01-29 19:21:49', '2025-01-24 13:11:56', 'unico', 28, 5000),
(29, '2025-01-24 13:28:07', '2025-01-24 13:28:07', 'unico', 29, 7000),
(30, '2025-01-24 13:29:01', '2025-01-24 13:29:01', 'unico', 30, 7000),
(31, '2025-01-24 17:12:14', '2025-01-24 17:12:14', 'unico', 31, 11000),
(32, '2025-01-24 17:14:26', '2025-01-24 17:14:26', 'unico', 32, 11000),
(33, '2025-01-24 17:15:01', '2025-01-24 17:15:01', 'unico', 33, 11000),
(34, '2025-01-24 17:18:37', '2025-01-24 17:18:37', 'unico', 34, 14000),
(35, '2025-01-24 17:19:21', '2025-01-24 17:18:53', 'unico', 35, 24000),
(36, '2025-01-24 17:21:14', '2025-01-24 17:20:24', 'unico', 36, 16000),
(37, '2025-01-24 17:24:09', '2025-01-24 17:21:40', 'unico', 37, 24000),
(38, '2025-01-24 17:25:42', '2025-01-24 17:25:42', 'unico', 38, 18000),
(39, '2025-01-24 17:26:11', '2025-01-24 17:26:00', 'unico', 39, 28000),
(40, '2025-01-24 17:28:31', '2025-01-24 17:26:32', 'unico', 40, 30000),
(41, '2025-01-24 22:29:48', '2025-01-24 22:29:48', 'unico', 41, 500),
(42, '2025-01-24 22:30:11', '2025-01-24 22:30:11', 'unico', 42, 1000),
(43, '2025-01-27 17:32:10', '2025-01-27 17:32:10', 'unico', 43, 5000),
(44, '2025-01-27 17:35:31', '2025-01-27 17:35:31', 'unico', 44, 6000),
(45, '2025-01-27 18:00:21', '2025-01-27 18:00:21', 'unico', 45, 5000),
(46, '2025-01-27 18:03:36', '2025-01-27 18:03:36', 'unico', 46, 4500),
(47, '2025-01-27 18:06:36', '2025-01-27 18:06:36', 'unico', 47, 4500),
(48, '2025-01-27 18:10:02', '2025-01-27 18:10:02', 'unico', 48, 4000),
(49, '2025-01-27 18:24:28', '2025-01-27 18:24:28', 'unico', 49, 5000),
(50, '2025-01-27 18:25:55', '2025-01-27 18:25:55', 'unico', 50, 5000),
(51, '2025-01-27 18:28:55', '2025-01-27 18:28:55', 'unico', 51, 1500),
(52, '2025-01-27 19:20:08', '2025-01-27 19:20:08', 'unico', 52, 3000),
(53, '2025-01-27 19:20:59', '2025-01-27 19:20:59', 'unico', 53, 4000),
(54, '2025-01-27 19:22:53', '2025-01-27 19:22:53', 'unico', 54, 3500),
(55, '2025-01-27 19:24:46', '2025-01-27 19:24:46', 'unico', 55, 2500),
(56, '2025-01-27 19:25:34', '2025-01-27 19:25:34', 'unico', 56, 1000),
(57, '2025-01-27 19:26:38', '2025-01-27 19:26:38', 'unico', 57, 3000),
(58, '2025-01-27 19:27:41', '2025-01-27 19:27:41', 'unico', 58, 2000),
(59, '2025-01-27 19:29:33', '2025-01-27 19:29:33', 'unico', 59, 8000),
(60, '2025-01-27 19:34:59', '2025-01-27 19:34:59', 'unico', 60, 7500),
(61, '2025-01-27 19:36:02', '2025-01-27 19:36:02', 'unico', 61, 6000),
(62, '2025-01-27 19:37:00', '2025-01-27 19:37:00', 'unico', 62, 6000),
(63, '2025-01-27 19:37:55', '2025-01-27 19:37:55', 'unico', 63, 7000),
(64, '2025-01-27 19:38:40', '2025-01-27 19:38:40', 'unico', 64, 3500),
(65, '2025-01-27 19:39:41', '2025-01-27 19:39:41', 'unico', 65, 4000),
(66, '2025-01-27 19:40:39', '2025-01-27 19:40:39', 'unico', 66, 6500),
(67, '2025-01-27 19:41:35', '2025-01-27 19:41:35', 'unico', 67, 6500),
(68, '2025-01-27 19:47:39', '2025-01-27 19:47:39', 'unico', 68, 10000),
(69, '2025-01-27 20:25:52', '2025-01-27 20:25:52', 'unico', 69, 14000),
(70, '2025-01-27 21:08:27', '2025-01-27 21:08:27', 'unico', 70, 17000),
(71, '2025-01-27 21:11:44', '2025-01-27 21:11:44', 'unico', 71, 14000),
(72, '2025-01-27 21:14:15', '2025-01-27 21:14:15', 'unico', 72, 19000),
(73, '2025-01-27 21:16:46', '2025-01-27 21:16:46', 'unico', 73, 11000),
(74, '2025-01-27 21:19:52', '2025-01-27 21:19:52', 'unico', 74, 12000),
(75, '2025-01-27 21:21:56', '2025-01-27 21:21:56', 'unico', 75, 10000),
(76, '2025-01-27 21:22:38', '2025-01-27 21:22:38', 'unico', 76, 11000),
(77, '2025-01-27 21:23:14', '2025-01-27 21:23:14', 'unico', 77, 11000),
(78, '2025-01-27 21:25:17', '2025-01-27 21:25:17', 'unico', 78, 7000),
(79, '2025-01-27 21:26:55', '2025-01-27 21:26:55', 'unico', 79, 3500),
(80, '2025-01-27 21:27:15', '2025-01-27 21:27:15', 'unico', 80, 3500),
(81, '2025-01-27 21:28:13', '2025-01-27 21:28:13', 'unico', 81, 3500),
(82, '2025-01-27 21:28:39', '2025-01-27 21:28:39', 'unico', 82, 3500),
(83, '2025-01-27 21:29:03', '2025-01-27 21:29:03', 'unico', 83, 5500),
(84, '2025-01-27 21:29:37', '2025-01-27 21:29:37', 'unico', 84, 3500),
(85, '2025-01-27 21:30:25', '2025-01-27 21:30:25', 'unico', 85, 2500),
(86, '2025-01-27 21:31:23', '2025-01-27 21:31:23', 'unico', 86, 3000),
(87, '2025-01-27 21:31:46', '2025-01-27 21:31:46', 'unico', 87, 5000),
(88, '2025-01-27 21:32:31', '2025-01-27 21:32:31', 'unico', 88, 2000),
(89, '2025-01-27 21:32:59', '2025-01-27 21:32:59', 'unico', 89, 9000),
(90, '2025-01-29 19:09:40', '2025-01-29 19:09:40', 'unico', 90, 22000),
(91, '2025-01-29 19:19:09', '2025-01-29 19:19:09', 'unico', 91, 22000),
(92, '2025-01-29 20:19:41', '2025-01-29 20:19:41', 'unico', 92, 6000),
(93, '2025-01-29 20:20:25', '2025-01-29 20:20:25', 'unico', 93, 3000),
(94, '2025-01-29 20:21:07', '2025-01-29 20:21:07', 'unico', 94, 3500),
(95, '2025-01-29 20:48:14', '2025-01-29 20:48:14', 'unico', 95, 2000),
(96, '2025-01-29 22:36:55', '2025-01-29 22:36:55', 'unico', 96, 2000),
(97, '2025-01-29 22:38:16', '2025-01-29 22:38:16', 'unico', 97, 5500),
(98, '2025-01-29 22:39:28', '2025-01-29 22:39:28', 'unico', 98, 3500),
(99, '2025-01-29 22:41:04', '2025-01-29 22:41:04', 'unico', 99, 3500),
(100, '2025-01-29 22:45:23', '2025-01-29 22:45:23', 'unico', 100, 1500),
(101, '2025-03-02 22:33:05', '2025-03-02 22:33:05', 'unico', 101, 3000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_puntos`
--

CREATE TABLE `gapp_puntos` (
  `id` int(11) NOT NULL,
  `desde` decimal(10,0) NOT NULL,
  `hasta` decimal(10,0) NOT NULL,
  `puntos` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_sabor`
--

CREATE TABLE `gapp_sabor` (
  `id` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `gapp_sabor`
--

INSERT INTO `gapp_sabor` (`id`, `updated_at`, `created_at`, `descripcion`) VALUES
(1, '2019-05-01 07:04:34', '2019-05-01 07:04:34', 'COCACOLA'),
(2, '2019-05-01 07:04:41', '2019-05-01 07:04:41', 'PEPSI'),
(3, '2019-05-01 07:04:45', '2019-05-01 07:04:45', 'MANZANA'),
(4, '2019-05-01 07:04:51', '2019-05-01 07:04:51', 'COLOMBIANA'),
(5, '2019-05-01 07:21:56', '2019-05-01 07:21:56', 'MR TEA'),
(6, '2019-05-01 07:28:14', '2019-05-01 07:28:14', 'NARANJA PIÑA'),
(7, '2019-05-01 07:28:20', '2019-05-01 07:28:20', 'MANGO'),
(8, '2019-05-01 07:28:31', '2019-05-01 07:28:31', 'FRUTAS TROPICALES'),
(10, '2019-05-01 07:28:40', '2019-05-01 07:28:40', 'DURAZNO'),
(11, '2019-05-01 08:24:34', '2019-05-01 08:24:34', 'AGUA'),
(12, '2019-05-04 07:50:25', '2019-05-04 07:50:25', 'aguila'),
(13, '2019-05-04 07:50:34', '2019-05-04 07:50:34', 'aguila light'),
(14, '2019-05-04 20:10:02', '2019-05-04 20:10:02', 'PIÑA'),
(15, '2019-05-04 20:10:07', '2019-05-04 20:10:07', 'NARANJA'),
(16, '2019-05-04 20:10:23', '2019-05-04 20:10:23', 'UVA'),
(17, '2019-05-04 20:10:30', '2019-05-04 20:10:30', 'SEVEN UP'),
(18, '2019-05-04 20:10:36', '2019-05-04 20:10:36', 'QUATRO'),
(19, '2019-05-04 20:11:32', '2019-05-04 20:11:32', 'ROJA'),
(20, '2019-07-28 12:22:12', '2019-07-28 12:22:12', 'club colombia'),
(21, '2019-07-28 12:22:15', '2019-07-28 12:22:15', 'poker'),
(22, '2019-07-28 12:22:20', '2019-07-28 12:22:20', 'polar'),
(23, '2019-07-28 12:22:24', '2019-07-28 12:22:24', 'pilsen'),
(24, '2019-07-31 18:54:05', '2019-07-31 18:54:05', 'limon'),
(25, '2020-02-15 18:29:42', '2020-02-15 18:29:42', 'MICHELADA'),
(26, '2021-03-29 19:05:42', '2021-03-29 19:05:42', 'CORONA'),
(27, '2021-03-29 19:06:02', '2021-03-29 19:06:02', 'TE HATSU NEGRO'),
(28, '2021-03-29 19:06:10', '2021-03-29 19:06:10', 'TE HATSU AMARILLO'),
(29, '2021-03-29 19:06:16', '2021-03-29 19:06:16', 'TE HATSU BLANCO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_saldos_producto`
--

CREATE TABLE `gapp_saldos_producto` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `ingrediente_id` int(11) DEFAULT NULL,
  `bodega` int(11) NOT NULL DEFAULT '0',
  `fecha_act` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `existencia` decimal(14,2) NOT NULL DEFAULT '0.00',
  `existencia_max` decimal(14,2) NOT NULL DEFAULT '0.00',
  `existencia_min` decimal(14,2) NOT NULL DEFAULT '0.00',
  `entradas00` decimal(14,2) NOT NULL DEFAULT '0.00',
  `entradas01` decimal(14,2) NOT NULL DEFAULT '0.00',
  `entradas02` decimal(14,2) NOT NULL DEFAULT '0.00',
  `entradas03` decimal(14,2) NOT NULL DEFAULT '0.00',
  `entradas04` decimal(14,2) NOT NULL DEFAULT '0.00',
  `entradas05` decimal(14,2) NOT NULL DEFAULT '0.00',
  `entradas06` decimal(14,2) NOT NULL DEFAULT '0.00',
  `entradas07` decimal(14,2) NOT NULL DEFAULT '0.00',
  `entradas08` decimal(14,2) NOT NULL DEFAULT '0.00',
  `entradas09` decimal(14,2) NOT NULL DEFAULT '0.00',
  `entradas10` decimal(14,2) NOT NULL DEFAULT '0.00',
  `entradas11` decimal(14,2) NOT NULL DEFAULT '0.00',
  `entradas12` decimal(14,2) NOT NULL DEFAULT '0.00',
  `salidas00` decimal(14,2) NOT NULL DEFAULT '0.00',
  `salidas01` decimal(14,2) NOT NULL DEFAULT '0.00',
  `salidas02` decimal(14,2) NOT NULL DEFAULT '0.00',
  `salidas03` decimal(14,2) NOT NULL DEFAULT '0.00',
  `salidas04` decimal(14,2) NOT NULL DEFAULT '0.00',
  `salidas05` decimal(14,2) NOT NULL DEFAULT '0.00',
  `salidas06` decimal(14,2) NOT NULL DEFAULT '0.00',
  `salidas07` decimal(14,2) NOT NULL DEFAULT '0.00',
  `salidas08` decimal(14,2) NOT NULL DEFAULT '0.00',
  `salidas09` decimal(14,2) NOT NULL DEFAULT '0.00',
  `salidas10` decimal(14,2) NOT NULL DEFAULT '0.00',
  `salidas11` decimal(14,2) NOT NULL DEFAULT '0.00',
  `salidas12` decimal(14,2) NOT NULL DEFAULT '0.00',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_tabla`
--

CREATE TABLE `gapp_tabla` (
  `id` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `tabla` varchar(10) NOT NULL,
  `valor` decimal(12,2) DEFAULT NULL,
  `valor_alf` varchar(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `gapp_tabla`
--

INSERT INTO `gapp_tabla` (`id`, `codigo`, `descripcion`, `tabla`, `valor`, `valor_alf`, `updated_at`, `created_at`) VALUES
(1, '0', 'IVA 0%', 'TIPOIVA', '0.00', NULL, '2019-07-24 14:59:23', '2019-07-24 14:59:23'),
(2, '19', 'IVA 19%', 'TIPOIVA', '19.00', NULL, '2019-07-24 14:59:23', '2019-07-24 14:59:23'),
(3, '8', 'ICO 8%', 'TIPOIVA', '8.00', NULL, '2019-07-24 14:59:50', '2019-07-24 14:59:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_tercero`
--

CREATE TABLE `gapp_tercero` (
  `id` int(11) NOT NULL,
  `identificacion` varchar(20) NOT NULL,
  `tipoidenti` char(1) DEFAULT NULL,
  `nombrecompleto` varchar(120) NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `telefono` varchar(40) DEFAULT NULL,
  `tipoclie` varchar(1) DEFAULT NULL,
  `observacion` varchar(200) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `nrotarjetapuntos` varchar(30) DEFAULT NULL,
  `puntosacumulados` double(14,2) DEFAULT NULL,
  `nombre1` varchar(20) DEFAULT NULL,
  `nombre2` varchar(20) DEFAULT NULL,
  `apellido1` varchar(20) DEFAULT NULL,
  `apellido2` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `estado` int(1) DEFAULT NULL,
  `fecha_nacimiento` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_tipo_documento`
--

CREATE TABLE `gapp_tipo_documento` (
  `id` int(10) NOT NULL,
  `codigo` varchar(3) NOT NULL,
  `descripcion` varchar(60) NOT NULL,
  `imparqueo` varchar(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `consecutivo` int(11) DEFAULT NULL,
  `tipoIE` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `gapp_tipo_documento`
--

INSERT INTO `gapp_tipo_documento` (`id`, `codigo`, `descripcion`, `imparqueo`, `created_at`, `updated_at`, `consecutivo`, `tipoIE`) VALUES
(1, 'FV', 'FACTURA DE VENTA', 'S', '2018-07-31 18:08:09', '2025-03-10 19:17:57', 67571, 'I'),
(2, 'FC', 'FACTURA DE COMPRA', 'S', '2018-07-31 18:09:12', '2021-11-10 12:12:14', 22, 'E'),
(3, 'NI', 'NOTA DE INVENTARIO', 'S', '2019-02-17 00:00:00', '2023-01-30 21:18:02', 21, NULL),
(4, 'BI', 'BASE INICIAL', 'S', '2019-02-17 00:00:00', '2024-07-26 20:55:12', 10, 'I'),
(5, 'PN', 'PAGO NOMINA', 'S', '2019-02-17 00:00:00', '2021-09-19 00:18:17', 25, 'E'),
(6, 'RC', 'RECIBO DE CAJA CARTERA', 'N', '2019-02-17 00:00:00', '2024-07-26 20:57:28', 1, 'I'),
(7, 'RT', 'RECIBO DE CAJA TESORERIA', 'N', '2019-02-17 00:00:00', '2019-02-17 00:00:00', 0, 'E'),
(8, 'CI', 'COMPROBANTE INGRESO', 'S', '2019-02-17 00:00:00', '2021-09-06 23:41:47', 6, 'I'),
(9, 'CE', 'COMPROBANTE EGRESO', 'S', '2019-02-17 00:00:00', '2021-09-19 00:19:18', 35, 'E'),
(10, 'CO', 'CONSUMOS', '', '2019-08-05 16:41:33', '2019-11-05 13:05:26', 6, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_tipo_producto`
--

CREATE TABLE `gapp_tipo_producto` (
  `id` int(10) NOT NULL,
  `codigo` varchar(3) DEFAULT NULL,
  `descripcion` varchar(60) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `fracciones` varchar(100) DEFAULT NULL,
  `aplica_tamanos` tinyint(4) DEFAULT '0',
  `aplica_sabores` tinyint(4) DEFAULT '0',
  `aplica_ingredientes` tinyint(4) DEFAULT '0',
  `estado` int(1) DEFAULT '1',
  `impresora` varchar(45) DEFAULT NULL,
  `tamanos` varchar(100) DEFAULT NULL,
  `valor_editable` int(1) DEFAULT NULL,
  `orden` int(5) DEFAULT NULL,
  `cobro_fraccion` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `gapp_tipo_producto`
--

INSERT INTO `gapp_tipo_producto` (`id`, `codigo`, `descripcion`, `created_at`, `updated_at`, `fracciones`, `aplica_tamanos`, `aplica_sabores`, `aplica_ingredientes`, `estado`, `impresora`, `tamanos`, `valor_editable`, `orden`, `cobro_fraccion`) VALUES
(1, NULL, 'BANDEJAS', '2025-01-22 20:51:54', '2025-03-02 22:36:36', '[\"1/1\"]', NULL, NULL, 1, 1, '', '[\"grande\",\"extrag\",\"mediano\",\"pequeno\",\"porcion\"]', NULL, NULL, 0),
(2, NULL, 'BEBIDAS', '2025-01-24 12:37:12', '2025-03-02 22:36:32', '[\"1/1\"]', NULL, NULL, NULL, 1, '', '[\"grande\",\"extrag\",\"mediano\",\"pequeno\",\"porcion\"]', NULL, NULL, 0),
(3, NULL, 'SABADOS Y DOMINGOS', '2025-01-24 12:37:38', '2025-03-02 22:36:31', '[\"1/1\"]', NULL, NULL, 1, 1, '', '[\"grande\",\"extrag\",\"mediano\",\"pequeno\",\"porcion\"]', NULL, NULL, 0),
(4, NULL, 'ENTRADAS', '2025-01-24 12:37:49', '2025-03-02 22:36:30', '[\"1/1\"]', NULL, NULL, 1, 1, '', '[\"grande\",\"extrag\",\"mediano\",\"pequeno\",\"porcion\"]', NULL, NULL, 0),
(5, NULL, 'PINCHOS', '2025-01-24 12:37:55', '2025-03-02 22:36:35', '[\"1/1\"]', NULL, NULL, 1, 1, '', '[\"grande\",\"extrag\",\"mediano\",\"pequeno\",\"porcion\"]', NULL, NULL, 0),
(6, NULL, 'PICADAS', '2025-01-24 12:38:03', '2025-03-02 22:36:37', '[\"1/1\"]', NULL, NULL, 1, 1, '', '[\"grande\",\"extrag\",\"mediano\",\"pequeno\",\"porcion\"]', NULL, NULL, 0),
(7, NULL, 'SALCHIPAPAS', '2025-01-24 12:38:13', '2025-03-02 22:36:38', '[\"1/1\"]', NULL, NULL, 1, 1, '', '[\"grande\",\"extrag\",\"mediano\",\"pequeno\",\"porcion\"]', NULL, NULL, 0),
(8, NULL, 'CALDOS', '2025-01-24 12:38:20', '2025-03-02 22:57:37', '[\"1/1\"]', NULL, NULL, 1, 1, '', '[\"grande\",\"extrag\",\"mediano\",\"pequeno\",\"porcion\"]', NULL, NULL, 0),
(9, NULL, 'ADICIONALES', '2025-01-24 12:38:42', '2025-03-02 22:36:34', '[\"1/1\"]', NULL, NULL, 1, 1, '', '[\"grande\",\"extrag\",\"mediano\",\"pequeno\",\"porcion\"]', NULL, NULL, 0),
(10, NULL, 'HAMBURGUESAS', '2025-01-24 12:38:51', '2025-03-02 22:36:29', '[\"1/1\"]', NULL, NULL, 1, 1, '', '[\"grande\",\"extrag\",\"mediano\",\"pequeno\",\"porcion\"]', NULL, NULL, 0),
(11, NULL, 'ICOPOR', '2025-01-24 22:28:47', '2025-03-02 22:36:33', '[\"1/1\"]', NULL, NULL, NULL, 1, '', '[\"grande\",\"extrag\",\"mediano\",\"pequeno\",\"porcion\"]', NULL, NULL, 0),
(12, NULL, 'AREPAS ASADAS', '2025-01-27 17:28:56', '2025-03-02 22:36:40', '[\"1/1\"]', NULL, NULL, 1, 1, 'POSCOCINA', '[\"grande\",\"extrag\",\"mediano\",\"pequeno\",\"porcion\"]', NULL, NULL, 0),
(13, NULL, 'COMBO', '2025-01-27 21:56:54', '2025-03-02 22:36:41', '[\"1/1\"]', NULL, NULL, 1, 1, '', '[\"grande\",\"extrag\",\"mediano\",\"pequeno\",\"porcion\"]', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gapp_users`
--

CREATE TABLE `gapp_users` (
  `id` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `usuario` varchar(45) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nombres` varchar(45) DEFAULT NULL,
  `apellidos` varchar(45) DEFAULT NULL,
  `rol` varchar(45) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `conn` varchar(45) DEFAULT 'hsoftware',
  `api_token` varchar(99) NOT NULL,
  `impresora` varchar(45) DEFAULT NULL,
  `caja_id` int(10) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `gapp_users`
--

INSERT INTO `gapp_users` (`id`, `updated_at`, `created_at`, `usuario`, `password`, `nombres`, `apellidos`, `rol`, `remember_token`, `conn`, `api_token`, `impresora`, `caja_id`) VALUES
(1, '2025-03-10 21:20:10', '2017-03-04 09:20:35', 'ADMIN', '$2y$10$UayVwJPk1kzwZDQ.q47Ezu7AfcSMbXd2xzlpertAdQ2Ds.lSDNJY6', 'admin', 'jr', 'Administrador', '03JHNXoZVjr46GkFOaODdg62bdzEIaf94LgnSAfXDX4lXs3fIlwOj0EUaTil', 'mysql', '', NULL, 1),
(2, '2024-07-26 20:51:28', '2018-11-18 13:42:20', 'MESEROX', '$2y$10$UayVwJPk1kzwZDQ.q47Ezu7AfcSMbXd2xzlpertAdQ2Ds.lSDNJY6', 'NOMBRES', 'APELLIDOS', 'Mesero', 'D3fBJWxIeaFbGaOFKPqNZkBmL3TL3WNbZTOG8nbBy54SddFEtbVMbc7V6drI', 'mysql', '', NULL, 1),
(3, '2024-07-26 20:52:29', '2018-11-18 15:22:40', 'MESERO9', '$2y$10$UayVwJPk1kzwZDQ.q47Ezu7AfcSMbXd2xzlpertAdQ2Ds.lSDNJY6', 'NOMBRES', 'APELLIDOS', 'Mesero', 'YR69Ei7DNZsCUkvtgQLfBMkOAypW4Hul1hTnymTJQh2zU3JlcnvWyqgnrxtG', 'mysql', '', NULL, 1),
(4, '2022-06-01 21:27:36', '2020-02-02 15:22:02', 'SANDY', '$2y$10$UayVwJPk1kzwZDQ.q47Ezu7AfcSMbXd2xzlpertAdQ2Ds.lSDNJY6', 'NOMBRES', 'APELLIDOS', 'Cajero', 'W5Yl1fLA8Bre687Y9WyTgLBsISgrC8siCapslZYbfSstWenk6bHjsgnZTBls', 'mysql', '', NULL, 1),
(5, '2022-09-21 18:06:31', '2018-11-18 13:42:20', 'CSK', '$2y$10$UayVwJPk1kzwZDQ.q47Ezu7AfcSMbXd2xzlpertAdQ2Ds.lSDNJY6', 'csk', 'Gapp', 'Administrador', 'OF1OnwmJ0hUgPdmw4dw8OVa3okMYtwpdTsxw55nuWz2mKbLizt9QTxVG5VzS', 'mysql', 'b2Q1enFKNWxFM3NtUWViWTl4T1hMOTVFMDBNRXU1ZlJtcWFCTE9hbA==', NULL, 2),
(10, '2021-06-02 18:57:50', '2018-11-18 13:42:20', 'ANGEL', '$2y$10$UayVwJPk1kzwZDQ.q47Ezu7AfcSMbXd2xzlpertAdQ2Ds.lSDNJY6', 'NOMBRES', 'APELLIDOS', 'Mesero', '1skidEAbb730qFgc1rz5Zfs2ayXQNSEVb8cIaebI1zrzgm3WvOYEQh0W6rHr', 'mysql', '', NULL, 1),
(11, '2022-02-15 20:24:58', '2018-11-18 13:42:20', 'MAURICIO', '$2y$10$UayVwJPk1kzwZDQ.q47Ezu7AfcSMbXd2xzlpertAdQ2Ds.lSDNJY6', 'NOMBRES', 'APELLIDOS', 'Mesero', 'M5nr2K9oLpkBHrqimIQg4T7w99B1GlsFi4fXJ1UGlilshHBLAwWd8d7JGuy8', 'mysql', '', NULL, 1),
(12, '2021-09-18 23:32:19', '2018-11-18 13:42:20', 'JOSE', '$2y$10$UayVwJPk1kzwZDQ.q47Ezu7AfcSMbXd2xzlpertAdQ2Ds.lSDNJY6', 'NOMBRES', 'APELLIDOS', 'Mesero', 'vaX6ORtrR1fIR06aB9dSH1pVHdDOK4WnhT4E8yuGAuJCLhmRmDMWX5CHhGnj', 'mysql', '', NULL, 1),
(13, '2021-09-11 17:40:59', '2018-11-18 13:42:20', 'JUANCARLOS', '$2y$10$UayVwJPk1kzwZDQ.q47Ezu7AfcSMbXd2xzlpertAdQ2Ds.lSDNJY6', 'NOMBRES', 'APELLIDOS', 'Mesero', 'VKfx4XgkvzuwE2O6Po1CsgvDW77I2RxNQItDlmEOMEcFvsF9f8z9lWk9ZtiB', 'mysql', '', NULL, 1),
(15, '2021-09-19 01:01:48', '2017-03-04 09:20:35', 'barra', '$2y$10$UayVwJPk1kzwZDQ.q47Ezu7AfcSMbXd2xzlpertAdQ2Ds.lSDNJY6', 'NOMBRES', 'APELLIDOS', 'Administrador', 'mqxI92Zxp7hU3z9G2JIOt6wg8Ow2OaEwXrgG1JLuYjxhaFfqx2TqC23Xf4Us', 'mysql', '', NULL, 2),
(16, '2021-09-11 17:28:19', '2017-03-04 09:20:35', 'FAISULI', '$2y$10$UayVwJPk1kzwZDQ.q47Ezu7AfcSMbXd2xzlpertAdQ2Ds.lSDNJY6', 'NOMBRES', 'APELLIDOS', 'Mesero', 'cRldK8LmV0r5IsUx6mnhZG1159u00oPQr2GFSuP8oMGMCxtr22vFLKIAHeU2', 'mysql', '', NULL, 2),
(17, '2021-09-12 17:43:48', '2017-03-04 09:20:35', 'OMAR', '$2y$10$UayVwJPk1kzwZDQ.q47Ezu7AfcSMbXd2xzlpertAdQ2Ds.lSDNJY6', 'NOMBRES', 'APELLIDOS', 'Mesero', 'Djr5o8c5ycCxqJLOrehajAbjP6riJE3NOyqsOcwRQrxzWu0IeY71xqycASWj', 'mysql', '', NULL, 2),
(18, '2021-09-10 17:21:22', '2017-03-04 09:20:35', 'MESERO8', '$2y$10$UayVwJPk1kzwZDQ.q47Ezu7AfcSMbXd2xzlpertAdQ2Ds.lSDNJY6', 'NOMBRES', 'APELLIDOS', 'Mesero', '8CoCaK7V7WT8RhaGuZPNz9Bxscb7JsZaMDjyxfOhuqZwWIQjOaB6INwJy25n', 'mysql', '', NULL, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `consulta_licencia`
--
ALTER TABLE `consulta_licencia`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `hsoft`
--
ALTER TABLE `hsoft`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `licencias`
--
ALTER TABLE `licencias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gapp_adicional`
--
ALTER TABLE `gapp_adicional`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adicional_product_fk_idx` (`producto_id`),
  ADD KEY `adicional_ingrediente_fk_idx` (`ingrediente_id`),
  ADD KEY `adicional_tipo_producto_fk_idx` (`tipo_producto_id`);

--
-- Indices de la tabla `gapp_combo`
--
ALTER TABLE `gapp_combo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gapp_combo_producto`
--
ALTER TABLE `gapp_combo_producto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gapp_config`
--
ALTER TABLE `gapp_config`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gapp_detalle_documento`
--
ALTER TABLE `gapp_detalle_documento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gapp_documento`
--
ALTER TABLE `gapp_documento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documento_usuario_fk_idx` (`usuario_id`);

--
-- Indices de la tabla `gapp_documento_formapago`
--
ALTER TABLE `gapp_documento_formapago`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rel_doc_fp` (`formapago_id`);

--
-- Indices de la tabla `gapp_formapago`
--
ALTER TABLE `gapp_formapago`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_formapago` (`codigo`);

--
-- Indices de la tabla `gapp_ingrediente`
--
ALTER TABLE `gapp_ingrediente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gapp_pedido`
--
ALTER TABLE `gapp_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_user_fk_idx` (`user_id`);

--
-- Indices de la tabla `gapp_producto`
--
ALTER TABLE `gapp_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_tipo_fk_idx` (`tipo_producto_id`);

--
-- Indices de la tabla `gapp_producto_ingrediente`
--
ALTER TABLE `gapp_producto_ingrediente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_ingrediente_ingrediente_fk_idx` (`ingrediente_id`),
  ADD KEY `producto_ingrediente_producto_fk_idx` (`producto_id`);

--
-- Indices de la tabla `gapp_producto_pedido`
--
ALTER TABLE `gapp_producto_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_pedido_producto_idx` (`producto_id`),
  ADD KEY `producto_pedido_pedido_fk_idx` (`pedido_id`);

--
-- Indices de la tabla `gapp_producto_pedido_adicional`
--
ALTER TABLE `gapp_producto_pedido_adicional`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_pedido_adicional_adicional_idx` (`adicional_id`),
  ADD KEY `producto_pedido_adicional_producto_pedido_idx` (`producto_pedido_id`);

--
-- Indices de la tabla `gapp_producto_pedido_documento`
--
ALTER TABLE `gapp_producto_pedido_documento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gapp_producto_pedido_ingrediente`
--
ALTER TABLE `gapp_producto_pedido_ingrediente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_pedido_ingrediente_ingrediente_idx` (`ingrediente_id`),
  ADD KEY `producto_pedido_ingrediente_producto_pedido_idx` (`producto_pedido_id`);

--
-- Indices de la tabla `gapp_producto_sabor`
--
ALTER TABLE `gapp_producto_sabor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `producto_sabor_producto_id_idx` (`producto_id`),
  ADD KEY `producto_sabor_sabor_idx` (`sabor_id`);

--
-- Indices de la tabla `gapp_producto_tamano`
--
ALTER TABLE `gapp_producto_tamano`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `producto_tamano_fk_idx` (`producto_id`);

--
-- Indices de la tabla `gapp_puntos`
--
ALTER TABLE `gapp_puntos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gapp_sabor`
--
ALTER TABLE `gapp_sabor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `gapp_saldos_producto`
--
ALTER TABLE `gapp_saldos_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `saldos_producto_fk` (`producto_id`);

--
-- Indices de la tabla `gapp_tabla`
--
ALTER TABLE `gapp_tabla`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gapp_tercero`
--
ALTER TABLE `gapp_tercero`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_ter_identi` (`identificacion`),
  ADD KEY `idx_ter_nombre` (`nombrecompleto`);

--
-- Indices de la tabla `gapp_tipo_documento`
--
ALTER TABLE `gapp_tipo_documento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gapp_tipo_producto`
--
ALTER TABLE `gapp_tipo_producto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gapp_users`
--
ALTER TABLE `gapp_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `consulta_licencia`
--
ALTER TABLE `consulta_licencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `hsoft`
--
ALTER TABLE `hsoft`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `licencias`
--
ALTER TABLE `licencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gapp_adicional`
--
ALTER TABLE `gapp_adicional`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gapp_combo`
--
ALTER TABLE `gapp_combo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gapp_combo_producto`
--
ALTER TABLE `gapp_combo_producto`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gapp_config`
--
ALTER TABLE `gapp_config`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `gapp_detalle_documento`
--
ALTER TABLE `gapp_detalle_documento`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `gapp_documento`
--
ALTER TABLE `gapp_documento`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `gapp_ingrediente`
--
ALTER TABLE `gapp_ingrediente`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;

--
-- AUTO_INCREMENT de la tabla `gapp_pedido`
--
ALTER TABLE `gapp_pedido`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `gapp_producto`
--
ALTER TABLE `gapp_producto`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `gapp_producto_ingrediente`
--
ALTER TABLE `gapp_producto_ingrediente`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2275;

--
-- AUTO_INCREMENT de la tabla `gapp_producto_pedido`
--
ALTER TABLE `gapp_producto_pedido`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `gapp_producto_pedido_adicional`
--
ALTER TABLE `gapp_producto_pedido_adicional`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gapp_producto_pedido_documento`
--
ALTER TABLE `gapp_producto_pedido_documento`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gapp_producto_pedido_ingrediente`
--
ALTER TABLE `gapp_producto_pedido_ingrediente`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT de la tabla `gapp_producto_sabor`
--
ALTER TABLE `gapp_producto_sabor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gapp_producto_tamano`
--
ALTER TABLE `gapp_producto_tamano`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `gapp_puntos`
--
ALTER TABLE `gapp_puntos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `gapp_sabor`
--
ALTER TABLE `gapp_sabor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `gapp_saldos_producto`
--
ALTER TABLE `gapp_saldos_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `gapp_tabla`
--
ALTER TABLE `gapp_tabla`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `gapp_tercero`
--
ALTER TABLE `gapp_tercero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `gapp_tipo_documento`
--
ALTER TABLE `gapp_tipo_documento`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `gapp_tipo_producto`
--
ALTER TABLE `gapp_tipo_producto`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `gapp_users`
--
ALTER TABLE `gapp_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `gapp_adicional`
--
ALTER TABLE `gapp_adicional`
  ADD CONSTRAINT `adicional_ingrediente_fk` FOREIGN KEY (`ingrediente_id`) REFERENCES `gapp_ingrediente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `adicional_product_fk` FOREIGN KEY (`producto_id`) REFERENCES `gapp_producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `adicional_tipo_producto_fk` FOREIGN KEY (`tipo_producto_id`) REFERENCES `gapp_tipo_producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `gapp_pedido`
--
ALTER TABLE `gapp_pedido`
  ADD CONSTRAINT `pedido_user_fk` FOREIGN KEY (`user_id`) REFERENCES `gapp_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `gapp_producto`
--
ALTER TABLE `gapp_producto`
  ADD CONSTRAINT `producto_tipo_fk` FOREIGN KEY (`tipo_producto_id`) REFERENCES `gapp_tipo_producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `gapp_producto_ingrediente`
--
ALTER TABLE `gapp_producto_ingrediente`
  ADD CONSTRAINT `producto_ingrediente_ingrediente_fk` FOREIGN KEY (`ingrediente_id`) REFERENCES `gapp_ingrediente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_ingrediente_producto_fk` FOREIGN KEY (`producto_id`) REFERENCES `gapp_producto` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `gapp_producto_pedido`
--
ALTER TABLE `gapp_producto_pedido`
  ADD CONSTRAINT `producto_pedido_pedido_fk` FOREIGN KEY (`pedido_id`) REFERENCES `gapp_pedido` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_pedido_producto` FOREIGN KEY (`producto_id`) REFERENCES `gapp_producto` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `gapp_producto_pedido_adicional`
--
ALTER TABLE `gapp_producto_pedido_adicional`
  ADD CONSTRAINT `producto_pedido_adicional_adicional` FOREIGN KEY (`adicional_id`) REFERENCES `gapp_adicional` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_pedido_adicional_producto_pedido` FOREIGN KEY (`producto_pedido_id`) REFERENCES `gapp_producto_pedido` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `gapp_producto_pedido_ingrediente`
--
ALTER TABLE `gapp_producto_pedido_ingrediente`
  ADD CONSTRAINT `producto_pedido_ingrediente_ingrediente` FOREIGN KEY (`ingrediente_id`) REFERENCES `gapp_ingrediente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_pedido_ingrediente_producto_pedido` FOREIGN KEY (`producto_pedido_id`) REFERENCES `gapp_producto_pedido` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `gapp_producto_sabor`
--
ALTER TABLE `gapp_producto_sabor`
  ADD CONSTRAINT `producto_sabor_producto_id` FOREIGN KEY (`producto_id`) REFERENCES `gapp_producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_sabor_sabor` FOREIGN KEY (`sabor_id`) REFERENCES `gapp_sabor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `gapp_producto_tamano`
--
ALTER TABLE `gapp_producto_tamano`
  ADD CONSTRAINT `producto_tamano_fk` FOREIGN KEY (`producto_id`) REFERENCES `gapp_producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
