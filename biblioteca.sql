-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 14-02-2026 a las 23:31:31
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
-- Base de datos: `biblioteca`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Admin`
--

CREATE TABLE `Admin` (
  `DNI_Usuario` int(11) NOT NULL,
  `Username` varchar(20) DEFAULT NULL,
  `Password` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Admin`
--

INSERT INTO `Admin` (`DNI_Usuario`, `Username`, `Password`) VALUES
(30728912, 'admin', 'minda');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Autores`
--

CREATE TABLE `Autores` (
  `Cutter` varchar(20) NOT NULL,
  `Nombre` varchar(20) DEFAULT NULL,
  `Nacionalidad` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Cancela`
--

CREATE TABLE `Cancela` (
  `N_control` int(11) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `F_Dev` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Catg_de_User_SA`
--

CREATE TABLE `Catg_de_User_SA` (
  `ID` int(11) NOT NULL,
  `Tipo` varchar(20) DEFAULT NULL,
  `N_de_dias` int(11) DEFAULT NULL,
  `N_de_ejemplares` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Catg_de_User_SA`
--

INSERT INTO `Catg_de_User_SA` (`ID`, `Tipo`, `N_de_dias`, `N_de_ejemplares`) VALUES
(1, 'Estudiante', 12, 3),
(2, 'Docente', 18, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CDD`
--

CREATE TABLE `CDD` (
  `Codigo` int(11) NOT NULL,
  `Descripcion` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CDD_Tiene`
--

CREATE TABLE `CDD_Tiene` (
  `Codigo` int(11) NOT NULL,
  `Codigo_Padre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Editorial`
--

CREATE TABLE `Editorial` (
  `ID` int(11) NOT NULL,
  `Nombre` varchar(20) DEFAULT NULL,
  `SedeMatriz` varchar(20) DEFAULT NULL,
  `Email` varchar(20) DEFAULT NULL,
  `Telefono` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Ejemplares`
--

CREATE TABLE `Ejemplares` (
  `N_de_Ejemplar` int(11) NOT NULL,
  `Año_Obras` int(11) NOT NULL,
  `Cutter_Autores` varchar(20) NOT NULL,
  `Codigo_CDD` int(11) NOT NULL,
  `ID_Estado` int(11) DEFAULT NULL,
  `ID_Soporte` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Estado`
--

CREATE TABLE `Estado` (
  `ID` int(11) NOT NULL,
  `Descripcion` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Obras`
--

CREATE TABLE `Obras` (
  `Año` int(11) NOT NULL,
  `Cutter_Autor` varchar(20) NOT NULL,
  `Codigo_CDD` int(11) NOT NULL,
  `Titulo` varchar(20) DEFAULT NULL,
  `Edicion` varchar(20) DEFAULT NULL,
  `Otros_Autores` varchar(20) DEFAULT NULL,
  `ISBN` int(11) DEFAULT NULL,
  `ISNN` int(11) DEFAULT NULL,
  `Descripcion` varchar(20) DEFAULT NULL,
  `L_Publicacion` varchar(20) DEFAULT NULL,
  `N_Dep_Legal` varchar(20) DEFAULT NULL,
  `ID_Editorial` int(11) DEFAULT NULL,
  `ID_T_Obras` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Prestamos`
--

CREATE TABLE `Prestamos` (
  `N_de_control` int(11) NOT NULL,
  `Fecha_de_prestamo` varchar(20) DEFAULT NULL,
  `Año_del_prestamo` int(11) DEFAULT NULL,
  `Devolucion_de_prestamo` varchar(20) DEFAULT NULL,
  `Tipo_de_prestamo` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Renueva`
--

CREATE TABLE `Renueva` (
  `N_control` int(11) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `F_Ren` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_noticias`
--

CREATE TABLE `tbl_noticias` (
  `id` int(11) NOT NULL,
  `titular` varchar(200) NOT NULL,
  `url_imagen` varchar(500) NOT NULL,
  `url_noticia` varchar(500) NOT NULL,
  `fecha` date NOT NULL,
  `visible` varchar(2) NOT NULL,
  `categoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tbl_noticias`
--

INSERT INTO `tbl_noticias` (`id`, `titular`, `url_imagen`, `url_noticia`, `fecha`, `visible`, `categoria`) VALUES
(2, '3 años después de su lanzamiento, puedes comprar el Samsung Galaxy S8 a precio de Xiaomi, ¡70% de descuento!', 'https://andro4all.com/files/2019/09/Samsung-Galaxy-S8-700x500.jpg', 'https://andro4all.com/ofertas/oferta-descuento-samsung-galaxy-s8', '2020-05-08', 'si', 'Ciencia y Tecnología'),
(3, 'Alta demanda de Switch y superventas de ‘Animal Crossing’ impulsan a Nintendo', 'https://cdn.forbes.com.mx/2017/03/bloggif_58c8715cc52a8-768x432.jpeg', 'https://www.forbes.com.mx/negocios-switch-animal-crossing-ventas-nintendo/', '2020-05-08', 'si', 'Ciencia y Tecnología'),
(4, 'WhatsApp y Netflix se unen: ahora podrás ver tus series y películas desde la app de mensajería', 'https://d33nllpiqx4xq6.cloudfront.net/files/Publicacion/1195300/Foto/note_picture/net-301960.jpg', 'https://www.noroeste.com.mx/publicaciones/view/whatsapp-y-netflix-se-unen-ahora-podras-ver-tus-series-y-peliculas-desde-la-app-de-mensajeria-1195300', '2020-05-08', 'si', 'Ciencia y Tecnología'),
(5, 'El plan de China y Rusia para construir una estación espacial en la Luna', 'https://ichef.bbci.co.uk/news/800/cpsprodpb/E6B5/production/_117516095_gettyimages-1031182150.jpg', 'https://www.bbc.com/mundo/noticias-56346295', '2021-03-11', 'si', 'Ciencia y Tecnología');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `T_Obras`
--

CREATE TABLE `T_Obras` (
  `ID` int(11) NOT NULL,
  `Descripcion` varchar(20) DEFAULT NULL,
  `Prestar` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `T_soporte`
--

CREATE TABLE `T_soporte` (
  `ID` int(11) NOT NULL,
  `Descripcion` varchar(20) DEFAULT NULL,
  `Prestable` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuario`
--

CREATE TABLE `Usuario` (
  `DNI` int(11) NOT NULL,
  `Nombre` varchar(20) DEFAULT NULL,
  `Apellido` varchar(20) DEFAULT NULL,
  `Edad` int(11) DEFAULT NULL,
  `Correo` varchar(255) DEFAULT NULL,
  `Num_Telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Usuario`
--

INSERT INTO `Usuario` (`DNI`, `Nombre`, `Apellido`, `Edad`, `Correo`, `Num_Telefono`) VALUES
(30728912, 'Jeanniel', 'Gonzalez', 20, 'correo@gmail.com', '04248630171'),
(31668011, 'Sara', 'Hernandez', 19, 'correo3@gmail.com', '04248630172');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuario_sin_acceso`
--

CREATE TABLE `Usuario_sin_acceso` (
  `DNI_Usuario` int(11) NOT NULL,
  `Carrera_Departamento` varchar(20) DEFAULT NULL,
  `ID_Catg_de_User_SA` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Usuario_sin_acceso`
--

INSERT INTO `Usuario_sin_acceso` (`DNI_Usuario`, `Carrera_Departamento`, `ID_Catg_de_User_SA`) VALUES
(30728912, 'Informática', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`DNI_Usuario`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indices de la tabla `Autores`
--
ALTER TABLE `Autores`
  ADD PRIMARY KEY (`Cutter`);

--
-- Indices de la tabla `Cancela`
--
ALTER TABLE `Cancela`
  ADD PRIMARY KEY (`N_control`,`Username`),
  ADD KEY `Username` (`Username`);

--
-- Indices de la tabla `Catg_de_User_SA`
--
ALTER TABLE `Catg_de_User_SA`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `CDD`
--
ALTER TABLE `CDD`
  ADD PRIMARY KEY (`Codigo`);

--
-- Indices de la tabla `CDD_Tiene`
--
ALTER TABLE `CDD_Tiene`
  ADD PRIMARY KEY (`Codigo`,`Codigo_Padre`);

--
-- Indices de la tabla `Editorial`
--
ALTER TABLE `Editorial`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `Ejemplares`
--
ALTER TABLE `Ejemplares`
  ADD PRIMARY KEY (`N_de_Ejemplar`,`Año_Obras`,`Cutter_Autores`,`Codigo_CDD`),
  ADD KEY `Año_Obras` (`Año_Obras`,`Cutter_Autores`,`Codigo_CDD`),
  ADD KEY `ID_Estado` (`ID_Estado`),
  ADD KEY `ID_Soporte` (`ID_Soporte`);

--
-- Indices de la tabla `Estado`
--
ALTER TABLE `Estado`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `Obras`
--
ALTER TABLE `Obras`
  ADD PRIMARY KEY (`Año`,`Cutter_Autor`,`Codigo_CDD`),
  ADD KEY `Cutter_Autor` (`Cutter_Autor`),
  ADD KEY `Codigo_CDD` (`Codigo_CDD`),
  ADD KEY `ID_Editorial` (`ID_Editorial`),
  ADD KEY `ID_T_Obras` (`ID_T_Obras`);

--
-- Indices de la tabla `Prestamos`
--
ALTER TABLE `Prestamos`
  ADD PRIMARY KEY (`N_de_control`);

--
-- Indices de la tabla `Renueva`
--
ALTER TABLE `Renueva`
  ADD PRIMARY KEY (`N_control`,`Username`),
  ADD KEY `Username` (`Username`);

--
-- Indices de la tabla `tbl_noticias`
--
ALTER TABLE `tbl_noticias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `T_Obras`
--
ALTER TABLE `T_Obras`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `T_soporte`
--
ALTER TABLE `T_soporte`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `Usuario`
--
ALTER TABLE `Usuario`
  ADD PRIMARY KEY (`DNI`);

--
-- Indices de la tabla `Usuario_sin_acceso`
--
ALTER TABLE `Usuario_sin_acceso`
  ADD PRIMARY KEY (`DNI_Usuario`),
  ADD KEY `ID_Catg_de_User_SA` (`ID_Catg_de_User_SA`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Admin`
--
ALTER TABLE `Admin`
  ADD CONSTRAINT `Admin_ibfk_1` FOREIGN KEY (`DNI_Usuario`) REFERENCES `Usuario` (`DNI`);

--
-- Filtros para la tabla `Cancela`
--
ALTER TABLE `Cancela`
  ADD CONSTRAINT `Cancela_ibfk_1` FOREIGN KEY (`N_control`) REFERENCES `Prestamos` (`N_de_control`),
  ADD CONSTRAINT `Cancela_ibfk_2` FOREIGN KEY (`Username`) REFERENCES `Admin` (`Username`);

--
-- Filtros para la tabla `CDD_Tiene`
--
ALTER TABLE `CDD_Tiene`
  ADD CONSTRAINT `CDD_Tiene_ibfk_1` FOREIGN KEY (`Codigo`) REFERENCES `CDD` (`Codigo`);

--
-- Filtros para la tabla `Ejemplares`
--
ALTER TABLE `Ejemplares`
  ADD CONSTRAINT `Ejemplares_ibfk_1` FOREIGN KEY (`Año_Obras`,`Cutter_Autores`,`Codigo_CDD`) REFERENCES `Obras` (`Año`, `Cutter_Autor`, `Codigo_CDD`),
  ADD CONSTRAINT `Ejemplares_ibfk_2` FOREIGN KEY (`ID_Estado`) REFERENCES `Estado` (`ID`),
  ADD CONSTRAINT `Ejemplares_ibfk_3` FOREIGN KEY (`ID_Soporte`) REFERENCES `T_soporte` (`ID`);

--
-- Filtros para la tabla `Obras`
--
ALTER TABLE `Obras`
  ADD CONSTRAINT `Obras_ibfk_1` FOREIGN KEY (`Cutter_Autor`) REFERENCES `Autores` (`Cutter`),
  ADD CONSTRAINT `Obras_ibfk_2` FOREIGN KEY (`Codigo_CDD`) REFERENCES `CDD` (`Codigo`),
  ADD CONSTRAINT `Obras_ibfk_3` FOREIGN KEY (`ID_Editorial`) REFERENCES `Editorial` (`ID`),
  ADD CONSTRAINT `Obras_ibfk_4` FOREIGN KEY (`ID_T_Obras`) REFERENCES `T_Obras` (`ID`);

--
-- Filtros para la tabla `Renueva`
--
ALTER TABLE `Renueva`
  ADD CONSTRAINT `Renueva_ibfk_1` FOREIGN KEY (`N_control`) REFERENCES `Prestamos` (`N_de_control`),
  ADD CONSTRAINT `Renueva_ibfk_2` FOREIGN KEY (`Username`) REFERENCES `Admin` (`Username`);

--
-- Filtros para la tabla `Usuario_sin_acceso`
--
ALTER TABLE `Usuario_sin_acceso`
  ADD CONSTRAINT `Usuario_sin_acceso_ibfk_1` FOREIGN KEY (`DNI_Usuario`) REFERENCES `Usuario` (`DNI`),
  ADD CONSTRAINT `Usuario_sin_acceso_ibfk_2` FOREIGN KEY (`ID_Catg_de_User_SA`) REFERENCES `Catg_de_User_SA` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
