-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2026 at 01:27 AM
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
-- Database: `my_notes`
--

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--


CREATE TABLE IF NOT EXISTS `users` (
  `user_id` varchar(50) NOT NULL,
  `user_email` varchar(150) DEFAULT NULL,
  `user_password` varchar(150) DEFAULT NULL,
  `is_activated` tinyint(1) NOT NULL DEFAULT 0,
  `activation_token` varchar(64) DEFAULT NULL,
  `reset_token_hash` varchar(240) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `username` varchar(250) DEFAULT NULL,
  `profile_picture` text DEFAULT NULL,
  `profile_picture_mime` varchar(50) DEFAULT NULL,

  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email` (`user_email`),
  UNIQUE KEY `reset_token_hash` (`reset_token_hash`),
  UNIQUE KEY `reset_token_expires_at` (`reset_token_expires_at`),
  UNIQUE KEY `username` (`username`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `notes` (
  `note_id` varchar(50) NOT NULL,
  `note_title` varchar(300) DEFAULT NULL,
  `note_content` text DEFAULT NULL,
  `note_date` datetime DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,

  PRIMARY KEY (`note_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--



--
-- Table structure for table `users`
--



--
-- Dumping data for table `users`
--



--
-- Indexes for dumped tables
--

--
-- Indexes for table `notes`
--


--
-- Indexes for table `users`
--
-- ALTER TABLE `users`
--   ADD PRIMARY KEY (`user_id`),
--   ADD UNIQUE KEY `user_email` (`user_email`),
--   ADD UNIQUE KEY `reset_token_hash` (`reset_token_hash`),
--   ADD UNIQUE KEY `reset_token_expires_at` (`reset_token_expires_at`),
--   ADD UNIQUE KEY `username` (`username`),
--   ADD UNIQUE KEY `unique_username` (`username`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notes`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
