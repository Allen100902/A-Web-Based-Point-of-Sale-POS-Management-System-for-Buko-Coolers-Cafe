-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2026 at 02:26 AM
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
-- Database: `u325660191_buko_coolers`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inventory`
--

CREATE TABLE `tbl_inventory` (
  `PK_INVENTORY_ITEM` int(11) NOT NULL,
  `INVENTORY_ITEM_CODE` varchar(10) NOT NULL,
  `INVENTORY_ITEM_NAME` varchar(80) NOT NULL,
  `INVENTORY_ITEM_AMOUNT` float NOT NULL,
  `INVENTORY_ITEM_UNITS` varchar(80) NOT NULL,
  `INVENTORY_ITEM_STOCK_MIN` int(11) NOT NULL DEFAULT 20,
  `INVENTORY_ITEM_COG` int(11) NOT NULL,
  `INVENTORY_ITEM_EXP` date NOT NULL,
  `INVENTORY_ITEM_TIMESTAMP` datetime NOT NULL DEFAULT current_timestamp(),
  `STATUS_INVENTORY_ITEM` varchar(25) NOT NULL DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_inventory`
--

INSERT INTO `tbl_inventory` (`PK_INVENTORY_ITEM`, `INVENTORY_ITEM_CODE`, `INVENTORY_ITEM_NAME`, `INVENTORY_ITEM_AMOUNT`, `INVENTORY_ITEM_UNITS`, `INVENTORY_ITEM_STOCK_MIN`, `INVENTORY_ITEM_COG`, `INVENTORY_ITEM_EXP`, `INVENTORY_ITEM_TIMESTAMP`, `STATUS_INVENTORY_ITEM`) VALUES
(1, 'RAW001', 'Buko Meat', 30, 'Grams', 20, 65, '2025-09-05', '2025-09-04 22:00:01', 'ARCHIVED'),
(2, 'PAST001', 'TEST', 30, 'Piece', 20, 69, '2025-09-05', '2025-09-04 22:01:47', 'ARCHIVED'),
(3, 'RAW001', 'Buko Meat', 20, 'Grams', 25, 30, '2025-09-06', '2025-09-07 11:47:35', 'ARCHIVED'),
(4, 'RAW001', 'Buko Meat', 120, 'Grams', 20, 150, '2025-09-07', '2025-09-07 11:47:35', 'ARCHIVED'),
(5, 'RAW002', 'Buko Juice', 910, 'Mililiters', 20, 200, '2025-09-06', '2025-09-07 11:47:35', 'ARCHIVED'),
(6, 'RAW001', 'Buko Meat', 430, 'Grams', 20, 130, '2025-09-10', '2025-09-10 00:45:56', 'ARCHIVED'),
(7, 'RAW001', 'Buko Meat', 0, 'Grams', 20, 30, '2025-09-09', '2025-09-07 12:01:01', 'ARCHIVED'),
(8, 'RAW002', 'Buko Juice', 1100, 'Mililiters', 20, 230, '2025-09-10', '2025-09-10 00:45:56', 'ARCHIVED'),
(9, 'RAW001', 'Buko Meat', 30, 'Grams', 20, 65, '2025-09-11', '2025-09-08 11:07:07', 'ARCHIVED'),
(10, 'RAW001', 'Buko Meat', 475, 'Grams', 20, 170, '2025-09-12', '2025-09-14 12:31:17', 'ARCHIVED'),
(11, 'RAW002', 'Buko Juice', 281, 'Mililiters', 20, 135, '2025-09-12', '2025-09-14 12:31:17', 'ARCHIVED'),
(12, 'PAST00052', 'TEST', 5, 'Piece', 20, 15, '2025-09-15', '2025-09-15 04:22:13', 'ARCHIVED'),
(13, 'RAW002', 'Buko Juice', 503, 'Mililiters', 20, 23, '2025-09-15', '2025-09-15 04:22:13', 'ARCHIVED'),
(14, 'RAW001', 'Buko Meat', 275, 'Grams', 20, 30, '2025-09-15', '2025-09-15 04:22:13', 'ARCHIVED'),
(15, 'RAW001', 'Buko Meat', 1215, 'Grams', 20, 320, '2025-09-18', '2025-09-18 05:31:15', 'ARCHIVED'),
(16, 'RAW002', 'Buko Juice', 1685, 'Mililiters', 20, 300, '2025-09-18', '2025-09-18 05:31:15', 'ARCHIVED'),
(17, 'RAW001', 'Buko Meat', 500, 'Grams', 20, 155, '2025-09-19', '2025-09-20 05:16:36', 'ARCHIVED'),
(18, 'RAW002', 'Buko Juice', 650, 'Grams', 20, 180, '2025-09-19', '2025-09-15 12:27:40', 'ARCHIVED'),
(19, 'RAW002', 'Buko Juice', 650, 'Mililiters', 20, 180, '2025-09-19', '2025-09-20 05:16:36', 'ARCHIVED'),
(20, 'RAW003', 'Ice Cream Cone', 25, 'Piece', 20, 150, '2025-09-24', '2025-09-29 23:41:00', 'ARCHIVED'),
(21, 'RAW004', 'Coconut Ice Cream', 1425, 'Mililiters', 20, 450, '2025-09-25', '2025-09-29 23:41:00', 'ARCHIVED'),
(22, 'RAW005', 'Coconut Powder', 859, 'Grams', 20, 1000, '2026-01-20', '2026-01-21 12:20:29', 'ARCHIVED'),
(23, 'RAW006', 'Thai Coffee', 859, 'Grams', 20, 1000, '2026-01-20', '2026-01-21 12:20:30', 'ARCHIVED'),
(24, 'RAW001', 'Buko Meat', 475, 'Grams', 20, 130, '2025-09-21', '2025-09-21 03:27:43', 'ARCHIVED'),
(25, 'RAW002', 'Buko Juice', 35, 'Mililiters', 20, 85, '2025-09-21', '2025-09-21 03:27:43', 'ARCHIVED'),
(26, 'RAW002', 'Buko Juice', 400, 'Mililiters', 20, 110, '2025-09-22', '2025-09-22 05:56:45', 'ARCHIVED'),
(27, 'PAST001', 'Chocolate Muffin', 2, 'Piece', 20, 300, '2025-09-23', '2025-09-29 23:41:00', 'ARCHIVED'),
(28, 'PAST002', 'Chocolate Cookies', 12, 'Piece', 20, 450, '2025-09-24', '2025-09-29 23:41:00', 'ARCHIVED'),
(29, 'PAST003', 'Chocolate Donut', 0, 'Piece', 20, 800, '2025-09-26', '2025-09-20 07:07:48', 'ARCHIVED'),
(30, 'RAW007', 'Coconut Milk', 1000, 'Mililiters', 20, 175, '2025-12-19', '2025-09-20 06:33:42', 'ARCHIVED'),
(31, 'RAW008', 'Coconut Cream', 500, 'Mililiters', 20, 285, '2025-10-19', '2025-09-20 06:33:49', 'ARCHIVED'),
(32, 'RAW009', 'Syrup', 350, 'Mililiters', 20, 210, '2025-10-04', '2025-09-20 06:30:40', 'ARCHIVED'),
(33, 'RAW010', 'Espresso', 1500, 'Mililiters', 20, 660, '2025-11-09', '2025-09-20 06:34:14', 'ARCHIVED'),
(34, 'RAW012', 'Milk', 1000, 'Mililiters', 20, 260, '2025-12-19', '2025-09-20 06:34:09', 'ARCHIVED'),
(35, 'RAW009', 'Sugar', 1200, 'Grams', 20, 180, '2025-12-19', '2025-09-20 06:33:59', 'ARCHIVED'),
(36, 'RAW007', 'Espresso', 865, 'Mililiters', 20, 750, '2025-10-20', '2025-09-21 03:40:42', 'ARCHIVED'),
(37, 'RAW008', 'Matcha Powder', 494, 'Grams', 20, 310, '2025-11-20', '2025-09-21 03:40:47', 'ARCHIVED'),
(38, 'RAW009', 'Milk', 1289, 'Mililiters', 20, 550, '2025-10-20', '2025-09-21 03:40:44', 'ARCHIVED'),
(39, 'RAW007', 'Espresso', 1000, 'Mililiters', 20, 550, '2025-10-20', '2025-09-21 03:49:53', 'ARCHIVED'),
(40, 'RAW007', 'Espresso', 1000, 'Grams', 20, 550, '2025-10-20', '2025-09-21 03:55:49', 'ARCHIVED'),
(41, 'RAW008', 'Matcha Powder', 500, 'Grams', 20, 400, '2025-10-20', '2025-09-21 03:55:52', 'ARCHIVED'),
(42, 'RAW009', 'Milk', 2000, 'Mililiters', 20, 850, '2025-12-20', '2025-09-21 03:55:55', 'ARCHIVED'),
(43, 'RAW007', 'Espresso', 775, 'Mililiters', 20, 550, '2025-10-20', '2025-11-11 09:59:01', 'ARCHIVED'),
(44, 'RAW008', 'Matcha Powder', 490, 'Grams', 20, 320, '2025-10-20', '2025-11-11 09:59:03', 'ARCHIVED'),
(45, 'RAW009', 'Milk', 0, 'Mililiters', 20, 800, '2025-11-20', '2025-09-29 23:52:39', 'ARCHIVED'),
(46, 'RAW001', 'Buko Meat', 465, 'Grams', 20, 240, '2025-09-27', '2025-09-29 23:41:00', 'ARCHIVED'),
(47, 'RAW002', 'Buko Juice', 685, 'Mililiters', 20, 250, '2025-09-25', '2025-09-29 23:41:00', 'ARCHIVED'),
(48, 'RAW010', 'Sugar', 984, 'Grams', 20, 180, '2026-01-21', '2026-01-25 11:46:57', 'ARCHIVED'),
(49, 'RAW001', 'Buko Meat', 990, 'Grams', 20, 360, '2025-10-03', '2025-10-05 15:49:21', 'ARCHIVED'),
(50, 'RAW002', 'Buko Juice', 1410, 'Mililiters', 20, 300, '2025-10-02', '2025-10-05 15:49:19', 'ARCHIVED'),
(51, 'RAW004', 'Coconut Ice Cream', 850, 'Mililiters', 20, 500, '2025-10-11', '2025-11-11 09:58:59', 'ARCHIVED'),
(52, 'RAW003', 'Ice Cream Cone', 0, 'Piece', 20, 80, '2025-10-11', '2025-09-30 08:18:16', 'ARCHIVED'),
(53, 'PAST001', 'Chocolate Muffin', 47, 'Piece', 48, 750, '2025-10-05', '2025-11-11 10:00:09', 'ARCHIVED'),
(54, 'PAST002', 'Chocolate Cookies', 48, 'Piece', 40, 700, '2025-10-07', '2025-11-11 10:00:10', 'ARCHIVED'),
(55, 'PAST003', 'Chocolate Donut', 33, 'Piece', 20, 430, '2025-10-03', '2025-10-06 08:53:44', 'ARCHIVED'),
(56, 'RAW009', 'Milk', 1863, 'Mililiters', 20, 470, '2026-03-27', '2025-11-16 12:47:14', 'ACTIVE'),
(57, 'RAW002', 'Buko Juice', 1600, 'Mililiters', 20, 350, '2025-10-03', '2025-10-06 08:53:44', 'ARCHIVED'),
(58, 'RAW010', 'Sugar', 3000, 'Grams', 20, 1500, '2025-10-07', '2025-11-11 09:58:57', 'ARCHIVED'),
(59, 'RAW001', 'Buko Meat', 2000, 'Mililiters', 20, 2500, '2025-10-05', '2025-11-11 09:58:55', 'ARCHIVED'),
(60, 'RAW001', 'Buko Meat', 485, 'Grams', 20, 230, '2025-11-19', '2025-12-14 07:10:10', 'ARCHIVED'),
(61, 'RAW002', 'Buko Juice', 865, 'Mililiters', 20, 235, '2025-11-19', '2025-12-14 07:10:12', 'ARCHIVED'),
(62, 'RAW003', 'Ice Cream Cone', 17, 'Piece', 20, 150, '2025-11-22', '2025-12-14 07:10:17', 'ARCHIVED'),
(63, 'RAW004', 'Coconut Ice Cream', 1255, 'Mililiters', 20, 500, '2025-11-22', '2025-12-14 07:10:15', 'ARCHIVED'),
(64, 'RAW003', 'Ice Cream Cone', 0, 'Piece', 20, 75, '2025-11-16', '2025-12-14 07:10:05', 'ARCHIVED'),
(65, 'RAW004', 'Coconut Ice Cream', 0, 'Mililiters', 20, 50, '2025-11-16', '2025-12-14 07:10:07', 'ARCHIVED'),
(66, 'RAW007', 'Espresso', 1000, 'Mililiters', 20, 325, '2025-12-15', '2026-01-21 12:20:19', 'ARCHIVED'),
(67, 'RAW008', 'Matcha Powder', 500, 'Grams', 20, 360, '2026-02-15', '2025-11-16 12:34:18', 'ACTIVE'),
(68, 'PAST001', 'Chocolate Muffin', 19, 'Piece', 20, 500, '2025-11-19', '2025-12-14 07:13:49', 'ARCHIVED'),
(69, 'PAST002', 'Chocolate Cookies', 30, 'Piece', 20, 285, '2025-11-22', '2025-12-14 07:13:51', 'ARCHIVED'),
(70, 'PAST003', 'Chocolate Donut', 21, 'Piece', 20, 300, '2025-11-17', '2025-12-14 07:13:47', 'ARCHIVED'),
(71, 'RAW007', 'Espresso', 1000, 'Mililiters', 20, 300, '2025-12-22', '2026-01-21 12:20:26', 'ARCHIVED'),
(72, 'RAW004', 'Coconut Ice Cream', 1500, 'Mililiters', 20, 250, '2025-12-19', '2026-01-21 12:20:24', 'ARCHIVED'),
(73, 'RAW003', 'Ice Cream Cone', 24, 'Piece', 20, 85, '2025-12-27', '2026-01-21 12:20:27', 'ARCHIVED'),
(74, 'RAW002', 'Buko Juice', 2000, 'Mililiters', 20, 400, '2025-12-17', '2026-01-21 12:20:23', 'ARCHIVED'),
(75, 'RAW001', 'Buko Meat', 1000, 'Grams', 20, 210, '2025-12-17', '2026-01-21 12:20:21', 'ARCHIVED'),
(76, 'PAST003', 'Chocolate Donut', 50, 'Piece', 20, 550, '2025-12-19', '2025-12-14 07:12:52', 'ARCHIVED'),
(77, 'PAST001', 'Chocolate Muffin', 60, 'Piece', 20, 700, '2025-12-27', '2026-01-21 12:31:31', 'ARCHIVED'),
(78, 'PAST003', 'Chocolate Donut', 36, 'Piece', 20, 550, '2025-12-19', '2026-01-21 12:31:33', 'ARCHIVED'),
(79, 'PAST002', 'Chocolate Cookies', 60, 'Piece', 20, 600, '2025-12-19', '2026-01-10 14:28:57', 'ARCHIVED'),
(80, 'PAST002', 'Chocolate Cookies', 0, 'Piece', 20, 300, '2026-01-11', '2026-01-21 12:31:30', 'ARCHIVED'),
(81, 'RAW001', 'Buko Meat', 260, 'Grams', 20, 300, '2026-01-31', '2026-01-25 11:48:40', 'ACTIVE'),
(82, 'RAW002', 'Buko Juice', 90, 'Mililiters', 20, 270, '2026-01-31', '2026-01-25 11:48:40', 'ACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inventory_materials_list`
--

CREATE TABLE `tbl_inventory_materials_list` (
  `PK_INVENTORY` int(11) NOT NULL,
  `INVENTORY_ITEM_CODE` varchar(10) NOT NULL,
  `INVENTORY_ITEM_DESCRIPTION` varchar(80) NOT NULL,
  `INVENTORY_ITEM_STATUS` varchar(40) NOT NULL DEFAULT 'ACTIVE',
  `INVENTORY_ITEM_TIMESTAMP` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_inventory_materials_list`
--

INSERT INTO `tbl_inventory_materials_list` (`PK_INVENTORY`, `INVENTORY_ITEM_CODE`, `INVENTORY_ITEM_DESCRIPTION`, `INVENTORY_ITEM_STATUS`, `INVENTORY_ITEM_TIMESTAMP`) VALUES
(1, 'RAW001', 'Buko Meat', 'ACTIVE', '2025-09-04 21:55:02'),
(2, 'RAW002', 'Buko Juice', 'ACTIVE', '2025-09-04 21:55:17'),
(3, 'RAW003', 'Ice Cream Cone', 'ACTIVE', '2025-09-04 21:55:27'),
(4, 'RAW004', 'Coconut Ice Cream', 'ACTIVE', '2025-09-04 21:55:37'),
(5, 'RAW0000001', 'TEXT', 'ARCHIVED', '2025-09-04 21:56:09'),
(6, 'PAST0046', 'TEST', 'ARCHIVED', '2025-09-07 11:46:42'),
(7, 'PAST00052', 'TEST', 'ARCHIVED', '2025-09-20 05:15:17'),
(8, 'RAW005', 'Coconut Powder', 'ACTIVE', '2025-09-18 05:38:23'),
(9, 'RAW006', 'Thai Coffee', 'ACTIVE', '2025-09-18 05:38:51'),
(10, 'RAW007', 'Coconut Milk', 'ARCHIVED', '2025-09-20 06:34:26'),
(11, 'RAW008', 'Coconut Cream', 'ARCHIVED', '2025-09-20 06:34:47'),
(12, 'RAW009', 'Sugar', 'ARCHIVED', '2025-09-20 06:34:44'),
(13, 'RAW007', 'Espresso', 'ARCHIVED', '2025-09-21 03:13:48'),
(14, 'RAW011', 'Matcha Powder', 'ARCHIVED', '2025-09-20 06:34:32'),
(15, 'RAW012', 'Milk', 'ARCHIVED', '2025-09-20 06:34:30'),
(16, 'PAST001', 'Chocolate Muffin', 'ACTIVE', '2025-09-20 05:34:56'),
(17, 'PAST002', 'Chocolate Cookies', 'ACTIVE', '2025-09-20 05:35:09'),
(18, 'PAST003', 'Chocolate Donut', 'ACTIVE', '2025-09-20 05:35:33'),
(19, 'RAW007', 'Espresso', 'ARCHIVED', '2025-09-21 03:28:42'),
(20, 'RAW008', 'Matcha Powder', 'ARCHIVED', '2025-09-21 03:28:40'),
(21, 'RAW009', 'Steamed Milk', 'ARCHIVED', '2025-09-21 03:28:38'),
(22, 'RAW007', 'Espresso', 'ARCHIVED', '2025-09-21 03:31:49'),
(23, 'RAW008', 'Matcha Powder', 'ARCHIVED', '2025-09-21 03:31:51'),
(24, 'RAW009', 'Steamed Milk', 'ARCHIVED', '2025-09-21 03:31:53'),
(25, 'RAW007', 'Espresso', 'ARCHIVED', '2025-09-21 03:41:13'),
(26, 'RAW008', 'Matcha Powder', 'ARCHIVED', '2025-09-21 03:41:11'),
(27, 'RAW009', 'Milk', 'ARCHIVED', '2025-09-21 03:41:09'),
(28, 'RAW007', 'Espresso', 'ACTIVE', '2025-09-21 03:42:38'),
(29, 'RAW008', 'Matcha Powder', 'ACTIVE', '2025-09-21 03:42:50'),
(30, 'RAW009', 'Milk', 'ACTIVE', '2025-09-21 03:43:01'),
(31, 'RAW010', 'Sugar', 'ACTIVE', '2025-09-22 05:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `NOTIF_ID` int(11) NOT NULL,
  `NOTIF_TITLE` varchar(255) NOT NULL,
  `NOTIF_INFO` varchar(255) NOT NULL,
  `NOTIF_TIMESTAMP` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`NOTIF_ID`, `NOTIF_TITLE`, `NOTIF_INFO`, `NOTIF_TIMESTAMP`) VALUES
(1, 'Out of Stock Raw Material Reminder', 'RAW004 Coconut Ice Cream has ran Out of Stock.', '2025-12-08 07:22:25'),
(2, 'Out of Stock Raw Material Reminder', 'RAW003 Ice Cream Cone has ran Out of Stock.', '2025-12-08 07:22:25'),
(3, 'Expired Raw Material Reminder', 'RAW001 Buko Meat has already expired. Please take appropriate action to avoid usage of expired raw material.', '2025-12-08 07:22:25'),
(4, 'Expired Raw Material Reminder', 'RAW002 Buko Juice has already expired. Please take appropriate action to avoid usage of expired raw material.', '2025-12-08 07:22:25'),
(5, 'Expired Raw Material Reminder', 'RAW003 Ice Cream Cone has already expired. Please take appropriate action to avoid usage of expired raw material.', '2025-12-08 07:22:25'),
(6, 'Expired Raw Material Reminder', 'RAW004 Coconut Ice Cream has already expired. Please take appropriate action to avoid usage of expired raw material.', '2025-12-08 07:22:25'),
(7, 'New Order Alert', 'New Order has been placed and paid by None with a total of 100.00. Please check order 9 for order information.', '2025-12-08 07:25:10'),
(8, 'New Order Alert', 'New Order has been placed and paid by None with a total of 100.00. Please check order 10 for order information.', '2025-12-08 08:07:42'),
(9, 'New Order Alert', 'New Order has been placed and paid by None with a total of 100.00. Please check order 11 for order information.', '2025-12-08 08:08:14'),
(10, 'New Order Alert', 'New Order has been placed and paid by None with a total of 80.00. Please check order 12 for order information.', '2025-12-08 08:16:18'),
(11, 'Critical Stock Level Reminder', 'PAST001 Chocolate Muffin has reached its critical threshold of 20 Piece. \r\n                                                The current available stock is 19 Piece. Please take appropriate action to avoid insufficient stock of inbound material.', '2025-12-08 08:17:07'),
(12, 'Replenish Stock Level Reminder', 'PAST003 Chocolate Donut has reached its warning threshold of 24 Piece. \r\n                                                The current available stock is 21 Piece. Please replenish stock.', '2025-12-08 08:17:07'),
(13, 'Critical Stock Level Reminder', 'RAW003 Ice Cream Cone has reached its critical threshold of 20 Piece. \r\n                                                The current available stock is 17 Piece. Please take appropriate action to avoid insufficient stock of inbound material.', '2025-12-08 08:17:07'),
(14, 'Expired Pastry Item Reminder', 'PAST003 Chocolate Donut has already expired. Please take appropriate action to avoid usage of expired pastry item.', '2025-12-08 08:18:14'),
(15, 'Expired Pastry Item Reminder', 'PAST001 Chocolate Muffin has already expired. Please take appropriate action to avoid usage of expired pastry item.', '2025-12-08 08:18:14'),
(16, 'Expired Pastry Item Reminder', 'PAST002 Chocolate Cookies has already expired. Please take appropriate action to avoid usage of expired pastry item.', '2025-12-08 08:18:14'),
(17, 'Expiring Raw Material Warning', 'RAW007 Espresso will expire in 3 days. Please take appropriate action to avoid expiring the raw material.', '2025-12-14 04:30:42'),
(18, 'Expiring Raw Material Warning', 'RAW002 Buko Juice will expire in 3 days. Please take appropriate action to avoid expiring the raw material.', '2025-12-14 07:08:17'),
(19, 'Expiring Raw Material Warning', 'RAW001 Buko Meat will expire in 3 days. Please take appropriate action to avoid expiring the raw material.', '2025-12-14 07:09:51'),
(20, 'Expired Raw Material Reminder', 'RAW007 Espresso has already expired. Please take appropriate action to avoid usage of expired raw material.', '2026-01-10 14:23:22'),
(21, 'Expiring Pastry Item Warning', 'PAST002 Chocolate Cookies will expire in 3 days. Please take appropriate action to avoid expiring the pastry item.', '2026-01-10 14:28:49'),
(22, 'New Order Alert', 'New Order has been placed and paid by None with a total of 360.00. Please check order 13 for order information.', '2026-01-10 14:29:24'),
(23, 'Out of Stock Pastry Item Reminder', 'PAST002 Chocolate Cookies has ran Out of Stock.', '2026-01-10 14:29:37'),
(24, 'Critical Stock Level Reminder', 'PAST002 Chocolate Cookies has reached its critical threshold of 20 Piece. \r\n                                                The current available stock is 0 Piece. Please take appropriate action to avoid insufficient stock of inbound material.', '2026-01-10 15:12:40'),
(25, 'Replenish Stock Level Reminder', 'RAW003 Ice Cream Cone has reached its warning threshold of 24 Piece. \r\n                                                The current available stock is 24 Piece. Please replenish stock.', '2026-01-10 15:12:40'),
(26, 'Critical Stock Level Reminder', 'PAST002 Chocolate Cookies has reached its critical threshold of 20 Piece. \r\n                                            The current available stock is 0 Piece. Please take appropriate action to avoid insufficient stock of inbound material.', '2026-01-21 10:26:55'),
(27, 'Replenish Stock Level Reminder', 'RAW003 Ice Cream Cone has reached its warning threshold of 24 Piece. \r\n                                            The current available stock is 24 Piece. Please replenish stock.', '2026-01-21 10:26:55'),
(28, 'Expired Raw Material Reminder', 'RAW005 Coconut Powder has already expired. Please take appropriate action to avoid usage of expired raw material.', '2026-01-21 11:04:52'),
(29, 'Expired Raw Material Reminder', 'RAW006 Thai Coffee has already expired. Please take appropriate action to avoid usage of expired raw material.', '2026-01-21 11:04:52'),
(30, 'Expiring Today Raw Material Warning', 'RAW010 Sugar will expire today. Please take appropriate action to avoid expiring the raw material.', '2026-01-21 11:04:52'),
(31, 'Expired Raw Material Reminder', 'PAST003 Chocolate Donut has already expired. Please take appropriate action to avoid usage of expired raw material.', '2026-01-21 11:09:11'),
(32, 'Expired Raw Material Reminder', 'PAST001 Chocolate Muffin has already expired. Please take appropriate action to avoid usage of expired raw material.', '2026-01-21 11:09:11'),
(33, 'Out of Stock Raw Material Reminder', 'PAST002 Chocolate Cookies has ran Out of Stock.', '2026-01-21 11:09:11'),
(34, 'New Order Alert', 'New Order has been placed and paid by None with a total of 40.00. Please check order 14 for order information.', '2026-01-21 12:21:11'),
(35, 'Expired Raw Material Reminder', 'RAW010 Sugar has already expired. Please take appropriate action to avoid usage of expired raw material.', '2026-01-25 11:46:37'),
(36, 'New Order Alert', 'New Order has been placed and paid by None with a total of 80.00. Please check order 15 for order information.', '2026-01-25 11:47:12'),
(37, 'New Order Alert', 'New Order has been placed and paid by None with a total of 20.00. Please check order 16 for order information.', '2026-01-25 11:47:52'),
(38, 'New Order Alert', 'New Order has been placed and paid by None with a total of 20.00. Please check order 17 for order information.', '2026-01-25 11:48:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `PK_ORDER_ID` int(11) NOT NULL,
  `CUST_NAME` varchar(50) NOT NULL,
  `ORDER_TIMESTAMP` datetime NOT NULL DEFAULT current_timestamp(),
  `ORDER_SUBTOTAL` decimal(10,2) NOT NULL,
  `ORDER_TOTAL_AMT` decimal(10,2) NOT NULL,
  `ORDER_DISCOUNT_TYPE` varchar(25) NOT NULL DEFAULT 'None',
  `ORDER_PAYMENT_METHOD` enum('Cash','GCash') NOT NULL,
  `ORDER_PAID_AMT` decimal(10,2) NOT NULL,
  `ORDER_CHANGE_AMT` decimal(10,2) NOT NULL,
  `ORDER_GCASH_REF` varchar(50) NOT NULL DEFAULT 'None'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_orders`
--

INSERT INTO `tbl_orders` (`PK_ORDER_ID`, `CUST_NAME`, `ORDER_TIMESTAMP`, `ORDER_SUBTOTAL`, `ORDER_TOTAL_AMT`, `ORDER_DISCOUNT_TYPE`, `ORDER_PAYMENT_METHOD`, `ORDER_PAID_AMT`, `ORDER_CHANGE_AMT`, `ORDER_GCASH_REF`) VALUES
(1, 'None', '2025-11-11 10:02:22', 200.00, 160.00, 'SC/PWD', 'Cash', 200.00, 40.00, ''),
(2, 'Irish Balana', '2025-11-11 10:03:36', 100.00, 100.00, 'None', 'Cash', 100.00, 0.00, ''),
(3, 'None', '2025-11-16 12:19:40', 100.00, 100.00, 'None', 'Cash', 100.00, 0.00, ''),
(4, 'Jero Galfo', '2025-11-16 12:47:14', 145.00, 116.00, 'SC/PWD', 'Cash', 120.00, 4.00, ''),
(5, 'None', '2025-11-16 14:25:25', 150.00, 150.00, 'None', 'Cash', 200.00, 50.00, ''),
(6, 'None', '2025-11-17 04:57:48', 45.00, 45.00, 'None', 'Cash', 100.00, 55.00, ''),
(7, 'None', '2025-11-17 04:59:10', 20.00, 20.00, 'None', 'Cash', 50.00, 30.00, ''),
(8, 'None', '2025-11-17 05:06:07', 135.00, 108.00, 'SC/PWD', 'Cash', 120.00, 12.00, ''),
(9, 'None', '2025-12-08 07:25:10', 100.00, 100.00, 'None', 'Cash', 100.00, 0.00, ''),
(10, 'None', '2025-12-08 08:07:42', 100.00, 100.00, 'None', 'Cash', 100.00, 0.00, ''),
(11, 'None', '2025-12-08 08:08:14', 100.00, 100.00, 'None', 'Cash', 100.00, 0.00, ''),
(12, 'None', '2025-12-08 08:16:18', 100.00, 80.00, 'SC/PWD', 'Cash', 90.00, 10.00, ''),
(13, 'None', '2026-01-10 14:29:24', 360.00, 360.00, 'None', 'Cash', 400.00, 40.00, ''),
(14, 'None', '2026-01-21 12:21:11', 40.00, 40.00, 'None', 'Cash', 50.00, 10.00, ''),
(15, 'None', '2026-01-25 11:47:12', 80.00, 80.00, 'None', 'Cash', 100.00, 20.00, ''),
(16, 'None', '2026-01-25 11:47:52', 20.00, 20.00, 'None', 'Cash', 20.00, 0.00, ''),
(17, 'None', '2026-01-25 11:48:40', 20.00, 20.00, 'None', 'Cash', 50.00, 0.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_items`
--

CREATE TABLE `tbl_order_items` (
  `PK_ORDER_ITEM` int(11) NOT NULL,
  `PK_ORDER_ID` int(11) NOT NULL,
  `ORDER_PRODUCT_CATEGORY` varchar(100) NOT NULL,
  `ORDER_PRODUCT_NAME` varchar(255) NOT NULL,
  `ORDER_PRODUCT_QTY` int(11) NOT NULL,
  `ORDER_PRICE` decimal(10,2) NOT NULL,
  `ORDER_ITEM_TIMESTAMP` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_order_items`
--

INSERT INTO `tbl_order_items` (`PK_ORDER_ITEM`, `PK_ORDER_ID`, `ORDER_PRODUCT_CATEGORY`, `ORDER_PRODUCT_NAME`, `ORDER_PRODUCT_QTY`, `ORDER_PRICE`, `ORDER_ITEM_TIMESTAMP`) VALUES
(1, 1, 'Coffee Series', 'Coconut Coffee Small Hot', 2, 100.00, '2025-11-11 10:02:22'),
(2, 2, 'Coffee Series', 'Coconut Coffee Small Hot', 1, 100.00, '2025-11-11 10:03:36'),
(3, 3, 'Dessert', 'Coconut Soft Serve Ice Cream', 4, 25.00, '2025-11-16 12:19:40'),
(4, 4, 'Coconut Classic Series', 'Fresh Coconut Shake Small', 1, 120.00, '2025-11-16 12:47:14'),
(5, 4, 'Dessert', 'Coconut Soft Serve Ice Cream', 1, 25.00, '2025-11-16 12:47:14'),
(6, 5, 'Coffee Series', 'Coconut Coffee Small Hot', 1, 100.00, '2025-11-16 14:25:25'),
(7, 5, 'Pastries', 'Chocolate Muffin', 1, 50.00, '2025-11-16 14:25:25'),
(8, 6, 'Coconut Classic Series', 'Fresh Coconut Juice Small', 1, 20.00, '2025-11-17 04:57:48'),
(9, 6, 'Dessert', 'Coconut Soft Serve Ice Cream', 1, 25.00, '2025-11-17 04:57:48'),
(10, 7, 'Coconut Classic Series', 'Fresh Coconut Juice Small', 1, 20.00, '2025-11-17 04:59:10'),
(11, 8, 'Pastries', 'Chocolate Donut', 3, 45.00, '2025-11-17 05:06:07'),
(12, 9, 'Coffee Series', 'Coconut Coffee Small Hot', 1, 100.00, '2025-12-08 07:25:10'),
(13, 10, 'Coffee Series', 'Coconut Coffee Small Hot', 1, 100.00, '2025-12-08 08:07:42'),
(14, 11, 'Coffee Series', 'Coconut Coffee Small Hot', 1, 100.00, '2025-12-08 08:08:14'),
(15, 12, 'Coffee Series', 'Coconut Coffee Small Hot', 1, 100.00, '2025-12-08 08:16:18'),
(16, 13, 'Pastries', 'Chocolate Cookies', 12, 30.00, '2026-01-10 14:29:24'),
(17, 14, 'Coconut Classic Series', 'Fresh Coconut Juice Small', 2, 20.00, '2026-01-21 12:21:11'),
(18, 15, 'Coconut Classic Series', 'Fresh Coconut Juice Small', 4, 20.00, '2026-01-25 11:47:12'),
(19, 16, 'Coconut Classic Series', 'Fresh Coconut Juice Small', 1, 20.00, '2026-01-25 11:47:52'),
(20, 17, 'Coconut Classic Series', 'Fresh Coconut Juice Small', 1, 20.00, '2026-01-25 11:48:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products_list`
--

CREATE TABLE `tbl_products_list` (
  `PK_PROD_LIST` int(11) NOT NULL,
  `PROD_CATEGORY` varchar(50) NOT NULL,
  `PROD_NAME` varchar(255) NOT NULL,
  `PROD_PRICE` decimal(10,2) NOT NULL,
  `PROD_STATUS` varchar(50) NOT NULL,
  `PROD_IMAGE` varchar(255) NOT NULL,
  `STATUS` varchar(25) NOT NULL DEFAULT 'ACTIVE',
  `PROD_TIMESTAMP` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_products_list`
--

INSERT INTO `tbl_products_list` (`PK_PROD_LIST`, `PROD_CATEGORY`, `PROD_NAME`, `PROD_PRICE`, `PROD_STATUS`, `PROD_IMAGE`, `STATUS`, `PROD_TIMESTAMP`) VALUES
(1, 'Coconut Classic Series', 'Fresh Coconut Juice Small', 20.00, 'Available', 'images/products/1768999639_logo.png', 'ACTIVE', '2026-01-21 20:47:19'),
(2, 'Dessert', 'Coconut Soft Serve Ice Cream', 25.00, 'Not Available', 'images/products/1768999770_logo.png', 'ACTIVE', '2026-01-21 20:50:04'),
(3, 'Coffee Series', 'colorum', 40.00, 'Not Available', 'images/products/1756995065_1744700374_testingkulorum.png', 'ARCHIVED', '2025-09-04 22:12:35'),
(4, 'Pastries', 'colorum', 69.00, 'Not Available', 'images/products/1757217329_Screenshot (33)B.png', 'ARCHIVED', '2025-09-07 11:55:52'),
(5, 'Coffee Series', 'Coconut Coffee Small Hot', 100.00, 'Not Available', 'images/products/1769000784_logo.png', 'ACTIVE', '2026-01-21 21:06:24'),
(6, 'Pastries', 'Chocolate Donut', 45.00, 'Not Available', 'images/products/1769000788_logo.png', 'ACTIVE', '2026-01-21 21:06:28'),
(7, 'Pastries', 'Chocolate Cookies', 30.00, 'Not Available', 'images/products/1769000795_logo.png', 'ACTIVE', '2026-01-21 21:06:35'),
(8, 'Pastries', 'Chocolate Muffin', 50.00, 'Not Available', 'images/products/1769000801_logo.png', 'ACTIVE', '2026-01-21 21:06:41'),
(9, 'Coffee Series', 'Matcha Espresso Hot Small', 135.00, 'Not Available', 'images/products/1758425252_33.png', 'ARCHIVED', '2025-09-21 03:28:47'),
(10, 'Coffee Series', 'Matcha Espresso Hot Small', 135.00, 'Not Available', 'images/products/1758425674_logo.png', 'ARCHIVED', '2025-09-21 03:41:02'),
(11, 'Coffee Series', 'Matcha Espresso Hot Small', 135.00, 'Available', 'images/products/1769000806_logo.png', 'ACTIVE', '2026-01-21 21:06:46'),
(12, 'Coconut Classic Series', 'Fresh Coconut Shake Small', 120.00, 'Available', 'images/products/1769000812_logo.png', 'ACTIVE', '2026-01-21 21:06:52');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_ingredients`
--

CREATE TABLE `tbl_product_ingredients` (
  `PK_PROD_ING` int(11) NOT NULL,
  `PK_PROD_LIST` int(11) DEFAULT NULL,
  `ING_CODE` varchar(10) NOT NULL,
  `INGREDIENT_NAME` varchar(80) NOT NULL,
  `INGREDIENT_AMOUNT` decimal(10,2) NOT NULL,
  `INGREDIENT_UNIT` varchar(80) NOT NULL,
  `INGREDIENT_TIMESTAMP` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product_ingredients`
--

INSERT INTO `tbl_product_ingredients` (`PK_PROD_ING`, `PK_PROD_LIST`, `ING_CODE`, `INGREDIENT_NAME`, `INGREDIENT_AMOUNT`, `INGREDIENT_UNIT`, `INGREDIENT_TIMESTAMP`) VALUES
(1, 1, 'RAW001', 'Buko Meat', 5.00, 'Grams', '2025-09-04 14:17:14'),
(3, 1, 'RAW002', 'Buko Juice', 45.00, 'Mililiters', '2025-09-07 03:58:34'),
(4, 5, 'RAW005', 'Coconut Powder', 1.00, 'Grams', '2025-09-18 05:42:02'),
(5, 5, 'RAW006', 'Thai Coffee', 1.00, 'Grams', '2025-09-18 05:42:14'),
(6, 8, 'PAST001', 'Chocolate Muffin', 1.00, 'Piece', '2025-09-20 05:53:45'),
(7, 7, 'PAST002', 'Chocolate Cookies', 1.00, 'Piece', '2025-09-20 05:53:58'),
(8, 6, 'PAST003', 'Chocolate Donut', 1.00, 'Piece', '2025-09-20 05:54:10'),
(12, 2, 'RAW003', 'Ice Cream Cone', 1.00, 'Piece', '2025-09-20 06:44:51'),
(13, 2, 'RAW004', 'Coconut Ice Cream', 15.00, 'Mililiters', '2025-09-20 06:46:23'),
(14, 10, 'RAW007', 'Espresso', 45.00, 'Mililiters', '2025-09-21 03:36:46'),
(15, 10, 'RAW008', 'Matcha Powder', 2.00, 'Grams', '2025-09-21 03:37:00'),
(16, 10, 'RAW009', 'Milk', 237.00, 'Mililiters', '2025-09-21 03:37:13'),
(21, 11, 'RAW007', 'Espresso', 45.00, 'Mililiters', '2025-09-21 03:59:28'),
(22, 11, 'RAW008', 'Matcha Powder', 2.00, 'Grams', '2025-09-21 03:59:44'),
(23, 11, 'RAW009', 'Milk', 237.00, 'Mililiters', '2025-09-21 03:59:58'),
(25, 12, 'RAW001', 'Buko Meat', 5.00, 'Grams', '2025-09-22 06:00:44'),
(26, 12, 'RAW002', 'Buko Juice', 45.00, 'Mililiters', '2025-09-22 06:00:59'),
(28, 12, 'RAW009', 'Milk', 119.00, 'Mililiters', '2025-09-22 06:01:45'),
(29, 12, 'RAW010', 'Sugar', 2.00, 'Grams', '2025-09-22 06:02:03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_prod_categories`
--

CREATE TABLE `tbl_prod_categories` (
  `PK_PROD_CAT` int(11) NOT NULL,
  `PROD_CAT_NAME` varchar(100) NOT NULL,
  `PROD_CAT_STATUS` varchar(25) NOT NULL DEFAULT 'ACTIVE',
  `PROD_CAT_TIMESTAMP` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_prod_categories`
--

INSERT INTO `tbl_prod_categories` (`PK_PROD_CAT`, `PROD_CAT_NAME`, `PROD_CAT_STATUS`, `PROD_CAT_TIMESTAMP`) VALUES
(1, 'Coconut Classic Series', 'ACTIVE', '2025-09-04 22:05:35'),
(2, 'Coconut Breeze Series', 'ACTIVE', '2025-09-04 22:07:06'),
(3, 'Soda Pop Series', 'ACTIVE', '2025-09-04 22:07:31'),
(4, 'Coffee Series', 'ACTIVE', '2025-09-04 22:07:39'),
(5, 'Dessert', 'ACTIVE', '2025-09-04 22:07:47'),
(6, 'Pastries', 'ACTIVE', '2025-09-04 22:07:55'),
(7, 'test21', 'ARCHIVED', '2025-09-04 22:08:22'),
(8, 'promo_sept', 'ARCHIVED', '2025-09-07 11:53:16');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `PK_USER` int(11) NOT NULL,
  `FIRST_NAME` varchar(50) NOT NULL,
  `LAST_NAME` varchar(50) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `PNUM` varchar(11) NOT NULL,
  `USR_ADD` varchar(255) NOT NULL,
  `USERNAME` varchar(60) NOT NULL,
  `USER_PASSWORD` varchar(20) NOT NULL,
  `ROLE` varchar(50) NOT NULL,
  `EMPLOYEE_STATUS` varchar(25) NOT NULL DEFAULT 'ACTIVE',
  `LAST_USR_UDATE` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`PK_USER`, `FIRST_NAME`, `LAST_NAME`, `EMAIL`, `PNUM`, `USR_ADD`, `USERNAME`, `USER_PASSWORD`, `ROLE`, `EMPLOYEE_STATUS`, `LAST_USR_UDATE`) VALUES
(1, 'Mark', 'Mediavilla', 'markandrei24@gmail.com', '09693010007', 'Antipolo, Rizal', 'Mark Mediavilla', '2002', 'Administrator', 'ACTIVE', '2025-09-07 03:33:12'),
(2, 'Ron Stewart', 'Dorimon', 'N/A', '00000', 'Teresa, Rizal', 'Ron Stewart Dorimon', '2022', 'Staff', 'ACTIVE', '2025-09-04 14:02:35'),
(3, 'Evalene', 'Belino', 'eval@123', '236870002', 'Antipolo, Rizal', 'Evalene Belino', 'kapoy', 'Administrator', 'ARCHIVED', '2025-09-07 03:38:13'),
(4, 'Louise Egee', 'Espra', 'N/A', '0234564', 'Antipolo, Rizal', 'Louise Egee Espra', '2022', 'Staff', 'ACTIVE', '2025-09-15 12:40:29'),
(5, 'Allen', 'Riñon', 'N/A', '3541230', 'Antipolo, Rizal', 'Allen Riñon', '2022', 'Staff', 'ACTIVE', '2025-09-15 12:40:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_inventory`
--
ALTER TABLE `tbl_inventory`
  ADD PRIMARY KEY (`PK_INVENTORY_ITEM`);

--
-- Indexes for table `tbl_inventory_materials_list`
--
ALTER TABLE `tbl_inventory_materials_list`
  ADD PRIMARY KEY (`PK_INVENTORY`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`NOTIF_ID`);

--
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`PK_ORDER_ID`);

--
-- Indexes for table `tbl_order_items`
--
ALTER TABLE `tbl_order_items`
  ADD PRIMARY KEY (`PK_ORDER_ITEM`),
  ADD KEY `FK_ORDER_ID` (`PK_ORDER_ID`);

--
-- Indexes for table `tbl_products_list`
--
ALTER TABLE `tbl_products_list`
  ADD PRIMARY KEY (`PK_PROD_LIST`);

--
-- Indexes for table `tbl_product_ingredients`
--
ALTER TABLE `tbl_product_ingredients`
  ADD PRIMARY KEY (`PK_PROD_ING`),
  ADD KEY `CONST_PROD` (`PK_PROD_LIST`);

--
-- Indexes for table `tbl_prod_categories`
--
ALTER TABLE `tbl_prod_categories`
  ADD PRIMARY KEY (`PK_PROD_CAT`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`PK_USER`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_inventory`
--
ALTER TABLE `tbl_inventory`
  MODIFY `PK_INVENTORY_ITEM` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `tbl_inventory_materials_list`
--
ALTER TABLE `tbl_inventory_materials_list`
  MODIFY `PK_INVENTORY` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `NOTIF_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `PK_ORDER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_order_items`
--
ALTER TABLE `tbl_order_items`
  MODIFY `PK_ORDER_ITEM` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_products_list`
--
ALTER TABLE `tbl_products_list`
  MODIFY `PK_PROD_LIST` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_product_ingredients`
--
ALTER TABLE `tbl_product_ingredients`
  MODIFY `PK_PROD_ING` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tbl_prod_categories`
--
ALTER TABLE `tbl_prod_categories`
  MODIFY `PK_PROD_CAT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `PK_USER` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_order_items`
--
ALTER TABLE `tbl_order_items`
  ADD CONSTRAINT `FK_ORDER_ID` FOREIGN KEY (`PK_ORDER_ID`) REFERENCES `tbl_orders` (`PK_ORDER_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_ingredients`
--
ALTER TABLE `tbl_product_ingredients`
  ADD CONSTRAINT `CONST_PROD` FOREIGN KEY (`PK_PROD_LIST`) REFERENCES `tbl_products_list` (`PK_PROD_LIST`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
