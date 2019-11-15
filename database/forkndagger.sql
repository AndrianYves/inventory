-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 15, 2019 at 09:10 PM
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `firstname`, `lastname`, `role`) VALUES
(1, 'superadmin', '$2y$10$k8N/zvkpiASI/5Cjfyh4qO690dzm6yFA7ukJ62YgbHpQNimyMD6Zq', 'superadmin@gmail.com', 'superadminFirst', 'superadminLast', 'Super User'),
(2, 'admin', '$2y$10$9pXipDwls1/S7d69Sq7TMu82yCBAh8B5HKCqBXGw3oEl.P2s0qPVC', 'admin@gmail.com', 'adminFirst', 'adminLast', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `categoryname` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `categoryname`) VALUES
(1, 'meat'),
(2, 'fruits'),
(3, 'liquid');

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
  `categoryID` int(255) NOT NULL,
  `unitID` int(255) NOT NULL,
  `timestamp` timestamp(6) NOT NULL,
  `adminID` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `itemname`, `description`, `quantity`, `categoryID`, `unitID`, `timestamp`, `adminID`) VALUES
(1, 'chicken', 'full grown', 28, 1, 1, '2019-11-15 10:39:16.000000', 1),
(2, 'apple', 'green and came from japan.', 4, 2, 1, '2019-11-15 10:42:58.000000', 1),
(3, 'beef', 'fresh from pampanga.', 5, 1, 1, '2019-11-15 10:54:45.000000', 1),
(4, 'water', 'free water', 0, 3, 2, '2019-11-15 11:42:38.000000', 1);

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
(1, 'adobe apple', 'adobo with apple', '2019-11-15 12:39:03.000000', 1),
(2, 'water beef', 'water beef for men', '2019-11-15 12:47:52.000000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `menuitems`
--

DROP TABLE IF EXISTS `menuitems`;
CREATE TABLE IF NOT EXISTS `menuitems` (
  `menuID` int(255) NOT NULL,
  `inventoryID` int(255) NOT NULL,
  `quantity` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menuitems`
--

INSERT INTO `menuitems` (`menuID`, `inventoryID`, `quantity`) VALUES
(1, 1, 2),
(1, 2, 5),
(2, 3, 3),
(2, 4, 5);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uom`
--

INSERT INTO `uom` (`id`, `uomname`) VALUES
(1, 'kg'),
(2, 'l');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
