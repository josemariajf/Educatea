-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-01-2024 a las 12:38:32
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `educatea2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

CREATE TABLE `asignaturas` (
  `asignatura_id` int(11) NOT NULL,
  `nombre_asignatura` varchar(100) NOT NULL,
  `codigo_asignatura` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignaturas`
--

INSERT INTO `asignaturas` (`asignatura_id`, `nombre_asignatura`, `codigo_asignatura`) VALUES
(14, 'Matematicas', 'MTM'),
(15, 'Lengua Castellana', 'LG'),
(16, 'Educacion fisica', 'EF');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas_clases`
--

CREATE TABLE `asignaturas_clases` (
  `asignatura_clase_id` int(11) NOT NULL,
  `asignatura_id` int(11) DEFAULT NULL,
  `clase_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignaturas_clases`
--

INSERT INTO `asignaturas_clases` (`asignatura_clase_id`, `asignatura_id`, `clase_id`) VALUES
(34, 14, 30),
(35, 16, 31),
(36, 14, 31),
(37, 15, 32),
(38, 16, 30),
(39, 15, 30),
(40, 14, 33);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases`
--

CREATE TABLE `clases` (
  `clase_id` int(11) NOT NULL,
  `nombre_clase` varchar(100) NOT NULL,
  `curso` varchar(50) NOT NULL,
  `id_tutor` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clases`
--

INSERT INTO `clases` (`clase_id`, `nombre_clase`, `curso`, `id_tutor`) VALUES
(30, '1ªA', '2023/2024', 27),
(31, '2ªA', '2023/2024', 3),
(32, '3ªA', '2023/2024', 30),
(33, '4ªA', '2023/2024', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases_usuarios`
--

CREATE TABLE `clases_usuarios` (
  `id` int(11) NOT NULL,
  `clase_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clases_usuarios`
--

INSERT INTO `clases_usuarios` (`id`, `clase_id`, `usuario_id`) VALUES
(67, 30, 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `rol_id` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`rol_id`, `nombre_rol`) VALUES
(1, 'alumno'),
(2, 'profesor'),
(3, 'director');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `tarea_id` int(11) NOT NULL,
  `descripcion_tarea` text NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `clase_id` int(11) DEFAULT NULL,
  `asignatura_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`tarea_id`, `descripcion_tarea`, `fecha_vencimiento`, `usuario_id`, `clase_id`, `asignatura_id`) VALUES
(23, 'Quiero que me hagas un dibujo de la Torre Eiffel', '2023-12-14', 3, 30, 14),
(24, 'Créame una rutina de deporte', '2023-12-30', 27, 31, 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas_usuarios`
--

CREATE TABLE `tareas_usuarios` (
  `id` int(11) NOT NULL,
  `tarea_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `calificacion` double DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `fecha_entrega` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas_usuarios`
--

INSERT INTO `tareas_usuarios` (`id`, `tarea_id`, `usuario_id`, `calificacion`, `url`, `fecha_entrega`) VALUES
(28, 24, 1, 7, '../../Tarea/Jimenez_Franco_Jose_Maria Plan de empresa Educatea (1).pdf', '2023-12-13'),
(29, 23, 1, 6, '../../Tarea/Jimenez_Franco_Jose_Maria Plan de empresa Educatea (1) (1).pdf', '2023-12-13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `usuario`, `nombre`, `apellido`, `correo_electronico`, `contrasena`, `rol_id`) VALUES
(0, 'nulo', 'nulo', 'nulo', 'nulo@gmail.com', 'nulo', 3),
(1, 'alumno', 'alumno', 'alumno', 'alumno@gmail.com', 'c6865cf98b133f1f3de596a4a2894630', 1),
(3, 'profesor', 'profesor', 'profesor', 'profesor@gmail.com', '793741d54b00253006453742ad4ed534', 2),
(4, 'director', 'director', 'director', 'director@gmail.com', '3d4e992d8d8a7d848724aa26ed7f4176', 3),
(25, 'usuario', 'usuario', 'usuario', 'usuario@gmail.com', 'f8032d5cae3de20fcec887f395ec9a6a', 1),
(27, 'juanito', 'Juanito', 'Perez', 'juanito@gmail.com', '671b7fa6fb0c818ad06b7e8596857740', 2),
(30, 'juanluis', 'juanluis', 'perez', 'juanluis@gmail.com', 'c0bd714baaa97e4253ef4f36542d9c5b', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`asignatura_id`);

--
-- Indices de la tabla `asignaturas_clases`
--
ALTER TABLE `asignaturas_clases`
  ADD PRIMARY KEY (`asignatura_clase_id`),
  ADD KEY `asignatura_id` (`asignatura_id`),
  ADD KEY `clase_id` (`clase_id`);

--
-- Indices de la tabla `clases`
--
ALTER TABLE `clases`
  ADD PRIMARY KEY (`clase_id`),
  ADD KEY `fk_tutor` (`id_tutor`);

--
-- Indices de la tabla `clases_usuarios`
--
ALTER TABLE `clases_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clase_id` (`clase_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`rol_id`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`tarea_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `clase_id` (`clase_id`),
  ADD KEY `asignatura_id` (`asignatura_id`);

--
-- Indices de la tabla `tareas_usuarios`
--
ALTER TABLE `tareas_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tarea_id` (`tarea_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  MODIFY `asignatura_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `asignaturas_clases`
--
ALTER TABLE `asignaturas_clases`
  MODIFY `asignatura_clase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `clases`
--
ALTER TABLE `clases`
  MODIFY `clase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `clases_usuarios`
--
ALTER TABLE `clases_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `rol_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `tarea_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `tareas_usuarios`
--
ALTER TABLE `tareas_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaturas_clases`
--
ALTER TABLE `asignaturas_clases`
  ADD CONSTRAINT `asignaturas_clases_ibfk_1` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`asignatura_id`),
  ADD CONSTRAINT `asignaturas_clases_ibfk_2` FOREIGN KEY (`clase_id`) REFERENCES `clases` (`clase_id`);

--
-- Filtros para la tabla `clases`
--
ALTER TABLE `clases`
  ADD CONSTRAINT `fk_tutor` FOREIGN KEY (`id_tutor`) REFERENCES `usuarios` (`usuario_id`);

--
-- Filtros para la tabla `clases_usuarios`
--
ALTER TABLE `clases_usuarios`
  ADD CONSTRAINT `clases_usuarios_ibfk_1` FOREIGN KEY (`clase_id`) REFERENCES `clases` (`clase_id`),
  ADD CONSTRAINT `clases_usuarios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`);

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `tareas_ibfk_2` FOREIGN KEY (`clase_id`) REFERENCES `clases` (`clase_id`),
  ADD CONSTRAINT `tareas_ibfk_3` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`asignatura_id`);

--
-- Filtros para la tabla `tareas_usuarios`
--
ALTER TABLE `tareas_usuarios`
  ADD CONSTRAINT `tareas_usuarios_ibfk_1` FOREIGN KEY (`tarea_id`) REFERENCES `tareas` (`tarea_id`),
  ADD CONSTRAINT `tareas_usuarios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`rol_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
