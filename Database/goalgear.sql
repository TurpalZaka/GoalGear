-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 08. Jun 2024 um 14:44
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `goalgear`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cartitems`
--

CREATE TABLE `cartitems` (
  `cart_item_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `session_id` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Nationalmannschaft-Trikot'),
(2, 'Fußballschuhe'),
(3, 'Accessoires & Ausrüstung');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `orderdetails`
--

CREATE TABLE `orderdetails` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `orderdetails`
--

INSERT INTO `orderdetails` (`order_detail_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(33, 17, 13, 2, 99.99),
(34, 17, 19, 1, 119.99),
(35, 17, 24, 1, 289.99),
(36, 17, 27, 2, 27.99),
(37, 17, 28, 2, 24.99),
(38, 18, 11, 1, 99.99),
(39, 18, 12, 1, 99.99),
(40, 18, 13, 1, 99.99),
(41, 18, 14, 1, 99.99),
(42, 18, 15, 1, 99.99),
(43, 18, 16, 1, 99.99);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `order_date`, `total_amount`, `status`) VALUES
(17, 20, '2024-06-08 12:37:51', 715.92, 'pending'),
(18, 20, '2024-06-08 12:38:49', 599.94, 'pending');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `rating` float DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `products`
--

INSERT INTO `products` (`id`, `name`, `image`, `price`, `rating`, `category_id`, `description`) VALUES
(11, 'Frankreich 2024/25', '../../Backend/productpictures/1717848688.jpg', 99.99, 4.5, 1, 'Offizielles Trikot der französischen Nationalmannschaft'),
(12, 'England 2024/25', '../../Backend/productpictures/1717848823.png', 99.99, 3.8, 1, 'Offizielles Trikot der englischen Nationalmannschaft'),
(13, 'Kroatien 2024/25', '../../Backend/productpictures/1717848985.png', 99.99, 3.9, 1, 'Offizielles Trikot der kroatischen Nationalmannschaft'),
(14, 'Niederlande 2024/25', '../../Backend/productpictures/1717848882.jpg', 99.99, 4.1, 1, 'Offizielles Trikot der niederländischen Nationalmannschaft'),
(15, 'Brasilien 2024/25', '../../Backend/productpictures/1717849009.jpg', 99.99, 4.5, 1, 'Offizielles Trikot der brasilianischen Nationalmannschaft'),
(16, 'Portugal 2024/25', '../../Backend/productpictures/1717849157.jpg', 99.99, 4.7, 1, 'Offizielles Trikot der portugiesischen Nationalmannschaft'),
(19, 'Nike Jr. Mercurial Superfly 9 Pro', '../../Backend/productpictures/1717849407.png', 119.99, 4.8, 2, 'High-Low-Fußballschuh für normalen Rasen'),
(20, 'Nike Phantom GX 2 Elite \"Erling Haaland Force9\"', '../../Backend/productpictures/1717849438.png', 269.99, 4.1, 2, 'FG Low-Top-Fußballschuh'),
(21, 'Nike Vapor 15 Academy Mercurial Dream Speed', '../../Backend/productpictures/1717849480.png', 89.99, 4.8, 2, 'MG Low-Top-Fußballschuh'),
(22, 'Nike Tiempo Legend 10 Academy', '../../Backend/productpictures/1717849541.png', 84.99, 4.9, 2, 'Low-Top-Fußballschuh für verschiedene Böden'),
(23, 'Nike Mercurial Vapor 15 Academy', '../../Backend/productpictures/1717849592.png', 119.99, 2.9, 2, 'Fußballschuh für verschiedene Böden'),
(24, 'Nike Mercurial Superfly 9 Elite SE', '../../Backend/productpictures/1717849627.png', 289.99, 3, 2, 'High-Top-Fußballschuh für normalen Rasen'),
(25, 'Paris Saint-Germain Academy', '../../Backend/productpictures/1717849790.png', 27.99, 4.2, 3, 'Fußball'),
(26, 'FC Barcelona Academy', '../../Backend/productpictures/1717849817.png', 27.99, 1.7, 3, 'Fußball'),
(27, 'Liverpool Academy', '../../Backend/productpictures/1717849849.jpg', 27.99, 5, 3, 'Fußball'),
(28, 'Nike Match Jr.', '../../Backend/productpictures/1717849912.png', 24.99, 4.4, 3, 'Torwarthandschuhe'),
(29, 'Nike Guard Lock', '../../Backend/productpictures/1717849976.png', 11.99, 4.8, 3, 'Fußball Schienbeinschoner-Stutzen (1 Paar)');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `payment_info` varchar(255) DEFAULT NULL,
  `salutation` varchar(50) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `address`, `city`, `postal_code`, `payment_info`, `salutation`, `first_name`, `last_name`, `role`, `active`) VALUES
(20, 'Masenku', 'marcel.ivanic@gmx.at', '$2y$10$BN/19EqNWr50F0Ls70ITAe908gev4Vua0ZeH9YUoINap4ewq4XNN.', 'Am Kanal 3', 'Wien', '1110', '', 'Herr', 'Marcel', 'Ivanic', 'user', 1),
(21, 'Admin', 'Admin@gmail.com', '$2y$10$2y/ptYqChzEpIIAViQd5COsv1oINufSlU8eBAw0trQSGUd8fdaboK', 'Donaustadt 6', 'Wien', '1220', '', 'Herr', 'Admin', 'Admin', 'admin', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `value` float NOT NULL,
  `expiry_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `value`, `expiry_date`) VALUES
(1, 'NAO0D', 50, '2024-06-08'),
(2, 'KX77U', 0, '2024-05-13'),
(3, 'BPI79', 50.32, '2024-06-14');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `cartitems`
--
ALTER TABLE `cartitems`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indizes für die Tabelle `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indizes für die Tabelle `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indizes für die Tabelle `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `cartitems`
--
ALTER TABLE `cartitems`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT für Tabelle `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT für Tabelle `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT für Tabelle `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT für Tabelle `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `cartitems`
--
ALTER TABLE `cartitems`
  ADD CONSTRAINT `cartitems_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cartitems_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
