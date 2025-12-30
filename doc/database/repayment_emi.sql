-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 30, 2025 at 12:05 PM
-- Server version: 9.1.0
-- PHP Version: 8.4.0

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
-- Table structure for table `repayment_emi`
--

DROP TABLE IF EXISTS `repayment_emi`;
CREATE TABLE IF NOT EXISTS `repayment_emi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `payee` int DEFAULT NULL,
  `source` int DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `pr_fee` float DEFAULT NULL,
  `emi` float DEFAULT NULL,
  `duration` float DEFAULT NULL,
  `distributed_date` date DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `remarks` longtext,
  `user_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `repayment_emi`
--

INSERT INTO `repayment_emi` (`id`, `payee`, `source`, `amount`, `pr_fee`, `emi`, `duration`, `distributed_date`, `payment_date`, `remarks`, `user_id`, `status`, `date`, `update_at`) VALUES
(4, 1, 1, 5000, 199, 1200, 5, '2026-12-30', '2026-01-26', 'Test', 1, 1, '2025-12-30 12:53:24', NULL),
(3, 1, 1, 5000, 199, 1200, 5, '2026-12-30', '2026-01-26', 'Test', 1, 1, '2025-12-30 12:52:30', NULL),
(5, 1, 1, 5000, 199, 1100, 5, '2026-12-30', '2026-01-26', NULL, 1, 1, '2025-12-30 12:57:52', NULL),
(6, 1, 1, 500, 199, 50, 5, '2026-12-30', '2026-01-26', NULL, 1, 1, '2025-12-30 13:01:50', NULL),
(7, 1, 1, 5000, 199, 1100, 5, '2026-12-30', '2026-01-26', 'remarks', 1, 1, '2025-12-30 13:03:05', NULL),
(8, 1, 1, 10000, 199, 1000, 10, '2026-12-30', '2026-01-09', NULL, 1, 1, '2025-12-30 14:16:11', NULL),
(9, 3, 1, 1000, 199, 1000, 5, '2026-12-30', '2026-01-30', NULL, 1, 1, '2025-12-30 14:17:53', NULL),
(10, 1, 1, 10000, 199, 1000, 10, '2026-12-30', '2026-01-30', NULL, 1, 1, '2025-12-30 14:26:07', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
