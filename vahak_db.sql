-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 19, 2026 at 09:51 PM
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
-- Database: `vahak_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `transporter_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `truck_id` int(11) DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  `destination` varchar(100) DEFAULT NULL,
  `goods_type` varchar(100) DEFAULT NULL,
  `weight_kg` int(11) DEFAULT NULL,
  `number_of_trucks` int(11) DEFAULT 1,
  `shipment_status` enum('pending','accepted','active','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `estimated_cost` decimal(10,2) DEFAULT NULL,
  `final_cost` decimal(10,2) DEFAULT NULL,
  `delivery_otp` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`id`, `customer_id`, `transporter_id`, `driver_id`, `truck_id`, `source`, `destination`, `goods_type`, `weight_kg`, `number_of_trucks`, `shipment_status`, `created_at`, `estimated_cost`, `final_cost`, `delivery_otp`) VALUES
(1, 3, NULL, NULL, 3, 'jamshedpur ', 'ranchi', 'Furniture', 4000, 2, 'pending', '2026-05-19 05:10:13', 2000.00, NULL, NULL),
(2, 3, NULL, NULL, 3, 'jamshedpur ', 'ranchi', 'Furniture', 3500, 2, 'pending', '2026-05-19 05:17:17', 2000.00, NULL, NULL),
(3, 3, NULL, NULL, 3, 'jsr', 'ranchi', 'Furniture', 3500, 2, 'pending', '2026-05-19 05:22:51', 2000.00, NULL, NULL),
(4, 3, NULL, NULL, 3, 'jamshedpur ', 'ranchi', 'Furniture', 3500, 2, 'pending', '2026-05-19 05:32:37', 4000.00, NULL, NULL),
(5, 3, NULL, 2, 3, 'jamshedpur ', 'ranchi', 'Furniture', 3500, 2, 'completed', '2026-05-19 05:39:08', 4000.00, NULL, '2388'),
(6, 3, 4, NULL, 2, 'jsr', 'dubai', 'Furniture', 3000, 2, 'accepted', '2026-05-19 05:41:58', 2400.00, NULL, NULL),
(7, 3, NULL, 2, 3, 'jamshedpur ', 'ranchi', 'Furniture', 2000, 1, 'active', '2026-05-19 06:29:10', 2000.00, NULL, NULL),
(8, 3, NULL, 2, 1, 'Ranchi', 'Delhi', 'Machines', 1500, 2, 'completed', '2026-05-19 17:59:20', 1600.00, NULL, '2218'),
(9, 5, NULL, 8, 4, 'Ranchi', 'mumbai', 'electronics', 7600, 1, 'completed', '2026-05-19 19:39:43', 5000.00, NULL, '6180');

-- --------------------------------------------------------

--
-- Table structure for table `trucks`
--

CREATE TABLE `trucks` (
  `id` int(11) NOT NULL,
  `truck_name` varchar(100) NOT NULL,
  `capacity_kg` int(11) NOT NULL,
  `truck_type` varchar(100) DEFAULT NULL,
  `base_price` decimal(10,2) DEFAULT NULL,
  `price_per_km` decimal(10,2) DEFAULT NULL,
  `truck_image` varchar(255) DEFAULT NULL,
  `availability_status` enum('available','busy') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trucks`
--

INSERT INTO `trucks` (`id`, `truck_name`, `capacity_kg`, `truck_type`, `base_price`, `price_per_km`, `truck_image`, `availability_status`, `created_at`) VALUES
(1, 'Tata Ace', 750, 'Mini Truck', 800.00, 18.00, 'tata_ace.jpg', 'available', '2026-05-18 15:05:13'),
(2, 'Tata Intra', 1500, 'Pickup Truck', 1200.00, 22.00, 'tata_intra.jpg', 'available', '2026-05-18 15:05:13'),
(3, 'Tata 407', 2500, 'Light Truck', 2000.00, 30.00, 'tata_407.jpg', 'available', '2026-05-18 15:05:13'),
(4, 'Tata 1613', 10000, 'Heavy Truck', 5000.00, 55.00, 'tata_1613.jpg', 'available', '2026-05-18 15:05:13'),
(5, 'Tata Prima', 40000, 'Trailer Truck', 12000.00, 90.00, 'tata_prima.jpg', 'available', '2026-05-18 15:05:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` enum('admin','customer','driver','transporter') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `password`, `phone`, `role`, `created_at`, `email`) VALUES
(1, 'Admin User', 'admin1', '1234', '9999999999', 'admin', '2026-05-18 06:59:31', ''),
(2, 'Rohit Driver', 'driver1', '1234', '8888888888', 'driver', '2026-05-18 06:59:31', ''),
(3, 'Aman Customer', 'customer1', '1234', '7777777777', 'customer', '2026-05-18 06:59:31', ''),
(4, 'Sharma Transport', 'transporter1', '1234', '6666666666', 'transporter', '2026-05-18 06:59:31', ''),
(5, 'Test Customer', '', '$2y$10$gKWNkZtoCecmCsIxOietxeGNXutWS8imlLdunAb.Ec3eZgIqNMNOu', NULL, 'customer', '2026-05-19 19:25:29', 'testcustomer@gmail.com'),
(7, 'Test Transporter', NULL, '$2y$10$O/2Aup731aG3psnst9ugNuju2NE3AaPHassp2LuU7GLqp7Me11Bxa', NULL, 'transporter', '2026-05-19 19:34:21', 'testtransporter@gmail.com'),
(8, 'Test Driver', NULL, '$2y$10$M/0NBTiH/Yue/wzwdGeWDO.E.K80HyWhzPRuHNHQHZMx/Jd3H7Szu', NULL, 'driver', '2026-05-19 19:37:15', 'testdriver@gmail.com'),
(9, 'Test Admin', NULL, '$2y$10$hY29ClHXq2THO1wmmJmjK.3EHdZRM/OwKfB.iYZjpxfg2EzJ5Efze', NULL, 'admin', '2026-05-19 19:37:59', 'testadmin@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trucks`
--
ALTER TABLE `trucks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `trucks`
--
ALTER TABLE `trucks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
