-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2026 at 03:19 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `expense_tracker_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `expense_records`
--

CREATE TABLE `expense_records` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `category` enum('Transportation','Housing','Entertainment','Utilities','Healthcare','Shopping','Other') NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expense_records`
--

INSERT INTO `expense_records` (`id`, `amount`, `expense_date`, `category`, `notes`, `created_at`) VALUES
(1, 4000.00, '2026-02-02', 'Transportation', '', '2026-02-02 13:54:15'),
(2, 5000.00, '2026-02-02', 'Shopping', NULL, '2026-02-02 14:28:20'),
(3, 3000.00, '2026-02-02', '', NULL, '2026-02-02 14:28:37');

-- --------------------------------------------------------

--
-- Table structure for table `income_records`
--

CREATE TABLE `income_records` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `income_date` date NOT NULL,
  `category` enum('Salary','Investment','Gift','Refund','Other') NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `income_records`
--

INSERT INTO `income_records` (`id`, `amount`, `income_date`, `category`, `notes`, `created_at`) VALUES
(1, 5000.00, '2026-02-02', 'Salary', 'Initial Monthly Budget', '2026-02-02 13:41:04'),
(2, 5000.00, '2026-02-02', 'Salary', 'Starting Balance', '2026-02-02 13:43:46'),
(3, 5000.00, '2026-02-02', 'Salary', 'Starting Balance', '2026-02-02 13:44:11'),
(4, 2000.00, '2026-02-02', 'Gift', NULL, '2026-02-02 14:27:31'),
(5, 5000.00, '2026-02-02', 'Gift', NULL, '2026-02-02 14:37:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `expense_records`
--
ALTER TABLE `expense_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_records`
--
ALTER TABLE `income_records`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expense_records`
--
ALTER TABLE `expense_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `income_records`
--
ALTER TABLE `income_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
