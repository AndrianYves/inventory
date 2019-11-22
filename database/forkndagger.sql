-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 22, 2019 at 06:22 AM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `forkndagger`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `role` enum('Super User','Admin') NOT NULL,
  `lastlogin` timestamp(6) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `firstname`, `lastname`, `role`, `lastlogin`) VALUES
(1, 'superadmin', '$2y$10$SB5nbD.QlZ/Yl0JvWHH.sOKMMTTDCBhQr4DKBGO8vEpGCKYWa0TCK', 'superadmin@gmail.com', 'superadminFirst', 'superadminLast', 'Super User', '2019-11-21 14:51:23.000000'),
(2, 'admin', '$2y$10$9pXipDwls1/S7d69Sq7TMu82yCBAh8B5HKCqBXGw3oEl.P2s0qPVC', 'admin@gmail.com', 'adminFirst', 'adminLast', 'Admin', '2019-11-20 08:54:09.000000'),
(5, 'superuser1', '$2y$10$9vTcDONC8FOhqeq8Jo0cRuUYPOXB33jMTYYyDqCirpdGaK.iX9Z1y', '12345@gmail.com', 'andrian yves', 'macalino', 'Super User', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `categoryname` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categoryname` (`categoryname`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `categoryname`) VALUES
(1, 'meat'),
(2, 'fruits'),
(3, 'condiments'),
(4, 'drinks'),
(5, 'fish'),
(6, '');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `itemname` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` decimal(65,2) UNSIGNED NOT NULL,
  `lowquantity` decimal(65,2) DEFAULT NULL,
  `categoryID` int(255) DEFAULT NULL,
  `unitID` int(255) DEFAULT NULL,
  `timestamp` timestamp(6) NOT NULL,
  `adminID` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `itemname`, `description`, `quantity`, `lowquantity`, `categoryID`, `unitID`, `timestamp`, `adminID`) VALUES
(1, 'apple', 'from japan', '1.00', '5.00', 2, 1, '2019-11-20 12:11:44.000000', 1),
(2, 'water', 'purified water', '1000.00', '1000.00', 4, 5, '2019-11-20 12:12:12.000000', 1),
(3, 'orange', 'orange from pampanga', '88.00', '5.00', 2, 1, '2019-11-20 12:12:41.000000', 1),
(4, 'lemon', 'lemon sauce', '100.00', '5.00', 2, 1, '2019-11-20 12:12:59.000000', 1),
(5, 'vinegar', 'datu puti vinegar', '10000.00', '250.00', 3, 5, '2019-11-20 12:13:21.000000', 1),
(6, 'water', 'sfdf', '0.00', '2.00', 1, 6, '2019-11-21 21:51:36.000000', 1),
(7, 'sdf', 'sdf', '0.00', '34.00', 6, 6, '2019-11-21 21:52:03.000000', 1),
(8, 'asd', 'wd', '0.00', '3.00', 2, 1, '2019-11-21 21:56:38.000000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ledger`
--

DROP TABLE IF EXISTS `ledger`;
CREATE TABLE IF NOT EXISTS `ledger` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `inventoryID` int(255) NOT NULL,
  `quantity` decimal(65,2) NOT NULL,
  `transaction` enum('Inventory','Order','Canceled','Returned','Spoilage') NOT NULL,
  `transactionID` bigint(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `timestamp` timestamp(6) NOT NULL,
  `adminID` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ledger`
--

INSERT INTO `ledger` (`id`, `inventoryID`, `quantity`, `transaction`, `transactionID`, `remarks`, `timestamp`, `adminID`) VALUES
(1, 1, '-1.00', 'Order', 1, NULL, '2019-11-20 14:37:23.000000', 1),
(2, 2, '-1000.00', 'Order', 1, NULL, '2019-11-20 14:37:23.000000', 1),
(3, 3, '-5.00', 'Order', 1, NULL, '2019-11-20 14:37:23.000000', 1),
(4, 2, '-2000.00', 'Order', 1, NULL, '2019-11-20 14:37:23.000000', 1),
(5, 2, '10000.00', 'Inventory', NULL, '', '2019-11-20 14:44:33.000000', 1),
(6, 1, '-1.00', 'Order', 2, NULL, '2019-11-20 14:45:32.000000', 1),
(7, 2, '-1000.00', 'Order', 2, NULL, '2019-11-20 14:45:32.000000', 1),
(8, 1, '1.00', 'Canceled', NULL, '', '2019-11-20 14:57:50.000000', 1),
(9, 2, '1000.00', 'Canceled', NULL, '', '2019-11-20 14:57:50.000000', 1),
(10, 3, '5.00', 'Canceled', NULL, '', '2019-11-20 14:57:50.000000', 1),
(11, 2, '2000.00', 'Canceled', NULL, '', '2019-11-20 14:57:50.000000', 1),
(12, 1, '-1.00', 'Order', 3, NULL, '2019-11-20 14:59:01.000000', 1),
(13, 2, '-1000.00', 'Order', 3, NULL, '2019-11-20 14:59:01.000000', 1),
(14, 1, '1.00', 'Canceled', NULL, 'it was bad', '2019-11-20 14:59:30.000000', 1),
(15, 2, '1000.00', 'Canceled', NULL, 'it was bad', '2019-11-20 14:59:30.000000', 1),
(16, 1, '-1.00', 'Order', 4, NULL, '2019-11-20 15:02:18.000000', 1),
(17, 2, '-1000.00', 'Order', 4, NULL, '2019-11-20 15:02:18.000000', 1),
(18, 3, '-5.00', 'Order', 4, NULL, '2019-11-20 15:02:18.000000', 1),
(19, 2, '-2000.00', 'Order', 4, NULL, '2019-11-20 15:02:18.000000', 1),
(20, 1, '-1.00', 'Order', 5, NULL, '2019-11-20 15:04:02.000000', 1),
(21, 2, '-1000.00', 'Order', 5, NULL, '2019-11-20 15:04:02.000000', 1),
(22, 3, '-5.00', 'Order', 5, NULL, '2019-11-20 15:04:02.000000', 1),
(23, 2, '-2000.00', 'Order', 5, NULL, '2019-11-20 15:04:02.000000', 1),
(24, 1, '1.00', 'Canceled', 5, 'none', '2019-11-20 15:04:11.000000', 1),
(25, 2, '1000.00', 'Canceled', 5, 'none', '2019-11-20 15:04:11.000000', 1),
(26, 3, '5.00', 'Canceled', 5, 'none', '2019-11-20 15:04:11.000000', 1),
(27, 2, '2000.00', 'Canceled', 5, 'none', '2019-11-20 15:04:11.000000', 1),
(28, 1, '-1.00', 'Order', 6, NULL, '2019-11-20 15:04:31.000000', 1),
(29, 2, '-1000.00', 'Order', 6, NULL, '2019-11-20 15:04:31.000000', 1),
(30, 3, '-5.00', 'Order', 6, NULL, '2019-11-20 15:04:31.000000', 1),
(31, 2, '-2000.00', 'Order', 6, NULL, '2019-11-20 15:04:31.000000', 1),
(32, 1, '1.00', 'Canceled', 6, '', '2019-11-20 15:04:43.000000', 1),
(33, 2, '1000.00', 'Canceled', 6, '', '2019-11-20 15:04:43.000000', 1),
(34, 3, '5.00', 'Canceled', 6, '', '2019-11-20 15:04:43.000000', 1),
(35, 2, '2000.00', 'Canceled', 6, '', '2019-11-20 15:04:43.000000', 1),
(36, 1, '1.00', 'Returned', NULL, '', '2019-11-20 15:11:52.000000', 1),
(37, 2, '1000.00', 'Returned', NULL, '', '2019-11-20 15:11:52.000000', 1),
(38, 1, '-1.00', 'Order', 7, NULL, '2019-11-20 15:12:30.000000', 1),
(39, 2, '-1000.00', 'Order', 7, NULL, '2019-11-20 15:12:30.000000', 1),
(40, 1, '1.00', 'Canceled', 7, 'they dont like it', '2019-11-20 15:12:50.000000', 1),
(41, 2, '1000.00', 'Canceled', 7, 'they dont like it', '2019-11-20 15:12:50.000000', 1),
(42, 1, '-1.00', 'Order', 8, NULL, '2019-11-20 15:14:25.000000', 1),
(43, 2, '-1000.00', 'Order', 8, NULL, '2019-11-20 15:14:25.000000', 1),
(44, 1, '1.00', 'Returned', 8, 'it was bad', '2019-11-20 15:15:31.000000', 1),
(45, 2, '1000.00', 'Returned', 8, 'it was bad', '2019-11-20 15:15:31.000000', 1),
(46, 1, '-1.00', 'Order', 9, NULL, '2019-11-20 15:16:16.000000', 1),
(47, 2, '-1000.00', 'Order', 9, NULL, '2019-11-20 15:16:16.000000', 1),
(48, 1, '1.00', 'Returned', 9, '', '2019-11-20 15:16:36.000000', 1),
(49, 2, '1000.00', 'Returned', 9, '', '2019-11-20 15:16:36.000000', 1),
(50, 1, '-2.00', 'Spoilage', NULL, 'full maggots', '2019-11-20 15:46:04.000000', 1),
(51, 1, '-2.00', 'Spoilage', NULL, 'full maggots', '2019-11-20 15:49:50.000000', 1),
(52, 1, '-80.00', 'Inventory', NULL, 'low', '2019-11-21 21:57:21.000000', 1),
(53, 2, '-5000.00', 'Inventory', NULL, '34', '2019-11-21 21:57:45.000000', 1),
(54, 3, '-2.00', 'Spoilage', NULL, 'wa', '2019-11-22 05:20:27.000000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `timestamp` timestamp(6) NOT NULL,
  `adminID` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `description`, `timestamp`, `adminID`) VALUES
(1, 'apple juice', 'sweet apple', '2019-11-20 14:36:46.000000', 1),
(2, 'orange juice', 'sweet oranges', '2019-11-20 14:37:13.000000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `menuitems`
--

DROP TABLE IF EXISTS `menuitems`;
CREATE TABLE IF NOT EXISTS `menuitems` (
  `menuID` int(255) NOT NULL,
  `inventoryID` int(255) NOT NULL,
  `quantity` decimal(65,2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menuitems`
--

INSERT INTO `menuitems` (`menuID`, `inventoryID`, `quantity`) VALUES
(1, 1, '1.00'),
(1, 2, '1000.00'),
(2, 3, '5.00'),
(2, 2, '2000.00');

-- --------------------------------------------------------

--
-- Table structure for table `orderlist`
--

DROP TABLE IF EXISTS `orderlist`;
CREATE TABLE IF NOT EXISTS `orderlist` (
  `orderID` int(255) NOT NULL,
  `menuID` int(255) NOT NULL,
  `quantity` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orderlist`
--

INSERT INTO `orderlist` (`orderID`, `menuID`, `quantity`) VALUES
(1, 1, 1),
(1, 2, 1),
(2, 1, 1),
(3, 1, 1),
(4, 1, 1),
(4, 2, 1),
(5, 1, 1),
(5, 2, 1),
(6, 1, 1),
(6, 2, 1),
(7, 1, 1),
(8, 1, 1),
(9, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` bigint(255) NOT NULL,
  `timestamp` timestamp(6) NULL DEFAULT NULL,
  `status` enum('Pending','Canceled','Returned','Delivered') DEFAULT NULL,
  `lastUpdatedStatus` timestamp(6) NULL DEFAULT NULL,
  `adminID` int(255) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `timestamp`, `status`, `lastUpdatedStatus`, `adminID`) VALUES
(1, '2019-11-20 14:37:23.000000', 'Canceled', '2019-11-20 14:57:50.000000', 1),
(2, '2019-11-20 14:45:32.000000', 'Returned', '2019-11-20 15:11:52.000000', 1),
(3, '2019-11-20 14:59:01.000000', 'Canceled', '2019-11-20 14:59:30.000000', 1),
(4, '2019-11-20 15:02:18.000000', 'Delivered', '2019-11-20 16:16:20.000000', 1),
(5, '2019-11-20 15:04:02.000000', 'Canceled', '2019-11-20 15:04:11.000000', 1),
(6, '2019-11-20 15:04:31.000000', 'Canceled', '2019-11-20 15:04:43.000000', 1),
(7, '2019-11-20 15:12:30.000000', 'Returned', '2019-11-20 15:12:50.000000', 1),
(8, '2019-11-20 15:14:25.000000', 'Returned', '2019-11-20 15:15:31.000000', 1),
(9, '2019-11-20 15:16:16.000000', 'Returned', '2019-11-20 15:16:36.000000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ordersitems`
--

DROP TABLE IF EXISTS `ordersitems`;
CREATE TABLE IF NOT EXISTS `ordersitems` (
  `orderID` bigint(255) NOT NULL,
  `inventoryID` bigint(255) NOT NULL,
  `quantity` decimal(65,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ordersitems`
--

INSERT INTO `ordersitems` (`orderID`, `inventoryID`, `quantity`) VALUES
(1, 1, '1.00'),
(1, 2, '1000.00'),
(1, 3, '5.00'),
(1, 2, '2000.00'),
(2, 1, '1.00'),
(2, 2, '1000.00'),
(3, 1, '1.00'),
(3, 2, '1000.00'),
(4, 1, '1.00'),
(4, 2, '1000.00'),
(4, 3, '5.00'),
(4, 2, '2000.00'),
(5, 1, '1.00'),
(5, 2, '1000.00'),
(5, 3, '5.00'),
(5, 2, '2000.00'),
(6, 1, '1.00'),
(6, 2, '1000.00'),
(6, 3, '5.00'),
(6, 2, '2000.00'),
(7, 1, '1.00'),
(7, 2, '1000.00'),
(8, 1, '1.00'),
(8, 2, '1000.00'),
(9, 1, '1.00'),
(9, 2, '1000.00');

-- --------------------------------------------------------

--
-- Table structure for table `reconciliation`
--

DROP TABLE IF EXISTS `reconciliation`;
CREATE TABLE IF NOT EXISTS `reconciliation` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `inventoryID` int(255) NOT NULL,
  `current` int(255) NOT NULL,
  `remarks` text,
  `date` varchar(255) NOT NULL,
  `timestamp` timestamp(6) NOT NULL,
  `adminID` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reconciliation`
--

INSERT INTO `reconciliation` (`id`, `inventoryID`, `current`, `remarks`, `date`, `timestamp`, `adminID`) VALUES
(1, 1, 12, '', '2019-11-21', '2019-11-20 17:01:33.000000', 1),
(2, 1, 34, '', '2019-11-21', '2019-11-20 17:01:33.000000', 1),
(3, 1, 5, '', '2019-11-21', '2019-11-20 17:01:33.000000', 1),
(4, 5, 5, '', '2019-11-21', '2019-11-20 17:01:33.000000', 1),
(5, 5, 3, '', '2019-11-21', '2019-11-20 17:01:33.000000', 1),
(6, 1, 23, '', '2019-11-21', '2019-11-20 17:05:30.000000', 1),
(7, 1, 45, '', '2019-11-21', '2019-11-20 17:05:30.000000', 1),
(8, 1, 5, '', '2019-11-21', '2019-11-20 17:05:30.000000', 1),
(9, 5, 2, '', '2019-11-21', '2019-11-20 17:05:30.000000', 1),
(10, 5, 34, '', '2019-11-21', '2019-11-20 17:05:30.000000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `spoilage`
--

DROP TABLE IF EXISTS `spoilage`;
CREATE TABLE IF NOT EXISTS `spoilage` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `inventoryID` int(255) NOT NULL,
  `quantity` decimal(65,2) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `spoilagedate` varchar(255) NOT NULL,
  `timestamp` timestamp(6) NOT NULL,
  `adminID` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `spoilage`
--

INSERT INTO `spoilage` (`id`, `inventoryID`, `quantity`, `remarks`, `spoilagedate`, `timestamp`, `adminID`) VALUES
(1, 1, '-2.00', 'full maggots', '2019-11-20', '2019-11-20 15:49:50.000000', 1),
(2, 3, '-2.00', 'wa', '2019-11-21', '2019-11-22 05:20:27.000000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `uom`
--

DROP TABLE IF EXISTS `uom`;
CREATE TABLE IF NOT EXISTS `uom` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `uomname` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`uomname`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uom`
--

INSERT INTO `uom` (`id`, `uomname`) VALUES
(6, ''),
(1, 'kg'),
(4, 'l'),
(5, 'ml'),
(2, 'pcs');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
