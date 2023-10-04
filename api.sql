-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 04, 2023 at 06:33 PM
-- Server version: 8.0.34-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `api`
--

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE `group` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  `added_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`id`, `name`, `created_at`, `updated_at`, `added_by`, `updated_by`, `status`) VALUES
(2, 'Batch2023', '2023-10-01', '0000-00-00', 11, 0, '1'),
(3, 'Test Group', '2023-10-02', '0000-00-00', 13, 0, '1'),
(5, 'Test Group2', '2023-10-03', '0000-00-00', 13, 0, '1'),
(7, 'Test Group235', '2023-10-03', '0000-00-00', 13, 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `id` int NOT NULL,
  `group_id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  `added_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`id`, `group_id`, `user_id`, `created_at`, `updated_at`, `added_by`, `updated_by`, `status`) VALUES
(1, 2, 11, '2023-10-02', '0000-00-00', 11, 0, '1'),
(2, 3, 11, '2023-10-04', '0000-00-00', 13, 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `group_message`
--

CREATE TABLE `group_message` (
  `id` int NOT NULL,
  `group_id` int NOT NULL,
  `user_id` int NOT NULL,
  `mesage` varchar(255) NOT NULL,
  `likes_count` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `added_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `status` enum('1','0') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `group_message`
--

INSERT INTO `group_message` (`id`, `group_id`, `user_id`, `mesage`, `likes_count`, `created_at`, `updated_at`, `added_by`, `updated_by`, `status`) VALUES
(1, 2, 11, 'Hello', 2, '2023-10-02 14:01:24', '0000-00-00 00:00:00', 11, 0, '1'),
(2, 2, 15, 'Hello, this is a test message.', 0, '2023-10-04 17:19:06', '0000-00-00 00:00:00', 13, 0, '1'),
(3, 2, 15, 'Hello, this is a test message.', 0, '2023-10-04 17:19:37', '0000-00-00 00:00:00', 13, 0, '1'),
(4, 2, 15, 'Hello, this is a test message.', 0, '2023-10-04 17:21:19', '0000-00-00 00:00:00', 13, 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `group_message_likes`
--

CREATE TABLE `group_message_likes` (
  `id` int NOT NULL,
  `group_message_id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  `added_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `group_message_likes`
--

INSERT INTO `group_message_likes` (`id`, `group_message_id`, `user_id`, `created_at`, `updated_at`, `added_by`, `updated_by`, `status`) VALUES
(1, 1, 11, '2023-10-04', '0000-00-00', 13, 0, '1'),
(2, 1, 15, '2023-10-04', '0000-00-00', 13, 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `dob` date DEFAULT NULL,
  `phone_number` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '',
  `avatar` varchar(255) DEFAULT 'default.jpg',
  `token` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_admin` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `is_confirmed` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `is_deleted` tinyint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `dob`, `phone_number`, `password`, `avatar`, `token`, `created_at`, `updated_at`, `is_admin`, `is_confirmed`, `is_deleted`) VALUES
(1, 'admin', 'admin@gmail.com', '1996-05-31', '1111122222', '$2y$10$NIafTkIOSHf3NxaOECoY/eQCTf64UcTJv3nzrlg./3cfY.aCzKSsm', 'default.jpg', '', '2023-10-01 13:22:38', '2023-10-01 13:22:38', 1, 1, 0),
(11, 'lokesh', 'lokesh@gmail.com', '1978-10-20', '1234567891', '$2y$10$F9GsOKQjxB3K/4FFNWPI2.SWdJR1JilF3wnPOpEELAFNJtTcoi/N2', 'default.jpg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiIxMSIsInVzZXJuYW1lIjoibG9rZXNoIiwiaXNfYWRtaW4iOiIwIiw', '2023-10-01 21:05:39', '2023-10-01 21:11:44', 0, 0, 0),
(12, 'Aditya12345', 'aditya21345@gmail.com', '1989-10-25', '1234567880', '$2y$10$6jY7F6Ehsp2MdCcVPn4Nl.4lRz1g3jVWhVXfzvqiey28.tZuFW6nK', 'default.jpg', '', '2023-10-02 14:44:42', NULL, 1, 1, 0),
(13, 'Pavan', 'Pavan@gmail.com', '1989-07-23', '1234567890', '$2y$10$iPquUSVRVfa8t9kq6Qex5u7ljkiMRiWeA/0Vogj/dzfhibTWNk.82', 'default.jpg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiIxMyIsInVzZXJuYW1lIjoiUGF2YW4iLCJpc19hZG1pbiI6IjAiLCJ', '2023-10-02 15:13:12', NULL, 0, 1, 0),
(14, 'Test', 'Test@gmail.com', '1989-07-23', '1234566891', '$2y$10$Kr6eOrn0T0nrg7HrOnc.aOdkcZlOdSlkATqBKicZ4zCOwnugtBchO', 'default.jpg', '', '2023-10-02 15:14:36', NULL, 0, 1, 0),
(15, 'Test', 'Test1@gmail.com', '1989-07-23', '1234666891', '$2y$10$Rj87tuREqIfX6J3CSG4V0.GCrgw0h.S2rFWLhSCAbJNbkKu8C4WYu', 'default.jpg', '', '2023-10-02 15:15:31', NULL, 0, 1, 0),
(16, 'Test12', 'Test12@gmail.com', '1989-07-23', '1233666891', '$2y$10$ZuqoeYAJJnucc3lUrGestuEJr1RVhqpOH5uOnB6OPLquANaIlo.E6', 'default.jpg', '', '2023-10-02 15:25:22', NULL, 0, 1, 0),
(17, 'updateduser', 'updateduser@gmail.com', '1990-02-02', '1233166891', '$2y$10$.904w32vFFIxL4BDM.HczuFCkuRaiEsD0UfGO71tYAXOcsWWtzRyK', 'default.jpg', '', '2023-10-02 17:20:25', '2023-10-03 22:03:44', 0, 1, 0),
(18, 'Examination', 'Exam@gmail.com', '1989-10-25', '1234517880', '$2y$10$IR4/PghqoFAeYRAaAJipf.jqOVJ1uCS25RZeBvKYupWvAJeeUq1sG', 'default.jpg', '', '2023-10-03 21:54:00', NULL, 1, 1, 0),
(19, 'testuser', 'testuser@example.com', '1990-01-01', '5678901234', '$2y$10$vti9BoBRwN1ufr8bI7I6Xeb8B4wkWUG34Y6iZGiMJS1ZZc2FTl1oe', 'default.jpg', '', '2023-10-03 21:57:41', NULL, 0, 1, 0),
(20, 'testuser1', 'testuser1@example.com', '1990-01-01', '5678101234', '$2y$10$IbBAM6gsQ7DR.pFpIimQ9utOTgYk.gDl/sagXTLaUJjX04DEWI6zW', 'default.jpg', '', '2023-10-03 21:59:54', NULL, 0, 1, 0),
(21, 'testuser21', 'testuser21@example.com', '1990-01-01', '5678111234', '$2y$10$Sm9fB.kBB8Un8xi0pOVNXOTVAk1zwoo8gKxLbI4WW97Ndfr89ZC0a', 'default.jpg', '', '2023-10-03 22:00:39', NULL, 0, 1, 0),
(22, 'testuser212', 'testuser212@example.com', '1990-01-01', '0678111234', '$2y$10$fATCSwybGdmauz8q7c5rtOF07PNUBjD4pBH.82T3co.jbQISXfPoS', 'default.jpg', '', '2023-10-03 22:01:38', NULL, 0, 1, 0),
(23, 'testuser4212', 'testuser4212@example.com', '1990-01-01', '0618111234', '$2y$10$7nQN9S8zJAWf2b7phbp/rO9LEkeNNsizewCPGC0/6poewY7yGSwR.', 'default.jpg', '', '2023-10-03 22:03:02', NULL, 0, 1, 0),
(24, 'Examination', 'Exam1@gmail.com', '1989-10-25', '1230517880', '$2y$10$7xOzc9DJ.9l2Odikpz.ECeazAVyp3M6BvZ1QfqfmB3f5zV8NJcNEm', 'default.jpg', '', '2023-10-04 18:29:39', NULL, 1, 1, 0),
(25, 'Test12', 'Test1234@gmail.com', '1989-07-23', '1233160891', '$2y$10$UX8Vpg9RPixIJfr6XCICaeNrE3JG19AVjCFIpU.Pfqbp39x69Kr6i', 'default.jpg', '', '2023-10-04 18:31:18', NULL, 0, 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_message`
--
ALTER TABLE `group_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_message_likes`
--
ALTER TABLE `group_message_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `group`
--
ALTER TABLE `group`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `group_message`
--
ALTER TABLE `group_message`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `group_message_likes`
--
ALTER TABLE `group_message_likes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
