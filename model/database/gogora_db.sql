-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2024 at 03:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gogora_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `blacklist`
--

CREATE TABLE `blacklist` (
  `blacklist_id` int(11) NOT NULL,
  `username` varchar(10) NOT NULL,
  `blacklist_date` datetime NOT NULL,
  `blacklist_status` enum('Blacklisted','Reinstated') NOT NULL,
  `reason` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL,
  `reservation_time` datetime NOT NULL,
  `status` enum('Active','Canceled','Completed') NOT NULL,
  `payment_status` enum('Paid','Pending','Not Paid') DEFAULT 'Not Paid',
  `total_fare` int(11) NOT NULL,
  `payment_method` enum('Manual','GCASH') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `user_id`, `ride_id`, `reservation_time`, `status`, `payment_status`, `total_fare`, `payment_method`) VALUES
(1, 3, 1, '2024-12-07 07:00:00', 'Active', 'Not Paid', 15, 'GCASH'),
(2, 3, 1, '2024-12-16 05:09:12', 'Active', 'Not Paid', 15, ''),
(3, 3, 1, '2024-12-16 05:50:46', 'Active', 'Not Paid', 15, ''),
(4, 3, 1, '2024-12-16 05:51:29', 'Active', 'Not Paid', 15, ''),
(5, 3, 1, '2024-12-16 05:54:01', 'Active', 'Not Paid', 15, ''),
(6, 10, 1, '2024-12-16 07:29:29', 'Active', 'Not Paid', 15, '');

-- --------------------------------------------------------

--
-- Table structure for table `rides`
--

CREATE TABLE `rides` (
  `ride_id` int(11) NOT NULL,
  `plate_number` varchar(6) NOT NULL,
  `route` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  `seats_available` int(11) NOT NULL,
  `ride_type` enum('Jeepney','Service') NOT NULL,
  `departure` datetime NOT NULL,
  `capacity` int(11) NOT NULL,
  `queue` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rides`
--

INSERT INTO `rides` (`ride_id`, `plate_number`, `route`, `time`, `seats_available`, `ride_type`, `departure`, `capacity`, `queue`) VALUES
(1, 'abc123', 'Bakakeng to Igorot Park', '2024-12-16 08:30:00', 21, 'Jeepney', '2024-12-15 08:50:00', 21, 1),
(2, 'abc456', 'Igorot Park to Bakakeng', '2024-12-16 09:30:00', 22, 'Jeepney', '2024-12-15 09:50:00', 24, 1);

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `plate_number` varchar(6) NOT NULL,
  `ride_type` enum('Jeepney','Service') NOT NULL,
  `capacity` int(11) NOT NULL,
  `pickup_point` varchar(45) NOT NULL,
  `destination` varchar(45) NOT NULL,
  `departure` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(10) NOT NULL,
  `firstname` varchar(10) NOT NULL,
  `lastname` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(17) NOT NULL,
  `role` enum('Student','Faculty','Employee','Admin','Manager') NOT NULL,
  `user_type` enum('Regular','Priority') NOT NULL,
  `avatar` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `firstname`, `lastname`, `password`, `email`, `role`, `user_type`, `avatar`) VALUES
(1, 'admin', 'ad', 'min', '$2y$10$7YPBFlCZ6IMN0paye1otl.SI2I/IMjeuf6v.S60T3cZvLiOjrj6v6', 'admin@gmail.com', 'Admin', 'Regular', ''),
(2, 'manager', 'man', 'ager', '$2y$10$zWm2HW5vC2WedY2iu3qV.Of6U6vfmMjpKVM4IwErhurlMLq.hweWK', 'manager@gmail.com', 'Manager', 'Regular', ''),
(3, 'z', 'zel', 'b', '$2y$10$0hOb/9a6QshF/s89q3Zm3eXLpJgzQwr7KPXsxe/Nz/zmjz6W.Qeea', '123456@slu.edu.ph', 'Student', 'Regular', ''),
(4, 'jekka', 'Jekka', 'Hufalar', '$2y$10$oI.T8Ox4m.Je9YbuS9RsQeguuMBte6L/A8sKzXKHCfcQOgecPWKm2', 'jekka@gmail.com', 'Student', 'Regular', NULL),
(5, 'mark', 'mark', 'galarce', '$2y$10$wMUqzROJi1hArxGexhURNujzZoBRupIxVo1pRkFzQxFdPJzDPj9q2', '123@gmail.com', 'Student', 'Regular', NULL),
(10, 'jem', 'jem', 'niduaza', '$2y$10$9.hMHUDtjJIFlgq4hmL7n.Nda7McTeXkRqJDO7R3dWNnSY3JGqMqa', 'jemmanid@gmail.co', 'Student', 'Regular', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blacklist`
--
ALTER TABLE `blacklist`
  ADD PRIMARY KEY (`blacklist_id`),
  ADD UNIQUE KEY `user_id_UNIQUE` (`blacklist_id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `user_id_idx` (`user_id`),
  ADD KEY `ride_id_idx` (`ride_id`);

--
-- Indexes for table `rides`
--
ALTER TABLE `rides`
  ADD PRIMARY KEY (`ride_id`),
  ADD UNIQUE KEY `ride_id_UNIQUE` (`ride_id`),
  ADD UNIQUE KEY `plate_number_UNIQUE` (`plate_number`),
  ADD UNIQUE KEY `departure_UNIQUE` (`departure`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`plate_number`),
  ADD UNIQUE KEY `plate_number_UNIQUE` (`plate_number`),
  ADD KEY `ride_type _idx` (`ride_type`),
  ADD KEY `departure_idx` (`departure`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_id_UNIQUE` (`user_id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rides`
--
ALTER TABLE `rides`
  MODIFY `ride_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blacklist`
--
ALTER TABLE `blacklist`
  ADD CONSTRAINT `user_name ` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON UPDATE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `ride_id` FOREIGN KEY (`ride_id`) REFERENCES `rides` (`ride_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `route`
--
ALTER TABLE `route`
  ADD CONSTRAINT `departure` FOREIGN KEY (`departure`) REFERENCES `rides` (`departure`) ON UPDATE CASCADE,
  ADD CONSTRAINT `plate_number` FOREIGN KEY (`plate_number`) REFERENCES `rides` (`plate_number`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ride_type ` FOREIGN KEY (`ride_type`) REFERENCES `rides` (`ride_type`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
