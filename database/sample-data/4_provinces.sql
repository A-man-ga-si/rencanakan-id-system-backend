-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 23, 2022 at 02:15 PM
-- Server version: 10.3.32-MariaDB-cll-lve
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u6592316_beta_rencanakan`
--

-- --------------------------------------------------------

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `name`, `created_at`, `updated_at`) VALUES
(11, 'ACEH', '2021-10-24 15:48:57', NULL),
(12, 'SUMATERA UTARA', '2021-10-24 15:48:57', NULL),
(13, 'SUMATERA BARAT', '2021-10-24 15:48:57', NULL),
(14, 'RIAU', '2021-10-24 15:48:57', NULL),
(15, 'JAMBI', '2021-10-24 15:48:57', NULL),
(16, 'SUMATERA SELATAN', '2021-10-24 15:48:57', NULL),
(17, 'BENGKULU', '2021-10-24 15:48:57', NULL),
(18, 'LAMPUNG', '2021-10-24 15:48:57', NULL),
(19, 'KEPULAUAN BANGKA BELITUNG', '2021-10-24 15:48:57', NULL),
(21, 'KEPULAUAN RIAU', '2021-10-24 15:48:57', NULL),
(31, 'DKI JAKARTA', '2021-10-24 15:48:57', NULL),
(32, 'JAWA BARAT', '2021-10-24 15:48:57', NULL),
(33, 'JAWA TENGAH', '2021-10-24 15:48:57', NULL),
(34, 'DI YOGYAKARTA', '2021-10-24 15:48:57', NULL),
(35, 'JAWA TIMUR', '2021-10-24 15:48:57', NULL),
(36, 'BANTEN', '2021-10-24 15:48:57', NULL),
(51, 'BALI', '2021-10-24 15:48:57', NULL),
(52, 'NUSA TENGGARA BARAT', '2021-10-24 15:48:57', NULL),
(53, 'NUSA TENGGARA TIMUR', '2021-10-24 15:48:57', NULL),
(61, 'KALIMANTAN BARAT', '2021-10-24 15:48:57', NULL),
(62, 'KALIMANTAN TENGAH', '2021-10-24 15:48:57', NULL),
(63, 'KALIMANTAN SELATAN', '2021-10-24 15:48:57', NULL),
(64, 'KALIMANTAN TIMUR', '2021-10-24 15:48:57', NULL),
(65, 'KALIMANTAN UTARA', '2021-10-24 15:48:57', NULL),
(71, 'SULAWESI UTARA', '2021-10-24 15:48:57', NULL),
(72, 'SULAWESI TENGAH', '2021-10-24 15:48:57', NULL),
(73, 'SULAWESI SELATAN', '2021-10-24 15:48:57', NULL),
(74, 'SULAWESI TENGGARA', '2021-10-24 15:48:57', NULL),
(75, 'GORONTALO', '2021-10-24 15:48:57', NULL),
(76, 'SULAWESI BARAT', '2021-10-24 15:48:57', NULL),
(81, 'MALUKU', '2021-10-24 15:48:57', NULL),
(82, 'MALUKU UTARA', '2021-10-24 15:48:57', NULL),
(91, 'PAPUA BARAT', '2021-10-24 15:48:57', NULL),
(94, 'PAPUA', '2021-10-24 15:48:57', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
