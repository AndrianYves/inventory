-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 15, 2019 at 04:39 PM
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
-- Table structure for table `adduom`
--

DROP TABLE IF EXISTS `adduom`;
CREATE TABLE IF NOT EXISTS `adduom` (
  `uom_add` varchar(20) NOT NULL,
  `uom_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`uom_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `adduom`
--

INSERT INTO `adduom` (`uom_add`, `uom_id`) VALUES
('dsds', 1),
('eqwe', 2),
('dasdsa', 3),
('asdsadsa', 4),
('dsadsa', 5),
('hehe', 6),
('audy', 7);

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
-- Table structure for table `cancel`
--

DROP TABLE IF EXISTS `cancel`;
CREATE TABLE IF NOT EXISTS `cancel` (
  `cancel_order` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `cancel_id` int(10) NOT NULL AUTO_INCREMENT,
  `cancel_remarks` varchar(150) NOT NULL,
  PRIMARY KEY (`cancel_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `category` varchar(20) NOT NULL,
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `deduction`
--

DROP TABLE IF EXISTS `deduction`;
CREATE TABLE IF NOT EXISTS `deduction` (
  `menu_name` varchar(150) NOT NULL,
  `quantity` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `itemname` varchar(45) NOT NULL,
  `category` char(45) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unitofmeasurement` char(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `itemname`, `category`, `quantity`, `unitofmeasurement`) VALUES
(1, 'asd', 'dry goods', 0, 'ds'),
(2, 'dsadasd', 'dry goods', 0, 'Piraso'),
(3, 'Ketchup', 'groceries', 0, 'mg'),
(4, 'patrick', 'dry goods', 0, 'mg'),
(5, 'keke', 'wet goods', 0, 'audy');

-- --------------------------------------------------------

--
-- Table structure for table `ledger`
--

DROP TABLE IF EXISTS `ledger`;
CREATE TABLE IF NOT EXISTS `ledger` (
  `item_name` varchar(50) NOT NULL,
  `uom` int(50) NOT NULL,
  `balance` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `desciption` varchar(150) NOT NULL,
  `menu_id` int(10) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(45) NOT NULL,
  `quantity` int(15) NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reconcilation`
--

DROP TABLE IF EXISTS `reconcilation`;
CREATE TABLE IF NOT EXISTS `reconcilation` (
  `item_name` varchar(150) NOT NULL,
  `inventory_quantity` int(50) NOT NULL,
  `current_quantity` int(50) NOT NULL,
  `remarks` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `relational`
--

DROP TABLE IF EXISTS `relational`;
CREATE TABLE IF NOT EXISTS `relational` (
  `relational_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `uom_id` int(11) NOT NULL,
  PRIMARY KEY (`relational_id`),
  KEY `id` (`id`),
  KEY `uom_id` (`uom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

DROP TABLE IF EXISTS `returns`;
CREATE TABLE IF NOT EXISTS `returns` (
  `return_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_table` int(40) NOT NULL,
  `return_order` varchar(50) NOT NULL,
  `quantity_order` int(40) NOT NULL,
  `remarks` varchar(150) NOT NULL,
  PRIMARY KEY (`return_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `spoilage`
--

DROP TABLE IF EXISTS `spoilage`;
CREATE TABLE IF NOT EXISTS `spoilage` (
  `spoilage_id` int(10) NOT NULL AUTO_INCREMENT,
  `spoilage_date` varchar(150) NOT NULL,
  `category` varchar(150) NOT NULL,
  PRIMARY KEY (`spoilage_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `relational`
--
ALTER TABLE `relational`
  ADD CONSTRAINT `relational_ibfk_1` FOREIGN KEY (`id`) REFERENCES `inventory` (`id`),
  ADD CONSTRAINT `relational_ibfk_2` FOREIGN KEY (`uom_id`) REFERENCES `adduom` (`uom_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
