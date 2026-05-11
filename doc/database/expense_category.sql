-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 11, 2026 at 05:52 PM
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
-- Table structure for table `expense_category`
--

DROP TABLE IF EXISTS `expense_category`;
CREATE TABLE IF NOT EXISTS `expense_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expense_category`
--

INSERT INTO `expense_category` (`id`, `name`, `user_id`, `status`, `date`, `update_at`) VALUES
(4, 'Groceries 🧺', 0, 1, '2026-05-11 21:06:47', '2026-05-11 21:17:47'),
(3, 'Food 🍔', 0, 1, '2026-05-11 21:03:44', '2026-05-11 21:17:47'),
(5, 'Rent 🏠', 0, 1, '2026-05-11 21:07:13', '2026-05-11 21:17:47'),
(6, 'Utilities ⚡', 0, 1, '2026-05-11 21:07:43', '2026-05-11 21:17:47'),
(7, 'Internet 🌐', 0, 1, '2026-05-11 21:08:00', '2026-05-11 21:17:47'),
(8, 'Mobile Recharge 📲', 0, 1, '2026-05-11 21:08:30', '2026-05-11 21:17:47'),
(9, 'Transport 🚌', 0, 1, '2026-05-11 21:08:57', '2026-05-11 21:17:47'),
(10, 'Fuel ⛽', 0, 1, '2026-05-11 21:09:08', '2026-05-11 21:17:47'),
(11, 'Shopping 🛍️', 0, 1, '2026-05-11 21:09:32', '2026-05-11 21:17:47'),
(12, 'Entertainment🎵', 0, 1, '2026-05-11 21:09:41', '2026-05-11 21:17:47'),
(13, 'Health 🏥', 0, 1, '2026-05-11 21:09:59', '2026-05-11 21:17:47'),
(14, 'Education 🎓', 0, 1, '2026-05-11 21:10:17', '2026-05-11 21:17:47'),
(15, 'Insurance 🥽', 0, 1, '2026-05-11 21:10:40', '2026-05-11 21:17:47'),
(16, 'Investments 💰', 0, 1, '2026-05-11 21:11:00', '2026-05-11 21:17:47'),
(17, 'Savings 🛟', 0, 1, '2026-05-11 21:11:18', '2026-05-11 21:17:47'),
(18, 'Travel ✈️', 0, 1, '2026-05-11 21:11:32', '2026-05-11 21:17:47'),
(19, 'Gifts 🎁', 0, 1, '2026-05-11 21:11:43', '2026-05-11 21:17:47'),
(20, 'Subscriptions 📺', 0, 1, '2026-05-11 21:11:58', '2026-05-11 21:17:47'),
(21, 'Bills 💸', 0, 1, '2026-05-11 21:12:19', '2026-05-11 21:17:47'),
(22, 'Maintenance ⛑️', 0, 1, '2026-05-11 21:12:54', '2026-05-11 21:17:47'),
(23, 'Personal Care 💇', 0, 1, '2026-05-11 21:13:11', '2026-05-11 21:17:47'),
(24, 'Family 👪', 0, 1, '2026-05-11 21:13:26', '2026-05-11 21:17:47'),
(25, 'Donations 🙏', 0, 1, '2026-05-11 21:14:13', '2026-05-11 21:17:47'),
(26, 'Taxes 📈', 0, 1, '2026-05-11 21:15:12', '2026-05-11 21:17:47'),
(27, 'Business 🏪', 0, 1, '2026-05-11 21:15:33', '2026-05-11 21:17:47'),
(28, 'Miscellaneous 🎈', 0, 1, '2026-05-11 21:16:59', '2026-05-11 21:17:47'),
(29, 'Other 🤷', 1, 1, '2026-05-11 21:24:31', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
