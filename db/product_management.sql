-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2025 at 12:28 PM
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
-- Database: `product_management`
--

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
(1, 43, 'Coca Cola', 7.00, 100, 'Coca Cola.jpg'),
(1, 44, 'Fanta Orange', 7.00, 100, 'Fanta Orange.jpg'),
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
(2, 61, 'Classic Burger', 40.00, 5, 'classic burger.jpg'),
(2, 62, 'Another Burger', 75.00, 3, 'anotherBurger.jpg'),
(2, 63, 'Yet Another Burger', 100.00, 7, 'Yet Another Burger.jpg'),
(2, 64, 'Chicken Burger', 40.00, 8, 'Chicken Burger.jpg'),
(2, 65, 'Mixed Tortia', 60.00, 10, 'tortia.jpg'),
(2, 66, 'Trible Taco', 60.00, 4, 'Taco.jpg'),
(2, 67, 'Home Pizza', 40.00, 2, 'HomePizza.jpg'),
(2, 68, 'Classic Pizza', 65.00, 6, 'ClassicPizza.jpg'),
(2, 69, 'Italic Pizza', 70.00, 0, 'italicpizza.jpg'),
(2, 70, 'Napilion Pizza', 70.00, 9, 'Napilion Pizza.jpg'),
(2, 71, 'Shawrma', 50.00, 5, 'shawrma.jpg'),
(2, 72, 'Baget', 40.00, 4, 'baget1.jpg'),
(2, 73, 'Diet meal 1', 45.00, 6, 'Diet1.jpg'),
(2, 74, 'Diet meal 2', 45.00, 7, 'diet2.jpg'),
(2, 75, 'Diet meal 3', 45.00, 3, 'Diet3.jpg'),
(2, 76, 'Fatosh Salad', 30.00, 8, 'fatosh.jpg'),
(2, 77, 'Arabic Salad', 20.00, 5, 'ArabicSalad.jpg'),
(2, 78, 'Tabola', 30.00, 6, 'tabola.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
