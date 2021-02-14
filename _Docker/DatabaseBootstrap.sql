SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

USE `dryft`;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `USER_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  PRIMARY KEY (`USER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`USER_ID`, `username`) VALUES
(1,	'dryfter');
