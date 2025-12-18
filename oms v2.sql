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

-- --------------------------------------------------------


--
-- Table structure for table `food`
--
DROP TABLE IF EXISTS `food`;
CREATE TABLE IF NOT EXISTS `food` (
  `food_code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`food_code`, `name`, `price`, `is_available`) VALUES
('ac2add1', 'Mee (Add-on)', 2.00, 1),
('ac2add2', 'Mee Hoon (Add-on)', 2.00, 1),
('ac2add3', 'Kuey Teow (Add-on)', 2.00, 1),
('ac2qty1', 'Sup Ayam Ala Thai', 8.00, 1),
('ac2qty10', 'Tom Yam Seafood (Sideng)', 13.00, 1),
('ac2qty11', 'Tom Yam Campur (Seekaw)', 13.00, 1),
('ac2qty12', 'Tom Yam Campur (Sideng)', 13.00, 1),
('ac2qty13', 'Tom Yam Sayur (Seekaw)', 8.00, 1),
('ac2qty14', 'Tom Yam Sayur (Sideng)', 8.00, 1),
('ac2qty15', 'Tom Yam Cendawan (Seekaw)', 8.00, 1),
('ac2qty16', 'Tom Yam Cendawan (Sideng)', 8.00, 1),
('ac2qty17', 'Bandung (Mee)', 10.50, 1),
('ac2qty18', 'Bandung (Mee Hoon)', 10.50, 1),
('ac2qty19', 'Bandung (Kuey Teow)', 10.50, 1),
('ac2qty2', 'Sup Daging Ala Thai', 9.00, 1),
('ac2qty20', 'Hong Kong (Mee)', 10.50, 1),
('ac2qty21', 'Hong Kong (Mee Hoon)', 10.50, 1),
('ac2qty22', 'Hong Kong (Kuey Teow)', 10.50, 1),
('ac2qty23', 'Hailam (Mee)', 10.50, 1),
('ac2qty24', 'Hailam (Mee Hoon)', 10.50, 1),
('ac2qty25', 'Hailam (Kuey Teow)', 10.50, 1),
('ac2qty26', 'Kung Fu (Mee)', 10.50, 1),
('ac2qty27', 'Kung Fu (Mee Hoon)', 10.50, 1),
('ac2qty28', 'Kung Fu (Kuey Teow)', 10.50, 1),
('ac2qty3', 'Tom Yam Ayam (Seekaw)', 8.00, 1),
('ac2qty4', 'Tom Yam Ayam (Sideng)', 8.00, 1),
('ac2qty5', 'Tom Yam Daging (Seekaw)', 9.00, 1),
('ac2qty6', 'Tom Yam Daging (Sideng)', 9.00, 1),
('ac2qty7', 'Tom Yam Ayam+Daging (Seekaw)', 12.00, 1),
('ac2qty8', 'Tom Yam Ayam+Daging (Sideng)', 12.00, 1),
('ac2qty9', 'Tom Yam Seafood (Seekaw)', 13.00, 1),
('acadd1', 'Nasi Putih', 2.00, 1),
('acadd2', 'Nasi Goreng', 3.00, 1),
('acqty1', 'Kailan (Biasa)', 7.00, 1),
('acqty10', 'Ayam Black Pepper', 7.50, 1),
('acqty11', 'Daging Black Pepper', 8.50, 1),
('acqty12', 'Sotong Black Pepper', 9.50, 1),
('acqty13', 'Ayam Sambal', 7.50, 1),
('acqty14', 'Daging Sambal', 8.50, 1),
('acqty15', 'Sotong Sambal', 9.50, 1),
('acqty16', 'Ayam Merah', 7.50, 1),
('acqty17', 'Daging Merah', 8.50, 1),
('acqty18', 'Sotong Merah', 9.50, 1),
('acqty19', 'Ayam Paprik', 7.50, 1),
('acqty2', 'Kailan (Ikan Masin)', 7.00, 1),
('acqty20', 'Daging Paprik', 8.50, 1),
('acqty21', 'Sotong Paprik', 9.50, 1),
('acqty22', 'Ayam Pha Khra Phao', 8.00, 1),
('acqty23', 'Daging Pha Khra Phao', 9.00, 1),
('acqty24', 'Ayam Kunyit', 7.50, 1),
('acqty25', 'Daging Kunyit', 9.50, 1),
('acqty26', 'Sotong Kunyit', 9.50, 1),
('acqty27', 'Udang Kunyit', 9.50, 1),
('acqty28', 'Sotong', 10.50, 1),
('acqty29', 'Udang', 10.50, 1),
('acqty3', 'Kangkung (Biasa)', 7.00, 1),
('acqty30', 'Cendawan', 7.00, 1),
('acqty31', 'Inokki', 7.00, 1),
('acqty4', 'Kangkung (Belacan)', 7.00, 1),
('acqty5', 'Taugeh (Biasa)', 7.00, 1),
('acqty6', 'Taugeh (Ikan Masin)', 7.00, 1),
('acqty7', 'Sawi (Biasa)', 7.00, 1),
('acqty8', 'Sawi (Ikan Masin)', 7.00, 1),
('acqty9', 'Cendawan Goreng Biasa', 7.00, 1),
('badd1', 'Telur 1/2 masak', 3.50, 1),
('bqty1', 'Lontong Kuah', 7.50, 1),
('bqty10', 'Bubur Nasi', 7.50, 1),
('bqty11', 'Bubur Ayam', 7.00, 1),
('bqty12', 'Laksa (Johor)', 8.00, 1),
('bqty13', 'Laksa (Penang)', 7.50, 1),
('bqty14', 'Bakso (Mee)', 7.50, 1),
('bqty15', 'Bakso (Mee Hoon)', 7.50, 1),
('bqty16', 'Bakso (Nasi)', 7.50, 1),
('bqty17', 'Soto (Mee)', 8.00, 1),
('bqty18', 'Soto (Mee Hoon)', 8.00, 1),
('bqty19', 'Soto (Nasi)', 8.00, 1),
('bqty2', 'Lontong Kering (Ayam)', 9.00, 1),
('bqty20', 'Roti Bakar', 2.50, 1),
('bqty21', 'Roti Kaya', 3.50, 1),
('bqty22', 'Roti Garlic', 3.50, 1),
('bqty3', 'Lontong Kering (Daging)', 9.50, 1),
('bqty4', 'Nasi Lemak Basmathi (Telur)', 6.00, 1),
('bqty5', 'Nasi Lemak Basmathi (Ayam)', 9.00, 1),
('bqty6', 'Nasi Lemak Rendang (Ayam)', 8.50, 1),
('bqty7', 'Nasi Lemak Rendang (Daging)', 9.50, 1),
('bqty8', 'Nasi Ayam Basmathi', 12.00, 1),
('bqty9', 'Nasi Ambang', 9.50, 1),
('dqty1', 'Teh O (Hot)', 2.30, 1),
('dqty10', 'Sirap (Cold)', 2.50, 1),
('dqty11', 'Sirap Selasih (Hot)', 2.50, 1),
('dqty12', 'Sirap Selasih (Cold)', 3.00, 1),
('dqty13', 'Sirap Limau (Hot)', 2.70, 1),
('dqty14', 'Sirap Limau (Cold)', 3.00, 1),
('dqty15', 'Sirap Laici (Hot)', 5.00, 1),
('dqty16', 'Sirap Laici (Cold)', 5.50, 1),
('dqty17', 'Sirap Bandung (Cold)', 3.50, 1),
('dqty18', 'Sirap Bandung Cincau (Cold)', 4.00, 1),
('dqty19', 'Sirap Bandung Soda (Cold)', 4.00, 1),
('dqty2', 'Teh O (Cold)', 2.50, 1),
('dqty20', 'Limau (Hot)', 2.70, 1),
('dqty21', 'Limau (Cold)', 3.00, 1),
('dqty22', 'Asam Boy (Hot)', 2.70, 1),
('dqty23', 'Asam Boy (Cold)', 3.00, 1),
('dqty24', 'Extra Joss Susu Anggur', 4.00, 1),
('dqty25', 'Extra Joss Susu Manga', 4.00, 1),
('dqty26', 'Vico (Hot)', 3.00, 1),
('dqty27', 'Vico (Cold)', 3.50, 1),
('dqty28', 'Orange Juice (Hot)', 4.70, 1),
('dqty29', 'Orange Juice (Cold)', 5.00, 1),
('dqty3', 'Teh Tarik (Hot)', 2.50, 1),
('dqty30', 'Apple Juice (Hot)', 4.70, 1),
('dqty31', 'Apple Juice (Cold)', 5.00, 1),
('dqty32', 'Watermelon Juice (Hot)', 4.70, 1),
('dqty33', 'Watermelon Juice (Cold)', 5.00, 1),
('dqty34', 'Lychee Juice (Hot)', 4.70, 1),
('dqty35', 'Lychee Juice (Cold)', 5.00, 1),
('dqty36', 'Lemon Juice (Hot)', 4.70, 1),
('dqty37', 'Lemon Juice (Cold)', 5.00, 1),
('dqty38', 'Cikong', 6.00, 1),
('dqty39', 'Ais Jelly Limau', 6.00, 1),
('dqty4', 'Teh Tarik (Cold)', 3.00, 1),
('dqty40', 'Cendol', 6.00, 1),
('dqty41', 'Indo Cafe O (Hot)', 4.70, 1),
('dqty42', 'Indo Cafe O (Cold)', 5.00, 1),
('dqty43', 'Indo Cafe Susu (Hot)', 4.70, 1),
('dqty44', 'Indo Cafe Susu (Cold)', 5.00, 1),
('dqty45', 'Kopi Tenggek (Hot)', 4.70, 1),
('dqty46', 'Kopi Tenggek (Cold)', 5.00, 1),
('dqty47', 'Kopi Special (Hot)', 4.70, 1),
('dqty48', 'Kopi Special (Cold)', 5.00, 1),
('dqty5', 'Teh Halia (Hot)', 3.50, 1),
('dqty6', 'Teh Halia (Cold)', 4.00, 1),
('dqty7', 'Teh Sarbat (Hot)', 3.50, 1),
('dqty8', 'Teh Sarbat (Cold)', 4.00, 1),
('dqty9', 'Sirap (Hot)', 2.00, 1),
('gqty1', 'Nasi Goreng Biasa', 7.50, 1),
('gqty10', 'Nasi Goreng Cina (Daging)', 8.50, 1),
('gqty11', 'Nasi Goreng Cina (Udang)', 10.50, 1),
('gqty12', 'Nasi Goreng Cina (Sotong)', 10.50, 1),
('gqty13', 'Nasi Goreng Ikan Masin', 8.50, 1),
('gqty14', 'Nasi Goreng Ikan Masin (Daging)', 9.50, 1),
('gqty15', 'Nasi Goreng Ikan Masin (Udang)', 11.50, 1),
('gqty16', 'Nasi Goreng Ikan Masin (Sotong)', 11.50, 1),
('gqty17', 'Nasi Goreng Cili Padi', 8.50, 1),
('gqty18', 'Nasi Goreng Cili Padi (Daging)', 9.50, 1),
('gqty19', 'Nasi Goreng Cili Padi (Udang)', 11.50, 1),
('gqty2', 'Nasi Goreng Biasa (Daging)', 8.50, 1),
('gqty20', 'Nasi Goreng Cili Padi (Sotong)', 11.50, 1),
('gqty21', 'Nasi Goreng Pattaya', 8.50, 1),
('gqty22', 'Nasi Goreng Pattaya (Daging)', 9.50, 1),
('gqty23', 'Nasi Goreng Pattaya (Udang)', 11.50, 1),
('gqty24', 'Nasi Goreng Pattaya (Sotong)', 11.50, 1),
('gqty25', 'Nasi Goreng Tom Yam', 9.00, 1),
('gqty26', 'Nasi Goreng Tom Yam (Daging)', 10.00, 1),
('gqty27', 'Nasi Goreng Tom Yam (Udang)', 12.00, 1),
('gqty28', 'Nasi Goreng Tom Yam (Sotong)', 12.00, 1),
('gqty29', 'Nasi Goreng Belacan', 12.00, 1),
('gqty3', 'Nasi Goreng Biasa (Udang)', 10.50, 1),
('gqty30', 'Nasi Goreng Belacan (Daging)', 13.00, 1),
('gqty31', 'Nasi Goreng Belacan (Udang)', 15.00, 1),
('gqty32', 'Nasi Goreng Belacan (Sotong)', 15.00, 1),
('gqty33', 'Mee Goreng', 7.50, 1),
('gqty34', 'Mee Goreng (Daging)', 8.50, 1),
('gqty35', 'Mee Goreng (Udang)', 10.50, 1),
('gqty36', 'Mee Goreng (Sotong)', 10.50, 1),
('gqty37', 'Mee Hoon Goreng Singapore', 7.50, 1),
('gqty38', 'Mee Hoon Goreng Singapore (Daging)', 8.50, 1),
('gqty39', 'Mee Hoon Goreng Singapore (Udang)', 10.50, 1),
('gqty4', 'Nasi Goreng Biasa (Sotong)', 10.50, 1),
('gqty40', 'Mee Hoon Goreng Singapore (Sotong)', 10.50, 1),
('gqty41', 'Char Kuey Teow', 8.00, 1),
('gqty42', 'Char Kuey Teow (Daging)', 9.00, 1),
('gqty43', 'Char Kuey Teow (Udang)', 11.00, 1),
('gqty44', 'Char Kuey Teow (Sotong)', 11.00, 1),
('gqty5', 'Nasi Goreng Kampung', 8.00, 1),
('gqty6', 'Nasi Goreng Kampung (Daging)', 9.00, 1),
('gqty7', 'Nasi Goreng Kampung (Udang)', 11.00, 1),
('gqty8', 'Nasi Goreng Kampung (Sotong)', 11.00, 1),
('gqty9', 'Nasi Goreng Cina', 7.50, 1),
('iqty1', 'Tiga Rasa', 40.00, 1),
('iqty2', 'Masam Manis', 40.00, 1),
('iqty3', 'Steam Lemon', 40.00, 1),
('iqty4', 'Laprik', 40.00, 1),
('iqty5', 'Goreng Kunyit', 40.00, 1),
('iqty6', 'Siakap Bakar', 40.00, 1),
('iqty7', 'Caru Bakar', 15.00, 1),
('iqty8', 'Kerang Bakar', 15.00, 1),
('iqty9', 'Sotong Bakar', 15.00, 1),
('lqty1', 'Nasi Bawal Goreng Berlado', 9.00, 1),
('lqty10', 'Laksa (Penang)', 7.50, 1),
('lqty11', 'Soto (Mee)', 8.00, 1),
('lqty12', 'Soto (Mee Hoon)', 8.00, 1),
('lqty13', 'Nasi Lemak Basmathi (Telur)', 6.00, 1),
('lqty14', 'Nasi Lemak Basmathi (Ayam)', 9.00, 1),
('lqty2', 'Nasi Siakap Goreng Berlado', 15.00, 1),
('lqty3', 'Nasi Keli Goreng Berlado', 10.90, 1),
('lqty4', 'Nasi Ayam Goreng Berlado', 8.50, 1),
('lqty5', 'Bubur Ayam', 6.50, 1),
('lqty6', 'Bubur Nasi', 7.50, 1),
('lqty7', 'Bakso (Mee)', 7.50, 1),
('lqty8', 'Bakso (Mee Hoon)', 7.50, 1),
('lqty9', 'Laksa (Johor)', 8.00, 1),
('rqty1', 'Roti Kosong', 1.50, 1),
('rqty10', 'Roti Planta', 3.00, 1),
('rqty11', 'Roti Sarang Burung Daging', 8.00, 1),
('rqty2', 'Roti Kosong Bawang', 2.00, 1),
('rqty3', 'Roti Tampal', 2.80, 1),
('rqty4', 'Roti Telur', 2.50, 1),
('rqty5', 'Roti Telur Bawang', 3.50, 1),
('rqty6', 'Roti Telur Double Jantan', 5.50, 1),
('rqty7', 'Roti Pisang', 4.50, 1),
('rqty8', 'Roti Sardin', 6.00, 1),
('rqty9', 'Roti Bom', 2.50, 1),
('sadd1', 'Mee', 2.00, 1),
('sadd2', 'Mee Hoon', 2.00, 1),
('sadd3', 'Kuey Teow', 2.00, 1),
('sadd4', 'Roti Francis', 2.50, 1),
('sadd5', 'Roti Gardenia', 2.50, 1),
('saddSet1', 'Nasi Putih Set', 5.00, 1),
('sqty1', 'Sup Gearbox Kambing', 19.00, 1),
('sqty2', 'Sup Kambing', 20.00, 1),
('sqty3', 'Sup Daging', 8.00, 1),
('sqty4', 'Sup Ayam', 7.00, 1),
('sqty5', 'Mee Rebus Gearbox Kambing', 20.00, 1),
('sqty6', 'Mee Rebus Daging', 9.50, 1),
('sqty7', 'Mee Rebus Ayam', 9.00, 1),
('wadd1', 'Fries (Add-on)', 2.00, 1),
('wqty1', 'Chicken Chop (Fried)', 18.50, 1),
('wqty10', 'Carbonara (Chicken)', 14.00, 1),
('wqty11', 'Bolognesse', 15.00, 1),
('wqty12', 'Mac & Cheese', 14.00, 1),
('wqty13', 'Smash Beef (Single)', 8.00, 1),
('wqty14', 'Smash Beef (Double)', 10.00, 1),
('wqty15', 'Crispy Chicken Burger', 7.50, 1),
('wqty16', 'Fries (Side)', 7.50, 1),
('wqty17', 'Nugget 8pcs', 8.00, 1),
('wqty18', 'Cheesy Wedges', 8.50, 1),
('wqty2', 'Chicken Chop (Grill)', 18.50, 1),
('wqty3', 'Fish N Chips', 16.50, 1),
('wqty4', 'Lamb Chop', 30.90, 1),
('wqty5', 'Aglio Olio (Seafood)', 17.00, 1),
('wqty6', 'Aglio Olio (Beef Bacon)', 15.00, 1),
('wqty7', 'Aglio Olio (Chicken)', 13.00, 1),
('wqty8', 'Carbonara (Seafood)', 18.00, 1),
('wqty9', 'Carbonara (Beef Bacon)', 16.00, 1);


-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Table structure for table customer
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `full_name`, `username`, `email`, `phone_number`, `password`, `address`) VALUES
(2, 'Test2', 'test', 'aa@a.a', NULL, '123', 'saxdwadawdaw'),
(4, 'FUCKER', 'FUCKER', 'fucker@fucker.fucker', NULL, 'fucker', NULL),
(5, 'aa', 'aa', 'aa@gmail.com', 'ss', '123', ''),
(6, 'aa', 'aaa', 'aaa@gmaicl.com', 'aa', 'aa', NULL),
(7, 'haha', 'haha', 'haha@haha.haha', '2', '123', '4ss');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--
DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'cart',
  `delivery_status` enum('Preparing','Delivering','Delivered','Completed') DEFAULT 'Preparing',
  `delivery_address` text DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `payment_method` varchar(20) DEFAULT NULL,
  `driver_name` varchar(50) DEFAULT NULL,
  `driver_plate` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `username`, `order_date`, `status`, `delivery_status`, `delivery_address`, `total_amount`, `payment_method`, `driver_name`, `driver_plate`) VALUES
(1, 'haha', '2025-12-17 13:02:01', 'purchased', 'Completed', '456', 15.00, 'cash', NULL, NULL),
(2, 'haha', '2025-12-17 13:07:06', 'purchased', 'Preparing', '4ss', 6.00, 'online', NULL, NULL),
(3, 'haha', '2025-12-17 13:37:42', 'purchased', 'Preparing', '4ss', 89.00, 'cash', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `food_code` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `food_code`, `quantity`) VALUES
(1, 1, 'sqty4', 1),
(2, 1, 'bqty19', 1),
(4, 2, 'rqty8', 1),
(5, 3, 'ac2qty4', 4),
(6, 3, 'wqty5', 1),
(7, 3, 'iqty1', 1);


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
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`food_code`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
  
--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
