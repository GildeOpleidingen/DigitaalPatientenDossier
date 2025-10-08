-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 10.250.0.103
-- Generation Time: Oct 06, 2025 at 08:09 AM
-- Server version: 8.0.43-0ubuntu0.24.04.1
-- PHP Version: 8.1.2-1ubuntu2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dpd`
--

-- --------------------------------------------------------

--
-- Table structure for table `afdelingen`
--

CREATE TABLE `afdelingen` (
  `id` int NOT NULL,
  `naam` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `afdelingen`
--

INSERT INTO `afdelingen` (`id`, `naam`) VALUES
(1, 'GG2'),
(2, 'Ziekenhuis'),
(3, 'GHZ'),
(4, 'Verpleeghuis'),
(5, 'Thuiszorg'),
(6, 'Revalidatie');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `afdelingen`
--
ALTER TABLE `afdelingen`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `afdelingen`
--
ALTER TABLE `afdelingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
