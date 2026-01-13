-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 13, 2026 at 12:06 PM
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
-- Table structure for table `splitwise_debit`
--

DROP TABLE IF EXISTS `splitwise_debit`;
CREATE TABLE IF NOT EXISTS `splitwise_debit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `friend_id` int DEFAULT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `remarks` longtext COLLATE utf8mb4_unicode_ci,
  `user_id` int DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `splitwise_debit`
--

INSERT INTO `splitwise_debit` (`id`, `friend_id`, `amount`, `remarks`, `user_id`, `status`, `date`, `update_at`) VALUES
(10, 5, 66.67, NULL, 1, 1, '2026-01-13 16:08:39', NULL),
(9, 3, 66.67, NULL, 1, 1, '2026-01-13 16:08:39', NULL),
(8, 5, 100, NULL, 1, 1, '2026-01-13 16:08:34', NULL),
(7, 3, 100, NULL, 1, 1, '2026-01-13 16:08:34', NULL),
(11, 3, 66.67, 'test', 1, 1, '2026-01-13 16:41:33', NULL),
(12, 5, 66.67, 'test', 1, 1, '2026-01-13 16:41:33', NULL),
(13, 3, 66.67, 'test', 1, 1, '2026-01-13 16:42:42', NULL),
(14, 5, 66.67, 'test', 1, 1, '2026-01-13 16:42:42', NULL),
(15, 3, 66.67, 'test', 1, 1, '2026-01-13 16:42:49', NULL),
(16, 5, 66.67, 'test', 1, 1, '2026-01-13 16:42:49', NULL),
(17, 3, 66.67, 'test', 1, 1, '2026-01-13 16:42:50', NULL),
(18, 5, 66.67, 'test', 1, 1, '2026-01-13 16:42:50', NULL),
(19, 3, 66.67, 'test', 1, 1, '2026-01-13 16:42:51', NULL),
(20, 5, 66.67, 'test', 1, 1, '2026-01-13 16:42:51', NULL),
(21, 3, 66.67, 'test', 1, 1, '2026-01-13 16:42:54', NULL),
(22, 5, 66.67, 'test', 1, 1, '2026-01-13 16:42:54', NULL),
(23, 3, 66.67, 'dgsf', 1, 1, '2026-01-13 16:43:05', NULL),
(24, 5, 66.67, 'dgsf', 1, 1, '2026-01-13 16:43:05', NULL),
(25, 3, 66.67, 'fdsf', 1, 1, '2026-01-13 16:44:52', NULL),
(26, 5, 66.67, 'fdsf', 1, 1, '2026-01-13 16:44:52', NULL),
(27, 3, 1818, 'dfds', 1, 1, '2026-01-13 16:45:06', NULL),
(28, 5, 1818, 'dfds', 1, 1, '2026-01-13 16:45:06', NULL),
(29, 3, 66.67, 'dsfsd', 1, 1, '2026-01-13 16:45:29', NULL),
(30, 5, 66.67, 'dsfsd', 1, 1, '2026-01-13 16:45:29', NULL),
(31, 3, 66.67, NULL, 1, 1, '2026-01-13 17:18:16', NULL),
(32, 5, 66.67, NULL, 1, 1, '2026-01-13 17:18:16', NULL),
(33, 3, 66.67, NULL, 1, 1, '2026-01-13 17:18:18', NULL),
(34, 5, 66.67, NULL, 1, 1, '2026-01-13 17:18:18', NULL),
(35, 3, 66.67, NULL, 1, 1, '2026-01-13 17:18:18', NULL),
(36, 5, 66.67, NULL, 1, 1, '2026-01-13 17:18:18', NULL),
(37, 3, 66.67, NULL, 1, 1, '2026-01-13 17:18:19', NULL),
(38, 5, 66.67, NULL, 1, 1, '2026-01-13 17:18:19', NULL),
(39, 4, 66.67, 'tets', 1, 1, '2026-01-13 17:19:02', NULL),
(40, 6, 66.67, 'tets', 1, 1, '2026-01-13 17:19:02', NULL),
(41, 3, 100, NULL, 1, 1, '2026-01-13 17:22:29', NULL),
(42, 3, 100, NULL, 1, 1, '2026-01-13 17:22:32', NULL),
(43, 3, 100, NULL, 1, 1, '2026-01-13 17:22:39', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
