-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:8805
-- Tiempo de generación: 21-04-2026 a las 23:17:26
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
-- Base de datos: `clinica_medica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `paciente` varchar(150) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `urgencia` enum('Normal','Alta','Emergencia') NOT NULL DEFAULT 'Normal',
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `paciente`, `telefono`, `id_especialidad`, `fecha`, `hora`, `urgencia`, `observaciones`) VALUES
(1, 'Juan Pérez', '7788-9900', 4, '2026-04-25', '09:00:00', 'Alta', 'Paciente con arritmia.'),
(2, 'Carlos Rodríguez', '7566-7788', 8, '2026-04-27', '14:00:00', 'Emergencia', 'Fractura expuesta.'),
(3, 'Kevin Castro', '79434613', 9, '2026-04-21', '15:16:00', 'Emergencia', 'Exceso de sexo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `costo_consulta` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id`, `nombre`, `descripcion`, `costo_consulta`) VALUES
(1, 'Medicina General', 'Consultas básicas y chequeos.', 25.00),
(2, 'Pediatría', 'Atención para niños y adolescentes.', 35.00),
(3, 'Ginecología', 'Salud integral de la mujer.', 45.00),
(4, 'Cardiología', 'Corazón y sistema circulatorio.', 60.00),
(5, 'Dermatología', 'Afecciones de la piel.', 40.00),
(6, 'Neurología', 'Sistema nervioso.', 65.00),
(7, 'Oftalmología', 'Salud visual.', 40.00),
(8, 'Traumatología', 'Lesiones óseas y musculares.', 50.00),
(9, 'Urología', 'Sistema urinario.', 55.00),
(10, 'Psiquiatría', 'Salud mental.', 60.00),
(11, 'Cirugía General', 'Intervenciones quirúrgicas.', 70.00),
(12, 'Nutrición', 'Asesoría alimenticia.', 30.00),
(13, 'Fisioterapia', 'Rehabilitación física.', 30.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `rol` enum('admin','visitante') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_completo`, `usuario`, `clave`, `rol`) VALUES
(3, 'Administrador San Miguel', 'admin', 'admin123', 'admin'),
(4, 'Visitante San Miguel', 'visitante', 'visita123', 'visitante');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `fk_especialidad` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
