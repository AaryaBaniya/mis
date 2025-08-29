-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2025 at 09:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `added_on`) VALUES
(32, 2, 12, 1, '2025-08-27 13:40:16'),
(66, 1, 9, 1, '2025-08-29 18:48:10');

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
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending',
  `payment_ref_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(6, 'Lord Shiva', 'Antique-finished brass Shiva Lingam placed on an intricately designed, representing divine energy, creation, and spiritual strength. A timeless piece perfect for home temples, pooja rituals, or sacred decor.', 27000.00, 'img_686f4f37889b5.jpg', 4),
(7, 'Classic Brass Wall Hook', 'Simple yet striking, this solid brass wall hook combines strength with sophistication. Perfect for coats, keys, or decor it brings golden warmth and order to your space.', 2800.00, 'img_68a0aeae7263e.jpg', 1),
(8, 'Lady Grace Brass Mirror', 'An exquisite brass mirror featuring a beautifully sculpted lady holding the frame.This piece symbolizes elegance and poise. A perfect blend of art and utility.', 8000.00, 'img_68a0af9255606.jpg', 2),
(9, 'Unity of Elephants Showpiece', 'Three elephants standing proudly atop each other—this brass showpiece symbolizes strength, wisdom, and unity. A meaningful décor piece that radiates positive energy and artistic brilliance.', 22000.00, 'img_68a0afc4b72ba.jpg', 2),
(10, 'Royal Camel Lock', 'This handcrafted brass lock features a camel design that brings a touch of Rajasthan\'s heritage into your hands. Both secure and decorative, it’s a fusion of tradition and utility.', 2500.00, 'img_68a0b050b4f8c.jpg', 4),
(11, 'Divine Durga Statue', 'Beautifully crafted in brass with an antique finish, this Durga statue embodies strength, protection, and motherly grace. Depicted with her powerful stance, it is a symbol of victory over negativity and a radiant piece of spiritual art for your home or altar.', 31000.00, 'img_68a0b092abe58.jpg', 3),
(12, 'Little Blessings Ganesh', 'Adorably handcrafted in brass, this little Ganesh radiates charm, positivity, and good fortune. Perfect as a desk companion, home decor, or a heartfelt gift, it carries blessings in the cutest form.', 26000.00, 'img_68a0b0e297ae6.jpg', 1),
(13, 'Antique  Kettle', 'Add a touch of timeless charm to your home with this exquisite antique brass kettle. Crafted with meticulous detail, its warm golden finish and vintage design evoke the elegance of a bygone era. Perfect as a decorative piece or for adding a rustic touch to your kitchen, this kettle is a blend of art and heritage, sure to catch every eye.', 19000.00, 'img_68a0b1595c159.jpg', 4),
(14, 'Shiv Linga', 'Embrace divinity with this finely crafted brass Shiv Linga. Its antique finish and serene design inspire peace and devotion, a sacred centerpiece for any altar or meditation space.', 12000.00, 'img_68a0b29e3ad91.jpg', 1);

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
  `khalti_idx` varchar(100) DEFAULT NULL,
  `order_reference_id` varchar(100) DEFAULT NULL,
  `cancelled_by` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `product_id`, `quantity`, `price`, `purchase_date`, `status`, `shipping_name`, `shipping_address`, `phone`, `payment_method`, `khalti_idx`, `order_reference_id`, `cancelled_by`) VALUES
(1, 2, 4, 1, 4500.00, '2025-07-10 11:50:17', 'Dispatched', 'Aarya Baniya', 'Indrachowk, Dallu, Makhan', '9828884062', 'COD', NULL, NULL, NULL),
(2, 1, 13, 1, 19000.00, '2025-08-16 22:13:56', 'Pending', 'Aarya Baniya', 'Indrachowk, Dallu, Makhan', '9828884062', 'COD', NULL, NULL, NULL),
(3, 1, 12, 1, 26000.00, '2025-08-16 22:13:56', 'Pending', 'Aarya Baniya', 'Indrachowk, Dallu, Makhan', '9828884062', 'COD', NULL, NULL, NULL),
(4, 1, 10, 3, 2500.00, '2025-08-16 22:13:56', 'Pending', 'Aarya Baniya', 'Indrachowk, Dallu, Makhan', '9828884062', 'COD', NULL, NULL, NULL),
(5, 1, 14, 1, 12000.00, '2025-08-26 15:58:59', 'Pending', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, NULL, NULL),
(6, 1, 12, 1, 26000.00, '2025-08-26 15:58:59', 'Pending', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, NULL, NULL),
(7, 1, 8, 1, 8000.00, '2025-08-26 15:58:59', 'Pending', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, NULL, NULL),
(8, 2, 13, 1, 19000.00, '2025-08-27 15:26:56', 'Dispatched', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, NULL, NULL),
(9, 2, 12, 2, 26000.00, '2025-08-27 15:26:56', 'Cancelled', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, NULL, 'admin'),
(10, 2, 11, 1, 31000.00, '2025-08-27 15:26:56', 'Pending', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, NULL, NULL),
(11, 1, 13, 2, 19000.00, '2025-08-28 09:35:26', 'Pending', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, NULL, NULL),
(12, 1, 12, 1, 26000.00, '2025-08-28 09:35:26', 'Pending', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, NULL, NULL),
(13, 1, 1, 1, 5500.00, '2025-08-28 09:35:26', 'Pending', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, NULL, NULL),
(14, 13, 13, 1, 19000.00, '2025-08-28 11:34:11', 'Pending', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, '1756373651-13', NULL),
(15, 13, 7, 1, 2800.00, '2025-08-28 11:51:27', 'Cancelled', 'Arshi baniya', 'Indrachowk', '9813412613', 'COD', NULL, '1756374687-13', 'admin'),
(16, 1, 13, 2, 19000.00, '2025-08-28 12:39:50', 'Pending', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, '1756377590-1', NULL),
(17, 13, 4, 1, 4500.00, '2025-08-28 16:23:33', 'Dispatched', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, '1756391013-13', NULL),
(18, 13, 14, 3, 12000.00, '2025-08-28 16:23:33', 'Cancelled', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, '1756391013-13', 'user'),
(19, 13, 4, 1, 4500.00, '2025-08-28 17:06:33', 'Pending', 'Pradeep Baniya', 'Ktm', '9841326592', 'COD', NULL, '1756393593-13', NULL),
(20, 1, 12, 1, 26000.00, '2025-08-29 06:48:18', 'Pending', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, '1756442898-1', NULL),
(21, 1, 13, 1, 19000.00, '2025-08-29 06:48:18', 'Pending', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, '1756442898-1', NULL),
(22, 1, 6, 1, 27000.00, '2025-08-29 11:28:39', 'Pending', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, '1756459719-1', NULL),
(23, 1, 13, 1, 19000.00, '2025-08-29 11:28:39', 'Cancelled', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, '1756459719-1', 'user'),
(24, 1, 13, 1, 19000.00, '2025-08-29 15:04:59', 'Pending', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, '1756472699-1', NULL),
(25, 1, 7, 1, 2800.00, '2025-08-29 16:51:44', 'Pending', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, '1756479104-1', NULL),
(26, 1, 13, 1, 19000.00, '2025-08-29 16:51:44', 'Pending', 'Aarya Baniya', 'Indrachowk', '9828884062', 'COD', NULL, '1756479104-1', NULL),
(27, 1, 3, 1, 32000.00, '2025-08-29 18:29:17', 'Success', 'Aarya Baniya', 'Indrachowk', '982345676', 'Khalti', 'czBmot7xrarn7o7CzrmrgR', 'ORD_68b1d55dbcf92', NULL),
(28, 13, 10, 1, 2500.00, '2025-08-29 18:50:37', 'Success', 'Aarya Baniya', 'Indrachowk', '982345676', 'Khalti', 'VEtqZktRdhjNjprCDGcbNd', 'ORD_68b1da5db320f', NULL),
(29, 13, 12, 1, 26000.00, '2025-08-29 18:50:37', 'Success', 'Aarya Baniya', 'Indrachowk', '982345676', 'Khalti', 'VEtqZktRdhjNjprCDGcbNd', 'ORD_68b1da5db320f', NULL),
(30, 13, 13, 1, 19000.00, '2025-08-29 18:50:37', 'Success', 'Aarya Baniya', 'Indrachowk', '982345676', 'Khalti', 'VEtqZktRdhjNjprCDGcbNd', 'ORD_68b1da5db320f', NULL),
(31, 13, 7, 1, 2800.00, '2025-08-29 18:57:41', 'Pending', 'Aarya Baniya', 'Indrachowk', '982345676', 'COD', NULL, 'ORD_68b1dc058fa7d', NULL),
(32, 13, 8, 1, 8000.00, '2025-08-29 18:58:33', 'Success', 'Aarya Baniya', 'Indrachowk', '982345676', 'Khalti', 'uN8Vn8wURTBS9xjyh27kbd', 'ORD_68b1dc39aa55e', NULL),
(33, 13, 3, 1, 32000.00, '2025-08-29 19:09:14', 'Pending', 'Aarya Baniya', 'Indrachowk', '982345676', 'COD', NULL, 'ORD_68b1deba0b6c0', NULL),
(34, 1, 12, 1, 26000.00, '2025-08-29 19:10:29', 'Pending', 'Aarya Baniya', 'Indrachowk', '982345676', 'COD', NULL, 'ORD_68b1df0514574', NULL),
(35, 1, 11, 1, 31000.00, '2025-08-29 19:45:04', 'Pending', 'Aarya Baniya', 'Indrachowk', '982345676', 'COD', NULL, 'ORD_68b1e720280fb', NULL),
(36, 1, 10, 1, 2500.00, '2025-08-29 19:45:51', 'Dispatched', 'Aarya Baniya', 'Indrachowk', '982345676', 'Khalti', NULL, 'ORD_68b1e74faa5bb', NULL),
(37, 13, 14, 1, 12000.00, '2025-08-29 19:55:16', 'Pending', 'Aarya Baniya', 'Indrachowk', '982345676', 'Khalti', 'w9BxAXvKGDEtDfqwSB7sW4', 'ORD_68b1e9845c670', NULL),
(38, 13, 1, 1, 5500.00, '2025-08-29 20:01:37', 'Pending', 'Aarya Baniya', 'Indrachowk', '982345676', 'Khalti', '6d2zM6dDQcRSgET4tbxwpj', 'ORD_68b1eb01c7464', NULL),
(39, 13, 10, 1, 2500.00, '2025-08-29 20:09:10', 'Pending', 'Aarya Baniya', 'Indrachowk', '982345676', 'Khalti', 'eAtparD9dJvBSwU7NR2Zs6', 'ORD_68b1ecc6cb747', NULL),
(40, 13, 14, 1, 12000.00, '2025-08-29 20:22:49', 'Pending', 'Aarya Baniya', 'Indrachowk', '982345676', 'Khalti', 'GxSsyeMGNDGxjAepGJRcon', 'ORD_68b1eff963868', NULL),
(41, 13, 12, 1, 26000.00, '2025-08-29 20:30:34', 'Pending', 'Aarya Baniya', 'Indrachowk', '982345676', 'Khalti', 'yQfDE7tLwM6tveTq659Mh4', 'ORD_68b1f1ca49476', NULL),
(42, 13, 7, 1, 2800.00, '2025-08-29 20:37:52', 'Pending', 'Aarya Baniya', 'Indrachowk', '982345676', 'Khalti', 'gYhDQzJVfMKBvozNREEqPF', 'ORD_68b1f380cfacf', NULL),
(43, 13, 12, 1, 26000.00, '2025-08-29 20:37:52', 'Pending', 'Aarya Baniya', 'Indrachowk', '982345676', 'Khalti', 'gYhDQzJVfMKBvozNREEqPF', 'ORD_68b1f380cfacf', NULL),
(44, 13, 9, 1, 22000.00, '2025-08-29 20:49:53', 'Pending', 'Aarya Baniya', 'Indrachowk', '982345676', 'Khalti', 'eSQhnL9TEpfroGQgX8EhUn', 'ORD_68b1f65171a88', NULL),
(45, 13, 1, 1, 5500.00, '2025-08-29 21:00:20', 'Pending', 'aa', 'aa', '8765432123', 'COD', NULL, 'ORD_68b1f8c4bdaca', NULL),
(46, 13, 12, 1, 26000.00, '2025-08-29 21:02:57', 'Pending', 'aa', 'aaaaa', '9876543211', 'COD', NULL, 'ORD_68b1f96155f4b', NULL);

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
(12, 'test', 'test@gmail.com', '$2y$10$IwMQV4rMF4u5YvzHsyxk1eIZFF2SeJKTmdOVZ9VDqrpmXe5LMFLFS', '2025-07-04 06:03:27', 'user'),
(13, 'Arshi Baniya', 'arshibaniya12@gmail.com', '$2y$10$Yg4DSASSTSRBiQuWZrU03ewFSXpicCsgZNc98/36kfAlxnn7WFwrm', '2025-08-28 09:18:46', 'user'),
(14, 'aaaa', 'aa@aa', '$2y$10$tNQYFkvXKUI/dtVk4vrM6O935O8ykiPYIDtkQBIkZBW8sdXm5X3ia', '2025-08-29 19:04:01', 'user');

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
