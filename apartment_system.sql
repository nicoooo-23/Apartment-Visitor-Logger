-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2026 at 01:38 PM
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
-- Database: `apartment_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE IF NOT EXISTS `admin_users` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT IGNORE INTO `admin_users` (`admin_id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$QA4ZGN90ccOvA5K2vb5drevUqEGpBhOSKe45HHOG7VmgKzbo92sT6');

-- --------------------------------------------------------

--
-- Table structure for table `apartments`
--

CREATE TABLE IF NOT EXISTS `apartments` (
  `apt_id` int(11) NOT NULL,
  `apartment_number` varchar(50) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `status` enum('occupied','vacant') DEFAULT 'vacant'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `apartments`
--

INSERT IGNORE INTO `apartments` (`apt_id`, `apartment_number`, `tenant_id`, `status`) VALUES
(1, '101', 1, 'occupied'),
(2, '102', 2, 'occupied'),
(3, '103', 3, 'occupied'),
(4, '104', 4, 'occupied');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE IF NOT EXISTS `tenants` (
  `t_id` int(11) NOT NULL,
  `tenant_name` varchar(255) NOT NULL,
  `tenant_email` varchar(255) DEFAULT NULL,
  `tenant_phone` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT IGNORE INTO `tenants` (`t_id`, `tenant_name`, `tenant_email`, `tenant_phone`) VALUES
(1, 'Nicole Rivera', 'sample@nico.com', '0911-222-3333'),
(2, 'Christine Dantes', 'sample@chrissy.com', '0922-333-4444'),
(3, 'Graciela Gozum', 'sample@gracie.com', '0933-444-5555'),
(4, 'Kenneth Guanlao', 'sample@neth.com', '0944-555-6666');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `v_id` int(11) NOT NULL,
  `visitor_name` varchar(255) NOT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `apartment_id` int(11) NOT NULL,
  `status` enum('checked_in','checked_out') DEFAULT 'checked_in',
  `visit_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `checkout_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`v_id`, `visitor_name`, `contact`, `purpose`, `apartment_id`, `status`, `visit_time`, `checkout_time`) VALUES
(1, 'Rain D.', '5317', 'Friend visitation.', 1, 'checked_out', '2026-03-09 11:59:51', '2026-03-09 12:36:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `apartments`
--
ALTER TABLE `apartments`
  ADD PRIMARY KEY (`apt_id`),
  ADD UNIQUE KEY `apartment_number` (`apartment_number`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`t_id`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`v_id`),
  ADD KEY `apartment_id` (`apartment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `apartments`
--
ALTER TABLE `apartments`
  MODIFY `apt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `v_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `apartments`
--
ALTER TABLE `apartments`
  ADD CONSTRAINT `apartments_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`t_id`) ON DELETE SET NULL;

--
-- Constraints for table `visitors`
--
ALTER TABLE `visitors`
  ADD CONSTRAINT `visitors_ibfk_1` FOREIGN KEY (`apartment_id`) REFERENCES `apartments` (`apt_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
