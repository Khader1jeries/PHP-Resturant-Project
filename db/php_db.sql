-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2025 at 08:18 PM
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
(5, 'admin', 'aliasade215@gmail.com', '2025-02-01 20:33:24', 1);

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
(17, 'test10', 'aksdk', 'aksd', 'aaksmd@gmal.com', '539123222', '1234', NULL, 0, 0, NULL, NULL, NULL, NULL),
(20, 'test12', 'test', 'test', 'test@test.com', '500000000', 'test', NULL, 0, 0, NULL, NULL, NULL, NULL),
(25, 'test3', 'test', 'test', 'aliahwc10@gmail.com', '0501112222', 'test9191', NULL, 0, 0, 'test1230', 'test8989', 'test0101', NULL),
(26, 'khader', 'khader', 'khader', 'khader.jeryes@gmail.com', '0533218112', '123123123', NULL, 0, 0, 'khader123321', 'khader123321', 'khader1231', NULL),
(29, 'sadc', 'asd', 'asd', 'sad@fsfdf.asdf', '0533218112', '1234567', NULL, 0, 0, NULL, NULL, NULL, '2025-01-29'),
(31, 'adssd', 'sfdsfd', 'sddsd', 'sad@fsfdf.asdfs', '0533218112', 'khader', NULL, 0, 0, NULL, NULL, NULL, '2000-01-11');

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
(1, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 16:35:09', 0),
(2, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 16:35:36', 0),
(3, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 16:38:27', 1),
(4, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 16:38:38', 0),
(6, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 16:43:44', 1),
(7, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 16:53:20', 0),
(8, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 16:53:25', 1),
(9, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 17:16:27', 1),
(10, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 17:16:35', 0),
(11, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 17:16:40', 0),
(12, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 17:16:44', 0),
(13, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 17:16:50', 0),
(14, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 17:19:15', 0),
(15, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 17:22:17', 0),
(16, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 17:24:26', 1),
(17, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 17:25:12', 1),
(18, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 17:25:44', 1),
(20, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 18:23:18', 1),
(21, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 18:24:20', 1),
(22, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 18:24:50', 1),
(23, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 18:25:17', 1),
(24, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 18:26:16', 1),
(25, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 19:00:43', 1),
(26, 'khader', 'khader.jeryes@gmail.com', '2025-02-01 19:15:23', 1);

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
(5, 'ali', '0539123222', 'aliahwc@gmail.com', 'hi test mail', '2025-01-06 13:07:53', 0, ''),
(7, 'ali', '0539513806', 'aliahwc@gmail.com', 'hi', '2025-01-13 10:32:19', 0, ''),
(10, 'khader', '0533218112', 'khader.jeryes@gmail.com', 'Khader test', '2025-01-30 11:17:43', 0, NULL),
(11, 'khader', '0533218112', 'khader.jeryes@gmail.com', 'Khader test', '2025-01-30 11:18:39', 0, 'test'),
(12, 'dsvc', '0546584521', 'khader.jeryes@gmail.com', 'kjdsgfi', '2025-01-30 11:30:24', 0, NULL),
(13, 'asd', '0532123545', 'khader.jeryes@gmail.com', 'sadcas', '2025-01-30 11:31:10', 0, NULL),
(14, 'asd', '0532123545', 'khader.jeryes@gmail.com', 'sadcas', '2025-01-30 11:33:55', 0, NULL),
(15, 'asd', '0533218112', 'khader.jeryes@gmail.com', 'dsfasf', '2025-01-30 11:34:18', 0, NULL),
(16, 'kha', '0533218112', 'khader.jeryes@gmail.com', '123456', '2025-01-31 14:20:14', 0, NULL),
(17, 'khader', '0533218112', 'khader.jeryes@gmail.com', 'test', '2025-01-31 16:55:16', 0, NULL);

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
(2, 5, 'admin', 'how are you', '2025-02-01 19:09:29');

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
(1, 43, 'Coca Cola', 7.00, 1, 'Coca Cola.jpg'),
(1, 44, 'Fanta Orange', 7.00, 0, 'Fanta Orange.jpg'),
(1, 45, 'Sprite Extreme', 8.00, 50, 'Sprite Extreme.jpg'),
(1, 46, 'Fanta Strawberry Kiwi', 10.00, 30, 'Fanta Strawberry Kiwi.jpg'),
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
(2, 62, 'Another Burger', 75.00, 0, 'anotherBurger.jpg'),
(2, 63, 'Yet Another Burger', 100.00, 0, 'burgerTest1111.jpg'),
(2, 64, 'Chicken Burger', 40.00, 0, 'Chicken Burger.jpg'),
(2, 65, 'Mixed Tortia', 60.00, 10, 'tortia.jpg'),
(2, 66, 'Trible Taco', 60.00, 4, 'Taco.jpg'),
(2, 67, 'Home Pizza', 40.00, 2, 'HomePizza.jpg'),
(2, 68, 'Classic Pizza', 65.00, 6, 'ClassicPizza.jpg'),
(2, 69, 'Italic Pizza', 70.00, 0, 'italicpizza.jpg'),
(2, 70, 'Napilion Pizza', 70.00, 9, 'Napilion Pizza.jpg'),
(2, 71, 'Shawrma', 50.00, 5, 'shawrma.jpg'),
(2, 72, 'Baget', 40.00, 4, 'baget1.jpg'),
(2, 74, 'Diet meal 2', 45.00, 7, 'diet2.jpg'),
(2, 75, 'Diet meal 3', 45.00, 3, 'Diet3.jpg'),
(2, 76, 'Fatosh Salad', 30.00, 8, 'fatosh.jpg'),
(2, 77, 'Arabic Salad', 20.00, 5, 'ArabicSalad.jpg'),
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
(4, 26, 425.00, '2025-01-31 17:10:35', 0),
(5, 26, 420.00, '2025-01-31 17:11:09', 0),
(6, 26, 75.00, '2025-02-01 17:23:32', 1),
(7, 26, 53.00, '2025-02-01 17:41:35', 1),
(8, 26, 120.00, '2025-02-01 17:49:27', 1),
(9, 26, 720.00, '2025-02-01 17:54:49', 1),
(10, 26, 720.00, '2025-02-01 17:59:08', 1),
(11, 26, 700.00, '2025-02-01 18:00:35', 1);

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
(7, 4, 62, 3, 75.00),
(8, 4, 63, 2, 100.00),
(9, 5, 63, 3, 100.00),
(10, 5, 64, 3, 40.00),
(11, 6, 62, 1, 75.00),
(12, 7, 43, 1, 7.00),
(13, 7, 44, 2, 7.00),
(14, 7, 45, 4, 8.00),
(15, 8, 65, 2, 60.00),
(16, 9, 64, 18, 40.00),
(17, 10, 64, 18, 40.00),
(18, 11, 44, 100, 7.00);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `admin_login_history`
--
ALTER TABLE `admin_login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `clientusers`
--
ALTER TABLE `clientusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `client_login_history`
--
ALTER TABLE `client_login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `conversation_messages`
--
ALTER TABLE `conversation_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `purchase_details`
--
ALTER TABLE `purchase_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
