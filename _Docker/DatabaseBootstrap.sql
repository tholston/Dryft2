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
  CONSTRAINT `driver_attributes_ibfk_1` FOREIGN KEY (`DRIVER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE NO ACTION
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
  CONSTRAINT `driver_payments_ibfk_1` FOREIGN KEY (`DRIVER_ID`) REFERENCES `driver_attributes` (`DRIVER_ID`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf16;


DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
  `LOCATION_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `latitude` decimal(9,6) NOT NULL,
  `longitude` decimal(9,6) NOT NULL,
  `nickname` varchar(60) NOT NULL,
  `line1` varchar(60) NOT NULL,
  `line2` varchar(60) NOT NULL,
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
  `driver` int(10) unsigned NOT NULL,
  `pickup` int(10) unsigned NOT NULL,
  `dropoff` int(10) unsigned NOT NULL,
  `departure` datetime NOT NULL,
  `arrival` datetime NOT NULL,
  `mileage` decimal(8,3) unsigned NOT NULL,
  PRIMARY KEY (`RIDE_ID`),
  KEY `client` (`client`),
  KEY `driver` (`driver`),
  KEY `pickup` (`pickup`),
  KEY `dropoff` (`dropoff`),
  CONSTRAINT `rides_ibfk_1` FOREIGN KEY (`client`) REFERENCES `users` (`USER_ID`) ON DELETE NO ACTION,
  CONSTRAINT `rides_ibfk_2` FOREIGN KEY (`driver`) REFERENCES `users` (`USER_ID`) ON DELETE NO ACTION,
  CONSTRAINT `rides_ibfk_3` FOREIGN KEY (`pickup`) REFERENCES `locations` (`LOCATION_ID`) ON DELETE NO ACTION,
  CONSTRAINT `rides_ibfk_4` FOREIGN KEY (`dropoff`) REFERENCES `locations` (`LOCATION_ID`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf16;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `USER_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `type` enum('Coordinator','Driver','Client') NOT NULL DEFAULT 'Client',
  `pw_hash` varchar(255) NOT NULL,
  `name_last` varchar(40) NOT NULL,
  `name_first` varchar(40) NOT NULL,
  `name_middle` varchar(40) NOT NULL,
  `home_address` int(10) unsigned NOT NULL,
  `mailing_address` int(10) unsigned NOT NULL,
  PRIMARY KEY (`USER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

INSERT INTO `users` (`USER_ID`, `username`, `type`, `pw_hash`, `name_last`, `name_first`, `name_middle`, `home_address`, `mailing_address`) VALUES
(1,	'dryfter',	'Coordinator',	'$2y$10$pO0E.UugyXzgeKHnhuEevu5wIhiXJBal/2DMJ2Z6TwIZRZbL3.A8m',	'Coordinator',	'DRyft',	'',	0,	0);


-- Begin inserting default testing entries into tables.

--Passwords for both driverTest and clientTest are "test123"
INSERT INTO `users` (`USER_ID`, `username`, `type`, `pw_hash`, `name_last`, `name_first`, `name_middle`, `home_address`, `mailing_address`) VALUES
(2,	'driverTest', 'Driver',	'$2y$10$egM0NpoIHRq/sLL41tF2auPOXQjryCZQv.jl4yMOT711LKPXF5FKG',	'Driver','DRyft',	'',	0,	0);
INSERT INTO `driver_attributes` (`DRIVER_ID`, `rate`, `is_available`) VALUES
(2, 2.5, False);

INSERT INTO `users` (`USER_ID`, `username`, `type`, `pw_hash`, `name_last`, `name_first`, `name_middle`, `home_address`, `mailing_address`) VALUES
(3, 'clientTest', 'Client', '$2y$10$egM0NpoIHRq/sLL41tF2auPOXQjryCZQv.jl4yMOT711LKPXF5FKG', 'Client', 'DRyft','',    0,    0);



--Multiple payments to driverTest
INSERT INTO `driver_payments` (`PAYMENT_ID`, `DRIVER_ID`, `mileage`, `rate`, `amount`, `status`) VALUES
(0, 2, 10, 15.50, 150, "Paid");
INSERT INTO `driver_payments` (`PAYMENT_ID`, `DRIVER_ID`, `mileage`, `rate`, `amount`, `status`) VALUES
(1, 2, 20, 15.50, 300, "Paid");
INSERT INTO `driver_payments` (`PAYMENT_ID`, `DRIVER_ID`, `mileage`, `rate`, `amount`, `status`) VALUES
(2, 2, 15, 15.50, 232.5, "Unpaid");

--1 to 2 rides for each payment listed above.
--All location ID's set to 0, feel free to change and add actual 'locations' table entries for each.
--Payment 0 (only ride)
INSERT INTO `rides` (`RIDE_ID`, `client`, `driver`, `pickup`, `dropoff`, `departure`,`arrival`,`mileage`) VALUES
(0, 3, 2, 0, 0, '1000-01-01 00:00:00', '1000-01-01 21:00:00', 10);
--Payment 1 (ride 1)
INSERT INTO `rides` (`RIDE_ID`, `client`, `driver`, `pickup`, `dropoff`, `departure`,`arrival`,`mileage`) VALUES
(1, 3, 2, 0, 0, '1000-01-01 00:00:00', '1000-01-01 21:00:00', 10);
--Payment 1 (ride 2)
INSERT INTO `rides` (`RIDE_ID`, `client`, `driver`, `pickup`, `dropoff`, `departure`,`arrival`,`mileage`) VALUES
(2, 3, 2, 0, 0, '1000-01-01 00:00:00', '1000-01-01 21:00:00', 10);
--Payment 2 (ride 1)
INSERT INTO `rides` (`RIDE_ID`, `client`, `driver`, `pickup`, `dropoff`, `departure`,`arrival`,`mileage`) VALUES
(3, 3, 2, 0, 0, '1000-01-01 00:00:00', '1000-01-01 21:00:00', 10);
--Payment 2 (ride 2)
INSERT INTO `rides` (`RIDE_ID`, `client`, `driver`, `pickup`, `dropoff`, `departure`,`arrival`,`mileage`) VALUES
(4, 3, 2, 0, 0, '1000-01-01 00:00:00', '1000-01-01 21:00:00', 5);

-- Entries into payment_rides to connect Example payments and rides.
INSERT INTO `payment_rides` (`PAYMENT_ID`, `RIDE_ID`) VALUES
(0, 0);
INSERT INTO `payment_rides` (`PAYMENT_ID`, `RIDE_ID`) VALUES
(1, 1);
INSERT INTO `payment_rides` (`PAYMENT_ID`, `RIDE_ID`) VALUES
(1, 2);
INSERT INTO `payment_rides` (`PAYMENT_ID`, `RIDE_ID`) VALUES
(2, 3);
INSERT INTO `payment_rides` (`PAYMENT_ID`, `RIDE_ID`) VALUES
(2, 4);

