-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307:3307
-- Generation Time: May 06, 2025 at 11:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_system`
--

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'Cleaning'),
(2, 'Cleaning'),
(3, 'Brushes & Tools'),
(4, 'Floor Cleaner');

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_no`, `item_name`, `category`, `quantity`, `item_unit`, `restock_point`, `status`, `category_id`) VALUES
(1, 0, 'Floor wax', 'Cleaning', 1, 'pcs', 3, '', NULL),
(2, 0, 'Dustpan', 'Cleaning', 5, 'pcs', 2, '', NULL),
(3, 3, 'Broom Stick', 'Cleaning', 10, 'pcs', 3, '', NULL),
(4, 0, 'QQQQQQ', 'Brushes & Tools', 10, 'pcs', 3, 'Sufficient', NULL),
(5, 0, 'WWWWW', 'Floor Cleaners', 3, 'pcs', 2, 'Sufficient', NULL),
(6, 0, 'rrr', 'Brushes & Tools', 5, 'pcs', 3, 'Sufficient', NULL),
(7, 0, 'hh', 'Brushes & Tools', 5, 'pcs', 3, 'Sufficient', NULL);

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Admin'),
(2, 'Staff');

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `first_name`, `last_name`, `role_id`, `status`) VALUES
(1, 'Yohan', 'Yohan', 'Jhay', 'Peñaloza', 1, 'Active'),
(2, 'Elma', 'Elma', 'Elma', 'Guab', 2, 'Inactive'),
(3, 'Fuentes', '12345', 'Mark James', 'Fuentes', 2, 'Active'),
(4, 'Harson', '123', 'Harson', 'Velgado', 2, 'Active');

--
-- Dumping data for table `users_backup`
--

INSERT INTO `users_backup` (`user_id`, `username`, `password`, `role_id`) VALUES
(1, 'jhay', 'jhay', 1),
(2, 'elma', 'elma', 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
