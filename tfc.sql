-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-04-2025 a las 17:27:56
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tfc`
--
CREATE DATABASE IF NOT EXISTS `tfc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tfc`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `appointment`
--

CREATE TABLE `appointment` (
  `id_appointment` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  `state` enum('earring','confirmed','canceled') NOT NULL DEFAULT 'earring',
  `id_customer` int(11) NOT NULL,
  `id_business` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  `comments` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `appointment`
--

INSERT INTO `appointment` (`id_appointment`, `date_time`, `state`, `id_customer`, `id_business`, `id_service`, `comments`) VALUES
(3, '2025-05-19 11:30:00', 'earring', 1, 2, 2, ''),
(4, '2025-04-21 09:00:00', 'canceled', 1, 1, 1, ''),
(5, '2025-04-21 09:15:00', 'canceled', 1, 1, 1, ''),
(6, '2025-04-21 09:30:00', 'confirmed', 1, 1, 1, ''),
(7, '2025-04-21 10:00:00', 'confirmed', 1, 1, 1, ''),
(8, '2025-04-21 09:45:00', 'earring', 1, 1, 1, ''),
(9, '2025-04-21 09:00:00', 'canceled', 1, 1, 1, ''),
(11, '2025-04-21 09:15:00', 'canceled', 1, 1, 1, ''),
(12, '2025-04-21 09:00:00', 'canceled', 1, 1, 1, ''),
(13, '2025-04-21 09:15:00', 'canceled', 1, 1, 1, ''),
(14, '2025-04-23 09:00:00', 'earring', 2, 1, 1, 'Cliente: Marta, Teléfono: 669334580, Comentarios: Llegara tarde'),
(16, '2025-04-28 09:00:00', 'earring', 1, 1, 1, ''),
(17, '2025-04-28 09:15:00', 'earring', 1, 1, 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `business`
--

CREATE TABLE `business` (
  `id_business` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `category` varchar(250) NOT NULL,
  `description` varchar(300) NOT NULL,
  `address` varchar(250) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `phone_number` int(11) NOT NULL,
  `business_image` varchar(300) NOT NULL,
  `id_administrator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `business`
--

INSERT INTO `business` (`id_business`, `name`, `category`, `description`, `address`, `postal_code`, `phone_number`, `business_image`, `id_administrator`) VALUES
(1, 'Tripiana', 'Peluqueria', 'Con mas de 50 años de servicios, Tripiana se enorgullece de contar con mas 10k clientes satisfechos', 'Calle Carretera Media Sala, 41, Cartagena, Murcia', '30310', 696754457, 'images/business_1_6803bfb1e3ad01.98118025.jpg', 2),
(2, 'Neumaticos y Servicios', 'Taller Automovilístico', 'Confiamos en contagiarte nuestro entusiasmo y que descubras que conducir no es solo un medio de transporte y cumplir trámites. Es mucho más…', 'ALAMEDA SAN ANTÓN, Nº33, Cartagena, Murcia', '30205', 654658825, 'images/fachada_neumaticos_alameda.png', 3),
(3, 'Calida', 'Peluqería', 'Calida Peluquería y Estética en Cartagena es una franquicia internacional líder que cuenta con la experiencia de más de 400 aperturas en todo el mundo y más de 4000 estilistas.', 'Rabales, Fuente Alamo, Murcia', '30310', 654855748, 'images/calida.jpg', 5),
(4, 'Peluquería Maya', 'Peluqueria', 'Peluquería moderna especializada en cortes, coloración y tratamientos capilares para mujeres y hombres. Ofrecemos servicios personalizados y productos de alta calidad en el centro de Cartagena.', 'Calle Real, 12, Cartagena, Murcia', '30201', 968123456, 'images/business_4_680ba0b87b4899.77367155.jpeg', 8),
(5, 'Barbería El Puerto', 'Peluqueria', 'Barbería tradicional y moderna, expertos en afeitados clásicos, arreglo de barba y cortes de tendencia. Ambiente acogedor y atención personalizada.', 'Plaza del Rey, 5, Cartagena, Murcia', '30202', 968654321, 'images/business_5_680ba1046a4c04.38791385.png', 9),
(6, 'Taller MotorCartagena', 'Taller de Automóviles', 'Especialistas en mecánica rápida, mantenimiento integral, diagnosis y reparación de vehículos. Servicio profesional y garantía en todos los trabajos.', 'Avenida Juan Carlos I, 45, Cartagena, Murcia', '30204', 968789123, 'images/business_6_680ba134d00c04.07693636.jpg', 10),
(7, 'TecnoAuto Cartagena', 'Taller de Automóviles', 'Servicio técnico especializado en reparación y sustitución de pantallas, cámaras de marcha atrás y sensores de aparcamiento para todo tipo de vehículos.', 'Calle Ángel Bruna, 21, Cartagena, Murcia', '30203', 968321654, 'images/business_7_680ba159937975.84497730.jpg', 11),
(8, 'Andreo Barber Shop', 'Peluqueria', 'Barbería tradicional con un toque moderno, especializada en cortes clásicos y afeitados personalizados. Atención cercana y ambiente relajado en el corazón de Cartagena.', 'Avda. de Murcia, 6 - Centro Comercial Cenit, Local 8, Cartagena', '30201', 968555123, 'images/business_8_680ba174638878.98326933.jpg', 12),
(9, 'Taller Vergara', 'Taller de Automóviles', 'Taller especializado en reparación mecánica, electrónica y mantenimiento integral de vehículos. Servicio profesional con garantía y rapidez en Cartagena.', 'Calle Ángel Bruna, 15, Cartagena', '30203', 968777456, 'images/business_9_680ba1dec6c190.23403567.jpg', 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `schedule`
--

CREATE TABLE `schedule` (
  `id_schedule` int(11) NOT NULL,
  `day_week` int(11) NOT NULL,
  `opening_hour` time NOT NULL,
  `closing_hour` time NOT NULL,
  `id_business` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `schedule`
--

INSERT INTO `schedule` (`id_schedule`, `day_week`, `opening_hour`, `closing_hour`, `id_business`) VALUES
(1, 1, '09:00:00', '20:00:00', 1),
(2, 2, '09:00:00', '20:00:00', 1),
(3, 3, '09:00:00', '20:00:00', 1),
(4, 4, '09:00:00', '20:00:00', 1),
(5, 5, '09:00:00', '20:30:00', 1),
(6, 1, '10:30:00', '14:00:00', 2),
(7, 1, '17:00:00', '20:00:00', 2),
(9, 6, '10:30:00', '14:30:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `service`
--

CREATE TABLE `service` (
  `id_service` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `duration` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `id_business` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `service`
--

INSERT INTO `service` (`id_service`, `name`, `duration`, `price`, `id_business`) VALUES
(1, 'Corte Fade/ Degradado', 15, 12.00, 1),
(2, 'Sustitución de luna', 60, 200.00, 2),
(8, 'Arreglo Barba', 30, 6.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `surnames` varchar(250) NOT NULL,
  `phone_number` int(11) NOT NULL,
  `birthdate` date NOT NULL DEFAULT '1900-01-01',
  `email` varchar(300) NOT NULL,
  `password` varchar(300) NOT NULL,
  `role` enum('customer','administrator') NOT NULL DEFAULT 'customer',
  `avatar_image` varchar(300) NOT NULL DEFAULT 'avatar_images/default_avatar.png',
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id_user`, `name`, `surnames`, `phone_number`, `birthdate`, `email`, `password`, `role`, `avatar_image`, `registration_date`) VALUES
(1, 'Elias', 'Pedrero Guerrouj', 644478521, '2004-09-08', 'eliaspedrero4@gmail.com', '12345678', 'customer', 'avatar_images/avatar_1_1744989405.jpg', '2025-04-18 15:16:45'),
(2, 'Ramon', 'Valverde Garcia', 669357851, '2003-10-08', 'ramonvalverde@gmail.com', '12345678', 'administrator', 'avatar_images/default_avatar.png', '2025-04-26 14:52:19'),
(3, 'Roberto', 'Segado Díaz', 621845480, '1900-01-01', 'robertosegado@gmail.com', '12345678', 'administrator', 'avatar_images/default_avatar.png', '2025-04-16 14:59:59'),
(4, 'Natalia', 'Lara Robles', 639254770, '1996-02-29', 'natalia@gmail.com', '12345678', 'customer', 'avatar_images/default_avatar.png', '2025-04-16 15:00:33'),
(5, 'Domingo', 'Martinez Pascual', 614785235, '1991-04-24', 'domingo@gmail.com', '12345678', 'administrator', 'avatar_images/default_avatar.png', '2025-04-16 15:00:51'),
(8, 'Paula', 'Maya Martinez', 0, '2005-11-22', 'paulamaya@gmail.com', '12345678', 'administrator', 'avatar_images/avatar_8_1745592512.jpeg', '2025-04-25 14:48:32'),
(9, 'Javier', 'Saura Jaen', 0, '2004-02-06', 'javiersaura@gmail.com', '12345678', 'administrator', 'avatar_images/default_avatar.png', '2025-04-25 14:39:18'),
(10, 'Juan Manuel', 'Esparza Cervantes', 0, '2004-02-03', 'juanmanuelesparza@gmail.com', '12345678', 'administrator', 'avatar_images/default_avatar.png', '2025-04-25 14:40:42'),
(11, 'Antonio', 'Truque Parra', 0, '2004-12-07', 'antoniotruque@gmail.com', '12345678', 'administrator', 'avatar_images/default_avatar.png', '2025-04-25 14:42:11'),
(12, 'Jose', 'Andreo Rodriguez', 0, '2004-05-13', 'joseandreo@gmail.com', '12345678', 'administrator', 'avatar_images/default_avatar.png', '2025-04-25 14:45:37'),
(13, 'Juan Pedro', 'Vergara Saez', 0, '2004-07-15', 'juanpedrovergara@gmail.com', '12345678', 'administrator', 'avatar_images/default_avatar.png', '2025-04-25 14:47:39');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id_appointment`),
  ADD KEY `fk_customer` (`id_customer`),
  ADD KEY `fk_service` (`id_service`),
  ADD KEY `fk_busines` (`id_business`);

--
-- Indices de la tabla `business`
--
ALTER TABLE `business`
  ADD PRIMARY KEY (`id_business`),
  ADD KEY `fk_user` (`id_administrator`);

--
-- Indices de la tabla `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id_schedule`),
  ADD KEY `fk_shedule` (`id_business`);

--
-- Indices de la tabla `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id_service`),
  ADD KEY `fk_business` (`id_business`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id_appointment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `business`
--
ALTER TABLE `business`
  MODIFY `id_business` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id_schedule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `fk_busines` FOREIGN KEY (`id_business`) REFERENCES `business` (`id_business`),
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`id_customer`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `fk_service` FOREIGN KEY (`id_service`) REFERENCES `service` (`id_service`);

--
-- Filtros para la tabla `business`
--
ALTER TABLE `business`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`id_administrator`) REFERENCES `users` (`id_user`);

--
-- Filtros para la tabla `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `fk_shedule` FOREIGN KEY (`id_business`) REFERENCES `business` (`id_business`);

--
-- Filtros para la tabla `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `fk_business` FOREIGN KEY (`id_business`) REFERENCES `business` (`id_business`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
