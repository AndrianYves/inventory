-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 18, 2019 at 07:09 AM
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
(1, 'superadmin', '$2y$10$SB5nbD.QlZ/Yl0JvWHH.sOKMMTTDCBhQr4DKBGO8vEpGCKYWa0TCK', 'superadmin@gmail.com', 'superadminFirst', 'superadminLast', 'Super User', '2019-11-17 05:03:06.000000'),
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `categoryname`) VALUES
(1, 'meat'),
(2, 'fruits'),
(3, 'condiments');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `itemname` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `categoryID` int(255) DEFAULT NULL,
  `unitID` int(255) DEFAULT NULL,
  `timestamp` timestamp(6) NOT NULL,
  `adminID` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `itemname`, `description`, `quantity`, `categoryID`, `unitID`, `timestamp`, `adminID`) VALUES
(1, 'apple', 'green from japan', 0, 2, 1, '2019-11-16 12:37:08.000000', 1),
(2, 'chicken', 'full grown', 0, 1, 1, '2019-11-16 12:39:47.000000', 1),
(3, 'vinegar', 'vinegar 1l', 0, 3, 4, '2019-11-16 12:51:31.000000', 1),
(4, 'potato', 'potato from soil', 10, 2, 2, '2019-11-16 12:51:31.000000', 1),
(5, 'beef', 'beef from cow', 5, 1, 2, '2019-11-16 12:51:31.000000', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `description`, `timestamp`, `adminID`) VALUES
(1, 'adobe apple', 'adobe with apples', '2019-11-16 13:07:41.000000', 1),
(2, 'adobe water', 'adobe with just water', '2019-11-16 13:56:25.000000', 1),
(3, '123', '321', '2019-11-17 09:12:10.000000', 1),
(4, 'dick stew', 'dick in stew', '2019-11-17 09:12:10.000000', 1),
(5, 'potato stew', 'potato in stew', '2019-11-17 09:12:10.000000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `menuitems`
--

DROP TABLE IF EXISTS `menuitems`;
CREATE TABLE IF NOT EXISTS `menuitems` (
  `menuID` int(255) NOT NULL,
  `inventoryID` int(255) NOT NULL,
  `quantity` decimal(65,2) NOT NULL
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
(3, 1, '123.00'),
(3, 1, '321.00'),
(4, 3, '2.00'),
(4, 4, '2.00'),
(5, 5, '5.00'),
(5, 3, '5.00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `qtyMenu` bigint(255) DEFAULT NULL,
  `menu_id` bigint(255) DEFAULT NULL,
  `timestamp` timestamp(6) NULL DEFAULT NULL,
  `addItemId` int(255) DEFAULT NULL,
  `addOrderQty` bigint(255) DEFAULT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `qtyMenu`, `menu_id`, `timestamp`, `addItemId`, `addOrderQty`) VALUES
(1, 22, 1, '2019-11-17 09:12:10.000000', 1, 2),
(2, 33, 2, '2019-11-17 09:12:10.000000', 2, 2),
(3, 44, 3, '2019-11-17 09:12:10.000000', 3, 2),
(4, 55, 4, '2019-11-17 09:12:10.000000', 4, 2),
(5, 66, 5, '2019-11-17 09:12:10.000000', 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

DROP TABLE IF EXISTS `returns`;
CREATE TABLE IF NOT EXISTS `returns` (
  `return_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint(255) DEFAULT NULL,
  `return_type` enum('spoilage','cancel','return') DEFAULT NULL,
  `return_date` timestamp(6) NULL DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`return_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`return_id`, `inventory_id`, `return_type`, `return_date`, `remarks`) VALUES
(1, 1, 'spoilage', '2019-11-17 09:12:10.000000', 'spoiled'),
(2, 2, 'cancel', '2019-11-17 09:12:10.000000', 'canceled'),
(3, 3, 'return', '2019-11-17 09:12:10.000000', 'returned'),
(4, 4, 'spoilage', '2019-11-17 09:12:10.000000', 'spolied'),
(5, 5, 'cancel', '2019-11-17 09:12:10.000000', 'canceled'),
(6, 1, 'return', '2019-11-17 09:12:10.000000', 'returned');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uom`
--

INSERT INTO `uom` (`id`, `uomname`) VALUES
(1, 'kg'),
(4, 'l'),
(2, 'pcs');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
