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
-- Database: oms
--
DROP DATABASE IF EXISTS oms;
CREATE DATABASE IF NOT EXISTS oms DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE oms;
-- --------------------------------------------------------

--
-- Table structure for table admin
--
DROP TABLE IF EXISTS admin;
CREATE TABLE IF NOT EXISTS admin (
  ID varchar(255) NOT NULL,
  Password varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `beverage`
--
DROP TABLE IF EXISTS beverage;
CREATE TABLE IF NOT EXISTS beverage  (
  `beverageID` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `hotPrice` decimal(10,2) DEFAULT NULL,
  `coldPrice` decimal(10,2) NOT NULL,
  `Availability` enum('Available','Unavailable') NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beverage`
--

INSERT INTO `beverage` (`beverageID`, `Name`, `hotPrice`, `coldPrice`, `Availability`) VALUES
('B001', 'TEH O', 2.30, 2.50, 'Available'),
('B002', 'TEH TARIK', 2.50, 3.00, 'Available'),
('B003', 'TEH HALIA', 3.50, 4.00, 'Available'),
('B004', 'TEH SARBAT', 3.50, 4.00, 'Available'),
('B005', 'SIRAP', 2.00, 2.50, 'Available'),
('B006', 'SIRAP SELASIH', 2.50, 3.00, 'Available'),
('B007', 'SIRAP LIMAU', 2.70, 3.00, 'Available'),
('B008', 'SIRAP LAICI', 5.00, 5.50, 'Available'),
('B009', 'SIRAP BANDUNG', NULL, 3.50, 'Available'),
('B010', 'SIRAP BANDUNG CINCAU', NULL, 4.00, 'Available'),
('B011', 'SIRAP BANDUNG SODA', NULL, 4.00, 'Available'),
('B012', 'LIMAU', 2.70, 3.00, 'Available'),
('B013', 'ASAM BOY', 2.70, 3.00, 'Available'),
('B014', 'EXTRA JOSS SUSU (ANGGUR)', NULL, 4.00, 'Available'),
('B015', 'EXTRA JOSS SUSU (MANGA)', NULL, 4.00, 'Available'),
('B016', 'VICO', 3.00, 3.50, 'Available'),
('B017', 'JUS ORANGE', 4.70, 5.00, 'Available'),
('B018', 'JUS APPLE', 4.70, 5.00, 'Available'),
('B019', 'JUS WATERMELON', 4.70, 5.00, 'Available'),
('B020', 'JUS LYCHEE', 4.70, 5.00, 'Available'),
('B021', 'JUS LEMON', 4.70, 5.00, 'Available'),
('B022', 'CIKONG', NULL, 6.00, 'Available'),
('B023', 'AIS JELLY LIMAU', NULL, 6.00, 'Available'),
('B024', 'CENDOL', NULL, 6.00, 'Available'),
('B025', 'INDO CAFE O', 4.70, 5.00, 'Available'),
('B026', 'INDO CAFE SUSU', 4.70, 5.00, 'Available'),
('B027', 'KOPI TENGGEK', 4.70, 5.00, 'Available'),
('B028', 'KOPI SPECIAL', 4.70, 5.00, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--
DROP TABLE IF EXISTS food;
CREATE TABLE IF NOT EXISTS food (
  `foodID` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Availability` enum('Available','Unavailable') NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`foodID`, `Name`, `Price`, `Availability`) VALUES
('F001', 'Sup Gearbox Kambing', 19.00, 'Available'),
('F002', 'Sup Kambing', 20.00, 'Available'),
('F003', 'Sup Daging', 8.00, 'Available'),
('F004', 'Sup Ayam', 7.00, 'Available'),
('F005', 'Add On: Mee', 2.00, 'Available'),
('F006', 'Add On: Mee Hoon', 2.00, 'Available'),
('F007', 'Add On: Kuey Teow', 2.00, 'Available'),
('F008', 'Add On: Roti Francis', 2.50, 'Available'),
('F009', 'Add On: Roti Gardenia', 2.50, 'Available'),
('F010', 'Set Nasi Putih + Telur Dadar + Sambal + Ulaman', 5.00, 'Available'),
('F011', 'Mee Rebus Gearbox Kambing', 20.00, 'Available'),
('F012', 'Mee Rebus Daging', 9.50, 'Available'),
('F013', 'Mee Rebus Ayam', 9.00, 'Available'),
('F014', 'Lontong Kuah', 7.50, 'Available'),
('F015', 'Lontong Kering (Ayam)', 9.00, 'Available'),
('F016', 'Lontong Kering (Daging)', 9.50, 'Available'),
('F017', 'Nasi Lemak Basmathi (Telur)', 6.00, 'Available'),
('F018', 'Nasi Lemak Basmathi (Ayam)', 9.00, 'Available'),
('F019', 'Nasi Lemak Rendang (Ayam)', 8.50, 'Available'),
('F020', 'Nasi Lemak Rendang (Daging)', 9.50, 'Available'),
('F021', 'Nasi Ayam Basmathi', 12.00, 'Available'),
('F022', 'Nasi Ambang', 9.50, 'Available'),
('F023', 'Bubur Nasi', 7.50, 'Available'),
('F024', 'Bubur Ayam', 7.00, 'Available'),
('F025', 'Laksa (Johor)', 8.00, 'Available'),
('F026', 'Laksa (Penang)', 7.50, 'Available'),
('F027', 'Bakso (Mee)', 7.50, 'Available'),
('F028', 'Bakso (Mee Hoon)', 7.50, 'Available'),
('F029', 'Bakso (Nasi)', 7.50, 'Available'),
('F030', 'Soto (Mee)', 8.00, 'Available'),
('F031', 'Soto (Mee Hoon)', 8.00, 'Available'),
('F032', 'Soto (Nasi)', 8.00, 'Available'),
('F033', 'Roti Bakar', 2.50, 'Available'),
('F034', 'Roti Kaya', 3.50, 'Available'),
('F035', 'Roti Garlic', 3.50, 'Available'),
('F036', 'Add On: Telur 1/2 masak', 3.50, 'Available'),
('F037', 'Nasi Bawal Goreng Berlado', 9.00, 'Available'),
('F038', 'Nasi Siakap Goreng Berlado', 15.00, 'Available'),
('F039', 'Nasi Keli Goreng Berlado', 10.90, 'Available'),
('F040', 'Nasi Ayam Goreng Berlado', 8.50, 'Available'),
('F041', 'Bubur Ayam (Lunch)', 6.50, 'Available'),
('F042', 'Bubur Nasi (Lunch)', 7.50, 'Available'),
('F043', 'Bakso Mee/MeeHoon (Lunch)', 7.50, 'Available'),
('F044', 'Laksa Johor (Lunch)', 8.00, 'Available'),
('F045', 'Laksa Penang (Lunch)', 7.50, 'Available'),
('F046', 'Soto Mee/MeeHoon (Lunch)', 8.00, 'Available'),
('F047', 'Nasi Lemak Basmathi Telur (Lunch)', 6.00, 'Available'),
('F048', 'Nasi Lemak Basmathi Ayam (Lunch)', 9.00, 'Available'),
('F049', 'Roti Kosong', 1.50, 'Available'),
('F050', 'Roti Kosong Bawang', 2.00, 'Available'),
('F051', 'Roti Tampal', 2.80, 'Available'),
('F052', 'Roti Telur', 2.80, 'Available'),
('F053', 'Roti Telur Bawang', 3.50, 'Available'),
('F054', 'Roti Telur Double Jantan', 5.50, 'Available'),
('F055', 'Roti Pisang', 4.50, 'Available'),
('F056', 'Roti Sardin', 6.00, 'Available'),
('F057', 'Roti Bom', 2.50, 'Available'),
('F058', 'Roti Planta', 3.00, 'Available'),
('F059', 'Roti Sarang Burung Daging', 8.00, 'Available'),
('F060', 'Siakap Tiga Rasa', 35.00, 'Available'),
('F061', 'Siakap Masam Manis', 35.00, 'Available'),
('F062', 'Siakap Steam Lemon', 35.00, 'Available'),
('F063', 'Siakap Laprik', 35.00, 'Available'),
('F064', 'Siakap Goreng Kunyit', 35.00, 'Available'),
('F065', 'Siakap Bakar', 35.00, 'Available'),
('F066', 'Caru Bakar', 8.00, 'Available'),
('F067', 'Kerang Bakar', 15.00, 'Available'),
('F068', 'Sotong Bakar', 15.00, 'Available'),
('F069', 'Kailan (Biasa)', 7.00, 'Available'),
('F070', 'Kailan (Ikan Masin)', 7.00, 'Available'),
('F071', 'Kangkung (Biasa)', 7.00, 'Available'),
('F072', 'Kangkung (Belacan)', 7.00, 'Available'),
('F073', 'Taugeh (Biasa)', 7.00, 'Available'),
('F074', 'Taugeh (Ikan Masin)', 7.00, 'Available'),
('F075', 'Sawi (Biasa)', 7.00, 'Available'),
('F076', 'Sawi (Ikan Masin)', 7.00, 'Available'),
('F077', 'Cendawan Goreng Biasa', 7.00, 'Available'),
('F078', 'Ayam Black Pepper', 7.50, 'Available'),
('F079', 'Daging Black Pepper', 8.50, 'Available'),
('F080', 'Sotong Black Pepper', 9.50, 'Available'),
('F081', 'Ayam Sambal', 7.50, 'Available'),
('F082', 'Daging Sambal', 8.50, 'Available'),
('F083', 'Sotong Sambal', 9.50, 'Available'),
('F084', 'Ayam Merah', 7.50, 'Available'),
('F085', 'Daging Merah', 8.50, 'Available'),
('F086', 'Sotong Merah', 9.50, 'Available'),
('F087', 'Ayam Paprik', 7.50, 'Available'),
('F088', 'Daging Paprik', 8.50, 'Available'),
('F089', 'Sotong Paprik', 9.50, 'Available'),
('F090', 'Ayam Pha Khra Phao', 8.00, 'Available'),
('F091', 'Daging Pha Khra Phao', 9.00, 'Available'),
('F092', 'Ayam Kunyit', 7.50, 'Available'),
('F093', 'Daging Kunyit', 9.50, 'Available'),
('F094', 'Sotong Kunyit', 9.50, 'Available'),
('F095', 'Udang Kunyit', 9.50, 'Available'),
('F096', 'Add On: Nasi Putih', 2.00, 'Available'),
('F097', 'Add On: Nasi Goreng', 3.00, 'Available'),
('F098', 'Sotong Goreng Tepung', 10.50, 'Available'),
('F099', 'Udang Goreng Tepung', 10.50, 'Available'),
('F100', 'Cendawan Goreng Tepung', 7.00, 'Available'),
('F101', 'Inokki Goreng Tepung', 7.00, 'Available'),
('F102', 'Sup Ayam Ala Thai', 8.00, 'Available'),
('F103', 'Sup Daging Ala Thai', 9.00, 'Available'),
('F104', 'Add On: Mee (Sup Thai)', 2.00, 'Available'),
('F105', 'Add On: Mee Hoon (Sup Thai)', 3.00, 'Available'),
('F106', 'Add On: Kuey Teow (Sup Thai)', 3.00, 'Available'),
('F107', 'Tom Yam Ayam', 8.00, 'Available'),
('F108', 'Tom Yam Daging', 9.00, 'Available'),
('F109', 'Tom Yam Ayam + Daging', 12.00, 'Available'),
('F110', 'Tom Yam Seafood', 13.00, 'Available'),
('F111', 'Tom Yam Campur', 13.00, 'Available'),
('F112', 'Tom Yam Sayur', 8.00, 'Available'),
('F113', 'Tom Yam Cendawan', 8.00, 'Available'),
('F114', 'Add On: Mee (Tomyam)', 2.00, 'Available'),
('F115', 'Add On: Mee Hoon (Tomyam)', 3.00, 'Available'),
('F116', 'Add On: Kuey Teow (Tomyam)', 3.00, 'Available'),
('F117', 'Mee Bandung', 10.50, 'Available'),
('F118', 'Mee Hong Kong', 10.50, 'Available'),
('F119', 'Mee Hailam', 10.50, 'Available'),
('F120', 'Mee Kung Fu', 10.50, 'Available'),
('F121', 'Mee Hoon Bandung', 10.50, 'Available'),
('F122', 'Mee Hoon Hong Kong', 10.50, 'Available'),
('F123', 'Mee Hoon Hailam', 10.50, 'Available'),
('F124', 'Mee Hoon Kung Fu', 10.50, 'Available'),
('F125', 'Kuey Teow Bandung', 10.50, 'Available'),
('F126', 'Kuey Teow Hong Kong', 10.50, 'Available'),
('F127', 'Kuey Teow Hailam', 10.50, 'Available'),
('F128', 'Kuey Teow Kung Fu', 10.50, 'Available'),
('F129', 'Chicken Chop (Fried)', 18.50, 'Available'),
('F130', 'Chicken Chop (Grill)', 18.50, 'Available'),
('F131', 'Fish N Chips', 16.50, 'Available'),
('F132', 'Lamb Chop', 30.90, 'Available'),
('F133', 'Spaghetti Aglio Olio (Seafood)', 17.00, 'Available'),
('F134', 'Spaghetti Aglio Olio (Beef Bacon)', 15.00, 'Available'),
('F135', 'Spaghetti Aglio Olio (Chicken)', 13.00, 'Available'),
('F136', 'Spaghetti Carbonara (Seafood)', 18.00, 'Available'),
('F137', 'Spaghetti Carbonara (Beef Bacon)', 16.00, 'Available'),
('F138', 'Spaghetti Carbonara (Chicken)', 14.00, 'Available'),
('F139', 'Spaghetti Bolognesse', 15.00, 'Available'),
('F140', 'Mac & Cheese', 14.00, 'Available'),
('F141', 'Burger Smash Beef (Single)', 8.00, 'Available'),
('F142', 'Burger Smash Beef (Double)', 10.00, 'Available'),
('F143', 'Burger Crispy Chicken', 7.50, 'Available'),
('F144', 'Add On: Fries (Burger)', 2.00, 'Available'),
('F145', 'Side: Fries', 7.50, 'Available'),
('F146', 'Side: Nugget 8pcs', 8.00, 'Available'),
('F147', 'Side: Cheesy Wedges', 8.50, 'Available'),
('F148', 'Nasi Goreng Biasa (Original)', 7.50, 'Available'),
('F149', 'Nasi Goreng Biasa (Daging)', 8.50, 'Available'),
('F150', 'Nasi Goreng Biasa (Udang)', 10.50, 'Available'),
('F151', 'Nasi Goreng Biasa (Sotong)', 10.50, 'Available'),
('F152', 'Nasi Goreng Kampung (Original)', 8.00, 'Available'),
('F153', 'Nasi Goreng Kampung (Daging)', 9.00, 'Available'),
('F154', 'Nasi Goreng Kampung (Udang)', 11.00, 'Available'),
('F155', 'Nasi Goreng Kampung (Sotong)', 11.00, 'Available'),
('F156', 'Nasi Goreng Cina (Original)', 7.50, 'Available'),
('F157', 'Nasi Goreng Cina (Daging)', 8.50, 'Available'),
('F158', 'Nasi Goreng Cina (Udang)', 10.50, 'Available'),
('F159', 'Nasi Goreng Cina (Sotong)', 10.50, 'Available'),
('F160', 'Nasi Goreng Ikan Masin (Original)', 8.50, 'Available'),
('F161', 'Nasi Goreng Ikan Masin (Daging)', 9.50, 'Available'),
('F162', 'Nasi Goreng Ikan Masin (Udang)', 11.50, 'Available'),
('F163', 'Nasi Goreng Ikan Masin (Sotong)', 11.50, 'Available'),
('F164', 'Nasi Goreng Cili Padi (Original)', 8.50, 'Available'),
('F165', 'Nasi Goreng Cili Padi (Daging)', 9.50, 'Available'),
('F166', 'Nasi Goreng Cili Padi (Udang)', 11.50, 'Available'),
('F167', 'Nasi Goreng Cili Padi (Sotong)', 11.50, 'Available'),
('F168', 'Nasi Goreng Pattaya (Original)', 8.50, 'Available'),
('F169', 'Nasi Goreng Pattaya (Daging)', 9.50, 'Available'),
('F170', 'Nasi Goreng Pattaya (Udang)', 11.50, 'Available'),
('F171', 'Nasi Goreng Pattaya (Sotong)', 11.50, 'Available'),
('F172', 'Nasi Goreng Tom Yam (Original)', 9.00, 'Available'),
('F173', 'Nasi Goreng Tom Yam (Daging)', 10.00, 'Available'),
('F174', 'Nasi Goreng Tom Yam (Udang)', 12.00, 'Available'),
('F175', 'Nasi Goreng Tom Yam (Sotong)', 12.00, 'Available'),
('F176', 'Nasi Goreng Belacan (Original)', 12.00, 'Available'),
('F177', 'Nasi Goreng Belacan (Daging)', 13.00, 'Available'),
('F178', 'Nasi Goreng Belacan (Udang)', 15.00, 'Available'),
('F179', 'Nasi Goreng Belacan (Sotong)', 15.00, 'Available'),
('F180', 'Mee Goreng (Original)', 7.50, 'Available'),
('F181', 'Mee Goreng (Daging)', 8.50, 'Available'),
('F182', 'Mee Goreng (Udang)', 10.50, 'Available'),
('F183', 'Mee Goreng (Sotong)', 10.50, 'Available'),
('F184', 'Mee Hoon Goreng Singapore (Original)', 7.50, 'Available'),
('F185', 'Mee Hoon Goreng Singapore (Daging)', 8.50, 'Available'),
('F186', 'Mee Hoon Goreng Singapore (Udang)', 10.50, 'Available'),
('F187', 'Mee Hoon Goreng Singapore (Sotong)', 10.50, 'Available'),
('F188', 'Char Kuey Teow (Original)', 8.00, 'Available'),
('F189', 'Char Kuey Teow (Daging)', 9.00, 'Available'),
('F190', 'Char Kuey Teow (Udang)', 11.00, 'Available'),
('F191', 'Char Kuey Teow (Sotong)', 11.00, 'Available');

-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Table structure for table members
--

DROP TABLE IF EXISTS members;
CREATE TABLE IF NOT EXISTS members (
  ID varchar(255) NOT NULL,
  Password varchar(255) NOT NULL,
  Name varchar(255) NOT NULL,
  Phone int(11) NOT NULL,
  Email varchar(255) NOT NULL,
  Address varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table orders
--

DROP TABLE IF EXISTS orders;
CREATE TABLE IF NOT EXISTS orders (
  ID varchar(255) NOT NULL,
  Type enum('online','walk-in') NOT NULL DEFAULT 'online',
  Member_ID varchar(255) NOT NULL,
  Runner_ID varchar(255) DEFAULT NULL,
  Total_Amount decimal(10,2) NOT NULL,
  Order_Date datetime NOT NULL DEFAULT current_timestamp(),
  Ready_Date datetime DEFAULT NULL,
  PickedUp_Date datetime DEFAULT NULL,
  Delivered_Date datetime DEFAULT NULL,
  Status enum('Order Placed','Readying Order','Picked Up','In Transit','Delivered') NOT NULL DEFAULT 'Order Placed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table order_items
--

DROP TABLE IF EXISTS order_items;
CREATE TABLE IF NOT EXISTS order_items (
  ID varchar(255) NOT NULL,
  Order_ID varchar(255) NOT NULL,
  FoodID varchar(255)  DEFAULT NULL,
  beverageID varchar(255) DEFAULT NULL,
  Quantity int(4) NOT NULL,
  Subtotal decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table runners
--

DROP TABLE IF EXISTS runners;
CREATE TABLE IF NOT EXISTS runners (
  ID varchar(255) NOT NULL,
  Password varchar(255) NOT NULL,
  Name varchar(255) NOT NULL,
  Tel varchar(11) NOT NULL,
  Email varchar(255) NOT NULL,
  Platform enum('Grab','Food Panda') NOT NULL,
  Status enum('Active', 'Disabled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table staff
--

DROP TABLE IF EXISTS staff;
CREATE TABLE IF NOT EXISTS staff (
  ID varchar(255) NOT NULL,
  Password varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table admin
--
ALTER TABLE admin
  ADD PRIMARY KEY (ID);

--
-- Indexes for table beverage
--
ALTER TABLE `beverage`
  ADD PRIMARY KEY (`beverageID`);

--
-- Indexes for table food
--
ALTER TABLE food
  ADD PRIMARY KEY (foodID);

--
-- Indexes for table members
--
ALTER TABLE members
  ADD PRIMARY KEY (ID);

--
-- Indexes for table orders
--
ALTER TABLE orders
  ADD PRIMARY KEY (ID),
  ADD KEY member_id (Member_ID),
  ADD KEY runner_id (Runner_ID);

--
-- Indexes for table order_items
--
ALTER TABLE order_items
  ADD PRIMARY KEY (ID),
  ADD KEY order_id (Order_ID),
  ADD KEY item_id (Item_ID);

--
-- Indexes for table runners
--
ALTER TABLE runners
  ADD PRIMARY KEY (ID);

--
-- Indexes for table staff
--
ALTER TABLE staff
  ADD PRIMARY KEY (ID);

--
-- Constraints for dumped tables
--

--
-- Constraints for table orders
--
ALTER TABLE orders
  ADD CONSTRAINT member_id FOREIGN KEY (Member_ID) REFERENCES members (ID) ON DELETE CASCADE,
  ADD CONSTRAINT runner_id FOREIGN KEY (Runner_ID) REFERENCES runners (ID) ON DELETE CASCADE;

--
-- Constraints for table order_items
--
ALTER TABLE order_items
  ADD CONSTRAINT beverageID FOREIGN KEY (beverageID) REFERENCES beverage (beverageID) ON DELETE CASCADE;
  ADD CONSTRAINT foodID FOREIGN KEY (foodID) REFERENCES food (foodID) ON DELETE CASCADE,
  ADD CONSTRAINT order_id FOREIGN KEY (Order_ID) REFERENCES orders (ID) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
