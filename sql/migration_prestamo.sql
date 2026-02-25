-- =====================================================
-- Migration: Prestamo CRUD Overhaul
-- Run this script on your 'biblioteca' database
-- =====================================================

-- 1. Drop dependent tables first (old schema)
DROP TABLE IF EXISTS `Renueva`;
DROP TABLE IF EXISTS `Cancela`;
DROP TABLE IF EXISTS `Cancelacion`;
DROP TABLE IF EXISTS `Renovacion`;
DROP TABLE IF EXISTS `Prestamos`;

-- 2. Recreate Prestamos with proper columns
CREATE TABLE `Prestamos` (
  `N_de_control` int(11) NOT NULL AUTO_INCREMENT,
  `F_prestamo` date NOT NULL,
  `F_devolucion` date NOT NULL,
  `N_de_Ejemplar` int(11) NOT NULL,
  `Año_Obras` int(11) NOT NULL,
  `Cutter_Autores` varchar(20) NOT NULL,
  `Codigo_CDD` int(11) NOT NULL,
  `DNI_Prestatario` int(11) NOT NULL,
  `Username_Prestamista` varchar(20) NOT NULL,
  `T_prestamo` varchar(20) NOT NULL,
  PRIMARY KEY (`N_de_control`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. Renovacion (log table)
CREATE TABLE `Renovacion` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(20) NOT NULL,
  `F_renovacion` date NOT NULL,
  `Prestamo` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. Cancelacion (log table)
CREATE TABLE `Cancelacion` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(20) NOT NULL,
  `F_cancelacion` date NOT NULL,
  `Prestamo` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
