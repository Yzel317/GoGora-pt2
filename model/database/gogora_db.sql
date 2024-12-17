-- MySQL dump with updated order for foreign key dependencies

-- Table structure for table `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(10) NOT NULL,
  `firstname` varchar(10) NOT NULL,
  `lastname` varchar(10) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(17) NOT NULL,
  `role` enum('Student','Faculty','Employee') NOT NULL,
  `user_type` enum('Regular','Priority') NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `blacklist`
DROP TABLE IF EXISTS `blacklist`;
CREATE TABLE `blacklist` (
  `blacklist_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(10) NOT NULL,
  `blacklist_date` datetime NOT NULL,
  `blacklist_status` enum('Blacklisted','Reinstated') NOT NULL,
  `reason` varchar(45) NOT NULL,
  PRIMARY KEY (`blacklist_id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  CONSTRAINT `fk_blacklist_username` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `rides`
DROP TABLE IF EXISTS `rides`;
CREATE TABLE `rides` (
  `ride_id` int NOT NULL AUTO_INCREMENT,
  `plate_number` varchar(6) NOT NULL,
  `route` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  `seats_available` int NOT NULL,
  `ride_type` enum('Jeepney','Service') NOT NULL,
  `departure` datetime NOT NULL,
  `capacity` int NOT NULL,
  `queue` int NOT NULL,
  PRIMARY KEY (`ride_id`),
  UNIQUE KEY `plate_number_UNIQUE` (`plate_number`),
  UNIQUE KEY `departure_UNIQUE` (`departure`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `reservations`
DROP TABLE IF EXISTS `reservations`;
CREATE TABLE `reservations` (
  `reservation_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `ride_id` int NOT NULL,
  `reservation_time` datetime NOT NULL,
  `status` enum('Active','Canceled','Completed') NOT NULL,
  `payment_status` enum('Paid','Pending','Not Paid') NOT NULL,
  `total_fare` int NOT NULL,
  `payment_method` enum('Manual','GCASH') NOT NULL,
  PRIMARY KEY (`reservation_id`),
  KEY `user_id_idx` (`user_id`),
  KEY `ride_id_idx` (`ride_id`),
  CONSTRAINT `fk_reservation_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_reservation_ride_id` FOREIGN KEY (`ride_id`) REFERENCES `rides` (`ride_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `route`
DROP TABLE IF EXISTS `route`;
CREATE TABLE `route` (
  `plate_number` varchar(6) NOT NULL,
  `ride_type` enum('Jeepney','Service') NOT NULL,
  `capacity` int NOT NULL,
  `pickup_point` varchar(45) NOT NULL,
  `destination` varchar(45) NOT NULL,
  `departure` datetime NOT NULL,
  PRIMARY KEY (`plate_number`),
  KEY `ride_type_idx` (`ride_type`),
  KEY `departure_idx` (`departure`),
  CONSTRAINT `fk_route_plate_number` FOREIGN KEY (`plate_number`) REFERENCES `rides` (`plate_number`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_route_departure` FOREIGN KEY (`departure`) REFERENCES `rides` (`departure`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
