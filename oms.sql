-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2025 at 06:21 PM
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
DROP DATABASE IF EXISTS oms;
CREATE DATABASE IF NOT EXISTS oms DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE oms;
-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

DROP TABLE IF EXISTS `addons`;
CREATE TABLE IF NOT EXISTS `addons`  (
  `addonID` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Category` enum('Signature','Breakfast','Ala-Carte','Ala-Carte 2','Western') NOT NULL DEFAULT 'Signature',
  `Section` enum('Add On','Add On Set','Add On (For Roti)','Add On (For Aneka Lauk Thai)','Add On (For Ala Thai & Tomyam)','Add On (For Burger)') NOT NULL DEFAULT 'Add On',
  `Price` decimal(10,2) NOT NULL,
  `Availability` enum('Available','Unavailable') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addons`
--

INSERT INTO `addons` (`addonID`, `Name`, `Category`, `Section`, `Price`, `Availability`) VALUES
('A001', 'Mee', 'Signature', 'Add On', 2.00, 'Available'),
('A002', 'Mee Hoon', 'Signature', 'Add On', 2.00, 'Available'),
('A003', 'Kuey Teow', 'Signature', 'Add On', 2.00, 'Available'),
('A004', 'Set Nasi Putih + Telur Dadar + Sambal + Ulaman', 'Signature', 'Add On Set', 5.00, 'Available'),
('A005', 'Roti Francis', 'Signature', 'Add On', 2.50, 'Available'),
('A006', 'Roti Gardenia', 'Signature', 'Add On', 2.50, 'Available'),
('A007', 'Telur 1/2 masak', 'Breakfast', 'Add On (For Roti)', 3.50, 'Available'),
('A008', 'Nasi Putih', 'Ala-Carte', 'Add On (For Aneka Lauk Thai)', 2.00, 'Available'),
('A009', 'Nasi Goreng', 'Ala-Carte', 'Add On (For Aneka Lauk Thai)', 3.00, 'Available'),
('A010', 'Mee (Sup Thai)', 'Ala-Carte 2', 'Add On (For Ala Thai & Tomyam)', 2.00, 'Available'),
('A011', 'Mee Hoon (Sup Thai)', 'Ala-Carte 2', 'Add On (For Ala Thai & Tomyam)', 3.00, 'Available'),
('A012', 'Kuey Teow (Sup Thai)', 'Ala-Carte 2', 'Add On (For Ala Thai & Tomyam)', 3.00, 'Available'),
('A013', 'Mee (Tomyam)', 'Ala-Carte 2', 'Add On (For Ala Thai & Tomyam)', 2.00, 'Available'),
('A014', 'Mee Hoon (Tomyam)', 'Ala-Carte 2', 'Add On (For Ala Thai & Tomyam)', 3.00, 'Available'),
('A015', 'Kuey Teow (Tomyam)', 'Ala-Carte 2', 'Add On (For Ala Thai & Tomyam)', 3.00, 'Available'),
('A016', 'Fries (Burger)', 'Western', 'Add On (For Burger)', 2.00, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `ID` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID`, `Password`, `Email`) VALUES
('admin', '$2y$10$BXALg81RUxnwHOHvXequH./3Ywqekr1n1vymKFoXpD3/kibBNd0SS', 'admin123@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `drinks`
--
DROP TABLE IF EXISTS `drinks`;
CREATE TABLE IF NOT EXISTS `drinks` (
  `drinkID` varchar(255) NOT NULL,
  `Section` enum('Non-Coffee','Jus','Cold Dessert','Coffee') NOT NULL DEFAULT 'Non-Coffee',
  `Name` varchar(255) NOT NULL,
  `hotPrice` decimal(10,2) DEFAULT NULL,
  `coldPrice` decimal(10,2) NOT NULL,
  `Availability` enum('Available','Unavailable') NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drinks`
--

INSERT INTO `drinks` (`drinkID`, `Section`, `Name`, `hotPrice`, `coldPrice`, `Availability`) VALUES
('B001', 'Non-Coffee', 'TEH O', 2.30, 2.50, 'Available'),
('B002', 'Non-Coffee', 'TEH TARIK', 2.50, 3.00, 'Available'),
('B003', 'Non-Coffee', 'TEH HALIA', 3.50, 4.00, 'Available'),
('B004', 'Non-Coffee', 'TEH SARBAT', 3.50, 4.00, 'Available'),
('B005', 'Non-Coffee', 'SIRAP', 2.00, 2.50, 'Available'),
('B006', 'Non-Coffee', 'SIRAP SELASIH', 2.50, 3.00, 'Available'),
('B007', 'Non-Coffee', 'SIRAP LIMAU', 2.70, 3.00, 'Available'),
('B008', 'Non-Coffee', 'SIRAP LAICI', 5.00, 5.50, 'Available'),
('B009', 'Non-Coffee', 'SIRAP BANDUNG', NULL, 3.50, 'Available'),
('B010', 'Non-Coffee', 'SIRAP BANDUNG CINCAU', NULL, 4.00, 'Available'),
('B011', 'Non-Coffee', 'SIRAP BANDUNG SODA', NULL, 4.00, 'Available'),
('B012', 'Non-Coffee', 'LIMAU', 2.70, 3.00, 'Available'),
('B013', 'Non-Coffee', 'ASAM BOY', 2.70, 3.00, 'Available'),
('B014', 'Non-Coffee', 'EXTRA JOSS SUSU (ANGGUR)', NULL, 4.00, 'Available'),
('B015', 'Non-Coffee', 'EXTRA JOSS SUSU (MANGA)', NULL, 4.00, 'Available'),
('B016', 'Non-Coffee', 'VICO', 3.00, 3.50, 'Available'),
('B017', 'Jus', 'JUS ORANGE', 4.70, 5.00, 'Available'),
('B018', 'Jus', 'JUS APPLE', 4.70, 5.00, 'Available'),
('B019', 'Jus', 'JUS WATERMELON', 4.70, 5.00, 'Available'),
('B020', 'Jus', 'JUS LYCHEE', 4.70, 5.00, 'Available'),
('B021', 'Jus', 'JUS LEMON', 4.70, 5.00, 'Available'),
('B022', 'Cold Dessert', 'CIKONG', NULL, 6.00, 'Available'),
('B023', 'Cold Dessert', 'AIS JELLY LIMAU', NULL, 6.00, 'Available'),
('B024', 'Cold Dessert', 'CENDOL', NULL, 6.00, 'Available'),
('B025', 'Coffee', 'INDO CAFE O\'', 4.70, 5.00, 'Available'),
('B026', 'Coffee', 'INDO CAFE SUSU', 4.70, 5.00, 'Available'),
('B027', 'Coffee', 'KOPI TENGGEK', 4.70, 5.00, 'Available'),
('B028', 'Coffee', 'KOPI SPECIAL', 4.70, 5.00, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

DROP TABLE IF EXISTS `food`;
CREATE TABLE IF NOT EXISTS `food` (
  `foodID` varchar(255) NOT NULL,
  `Category` enum('Signature','Breakfast','Lunch','Roti','Ikan','Ala-Carte','Ala-Carte 2','Western','Goreng-goreng') NOT NULL,
  `Section` enum('Sup ZZ','Mee Rebus ZZ','Masakan Panas','Roti Bakar','Set Nasi & Lauk','Roti Menu','Ikan Siakap','Bakar-Bakar','Sayur','Aneka Lauk Thai','Goreng Tepung','Sup Ala Thai','Tomyam','Mee Kuah','Nasi Goreng','Mee Goreng','Fried & Grill','Spaghetti','Burger','Sides') NOT NULL,
  `Type` enum('Mee','Mee Hoon','Kuey Teow','Original','Daging','Udang','Sotong') DEFAULT NULL,
  `Name` varchar(255) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Availability` enum('Available','Unavailable') NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`foodID`, `Category`, `Section`, `Type`, `Name`, `Price`, `Availability`) VALUES
('F001', 'Signature', 'Sup ZZ', NULL, 'Sup Gearbox Kambing', 19.00, 'Available'),
('F002', 'Signature', 'Sup ZZ', NULL, 'Sup Kambing', 20.00, 'Available'),
('F003', 'Signature', 'Sup ZZ', NULL, 'Sup Daging', 8.00, 'Available'),
('F004', 'Signature', 'Sup ZZ', NULL, 'Sup Ayam', 7.00, 'Available'),
('F005', 'Signature', 'Mee Rebus ZZ', NULL, 'Mee Rebus Gearbox Kambing', 20.00, 'Available'),
('F006', 'Signature', 'Mee Rebus ZZ', NULL, 'Mee Rebus Daging', 9.50, 'Available'),
('F007', 'Signature', 'Mee Rebus ZZ', NULL, 'Mee Rebus Ayam', 9.00, 'Available'),
('F008', 'Breakfast', 'Masakan Panas', NULL, 'Lontong Kuah', 7.50, 'Available'),
('F009', 'Breakfast', 'Masakan Panas', NULL, 'Lontong Kering (Ayam)', 9.00, 'Available'),
('F010', 'Breakfast', 'Masakan Panas', NULL, 'Lontong Kering (Daging)', 9.50, 'Available'),
('F011', 'Breakfast', 'Masakan Panas', NULL, 'Nasi Lemak Basmathi (Telur)', 6.00, 'Available'),
('F012', 'Breakfast', 'Masakan Panas', NULL, 'Nasi Lemak Basmathi (Ayam)', 9.00, 'Available'),
('F013', 'Breakfast', 'Masakan Panas', NULL, 'Nasi Lemak Rendang (Ayam)', 8.50, 'Available'),
('F014', 'Breakfast', 'Masakan Panas', NULL, 'Nasi Lemak Rendang (Daging)', 9.50, 'Available'),
('F015', 'Breakfast', 'Masakan Panas', NULL, 'Nasi Ayam Basmathi', 12.00, 'Available'),
('F016', 'Breakfast', 'Masakan Panas', NULL, 'Nasi Ambang', 9.50, 'Available'),
('F017', 'Breakfast', 'Masakan Panas', NULL, 'Bubur Nasi', 7.50, 'Available'),
('F018', 'Breakfast', 'Masakan Panas', NULL, 'Bubur Ayam', 7.00, 'Available'),
('F019', 'Breakfast', 'Masakan Panas', NULL, 'Laksa (Johor)', 8.00, 'Available'),
('F020', 'Breakfast', 'Masakan Panas', NULL, 'Laksa (Penang)', 7.50, 'Available'),
('F021', 'Breakfast', 'Masakan Panas', NULL, 'Bakso (Mee)', 7.50, 'Available'),
('F022', 'Breakfast', 'Masakan Panas', NULL, 'Bakso (Mee Hoon)', 7.50, 'Available'),
('F023', 'Breakfast', 'Masakan Panas', NULL, 'Bakso (Nasi)', 7.50, 'Available'),
('F024', 'Breakfast', 'Masakan Panas', NULL, 'Soto (Mee)', 8.00, 'Available'),
('F025', 'Breakfast', 'Masakan Panas', NULL, 'Soto (Mee Hoon)', 8.00, 'Available'),
('F026', 'Breakfast', 'Masakan Panas', NULL, 'Soto (Nasi)', 8.00, 'Available'),
('F027', 'Breakfast', 'Roti Bakar', NULL, 'Roti Bakar', 2.50, 'Available'),
('F028', 'Breakfast', 'Roti Bakar', NULL, 'Roti Kaya', 3.50, 'Available'),
('F029', 'Breakfast', 'Roti Bakar', NULL, 'Roti Garlic', 3.50, 'Available'),
('F030', 'Lunch', 'Set Nasi & Lauk', NULL, 'Nasi Bawal Goreng Berlado', 9.00, 'Available'),
('F031', 'Lunch', 'Set Nasi & Lauk', NULL, 'Nasi Siakap Goreng Berlado', 15.00, 'Available'),
('F032', 'Lunch', 'Set Nasi & Lauk', NULL, 'Nasi Keli Goreng Berlado', 10.90, 'Available'),
('F033', 'Lunch', 'Set Nasi & Lauk', NULL, 'Nasi Ayam Goreng Berlado', 8.50, 'Available'),
('F034', 'Lunch', 'Masakan Panas', NULL, 'Bubur Ayam', 6.50, 'Available'),
('F035', 'Lunch', 'Masakan Panas', NULL, 'Bubur Nasi', 7.50, 'Available'),
('F036', 'Lunch', 'Masakan Panas', NULL, 'Bakso Mee', 7.50, 'Available'),
('F037', 'Lunch', 'Masakan Panas', NULL, 'Bakso MeeHoon', 7.50, 'Available'),
('F038', 'Lunch', 'Masakan Panas', NULL, 'Laksa Johor', 8.00, 'Available'),
('F039', 'Lunch', 'Masakan Panas', NULL, 'Laksa Penang', 7.50, 'Available'),
('F040', 'Lunch', 'Masakan Panas', NULL, 'Soto Mee', 8.00, 'Available'),
('F041', 'Lunch', 'Masakan Panas', NULL, 'Soto MeeHoon', 8.00, 'Available'),
('F042', 'Lunch', 'Masakan Panas', NULL, 'Nasi Lemak Basmathi Telur', 6.00, 'Available'),
('F043', 'Lunch', 'Masakan Panas', NULL, 'Nasi Lemak Basmathi Ayam', 9.00, 'Available'),
('F044', 'Roti', 'Roti Menu', NULL, 'Roti Kosong', 1.50, 'Available'),
('F045', 'Roti', 'Roti Menu', NULL, 'Roti Kosong Bawang', 2.00, 'Available'),
('F046', 'Roti', 'Roti Menu', NULL, 'Roti Tampal', 2.80, 'Available'),
('F047', 'Roti', 'Roti Menu', NULL, 'Roti Telur', 2.80, 'Available'),
('F048', 'Roti', 'Roti Menu', NULL, 'Roti Telur Bawang', 3.50, 'Available'),
('F049', 'Roti', 'Roti Menu', NULL, 'Roti Telur Double Jantan', 5.50, 'Available'),
('F050', 'Roti', 'Roti Menu', NULL, 'Roti Pisang', 4.50, 'Available'),
('F051', 'Roti', 'Roti Menu', NULL, 'Roti Sardin', 6.00, 'Available'),
('F052', 'Roti', 'Roti Menu', NULL, 'Roti Bom', 2.50, 'Available'),
('F053', 'Roti', 'Roti Menu', NULL, 'Roti Planta', 3.00, 'Available'),
('F054', 'Roti', 'Roti Menu', NULL, 'Roti Sarang Burung Daging', 8.00, 'Available'),
('F055', 'Ikan', 'Ikan Siakap', NULL, 'Siakap Tiga Rasa', 35.00, 'Available'),
('F056', 'Ikan', 'Ikan Siakap', NULL, 'Siakap Masam Manis', 35.00, 'Available'),
('F057', 'Ikan', 'Ikan Siakap', NULL, 'Siakap Steam Lemon', 35.00, 'Available'),
('F058', 'Ikan', 'Ikan Siakap', NULL, 'Siakap Laprik', 35.00, 'Available'),
('F059', 'Ikan', 'Ikan Siakap', NULL, 'Siakap Goreng Kunyit', 35.00, 'Available'),
('F060', 'Ikan', 'Bakar-Bakar', NULL, 'Siakap Bakar', 35.00, 'Available'),
('F061', 'Ikan', 'Bakar-Bakar', NULL, 'Caru Bakar', 8.00, 'Available'),
('F062', 'Ikan', 'Bakar-Bakar', NULL, 'Kerang Bakar', 15.00, 'Available'),
('F063', 'Ikan', 'Bakar-Bakar', NULL, 'Sotong Bakar', 15.00, 'Available'),
('F064', 'Ala-Carte', 'Sayur', NULL, 'Kailan (Biasa)', 7.00, 'Available'),
('F065', 'Ala-Carte', 'Sayur', NULL, 'Kailan (Ikan Masin)', 7.00, 'Available'),
('F066', 'Ala-Carte', 'Sayur', NULL, 'Kangkung (Biasa)', 7.00, 'Available'),
('F067', 'Ala-Carte', 'Sayur', NULL, 'Kangkung (Belacan)', 7.00, 'Available'),
('F068', 'Ala-Carte', 'Sayur', NULL, 'Taugeh (Biasa)', 7.00, 'Available'),
('F069', 'Ala-Carte', 'Sayur', NULL, 'Taugeh (Ikan Masin)', 7.00, 'Available'),
('F070', 'Ala-Carte', 'Sayur', NULL, 'Sawi (Biasa)', 7.00, 'Available'),
('F071', 'Ala-Carte', 'Sayur', NULL, 'Sawi (Ikan Masin)', 7.00, 'Available'),
('F072', 'Ala-Carte', 'Sayur', NULL, 'Cendawan Goreng Biasa', 7.00, 'Available'),
('F073', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Ayam Black Pepper', 7.50, 'Available'),
('F074', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Daging Black Pepper', 8.50, 'Available'),
('F075', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Sotong Black Pepper', 9.50, 'Available'),
('F076', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Ayam Sambal', 7.50, 'Available'),
('F077', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Daging Sambal', 8.50, 'Available'),
('F078', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Sotong Sambal', 9.50, 'Available'),
('F079', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Ayam Merah', 7.50, 'Available'),
('F080', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Daging Merah', 8.50, 'Available'),
('F081', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Sotong Merah', 9.50, 'Available'),
('F082', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Ayam Paprik', 7.50, 'Available'),
('F083', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Daging Paprik', 8.50, 'Available'),
('F084', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Sotong Paprik', 9.50, 'Available'),
('F085', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Ayam Pha Khra Phao', 8.00, 'Available'),
('F086', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Daging Pha Khra Phao', 9.00, 'Available'),
('F087', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Ayam Kunyit', 7.50, 'Available'),
('F088', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Daging Kunyit', 9.50, 'Available'),
('F089', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Sotong Kunyit', 9.50, 'Available'),
('F090', 'Ala-Carte', 'Aneka Lauk Thai', NULL, 'Udang Kunyit', 9.50, 'Available'),
('F091', 'Ala-Carte', 'Goreng Tepung', NULL, 'Sotong Goreng Tepung', 10.50, 'Available'),
('F092', 'Ala-Carte', 'Goreng Tepung', NULL, 'Udang Goreng Tepung', 10.50, 'Available'),
('F093', 'Ala-Carte', 'Goreng Tepung', NULL, 'Cendawan Goreng Tepung', 7.00, 'Available'),
('F094', 'Ala-Carte', 'Goreng Tepung', NULL, 'Inokki Goreng Tepung', 7.00, 'Available'),
('F095', 'Ala-Carte 2', 'Sup Ala Thai', NULL, 'Sup Ayam Ala Thai', 8.00, 'Available'),
('F096', 'Ala-Carte 2', 'Sup Ala Thai', NULL, 'Sup Daging Ala Thai', 9.00, 'Available'),
('F097', 'Ala-Carte 2', 'Tomyam', NULL, 'Tom Yam Ayam', 8.00, 'Available'),
('F098', 'Ala-Carte 2', 'Tomyam', NULL, 'Tom Yam Daging', 9.00, 'Available'),
('F099', 'Ala-Carte 2', 'Tomyam', NULL, 'Tom Yam Ayam + Daging', 12.00, 'Available'),
('F100', 'Ala-Carte 2', 'Tomyam', NULL, 'Tom Yam Seafood', 13.00, 'Available'),
('F101', 'Ala-Carte 2', 'Tomyam', NULL, 'Tom Yam Campur', 13.00, 'Available'),
('F102', 'Ala-Carte 2', 'Tomyam', NULL, 'Tom Yam Sayur', 8.00, 'Available'),
('F103', 'Ala-Carte 2', 'Tomyam', NULL, 'Tom Yam Cendawan', 8.00, 'Available'),
('F104', 'Ala-Carte 2', 'Mee Kuah', 'Mee', 'Bandung', 10.50, 'Available'),
('F105', 'Ala-Carte 2', 'Mee Kuah', 'Mee', 'Hong Kong', 10.50, 'Available'),
('F106', 'Ala-Carte 2', 'Mee Kuah', 'Mee', 'Hailam', 10.50, 'Available'),
('F107', 'Ala-Carte 2', 'Mee Kuah', 'Mee', 'Kung Fu', 10.50, 'Available'),
('F108', 'Ala-Carte 2', 'Mee Kuah', 'Mee Hoon', 'Bandung', 10.50, 'Available'),
('F109', 'Ala-Carte 2', 'Mee Kuah', 'Mee Hoon', 'Hong Kong', 10.50, 'Available'),
('F110', 'Ala-Carte 2', 'Mee Kuah', 'Mee Hoon', 'Hailam', 10.50, 'Available'),
('F111', 'Ala-Carte 2', 'Mee Kuah', 'Mee Hoon', 'Kung Fu', 10.50, 'Available'),
('F112', 'Ala-Carte 2', 'Mee Kuah', 'Kuey Teow', 'Bandung', 10.50, 'Available'),
('F113', 'Ala-Carte 2', 'Mee Kuah', 'Kuey Teow', 'Hong Kong', 10.50, 'Available'),
('F114', 'Ala-Carte 2', 'Mee Kuah', 'Kuey Teow', 'Hailam', 10.50, 'Available'),
('F115', 'Ala-Carte 2', 'Mee Kuah', 'Kuey Teow', 'Kung Fu', 10.50, 'Available'),
('F116', 'Western', 'Fried & Grill', NULL, 'Chicken Chop (Fried)', 18.50, 'Available'),
('F117', 'Western', 'Fried & Grill', NULL, 'Chicken Chop (Grill)', 18.50, 'Available'),
('F118', 'Western', 'Fried & Grill', NULL, 'Fish N Chips', 16.50, 'Available'),
('F119', 'Western', 'Fried & Grill', NULL, 'Lamb Chop', 30.90, 'Available'),
('F120', 'Western', 'Spaghetti', NULL, 'Spaghetti Aglio Olio (Seafood)', 17.00, 'Available'),
('F121', 'Western', 'Spaghetti', NULL, 'Spaghetti Aglio Olio (Beef Bacon)', 15.00, 'Available'),
('F122', 'Western', 'Spaghetti', NULL, 'Spaghetti Aglio Olio (Chicken)', 13.00, 'Available'),
('F123', 'Western', 'Spaghetti', NULL, 'Spaghetti Carbonara (Seafood)', 18.00, 'Available'),
('F124', 'Western', 'Spaghetti', NULL, 'Spaghetti Carbonara (Beef Bacon)', 16.00, 'Available'),
('F125', 'Western', 'Spaghetti', NULL, 'Spaghetti Carbonara (Chicken)', 14.00, 'Available'),
('F126', 'Western', 'Spaghetti', NULL, 'Spaghetti Bolognesse', 15.00, 'Available'),
('F127', 'Western', 'Spaghetti', NULL, 'Mac & Cheese', 14.00, 'Available'),
('F128', 'Western', 'Burger', NULL, 'Burger Smash Beef (Single)', 8.00, 'Available'),
('F129', 'Western', 'Burger', NULL, 'Burger Smash Beef (Double)', 10.00, 'Available'),
('F130', 'Western', 'Burger', NULL, 'Burger Crispy Chicken', 7.50, 'Available'),
('F131', 'Western', 'Sides', NULL, 'Fries', 7.50, 'Available'),
('F132', 'Western', 'Sides', NULL, 'Nugget (8pcs)', 8.00, 'Available'),
('F133', 'Western', 'Sides', NULL, 'Cheesy Wedges', 8.50, 'Available'),
('F134', 'Goreng-goreng', 'Nasi Goreng', 'Original', 'Nasi Goreng Biasa', 7.50, 'Available'),
('F135', 'Goreng-goreng', 'Nasi Goreng', 'Daging', 'Nasi Goreng Biasa', 8.50, 'Available'),
('F136', 'Goreng-goreng', 'Nasi Goreng', 'Udang', 'Nasi Goreng Biasa', 10.50, 'Available'),
('F137', 'Goreng-goreng', 'Nasi Goreng', 'Sotong', 'Nasi Goreng Biasa', 10.50, 'Available'),
('F138', 'Goreng-goreng', 'Nasi Goreng', 'Original', 'Nasi Goreng Kampung', 8.00, 'Available'),
('F139', 'Goreng-goreng', 'Nasi Goreng', 'Daging', 'Nasi Goreng Kampung', 9.00, 'Available'),
('F140', 'Goreng-goreng', 'Nasi Goreng', 'Udang', 'Nasi Goreng Kampung', 11.00, 'Available'),
('F141', 'Goreng-goreng', 'Nasi Goreng', 'Sotong', 'Nasi Goreng Kampung', 11.00, 'Available'),
('F142', 'Goreng-goreng', 'Nasi Goreng', 'Original', 'Nasi Goreng Cina', 7.50, 'Available'),
('F143', 'Goreng-goreng', 'Nasi Goreng', 'Daging', 'Nasi Goreng Cina', 8.50, 'Available'),
('F144', 'Goreng-goreng', 'Nasi Goreng', 'Udang', 'Nasi Goreng Cina', 10.50, 'Available'),
('F145', 'Goreng-goreng', 'Nasi Goreng', 'Sotong', 'Nasi Goreng Cina', 10.50, 'Available'),
('F146', 'Goreng-goreng', 'Nasi Goreng', 'Original', 'Nasi Goreng Ikan Masin', 8.50, 'Available'),
('F147', 'Goreng-goreng', 'Nasi Goreng', 'Daging', 'Nasi Goreng Ikan Masin', 9.50, 'Available'),
('F148', 'Goreng-goreng', 'Nasi Goreng', 'Udang', 'Nasi Goreng Ikan Masin', 11.50, 'Available'),
('F149', 'Goreng-goreng', 'Nasi Goreng', 'Sotong', 'Nasi Goreng Ikan Masin', 11.50, 'Available'),
('F150', 'Goreng-goreng', 'Nasi Goreng', 'Original', 'Nasi Goreng Cili Padi', 8.50, 'Available'),
('F151', 'Goreng-goreng', 'Nasi Goreng', 'Daging', 'Nasi Goreng Cili Padi', 9.50, 'Available'),
('F152', 'Goreng-goreng', 'Nasi Goreng', 'Udang', 'Nasi Goreng Cili Padi', 11.50, 'Available'),
('F153', 'Goreng-goreng', 'Nasi Goreng', 'Sotong', 'Nasi Goreng Cili Padi', 11.50, 'Available'),
('F154', 'Goreng-goreng', 'Nasi Goreng', 'Original', 'Nasi Goreng Pattaya', 8.50, 'Available'),
('F155', 'Goreng-goreng', 'Nasi Goreng', 'Daging', 'Nasi Goreng Pattaya', 9.50, 'Available'),
('F156', 'Goreng-goreng', 'Nasi Goreng', 'Udang', 'Nasi Goreng Pattaya', 11.50, 'Available'),
('F157', 'Goreng-goreng', 'Nasi Goreng', 'Sotong', 'Nasi Goreng Pattaya', 11.50, 'Available'),
('F158', 'Goreng-goreng', 'Nasi Goreng', 'Original', 'Nasi Goreng Tom Yam', 9.00, 'Available'),
('F159', 'Goreng-goreng', 'Nasi Goreng', 'Daging', 'Nasi Goreng Tom Yam', 10.00, 'Available'),
('F160', 'Goreng-goreng', 'Nasi Goreng', 'Udang', 'Nasi Goreng Tom Yam', 12.00, 'Available'),
('F161', 'Goreng-goreng', 'Nasi Goreng', 'Sotong', 'Nasi Goreng Tom Yam', 12.00, 'Available'),
('F162', 'Goreng-goreng', 'Nasi Goreng', 'Original', 'Nasi Goreng Belacan', 12.00, 'Available'),
('F163', 'Goreng-goreng', 'Nasi Goreng', 'Daging', 'Nasi Goreng Belacan', 13.00, 'Available'),
('F164', 'Goreng-goreng', 'Nasi Goreng', 'Udang', 'Nasi Goreng Belacan', 15.00, 'Available'),
('F165', 'Goreng-goreng', 'Nasi Goreng', 'Sotong', 'Nasi Goreng Belacan', 15.00, 'Available'),
('F166', 'Goreng-goreng', 'Mee Goreng', 'Original', 'Mee Goreng', 7.50, 'Available'),
('F167', 'Goreng-goreng', 'Mee Goreng', 'Daging', 'Mee Goreng', 8.50, 'Available'),
('F168', 'Goreng-goreng', 'Mee Goreng', 'Udang', 'Mee Goreng', 10.50, 'Available'),
('F169', 'Goreng-goreng', 'Mee Goreng', 'Sotong', 'Mee Goreng', 10.50, 'Available'),
('F170', 'Goreng-goreng', 'Mee Goreng', 'Original', 'Mee Hoon Goreng Singapore', 7.50, 'Available'),
('F171', 'Goreng-goreng', 'Mee Goreng', 'Daging', 'Mee Hoon Goreng Singapore', 8.50, 'Available'),
('F172', 'Goreng-goreng', 'Mee Goreng', 'Udang', 'Mee Hoon Goreng Singapore', 10.50, 'Available'),
('F173', 'Goreng-goreng', 'Mee Goreng', 'Sotong', 'Mee Hoon Goreng Singapore', 10.50, 'Available'),
('F174', 'Goreng-goreng', 'Mee Goreng', 'Original', 'Char Kuey Teow', 8.00, 'Available'),
('F175', 'Goreng-goreng', 'Mee Goreng', 'Daging', 'Char Kuey Teow', 9.00, 'Available'),
('F176', 'Goreng-goreng', 'Mee Goreng', 'Udang', 'Char Kuey Teow', 11.00, 'Available'),
('F177', 'Goreng-goreng', 'Mee Goreng', 'Sotong', 'Char Kuey Teow', 11.00, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--
DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `ID` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`ID`, `Password`, `Name`, `Username`, `Phone`, `Email`, `Address`) VALUES
('M01', 'p@ssword', 'Harry Parsons', 'Evilparsnips', '0123456778', 'harry1995@gmail.com', '38, Jalan ABC, Taman UTeM, 76100 Melaka');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS orders;
CREATE TABLE IF NOT EXISTS `orders` (
  `ID` int(255) NOT NULL,
  `Type` enum('online','walk-in') NOT NULL DEFAULT 'online',
  `Member_ID` varchar(255) NOT NULL,
  `Runner_ID` varchar(255) DEFAULT NULL,
  `Total_Amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `Order_Date` datetime NOT NULL DEFAULT current_timestamp(),
  `Ready_Date` datetime DEFAULT NULL,
  `PickedUp_Date` datetime DEFAULT NULL,
  `Delivered_Date` datetime DEFAULT NULL,
  `Payment_Method` enum('Cash on Delivery','Online Banking','E-Wallet') NOT NULL DEFAULT 'Cash on Delivery',
  `Status` enum('Cart','Order Placed','Readying Order','In Transit','Delivered','Completed') NOT NULL DEFAULT 'Cart'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `ID` int(255) NOT NULL,
  `Order_ID` int(255) NOT NULL,
  `foodID` varchar(255) DEFAULT NULL,
  `drinkID` varchar(255) DEFAULT NULL,
  `Type` enum('Hot','Cold') DEFAULT NULL,
  `addonID` varchar(255) DEFAULT NULL,
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
  `BirthDate` date NOT NULL,
  `Tel` varchar(20) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Platform` enum('Grab','Food Panda') NOT NULL,
  `Plate` varchar(10) NOT NULL,
  `Status` enum('Active', 'Inactive', 'Disabled') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `runners`
--

INSERT INTO `runners` (`ID`, `Password`, `Name`, `BirthDate`, `Tel`, `Email`, `Platform`, `Plate`, `Status`) VALUES
('R01', '$2y$10$J7eqvrcUYpPPjFDKfoYKy.waKLIqxertJC74KShL8m23YdEvrRApK', 'Alex Sambal', '1998-08-12', '+60 173576371', 'alex1998@gmail.com', 'Grab', 'MCQ7137', 'Active');

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
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`ID`, `Password`) VALUES
('S01', '$2y$10$FZaqf0XJs/sH1fyqTZWK2e1k8LTlaU75eXtnR51sYI/DZkb5z7ck6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`addonID`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `drinks`
--
ALTER TABLE `drinks`
  ADD PRIMARY KEY (`drinkID`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`foodID`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Username` (`Username`);

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
  ADD KEY `foodID` (`foodID`),
  ADD KEY `beverage` (`drinkID`),
  ADD KEY `addonID` (`addonID`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `member_id` FOREIGN KEY (`Member_ID`) REFERENCES `members` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `runner_id` FOREIGN KEY (`Runner_ID`) REFERENCES `runners` (`ID`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `addonID` FOREIGN KEY (`addonID`) REFERENCES `addons` (`addonID`) ON DELETE CASCADE,
  ADD CONSTRAINT `drinkID` FOREIGN KEY (`drinkID`) REFERENCES `drinks` (`drinkID`) ON DELETE CASCADE,
  ADD CONSTRAINT `foodID` FOREIGN KEY (`foodID`) REFERENCES `food` (`foodID`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderID` FOREIGN KEY (`Order_ID`) REFERENCES `orders` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
