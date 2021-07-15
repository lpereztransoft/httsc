-- phpMyAdmin SQL Dump
-- version 4.0.10.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 16-06-2021 a las 12:32:39
-- Versión del servidor: 5.5.68-MariaDB
-- Versión de PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `transoft_hoteles`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hotcarhab`
--

CREATE TABLE IF NOT EXISTS `hotcarhab` (
  `idcarhab` int(11) NOT NULL AUTO_INCREMENT,
  `carhabdsc` varchar(100) NOT NULL,
  `carhabfecalta` date NOT NULL,
  PRIMARY KEY (`idcarhab`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hotcarhotel`
--

CREATE TABLE IF NOT EXISTS `hotcarhotel` (
  `idcarhotel` int(11) NOT NULL AUTO_INCREMENT,
  `carhotdsc` varchar(100) NOT NULL,
  `carhotfecalta` date NOT NULL,
  PRIMARY KEY (`idcarhotel`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hothabitaciones`
--

CREATE TABLE IF NOT EXISTS `hothabitaciones` (
  `idhabitacion` int(11) NOT NULL AUTO_INCREMENT,
  `idhothotel` int(11) NOT NULL,
  `habmaxmayor` int(2) NOT NULL,
  `habmaxmenor` int(2) NOT NULL,
  `habtarifamayor` double NOT NULL,
  `habtarifamenor` double NOT NULL,
  `habdsc` longtext,
  `habfecdesde` date DEFAULT NULL,
  `habfechasta` date DEFAULT NULL,
  `habestado` int(1) NOT NULL,
  `habfecalta` date NOT NULL,
  `idtiphab` int(11) NOT NULL,
  `habestadodisp` int(1) DEFAULT NULL,
  PRIMARY KEY (`idhabitacion`),
  KEY `fk_hothabitaciones_hothoteles1_idx` (`idhothotel`),
  KEY `fk_hothabitaciones_hottiphab1_idx` (`idtiphab`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=349 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hothabreservas`
--

CREATE TABLE IF NOT EXISTS `hothabreservas` (
  `idreserva` int(11) NOT NULL AUTO_INCREMENT,
  `idhabitacion` int(11) NOT NULL,
  `revfechaingreso` date DEFAULT NULL,
  `revfechasalida` date DEFAULT NULL,
  `revcodigoreserva` varchar(5) DEFAULT NULL,
  `revmontoreserva` varchar(45) DEFAULT NULL,
  `revmontocomision` varchar(45) DEFAULT NULL,
  `revagencygcp` varchar(45) DEFAULT NULL,
  `revagencyuserid` varchar(45) DEFAULT NULL,
  `revestado` int(1) DEFAULT NULL,
  PRIMARY KEY (`idreserva`),
  KEY `fk_hothabreservas_hothabitaciones1_idx` (`idhabitacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=131 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hothab_hotcarhab`
--

CREATE TABLE IF NOT EXISTS `hothab_hotcarhab` (
  `hotidcarhab` int(11) NOT NULL,
  `hotidhabitacion` int(11) NOT NULL,
  KEY `fk_hothab_hotcarhab_hotcarhab1_idx` (`hotidcarhab`),
  KEY `fk_hothab_hotcarhab_hothabitaciones1_idx` (`hotidhabitacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hothab_hotserhab`
--

CREATE TABLE IF NOT EXISTS `hothab_hotserhab` (
  `hotidserhab` int(11) NOT NULL,
  `hotidhabitacion` int(11) NOT NULL,
  PRIMARY KEY (`hotidserhab`,`hotidhabitacion`),
  KEY `fk_hothab_hotserhab_hotserhab1_idx` (`hotidserhab`),
  KEY `fk_hothab_hotserhab_hothabitaciones1_idx` (`hotidhabitacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hothoteles`
--

CREATE TABLE IF NOT EXISTS `hothoteles` (
  `idHotel` int(11) NOT NULL AUTO_INCREMENT,
  `hotnombre` varchar(100) NOT NULL,
  `hotdsc` longtext,
  `hotdireccion` varchar(150) DEFAULT NULL,
  `hotlocalidad` varchar(3) NOT NULL,
  `hotcoordenadas` varchar(50) DEFAULT NULL,
  `hottelefono` varchar(20) NOT NULL,
  `hottelefono2` varchar(20) DEFAULT NULL,
  `hottelefono3` varchar(20) DEFAULT NULL,
  `hotcategoria` tinyint(1) NOT NULL,
  `hotestado` tinyint(1) NOT NULL,
  `hotpolcancel` tinyint(1) NOT NULL,
  `hotfecalta` date NOT NULL,
  `hotpublica` tinyint(1) NOT NULL,
  `hottipocta` char(2) DEFAULT NULL,
  `hotnrocuenta` varchar(25) DEFAULT NULL,
  `hotabonado` varchar(250) DEFAULT NULL,
  `hottipdoc` char(3) DEFAULT NULL,
  `hotdocumento` varchar(25) DEFAULT NULL,
  `hotcomplemento` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`idHotel`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hothotel_hotcarhot`
--

CREATE TABLE IF NOT EXISTS `hothotel_hotcarhot` (
  `hotidHotel` int(11) NOT NULL,
  `hotcarhotel` int(11) NOT NULL,
  KEY `fk_hothotel_hotcarhot_hothoteles1_idx` (`hotidHotel`),
  KEY `fk_hothotel_hotcarhot_hotcarhotel1_idx` (`hotcarhotel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hothuesped`
--

CREATE TABLE IF NOT EXISTS `hothuesped` (
  `idhuesped` int(11) NOT NULL AUTO_INCREMENT,
  `idreserva` int(11) NOT NULL,
  `huenombre` varchar(200) DEFAULT NULL,
  `hueci` varchar(45) DEFAULT NULL,
  `huetelefono` varchar(45) DEFAULT NULL,
  `hueemail` varchar(75) DEFAULT NULL,
  `huefecnacimento` date DEFAULT NULL,
  PRIMARY KEY (`idhuesped`),
  KEY `fk_hothuesped_hothabreservas1_idx` (`idreserva`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=120 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hotimagenes`
--

CREATE TABLE IF NOT EXISTS `hotimagenes` (
  `idimagen` int(11) NOT NULL AUTO_INCREMENT,
  `hotidhotel` int(11) NOT NULL,
  `imgdsc` varchar(100) DEFAULT NULL,
  `imgimagen` varchar(100) NOT NULL,
  `imgfecalta` date NOT NULL,
  PRIMARY KEY (`idimagen`),
  KEY `fk_hotimagenes_hothoteles1_idx` (`hotidhotel`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=151 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hotrecursos`
--

CREATE TABLE IF NOT EXISTS `hotrecursos` (
  `idrec` int(11) NOT NULL AUTO_INCREMENT,
  `recdsc` varchar(100) NOT NULL,
  `recfecalta` date NOT NULL,
  PRIMARY KEY (`idrec`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hotroles`
--

CREATE TABLE IF NOT EXISTS `hotroles` (
  `idrol` int(11) NOT NULL AUTO_INCREMENT,
  `roldsc` varchar(100) NOT NULL,
  `rolfecalta` date NOT NULL,
  PRIMARY KEY (`idrol`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hotrol_hotrec`
--

CREATE TABLE IF NOT EXISTS `hotrol_hotrec` (
  `listar` tinyint(1) NOT NULL,
  `insertar` tinyint(1) NOT NULL,
  `modificar` tinyint(1) NOT NULL,
  `eliminar` tinyint(1) NOT NULL,
  `cambiarEstado` tinyint(1) NOT NULL,
  `checkin` tinyint(1) DEFAULT NULL,
  `checkout` tinyint(1) DEFAULT NULL,
  `noshow` tinyint(1) DEFAULT NULL,
  `anular` tinyint(1) DEFAULT NULL,
  `hotidrol` int(11) NOT NULL,
  `hotidrec` int(11) NOT NULL,
  KEY `fk_hotrol_hotrec_hotroles1_idx` (`hotidrol`),
  KEY `fk_hotrol_hotrec_hotrecursos1_idx` (`hotidrec`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hotserhab`
--

CREATE TABLE IF NOT EXISTS `hotserhab` (
  `idserhab` int(11) NOT NULL AUTO_INCREMENT,
  `serhabdsc` varchar(100) NOT NULL,
  `serhabfecalta` date NOT NULL,
  PRIMARY KEY (`idserhab`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hottiphab`
--

CREATE TABLE IF NOT EXISTS `hottiphab` (
  `idtiphab` int(11) NOT NULL AUTO_INCREMENT,
  `tipdsc` varchar(100) NOT NULL,
  `tipfecalta` date NOT NULL,
  PRIMARY KEY (`idtiphab`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hotusuario`
--

CREATE TABLE IF NOT EXISTS `hotusuario` (
  `idusu` int(11) NOT NULL AUTO_INCREMENT,
  `usunombre` varchar(100) NOT NULL,
  `usuemail` varchar(75) NOT NULL,
  `usupassword` varchar(255) NOT NULL,
  `usuestado` tinyint(1) NOT NULL,
  `usuci` varchar(45) DEFAULT NULL,
  `usutelefono` varchar(45) DEFAULT NULL,
  `usufechanac` date DEFAULT NULL,
  `usufechaalta` date NOT NULL,
  `usufecmod` datetime NOT NULL,
  PRIMARY KEY (`idusu`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hotusu_hothot`
--

CREATE TABLE IF NOT EXISTS `hotusu_hothot` (
  `hotidusu` int(11) NOT NULL,
  `hotidhotel` int(11) NOT NULL,
  KEY `fk_hotusu_hothot_hotusuario1_idx` (`hotidusu`),
  KEY `fk_hotusu_hothot_hothoteles1_idx` (`hotidhotel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hotusu_hotrol`
--

CREATE TABLE IF NOT EXISTS `hotusu_hotrol` (
  `hotidusu` int(11) NOT NULL,
  `hotidrol` int(11) NOT NULL,
  KEY `fk_hotusu_hotrol_hotusu1_idx` (`hotidusu`),
  KEY `fk_hotusu_hotrol_hotrol1_idx` (`hotidrol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `hothabitaciones`
--
ALTER TABLE `hothabitaciones`
  ADD CONSTRAINT `fk_hothabitaciones_hothoteles1` FOREIGN KEY (`idhothotel`) REFERENCES `hothoteles` (`idHotel`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hothabitaciones_hottiphab1` FOREIGN KEY (`idtiphab`) REFERENCES `hottiphab` (`idtiphab`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `hothabreservas`
--
ALTER TABLE `hothabreservas`
  ADD CONSTRAINT `fk_hothabreservas_hothabitaciones1` FOREIGN KEY (`idhabitacion`) REFERENCES `hothabitaciones` (`idhabitacion`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `hothab_hotcarhab`
--
ALTER TABLE `hothab_hotcarhab`
  ADD CONSTRAINT `fk_hothab_hotcarhab_hotcarhab1` FOREIGN KEY (`hotidcarhab`) REFERENCES `hotcarhab` (`idcarhab`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hothab_hotcarhab_hothabitaciones1` FOREIGN KEY (`hotidhabitacion`) REFERENCES `hothabitaciones` (`idhabitacion`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `hothab_hotserhab`
--
ALTER TABLE `hothab_hotserhab`
  ADD CONSTRAINT `fk_hothab_hotserhab_hothabitaciones1` FOREIGN KEY (`hotidhabitacion`) REFERENCES `hothabitaciones` (`idhabitacion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hothab_hotserhab_hotserhab1` FOREIGN KEY (`hotidserhab`) REFERENCES `hotserhab` (`idserhab`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `hothotel_hotcarhot`
--
ALTER TABLE `hothotel_hotcarhot`
  ADD CONSTRAINT `fk_hothotel_hotcarhot_hotcarhotel1` FOREIGN KEY (`hotcarhotel`) REFERENCES `hotcarhotel` (`idcarhotel`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hothotel_hotcarhot_hothoteles1` FOREIGN KEY (`hotidHotel`) REFERENCES `hothoteles` (`idHotel`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `hothuesped`
--
ALTER TABLE `hothuesped`
  ADD CONSTRAINT `fk_hothuesped_hothabreservas1` FOREIGN KEY (`idreserva`) REFERENCES `hothabreservas` (`idreserva`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `hotimagenes`
--
ALTER TABLE `hotimagenes`
  ADD CONSTRAINT `fk_hotimagenes_hothoteles1` FOREIGN KEY (`hotidhotel`) REFERENCES `hothoteles` (`idHotel`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `hotrol_hotrec`
--
ALTER TABLE `hotrol_hotrec`
  ADD CONSTRAINT `fk_hotrol_hotrec_hotrecursos1` FOREIGN KEY (`hotidrec`) REFERENCES `hotrecursos` (`idrec`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hotrol_hotrec_hotroles1` FOREIGN KEY (`hotidrol`) REFERENCES `hotroles` (`idrol`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `hotusu_hothot`
--
ALTER TABLE `hotusu_hothot`
  ADD CONSTRAINT `fk_hotusu_hothot_hothoteles1` FOREIGN KEY (`hotidhotel`) REFERENCES `hothoteles` (`idHotel`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hotusu_hothot_hotusuario1` FOREIGN KEY (`hotidusu`) REFERENCES `hotusuario` (`idusu`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `hotusu_hotrol`
--
ALTER TABLE `hotusu_hotrol`
  ADD CONSTRAINT `fk_hotusu_hotrol_hotrol1` FOREIGN KEY (`hotidrol`) REFERENCES `hotroles` (`idrol`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hotusu_hotrol_hotusu1` FOREIGN KEY (`hotidusu`) REFERENCES `hotusuario` (`idusu`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
