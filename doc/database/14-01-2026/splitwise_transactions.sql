-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 13, 2026 at 06:44 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vtrack`
--

-- --------------------------------------------------------

--
-- Table structure for table `splitwise_transactions`
--

DROP TABLE IF EXISTS `splitwise_transactions`;
CREATE TABLE IF NOT EXISTS `splitwise_transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `friend_id` int DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `remarks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `trans_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DEBIT',
  `user_id` int DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `splitwise_transactions`
--

INSERT INTO `splitwise_transactions` (`id`, `friend_id`, `amount`, `remarks`, `trans_type`, `user_id`, `status`, `date`, `update_at`) VALUES
(21, 3, 66.67, 'test', 'DEBIT', 1, 1, '2026-01-14 00:03:07', NULL),
(22, 6, 66.67, 'test', 'DEBIT', 1, 1, '2026-01-14 00:03:07', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
