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
-- Table structure for table `framework__settings`
--

CREATE TABLE IF NOT EXISTS `dashwall__settings` (
  `setting` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`setting`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `framework__settings`
--

INSERT INTO `dashwall__settings` (`setting`, `value`) VALUES
('title', 'Dash|Wall'),
('owner', 'Owner name'),
('theme', 'dark');

-- --------------------------------------------------------

--
-- Table structure for table `dashwall__tiles`
--

CREATE TABLE IF NOT EXISTS `dashwall__tiles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order` tinyint(3) unsigned NOT NULL,
  `width` tinyint(1) unsigned NOT NULL,
  `height` tinyint(1) unsigned NOT NULL,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `plugin` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parameters` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dashwall__tiles`
--

INSERT INTO `dashwall__tiles` (`id`, `order`, `width`, `height`, `title`, `plugin`, `parameters`) VALUES
(1, 1, 1, 1, 'TIMESTAMP', 'dwp_datetime', '{"refresh":"1000","format":"H:i:s"}');

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
