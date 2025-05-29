-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: May 29, 2025 at 10:30 PM
-- Server version: 8.0.42
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `post_offices`
--

CREATE TABLE `post_offices` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `post_offices`
--

INSERT INTO `post_offices` (`id`, `name`, `address`, `city`, `postal_code`) VALUES
(1, 'Branch 12', 'Peremohy St, 12, Zhytomyr, Zhytomyrs\'ka oblast, 10001', 'Zhytomyr', '2000'),
(2, 'Branch 1', 'Troianivska St, 10, Zhytomyr, Zhytomyrs\'ka oblast, 10001', 'Zhytomyr', '10001'),
(3, 'Branch 3', 'Kyivska St, Zhytomyr, Zhytomyrs\'ka oblast, Ukraine, 10000', 'Kiev', '10000');

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `receiver_id` int DEFAULT NULL,
  `receiver_name` varchar(255) DEFAULT NULL,
  `sender_name` varchar(255) NOT NULL,
  `address` text,
  `weight` float DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `send_office` int DEFAULT NULL,
  `receive_office` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`id`, `user_id`, `receiver_id`, `receiver_name`, `sender_name`, `address`, `weight`, `type`, `created_at`, `send_office`, `receive_office`) VALUES
(17, 9, 7, 'adadssad', '', 'asdadasd', 4, 'food', 1748524630, 1, 2),
(18, 7, 9, 'adadssad', '', 'asdadasd', 6, 'toys', 1748524640, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `status` varchar(50) DEFAULT 'open',
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `user_id`, `subject`, `message`, `response`, `status`, `created_at`) VALUES
(1, 7, 'Bug', 'there are bug', 'resolved', 'closed', '1748256832'),
(2, 7, 'another bug', 'It doesnt work again', '', 'open', '1748537822');

-- --------------------------------------------------------

--
-- Table structure for table `tracking_status`
--

CREATE TABLE `tracking_status` (
  `id` int NOT NULL,
  `shipment_id` int NOT NULL,
  `status` varchar(255) NOT NULL,
  `location` text NOT NULL,
  `send_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `arrive_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tracking_status`
--

INSERT INTO `tracking_status` (`id`, `shipment_id`, `status`, `location`, `send_at`, `arrive_at`) VALUES
(6, 17, 'sent', '', NULL, NULL),
(7, 18, 'sent', 'Zhytnii Rynok Square, Zhytomyr, Zhytomyrs\'ka oblast, Ukraine, 10000', '1748563200', '1748649600');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `password`, `phone_number`, `role`) VALUES
(4, 'admin', 'user', 'user1@gmail.com', '$2y$12$N/H0gZkuscfvdL.FNW4dS.8p5nEousIffwI7JCeRpsolqUicVquhS', '+380645974556', 'ROLE_ADMIN'),
(7, 'new', 'Lester', 'lester@example.com', '$2y$12$z1TMGo6mb7DsJWNqoW2.LuqwQtvnVet08IqXgfq.jP5seOmkrIOuO', '+380675125689', 'ROLE_USER'),
(8, 'Masde', 'asd', 'lasd@example.com', '$2y$12$tLTQytz8b1pkBFZaMlSeXOLIo8fgIaStGaEP.0nwLYi06o96hCM46', '+380678562318', 'ROLE_USER'),
(9, 'new', 'sdasdas', 'newUser@gmail.com', '$2y$12$56TvRRKsGhw5iV5coYwQBO8TIFepBSZ5DiXQxv2QcDUpT2z3LkSXC', '+380983393183', 'ROLE_USER');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `post_offices`
--
ALTER TABLE `post_offices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `shipments_ibfk_2` (`receiver_id`),
  ADD KEY `shipments_ibfk_3` (`send_office`),
  ADD KEY `shipments_ibfk_4` (`receive_office`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tracking_status`
--
ALTER TABLE `tracking_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_shipment` (`shipment_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_uniq` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `post_offices`
--
ALTER TABLE `post_offices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tracking_status`
--
ALTER TABLE `tracking_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipments_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `shipments_ibfk_3` FOREIGN KEY (`send_office`) REFERENCES `post_offices` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `shipments_ibfk_4` FOREIGN KEY (`receive_office`) REFERENCES `post_offices` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tracking_status`
--
ALTER TABLE `tracking_status`
  ADD CONSTRAINT `fk_shipment` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
