
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Set encoding
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Database: `gogora_db`

-- --------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `firstname` varchar(10) NOT NULL,
  `lastname` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(17) NOT NULL,
  `role` enum('Student','Faculty','Employee','Admin','Manager') NOT NULL,
  `user_type` enum('Regular','Priority') NOT NULL,
  `avatar` longblob DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for `rides`
-- --------------------------------------------------------

CREATE TABLE `rides` (
  `ride_id` int(11) NOT NULL AUTO_INCREMENT,
  `plate_number` varchar(6) NOT NULL,
  `route` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  `seats_available` int(11) NOT NULL,
  `ride_type` enum('Jeepney','Service') NOT NULL,
  `departure` datetime NOT NULL,
  `capacity` int(11) NOT NULL,
  `queue` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `image_blob` LONGBLOB DEFAULT NULL,
  PRIMARY KEY (`ride_id`),
  UNIQUE KEY `plate_number_UNIQUE` (`plate_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for `blacklist`
-- --------------------------------------------------------

CREATE TABLE `blacklist` (
  `blacklist_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(10) NOT NULL,
  `blacklist_date` datetime NOT NULL,
  `blacklist_status` enum('Blacklisted','Reinstated') NOT NULL,
  `reason` varchar(45) NOT NULL,
  PRIMARY KEY (`blacklist_id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  CONSTRAINT `user_name` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for `reservations`
-- --------------------------------------------------------

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL,
  `reservation_time` datetime NOT NULL,
  `status` enum('Active','Canceled','Completed') NOT NULL,
  `payment_status` enum('Paid','Pending','Not Paid') DEFAULT 'Not Paid',
  `total_fare` int(11) NOT NULL,
  `payment_method` enum('Manual','GCASH') NOT NULL,
  PRIMARY KEY (`reservation_id`),
  KEY `user_id_idx` (`user_id`),
  KEY `ride_id_idx` (`ride_id`),
  CONSTRAINT `ride_id` FOREIGN KEY (`ride_id`) REFERENCES `rides` (`ride_id`) ON UPDATE CASCADE,
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for `schedules`
-- --------------------------------------------------------

CREATE TABLE `schedules` (
  `sched_id` int(11) NOT NULL AUTO_INCREMENT,
  `ride_id` int(11) NOT NULL,
  `route` varchar(45) NOT NULL,
  `departure` datetime NOT NULL,
  `seats_available` int(11) NOT NULL,
  PRIMARY KEY (`sched_id`),
  KEY `ride_id` (`ride_id`),
  CONSTRAINT `schedule_ride_id` FOREIGN KEY (`ride_id`) REFERENCES `rides` (`ride_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Insert data into `users`
-- --------------------------------------------------------

INSERT INTO `users` (`user_id`, `username`, `firstname`, `lastname`, `password`, `email`, `role`, `user_type`) VALUES
(1, 'admin', 'ad', 'min', '$2y$10$7YPBFlCZ6IMN0paye1otl.SI2I/IMjeuf6v.S60T3cZvLiOjrj6v6', 'admin@gmail.com', 'Admin', 'Regular'),
(2, 'manager', 'man', 'ager', '$2y$10$zWm2HW5vC2WedY2iu3qV.Of6U6vfmMjpKVM4IwErhurlMLq.hweWK', 'manager@gmail.com', 'Manager', 'Regular'),
(3, 'z', 'zel', 'b', '$2y$10$0hOb/9a6QshF/s89q3Zm3eXLpJgzQwr7KPXsxe/Nz/zmjz6W.Qeea', '123456@slu.edu.ph', 'Student', 'Regular');

-- Add data to `rides`, `reservations`, `schedules`, `blacklist` as per original script...

-- --------------------------------------------------------
-- Auto-increment settings
-- --------------------------------------------------------

ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `rides`
  MODIFY `ride_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
