SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

USE `dryft`;

DROP TABLE IF EXISTS `driver_attributes`;
CREATE TABLE `driver_attributes` (
  `DRIVER_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rate` decimal(5,2) unsigned NOT NULL DEFAULT 0.00,
  `is_available` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`DRIVER_ID`),
  CONSTRAINT `driver_attributes_ibfk_2` FOREIGN KEY (`DRIVER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf16;


DROP TABLE IF EXISTS `driver_payments`;
CREATE TABLE `driver_payments` (
  `PAYMENT_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `DRIVER_ID` int(10) unsigned NOT NULL,
  `mileage` decimal(10,3) unsigned NOT NULL,
  `rate` decimal(5,2) unsigned NOT NULL,
  `amount` decimal(7,2) unsigned NOT NULL,
  `status` enum('Paid','Unpaid') NOT NULL,
  PRIMARY KEY (`PAYMENT_ID`),
  KEY `DRIVER_ID` (`DRIVER_ID`),
  CONSTRAINT `driver_payments_ibfk_1` FOREIGN KEY (`DRIVER_ID`) REFERENCES `driver_attributes` (`DRIVER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;


DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
  `LOCATION_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `nickname` varchar(60) NOT NULL,
  `line1` varchar(60) NOT NULL,
  `line2` varchar(60) DEFAULT NULL,
  `city` varchar(60) NOT NULL,
  `state` varchar(2) NOT NULL,
  `zip` varchar(10) NOT NULL,
  PRIMARY KEY (`LOCATION_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;


DROP TABLE IF EXISTS `payment_rides`;
CREATE TABLE `payment_rides` (
  `PAYMENT_ID` int(10) unsigned NOT NULL,
  `RIDE_ID` int(10) unsigned NOT NULL,
  KEY `PAYMENT_ID` (`PAYMENT_ID`),
  KEY `RIDE_ID` (`RIDE_ID`),
  CONSTRAINT `payment_rides_ibfk_1` FOREIGN KEY (`PAYMENT_ID`) REFERENCES `driver_payments` (`PAYMENT_ID`) ON DELETE CASCADE,
  CONSTRAINT `payment_rides_ibfk_2` FOREIGN KEY (`RIDE_ID`) REFERENCES `rides` (`RIDE_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf16;


DROP TABLE IF EXISTS `rides`;
CREATE TABLE `rides` (
  `RIDE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client` int(10) unsigned NOT NULL,
  `driver` int(10) unsigned DEFAULT NULL,
  `pickup` int(10) unsigned NOT NULL,
  `dropoff` int(10) unsigned NOT NULL,
  `departure` datetime NOT NULL,
  `arrival` datetime NOT NULL,
  `mileage` decimal(8,3) unsigned NOT NULL,
  PRIMARY KEY (`RIDE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `USER_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `type` enum('Coordinator','Driver','Client') NOT NULL DEFAULT 'Client',
  `pw_hash` varchar(255) NOT NULL,
  `name_last` varchar(40) DEFAULT NULL,
  `name_first` varchar(40) DEFAULT NULL,
  `name_middle` varchar(40) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `home_address` int(10) unsigned DEFAULT NULL,
  `mailing_address` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`USER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

INSERT INTO `users` (`USER_ID`, `username`, `type`, `pw_hash`, `name_last`, `name_first`, `name_middle`, `email`, `phone`, `home_address`, `mailing_address`) VALUES
(1,	'dryfter',	'Coordinator',	'$2y$10$pO0E.UugyXzgeKHnhuEevu5wIhiXJBal/2DMJ2Z6TwIZRZbL3.A8m',	'Coordinator',	'DRyft',	'',	'',	NULL,	0,	0);
