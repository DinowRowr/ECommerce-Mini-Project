-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2023 at 05:17 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `registered`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `contact_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`contact_id`, `user_id`, `email`, `message`) VALUES
(14, 44, 'quirogadinothelo@gmail.com', 'bla bla bla');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `name`, `category`, `description`, `price`, `quantity`) VALUES
(1, 'beanie1', 'Accessories', 'Black beanie with merch name	', 300, 0),
(2, 'beanie2', 'Accessories', 'Black beanie with merch name', 300, 7),
(3, 'tshirt1', 'T-Shirt', 'White T-shirt with merch name', 750, 8),
(4, 'tshirt2', 'T-Shirt', 'White T-shirt with merch name', 750, 1),
(5, 'tshirt3', 'T-Shirt', 'Black T-shirt with merch name', 750, 9),
(6, 'hoodie1', 'Hoodies', 'Black hoodie with merch name', 900, 4),
(7, 'sweater1', 'Sweaters', 'White Sweater with Dog and Im Cool text', 900, 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `payment_method` enum('cod','ewallet','creditDebit','') DEFAULT NULL,
  `purchased_on` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total`, `address`, `payment_method`, `purchased_on`) VALUES
(78, 44, '2100.00', 'Trece Martires City, Cavite', 'creditDebit', '2023-06-24 22:40:57'),
(79, 46, '5550.00', 'Maragondon, Cavite', 'ewallet', '2023-06-24 23:48:11'),
(80, 44, '9000.00', 'Trece Martires City, Cavite', 'creditDebit', '2023-06-24 23:48:33'),
(81, 46, '5250.00', 'Maragondon, Cavite', 'cod', '2023-06-24 23:50:19'),
(82, 44, '2700.00', 'Trece Martires City, Cavite', 'cod', '2023-06-30 10:57:38');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `item_id`, `quantity`, `total`) VALUES
(30, 78, 5, 1, '750.00'),
(31, 78, 3, 1, '750.00'),
(32, 78, 1, 1, '300.00'),
(33, 78, 2, 1, '300.00'),
(34, 79, 2, 1, '300.00'),
(35, 79, 4, 1, '750.00'),
(36, 79, 6, 5, '4500.00'),
(37, 80, 7, 7, '6300.00'),
(38, 80, 1, 9, '2700.00'),
(39, 81, 4, 7, '5250.00'),
(40, 82, 6, 1, '900.00'),
(41, 82, 3, 1, '750.00'),
(42, 82, 2, 1, '300.00'),
(43, 82, 4, 1, '750.00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(128) NOT NULL,
  `phoneNum` bigint(15) NOT NULL,
  `address` varchar(100) NOT NULL,
  `bday` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `user_type` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `firstName`, `lastName`, `email`, `password`, `phoneNum`, `address`, `bday`, `gender`, `user_type`) VALUES
(1, 'admin', '', 'admin@gmail.com', '$2y$10$ZPGq3ImHsX2vghDEtraUHuo8zpdqnpWRkezgCRZlDZKi1FTk0s3im', 0, '0', '0000-00-00', '', 'admin'),
(44, 'Dinothelo', 'Quiroga', 'quirogadinothelo@gmail.com', '$2y$10$cmtmXROy7BavBWDPKTFhR.Ksvcx5a8BulqvMX80SOJ8lQv8fkvBB2', 9477881951, 'Trece Martires City, Cavite', '2003-03-13', 'male', 'user'),
(45, 'Charles Dave', 'Arevalo', 'charlesarevalo@gmail.com', '$2y$10$sKvLFJEze0deGcpyPHS1ve0S3cuF3UstytsPgBZVms7gGER.e4zcK', 9123456789, 'Tagaytay, Cavite', '2002-02-22', 'male', 'user'),
(46, 'Janlee', 'Loren', 'janleeloren@gmail.com', '$2y$10$l2Sjp8Jn3l0zC8GLGjoPQeQnwwK1SSSaGZjkS44suHUmK7JDZA53m', 9123456789, 'Maragondon, Cavite', '2003-05-15', 'female', 'user'),
(48, 'Archie', 'Patawe', 'archiepatawe@gmail.com', '$2y$10$OE7pgJCX61pFvqzpj.hz6OQa1.ucPjIw29RlTO96sKj5rPhalFqCS', 9123456789, 'Silang, Cavite', '2002-07-17', 'male', 'user'),
(55, 'John Herson', 'Radones', 'hersonradones@gmail.com', '$2y$10$1Ax5.c67zVov8pzdqEqMV.TsASCi5sa7FGL3NBADQ2B1wq9b1VyVa', 9123456789, 'Tanza, Cavite', '2002-11-11', 'other', 'user'),
(57, 'Jopay', 'Mojica', 'jopaymojica@gmail.com', '$2y$10$ixQ.fnO9mgDuEzzmo2g5MORLriM5B9V68ogDMhwf4CEzVNRNPhgq.', 9123456789, 'Pasay, Metro Manila', '2002-02-22', 'female', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
