-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 19, 2019 at 03:12 PM
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
(1, 'superadmin', '$2y$10$SB5nbD.QlZ/Yl0JvWHH.sOKMMTTDCBhQr4DKBGO8vEpGCKYWa0TCK', 'superadmin@gmail.com', 'superadminFirst', 'superadminLast', 'Super User', '2019-11-19 04:51:03.000000'),
(2, 'admin', '$2y$10$9pXipDwls1/S7d69Sq7TMu82yCBAh8B5HKCqBXGw3oEl.P2s0qPVC', 'admin@gmail.com', 'adminFirst', 'adminLast', 'Admin', '2019-11-16 13:11:38.000000'),
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `categoryname`) VALUES
(1, 'meat'),
(2, 'fruits'),
(3, 'condiments'),
(4, 'drinks');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `itemname`, `description`, `quantity`, `lowquantity`, `categoryID`, `unitID`, `timestamp`, `adminID`) VALUES
(1, 'green apple', 'green from japan', '2.49', '3.00', 2, 1, '2019-11-16 12:37:08.000000', 1),
(2, 'chicken', 'full grown', '0.00', '3.00', 1, 1, '2019-11-16 12:39:47.000000', 1),
(3, 'vinegar', 'vinegar 1l', '1.25', '5.00', 3, 4, '2019-11-16 12:51:31.000000', 1),
(4, 'purified water', 'purified water for drinks recipe.', '11.00', '5.00', 4, 5, '2019-11-17 02:49:00.000000', 1),
(5, 'watermelon', 'fresh from pampanga.', '10.00', '5.00', 2, 1, '2019-11-17 02:49:17.000000', 1),
(6, 'lemon', 'dole lemons.', '10.00', '5.00', 2, 1, '2019-11-17 02:49:41.000000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ledger`
--

DROP TABLE IF EXISTS `ledger`;
CREATE TABLE IF NOT EXISTS `ledger` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `inventoryID` int(255) NOT NULL,
  `quantity` decimal(65,2) NOT NULL,
  `transaction` enum('Inventory','Order','Cancel','Return','Spoilage') NOT NULL,
  `timestamp` timestamp(6) NOT NULL,
  `adminID` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ledger`
--

INSERT INTO `ledger` (`id`, `inventoryID`, `quantity`, `transaction`, `timestamp`, `adminID`) VALUES
(1, 1, '3.00', 'Inventory', '2019-11-19 06:45:38.000000', 1),
(2, 1, '-10.00', 'Inventory', '2019-11-19 06:50:26.000000', 1),
(3, 1, '3.00', 'Spoilage', '2019-11-19 06:55:41.000000', 1),
(4, 1, '-3.00', 'Spoilage', '2019-11-19 06:59:58.000000', 1),
(5, 1, '23.00', 'Inventory', '2019-11-19 14:02:05.000000', 1),
(6, 1, '-3.00', 'Inventory', '2019-11-19 04:02:59.000000', 1),
(7, 1, '2.00', 'Inventory', '2019-11-19 14:03:23.000000', 1),
(8, 4, '1.00', 'Inventory', '2019-11-19 14:03:54.000000', 1),
(9, 1, '1.00', 'Inventory', '2019-11-19 14:04:39.000000', 1),
(10, 1, '2.00', 'Inventory', '2019-11-19 14:04:44.000000', 1),
(11, 1, '-3.00', 'Inventory', '2019-11-19 14:04:59.000000', 1),
(12, 1, '-34.00', 'Inventory', '2019-11-19 14:06:07.000000', 1),
(13, 1, '-20.00', 'Inventory', '2019-11-19 14:06:12.000000', 1),
(14, 3, '-3.00', 'Inventory', '2019-11-19 14:06:20.000000', 1),
(15, 3, '-5.00', 'Inventory', '2019-11-19 14:06:26.000000', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `description`, `timestamp`, `adminID`) VALUES
(1, 'adobe apple', 'adobe with apples', '2019-11-16 13:07:41.000000', 1),
(2, 'adobe water', 'adobe with just water', '2019-11-16 13:56:25.000000', 1),
(3, 'sinigang', 'sinigang with fresh apples', '2019-11-17 00:24:20.000000', 1),
(4, 'apple juice', 'apple juice', '2019-11-17 02:50:02.000000', 1),
(5, 'watermelon juice', 'watermelon juice', '2019-11-17 02:50:31.000000', 1),
(6, 'watermelon apple', 'watermelon apple', '2019-11-17 02:51:14.000000', 1),
(7, 'lemon', 'lemon', '2019-11-17 02:51:30.000000', 1),
(8, 'sinigang na lemon', 'sinigang na lemon', '2019-11-17 03:35:13.000000', 1);

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
(1, 1, '3.00'),
(1, 2, '1.00'),
(1, 3, '0.25'),
(2, 2, '1.00'),
(2, 3, '0.50'),
(3, 1, '0.25'),
(3, 2, '2.00'),
(3, 3, '0.25'),
(4, 1, '2.00'),
(4, 4, '250.00'),
(5, 5, '0.50'),
(5, 4, '250.00'),
(6, 1, '0.50'),
(6, 5, '0.50'),
(6, 4, '500.00'),
(7, 6, '0.50'),
(7, 5, '250.00'),
(8, 3, '0.25'),
(8, 5, '0.50'),
(8, 4, '0.25');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` bigint(255) NOT NULL,
  `qtyMenu` bigint(255) DEFAULT NULL,
  `menu_id` bigint(255) DEFAULT NULL,
  `timestamp` timestamp(6) NULL DEFAULT NULL,
  `status` enum('Pending','Canceled','Returned','Delivered') DEFAULT NULL,
  `lastUpdatedStatus` timestamp(6) NULL DEFAULT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `qtyMenu`, `menu_id`, `timestamp`, `status`, `lastUpdatedStatus`) VALUES
(1, 2, 1, '2019-11-18 22:40:38.000000', 'Delivered', '2019-11-19 06:20:43.000000');

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
(1, 1, '6.00'),
(1, 2, '2.00'),
(1, 3, '0.50'),
(2, 1, '12.00'),
(2, 2, '4.00'),
(2, 3, '1.00');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reconciliation`
--

INSERT INTO `reconciliation` (`id`, `inventoryID`, `current`, `remarks`, `date`, `timestamp`, `adminID`) VALUES
(1, 1, 5, 'excess', '2019-11-17', '2019-11-17 09:46:30.000000', 1),
(2, 6, 10, 'missing', '2019-11-17', '2019-11-17 09:46:30.000000', 1),
(3, 1, 5, '', '2019-11-17', '2019-11-17 09:46:30.000000', 1),
(4, 5, 10, '', '2019-11-17', '2019-11-17 09:46:30.000000', 1),
(5, 4, 7, 'missing', '2019-11-17', '2019-11-17 09:46:30.000000', 1),
(6, 1, 10, '', '2019-11-17', '2019-11-17 09:46:30.000000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

DROP TABLE IF EXISTS `returns`;
CREATE TABLE IF NOT EXISTS `returns` (
  `return_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint(255) DEFAULT NULL,
  `menu_id` bigint(255) DEFAULT NULL,
  `return_type` enum('spoilage','cancel','return') DEFAULT NULL,
  `return_date` timestamp(6) NULL DEFAULT NULL,
  `return_qty` bigint(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`return_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uom`
--

INSERT INTO `uom` (`id`, `uomname`) VALUES
(1, 'kg'),
(4, 'l'),
(5, 'ml'),
(2, 'pcs');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
