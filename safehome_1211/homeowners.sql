-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2024 at 02:32 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `safehome`
--

-- --------------------------------------------------------

--
-- Table structure for table `homeowners`
--

CREATE TABLE `homeowners` (
  `id` int(15) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `geo_url` varchar(255) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `code` int(20) DEFAULT NULL,
  `verification_status` varchar(20) NOT NULL,
  `threshold_mode` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homeowners`
--

INSERT INTO `homeowners` (`id`, `firstname`, `lastname`, `username`, `email`, `address`, `contact`, `password`, `latitude`, `longitude`, `geo_url`, `picture`, `code`, `verification_status`, `threshold_mode`) VALUES
(10, 'Nicko Ross', 'Jopson', 'nicko_15', 'nickojopson.24@gmail.com', 'A. Valino Street, Aduas Centro, Cabanatuan, Nueva Ecija', '09331877460', '8eaefc8f98b3614158c91f6645fd4b5f', '15.496555464227585', '120.9629145220202', 'http://maps.google.com/maps?f=q&q=15.496555464227585,%20120.9629145220202', '6564797a4e93e.png', 0, 'verified', 'Average'),
(14, 'Chrisden Ann', 'Pizarro', 'dendenden', 'chrisdenpizarro@gmail.com', 'Mabini Street, Sangitan, Cabanatuan, Nueva Ecija, Central Luzon, 3100, Philippines', '09288382725', 'db5c5169946dea282dd6852592a95cc9', '15.495299472957987', '120.96970900345605', 'http://maps.google.com/maps?f=q&q=15.495299472957987,%20120.96970900345605', '', 0, 'verified', 'Average');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `homeowners`
--
ALTER TABLE `homeowners`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `homeowners`
--
ALTER TABLE `homeowners`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
