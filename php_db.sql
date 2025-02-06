-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2025 at 10:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `php_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminusers`
--

CREATE TABLE `adminusers` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` int(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `temp_password` varchar(255) DEFAULT NULL,
  `temp_password_used` tinyint(1) NOT NULL DEFAULT 0,
  `failed_attempts` int(11) NOT NULL DEFAULT 0,
  `password_1` varchar(255) DEFAULT NULL,
  `password_2` varchar(255) DEFAULT NULL,
  `password_3` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminusers`
--

INSERT INTO `adminusers` (`id`, `username`, `firstname`, `lastname`, `email`, `phone`, `password`, `temp_password`, `temp_password_used`, `failed_attempts`, `password_1`, `password_2`, `password_3`, `dob`) VALUES
(1, 'admin', 'ad', 'min', 'aliasade215@gmail.com', 539513806, 'admin', NULL, 0, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_login_history`
--

CREATE TABLE `admin_login_history` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_login_history`
--

INSERT INTO `admin_login_history` (`id`, `username`, `email`, `date`, `success`) VALUES
(1, 'admin', 'aliasade215@gmail.com', '2025-02-01 16:53:36', 0),
(2, 'admin', 'aliasade215@gmail.com', '2025-02-01 16:53:46', 1),
(3, 'admin', 'aliasade215@gmail.com', '2025-02-01 18:23:35', 1),
(4, 'admin', 'aliasade215@gmail.com', '2025-02-01 18:24:56', 1),
(5, 'admin', 'aliasade215@gmail.com', '2025-02-01 20:33:24', 1),
(6, 'admin', 'aliasade215@gmail.com', '2025-02-02 15:18:21', 1),
(7, 'admin', 'aliasade215@gmail.com', '2025-02-02 15:57:02', 1),
(8, 'admin', 'aliasade215@gmail.com', '2025-02-02 16:25:13', 0),
(9, 'admin', 'aliasade215@gmail.com', '2025-02-02 16:25:17', 1),
(10, 'admin', 'aliasade215@gmail.com', '2025-02-02 16:30:07', 1),
(11, 'admin', 'aliasade215@gmail.com', '2025-02-03 15:36:56', 1),
(12, 'admin', 'aliasade215@gmail.com', '2025-02-03 15:43:17', 1),
(13, 'admin', 'aliasade215@gmail.com', '2025-02-03 19:34:41', 1),
(14, 'admin', 'aliasade215@gmail.com', '2025-02-03 19:35:34', 1),
(15, 'admin', 'aliasade215@gmail.com', '2025-02-03 20:32:30', 0),
(16, 'admin', 'aliasade215@gmail.com', '2025-02-03 20:34:23', 1),
(17, 'admin', 'aliasade215@gmail.com', '2025-02-06 18:13:16', 1),
(18, 'admin', 'aliasade215@gmail.com', '2025-02-06 23:00:43', 1),
(19, 'admin', 'aliasade215@gmail.com', '2025-02-06 23:18:26', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `added_date`) VALUES
(9, 25, 44, 1, '2025-01-27 13:47:15'),
(10, 25, 62, 1, '2025-01-27 13:47:16');

-- --------------------------------------------------------

--
-- Table structure for table `clientusers`
--

CREATE TABLE `clientusers` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `temp_password` varchar(255) DEFAULT NULL,
  `temp_password_used` tinyint(1) NOT NULL DEFAULT 0,
  `failed_attempts` int(11) NOT NULL DEFAULT 0,
  `password_1` varchar(255) DEFAULT NULL,
  `password_2` varchar(255) DEFAULT NULL,
  `password_3` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clientusers`
--

INSERT INTO `clientusers` (`id`, `username`, `firstname`, `lastname`, `email`, `phone`, `password`, `temp_password`, `temp_password_used`, `failed_attempts`, `password_1`, `password_2`, `password_3`, `dob`) VALUES
(20, 'test12', 'test', 'test', 'test@test.com', '500000000', 'test', NULL, 0, 0, NULL, NULL, NULL, NULL),
(25, 'test3', 'test', 'test', 'aliahwc10@gmail.com', '0501112222', 'test9191', NULL, 0, 0, 'test1230', 'test8989', 'test0101', NULL),
(36, 'khader', 'khader', 'khader', 'khader.jeryes@gmail.com', '0533218112', '123123123', NULL, 0, 0, NULL, NULL, NULL, '1997-02-04');

-- --------------------------------------------------------

--
-- Table structure for table `client_login_history`
--

CREATE TABLE `client_login_history` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_login_history`
--

INSERT INTO `client_login_history` (`id`, `username`, `email`, `date`, `success`) VALUES
(57, 'khader', 'khader.jeryes@gmail.com', '2025-02-06 23:17:04', 1),
(58, 'khader', 'khader.jeryes@gmail.com', '2025-02-06 23:17:54', 1),
(59, 'khader', 'khader.jeryes@gmail.com', '2025-02-06 23:21:25', 1);

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` int(11) NOT NULL DEFAULT 0,
  `Answer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `phone`, `email`, `message`, `submission_date`, `Status`, `Answer`) VALUES
(5, 'ali', '0539123222', 'aliahwc@gmail.com', 'hi test mail', '2025-01-06 13:07:53', 1, ''),
(7, 'ali', '0539513806', 'aliahwc@gmail.com', 'hi', '2025-01-13 10:32:19', 0, ''),
(10, 'khader', '0533218112', 'khader.jeryes@gmail.com', 'Khader test', '2025-01-30 11:17:43', 2, NULL),
(11, 'khader', '0533218112', 'khader.jeryes@gmail.com', 'Khader test', '2025-01-30 11:18:39', 1, 'test'),
(12, 'dsvc', '0546584521', 'khader.jeryes@gmail.com', 'kjdsgfi', '2025-01-30 11:30:24', 0, NULL),
(13, 'asd', '0532123545', 'khader.jeryes@gmail.com', 'sadcas', '2025-01-30 11:31:10', 1, NULL),
(14, 'asd', '0532123545', 'khader.jeryes@gmail.com', 'sadcas', '2025-01-30 11:33:55', 0, NULL),
(15, 'asd', '0533218112', 'khader.jeryes@gmail.com', 'dsfasf', '2025-01-30 11:34:18', 0, NULL),
(16, 'kha', '0533218112', 'khader.jeryes@gmail.com', '123456', '2025-01-31 14:20:14', 0, NULL),
(17, 'khader', '0533218112', 'khader.jeryes@gmail.com', 'test', '2025-01-31 16:55:16', 1, NULL),
(18, 'kha', '0533218112', 'khader.jeryes1@gmail.com', 'testtt', '2025-02-03 17:35:07', 1, NULL),
(19, 'khader', '0533218112', 'khader.jeryes@gmail.com', 'asdasd', '2025-02-03 18:55:20', 0, NULL),
(20, 'khader', '0533218112', 'khader.jeryes@gmail.com', 'asdasd', '2025-02-03 18:56:53', 0, NULL),
(21, 'sdf', '0532123545', 'khader.jeryes1@gmail.com', 'asdasd', '2025-02-06 16:13:05', 1, NULL),
(22, 'test', '0532123546', 'khasss@fsdfsdf.Esf', 'test', '2025-02-06 20:57:51', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `conversation_messages`
--

CREATE TABLE `conversation_messages` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `sender` enum('admin','client') NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conversation_messages`
--

INSERT INTO `conversation_messages` (`id`, `contact_id`, `sender`, `message`, `created_at`) VALUES
(1, 5, 'admin', 'hi', '2025-02-01 19:09:19'),
(2, 5, 'admin', 'how are you', '2025-02-01 19:09:29'),
(3, 11, 'admin', 'sadasd', '2025-02-02 14:23:49'),
(4, 11, 'admin', 'asdsd', '2025-02-02 14:24:02'),
(5, 11, 'client', 'as', '2025-02-02 14:25:00'),
(6, 18, 'client', 'sadf', '2025-02-03 17:48:24'),
(7, 18, 'admin', 'asd', '2025-02-03 17:48:29'),
(8, 18, 'client', 'asd', '2025-02-03 18:23:11'),
(9, 13, 'client', 'asd', '2025-02-03 19:04:53'),
(10, 21, 'client', 'asd', '2025-02-06 16:13:58'),
(11, 21, 'admin', 'asdas', '2025-02-06 16:14:05');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `kind` int(11) NOT NULL DEFAULT 0,
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`kind`, `id`, `name`, `price`, `stock`, `path`) VALUES
(1, 43, 'Coca Cola', 7.00, 2, 'Coca Cola.jpg'),
(1, 44, 'Fanta Orange', 7.00, 2, 'Fanta Orange.jpg'),
(1, 45, 'Sprite Extreme', 8.00, 0, 'Sprite Extreme.jpg'),
(1, 46, 'Fanta Strawberry Kiwi', 10.00, 29, 'Fanta Strawberry Kiwi.jpg'),
(1, 47, 'Sprite Zero', 7.00, 50, 'Sprite Zero.jpg'),
(1, 48, 'Coca Cola Zero', 7.00, 100, 'Coca Cola Zero.jpg'),
(1, 49, 'Chocolate Ice Vanil', 10.00, 40, 'Chocolate Ice Vanil.jpg'),
(1, 50, 'Chocolate Milk', 10.00, 40, 'Chocolate Milk.jpg'),
(1, 51, 'Ice Vanil', 10.00, 50, 'Ice Vanil.jpg'),
(1, 52, 'Kabitchino', 6.00, 40, 'Kabitchino.jpg'),
(1, 53, 'Lateh', 8.00, 50, 'Lateh.jpg'),
(1, 54, 'Espresso', 5.00, 60, 'Espresso.jpg'),
(1, 55, 'Dark Chocolate', 15.00, 20, 'Dark Chocolate.jpg'),
(1, 56, 'Arabic Caffe', 12.00, 30, 'Arabic Caffe.jpg'),
(1, 57, 'Tea', 5.00, 100, 'Tea.jpg'),
(1, 58, 'Lemon Juice', 7.00, 100, 'Lemon Jucie.jpg'),
(1, 59, 'Orange Juice', 10.00, 100, 'Orange Jucie.jpg'),
(1, 60, 'Strawberry Juice', 15.00, 50, 'Strawberry Jucie.jpg'),
(2, 62, 'Another Burgerasd', 75.00, 5, 'anotherBurger.jpg'),
(2, 63, 'Yet Another Burger', 100.00, 4, 'burgerTest1111.jpg'),
(2, 64, 'Chicken Burger', 40.00, 4, 'Chicken Burger.jpg'),
(2, 65, 'Mixed Tortia', 60.00, 0, 'tortia.jpg'),
(2, 66, 'Trible Taco', 60.00, 2, 'Taco.jpg'),
(2, 67, 'Home Pizza', 40.00, 2, 'HomePizza.jpg'),
(2, 68, 'Classic Pizza', 65.00, 6, 'ClassicPizza.jpg'),
(2, 69, 'Italic Pizza', 70.00, 2, 'italicpizza.jpg'),
(2, 70, 'Napilion Pizza', 70.00, 9, 'Napilion Pizza.jpg'),
(2, 71, 'Shawrma', 50.00, 5, 'shawrma.jpg'),
(2, 72, 'Baget', 40.00, 3, 'baget1.jpg'),
(2, 74, 'Diet meal 2', 45.00, 7, 'diet2.jpg'),
(2, 75, 'Diet meal 3', 45.00, 3, 'Diet3.jpg'),
(2, 76, 'Fatosh Salad', 30.00, 8, 'fatosh.jpg'),
(2, 77, 'Arabic Salad', 20.00, 2, 'ArabicSalad.jpg'),
(2, 78, 'Tabola', 30.00, 6, 'tabola.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `done` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `total_amount`, `purchase_date`, `done`) VALUES
(17, 36, 100.00, '2025-03-07 21:17:37', 0);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_details`
--

CREATE TABLE `purchase_details` (
  `id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_details`
--

INSERT INTO `purchase_details` (`id`, `purchase_id`, `product_id`, `quantity`, `price`) VALUES
(31, 17, 65, 1, 60.00),
(32, 17, 72, 1, 40.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminusers`
--
ALTER TABLE `adminusers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admin_login_history`
--
ALTER TABLE `admin_login_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `clientusers`
--
ALTER TABLE `clientusers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `client_login_history`
--
ALTER TABLE `client_login_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_phone` (`phone`);

--
-- Indexes for table `conversation_messages`
--
ALTER TABLE `conversation_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `purchase_details`
--
ALTER TABLE `purchase_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_id` (`purchase_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminusers`
--
ALTER TABLE `adminusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `admin_login_history`
--
ALTER TABLE `admin_login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `clientusers`
--
ALTER TABLE `clientusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `client_login_history`
--
ALTER TABLE `client_login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `conversation_messages`
--
ALTER TABLE `conversation_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `purchase_details`
--
ALTER TABLE `purchase_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_login_history`
--
ALTER TABLE `admin_login_history`
  ADD CONSTRAINT `admin_login_history_ibfk_1` FOREIGN KEY (`username`) REFERENCES `adminusers` (`username`),
  ADD CONSTRAINT `admin_login_history_ibfk_2` FOREIGN KEY (`email`) REFERENCES `adminusers` (`email`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `clientusers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `client_login_history`
--
ALTER TABLE `client_login_history`
  ADD CONSTRAINT `client_login_history_ibfk_1` FOREIGN KEY (`username`) REFERENCES `clientusers` (`username`),
  ADD CONSTRAINT `client_login_history_ibfk_2` FOREIGN KEY (`email`) REFERENCES `clientusers` (`email`);

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `clientusers` (`id`);

--
-- Constraints for table `purchase_details`
--
ALTER TABLE `purchase_details`
  ADD CONSTRAINT `purchase_details_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;
