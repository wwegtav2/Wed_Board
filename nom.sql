-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 03:51 PM
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
-- Database: `nom`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `aquestionno` int(4) NOT NULL,
  `ano` int(4) NOT NULL,
  `adetail` longtext NOT NULL,
  `aname` varchar(20) NOT NULL,
  `idphone` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `non_it`
--

CREATE TABLE `non_it` (
  `idphone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `namesur` varchar(50) NOT NULL,
  `gender` enum('ไม่ระบุ','ชาย','หญิง') NOT NULL,
  `birth` date NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `non_it`
--

INSERT INTO `non_it` (`idphone`, `password`, `name`, `namesur`, `gender`, `birth`, `address`) VALUES
('0123456789', '$2y$10$QGzWZ5/dRKQeYIsMz7lFX./p7/29F5/OtjQhtVQayvwQAh.K48MZS', 'ธันวา', 'ดำรักษ์', 'ชาย', '2025-05-14', '000 หมู่ที่ 0 ตำบลควนหลัง');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `qno` int(4) NOT NULL,
  `qtopic` varchar(50) NOT NULL,
  `qdetail` longtext NOT NULL,
  `qcount` int(4) DEFAULT NULL,
  `qname` varchar(20) NOT NULL,
  `idphone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD UNIQUE KEY `ano` (`ano`);

--
-- Indexes for table `non_it`
--
ALTER TABLE `non_it`
  ADD PRIMARY KEY (`idphone`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`qno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answer`
--

ALTER TABLE `answer`
  MODIFY `ano` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
