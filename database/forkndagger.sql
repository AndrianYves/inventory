-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 20, 2020 at 12:26 AM
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
  `status` enum('Active','Block') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `firstname`, `lastname`, `role`, `lastlogin`, `status`) VALUES
(1, 'superadmin', '$2y$10$SB5nbD.QlZ/Yl0JvWHH.sOKMMTTDCBhQr4DKBGO8vEpGCKYWa0TCK', 'superadmin@gmail.com', 'superadminFirst', 'superadminLast', 'Super User', '2020-01-19 13:53:16.000000', 'Active'),
(2, 'admin', '$2y$10$9pXipDwls1/S7d69Sq7TMu82yCBAh8B5HKCqBXGw3oEl.P2s0qPVC', 'admin@gmail.com', 'adminFirst', 'adminLast', 'Admin', '2020-01-01 01:21:27.000000', 'Block'),
(5, 'superuser1', '$2y$10$9vTcDONC8FOhqeq8Jo0cRuUYPOXB33jMTYYyDqCirpdGaK.iX9Z1y', '12345@gmail.com', 'andrian yves', 'macalino', 'Super User', NULL, 'Active');

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

-- --------------------------------------------------------

--
-- Table structure for table `orderlist`
--

DROP TABLE IF EXISTS `orderlist`;
CREATE TABLE IF NOT EXISTS `orderlist` (
  `orderID` int(255) NOT NULL,
  `menuID` int(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `total` int(11) DEFAULT NULL,
  `delivered` int(11) DEFAULT '0',
  `canceled_returned_order` int(11) DEFAULT '0',
  `status` enum('Delivered','Canceled','Returned','Pending') DEFAULT NULL,
  `remarks` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` bigint(255) NOT NULL,
  `table_number` int(255) NOT NULL,
  `timestamp` timestamp(6) NULL DEFAULT NULL,
  `status` enum('Pending','Canceled','Completed') DEFAULT NULL,
  `lastUpdatedStatus` timestamp(6) NULL DEFAULT NULL,
  `adminID` int(255) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

DROP TABLE IF EXISTS `tables`;
CREATE TABLE IF NOT EXISTS `tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tablenumber` int(11) NOT NULL,
  `status` enum('Vacant','Occupied') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `tablenumber`, `status`) VALUES
(1, 1, 'Vacant'),
(2, 2, 'Vacant'),
(3, 3, 'Vacant'),
(4, 4, 'Vacant'),
(5, 5, 'Vacant'),
(6, 6, 'Vacant'),
(7, 7, 'Vacant'),
(8, 8, 'Vacant'),
(9, 9, 'Vacant'),
(10, 10, 'Vacant'),
(11, 11, 'Vacant'),
(12, 12, 'Vacant'),
(13, 13, 'Vacant'),
(14, 14, 'Vacant'),
(15, 15, 'Vacant'),
(16, 16, 'Vacant'),
(17, 17, 'Vacant'),
(18, 18, 'Vacant');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
