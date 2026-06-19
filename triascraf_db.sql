-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Jun 2026 pada 10.41
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `triascraf_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `image`, `created_at`) VALUES
(1, 'Hijab Segi Empat', 'hijab-segi-empat', NULL, '2026-06-14 02:59:02'),
(2, 'Hijab Pashmina', 'hijab-pashmina', NULL, '2026-06-14 02:59:02'),
(3, 'Hijab Instan', 'hijab-instan', NULL, '2026-06-14 02:59:02'),
(4, 'Hijab Premium', 'hijab-premium', NULL, '2026-06-14 02:59:02'),
(5, 'aksesoris', 'aksesoris', NULL, '2026-06-14 08:39:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_code` varchar(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `status` enum('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_code`, `name`, `email`, `phone`, `address`, `city`, `province`, `postal_code`, `notes`, `total_price`, `status`, `created_at`) VALUES
(1, 2, 'TRC-C371F5-260614', 'vicii', 'vivitnurul23@gmail.com', '08812320511', 'kp. lubangbuaya', 'bekasi', 'Jawa Barat', '17320', '', 30000.00, 'pending', '2026-06-14 11:23:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(200) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`) VALUES
(1, 1, 2, 'Hijab Segi Empat Polos Premium', 15000.00, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `stock`, `image`, `is_featured`, `is_active`, `created_at`) VALUES
(1, 1, 'Hijab Segi Empat Voal Motif Bunga', 'hijab-segi-empat-voal-motif-bunga', 'Bahan voal premium, motif bunga cantik, cocok untuk acara formal maupun kasual.', 25000.00, 10, 'prod_6a2e6a96f4226.jpg', 1, 1, '2026-06-14 02:59:02'),
(2, 1, 'Hijab Segi Empat Polos Premium', 'hijab-segi-empat-polos-premium', 'Bahan voal polos lembut, tersedia dalam berbagai warna pastel.', 15000.00, 5, 'prod_6a2eb24c6f294.jpg', 1, 1, '2026-06-14 02:59:02'),
(3, 2, 'Pashmina Printing Sublime Motif Cashmere Turki Viral', 'pashmina-printing-sublime-motif-cashmere-turki-viral', 'Pashmina bahan ceruti dengan motif Printing Sublime Motif Cashmere Turki yg modern dan cantik', 39000.00, 5, 'prod_6a2eb454ef5be.jpg', 1, 1, '2026-06-14 02:59:02'),
(4, 2, 'Pashmina Diamond shimmer', 'pashmina-diamond-shimmer', 'Kombinasi bahan diamond dengan sentuhan glitter halus.', 25000.00, 4, 'prod_6a2eb4d2d117a.jpg', 0, 1, '2026-06-14 02:59:02'),
(5, 3, 'Hijab Instan Jersey Sporty', 'hijab-instan-jersey-sporty', 'Nyaman dipakai sehari-hari, bahan jersey stretch.', 15000.00, 5, 'prod_6a2e8db3be33e.jpg', 1, 1, '2026-06-14 02:59:02'),
(6, 4, 'Hijab Pashmina ombre gradasi', 'hijab-pashmina-ombre-gradasi', 'bahan yg adem dan berkualitas yg lagi hits sekarang', 30000.00, 5, 'prod_6a2e9c61b8728.jpg', 1, 1, '2026-06-14 02:59:02'),
(7, 5, 'Cepol Hijab', 'cepol-hijab', 'cepol terbaik yg nyaman dan tidak bikin kepala sakit', 7000.00, 5, 'prod_6a2e6a41e7c55.jpg', 0, 1, '2026-06-14 08:45:53'),
(8, 2, 'Pashmina Viscose', 'pashmina-viscose', 'pashmina viscose bahan premium yang adem dan nyaman digunakan untuk muslimah', 40000.00, 5, 'prod_6a2e9bf67ecf8.jpg', 1, 1, '2026-06-14 12:17:58'),
(9, 5, 'Ciput Hijab', 'ciput-hijab', 'Ciput adem berbahan premium', 10000.00, 10, 'prod_6a2e9cd43ba62.jpg', 0, 1, '2026-06-14 12:21:40'),
(10, 2, 'pashmina viscose motif', 'pashmina-viscose-motif', 'motif modern dengan bahan viscose yg adem', 40000.00, 5, 'prod_6a2e9d5d6b933.jpg', 0, 1, '2026-06-14 12:23:57'),
(11, 5, 'Bros Dagu', 'bros-dagu', '', 5000.00, 5, 'prod_6a2e9dde21bbc.jpg', 0, 1, '2026-06-14 12:26:06'),
(12, 2, 'Pashmina Ceruty Baby Doll', 'pashmina-ceruty-baby-doll', '', 25000.00, 3, 'prod_6a2eb66ccc4ba.jpg', 0, 1, '2026-06-14 14:10:52'),
(13, 1, 'Segi empat Bella Squer', 'segi-empat-bella-squer', '', 13000.00, 5, 'prod_6a2eb7a8d181b.jpg', 1, 1, '2026-06-14 14:16:08'),
(14, 2, 'Pashmina Kaos Rayon Premium', 'pashmina-kaos-rayon-premium', 'Pashmina Kaos Rayon Premium, dengan bahan yang lembut dan nyaman', 30000.00, 5, 'prod_6a2eb825f168d.jpg', 1, 1, '2026-06-14 14:18:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `key_name`, `value`) VALUES
(1, 'whatsapp_number', '083807066072'),
(2, 'whatsapp_message', 'Halo Triascraf! Saya ingin memesan produk berikut:'),
(3, 'store_name', 'Triascraf'),
(4, 'store_tagline', 'Elegan dalam Balutan Syar&amp;#039;i'),
(5, 'store_email', 'triascarfofficial@gmail.com'),
(6, 'store_instagram', 'triascrafofficial'),
(7, 'store_address', 'JL. Tegal Danas, Bekasi Regency, Bekasi, Jawa Barat');

-- --------------------------------------------------------

--
-- Struktur dari tabel `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `rating` int(11) DEFAULT 5,
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `testimonials`
--

INSERT INTO `testimonials` (`id`, `user_id`, `name`, `message`, `rating`, `is_approved`, `created_at`) VALUES
(1, 2, 'vicii', 'baguss, suka bnget sama kerudung nyaa', 5, 1, '2026-06-14 13:22:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `city`, `province`, `postal_code`, `role`, `created_at`) VALUES
(1, 'Admin Triascraf', 'admin@triascraf.com', '$2y$10$d4X790JZhZipNKCcVQz7POzdlUwTBLTZBGoMuD1EOmn.tdmjg6zfG', NULL, NULL, NULL, NULL, NULL, 'admin', '2026-06-14 02:59:01'),
(2, 'vicii', 'vivitnurul23@gmail.com', '$2y$10$awC6W4fDcpDr8UIzoamcve2Z6hi21i2auPEAK7m24IyYuVE1yjIgS', '08812320511', 'Jl. Bunga mawar no.25 RT/RW 002/06', 'bekasi', 'Jawa Barat', '17320', 'user', '2026-06-14 11:22:06');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name` (`key_name`);

--
-- Indeks untuk tabel `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `testimonials`
--
ALTER TABLE `testimonials`
  ADD CONSTRAINT `testimonials_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
