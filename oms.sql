-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2025 at 05:29 AM
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
-- Database: `oms`
--
DROP DATABASE IF EXISTS `oms`;
CREATE DATABASE IF NOT EXISTS `oms` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `oms`;
-- --------------------------------------------------------

--
-- Table structure for table `admin`
--
DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `ID` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--
DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `ID` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Availability` enum('Available','Unavailable') NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `ID` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Phone` int(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `ID` varchar(255) NOT NULL,
  `Type` enum('online','walk-in') NOT NULL DEFAULT 'online',
  `Member_ID` varchar(255) NOT NULL,
  `Runner_ID` varchar(255) DEFAULT NULL,
  `Total_Amount` decimal(10,2) NOT NULL,
  `Order_Date` datetime NOT NULL DEFAULT current_timestamp(),
  `Ready_Date` datetime DEFAULT NULL,
  `PickedUp_Date` datetime DEFAULT NULL,
  `Delivered_Date` datetime DEFAULT NULL,
  `Status` enum('Order Placed','Readying Order','Picked Up','In Transit','Delivered') NOT NULL DEFAULT 'Order Placed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `ID` varchar(255) NOT NULL,
  `Order_ID` varchar(255) NOT NULL,
  `Item_ID` varchar(255) NOT NULL,
  `Quantity` int(4) NOT NULL,
  `Subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `runners`
--

DROP TABLE IF EXISTS `runners`;
CREATE TABLE IF NOT EXISTS `runners` (
  `ID` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Tel` varchar(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Platform` enum('Grab','Food Panda') NOT NULL,
  `Status` enum('Active', 'Disabled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `ID` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `member_id` (`Member_ID`),
  ADD KEY `runner_id` (`Runner_ID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `order_id` (`Order_ID`),
  ADD KEY `item_id` (`Item_ID`);

--
-- Indexes for table `runners`
--
ALTER TABLE `runners`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `member_id` FOREIGN KEY (`Member_ID`) REFERENCES `members` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `runner_id` FOREIGN KEY (`Runner_ID`) REFERENCES `runners` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `item_id` FOREIGN KEY (`Item_ID`) REFERENCES `items` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_id` FOREIGN KEY (`Order_ID`) REFERENCES `orders` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
