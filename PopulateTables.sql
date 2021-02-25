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
