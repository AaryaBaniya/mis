-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2025 at 06:51 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brasshub`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(4, 'Antiques'),
(2, 'Decor'),
(1, 'New Arrivals'),
(3, 'Statue');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES
(1, 'Door Knob', 'A sturdy brass door knob gently grasped by a hand, showcasing the smooth, rounded surface and classic polished finish a timeless touch of elegance and function.', 5500.00, 'img_686e364f61015.jpg', 2),
(3, 'Krishna statue', 'Beautifully handcrafted brass Krishna statue featuring Lord Krishna in a graceful flute-playing pose. Symbolizing love, peace, and divinity, this piece is perfect for home decor, pooja rooms or as a spiritual gift.', 32000.00, 'img_686e927f86aea.jpg', 3),
(4, 'Nut cracker', 'Durable and easy-to-use brass nut cracker designed for cracking hard shells like walnuts and betel nuts. Its strong grip and traditional design make it a reliable tool for every kitchen.', 4500.00, 'img_686f4e65d9b89.jpg', 1),
(5, 'Lord Ganesh', 'Intricately crafted brass Lord Ganesh statue, symbolizing wisdom, prosperity, and new beginnings. Perfect for home temples, office spaces, or as a thoughtful gift for blessings and positivity.', 20000.00, 'img_686f4ea8d0581.jpg', 3),
(6, 'Lord Shiva', 'Antique-finished brass Shiva Lingam placed on an intricately designed, representing divine energy, creation, and spiritual strength. A timeless piece perfect for home temples, pooja rituals, or sacred decor.', 27000.00, 'img_686f4f37889b5.jpg', 4);

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `purchase_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `shipping_name` varchar(100) NOT NULL,
  `shipping_address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `cancelled_by` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `product_id`, `quantity`, `price`, `purchase_date`, `status`, `shipping_name`, `shipping_address`, `phone`, `payment_method`, `cancelled_by`) VALUES
(1, 2, 4, 1, 4500.00, '2025-07-10 11:50:17', 'Dispatched', 'Aarya Baniya', 'Indrachowk, Dallu, Makhan', '9828884062', 'COD', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'Aarya Baniya', 'aaryabaniya12@gmail.com', '$2y$10$4LekWkpK7rKjTw3vtndC5uvDmm6IxW.FFA8Fewn/NQ.6rnfAJfLIS', '2025-06-24 07:23:56', 'user'),
(2, 'Prasamsha parajuli', 'prasamshaparajuli12@gmail.com', '$2y$10$c9n4XDuAScxYgB2jxPIhx.w1V6uAgwDwEyfILy5YDJi3q0Ewixtre', '2025-06-25 05:22:28', 'user'),
(3, 'Test', 'text@example.com', '$2y$10$U1Hy5..qUaQZvwMG5wwHXejgs/C/Ss.kjfDkHbylsySCLqond4P0u', '2025-06-25 06:42:14', 'user'),
(7, 'Aarya Baniya', 'aaryabaniya1@gmail.com', '$2y$10$PZP4U0ZtoSGMDCcSRXECBuDDmgAXMfe/DcX2INe4b0Sba4KffhSu6', '2025-06-25 06:49:28', 'user'),
(8, 'Aarya Baniya', 'aaryabaniya2@gmail.com', '$2y$10$mZqYvoamooRoKMA8hdAV3ew2700Hafl.3TM6eQi9.1Tjtqa2hwJfC', '2025-06-25 06:50:18', 'user'),
(9, 'Admin', 'admin@example.com', '$2y$10$4hXwHhKFoz7/qEizNKecyePkacNjVEdQ9ceBM8/1DFnhXYMgoTSiu', '2025-07-01 13:33:54', 'admin'),
(10, 'Tenzin Palki', 'aaryabaniya11@gmail.com', '$2y$10$dRvAuljnTNRDUrydMlOT3uWriXulqMQX0ga3ufIV3x0tIkcKvdeu6', '2025-07-02 16:42:09', 'user'),
(11, 'Salina Lama', 'aaryabaniya22@gmail.com', '$2y$10$4ixDgzbLHfhlvbISdIPq9u/65FkCFwQ2yu8h3wZ/aAnx6/Arh.uRe', '2025-07-02 16:43:33', 'user'),
(12, 'test', 'test@gmail.com', '$2y$10$IwMQV4rMF4u5YvzHsyxk1eIZFF2SeJKTmdOVZ9VDqrpmXe5LMFLFS', '2025-07-04 06:03:27', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`),
  ADD KEY `fk_product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
