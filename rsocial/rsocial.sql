-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-02-2024 a las 19:51:24
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
-- Base de datos: `rsocial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `id_publicacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `id_publicacion`, `id_usuario`, `contenido`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 1, 15, 'Son las 7:11pm', '2024-02-13 23:11:37', '2024-02-13 23:11:37'),
(3, 8, 15, 'son las 7:31pm', '2024-02-13 23:31:46', '2024-02-13 23:31:46'),
(4, 8, 15, 'son las 7:33pm', '2024-02-13 23:33:19', '2024-02-13 23:33:29'),
(5, 7, 15, 'Hola Yo soy Williams', '2024-02-13 23:53:04', '2024-02-13 23:53:04'),
(6, 1, 15, 'hola', '2024-02-13 23:53:11', '2024-02-13 23:53:11'),
(7, 3, 15, 'Este es un comentario', '2024-02-13 23:54:25', '2024-02-13 23:54:25'),
(8, 9, 15, 'Hola soy williams', '2024-02-13 23:58:02', '2024-02-13 23:58:02'),
(9, 8, 15, 'hola pana', '2024-02-14 00:51:56', '2024-02-14 00:51:56'),
(10, 11, 15, 'hola desde alla', '2024-02-14 02:31:08', '2024-02-14 02:31:08'),
(11, 11, 15, 'adios pana', '2024-02-14 03:19:08', '2024-02-14 03:19:08'),
(12, 10, 15, 'yo tampoco', '2024-02-14 03:21:30', '2024-02-14 03:21:30'),
(13, 10, 15, 'ya sabes?', '2024-02-14 03:21:45', '2024-02-14 03:21:45'),
(17, 13, 15, 'Si, ya sabemos', '2024-02-14 17:32:07', '2024-02-14 17:32:07'),
(18, 12, 15, 'Hola', '2024-02-14 17:35:50', '2024-02-14 17:35:50'),
(19, 14, 17, 'Hoy es jueves', '2024-02-15 03:00:36', '2024-02-15 03:00:36'),
(20, 13, 17, 'Hoy es jueves', '2024-02-15 03:00:50', '2024-02-15 03:00:50'),
(25, 18, 21, 'no', '2024-02-15 18:09:57', '2024-02-15 19:38:12'),
(38, 18, 21, 'hola', '2024-02-15 19:24:37', '2024-02-15 19:24:37'),
(40, 18, 21, 'si?', '2024-02-15 23:51:11', '2024-02-15 23:51:11'),
(50, 19, 15, 'hola esta bien', '2024-02-18 02:01:13', '2024-02-18 02:01:13'),
(51, 19, 15, 'perfect', '2024-02-18 02:01:33', '2024-02-18 02:01:33'),
(54, 18, 22, 'hola', '2024-02-18 02:58:40', '2024-02-18 02:58:40'),
(55, 29, 15, 'me duele la espalda', '2024-02-18 03:14:29', '2024-02-18 12:53:34'),
(57, 30, 15, 'Es importante que sepas porque si no sabes no sabras bien y no tendras el conocimiento de saber a traves de lo que se sabe que tu no sabes', '2024-02-18 17:44:32', '2024-02-18 17:44:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
--

CREATE TABLE `publicaciones` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `publicaciones`
--

INSERT INTO `publicaciones` (`id`, `id_usuario`, `contenido`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 15, 'hola', '2024-02-13 20:28:29', '2024-02-13 20:28:29'),
(2, 15, 'Este es mi segundo post', '2024-02-13 20:31:12', '2024-02-13 20:31:12'),
(3, 11, 'Este es mi primer post', '2024-02-13 20:31:43', '2024-02-13 20:31:43'),
(4, 15, 'Soy el mejor', '2024-02-13 20:50:43', '2024-02-13 20:50:43'),
(6, 15, 'Hola sea quien sea', '2024-02-13 21:22:23', '2024-02-13 21:22:23'),
(7, 17, 'Hola soy la mamá de williams', '2024-02-13 21:29:01', '2024-02-13 21:29:01'),
(8, 15, 'Son las 6:47pm', '2024-02-13 22:47:27', '2024-02-13 22:47:27'),
(9, 11, 'Hola Soy Ana', '2024-02-13 23:57:07', '2024-02-13 23:57:07'),
(10, 15, 'No se que pasaaaa', '2024-02-14 01:01:22', '2024-02-14 01:01:22'),
(11, 15, 'Hola desde aqui', '2024-02-14 01:39:13', '2024-02-14 01:39:13'),
(12, 17, 'Hola', '2024-02-14 04:32:59', '2024-02-14 04:32:59'),
(13, 15, 'Hoy es miercoles', '2024-02-14 17:18:37', '2024-02-14 17:18:37'),
(14, 17, 'Hola son las 11:00pm', '2024-02-15 03:00:08', '2024-02-15 03:00:08'),
(15, 21, 'idk', '2024-02-15 04:10:54', '2024-02-15 04:10:54'),
(16, 21, 'q hacen', '2024-02-15 04:12:35', '2024-02-15 04:12:35'),
(17, 21, 'bulleao', '2024-02-15 04:13:28', '2024-02-15 04:13:28'),
(18, 15, 'Ya casi terminamos', '2024-02-15 05:09:32', '2024-02-15 05:09:32'),
(19, 22, 'Hola este es mi primer post', '2024-02-15 23:58:42', '2024-02-15 23:58:42'),
(29, 15, 'es hora de seguir programando', '2024-02-18 03:10:44', '2024-02-18 03:10:44'),
(30, 15, 'nose', '2024-02-18 14:48:19', '2024-02-18 14:48:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `correo_electronico` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `fecha_expiracion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tokens`
--

INSERT INTO `tokens` (`id`, `correo_electronico`, `token`, `fecha_expiracion`) VALUES
(24, 'juliana21@gmail.com', 'eb3002', '2024-02-15 00:02:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` varchar(20) NOT NULL DEFAULT 'offline'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `correo_electronico`, `contrasena`, `rol`, `fecha_creacion`, `fecha_actualizacion`, `estado`) VALUES
(3, 'Cesar', 'cesar@gmail.com', 'cesar21', 'usuario', '2024-02-11 18:02:06', '2024-02-11 18:02:06', 'offline'),
(4, 'Carlos', 'carlos@gmail.com', '$2y$10$MQsrTQewmHqtPgYxLJjpGuNPN5jx/ZP9azMk2AFsRis0dzOeVDnBO', 'usuario', '2024-02-11 18:50:50', '2024-02-11 18:50:50', 'offline'),
(7, 'Cr7', 'cristiano@gmail.com', '$2y$10$46a6YuPW/63ZMZUS1tA/2ubRFTEk7P0yH1/Bz.9gKXWmjgxpzTwnS', 'usuario', '2024-02-11 19:34:29', '2024-02-11 19:34:29', 'offline'),
(11, 'Ana Cesar', 'ana@gmail.com', '$2y$10$8oQC/R7Km/O8mRddL0YUtuSFWzX5x6Lqg.ejlvVYxYmspA3VaYRou', 'usuario', '2024-02-12 05:01:55', '2024-02-15 03:03:24', 'offline'),
(13, 'Benito Martinez', 'benito@gmail.com', '$2y$10$D/kkvRp/QmIDqMwxWdXxN.dxncDhmYIOIcwnEEvVmU1hDdifX6tDu', 'usuario', '2024-02-12 16:43:41', '2024-02-12 16:43:41', 'offline'),
(14, 'Jose Balvin', 'balvin@gmail.com', '$2y$10$7MaHTaxx8pPodnsGlWjSg.UtFKWdO26okYcXzyLok0HL/KFOP14li', 'usuario', '2024-02-12 16:45:19', '2024-02-12 16:45:19', 'offline'),
(15, 'Williams', 'williamscesar21@gmail.com', '$2y$10$K9n3QbU5Kf7R2wsC5dAbW.5uPJWv5oNHWupzuZ79XO8EqNThybvdG', 'admin', '2024-02-12 17:46:43', '2024-02-18 18:30:16', 'offline'),
(17, 'Denny Cesar', 'cesar.denny@gmail.com', '$2y$10$AVED7km4GwJ6okmC8.wctOmO33fr.rqXqetRontuLTln6d6fo8cDS', 'user', '2024-02-13 21:28:07', '2024-02-18 18:31:06', 'offline'),
(18, 'Wilkerson Antonieto', 'WilkersonAntonieto@gmail.com', '$2y$10$FKa6yIrEL4.pifnnrmGtC.K.RaqsAV3JkjoU0EpCioo8iC.amJHVG', 'usuario', '2024-02-14 18:36:42', '2024-02-15 03:02:55', 'offline'),
(19, 'Cesar Diaz', 'cesar21@gmail.com', '$2y$10$z8nlZKJIAr0b6Kl/Op2sBeLxMcaZ9YBSO5WJcheQIb.dw5Q6n15u.', 'usuario', '2024-02-14 19:24:48', '2024-02-15 02:58:54', 'offline'),
(21, 'Juliana', 'Juliana@gmail.com', '$2y$10$LFuQYeIP/Dis98Bipmp8IeWE5D8BY3K2xM4BEj9CASiVoIdYdbmya', 'usuario', '2024-02-15 03:25:52', '2024-02-17 00:51:16', 'offline'),
(22, 'Williams Jesus  Cesar', 'williamscesar1311@gmail.com', '$2y$10$DrI4YCQlhJb7QIcnNq4OwujLt0R2b3WWOxrOC3Xy5KMTlP0/i8UZ6', 'admin', '2024-02-15 23:57:09', '2024-02-18 17:15:32', 'offline');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_publicacion` (`id_publicacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_publicacion`) REFERENCES `publicaciones` (`id`),
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD CONSTRAINT `publicaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
