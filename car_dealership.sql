-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2026 at 05:05 PM
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
-- Database: `car_dealership`
--
CREATE DATABASE IF NOT EXISTS `car_dealership` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `car_dealership`;

-- --------------------------------------------------------

--
-- Table structure for table `orderitem`
--

CREATE TABLE `orderitem` (
  `orderItemID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitem`
--

INSERT INTO `orderitem` (`orderItemID`, `orderID`, `productID`, `quantity`) VALUES
(8, 13, 1, 1),
(9, 13, 2, 1),
(10, 14, 5, 1),
(11, 15, 3, 1),
(12, 16, 2, 1),
(13, 16, 4, 1),
(14, 17, 2, 1),
(15, 17, 4, 1),
(16, 17, 3, 1),
(17, 18, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL,
  `customerID` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `order_status` varchar(50) DEFAULT 'Pending',
  `total` decimal(10,2) NOT NULL,
  `delivery` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postcode` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `customerID`, `order_date`, `order_status`, `total`, `delivery`, `address`, `city`, `postcode`) VALUES
(13, 8, '2026-04-30 14:19:38', 'Pending', 58000.00, 'Standard', 'UU ', 'derry', '1234'),
(14, 8, '2026-04-30 14:20:02', 'Pending', 42000.00, 'Standard', 'UU ', 'derry', '1234'),
(15, 8, '2026-04-30 14:20:10', 'Pending', 18000.00, 'Standard', 'UU ', 'derry', '1234'),
(16, 8, '2026-04-30 14:20:21', 'Pending', 62000.00, 'Standard', 'UU ', 'derry', '1234'),
(17, 9, '2026-04-30 15:44:30', 'Pending', 80000.00, 'Standard', 'uu', 'Derry', '1234'),
(18, 9, '2026-04-30 15:45:14', 'Pending', 23000.00, 'Standard', 'uu', 'Derry', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productID` int(11) NOT NULL,
  `model_name` varchar(150) NOT NULL,
  `model_year` int(11) NOT NULL,
  `fuel_type` varchar(50) NOT NULL,
  `engine_size` decimal(3,1) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productID`, `model_name`, `model_year`, `fuel_type`, `engine_size`, `colour`, `price`) VALUES
(1, 'Toyota Corolla', 2023, 'Petrol', 1.8, 'Black', 23000.00),
(2, 'Toyota RAV4', 2024, 'Hybrid', 2.5, 'White', 35000.00),
(3, 'Toyota Yaris', 2022, 'Petrol', 1.5, 'Blue', 18000.00),
(4, 'Toyota Prius', 2023, 'Hybrid', 1.8, 'Silver', 27000.00),
(5, 'Toyota Hilux', 2024, 'Diesel', 2.8, 'Grey', 42000.00);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `reviewID` int(11) NOT NULL,
  `customerID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `review_date` datetime DEFAULT current_timestamp(),
  `review` text NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`reviewID`, `customerID`, `productID`, `review_date`, `review`, `rating`) VALUES
(16, 8, 1, '2026-04-30 14:20:57', 'Great Car comfy and cheap\r\n', 4),
(17, 8, 5, '2026-04-30 14:21:11', 'Monster Truck', 5),
(18, 8, 1, '2026-04-30 14:21:28', 'diffcult clutch\r\n', 1),
(19, 8, 4, '2026-04-30 14:21:48', 'Relible easy to use webpage!\r\n', 4),
(20, 8, 4, '2026-04-30 14:22:03', 'amazing work ', 4);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `productID` int(11) NOT NULL,
  `stock_level` int(11) NOT NULL DEFAULT 0,
  `reorder` int(11) NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`productID`, `stock_level`, `reorder`) VALUES
(1, 3, 2),
(2, 2, 1),
(3, 5, 2),
(4, 7, 1),
(5, 5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postcode` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  `tele_no` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `first_name`, `last_name`, `address`, `city`, `postcode`, `email`, `tele_no`, `password`, `role`) VALUES
(6, 'Andrew', 'mullan', '8 Ervey Court', 'Londonderry', 'BT47 3WN', 'acmullan937@gmail.com', '07582223912', '$2y$10$y4kx4cHKwhLywR7wWkvXy.e6IGytIbD453YbocslmQGl9.Ix6w3gq', 'admin'),
(8, 'Group 1', 'g', 'UU ', 'derry', '1234', 'com336@gmail.com', NULL, '$2y$10$1WFJVqDrc0hOOL9IaXxWXOPb3kDjhb6QzmIWMto5cOJ3J9MBlJS4W', 'customer'),
(9, 'group1Admin', 'g', 'uu', 'Derry', '1234', 'com336admin@gmail.com', NULL, '$2y$10$52Iuh7XW/yDQN8EiTELTT.6pMDca5ajGfQBWnYz3M8fCEpcT3YwG.', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD PRIMARY KEY (`orderItemID`),
  ADD KEY `orderID` (`orderID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `customerID` (`customerID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productID`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`reviewID`),
  ADD KEY `customerID` (`customerID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`productID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orderitem`
--
ALTER TABLE `orderitem`
  MODIFY `orderItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `reviewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD CONSTRAINT `orderitem_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orderitem_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customerID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`customerID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
