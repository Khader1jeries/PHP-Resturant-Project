-- Database: `contact`
CREATE DATABASE IF NOT EXISTS `contact`;
USE `contact`;

-- Table structure for `contact_us`
CREATE TABLE IF NOT EXISTS `contact_us` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(15) NOT NULL,
  `message` TEXT NOT NULL,
  `submission_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Optional: Insert dummy data for testing
INSERT INTO `contact_us` (`name`, `phone`, `message`, `submission_date`) VALUES
('John Doe', '1234567890', 'This is a test message.', '2025-01-06 12:21:46');
