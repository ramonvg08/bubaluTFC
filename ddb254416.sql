-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: proxysql-01.dd.scip.local
-- Tiempo de generación: 01-06-2025 a las 19:10:24
-- Versión del servidor: 11.6.2-MariaDB-deb12
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ddb254416`
--
CREATE DATABASE IF NOT EXISTS `ddb254416` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ddb254416`;

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
(1, '2025-06-03 10:00:00', 'canceled', 21, 1, 1, 'Primera revisión'),
(2, '2025-06-04 11:00:00', 'confirmed', 22, 1, 2, ''),
(3, '2025-06-05 12:00:00', 'canceled', 23, 1, 3, ''),
(4, '2025-06-06 10:00:00', 'confirmed', 24, 2, 5, ''),
(5, '2025-06-07 11:30:00', 'confirmed', 25, 2, 6, 'Primera vez que reservo aquí, espero una buena atención.'),
(6, '2025-06-08 12:30:00', 'canceled', 26, 2, 5, ''),
(7, '2025-06-09 10:15:00', 'earring', 27, 3, 9, ''),
(8, '2025-06-10 11:15:00', 'confirmed', 28, 3, 10, ''),
(9, '2025-06-11 12:15:00', 'canceled', 29, 3, 11, 'Por favor, confirmar disponibilidad antes del día.'),
(10, '2025-06-12 09:00:00', 'earring', 30, 4, 13, 'Necesito que sea rápido, tengo otro compromiso.'),
(11, '2025-06-13 10:00:00', 'confirmed', 21, 4, 14, ''),
(12, '2025-06-14 11:00:00', 'canceled', 22, 4, 15, ''),
(13, '2025-06-15 10:30:00', 'earring', 23, 5, 17, ''),
(14, '2025-06-16 11:30:00', 'confirmed', 24, 5, 18, 'Ya he ido otras veces, excelente servicio.'),
(15, '2025-06-17 12:30:00', 'canceled', 25, 5, 19, ''),
(16, '2025-06-18 10:45:00', 'earring', 26, 6, 21, ''),
(17, '2025-06-19 11:45:00', 'confirmed', 27, 6, 22, ''),
(18, '2025-06-20 12:45:00', 'canceled', 28, 6, 23, 'Solicito factura tras la cita, gracias.'),
(19, '2025-06-21 09:30:00', 'earring', 29, 7, 25, 'Vengo recomendado por un amigo.'),
(20, '2025-06-22 10:30:00', 'confirmed', 30, 7, 26, ''),
(21, '2025-06-23 11:30:00', 'canceled', 21, 7, 27, ''),
(22, '2025-06-24 09:15:00', 'earring', 22, 8, 30, ''),
(23, '2025-06-25 10:15:00', 'confirmed', 23, 8, 29, 'Cita reservada online, espero confirmación.'),
(24, '2025-06-26 11:15:00', 'canceled', 24, 8, 31, ''),
(25, '2025-06-27 10:45:00', 'earring', 25, 9, 32, ''),
(26, '2025-06-28 11:45:00', 'confirmed', 26, 9, 33, ''),
(27, '2025-06-29 12:45:00', 'canceled', 27, 9, 34, 'Por favor, contactar si hay algún cambio.'),
(28, '2025-06-30 09:00:00', 'earring', 28, 10, 36, ''),
(29, '2025-07-01 10:00:00', 'confirmed', 29, 10, 37, ''),
(30, '2025-07-02 11:00:00', 'canceled', 30, 10, 38, 'Preferiría atención con el mismo profesional que la vez anterior.'),
(31, '2025-07-03 09:30:00', 'canceled', 21, 11, 41, 'Voy con algo de prisa, agradecería puntualidad.'),
(32, '2025-07-04 10:30:00', 'confirmed', 22, 11, 42, ''),
(33, '2025-07-05 11:30:00', 'canceled', 23, 11, 43, ''),
(34, '2025-07-06 10:45:00', 'earring', 24, 12, 46, ''),
(35, '2025-07-07 11:45:00', 'confirmed', 25, 12, 47, 'Es una cita urgente, agradeceré prioridad.'),
(36, '2025-07-08 12:45:00', 'canceled', 26, 12, 45, ''),
(37, '2025-07-09 09:15:00', 'earring', 27, 13, 50, ''),
(38, '2025-07-10 10:15:00', 'confirmed', 28, 13, 51, ''),
(39, '2025-07-11 11:15:00', 'canceled', 29, 13, 49, 'Quiero aclarar dudas sobre el servicio antes de realizarlo.'),
(40, '2025-07-12 10:00:00', 'earring', 30, 14, 54, 'Espero que puedan atender una consulta adicional.'),
(41, '2025-07-13 11:00:00', 'confirmed', 21, 14, 55, ''),
(42, '2025-07-14 12:00:00', 'canceled', 22, 14, 53, ''),
(43, '2025-07-15 10:30:00', 'earring', 23, 15, 57, ''),
(44, '2025-07-16 11:30:00', 'confirmed', 24, 15, 58, 'Espero que puedan atender una consulta adicional.'),
(45, '2025-07-17 12:30:00', 'canceled', 25, 15, 59, ''),
(46, '2025-07-18 10:15:00', 'earring', 26, 16, 61, ''),
(47, '2025-07-19 11:15:00', 'confirmed', 27, 16, 62, ''),
(48, '2025-07-20 12:15:00', 'canceled', 28, 16, 60, 'Confirmar si debo llevar algo a la cita.'),
(49, '2025-07-21 09:45:00', 'earring', 29, 17, 65, 'Tengo movilidad reducida, agradecería accesibilidad.'),
(50, '2025-07-22 10:45:00', 'confirmed', 30, 17, 66, ''),
(51, '2025-07-23 11:45:00', 'canceled', 21, 17, 64, ''),
(52, '2025-07-24 10:00:00', 'earring', 22, 18, 69, ''),
(53, '2025-07-25 11:00:00', 'confirmed', 23, 18, 70, 'Por favor, avisar si hay retrasos previstos.'),
(54, '2025-07-26 12:00:00', 'canceled', 24, 18, 68, ''),
(55, '2025-07-27 10:30:00', 'earring', 25, 19, 73, ''),
(56, '2025-07-28 11:30:00', 'confirmed', 26, 19, 74, ''),
(57, '2025-07-29 12:30:00', 'canceled', 27, 19, 72, 'Será mi primera visita, agradezco orientación.'),
(58, '2025-07-30 10:15:00', 'earring', 28, 20, 77, 'Tengo algunas alergias, comentarlas antes de usar productos.'),
(59, '2025-07-31 11:15:00', 'confirmed', 29, 20, 78, ''),
(60, '2025-08-01 12:15:00', 'canceled', 30, 20, 76, ''),
(61, '2025-05-28 09:00:00', 'confirmed', 31, 2, 5, ''),
(62, '2025-06-02 16:00:00', 'confirmed', 32, 2, 5, ''),
(68, '2025-05-29 12:00:00', 'canceled', 20, 20, 75, 'Cliente: alberto, Teléfono: 677342156, Comentarios: Llegaré tarde'),
(69, '2025-05-29 14:00:00', 'earring', 20, 8, 29, ''),
(70, '2025-05-30 16:00:00', 'earring', 39, 3, 9, ''),
(71, '2025-05-30 11:45:00', 'earring', 40, 3, 10, ''),
(72, '2025-06-03 13:30:00', 'confirmed', 2, 2, 6, 'Cliente: MariCarmen, Teléfono: 33326578, Comentarios: Vendra con la Charo');

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
(1, 'AutoPro Murcia', 'Taller Automovilistico', 'Mecánica general y diagnosis avanzada para todo tipo de vehículos.', 'Calle Mayor, 120, Murcia', '30001', 678123456, 'images/business_autopro.jpg', 1),
(2, 'Estética Belmar', 'Centro de Estética', 'Tratamientos estéticos personalizados y depilación láser.', 'Av. Libertad, 45, Murcia', '30009', 679234567, 'images/business_belmar.jpg', 2),
(3, 'Clínica Dental Sonrisa', 'Dentista', 'Expertos en ortodoncia, implantes y blanqueamientos.', 'Calle Princesa, 12, Murcia', '30008', 612345678, 'images/business_dental.jpg', 3),
(4, 'GymFit 24h', 'Gimnasio', 'Centro deportivo 24/7 con clases dirigidas y zona de musculación.', 'Calle Sagasta, 33, Murcia', '30003', 634987123, 'images/business_gymfit.jpg', 4),
(5, 'VetSalud Express', 'Veterinario', 'Veterinaria general y exótica. Urgencias 24h.', 'Cuartel Artillería, 9, Murcia', '30004', 689321654, 'images/business_vet.jpg', 5),
(6, 'Clínica Medisalud', 'Clinica', 'Consulta general, pediatría, ginecología y más.', 'Ronda Sur, 12, Murcia', '30010', 654789123, 'images/business_medisalud.jpg', 6),
(7, 'ChicLook', 'Peluquería', 'Corte, tinte y tratamientos capilares en un ambiente moderno.', 'Juan Carlos I, 112, Murcia', '30007', 657456789, 'images/business_chiclook.jpg', 7),
(8, 'ReparaTodo', 'Taller Automovilistico', 'Reparaciones exprés con garantía oficial.', 'Pedro Flores, 22, Murcia', '30011', 623789456, 'images/business_reparatodo.jpg', 8),
(9, 'Bella Center', 'Centro de Estética', 'Radiofrecuencia, mesoterapia, higiene facial y más.', 'Calle Cartagena, 18, Murcia', '30002', 645123789, 'images/business_bella.jpg', 9),
(10, 'Dental Nova', 'Dentista', 'Ortodoncia invisible, cirugía y estética dental.', 'Av. Europa, 99, Murcia', '30005', 678654321, 'images/business_nova.jpg', 10),
(11, 'Iron Gym', 'Gimnasio', 'Entrena en un entorno profesional y bien equipado.', 'Plaza Circular, 1, Murcia', '30008', 621987654, 'images/business_iron.jpg', 11),
(12, 'VetCare', 'Veterinario', 'Cuidado profesional para mascotas y animales exóticos.', 'Trapería, 20, Murcia', '30001', 699112233, 'images/business_vetcare.jpg', 12),
(13, 'Centro Vida', 'Clinica', 'Centro médico integral con laboratorio propio.', 'Azucaque, 5, Murcia', '30009', 622334455, 'images/business_vida.jpg', 13),
(14, 'Look&Style', 'Peluquería', 'Tu estilo con productos naturales y veganos.', 'Jabonerías, 14, Murcia', '30001', 600987654, 'images/business_look.jpg', 14),
(15, 'ElectroAuto', 'Taller Automovilistico', 'Especialistas en coches eléctricos e híbridos.', 'Santa Teresa, 38, Murcia', '30003', 677998877, 'images/business_eauto.jpg', 15),
(16, 'Zen Belleza', 'Centro de Estética', 'Relájate y cuida tu piel con terapias orientales.', 'Platería, 17, Murcia', '30004', 644112233, 'images/business_zen.jpg', 16),
(17, 'Dental Kids', 'Dentista', 'Clínica infantil con atención lúdica y profesional.', 'San Nicolás, 27, Murcia', '30005', 688445566, 'images/business_kids.jpg', 17),
(18, 'The Muscle Room', 'Gimnasio', 'Culturismo y powerlifting en el mejor ambiente.', 'Palmar, 77, Murcia', '30120', 633221144, 'images/business_muscle.jpg', 18),
(19, 'ExoVet', 'Veterinario', 'Atención especializada en animales exóticos.', 'Carmen Conde, 8, Murcia', '30009', 688112233, 'images/business_exovet.jpg', 19),
(20, 'Soluciones XYZ', 'Otros', 'Consultora de software y servicios para pymes.', 'Polígono Oeste, C/ Uruguay, Alcantarilla', '30820', 687123456, 'images/business_xyz.jpg', 20);

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
(1, 1, '08:00:00', '14:00:00', 1),
(2, 1, '16:00:00', '21:00:00', 1),
(3, 2, '08:00:00', '14:00:00', 1),
(4, 2, '16:00:00', '21:00:00', 1),
(5, 3, '08:00:00', '14:00:00', 1),
(6, 3, '16:00:00', '21:00:00', 1),
(7, 4, '08:00:00', '14:00:00', 1),
(8, 4, '16:00:00', '21:00:00', 1),
(9, 5, '08:00:00', '14:00:00', 1),
(10, 5, '16:00:00', '21:00:00', 1),
(11, 1, '08:00:00', '14:00:00', 3),
(12, 1, '16:00:00', '21:00:00', 3),
(13, 2, '08:00:00', '14:00:00', 3),
(14, 2, '16:00:00', '21:00:00', 3),
(15, 3, '08:00:00', '14:00:00', 3),
(16, 3, '16:00:00', '21:00:00', 3),
(17, 4, '08:00:00', '14:00:00', 3),
(18, 4, '16:00:00', '21:00:00', 3),
(19, 5, '08:00:00', '14:00:00', 3),
(20, 5, '16:00:00', '21:00:00', 3),
(21, 1, '08:00:00', '14:00:00', 6),
(22, 1, '16:00:00', '21:00:00', 6),
(23, 2, '08:00:00', '14:00:00', 6),
(24, 2, '16:00:00', '21:00:00', 6),
(25, 3, '08:00:00', '14:00:00', 6),
(26, 3, '16:00:00', '21:00:00', 6),
(27, 4, '08:00:00', '14:00:00', 6),
(28, 4, '16:00:00', '21:00:00', 6),
(29, 5, '08:00:00', '14:00:00', 6),
(30, 5, '16:00:00', '21:00:00', 6),
(31, 1, '08:00:00', '14:00:00', 9),
(32, 1, '16:00:00', '21:00:00', 9),
(33, 2, '08:00:00', '14:00:00', 9),
(34, 2, '16:00:00', '21:00:00', 9),
(35, 3, '08:00:00', '14:00:00', 9),
(36, 3, '16:00:00', '21:00:00', 9),
(37, 4, '08:00:00', '14:00:00', 9),
(38, 4, '16:00:00', '21:00:00', 9),
(39, 5, '08:00:00', '14:00:00', 9),
(40, 5, '16:00:00', '21:00:00', 9),
(41, 1, '08:00:00', '14:00:00', 11),
(42, 1, '16:00:00', '21:00:00', 11),
(43, 2, '08:00:00', '14:00:00', 11),
(44, 2, '16:00:00', '21:00:00', 11),
(45, 3, '08:00:00', '14:00:00', 11),
(46, 3, '16:00:00', '21:00:00', 11),
(47, 4, '08:00:00', '14:00:00', 11),
(48, 4, '16:00:00', '21:00:00', 11),
(49, 5, '08:00:00', '14:00:00', 11),
(50, 5, '16:00:00', '21:00:00', 11),
(51, 1, '08:00:00', '14:00:00', 14),
(52, 1, '16:00:00', '21:00:00', 14),
(53, 2, '08:00:00', '14:00:00', 14),
(54, 2, '16:00:00', '21:00:00', 14),
(55, 3, '08:00:00', '14:00:00', 14),
(56, 3, '16:00:00', '21:00:00', 14),
(57, 4, '08:00:00', '14:00:00', 14),
(58, 4, '16:00:00', '21:00:00', 14),
(59, 5, '08:00:00', '14:00:00', 14),
(60, 5, '16:00:00', '21:00:00', 14),
(61, 1, '08:00:00', '14:00:00', 17),
(62, 1, '16:00:00', '21:00:00', 17),
(63, 2, '08:00:00', '14:00:00', 17),
(64, 2, '16:00:00', '21:00:00', 17),
(65, 3, '08:00:00', '14:00:00', 17),
(66, 3, '16:00:00', '21:00:00', 17),
(67, 4, '08:00:00', '14:00:00', 17),
(68, 4, '16:00:00', '21:00:00', 17),
(69, 5, '08:00:00', '14:00:00', 17),
(70, 5, '16:00:00', '21:00:00', 17),
(71, 1, '08:00:00', '14:00:00', 19),
(72, 1, '16:00:00', '21:00:00', 19),
(73, 2, '08:00:00', '14:00:00', 19),
(74, 2, '16:00:00', '21:00:00', 19),
(75, 3, '08:00:00', '14:00:00', 19),
(76, 3, '16:00:00', '21:00:00', 19),
(77, 4, '08:00:00', '14:00:00', 19),
(78, 4, '16:00:00', '21:00:00', 19),
(79, 5, '08:00:00', '14:00:00', 19),
(80, 5, '16:00:00', '21:00:00', 19),
(81, 1, '09:00:00', '20:00:00', 2),
(82, 2, '09:00:00', '20:00:00', 2),
(83, 3, '09:00:00', '20:00:00', 2),
(84, 4, '09:00:00', '20:00:00', 2),
(85, 5, '09:00:00', '20:00:00', 2),
(86, 1, '09:00:00', '20:00:00', 4),
(87, 2, '09:00:00', '20:00:00', 4),
(88, 3, '09:00:00', '20:00:00', 4),
(89, 4, '09:00:00', '20:00:00', 4),
(90, 5, '09:00:00', '20:00:00', 4),
(91, 1, '09:00:00', '20:00:00', 5),
(92, 2, '09:00:00', '20:00:00', 5),
(93, 3, '09:00:00', '20:00:00', 5),
(94, 4, '09:00:00', '20:00:00', 5),
(95, 5, '09:00:00', '20:00:00', 5),
(96, 1, '09:00:00', '20:00:00', 7),
(97, 2, '09:00:00', '20:00:00', 7),
(98, 3, '09:00:00', '20:00:00', 7),
(99, 4, '09:00:00', '20:00:00', 7),
(100, 5, '09:00:00', '20:00:00', 7),
(101, 1, '09:00:00', '20:00:00', 8),
(102, 2, '09:00:00', '20:00:00', 8),
(103, 3, '09:00:00', '20:00:00', 8),
(104, 4, '09:00:00', '20:00:00', 8),
(105, 5, '09:00:00', '20:00:00', 8),
(106, 1, '09:00:00', '20:00:00', 10),
(107, 2, '09:00:00', '20:00:00', 10),
(108, 3, '09:00:00', '20:00:00', 10),
(109, 4, '09:00:00', '20:00:00', 10),
(110, 5, '09:00:00', '20:00:00', 10),
(111, 1, '09:00:00', '20:00:00', 12),
(112, 2, '09:00:00', '20:00:00', 12),
(113, 3, '09:00:00', '20:00:00', 12),
(114, 4, '09:00:00', '20:00:00', 12),
(115, 5, '09:00:00', '20:00:00', 12),
(116, 1, '09:00:00', '20:00:00', 13),
(117, 2, '09:00:00', '20:00:00', 13),
(118, 3, '09:00:00', '20:00:00', 13),
(119, 4, '09:00:00', '20:00:00', 13),
(120, 5, '09:00:00', '20:00:00', 13),
(121, 1, '09:00:00', '20:00:00', 15),
(122, 2, '09:00:00', '20:00:00', 15),
(123, 3, '09:00:00', '20:00:00', 15),
(124, 4, '09:00:00', '20:00:00', 15),
(125, 5, '09:00:00', '20:00:00', 15),
(126, 1, '09:00:00', '20:00:00', 16),
(127, 2, '09:00:00', '20:00:00', 16),
(128, 3, '09:00:00', '20:00:00', 16),
(129, 4, '09:00:00', '20:00:00', 16),
(130, 5, '09:00:00', '20:00:00', 16),
(131, 1, '09:00:00', '20:00:00', 18),
(132, 2, '09:00:00', '20:00:00', 18),
(133, 3, '09:00:00', '20:00:00', 18),
(134, 4, '09:00:00', '20:00:00', 18),
(135, 5, '09:00:00', '20:00:00', 18),
(136, 1, '09:00:00', '20:00:00', 20),
(137, 2, '09:00:00', '20:00:00', 20),
(138, 3, '09:00:00', '20:00:00', 20),
(139, 4, '09:00:00', '20:00:00', 20),
(140, 5, '09:00:00', '20:00:00', 20);

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
(1, 'Cambio de aceite y filtros', 60, 80.00, 1),
(2, 'Revisión pre-ITV', 45, 50.00, 1),
(3, 'Sustitución de frenos', 90, 120.00, 1),
(4, 'Alineación de dirección', 40, 40.00, 1),
(5, 'Higiene facial profunda', 60, 50.00, 2),
(6, 'Depilación láser piernas completas', 90, 70.00, 2),
(7, 'Tratamiento antiacné', 50, 50.00, 2),
(8, 'Masaje relajante', 60, 55.00, 2),
(9, 'Limpieza dental', 30, 35.00, 3),
(10, 'Empaste dental', 45, 60.00, 3),
(11, 'Blanqueamiento dental', 60, 150.00, 3),
(12, 'Ortodoncia (consulta inicial)', 30, 0.00, 3),
(13, 'Clase de Spinning', 45, 8.00, 4),
(14, 'Entrenamiento personal', 60, 30.00, 4),
(15, 'Zumba', 60, 7.00, 4),
(16, 'Acceso libre mensual', 1440, 35.00, 4),
(17, 'Consulta general', 30, 25.00, 5),
(18, 'Vacunación anual', 20, 30.00, 5),
(19, 'Desparasitación interna/externa', 15, 20.00, 5),
(20, 'Implantación de microchip', 25, 35.00, 5),
(21, 'Consulta médica general', 20, 30.00, 6),
(22, 'Análisis de sangre completo', 15, 40.00, 6),
(23, 'Consulta pediátrica', 25, 35.00, 6),
(24, 'Electrocardiograma', 30, 50.00, 6),
(25, 'Corte mujer', 30, 20.00, 7),
(26, 'Coloración completa', 90, 50.00, 7),
(27, 'Tratamiento de keratina', 60, 45.00, 7),
(28, 'Peinado para eventos', 45, 35.00, 7),
(29, 'Cambio de batería', 30, 60.00, 8),
(30, 'Reparación de alternador', 90, 150.00, 8),
(31, 'Cambio de amortiguadores', 120, 180.00, 8),
(32, 'Radiofrecuencia facial', 45, 60.00, 9),
(33, 'Mesoterapia corporal', 50, 70.00, 9),
(34, 'Presoterapia', 40, 35.00, 9),
(35, 'Exfoliación y limpieza', 30, 30.00, 9),
(36, 'Revisión y diagnóstico', 30, 25.00, 10),
(37, 'Colocación de carillas', 120, 400.00, 10),
(38, 'Extracción de muela', 45, 70.00, 10),
(39, 'Fluorización dental', 20, 20.00, 10),
(40, 'CrossFit básico', 60, 9.00, 11),
(41, 'Entrenamiento HIIT', 45, 10.00, 11),
(42, 'Acceso mensual', 1440, 40.00, 11),
(43, 'Consulta con entrenador', 30, 15.00, 11),
(44, 'Consulta para cachorros', 25, 30.00, 12),
(45, 'Corte de uñas', 15, 10.00, 12),
(46, 'Limpieza de oídos', 15, 12.00, 12),
(47, 'Radiografía simple', 30, 45.00, 12),
(48, 'Consulta ginecológica', 30, 40.00, 13),
(49, 'Ecografía abdominal', 25, 45.00, 13),
(50, 'Consulta de nutrición', 30, 35.00, 13),
(51, 'Chequeo general', 45, 60.00, 13),
(52, 'Corte masculino', 20, 15.00, 14),
(53, 'Balayage', 120, 60.00, 14),
(54, 'Tratamiento anticaída', 45, 30.00, 14),
(55, 'Tinte raíz', 60, 35.00, 14),
(56, 'Diagnóstico electrónico', 60, 55.00, 15),
(57, 'Reparación de centralita', 90, 180.00, 15),
(58, 'Sustitución sensores', 45, 70.00, 15),
(59, 'Programación de llaves', 30, 50.00, 15),
(60, 'Masaje shiatsu', 60, 65.00, 16),
(61, 'Tratamiento reafirmante', 50, 60.00, 16),
(62, 'Aromaterapia facial', 45, 45.00, 16),
(63, 'Revisión infantil', 20, 25.00, 17),
(64, 'Selladores de fisuras', 30, 40.00, 17),
(65, 'Empaste infantil', 30, 35.00, 17),
(66, 'Extracción de diente de leche', 20, 20.00, 17),
(67, 'Plan personalizado musculación', 60, 35.00, 18),
(68, 'Asesoría de suplementación', 45, 20.00, 18),
(69, 'Rutina avanzada fuerza', 90, 30.00, 18),
(70, 'Entrenamiento en pareja', 60, 25.00, 18),
(71, 'Consulta reptiles', 30, 35.00, 19),
(72, 'Vacuna aves exóticas', 20, 28.00, 19),
(73, 'Control postoperatorio', 30, 20.00, 19),
(74, 'Consulta roedores', 25, 25.00, 19),
(75, 'Consultoría informática', 60, 75.00, 20),
(76, 'Mantenimiento de equipos', 90, 90.00, 20),
(77, 'Diseño web básico', 120, 150.00, 20),
(78, 'Instalación de software', 45, 40.00, 20);

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
(1, 'Carlos', 'Ramírez López', 678123456, '1990-05-12', 'carlos.ramirez@example.com', 'Car@123456', 'administrator', 'avatar_images/avatar1.jpg', '2025-05-27 14:45:47'),
(2, 'Laura', 'García Pérez', 679234567, '1988-09-21', 'laura.garcia@example.com', 'Laur#98765', 'administrator', 'avatar_images/avatar2.jpg', '2025-05-27 14:45:47'),
(3, 'Manuel', 'Martínez Ruiz', 612345678, '1992-01-10', 'manuel.martinez@example.com', 'M4nu_8756', 'administrator', 'avatar_images/avatar3.jpg', '2025-05-27 14:45:47'),
(4, 'Lucía', 'Hernández Gómez', 634987123, '1995-04-22', 'lucia.hernandez@example.com', 'Luci@8890', 'administrator', 'avatar_images/avatar4.jpg', '2025-05-27 14:45:47'),
(5, 'Pedro', 'Sánchez Torres', 689321654, '1987-12-01', 'pedro.sanchez@example.com', 'P3dro!910', 'administrator', 'avatar_images/avatar5.jpg', '2025-05-27 14:45:47'),
(6, 'Sofía', 'Moreno Díaz', 654789123, '1991-07-08', 'sofia.moreno@example.com', 'S0fia_111', 'administrator', 'avatar_images/avatar6.jpg', '2025-05-27 14:45:47'),
(7, 'Diego', 'Jiménez Romero', 657456789, '1993-11-30', 'diego.jimenez@example.com', 'Dieg@5432', 'administrator', 'avatar_images/avatar7.jpg', '2025-05-27 14:45:48'),
(8, 'Elena', 'Ruiz Navarro', 623789456, '1989-03-17', 'elena.ruiz@example.com', 'El3n@2025', 'administrator', 'avatar_images/avatar8.jpg', '2025-05-27 14:45:48'),
(9, 'David', 'Fernández Castro', 645123789, '1996-08-05', 'david.fernandez@example.com', 'Dav!d_951', 'administrator', 'avatar_images/avatar9.jpg', '2025-05-27 14:45:48'),
(10, 'Marta', 'Ortega León', 678654321, '1994-06-14', 'marta.ortega@example.com', 'Mart@7788', 'administrator', 'avatar_images/avatar10.jpg', '2025-05-27 14:45:48'),
(11, 'Javier', 'Domínguez Vela', 621987654, '1985-10-19', 'javier.dominguez@example.com', 'Javi_1289', 'administrator', 'avatar_images/avatar11.jpg', '2025-05-27 14:45:48'),
(12, 'Paula', 'Cano Rivas', 699112233, '1993-02-03', 'paula.cano@example.com', 'Paula$012', 'administrator', 'avatar_images/avatar12.jpg', '2025-05-27 14:45:48'),
(13, 'Alejandro', 'López Sáez', 622334455, '1986-04-28', 'alejandro.lopez@example.com', 'Alej@997', 'administrator', 'avatar_images/avatar13.jpg', '2025-05-27 14:45:48'),
(14, 'Cristina', 'Molina Vera', 600987654, '1990-11-07', 'cristina.molina@example.com', 'Cris#4455', 'administrator', 'avatar_images/avatar14.jpg', '2025-05-27 14:45:48'),
(15, 'Sergio', 'Castro Mena', 677998877, '1995-01-20', 'sergio.castro@example.com', 'Sergio8&9', 'administrator', 'avatar_images/avatar15.jpg', '2025-05-27 14:45:48'),
(16, 'Nuria', 'Silva Gallego', 644112233, '1991-09-12', 'nuria.silva@example.com', 'Nuri@6537', 'administrator', 'avatar_images/avatar16.jpg', '2025-05-27 14:45:48'),
(17, 'Rubén', 'Gómez Salas', 688445566, '1987-03-26', 'ruben.gomez@example.com', 'Rub#en_89', 'administrator', 'avatar_images/avatar17.jpg', '2025-05-27 14:45:48'),
(18, 'Irene', 'Peña Lozano', 633221144, '1992-12-30', 'irene.pena@example.com', 'Ir3ne*320', 'administrator', 'avatar_images/avatar18.jpg', '2025-05-27 14:45:48'),
(19, 'Álvaro', 'Vidal Bonet', 688112233, '1994-07-17', 'alvaro.vidal@example.com', 'Alv@3890', 'administrator', 'avatar_images/avatar19.jpg', '2025-05-27 14:45:48'),
(20, 'Beatriz', 'Campos Nieto', 687123456, '1990-06-02', 'beatriz.campos@example.com', 'Beat!z579', 'administrator', 'avatar_images/avatar20.jpg', '2025-05-27 14:45:48'),
(21, 'Ramón', 'Valverde García', 610990933, '2002-10-08', 'ramon@gmail.com', '12345678a', 'customer', 'avatar_images/avatar_21_1748370138.jpeg', '2025-05-27 18:22:18'),
(22, 'Ana', 'Gómez Ruiz', 622334455, '1995-08-30', 'ana@example.com', 'Ana#5678', 'customer', 'avatar_images/avatar22.jpg', '2025-05-27 15:55:32'),
(23, 'Mario', 'Fernández Pérez', 633445566, '1988-11-12', 'mario@example.com', 'Mario!7890', 'customer', 'avatar_images/avatar23.jpg', '2025-05-27 15:55:32'),
(24, 'Clara', 'López García', 644556677, '1993-06-22', 'clara@example.com', 'Clara_123', 'customer', 'avatar_images/avatar24.jpg', '2025-05-27 15:55:32'),
(25, 'Diego', 'Sánchez Gil', 655667788, '1991-04-02', 'diego@example.com', 'D1ego@456', 'customer', 'avatar_images/avatar25.jpg', '2025-05-27 15:55:32'),
(26, 'Paula', 'Navarro Díaz', 666778899, '1990-10-10', 'paula@example.com', 'P4ula_321', 'customer', 'avatar_images/avatar26.jpg', '2025-05-27 15:55:32'),
(27, 'Sergio', 'Ramírez Ortega', 677889900, '1994-01-05', 'sergio@example.com', 'Sergio456', 'customer', 'avatar_images/avatar27.jpg', '2025-05-27 15:55:32'),
(28, 'Laura', 'Vega Molina', 688990011, '1989-09-17', 'laura@example.com', 'L@ura789', 'customer', 'avatar_images/avatar28.jpg', '2025-05-27 15:55:32'),
(29, 'Alberto', 'Castro León', 699001122, '1997-02-27', 'alberto@example.com', 'Alber!2024', 'customer', 'avatar_images/avatar29.jpg', '2025-05-27 15:55:32'),
(30, 'Eva', 'Romero Sáez', 600112233, '1996-05-11', 'eva@example.com', 'Eva@2025', 'customer', 'avatar_images/avatar30.jpg', '2025-05-27 15:55:32'),
(31, 'Carla', 'Rodrí­guez', 0, '1999-04-27', 'carla_kukirb@hotmail.com', '27-04-99Carla', 'customer', 'avatar_images/default_avatar.png', '2025-05-28 08:04:21'),
(32, 'Elias', 'Pedrero Guerrouj', 644418728, '2004-09-08', 'eliaspedrero4@gmail.com', '12345678Ep', 'customer', 'avatar_images/avatar_32_1748370341.jpeg', '2025-05-27 18:25:41'),
(39, 'Ramon', 'Valverde', 666756234, '1900-01-01', 'ramonin@gmail.com', '12345678Ep', 'customer', 'avatar_images/default_avatar.png', '2025-05-29 15:21:59'),
(40, 'Eliass', 'Pedrero', 123123123, '1900-01-01', 'elias@gmail.com', '12345678Ep', 'customer', 'avatar_images/avatar_40_1748532833.jpeg', '2025-05-29 15:33:53');

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
  MODIFY `id_appointment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT de la tabla `business`
--
ALTER TABLE `business`
  MODIFY `id_business` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id_schedule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT de la tabla `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

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
