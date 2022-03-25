-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 25, 2022 at 06:53 PM
-- Server version: 8.0.28-0ubuntu0.20.04.3
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `org`
--

-- --------------------------------------------------------

--
-- Table structure for table `jhotjpn_billing`
--

CREATE TABLE `jhotjpn_billing` (
  `id` int NOT NULL,
  `client_id` int NOT NULL,
  `server_id` int NOT NULL,
  `amount` float NOT NULL,
  `bill_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `balance` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jotjpn_assigned_servers`
--

CREATE TABLE `jotjpn_assigned_servers` (
  `id` int NOT NULL,
  `client_id` int NOT NULL,
  `server_id` int NOT NULL,
  `server_name` varchar(256) NOT NULL,
  `server_identifier` varchar(256) NOT NULL,
  `server_ip` varchar(256) DEFAULT NULL,
  `price_per_month` varchar(256) NOT NULL,
  `price_per_hour` varchar(256) NOT NULL,
  `vcpu` int NOT NULL,
  `ram_gb` int NOT NULL,
  `space_gb` int NOT NULL,
  `data_transfer_tb` int NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `billed` float NOT NULL,
  `assigned_server_status` enum('Active','Inactive','Processing') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jotjpn_backup`
--

CREATE TABLE `jotjpn_backup` (
  `id` int NOT NULL,
  `unique_id` int NOT NULL,
  `name` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `server_id` int NOT NULL,
  `date` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jotjpn_clients`
--

CREATE TABLE `jotjpn_clients` (
  `client_id` int NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `full_name` varchar(256) NOT NULL,
  `contact_number` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `address` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `city` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `state` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `country` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `pin_code` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `company_name` varchar(256) DEFAULT NULL,
  `tax_number` varchar(256) DEFAULT NULL,
  `account_balance` float NOT NULL,
  `account_status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email_verification` enum('Pending','Verified') NOT NULL,
  `verification_code` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jotjpn_clients`
--

INSERT INTO `jotjpn_clients` (`client_id`, `email`, `password`, `full_name`, `contact_number`, `address`, `city`, `state`, `country`, `pin_code`, `company_name`, `tax_number`, `account_balance`, `account_status`, `created_datetime`, `email_verification`, `verification_code`) VALUES
(1, 'vivek@example.com', 'MTIzNDU2', 'Vivek Thakur', '1234569870', NULL, NULL, NULL, NULL, '147001', NULL, NULL, 100, 'Active', '2021-12-31 06:33:42', 'Verified', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jotjpn_message`
--

CREATE TABLE `jotjpn_message` (
  `id` int NOT NULL,
  `ticket_id` int NOT NULL,
  `msg_by` int NOT NULL,
  `msg` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `msg_time` int NOT NULL,
  `screenshot` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `msg_read` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jotjpn_region`
--

CREATE TABLE `jotjpn_region` (
  `id` int NOT NULL,
  `region_name` varchar(256) NOT NULL,
  `region_code` varchar(100) DEFAULT NULL,
  `region_status` enum('Active','Inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jotjpn_region`
--

INSERT INTO `jotjpn_region` (`id`, `region_name`, `region_code`, `region_status`) VALUES
(2, 'Bangalore', 'blr1', 'Active'),
(3, 'New York', 'nyc1', 'Active'),
(8, 'Toronto', 'tor1', 'Active'),
(10, 'UK', 'lon1', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `jotjpn_servers`
--

CREATE TABLE `jotjpn_servers` (
  `id` int NOT NULL,
  `server_type_id` int NOT NULL,
  `server_name` varchar(256) NOT NULL,
  `price_per_month` float NOT NULL,
  `price_per_hour` varchar(256) NOT NULL,
  `vcpu` int NOT NULL,
  `ram_gb` int NOT NULL,
  `space_gb` int NOT NULL,
  `data_transfer_tb` int NOT NULL,
  `other_info` varchar(1000) NOT NULL,
  `server_status` enum('Active','Inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jotjpn_servers`
--

INSERT INTO `jotjpn_servers` (`id`, `server_type_id`, `server_name`, `price_per_month`, `price_per_hour`, `vcpu`, `ram_gb`, `space_gb`, `data_transfer_tb`, `other_info`, `server_status`) VALUES
(4, 1, 'Standard', 24.99, '0.034', 2, 4, 80, 4, 'Within 6hr support ticket response', 'Active'),
(6, 1, 'Production', 59.99, '0.083', 4, 8, 160, 5, 'Instant support ticket response', 'Active'),
(10, 1, 'Basic', 12.99, '0.018', 1, 2, 25, 1, 'Within 12hr support ticket response', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `jotjpn_server_type`
--

CREATE TABLE `jotjpn_server_type` (
  `server_type_id` int NOT NULL,
  `type_name` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jotjpn_server_type`
--

INSERT INTO `jotjpn_server_type` (`server_type_id`, `type_name`, `description`) VALUES
(1, 'Virtual Machine', 'Virtual machines with dedicated resources of CPU, RAM, Space & Network');

-- --------------------------------------------------------

--
-- Table structure for table `jotjpn_settings`
--

CREATE TABLE `jotjpn_settings` (
  `id` int NOT NULL,
  `digitalocean_api_key` varchar(256) NOT NULL,
  `paypal_receiver_id` varchar(256) NOT NULL,
  `razorpay_id` varchar(256) NOT NULL,
  `razorpay_key` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jotjpn_settings`
--

INSERT INTO `jotjpn_settings` (`id`, `digitalocean_api_key`, `paypal_receiver_id`, `razorpay_id`, `razorpay_key`) VALUES
(1, '', '', '11111', '11111');

-- --------------------------------------------------------

--
-- Table structure for table `jotjpn_ssh_key`
--

CREATE TABLE `jotjpn_ssh_key` (
  `id` int NOT NULL,
  `client_id` int NOT NULL,
  `ssh_key_id` varchar(256) NOT NULL,
  `fingerprint` varchar(1000) NOT NULL,
  `public_key` varchar(1000) NOT NULL,
  `ssh_key_name` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jotjpn_tickets`
--

CREATE TABLE `jotjpn_tickets` (
  `id` int NOT NULL,
  `client_id` int NOT NULL,
  `resource_type` varchar(100) NOT NULL DEFAULT 'Server',
  `resource_id` int NOT NULL,
  `title` varchar(1000) NOT NULL,
  `ticket_time` int NOT NULL,
  `ticket_status` enum('Open','Closed') DEFAULT 'Open',
  `last_msg` int NOT NULL,
  `no_msg` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jotjpn_transaction`
--

CREATE TABLE `jotjpn_transaction` (
  `id` int NOT NULL,
  `client_id` int NOT NULL,
  `amount` float NOT NULL,
  `t_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `t_id` varchar(256) NOT NULL,
  `t_mode` varchar(256) DEFAULT NULL,
  `t_status` enum('Success','Failed','Pending') NOT NULL,
  `remark` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jotjpn_user`
--

CREATE TABLE `jotjpn_user` (
  `id` int NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jotjpn_user`
--

INSERT INTO `jotjpn_user` (`id`, `username`, `password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jhotjpn_billing`
--
ALTER TABLE `jhotjpn_billing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `jotjpn_assigned_servers`
--
ALTER TABLE `jotjpn_assigned_servers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `server_id` (`server_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `jotjpn_backup`
--
ALTER TABLE `jotjpn_backup`
  ADD PRIMARY KEY (`id`),
  ADD KEY `server_id` (`server_id`),
  ADD KEY `unique_id` (`unique_id`);

--
-- Indexes for table `jotjpn_clients`
--
ALTER TABLE `jotjpn_clients`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `jotjpn_message`
--
ALTER TABLE `jotjpn_message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `msg_by` (`msg_by`);

--
-- Indexes for table `jotjpn_region`
--
ALTER TABLE `jotjpn_region`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `region_name` (`region_name`);

--
-- Indexes for table `jotjpn_servers`
--
ALTER TABLE `jotjpn_servers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `server_name` (`server_name`),
  ADD KEY `server_type_id` (`server_type_id`);

--
-- Indexes for table `jotjpn_server_type`
--
ALTER TABLE `jotjpn_server_type`
  ADD PRIMARY KEY (`server_type_id`),
  ADD UNIQUE KEY `type_name` (`type_name`),
  ADD KEY `id` (`server_type_id`);

--
-- Indexes for table `jotjpn_settings`
--
ALTER TABLE `jotjpn_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `jotjpn_ssh_key`
--
ALTER TABLE `jotjpn_ssh_key`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `jotjpn_tickets`
--
ALTER TABLE `jotjpn_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `resource_id` (`resource_id`);

--
-- Indexes for table `jotjpn_transaction`
--
ALTER TABLE `jotjpn_transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `jotjpn_user`
--
ALTER TABLE `jotjpn_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jhotjpn_billing`
--
ALTER TABLE `jhotjpn_billing`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jotjpn_assigned_servers`
--
ALTER TABLE `jotjpn_assigned_servers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jotjpn_backup`
--
ALTER TABLE `jotjpn_backup`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jotjpn_clients`
--
ALTER TABLE `jotjpn_clients`
  MODIFY `client_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `jotjpn_message`
--
ALTER TABLE `jotjpn_message`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jotjpn_region`
--
ALTER TABLE `jotjpn_region`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `jotjpn_servers`
--
ALTER TABLE `jotjpn_servers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `jotjpn_server_type`
--
ALTER TABLE `jotjpn_server_type`
  MODIFY `server_type_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jotjpn_settings`
--
ALTER TABLE `jotjpn_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jotjpn_ssh_key`
--
ALTER TABLE `jotjpn_ssh_key`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jotjpn_tickets`
--
ALTER TABLE `jotjpn_tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jotjpn_transaction`
--
ALTER TABLE `jotjpn_transaction`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jotjpn_user`
--
ALTER TABLE `jotjpn_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jotjpn_servers`
--
ALTER TABLE `jotjpn_servers`
  ADD CONSTRAINT `jotjpn_servers_ibfk_1` FOREIGN KEY (`server_type_id`) REFERENCES `jotjpn_server_type` (`server_type_id`);

--
-- Constraints for table `jotjpn_transaction`
--
ALTER TABLE `jotjpn_transaction`
  ADD CONSTRAINT `jotjpn_transaction_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `jotjpn_clients` (`client_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
