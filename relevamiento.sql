-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 28-10-2026 a las 18:09:19
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `relevamiento`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Equipos`
--

CREATE TABLE `Equipos` (
  `id_equipo` int(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `id_ubicacion` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Equipos`
--

INSERT INTO `Equipos` (`id_equipo`, `nombre`, `tipo`, `id_ubicacion`) VALUES
(1, 'Computadora gris 34', 'Computadora', 2),
(2, 'Computadora 29', 'Computadora', 2),
(3, 'Computadora 31', 'Computadora', 2),
(4, 'Computadora 22', 'Computadora', 2),
(5, 'Computadora 6', 'Computadora', 2),
(6, 'Computadora 15', 'Computadora', 2),
(7, 'Computadora 33', 'Computadora', 2),
(8, 'Computadora 35', 'Computadora', 2),
(9, 'Computadora 25', 'Computadora', 2),
(10, 'Tele 1', 'Televisor', 2),
(11, 'Computadora gris de Preceptoría', 'Computadora', 1),
(12, 'Computadora de Preceptoría 1', 'Computadora', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(100) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `nombre`) VALUES
(1, 'Pendiente'),
(2, 'En reparación'),
(3, 'Resuelto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Intervenciones`
--

CREATE TABLE `Intervenciones` (
  `id_intervencion` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `accion` varchar(250) DEFAULT NULL,
  `observacion` varchar(250) DEFAULT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Prioridad`
--

CREATE TABLE `Prioridad` (
  `id_prioridad` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Prioridad`
--

INSERT INTO `Prioridad` (`id_prioridad`, `nombre`) VALUES
(1, 'Baja'),
(2, 'Media'),
(3, 'Alta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Problemas`
--

CREATE TABLE `Problemas` (
  `id_problema` int(10) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Problemas`
--

INSERT INTO `Problemas` (`id_problema`, `nombre`) VALUES
(1, 'Falla táctil'),
(2, 'No enciende'),
(3, 'Falla HDMI'),
(4, 'Pérdida de conexión con impresora'),
(5, 'Funcionamiento lento');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Solicitudes`
--

CREATE TABLE `Solicitudes` (
  `id_solicitud` int(10) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `id_equipo` int(100) NOT NULL,
  `id_problema` int(100) NOT NULL,
  `id_estado` int(100) NOT NULL,
  `id_prioridad` int(100) NOT NULL,
  `id_usuario` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Solicitudes`
--

INSERT INTO `Solicitudes` (`id_solicitud`, `fecha`, `descripcion`, `id_equipo`, `id_problema`, `id_estado`, `id_prioridad`, `id_usuario`) VALUES
(1, '2026-10-27', 'La computadora gris 34 presenta falla táctil', 1, 1, 1, 2, 1),
(2, '2026-10-27', 'La computadora 29 no enciende', 2, 2, 1, 3, 1),
(3, '2026-10-27', 'La computadora 31 no enciende', 3, 2, 1, 3, 1),
(4, '2026-10-27', 'La computadora 22 no enciende', 4, 2, 1, 3, 1),
(5, '2026-10-27', 'La computadora 6 no enciende', 5, 2, 1, 3, 1),
(6, '2026-10-27', 'La computadora 15 no enciende', 6, 2, 1, 3, 1),
(7, '2026-10-27', 'La computadora 33 no enciende', 7, 2, 1, 3, 1),
(8, '2026-10-27', 'La computadora 35 no enciende', 8, 2, 1, 3, 1),
(9, '2026-10-27', 'La computadora 25 no enciende', 9, 2, 1, 3, 1),
(10, '2026-10-27', 'No funcionan las entradas HDMI 1 y 2', 10, 3, 1, 2, 1),
(11, '2026-10-27', 'Perdió la conexión con la impresora', 11, 4, 1, 2, 1),
(12, '2026-10-27', 'La computadora funciona lentamente y necesita optimización', 12, 5, 1, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Ubicaciones`
--

CREATE TABLE `Ubicaciones` (
  `id_ubicacion` int(100) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Ubicaciones`
--

INSERT INTO `Ubicaciones` (`id_ubicacion`, `nombre`) VALUES
(1, 'Preceptoría'),
(2, 'Laboratorio'),
(3, 'Taller'),
(4, 'Direccion'),
(5, 'Pañol'),
(6, 'SUM'),
(7, 'Aula');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `id_usuario` int(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rol` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`id_usuario`, `nombre`, `rol`, `correo`, `contrasena`) VALUES
(1, 'Tapia', 'Profesor', 'tapia@gmail.com', '123456');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Equipos`
--
ALTER TABLE `Equipos`
  ADD PRIMARY KEY (`id_equipo`),
  ADD KEY `fk_id_ubicacion` (`id_ubicacion`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `Intervenciones`
--
ALTER TABLE `Intervenciones`
  ADD PRIMARY KEY (`id_intervencion`),
  ADD KEY `fk_id_solicitud` (`id_solicitud`),
  ADD KEY `fk_id_usuario` (`id_usuario`);

--
-- Indices de la tabla `Prioridad`
--
ALTER TABLE `Prioridad`
  ADD PRIMARY KEY (`id_prioridad`);

--
-- Indices de la tabla `Problemas`
--
ALTER TABLE `Problemas`
  ADD PRIMARY KEY (`id_problema`);

--
-- Indices de la tabla `Solicitudes`
--
ALTER TABLE `Solicitudes`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `fk_id_equipo` (`id_equipo`),
  ADD KEY `fk_id_problema` (`id_problema`),
  ADD KEY `fk_id_estado` (`id_estado`),
  ADD KEY `fk_id_prioridadf` (`id_prioridad`),
  ADD KEY `fk_usuario` (`id_usuario`);

--
-- Indices de la tabla `Ubicaciones`
--
ALTER TABLE `Ubicaciones`
  ADD PRIMARY KEY (`id_ubicacion`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Equipos`
--
ALTER TABLE `Equipos`
  MODIFY `id_equipo` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `Intervenciones`
--
ALTER TABLE `Intervenciones`
  MODIFY `id_intervencion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Prioridad`
--
ALTER TABLE `Prioridad`
  MODIFY `id_prioridad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `Problemas`
--
ALTER TABLE `Problemas`
  MODIFY `id_problema` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `Solicitudes`
--
ALTER TABLE `Solicitudes`
  MODIFY `id_solicitud` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `Ubicaciones`
--
ALTER TABLE `Ubicaciones`
  MODIFY `id_ubicacion` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `id_usuario` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Equipos`
--
ALTER TABLE `Equipos`
  ADD CONSTRAINT `fk_id_ubicacion` FOREIGN KEY (`id_ubicacion`) REFERENCES `Ubicaciones` (`id_ubicacion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Intervenciones`
--
ALTER TABLE `Intervenciones`
  ADD CONSTRAINT `fk_id_solicitud` FOREIGN KEY (`id_solicitud`) REFERENCES `Solicitudes` (`id_solicitud`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `Usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Solicitudes`
--
ALTER TABLE `Solicitudes`
  ADD CONSTRAINT `fk_id_equipo` FOREIGN KEY (`id_equipo`) REFERENCES `Equipos` (`id_equipo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_estado` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_prioridadf` FOREIGN KEY (`id_prioridad`) REFERENCES `Prioridad` (`id_prioridad`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_problema` FOREIGN KEY (`id_problema`) REFERENCES `Problemas` (`id_problema`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `Usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
