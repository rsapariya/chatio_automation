-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 08, 2023 at 02:15 AM
-- Server version: 8.0.32-0ubuntu0.20.04.2
-- PHP Version: 5.6.40-65+ubuntu20.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `maximumsettings`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_quick_validation`
--

CREATE TABLE `api_quick_validation` (
  `id` int NOT NULL,
  `ip_address` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `vmid` int DEFAULT NULL,
  `function` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `data` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `response` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `api_quick_validation`
--

INSERT INTO `api_quick_validation` (`id`, `ip_address`, `vmid`, `function`, `status`, `data`, `response`, `created`) VALUES
(5107, '23.178.0.22', NULL, 'account_create', 'pending', 'tkg9sZRtlqMcTKmgfIHwjDf+n85f/YzRhvWbITa/mmSJZ2zEOtfxr8guIuBKvjgJc1DHyfUF8uMvXn58YSeXuthvguDZD+Y7MuWYbGQzo08vSLGhleo3AiScEV3fzM8t5+9NSW8r0TFq2aPtRjjHWZnxgdqF3jLWoa6hLRz17MCk4cRTVb5T5zXu2sr3JLS9E/779lnK1Vf5wF5rLR+0U9/ZTn9r8KJXH2Bay1ZWG7zIxPCOhE+cGnxoSiKL1StdF3k+SOOm7TJFfuTjKGfsDAJELOnTPD7pwa7+SCyfuY85HkFpMjhdjS4wKAPA71DCj7r1huOTfRd6i3cccO+DmTzH9L53jLWSOjhDfV3Ooc2ezdxknGB+jO7n3R2UlNtyalEQGULopVCnCdQAkEOIp9DHYGPvNArafEyTeALMOsqGnZtjV5IVPFdb/8bIb4EPhdpo8810iPhlRFPvb9t3xm66MskcJCGG+IVKW7QQHkp1uYHtHMnyASycT1ZhZvGbeGbDiaTq8zQUFfroYZJmOZPxrNBohJR3Qg//ZwnGZ4Ox57hdbjSMRzCnwbdSNChFwYFXTut3uDZm2zl8V3VYLvgUOa/h2V82XK+VsVc5P0d+5SxqLpEdr5DWLlkShMtrCwmgWkyvvfDNaWnZz0PsUUP1pQ89SU2VLX+dHZBQAa7Lsekis0vimTefxuPA/oobQccpOozqg8tfXwdSYk8lKUy1MlaU789BYnuu+cc8vLPm+9cvmMXpV5BGLgOYMtAMFMBiJFH/aZWZ+pCoSqJ67C5spK7uD7pW+XtkwRagDrWWGikXInNkiZ593wdKPkD9vMqmRZy9C1KEAJ0Hwji1FwHRygj1vgT+GERnVNWwwut9Jj0qlm8jjeKXRkAa::73d7a1d37bd94cb0970d916a0d5c460b', NULL, '2023-06-08 06:13:29');
--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_quick_validation`
--
ALTER TABLE `api_quick_validation`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_quick_validation`
--
ALTER TABLE `api_quick_validation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5108;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
