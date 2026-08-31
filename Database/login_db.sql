-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 31, 2026 at 03:59 PM
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
-- Database: `login_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `approval_id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `approved_by` varchar(100) DEFAULT NULL,
  `action_date` timestamp NULL DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approvals`
--

INSERT INTO `approvals` (`approval_id`, `request_id`, `role`, `status`, `approved_by`, `action_date`, `remarks`) VALUES
(1, 14, 'HR', 'Approved', NULL, NULL, ''),
(2, 14, 'Manager', 'Rejected', NULL, NULL, ''),
(4, 14, 'Admin', 'Pending', NULL, NULL, NULL),
(5, 15, 'HR', 'Approved', NULL, NULL, ''),
(6, 15, 'Manager', 'Approved', NULL, NULL, ''),
(7, 15, 'Finance', 'Approved', NULL, NULL, ''),
(8, 15, 'Admin', 'Completed', NULL, NULL, ''),
(9, 16, 'HR', 'Approved', NULL, NULL, ''),
(10, 16, 'Manager', 'Approved', NULL, NULL, ''),
(12, 16, 'Admin', 'Approved', NULL, NULL, ''),
(13, 17, 'HR', 'Approved', NULL, NULL, 'no remarks'),
(14, 17, 'Manager', 'Approved', NULL, NULL, ''),
(16, 17, 'Admin', 'Completed', NULL, NULL, 'proceed'),
(17, 22, 'HR', 'Approved', NULL, NULL, ''),
(18, 22, 'Manager', 'Approved', NULL, NULL, ''),
(19, 22, 'Admin', 'Approved', NULL, NULL, ''),
(21, 24, 'HR', 'Approved', NULL, NULL, ''),
(22, 24, 'Manager', 'Approved', NULL, NULL, ''),
(23, 24, 'Admin', 'Completed', NULL, NULL, ''),
(24, 25, 'HR', 'Approved', NULL, NULL, ''),
(25, 25, 'Manager', 'Approved', NULL, NULL, ''),
(26, 25, 'Admin', 'Approved', NULL, NULL, ''),
(27, 26, 'HR', 'Approved', NULL, NULL, ''),
(28, 26, 'Manager', 'Approved', NULL, NULL, ''),
(29, 26, 'Admin', 'Completed', NULL, NULL, ''),
(30, 27, 'HR', 'Approved', NULL, NULL, ''),
(31, 27, 'Manager', 'Approved', NULL, NULL, ''),
(32, 27, 'Admin', 'Completed', NULL, NULL, 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `uploaded_by` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `request_id`, `file_name`, `uploaded_by`) VALUES
(1, 22, '1781296289_receipt (Generated on 29_03_2026 08_22 PM).pdf', 'user'),
(2, 24, '1781519341_Student_report (Generated on 14_05_2026 11_48 AM).pdf', 'user'),
(4, 25, '1782669597_Student_report (Generated on 14_05_2026 11_48 AM).pdf', 'user'),
(5, 26, '1782673171_Student_report (Generated on 14_05_2026 11_48 AM).pdf', 'user'),
(6, 27, '1782832536_C Assignment.pdf', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `travel_details`
--

CREATE TABLE `travel_details` (
  `detail_id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `time` time DEFAULT NULL,
  `from_location` varchar(100) DEFAULT NULL,
  `to_location` varchar(100) DEFAULT NULL,
  `mode` varchar(50) DEFAULT NULL,
  `accommodation` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `travel_details`
--

INSERT INTO `travel_details` (`detail_id`, `request_id`, `time`, `from_location`, `to_location`, `mode`, `accommodation`) VALUES
(2, 2, '22:00:00', 'aluva', 'banglore', 'Bus', 'Yes'),
(7, 7, '21:00:00', 'ernakulam', 'chennai', 'Bus', 'No'),
(12, 12, '22:30:00', 'chennai', 'banglore', 'Bus', 'Yes'),
(13, 13, '12:00:00', 'banglore', 'kochi', 'Bus', 'Yes'),
(14, 14, '23:00:00', 'banglore', 'hyderabad', 'Flight', 'Yes'),
(15, 15, '22:30:00', 'kochi', 'banglore', 'Bus', 'Yes'),
(17, 17, '03:04:00', 'aluva', 'banglore', 'Bus', 'Yes'),
(18, 18, '12:30:00', 'chennai', 'kochi', 'Flight', 'No'),
(19, 19, '12:30:00', 'chennai', 'kochi', 'Flight', 'No'),
(20, 20, '12:30:00', 'chennai', 'kochi', 'Flight', 'No'),
(21, 21, '12:30:00', 'chennai', 'kochi', 'Flight', 'No'),
(24, 23, '23:30:00', 'chennai', 'hyderabad', 'Flight', 'Yes'),
(28, 24, '22:30:00', 'chennai', 'hyderabad', 'Flight', 'Yes'),
(30, 16, '11:00:00', 'aluva', 'banglore', 'Flight', 'Yes'),
(31, 22, '11:30:00', 'chennai', 'kochi', 'Flight', 'No'),
(32, 25, '12:30:00', 'banglore', 'hyderabad', 'Flight', 'Yes'),
(33, 26, '21:20:00', 'banglore', 'mumbai', 'Flight', 'Yes'),
(35, 27, '04:00:00', 'chennai', 'banglore', 'Flight', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `travel_requests`
--

CREATE TABLE `travel_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `dept` varchar(50) DEFAULT NULL,
  `designation` varchar(50) DEFAULT NULL,
  `grade` varchar(20) DEFAULT NULL,
  `travel_date` date DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `travel_requests`
--

INSERT INTO `travel_requests` (`request_id`, `user_id`, `name`, `dept`, `designation`, `grade`, `travel_date`, `purpose`, `created_at`) VALUES
(2, NULL, 'shreya', 'cs', 'hr', '2', '2026-12-31', 'visit', '2026-06-07 17:32:51'),
(7, NULL, 'shreya', 'finance', 'BA', '1', '0226-08-03', 'office', '2026-06-07 17:36:14'),
(12, NULL, 'swetha', 'testing', 'jr analyst', '3', '2026-11-09', 'visit', '2026-06-07 18:02:47'),
(13, NULL, 'swetha', 'bugging', 'assistant', '4', '2026-12-05', 'office', '2026-06-07 18:10:11'),
(14, NULL, 'shreya', 'testing', 'assistant', '1', '2026-10-31', 'office', '2026-06-07 19:44:34'),
(15, NULL, 'swetha', 'finance', 'assistant', '1', '2026-10-21', 'study', '2026-06-10 13:33:26'),
(16, NULL, 'shwetha', 'CS', 'manager', '2', '2026-12-12', 'official meeting', '2026-06-11 04:06:12'),
(17, NULL, 'shreya', 'finance', 'assistant', 'a', '2026-10-13', 'office', '2026-06-11 09:34:09'),
(18, NULL, 'shreya', '', 'assistant', 'A', '2026-10-12', 'official meeting', '2026-06-12 20:22:05'),
(19, NULL, 'shreya', '', 'assistant', 'A', '2026-10-12', 'official meeting', '2026-06-12 20:23:11'),
(20, NULL, 'shreya', '', 'assistant', 'A', '2026-10-12', 'official meeting', '2026-06-12 20:23:17'),
(21, NULL, 'shreya', '', 'assistant', 'A', '2026-10-12', 'official meeting', '2026-06-12 20:24:37'),
(22, NULL, 'shreya', 'CS', 'assistant', 'A', '2026-10-13', 'official meeting', '2026-06-12 20:31:29'),
(23, NULL, 'swetha', 'sales', 'assistant', 'B', '2026-10-31', 'study', '2026-06-15 10:29:01'),
(24, NULL, 'swetha', 'sales', 'assistant', 'B', '2026-11-01', 'study', '2026-06-15 12:42:27'),
(25, NULL, 'shwetha', 'customer support', 'assistant', 'c', '0000-00-00', 'official meeting', '2026-06-28 17:59:57'),
(26, NULL, 'swetha', 'Finance', 'jr analyst', 'b', '2026-08-21', 'visit', '2026-06-28 18:59:31'),
(27, NULL, 'shwetha', 'customer support', 'assistant', 'D', '2026-08-12', 'training', '2026-06-30 15:15:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(211, 'manager', '000', 'Manager'),
(243, 'shreya', '123', 'user'),
(324, 'admin', '555', 'Admin'),
(708, 'swetha', '1997', 'user'),
(710, 'hr', '222', 'HR'),
(12345678, 'shwetha', 'swetha', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`approval_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `travel_details`
--
ALTER TABLE `travel_details`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `travel_requests`
--
ALTER TABLE `travel_requests`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approvals`
--
ALTER TABLE `approvals`
  MODIFY `approval_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `travel_details`
--
ALTER TABLE `travel_details`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `travel_requests`
--
ALTER TABLE `travel_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12345679;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approvals`
--
ALTER TABLE `approvals`
  ADD CONSTRAINT `approvals_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `travel_requests` (`request_id`);

--
-- Constraints for table `travel_details`
--
ALTER TABLE `travel_details`
  ADD CONSTRAINT `travel_details_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `travel_requests` (`request_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
