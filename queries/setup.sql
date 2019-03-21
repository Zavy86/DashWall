--
-- Setup Dash|Wall
--
-- Version 1.0.0
--

-- --------------------------------------------------------

SET TIME_ZONE = "+00:00";
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------

--
-- Table structure for table `dashwall__datasources`
--

CREATE TABLE IF NOT EXISTS `dashwall__datasources` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `connector` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hostname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `database` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tns` text COLLATE utf8_unicode_ci,
  `queries` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dashwall__schedules`
--

CREATE TABLE IF NOT EXISTS `dashwall__schedules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `minutes` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hours` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `plugin` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parameters` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------


--
-- Table structure for table `dashwall__dashboards`
--

CREATE TABLE IF NOT EXISTS `dashwall__dashboards` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `orientation` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `theme` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'light, dark',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dashwall__dashboards`
--

INSERT INTO `dashwall__dashboards` (`id`, `code`, `title`, `orientation`, `theme`) VALUES
(1, 'default', 'Dashboard', 'landscape', 'dark');

-- --------------------------------------------------------

--
-- Table structure for table `dashwall__tiles`
--

CREATE TABLE IF NOT EXISTS `dashwall__tiles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fkDashboard` int(11) unsigned NOT NULL,
  `order` tinyint(3) unsigned NOT NULL,
  `width` tinyint(1) unsigned NOT NULL,
  `height` tinyint(1) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `plugin` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parameters` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fkDashboard` (`fkDashboard`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dashwall__tiles`
--

INSERT INTO `dashwall__tiles` (`id`, `fkDashboard`, `order`, `width`, `height`, `title`, `plugin`, `parameters`) VALUES
(1, 1, 1, 1, 1, 'TIMESTAMP', 'dwp_datetime', '{"refresh":"1000","format":"H:i:s","color":"#ffffff"}');

-- --------------------------------------------------------

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dashwall__tiles`
--
ALTER TABLE `dashwall__tiles`
  ADD CONSTRAINT `dashwall__tiles_ibfk_1` FOREIGN KEY (`fkDashboard`) REFERENCES `dashwall__dashboards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
