-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20260526.9a43c2e222
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 29 Jun 2026 pada 09.07
-- Versi server: 8.0.30
-- Versi PHP: 8.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `poktan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `batas_pupuk_petani`
--

CREATE TABLE `batas_pupuk_petani` (
  `id` bigint UNSIGNED NOT NULL,
  `id_petani` bigint UNSIGNED NOT NULL,
  `id_produk_pupuk` bigint UNSIGNED NOT NULL,
  `maksimal_jumlah` int UNSIGNED NOT NULL DEFAULT '0',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `batas_pupuk_petani`
--

INSERT INTO `batas_pupuk_petani` (`id`, `id_petani`, `id_produk_pupuk`, `maksimal_jumlah`, `aktif`, `created_at`, `updated_at`) VALUES
(1, 8, 1, 1, 1, '2026-06-29 01:32:34', '2026-06-29 01:32:34'),
(2, 8, 2, 1, 1, '2026-06-29 01:32:34', '2026-06-29 01:32:34'),
(3, 8, 3, 1, 1, '2026-06-29 01:32:34', '2026-06-29 01:32:34'),
(4, 8, 4, 1, 1, '2026-06-29 01:32:34', '2026-06-29 01:32:34'),
(5, 8, 5, 1, 1, '2026-06-29 01:32:34', '2026-06-29 01:32:34'),
(6, 8, 6, 0, 1, '2026-06-29 01:32:34', '2026-06-29 01:38:29'),
(7, 6, 1, 1, 1, '2026-06-29 01:38:38', '2026-06-29 01:38:38'),
(8, 6, 2, 0, 1, '2026-06-29 01:38:38', '2026-06-29 01:49:56'),
(9, 6, 3, 1, 1, '2026-06-29 01:38:38', '2026-06-29 01:38:38'),
(10, 6, 4, 1, 1, '2026-06-29 01:38:38', '2026-06-29 01:38:38'),
(11, 6, 5, 1, 1, '2026-06-29 01:38:38', '2026-06-29 01:38:38'),
(12, 6, 6, 1, 1, '2026-06-29 01:38:38', '2026-06-29 01:53:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('poktan-lancang-kuning-cache-15ace6544afcc8efea8963df5fbb6c7c', 'i:1;', 1782714056),
('poktan-lancang-kuning-cache-15ace6544afcc8efea8963df5fbb6c7c:timer', 'i:1782714056;', 1782714056),
('poktan-lancang-kuning-cache-18651eb7ee8908c436ff0501fc0c506f', 'i:2;', 1782696442),
('poktan-lancang-kuning-cache-18651eb7ee8908c436ff0501fc0c506f:timer', 'i:1782696442;', 1782696442),
('poktan-lancang-kuning-cache-187b5784fa4e4f82bfc7907e6ecdcc9e', 'i:1;', 1782717484),
('poktan-lancang-kuning-cache-187b5784fa4e4f82bfc7907e6ecdcc9e:timer', 'i:1782717484;', 1782717484),
('poktan-lancang-kuning-cache-1c31ecdcf43a4c45335e125fdd661c66', 'i:1;', 1782717358),
('poktan-lancang-kuning-cache-1c31ecdcf43a4c45335e125fdd661c66:timer', 'i:1782717358;', 1782717358),
('poktan-lancang-kuning-cache-2181081540c9893b09de3962e8ba9b9d', 'i:1;', 1782696271),
('poktan-lancang-kuning-cache-2181081540c9893b09de3962e8ba9b9d:timer', 'i:1782696271;', 1782696271),
('poktan-lancang-kuning-cache-21c7ea48997eeecf541f9afb4a8bfc81', 'i:6;', 1782714491),
('poktan-lancang-kuning-cache-21c7ea48997eeecf541f9afb4a8bfc81:timer', 'i:1782714491;', 1782714491),
('poktan-lancang-kuning-cache-2488853d5626dc20f99e34b9ffd99768', 'i:1;', 1782698440),
('poktan-lancang-kuning-cache-2488853d5626dc20f99e34b9ffd99768:timer', 'i:1782698440;', 1782698440),
('poktan-lancang-kuning-cache-25da519ca5d8c76a13542b1c393e2fef', 'i:1;', 1782717280),
('poktan-lancang-kuning-cache-25da519ca5d8c76a13542b1c393e2fef:timer', 'i:1782717280;', 1782717280),
('poktan-lancang-kuning-cache-2741dba96ac1ff4bcfcab1e559b9fbb1', 'i:1;', 1782696333),
('poktan-lancang-kuning-cache-2741dba96ac1ff4bcfcab1e559b9fbb1:timer', 'i:1782696333;', 1782696333),
('poktan-lancang-kuning-cache-29c75e1ad425032554a3369959839cee', 'i:1;', 1782714056),
('poktan-lancang-kuning-cache-29c75e1ad425032554a3369959839cee:timer', 'i:1782714056;', 1782714056),
('poktan-lancang-kuning-cache-2b8b7d82d67a1994fbc48c67a5f3f168', 'i:1;', 1782716802),
('poktan-lancang-kuning-cache-2b8b7d82d67a1994fbc48c67a5f3f168:timer', 'i:1782716802;', 1782716802),
('poktan-lancang-kuning-cache-2edd9c55ac2c71a009f0edf9371b447d', 'i:3;', 1782717468),
('poktan-lancang-kuning-cache-2edd9c55ac2c71a009f0edf9371b447d:timer', 'i:1782717468;', 1782717468),
('poktan-lancang-kuning-cache-343e0a1a4a5a5b990b7813b66a75731e', 'i:3;', 1782699986),
('poktan-lancang-kuning-cache-343e0a1a4a5a5b990b7813b66a75731e:timer', 'i:1782699986;', 1782699986),
('poktan-lancang-kuning-cache-481eea3809f3fcfeb0f0df82b04f04a1', 'i:1;', 1782717811),
('poktan-lancang-kuning-cache-481eea3809f3fcfeb0f0df82b04f04a1:timer', 'i:1782717811;', 1782717811),
('poktan-lancang-kuning-cache-5785c8e36e5b630c81ff5f2a7188b188', 'i:2;', 1782698398),
('poktan-lancang-kuning-cache-5785c8e36e5b630c81ff5f2a7188b188:timer', 'i:1782698398;', 1782698398),
('poktan-lancang-kuning-cache-784918fc09c45fdea848126fa9507023', 'i:1;', 1782716668),
('poktan-lancang-kuning-cache-784918fc09c45fdea848126fa9507023:timer', 'i:1782716668;', 1782716668),
('poktan-lancang-kuning-cache-7be4d43d0f31a4410fa427ea07229edf', 'i:3;', 1782713928),
('poktan-lancang-kuning-cache-7be4d43d0f31a4410fa427ea07229edf:timer', 'i:1782713928;', 1782713928),
('poktan-lancang-kuning-cache-7c2150d7106073168321f39ec452420d', 'i:10;', 1782717812),
('poktan-lancang-kuning-cache-7c2150d7106073168321f39ec452420d:timer', 'i:1782717812;', 1782717812),
('poktan-lancang-kuning-cache-7c7bbdd8392ceaf9c128cd15d267ca40', 'i:1;', 1782698079),
('poktan-lancang-kuning-cache-7c7bbdd8392ceaf9c128cd15d267ca40:timer', 'i:1782698079;', 1782698079),
('poktan-lancang-kuning-cache-7ec5562a71b95bd0cba6053c68685552', 'i:2;', 1782696442),
('poktan-lancang-kuning-cache-7ec5562a71b95bd0cba6053c68685552:timer', 'i:1782696442;', 1782696442),
('poktan-lancang-kuning-cache-830b3b63aeb7c393f1a75d0894c14921', 'i:4;', 1782698457),
('poktan-lancang-kuning-cache-830b3b63aeb7c393f1a75d0894c14921:timer', 'i:1782698457;', 1782698457),
('poktan-lancang-kuning-cache-8b5b977ab8b0ee02059a025119a6ccbf', 'i:1;', 1782698456),
('poktan-lancang-kuning-cache-8b5b977ab8b0ee02059a025119a6ccbf:timer', 'i:1782698456;', 1782698456),
('poktan-lancang-kuning-cache-8d506be3a010b2bac0984ebf9ec2ec8f', 'i:2;', 1782547340),
('poktan-lancang-kuning-cache-8d506be3a010b2bac0984ebf9ec2ec8f:timer', 'i:1782547340;', 1782547340),
('poktan-lancang-kuning-cache-946216041a3707ef5510bb6c4fe64f6c', 'i:5;', 1782550820),
('poktan-lancang-kuning-cache-946216041a3707ef5510bb6c4fe64f6c:timer', 'i:1782550820;', 1782550820),
('poktan-lancang-kuning-cache-94d92f976fd06fd3e8cf53ec4e03d646', 'i:1;', 1782717358),
('poktan-lancang-kuning-cache-94d92f976fd06fd3e8cf53ec4e03d646:timer', 'i:1782717358;', 1782717358),
('poktan-lancang-kuning-cache-96519e08e4b80d83b272cdfe293c0b76', 'i:1;', 1782699811),
('poktan-lancang-kuning-cache-96519e08e4b80d83b272cdfe293c0b76:timer', 'i:1782699811;', 1782699811),
('poktan-lancang-kuning-cache-a3affa0d1e1a3c72b78aa984c3367a05', 'i:3;', 1782696950),
('poktan-lancang-kuning-cache-a3affa0d1e1a3c72b78aa984c3367a05:timer', 'i:1782696950;', 1782696950),
('poktan-lancang-kuning-cache-a85a9a9c3f91bf7be0ea35d618d9595a', 'i:2;', 1782716669),
('poktan-lancang-kuning-cache-a85a9a9c3f91bf7be0ea35d618d9595a:timer', 'i:1782716669;', 1782716669),
('poktan-lancang-kuning-cache-ae8e124079681b6974342984529676c9', 'i:1;', 1782714001),
('poktan-lancang-kuning-cache-ae8e124079681b6974342984529676c9:timer', 'i:1782714001;', 1782714001),
('poktan-lancang-kuning-cache-b2a71d0af133128051c2473c9cfa9040', 'i:1;', 1782716802),
('poktan-lancang-kuning-cache-b2a71d0af133128051c2473c9cfa9040:timer', 'i:1782716802;', 1782716802),
('poktan-lancang-kuning-cache-b37e2b0b86a8368405df02e0b66d0b39', 'i:4;', 1782718005),
('poktan-lancang-kuning-cache-b37e2b0b86a8368405df02e0b66d0b39:timer', 'i:1782718005;', 1782718005),
('poktan-lancang-kuning-cache-b5327024a67c20eee17968adba0251e8', 'i:6;', 1782699804),
('poktan-lancang-kuning-cache-b5327024a67c20eee17968adba0251e8:timer', 'i:1782699804;', 1782699804),
('poktan-lancang-kuning-cache-bcc4ed2eb13c9255957bd891a5d4accd', 'i:1;', 1782714178),
('poktan-lancang-kuning-cache-bcc4ed2eb13c9255957bd891a5d4accd:timer', 'i:1782714178;', 1782714178),
('poktan-lancang-kuning-cache-be1cb898a7bf02b18bf8f295136ac2cb', 'i:1;', 1782717811),
('poktan-lancang-kuning-cache-be1cb898a7bf02b18bf8f295136ac2cb:timer', 'i:1782717811;', 1782717811),
('poktan-lancang-kuning-cache-c636f71fad55512f088d69ca9514c688', 'i:1;', 1782714001),
('poktan-lancang-kuning-cache-c636f71fad55512f088d69ca9514c688:timer', 'i:1782714001;', 1782714001),
('poktan-lancang-kuning-cache-cc5febc8615e1b6a5acb50412e434064', 'i:1;', 1782714178),
('poktan-lancang-kuning-cache-cc5febc8615e1b6a5acb50412e434064:timer', 'i:1782714178;', 1782714178),
('poktan-lancang-kuning-cache-cuaca-lokasi-1.38-109.32', 'a:2:{s:6:\"status\";s:7:\"success\";s:4:\"data\";a:3:{s:8:\"location\";s:13:\"Tanjung Mekar\";s:10:\"total_data\";i:19;s:8:\"forecast\";a:19:{i:0;a:6:{s:14:\"local_datetime\";s:19:\"2026-06-29 15:00:00\";s:11:\"temperature\";i:29;s:7:\"weather\";s:13:\"Cerah Berawan\";s:8:\"humidity\";i:82;s:10:\"wind_speed\";d:9.1;s:14:\"wind_direction\";s:2:\"NW\";}i:1;a:6:{s:14:\"local_datetime\";s:19:\"2026-06-29 18:00:00\";s:11:\"temperature\";i:27;s:7:\"weather\";s:12:\"Hujan Ringan\";s:8:\"humidity\";i:89;s:10:\"wind_speed\";d:3.7;s:14:\"wind_direction\";s:2:\"SW\";}i:2;a:6:{s:14:\"local_datetime\";s:19:\"2026-06-29 21:00:00\";s:11:\"temperature\";i:26;s:7:\"weather\";s:13:\"Cerah Berawan\";s:8:\"humidity\";i:95;s:10:\"wind_speed\";d:5.1;s:14:\"wind_direction\";s:2:\"SE\";}i:3;a:6:{s:14:\"local_datetime\";s:19:\"2026-06-30 00:00:00\";s:11:\"temperature\";i:24;s:7:\"weather\";s:7:\"Berawan\";s:8:\"humidity\";i:95;s:10:\"wind_speed\";d:3.3;s:14:\"wind_direction\";s:2:\"SE\";}i:4;a:6:{s:14:\"local_datetime\";s:19:\"2026-06-30 03:00:00\";s:11:\"temperature\";i:24;s:7:\"weather\";s:11:\"Udara Kabur\";s:8:\"humidity\";i:97;s:10:\"wind_speed\";d:4.6;s:14:\"wind_direction\";s:1:\"E\";}i:5;a:6:{s:14:\"local_datetime\";s:19:\"2026-06-30 06:00:00\";s:11:\"temperature\";i:25;s:7:\"weather\";s:13:\"Cerah Berawan\";s:8:\"humidity\";i:92;s:10:\"wind_speed\";i:8;s:14:\"wind_direction\";s:1:\"E\";}i:6;a:6:{s:14:\"local_datetime\";s:19:\"2026-06-30 09:00:00\";s:11:\"temperature\";i:29;s:7:\"weather\";s:13:\"Cerah Berawan\";s:8:\"humidity\";i:74;s:10:\"wind_speed\";d:10.1;s:14:\"wind_direction\";s:2:\"SE\";}i:7;a:6:{s:14:\"local_datetime\";s:19:\"2026-06-30 12:00:00\";s:11:\"temperature\";i:28;s:7:\"weather\";s:7:\"Berawan\";s:8:\"humidity\";i:84;s:10:\"wind_speed\";d:14.8;s:14:\"wind_direction\";s:1:\"W\";}i:8;a:6:{s:14:\"local_datetime\";s:19:\"2026-06-30 15:00:00\";s:11:\"temperature\";i:25;s:7:\"weather\";s:12:\"Hujan Ringan\";s:8:\"humidity\";i:97;s:10:\"wind_speed\";d:3.1;s:14:\"wind_direction\";s:2:\"SW\";}i:9;a:6:{s:14:\"local_datetime\";s:19:\"2026-06-30 18:00:00\";s:11:\"temperature\";i:25;s:7:\"weather\";s:7:\"Berawan\";s:8:\"humidity\";i:98;s:10:\"wind_speed\";d:4.8;s:14:\"wind_direction\";s:2:\"SE\";}i:10;a:6:{s:14:\"local_datetime\";s:19:\"2026-06-30 21:00:00\";s:11:\"temperature\";i:24;s:7:\"weather\";s:13:\"Cerah Berawan\";s:8:\"humidity\";i:98;s:10:\"wind_speed\";d:4.9;s:14:\"wind_direction\";s:1:\"S\";}i:11;a:6:{s:14:\"local_datetime\";s:19:\"2026-07-01 00:00:00\";s:11:\"temperature\";i:24;s:7:\"weather\";s:7:\"Berawan\";s:8:\"humidity\";i:97;s:10:\"wind_speed\";d:3.8;s:14:\"wind_direction\";s:1:\"S\";}i:12;a:6:{s:14:\"local_datetime\";s:19:\"2026-07-01 03:00:00\";s:11:\"temperature\";i:24;s:7:\"weather\";s:7:\"Berawan\";s:8:\"humidity\";i:97;s:10:\"wind_speed\";d:2.2;s:14:\"wind_direction\";s:1:\"S\";}i:13;a:6:{s:14:\"local_datetime\";s:19:\"2026-07-01 06:00:00\";s:11:\"temperature\";i:24;s:7:\"weather\";s:5:\"Cerah\";s:8:\"humidity\";i:96;s:10:\"wind_speed\";d:9.1;s:14:\"wind_direction\";s:2:\"SE\";}i:14;a:6:{s:14:\"local_datetime\";s:19:\"2026-07-01 09:00:00\";s:11:\"temperature\";i:28;s:7:\"weather\";s:13:\"Cerah Berawan\";s:8:\"humidity\";i:84;s:10:\"wind_speed\";d:9.1;s:14:\"wind_direction\";s:2:\"SE\";}i:15;a:6:{s:14:\"local_datetime\";s:19:\"2026-07-01 12:00:00\";s:11:\"temperature\";i:30;s:7:\"weather\";s:13:\"Cerah Berawan\";s:8:\"humidity\";i:74;s:10:\"wind_speed\";d:6.8;s:14:\"wind_direction\";s:2:\"SE\";}i:16;a:6:{s:14:\"local_datetime\";s:19:\"2026-07-01 15:00:00\";s:11:\"temperature\";i:27;s:7:\"weather\";s:13:\"Cerah Berawan\";s:8:\"humidity\";i:93;s:10:\"wind_speed\";d:6.8;s:14:\"wind_direction\";s:2:\"SE\";}i:17;a:6:{s:14:\"local_datetime\";s:19:\"2026-07-01 18:00:00\";s:11:\"temperature\";i:26;s:7:\"weather\";s:5:\"Cerah\";s:8:\"humidity\";i:97;s:10:\"wind_speed\";d:4.3;s:14:\"wind_direction\";s:1:\"E\";}i:18;a:6:{s:14:\"local_datetime\";s:19:\"2026-07-01 21:00:00\";s:11:\"temperature\";i:25;s:7:\"weather\";s:7:\"Berawan\";s:8:\"humidity\";i:97;s:10:\"wind_speed\";d:4.3;s:14:\"wind_direction\";s:1:\"E\";}}}}', 1782717510),
('poktan-lancang-kuning-cache-d2f73a23d8dcd6e08308b4bdbbbedc3c', 'i:1;', 1782714205),
('poktan-lancang-kuning-cache-d2f73a23d8dcd6e08308b4bdbbbedc3c:timer', 'i:1782714205;', 1782714205),
('poktan-lancang-kuning-cache-d5dd0f3caf5792f7c9814f5e4ba7dfde', 'i:1;', 1782696333),
('poktan-lancang-kuning-cache-d5dd0f3caf5792f7c9814f5e4ba7dfde:timer', 'i:1782696333;', 1782696333),
('poktan-lancang-kuning-cache-e414752f59749559742c2d2d4dc69c69', 'i:2;', 1782696369),
('poktan-lancang-kuning-cache-e414752f59749559742c2d2d4dc69c69:timer', 'i:1782696369;', 1782696369),
('poktan-lancang-kuning-cache-e7cf66797159dc3cd3e85f72e15bb551', 'i:24;', 1782715498),
('poktan-lancang-kuning-cache-e7cf66797159dc3cd3e85f72e15bb551:timer', 'i:1782715498;', 1782715498),
('poktan-lancang-kuning-cache-e9b6cc1432541b9ceebf113eee05eeba', 'i:8;', 1782716668),
('poktan-lancang-kuning-cache-e9b6cc1432541b9ceebf113eee05eeba:timer', 'i:1782716668;', 1782716668),
('poktan-lancang-kuning-cache-f19aa7212a0f189a0951135dc2d8128e', 'i:1;', 1782717484),
('poktan-lancang-kuning-cache-f19aa7212a0f189a0951135dc2d8128e:timer', 'i:1782717484;', 1782717484),
('poktan-lancang-kuning-cache-f1f70ec40aaa556905d4a030501c0ba4', 'i:1;', 1782717358),
('poktan-lancang-kuning-cache-f1f70ec40aaa556905d4a030501c0ba4:timer', 'i:1782717358;', 1782717358),
('poktan-lancang-kuning-cache-f62451cccb3ac0945a15a1ae4d80a55a', 'i:1;', 1782716680),
('poktan-lancang-kuning-cache-f62451cccb3ac0945a15a1ae4d80a55a:timer', 'i:1782716680;', 1782716680);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pesanan_marketplace`
--

CREATE TABLE `detail_pesanan_marketplace` (
  `id` bigint UNSIGNED NOT NULL,
  `id_pesanan_marketplace` bigint UNSIGNED NOT NULL,
  `id_produk_marketplace` bigint UNSIGNED DEFAULT NULL,
  `nama_produk` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int UNSIGNED NOT NULL DEFAULT '1',
  `satuan` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kg',
  `harga_satuan` decimal(15,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `subtotal` decimal(15,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `detail_pesanan_marketplace`
--

INSERT INTO `detail_pesanan_marketplace` (`id`, `id_pesanan_marketplace`, `id_produk_marketplace`, `nama_produk`, `jumlah`, `satuan`, `harga_satuan`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Padi ringka\'', 5, 'kg', 12000.00, 60000.00, '2026-06-29 01:30:39', '2026-06-29 01:30:39'),
(2, 2, 1, 'Padi ringka\'', 15, 'kg', 12000.00, 180000.00, '2026-06-29 01:41:23', '2026-06-29 01:41:23'),
(3, 3, 1, 'Padi ringka\'', 1, 'kg', 12000.00, 12000.00, '2026-06-29 01:41:26', '2026-06-29 01:41:26'),
(4, 4, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 01:43:33', '2026-06-29 01:43:33'),
(5, 5, 2, 'Padi ampari', 30, 'Karung', 6300.00, 189000.00, '2026-06-29 01:47:15', '2026-06-29 01:47:15'),
(6, 6, 2, 'Padi ampari', 1, 'Karung', 6300.00, 6300.00, '2026-06-29 01:47:19', '2026-06-29 01:47:19'),
(7, 7, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:58', '2026-06-29 07:19:58'),
(8, 8, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:58', '2026-06-29 07:19:58'),
(9, 9, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:58', '2026-06-29 07:19:58'),
(10, 10, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:59', '2026-06-29 07:19:59'),
(11, 11, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:59', '2026-06-29 07:19:59'),
(12, 12, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:59', '2026-06-29 07:19:59'),
(13, 13, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:59', '2026-06-29 07:19:59'),
(14, 14, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:59', '2026-06-29 07:19:59'),
(15, 15, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:59', '2026-06-29 07:19:59'),
(16, 16, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:59', '2026-06-29 07:19:59'),
(17, 17, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:59', '2026-06-29 07:19:59'),
(18, 18, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:59', '2026-06-29 07:19:59'),
(19, 19, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:59', '2026-06-29 07:19:59'),
(20, 20, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:59', '2026-06-29 07:19:59'),
(21, 21, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:59', '2026-06-29 07:19:59'),
(22, 22, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:59', '2026-06-29 07:19:59'),
(23, 23, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:19:59', '2026-06-29 07:19:59'),
(24, 24, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:20:00', '2026-06-29 07:20:00'),
(25, 25, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:20:00', '2026-06-29 07:20:00'),
(26, 26, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:20:00', '2026-06-29 07:20:00'),
(27, 27, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:20:00', '2026-06-29 07:20:00'),
(28, 28, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:20:02', '2026-06-29 07:20:02'),
(29, 29, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:20:02', '2026-06-29 07:20:02'),
(30, 30, 1, 'Padi ringka\'', 2, 'kg', 12000.00, 24000.00, '2026-06-29 07:20:04', '2026-06-29 07:20:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pesanan_pupuk`
--

CREATE TABLE `detail_pesanan_pupuk` (
  `id` bigint UNSIGNED NOT NULL,
  `id_pesanan_pupuk` bigint UNSIGNED NOT NULL,
  `id_produk_pupuk` bigint UNSIGNED DEFAULT NULL,
  `nama_produk` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int UNSIGNED NOT NULL DEFAULT '1',
  `satuan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `harga_satuan` decimal(15,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `subtotal` decimal(15,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `detail_pesanan_pupuk`
--

INSERT INTO `detail_pesanan_pupuk` (`id`, `id_pesanan_pupuk`, `id_produk_pupuk`, `nama_produk`, `jumlah`, `satuan`, `harga_satuan`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 'KCL', 1, '50 kg', 130000.00, 130000.00, '2026-06-29 01:35:09', '2026-06-29 01:35:09'),
(2, 2, 6, 'KCL', 1, '50 kg', 130000.00, 130000.00, '2026-06-29 01:35:30', '2026-06-29 01:35:30'),
(3, 3, 6, 'KCL', 1, '50 kg', 130000.00, 130000.00, '2026-06-29 01:38:29', '2026-06-29 01:38:29'),
(4, 4, 6, 'KCL', 1, '50 kg', 130000.00, 130000.00, '2026-06-29 01:49:05', '2026-06-29 01:49:05'),
(5, 5, 6, 'KCL', 1, '50 kg', 130000.00, 130000.00, '2026-06-29 01:49:35', '2026-06-29 01:49:35'),
(6, 6, 2, 'NPK', 1, '50 kg', 160000.00, 160000.00, '2026-06-29 01:49:56', '2026-06-29 01:49:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_panen_padi`
--

CREATE TABLE `hasil_panen_padi` (
  `id` bigint UNSIGNED NOT NULL,
  `id_petani` bigint UNSIGNED NOT NULL,
  `jumlah_kg` decimal(14,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `jenis_bibit` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_panen` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `hasil_panen_padi`
--

INSERT INTO `hasil_panen_padi` (`id`, `id_petani`, `jumlah_kg`, `jenis_bibit`, `tanggal_panen`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 8, 1200.00, 'Ampari', '2026-06-29', '2026-06-29 01:37:14', '2026-06-29 01:37:14', NULL),
(2, 6, 1600.00, 'Ringka\'', '2026-06-29', '2026-06-29 01:40:18', '2026-06-29 01:40:18', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_tanam`
--

CREATE TABLE `jadwal_tanam` (
  `id` bigint UNSIGNED NOT NULL,
  `id_petani` bigint UNSIGNED NOT NULL,
  `tanggal_semai` date NOT NULL,
  `perkiraan_tanggal_tanam` date DEFAULT NULL,
  `perkiraan_tanggal_panen` date DEFAULT NULL,
  `tahap_aktif` enum('pembibitan','penanaman','perawatan_tanaman','panen','selesai') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pembibitan',
  `jumlah_tahap_selesai` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `persentase_progres` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `status` enum('rencana','aktif','selesai','batal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'rencana',
  `dimulai_pada` datetime DEFAULT NULL,
  `diselesaikan_pada` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jadwal_tanam`
--

INSERT INTO `jadwal_tanam` (`id`, `id_petani`, `tanggal_semai`, `perkiraan_tanggal_tanam`, `perkiraan_tanggal_panen`, `tahap_aktif`, `jumlah_tahap_selesai`, `persentase_progres`, `status`, `dimulai_pada`, `diselesaikan_pada`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 8, '2026-06-29', '2026-07-20', '2026-11-12', 'pembibitan', 0, 0, 'rencana', NULL, NULL, '2026-06-29 01:36:40', '2026-06-29 01:36:40', NULL),
(2, 6, '2026-06-29', '2026-07-20', '2026-11-12', 'pembibitan', 0, 0, 'aktif', '2026-06-29 08:38:55', NULL, '2026-06-29 01:38:15', '2026-06-29 01:38:55', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `konten_aplikasi`
--

CREATE TABLE `konten_aplikasi` (
  `id` bigint UNSIGNED NOT NULL,
  `dibuat_oleh` bigint UNSIGNED DEFAULT NULL,
  `kategori` enum('edukasi','hama_penyakit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_konten` enum('artikel','video','panduan','solusi') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'artikel',
  `judul` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tautan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','terbit','arsip') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'terbit',
  `diterbitkan_pada` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `konten_aplikasi`
--

INSERT INTO `konten_aplikasi` (`id`, `dibuat_oleh`, `kategori`, `jenis_konten`, `judul`, `slug`, `deskripsi`, `gambar`, `tautan`, `status`, `diterbitkan_pada`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'edukasi', 'video', 'judul', 'judul', 'hahahhaa', '/assets/edukasi/orang-edukasi.png', 'https://www.youtube.com/watch?v=eRBLJudYeUY', 'terbit', '2026-06-29 08:33:50', '2026-06-29 01:33:50', '2026-06-29 01:33:50', NULL),
(2, 1, 'hama_penyakit', 'video', 'judul', 'judul-2', 'judul', '/assets/hama-penyakit/hama.png', 'https://www.youtube.com/watch?v=EvjZ7ckgYTg', 'terbit', '2026-06-29 08:35:17', '2026-06-29 01:35:17', '2026-06-29 01:35:17', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `lahan_petani`
--

CREATE TABLE `lahan_petani` (
  `id` bigint UNSIGNED NOT NULL,
  `id_petani` bigint UNSIGNED NOT NULL,
  `nama_lahan` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Lahan Padi',
  `nama_pemilik` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `luas_meter` int UNSIGNED NOT NULL DEFAULT '0',
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('aktif','nonaktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `lahan_petani`
--

INSERT INTO `lahan_petani` (`id`, `id_petani`, `nama_lahan`, `nama_pemilik`, `luas_meter`, `alamat`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'Lahan Padi', 'xxx', 900, NULL, 'aktif', '2026-06-27 08:00:57', '2026-06-27 08:02:41', NULL),
(2, 6, 'Lahan Padi', 'manda', 900, 'Sambas', 'aktif', '2026-06-29 01:25:30', '2026-06-29 01:57:04', NULL),
(3, 8, 'Lahan Padi', 'Fakih', 600, NULL, 'aktif', '2026-06-29 01:28:35', '2026-06-29 01:29:41', NULL),
(4, 10, 'Lahan Padi', 'Tobi', 0, NULL, 'aktif', '2026-06-29 06:18:07', '2026-06-29 06:18:07', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `metode_pembayaran`
--

CREATE TABLE `metode_pembayaran` (
  `id` bigint UNSIGNED NOT NULL,
  `konteks` enum('marketplace_pembeli','pupuk_petani') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `metode` enum('tunai','transfer','qris') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_tampilan` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `metode_pembayaran`
--

INSERT INTO `metode_pembayaran` (`id`, `konteks`, `metode`, `nama_tampilan`, `aktif`, `created_at`, `updated_at`) VALUES
(1, 'marketplace_pembeli', 'tunai', 'Tunai', 1, '2026-06-27 05:56:43', '2026-06-27 05:56:43'),
(2, 'marketplace_pembeli', 'transfer', 'Transfer', 0, '2026-06-27 05:56:43', '2026-06-29 01:31:10'),
(3, 'marketplace_pembeli', 'qris', 'QRIS', 0, '2026-06-27 05:56:43', '2026-06-29 01:31:10'),
(4, 'pupuk_petani', 'tunai', 'Tunai', 1, '2026-06-27 05:56:43', '2026-06-29 01:34:17'),
(5, 'pupuk_petani', 'transfer', 'Transfer', 0, '2026-06-27 05:56:43', '2026-06-29 01:31:10'),
(6, 'pupuk_petani', 'qris', 'QRIS', 0, '2026-06-27 05:56:43', '2026-06-29 01:34:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_13_000000_create_poktan_feature_tables', 1),
(5, '2026_06_19_000000_backfill_existing_farmer_data', 1),
(6, '2026_06_19_010000_add_location_name_to_users_table', 1),
(7, '2026_06_21_000000_reconcile_database_with_current_application', 1),
(8, '2026_06_23_000000_change_fertilizer_limit_to_integer', 1),
(9, '2026_06_26_000000_remove_unused_auth_fields', 1),
(10, '2026_06_26_010000_prune_unused_project_fields', 1),
(11, '2026_06_26_020000_adjust_project_field_types', 1),
(13, '2026_06_26_030000_create_rdkk_report_tables_tanpa_mt', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi_aplikasi`
--

CREATE TABLE `notifikasi_aplikasi` (
  `id` bigint UNSIGNED NOT NULL,
  `dibuat_oleh` bigint UNSIGNED DEFAULT NULL,
  `kategori` enum('transaksi','pupuk','cuaca','edukasi','hama_penyakit','sistem') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sistem',
  `target_peran` enum('semua','admin','petani','pembeli','khusus') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'semua',
  `judul` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tautan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_tambahan` json DEFAULT NULL,
  `diterbitkan_pada` datetime DEFAULT NULL,
  `berakhir_pada` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notifikasi_aplikasi`
--

INSERT INTO `notifikasi_aplikasi` (`id`, `dibuat_oleh`, `kategori`, `target_peran`, `judul`, `pesan`, `tautan`, `data_tambahan`, `diterbitkan_pada`, `berakhir_pada`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'Dani ingin membeli 5 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 1}', '2026-06-29 08:30:39', NULL, '2026-06-29 01:30:39', '2026-06-29 01:30:39', NULL),
(2, 4, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 4, \"id_pesanan_marketplace\": 1}', '2026-06-29 08:30:39', NULL, '2026-06-29 01:30:39', '2026-06-29 01:30:39', NULL),
(3, 6, 'transaksi', 'khusus', 'Status pesanan MKT-20260629083039-RBTH', 'Pesanan Anda disetujui.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 4, \"id_pesanan_marketplace\": 1}', '2026-06-29 08:32:01', NULL, '2026-06-29 01:32:01', '2026-06-29 01:32:01', NULL),
(4, 8, 'pupuk', 'admin', 'Pesanan pupuk baru', 'Fakih membuat pesanan PPK-20260629083509-XYX7.', NULL, NULL, '2026-06-29 08:35:09', NULL, '2026-06-29 01:35:09', '2026-06-29 01:35:09', NULL),
(5, 8, 'pupuk', 'admin', 'Pesanan pupuk dibatalkan', 'Fakih membatalkan pesanan PPK-20260629083509-XYX7.', NULL, NULL, '2026-06-29 08:35:20', NULL, '2026-06-29 01:35:20', '2026-06-29 01:35:20', NULL),
(6, 8, 'pupuk', 'admin', 'Pesanan pupuk baru', 'Fakih membuat pesanan PPK-20260629083530-PUFT.', NULL, NULL, '2026-06-29 08:35:30', NULL, '2026-06-29 01:35:30', '2026-06-29 01:35:30', NULL),
(7, 8, 'pupuk', 'admin', 'Pesanan pupuk dibatalkan', 'Fakih membatalkan pesanan PPK-20260629083530-PUFT.', NULL, NULL, '2026-06-29 08:35:49', NULL, '2026-06-29 01:35:49', '2026-06-29 01:35:49', NULL),
(8, 1, 'sistem', 'semua', 'main', 'besok', NULL, NULL, '2026-06-29 08:36:49', NULL, '2026-06-29 01:36:49', '2026-06-29 01:36:49', NULL),
(9, 1, 'hama_penyakit', 'semua', 'a', 'a', NULL, NULL, '2026-06-29 08:37:36', NULL, '2026-06-29 01:37:36', '2026-06-29 01:37:36', NULL),
(10, 8, 'pupuk', 'admin', 'Pesanan pupuk baru', 'Fakih membuat pesanan PPK-20260629083829-FKP7.', NULL, NULL, '2026-06-29 08:38:29', NULL, '2026-06-29 01:38:29', '2026-06-29 01:38:29', NULL),
(11, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 15 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 2}', '2026-06-29 08:41:23', NULL, '2026-06-29 01:41:23', '2026-06-29 01:41:23', NULL),
(12, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 2}', '2026-06-29 08:41:23', NULL, '2026-06-29 01:41:23', '2026-06-29 01:41:23', NULL),
(13, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 1 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 3}', '2026-06-29 08:41:26', NULL, '2026-06-29 01:41:26', '2026-06-29 01:41:26', NULL),
(14, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 3}', '2026-06-29 08:41:26', NULL, '2026-06-29 01:41:26', '2026-06-29 01:41:26', NULL),
(15, 6, 'transaksi', 'khusus', 'Status pesanan MKT-20260629084123-MVPY', 'Pesanan Anda disetujui.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 2}', '2026-06-29 08:41:46', NULL, '2026-06-29 01:41:46', '2026-06-29 01:41:46', NULL),
(16, 7, 'transaksi', 'khusus', 'Pesanan dibatalkan oleh pembeli', 'fakih membatalkan pesanan MKT-20260629084126-KK1G.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 3}', '2026-06-29 08:42:20', NULL, '2026-06-29 01:42:20', '2026-06-29 01:42:20', NULL),
(17, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 4}', '2026-06-29 08:43:33', NULL, '2026-06-29 01:43:33', '2026-06-29 01:43:33', NULL),
(18, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 4}', '2026-06-29 08:43:33', NULL, '2026-06-29 01:43:33', '2026-06-29 01:43:33', NULL),
(19, 6, 'transaksi', 'khusus', 'Status pesanan MKT-20260629084333-FZ4M', 'Pesanan Anda disetujui.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 4}', '2026-06-29 08:44:20', NULL, '2026-06-29 01:44:20', '2026-06-29 01:44:20', NULL),
(20, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ampari', 'fakih ingin membeli 30 Karung.', '/marketplace', '{\"id_pengguna\": 8, \"id_pesanan_marketplace\": 5}', '2026-06-29 08:47:15', NULL, '2026-06-29 01:47:15', '2026-06-29 01:47:15', NULL),
(21, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ampari sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 5}', '2026-06-29 08:47:15', NULL, '2026-06-29 01:47:15', '2026-06-29 01:47:15', NULL),
(22, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ampari', 'fakih ingin membeli 1 Karung.', '/marketplace', '{\"id_pengguna\": 8, \"id_pesanan_marketplace\": 6}', '2026-06-29 08:47:19', NULL, '2026-06-29 01:47:19', '2026-06-29 01:47:19', NULL),
(23, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ampari sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 6}', '2026-06-29 08:47:19', NULL, '2026-06-29 01:47:19', '2026-06-29 01:47:19', NULL),
(24, 6, 'pupuk', 'admin', 'Pesanan pupuk baru', 'manda membuat pesanan PPK-20260629084905-UKH6.', NULL, NULL, '2026-06-29 08:49:05', NULL, '2026-06-29 01:49:05', '2026-06-29 01:49:05', NULL),
(25, 6, 'pupuk', 'admin', 'Pesanan pupuk dibatalkan', 'manda membatalkan pesanan PPK-20260629084905-UKH6.', NULL, NULL, '2026-06-29 08:49:13', NULL, '2026-06-29 01:49:13', '2026-06-29 01:49:13', NULL),
(26, 6, 'pupuk', 'admin', 'Pesanan pupuk baru', 'manda membuat pesanan PPK-20260629084935-ZI2I.', NULL, NULL, '2026-06-29 08:49:35', NULL, '2026-06-29 01:49:35', '2026-06-29 01:49:35', NULL),
(27, 6, 'pupuk', 'admin', 'Pesanan pupuk baru', 'manda membuat pesanan PPK-20260629084956-CFZK.', NULL, NULL, '2026-06-29 08:49:56', NULL, '2026-06-29 01:49:56', '2026-06-29 01:49:56', NULL),
(28, 8, 'transaksi', 'khusus', 'Status pesanan MKT-20260629084718-DC3J', 'Pesanan Anda disetujui.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 6}', '2026-06-29 08:52:23', NULL, '2026-06-29 01:52:23', '2026-06-29 01:52:23', NULL),
(29, 8, 'transaksi', 'khusus', 'Status pesanan MKT-20260629084715-LZ5P', 'Pesanan Anda ditolak.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 5}', '2026-06-29 08:53:00', NULL, '2026-06-29 01:53:00', '2026-06-29 01:53:00', NULL),
(30, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 7}', '2026-06-29 14:19:58', NULL, '2026-06-29 07:19:58', '2026-06-29 07:19:58', NULL),
(31, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 7}', '2026-06-29 14:19:58', NULL, '2026-06-29 07:19:58', '2026-06-29 07:19:58', NULL),
(32, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 8}', '2026-06-29 14:19:58', NULL, '2026-06-29 07:19:58', '2026-06-29 07:19:58', NULL),
(33, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 8}', '2026-06-29 14:19:58', NULL, '2026-06-29 07:19:58', '2026-06-29 07:19:58', NULL),
(34, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 9}', '2026-06-29 14:19:58', NULL, '2026-06-29 07:19:58', '2026-06-29 07:19:58', NULL),
(35, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 9}', '2026-06-29 14:19:58', NULL, '2026-06-29 07:19:58', '2026-06-29 07:19:58', NULL),
(36, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 10}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(37, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 10}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(38, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 11}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(39, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 11}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(40, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 12}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(41, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 12}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(42, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 13}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(43, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 13}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(44, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 14}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(45, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 14}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(46, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 15}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(47, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 15}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(48, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 16}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(49, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 16}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(50, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 17}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(51, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 17}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(52, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 18}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(53, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 18}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(54, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 19}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(55, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 19}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(56, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 20}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(57, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 20}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(58, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 21}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(59, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 21}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(60, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 22}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(61, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 22}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(62, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 23}', '2026-06-29 14:19:59', NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(63, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 23}', '2026-06-29 14:20:00', NULL, '2026-06-29 07:20:00', '2026-06-29 07:20:00', NULL),
(64, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 24}', '2026-06-29 14:20:00', NULL, '2026-06-29 07:20:00', '2026-06-29 07:20:00', NULL),
(65, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 24}', '2026-06-29 14:20:00', NULL, '2026-06-29 07:20:00', '2026-06-29 07:20:00', NULL),
(66, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 25}', '2026-06-29 14:20:00', NULL, '2026-06-29 07:20:00', '2026-06-29 07:20:00', NULL),
(67, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 25}', '2026-06-29 14:20:00', NULL, '2026-06-29 07:20:00', '2026-06-29 07:20:00', NULL),
(68, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 26}', '2026-06-29 14:20:00', NULL, '2026-06-29 07:20:00', '2026-06-29 07:20:00', NULL),
(69, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 26}', '2026-06-29 14:20:00', NULL, '2026-06-29 07:20:00', '2026-06-29 07:20:00', NULL),
(70, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 27}', '2026-06-29 14:20:00', NULL, '2026-06-29 07:20:00', '2026-06-29 07:20:00', NULL),
(71, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 27}', '2026-06-29 14:20:00', NULL, '2026-06-29 07:20:00', '2026-06-29 07:20:00', NULL),
(72, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 28}', '2026-06-29 14:20:02', NULL, '2026-06-29 07:20:02', '2026-06-29 07:20:02', NULL),
(73, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 28}', '2026-06-29 14:20:02', NULL, '2026-06-29 07:20:02', '2026-06-29 07:20:02', NULL),
(74, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 29}', '2026-06-29 14:20:02', NULL, '2026-06-29 07:20:02', '2026-06-29 07:20:02', NULL),
(75, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 29}', '2026-06-29 14:20:02', NULL, '2026-06-29 07:20:02', '2026-06-29 07:20:02', NULL),
(76, 7, 'transaksi', 'khusus', 'Permintaan pembelian Padi ringka\'', 'fakih ingin membeli 2 kg.', '/marketplace', '{\"id_pengguna\": 6, \"id_pesanan_marketplace\": 30}', '2026-06-29 14:20:04', NULL, '2026-06-29 07:20:04', '2026-06-29 07:20:04', NULL),
(77, 7, 'transaksi', 'khusus', 'Pesanan menunggu persetujuan', 'Pesanan Padi ringka\' sedang menunggu persetujuan petani.', '/pembeli/riwayat-belanja', '{\"id_pengguna\": 7, \"id_pesanan_marketplace\": 30}', '2026-06-29 14:20:04', NULL, '2026-06-29 07:20:04', '2026-06-29 07:20:04', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerima_notifikasi`
--

CREATE TABLE `penerima_notifikasi` (
  `id` bigint UNSIGNED NOT NULL,
  `id_notifikasi` bigint UNSIGNED NOT NULL,
  `id_pengguna` bigint UNSIGNED NOT NULL,
  `dibaca_pada` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penerima_notifikasi`
--

INSERT INTO `penerima_notifikasi` (`id`, `id_notifikasi`, `id_pengguna`, `dibaca_pada`, `created_at`, `updated_at`) VALUES
(1, 2, 4, NULL, '2026-06-29 01:30:43', '2026-06-29 01:32:08'),
(2, 1, 6, '2026-06-29 08:31:39', '2026-06-29 01:31:19', '2026-06-29 06:44:13'),
(4, 3, 4, NULL, '2026-06-29 01:32:05', '2026-06-29 01:32:08'),
(9, 8, 6, '2026-06-29 08:37:14', '2026-06-29 01:37:07', '2026-06-29 06:44:13'),
(13, 9, 8, '2026-06-29 08:38:12', '2026-06-29 01:37:46', '2026-06-29 01:52:14'),
(14, 8, 8, '2026-06-29 08:38:12', '2026-06-29 01:37:46', '2026-06-29 01:52:14'),
(17, 13, 6, '2026-06-29 08:41:32', '2026-06-29 01:41:29', '2026-06-29 06:44:13'),
(18, 11, 6, NULL, '2026-06-29 01:41:29', '2026-06-29 06:44:13'),
(19, 9, 6, NULL, '2026-06-29 01:41:29', '2026-06-29 06:44:13'),
(22, 15, 7, NULL, '2026-06-29 01:42:35', '2026-06-29 07:20:24'),
(23, 14, 7, NULL, '2026-06-29 01:42:35', '2026-06-29 07:20:24'),
(24, 12, 7, NULL, '2026-06-29 01:42:35', '2026-06-29 07:20:24'),
(25, 9, 7, NULL, '2026-06-29 01:42:35', '2026-06-29 07:20:24'),
(26, 8, 7, NULL, '2026-06-29 01:42:35', '2026-06-29 07:20:24'),
(27, 19, 7, NULL, '2026-06-29 01:46:25', '2026-06-29 07:20:24'),
(28, 18, 7, NULL, '2026-06-29 01:46:25', '2026-06-29 07:20:24'),
(34, 22, 8, NULL, '2026-06-29 01:52:14', '2026-06-29 01:52:14'),
(35, 20, 8, NULL, '2026-06-29 01:52:14', '2026-06-29 01:52:14'),
(38, 29, 7, NULL, '2026-06-29 01:53:51', '2026-06-29 07:20:24'),
(39, 28, 7, NULL, '2026-06-29 01:53:51', '2026-06-29 07:20:24'),
(40, 23, 7, NULL, '2026-06-29 01:53:51', '2026-06-29 07:20:24'),
(41, 21, 7, NULL, '2026-06-29 01:53:51', '2026-06-29 07:20:24'),
(49, 17, 6, NULL, '2026-06-29 01:54:54', '2026-06-29 06:44:13'),
(50, 16, 6, NULL, '2026-06-29 01:54:54', '2026-06-29 06:44:13'),
(92, 77, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(93, 75, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(94, 73, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(95, 71, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(96, 69, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(97, 67, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(98, 65, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(99, 63, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(100, 61, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(101, 59, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(102, 57, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(103, 55, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(104, 53, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(105, 51, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(106, 49, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(107, 47, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(108, 45, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(109, 43, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(110, 41, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(111, 39, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(112, 37, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(113, 35, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(114, 33, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24'),
(115, 31, 7, NULL, '2026-06-29 07:20:24', '2026-06-29 07:20:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan_aplikasi`
--

CREATE TABLE `pengaturan_aplikasi` (
  `id` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `nama_aplikasi` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'POKTAN Lancang Kuning',
  `lokasi_aplikasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_marketplace` enum('aktif','perawatan','nonaktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `maintenance_aktif` tinyint(1) NOT NULL DEFAULT '0',
  `pesan_maintenance` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengaturan_aplikasi`
--

INSERT INTO `pengaturan_aplikasi` (`id`, `nama_aplikasi`, `lokasi_aplikasi`, `status_marketplace`, `maintenance_aktif`, `pesan_maintenance`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'POKTAN Lancang Kuning', 'Lancang Kuning', 'aktif', 0, 'Aplikasi sedang dalam perawatan. Silakan coba lagi nanti.', 1, '2026-06-27 05:56:43', '2026-06-29 06:27:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan_marketplace`
--

CREATE TABLE `pesanan_marketplace` (
  `id` bigint UNSIGNED NOT NULL,
  `nomor_pesanan` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_pembeli` bigint UNSIGNED NOT NULL,
  `id_penjual` bigint UNSIGNED NOT NULL,
  `nama_pembeli_snapshot` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `metode_pembayaran` enum('tunai','transfer','qris') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tunai',
  `status_pembayaran` enum('menunggu','lunas','gagal','dibatalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `status_pesanan` enum('menunggu','disetujui','ditolak','selesai','dibatalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan_pembeli` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_harga` decimal(15,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `dipesan_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dikonfirmasi_pada` datetime DEFAULT NULL,
  `diselesaikan_pada` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pesanan_marketplace`
--

INSERT INTO `pesanan_marketplace` (`id`, `nomor_pesanan`, `id_pembeli`, `id_penjual`, `nama_pembeli_snapshot`, `metode_pembayaran`, `status_pembayaran`, `status_pesanan`, `catatan_pembeli`, `total_harga`, `dipesan_pada`, `dikonfirmasi_pada`, `diselesaikan_pada`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'MKT-20260629083039-RBTH', 4, 6, 'Dani', 'tunai', 'menunggu', 'disetujui', NULL, 60000.00, '2026-06-29 08:30:39', '2026-06-29 08:32:01', NULL, '2026-06-29 01:30:39', '2026-06-29 01:32:01', NULL),
(2, 'MKT-20260629084123-MVPY', 7, 6, 'fakih', 'tunai', 'menunggu', 'disetujui', NULL, 180000.00, '2026-06-29 08:41:23', '2026-06-29 08:41:46', NULL, '2026-06-29 01:41:23', '2026-06-29 01:41:46', NULL),
(3, 'MKT-20260629084126-KK1G', 7, 6, 'fakih', 'tunai', 'dibatalkan', 'dibatalkan', NULL, 12000.00, '2026-06-29 08:41:26', NULL, NULL, '2026-06-29 01:41:26', '2026-06-29 01:42:20', NULL),
(4, 'MKT-20260629084333-FZ4M', 7, 6, 'fakih', 'tunai', 'menunggu', 'disetujui', 'Saya akan jemput padi anda', 24000.00, '2026-06-29 08:43:33', '2026-06-29 08:44:20', NULL, '2026-06-29 01:43:33', '2026-06-29 01:44:20', NULL),
(5, 'MKT-20260629084715-LZ5P', 7, 8, 'fakih', 'tunai', 'dibatalkan', 'ditolak', NULL, 189000.00, '2026-06-29 08:47:15', '2026-06-29 08:53:00', NULL, '2026-06-29 01:47:15', '2026-06-29 01:53:00', NULL),
(6, 'MKT-20260629084718-DC3J', 7, 8, 'fakih', 'tunai', 'menunggu', 'disetujui', NULL, 6300.00, '2026-06-29 08:47:18', '2026-06-29 08:52:23', NULL, '2026-06-29 01:47:18', '2026-06-29 01:52:23', NULL),
(7, 'MKT-20260629141958-PCTL', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', NULL, 24000.00, '2026-06-29 14:19:58', NULL, NULL, '2026-06-29 07:19:58', '2026-06-29 07:19:58', NULL),
(8, 'MKT-20260629141958-L9L1', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', NULL, 24000.00, '2026-06-29 14:19:58', NULL, NULL, '2026-06-29 07:19:58', '2026-06-29 07:19:58', NULL),
(9, 'MKT-20260629141958-WEHF', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', NULL, 24000.00, '2026-06-29 14:19:58', NULL, NULL, '2026-06-29 07:19:58', '2026-06-29 07:19:58', NULL),
(10, 'MKT-20260629141959-ORZR', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', NULL, 24000.00, '2026-06-29 14:19:59', NULL, NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(11, 'MKT-20260629141959-FZTG', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', NULL, 24000.00, '2026-06-29 14:19:59', NULL, NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(12, 'MKT-20260629141959-ELH7', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:19:59', NULL, NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(13, 'MKT-20260629141959-3KJO', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:19:59', NULL, NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(14, 'MKT-20260629141959-LZP5', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:19:59', NULL, NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(15, 'MKT-20260629141959-M1SH', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:19:59', NULL, NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(16, 'MKT-20260629141959-EF8O', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:19:59', NULL, NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(17, 'MKT-20260629141959-TYZH', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:19:59', NULL, NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(18, 'MKT-20260629141959-JLPH', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:19:59', NULL, NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(19, 'MKT-20260629141959-NDYP', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:19:59', NULL, NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(20, 'MKT-20260629141959-KDCV', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:19:59', NULL, NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(21, 'MKT-20260629141959-0GP5', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:19:59', NULL, NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(22, 'MKT-20260629141959-LSTD', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:19:59', NULL, NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(23, 'MKT-20260629141959-UBRD', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:19:59', NULL, NULL, '2026-06-29 07:19:59', '2026-06-29 07:19:59', NULL),
(24, 'MKT-20260629142000-XGJM', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:20:00', NULL, NULL, '2026-06-29 07:20:00', '2026-06-29 07:20:00', NULL),
(25, 'MKT-20260629142000-6E4E', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:20:00', NULL, NULL, '2026-06-29 07:20:00', '2026-06-29 07:20:00', NULL),
(26, 'MKT-20260629142000-XPAL', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:20:00', NULL, NULL, '2026-06-29 07:20:00', '2026-06-29 07:20:00', NULL),
(27, 'MKT-20260629142000-JLYS', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:20:00', NULL, NULL, '2026-06-29 07:20:00', '2026-06-29 07:20:00', NULL),
(28, 'MKT-20260629142002-YPID', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:20:02', NULL, NULL, '2026-06-29 07:20:02', '2026-06-29 07:20:02', NULL),
(29, 'MKT-20260629142002-NRMW', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:20:02', NULL, NULL, '2026-06-29 07:20:02', '2026-06-29 07:20:02', NULL),
(30, 'MKT-20260629142004-XCEJ', 7, 6, 'fakih', 'tunai', 'menunggu', 'menunggu', 'ambek sorang digudang.', 24000.00, '2026-06-29 14:20:04', NULL, NULL, '2026-06-29 07:20:04', '2026-06-29 07:20:04', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan_pupuk`
--

CREATE TABLE `pesanan_pupuk` (
  `id` bigint UNSIGNED NOT NULL,
  `nomor_pesanan` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_petani` bigint UNSIGNED NOT NULL,
  `metode_pembayaran` enum('tunai','transfer','qris') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tunai',
  `status_pembayaran` enum('menunggu','lunas','gagal','dibatalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `status_pesanan` enum('menunggu','diterima','ditolak','selesai','dibatalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `total_harga` decimal(15,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `dipesan_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dikonfirmasi_pada` datetime DEFAULT NULL,
  `diselesaikan_pada` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pesanan_pupuk`
--

INSERT INTO `pesanan_pupuk` (`id`, `nomor_pesanan`, `id_petani`, `metode_pembayaran`, `status_pembayaran`, `status_pesanan`, `total_harga`, `dipesan_pada`, `dikonfirmasi_pada`, `diselesaikan_pada`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'PPK-20260629083509-XYX7', 8, 'tunai', 'dibatalkan', 'dibatalkan', 130000.00, '2026-06-29 08:35:09', NULL, NULL, '2026-06-29 01:35:09', '2026-06-29 01:35:20', NULL),
(2, 'PPK-20260629083530-PUFT', 8, 'tunai', 'dibatalkan', 'dibatalkan', 130000.00, '2026-06-29 08:35:30', NULL, NULL, '2026-06-29 01:35:30', '2026-06-29 01:35:49', NULL),
(3, 'PPK-20260629083829-FKP7', 8, 'tunai', 'menunggu', 'diterima', 130000.00, '2026-06-29 08:38:29', '2026-06-29 08:49:37', NULL, '2026-06-29 01:38:29', '2026-06-29 01:49:37', NULL),
(4, 'PPK-20260629084905-UKH6', 6, 'tunai', 'dibatalkan', 'dibatalkan', 130000.00, '2026-06-29 08:49:05', NULL, NULL, '2026-06-29 01:49:05', '2026-06-29 01:49:13', NULL),
(5, 'PPK-20260629084935-ZI2I', 6, 'tunai', 'dibatalkan', 'dibatalkan', 130000.00, '2026-06-29 08:49:35', '2026-06-29 08:50:05', NULL, '2026-06-29 01:49:35', '2026-06-29 01:53:17', NULL),
(6, 'PPK-20260629084956-CFZK', 6, 'tunai', 'lunas', 'selesai', 160000.00, '2026-06-29 08:49:56', '2026-06-29 08:50:03', '2026-06-29 08:53:13', '2026-06-29 01:49:56', '2026-06-29 01:53:13', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk_marketplace`
--

CREATE TABLE `produk_marketplace` (
  `id` bigint UNSIGNED NOT NULL,
  `id_penjual` bigint UNSIGNED NOT NULL,
  `nama_produk` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `alamat_produk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `harga` decimal(15,2) UNSIGNED DEFAULT NULL,
  `jumlah_stok` int UNSIGNED NOT NULL DEFAULT '0',
  `satuan` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kg',
  `gambar_produk` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `produk_marketplace`
--

INSERT INTO `produk_marketplace` (`id`, `id_penjual`, `nama_produk`, `deskripsi`, `alamat_produk`, `harga`, `jumlah_stok`, `satuan`, `gambar_produk`, `aktif`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 6, 'Padi ringka\'', 'Padi ini sangat lembut dan enak', 'Semparuk,surabaye', 12000.00, 58, 'kg', '/storage/poktan/marketplace/Uq6HurWxG0138Ya24QSm22Hsm5iqQw0zzGKgI4gx.jpg', 1, '2026-06-29 01:28:35', '2026-06-29 01:44:20', NULL),
(2, 8, 'Padi ampari', 'Jual padi', 'Surabaya kec semparuk', 6300.00, 29, 'Karung', '/storage/poktan/marketplace/MOLSFNws5wCY4rEA9QnSJiTjyoCXcIKrpUaL93u9.jpg', 1, '2026-06-29 01:33:45', '2026-06-29 01:52:23', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk_pupuk`
--

CREATE TABLE `produk_pupuk` (
  `id` bigint UNSIGNED NOT NULL,
  `dibuat_oleh` bigint UNSIGNED DEFAULT NULL,
  `nama_produk` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ukuran_kemasan` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `harga` decimal(15,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `jumlah_stok` int UNSIGNED NOT NULL DEFAULT '0',
  `gambar_produk` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `produk_pupuk`
--

INSERT INTO `produk_pupuk` (`id`, `dibuat_oleh`, `nama_produk`, `slug`, `deskripsi`, `ukuran_kemasan`, `harga`, `jumlah_stok`, `gambar_produk`, `aktif`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'Urea', 'urea', 'Pupuk nitrogen untuk pertumbuhan daun dan batang padi.', '50 kg', 120000.00, 8, '/assets/pupuk/tas_pupuk_urea_dengan_granula.png', 1, '2026-06-27 05:56:43', '2026-06-29 01:38:38', NULL),
(2, NULL, 'NPK', 'npk', 'Pupuk majemuk untuk mendukung pertumbuhan tanaman padi.', '50 kg', 160000.00, 8, '/assets/pupuk/pupuk_NPK.png', 1, '2026-06-27 05:56:43', '2026-06-29 01:38:38', NULL),
(3, NULL, 'NPK Formula', 'npk-formula', 'Pupuk NPK formula khusus untuk kebutuhan RDKK.', '50 kg', 160000.00, 8, '/assets/pupuk/pupuk_majemuk_npk_16_16_16.png', 1, '2026-06-27 05:56:43', '2026-06-29 01:38:38', NULL),
(4, NULL, 'Pupuk Organik', 'pupuk-organik', 'Pupuk organik untuk memperbaiki struktur dan kesuburan tanah.', '25 kg', 85000.00, 8, '/assets/pupuk/pupuk_organik_dengan_tanah_kompos.png', 1, '2026-06-27 05:56:43', '2026-06-29 01:38:38', NULL),
(5, NULL, 'ZA', 'za', 'Pupuk ZA sebagai sumber nitrogen dan sulfur untuk tanaman.', '50 kg', 110000.00, 8, '/assets/pupuk/pupuk_KCL.png', 1, '2026-06-27 05:56:43', '2026-06-29 01:38:38', NULL),
(6, NULL, 'KCL', 'kcl', 'Pupuk kalium untuk meningkatkan kualitas hasil panen.', '50 kg', 130000.00, 8, '/assets/pupuk/tas_pupuk_kcl_dengan_granula.png', 1, '2026-06-27 05:56:43', '2026-06-29 01:38:38', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `profil_pembeli`
--

CREATE TABLE `profil_pembeli` (
  `id` bigint UNSIGNED NOT NULL,
  `id_pengguna` bigint UNSIGNED NOT NULL,
  `nama_gudang` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_gudang` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `profil_pembeli`
--

INSERT INTO `profil_pembeli` (`id`, `id_pengguna`, `nama_gudang`, `alamat_gudang`, `created_at`, `updated_at`) VALUES
(1, 3, 'Gudang fakih', NULL, '2026-06-27 08:01:33', '2026-06-27 08:01:33'),
(2, 4, 'Dani', 'Jawai Selatan', '2026-06-29 01:23:32', '2026-06-29 01:26:16'),
(3, 5, 'Gudang manda', NULL, '2026-06-29 01:23:50', '2026-06-29 01:23:50'),
(4, 7, 'Mitra Jaya', NULL, '2026-06-29 01:26:27', '2026-06-29 01:26:27'),
(5, 9, 'Gudang Tayo 123', NULL, '2026-06-29 01:59:41', '2026-06-29 01:59:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `profil_petani`
--

CREATE TABLE `profil_petani` (
  `id` bigint UNSIGNED NOT NULL,
  `id_pengguna` bigint UNSIGNED NOT NULL,
  `luas_lahan_meter` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `profil_petani`
--

INSERT INTO `profil_petani` (`id`, `id_pengguna`, `luas_lahan_meter`, `created_at`, `updated_at`) VALUES
(1, 2, 900, '2026-06-27 08:00:57', '2026-06-27 08:02:41'),
(2, 6, 900, '2026-06-29 01:25:30', '2026-06-29 01:26:00'),
(3, 8, 600, '2026-06-29 01:28:35', '2026-06-29 01:29:41'),
(4, 10, 0, '2026-06-29 06:18:07', '2026-06-29 06:18:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `progres_tahap_tanam`
--

CREATE TABLE `progres_tahap_tanam` (
  `id` bigint UNSIGNED NOT NULL,
  `id_jadwal_tanam` bigint UNSIGNED NOT NULL,
  `urutan` tinyint UNSIGNED NOT NULL,
  `nama_tahap` enum('pembibitan','penanaman','perawatan_tanaman','panen') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rentang_hari` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_mulai_target` date DEFAULT NULL,
  `tanggal_selesai_target` date DEFAULT NULL,
  `tanggal_mulai_aktual` date DEFAULT NULL,
  `tanggal_selesai_aktual` date DEFAULT NULL,
  `status` enum('menunggu','aktif','selesai','dilewati') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `progres_tahap_tanam`
--

INSERT INTO `progres_tahap_tanam` (`id`, `id_jadwal_tanam`, `urutan`, `nama_tahap`, `rentang_hari`, `tanggal_mulai_target`, `tanggal_selesai_target`, `tanggal_mulai_aktual`, `tanggal_selesai_aktual`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'pembibitan', '0-21 Hari', '2026-06-29', '2026-07-20', NULL, NULL, 'menunggu', '2026-06-29 01:36:40', '2026-06-29 01:36:40'),
(2, 1, 2, 'penanaman', '15-21 Hari Setelah Semai', '2026-07-14', '2026-07-20', NULL, NULL, 'menunggu', '2026-06-29 01:36:40', '2026-06-29 01:36:40'),
(3, 1, 3, 'perawatan_tanaman', '0-90 Hari Setelah Tanam', '2026-07-20', '2026-10-18', NULL, NULL, 'menunggu', '2026-06-29 01:36:40', '2026-06-29 01:36:40'),
(4, 1, 4, 'panen', '100-115 Hari Setelah Tanam', '2026-10-28', '2026-11-12', NULL, NULL, 'menunggu', '2026-06-29 01:36:40', '2026-06-29 01:36:40'),
(5, 2, 1, 'pembibitan', '0-21 Hari', '2026-06-29', '2026-07-20', '2026-06-29', NULL, 'aktif', '2026-06-29 01:38:15', '2026-06-29 01:38:55'),
(6, 2, 2, 'penanaman', '15-21 Hari Setelah Semai', '2026-07-14', '2026-07-20', NULL, NULL, 'menunggu', '2026-06-29 01:38:15', '2026-06-29 01:38:55'),
(7, 2, 3, 'perawatan_tanaman', '0-90 Hari Setelah Tanam', '2026-07-20', '2026-10-18', NULL, NULL, 'menunggu', '2026-06-29 01:38:15', '2026-06-29 01:38:55'),
(8, 2, 4, 'panen', '100-115 Hari Setelah Tanam', '2026-10-28', '2026-11-12', NULL, NULL, 'menunggu', '2026-06-29 01:38:15', '2026-06-29 01:38:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rdkk_detail_laporan`
--

CREATE TABLE `rdkk_detail_laporan` (
  `id` bigint UNSIGNED NOT NULL,
  `id_rdkk_laporan` bigint UNSIGNED NOT NULL,
  `id_petani` bigint UNSIGNED DEFAULT NULL,
  `nik` char(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_petani` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rencana_tanam_ha` decimal(8,3) UNSIGNED NOT NULL DEFAULT '0.000',
  `urea_jumlah` int UNSIGNED NOT NULL DEFAULT '0',
  `npk_jumlah` int UNSIGNED NOT NULL DEFAULT '0',
  `npk_formula_jumlah` int UNSIGNED NOT NULL DEFAULT '0',
  `organik_jumlah` int UNSIGNED NOT NULL DEFAULT '0',
  `za_jumlah` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rdkk_laporan`
--

CREATE TABLE `rdkk_laporan` (
  `id` bigint UNSIGNED NOT NULL,
  `tahun` smallint UNSIGNED NOT NULL,
  `kecamatan` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `desa_kelurahan` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelompok_tani` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subsektor` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Tanaman Pangan',
  `komoditas` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Padi',
  `kios` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dicetak_oleh` bigint UNSIGNED DEFAULT NULL,
  `dicetak_pada` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7nNa6AHFL952fnG2W9N3ndJLV1TGZ6PMZbY0VM5g', 8, '192.168.100.9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'eyJfdG9rZW4iOiIyR2E0ZkpSR3BmOHBDZVZKNFZjclVrTWlWZUxEYUJsZFB5S05ZY2NIIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE5Mi4xNjguMTAwLjEyNjo4MDAwXC9hcGlcL3BlbmdhdHVyYW4iLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjh9', 1782717773),
('CJEBNVzziyfS9LOB7LgZTRzC5NAzFaSnGoZi90uz', 10, '192.168.100.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJvcEJQOGd5SndFRkZobktvU2dET3E1OGdNOTZWcmlsbkE1VlZkb0d2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE5Mi4xNjguMTAwLjEyNjo4MDAwXC9hcGlcL3BlbmdhdHVyYW4iLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEwfQ==', 1782714276),
('EtvQi5b9CUfICMmkdTWd0S7XUZxDVbvA9Yq5KjOH', 7, '192.168.100.221', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJxcHI1dDM3MW9zeUREcHJHVlVYV0FidGF3aFE3TGhGRzhwSXFmQjQyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE5Mi4xNjguMTAwLjEyNjo4MDAwXC9hcGlcL3BlbmdhdHVyYW4iLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjd9', 1782717948),
('MdVFh9r41xOtyZNEe2waE2dIGavQR7GIDFliM5TF', 10, '192.168.100.227', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJCRnZNMlFNNjBOdzkwZ2E2M1cyNnB1WWhwb2JnUnp3UmFjRHQwcHNHIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE5Mi4xNjguMTAwLjEyNjo4MDAwXC9hcGlcL3BlbmdhdHVyYW4iLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEwfQ==', 1782714455),
('NUI7ngAd2zudO0MYXxw2Zg1yDB19j45jSiBwjGJz', 6, '192.168.100.183', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI3dWFSYkJxZGQxZ3dldThmYlUzVHRNcU1md1dDb2pkVjM0QUZ1UmlTIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE5Mi4xNjguMTAwLjEyNjo4MDAwXC9hcGlcL21hcmtldHBsYWNlLXBlc2FuYW4iLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjZ9', 1782715481),
('pWUrNqQ2ool3Gg5VPbhWialsFf5aNj2r1Ud9K8e6', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJWWFpWeXRXM0dpSWM5U1NTUm5mT0N5RTlJUlJJZkRQWVUxNFJFMjhyIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FwaVwvYWRtaW5cL2Jvb3RzdHJhcCIsInJvdXRlIjpudWxsfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1782717299),
('RZQfTAXQriOShflEUta1St0IhzjVyg3qDRqFJjAp', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.126.0 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36', 'eyJfdG9rZW4iOiJoYTU0ekdmOHJCUFFuNGpkcEZSTWt2TnNKeXg2OFlzNU9yQjVVT1pVIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1782712616),
('zQmKUBOE1uD0wpp0w603isYPA4a87m4QCVM4cHrN', 7, '192.168.100.237', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJ0akRrU3ExdERBR2QwQmI5MlZwWGJEam9kdnF6b3FEOGNuemt5bE5hIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE5Mi4xNjguMTAwLjEyNjo4MDAwXC9hcGlcL3BlbmdhdHVyYW4iLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjd9', 1782716743);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_hp` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` char(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nama_lokasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_profil` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `peran` enum('admin','petani','pembeli') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'petani',
  `status` enum('aktif','menunggu','nonaktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_updated_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `nomor_hp`, `nik`, `alamat`, `nama_lokasi`, `foto_profil`, `peran`, `status`, `latitude`, `longitude`, `password`, `password_updated_at`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin Lancang Kuning', 'admin', NULL, NULL, NULL, NULL, NULL, 'admin', 'aktif', NULL, NULL, '$2y$12$q75NMc3zdzw3b9CkEkQow.7VYPaGNNzv9T8UDUQCgjgcUc4qJx7Mq', '2026-06-27 12:56:43', NULL, '2026-06-27 05:56:43', '2026-06-27 05:56:43', NULL),
(2, 'xxx', '1234567891101234', '08123456789', '1234567891101234', NULL, 'Tanjung Mekar', NULL, 'petani', 'aktif', 1.3803577, 109.3197120, '$2y$12$NJuETJ3rOTctGWR6TMRDLeyBh/IT1MLsPZnMK82H9I5hBtB8r9Caa', '2026-06-27 15:00:57', NULL, '2026-06-27 08:00:57', '2026-06-29 07:03:31', NULL),
(3, 'fakih', '08122355135', '08122355135', NULL, NULL, NULL, NULL, 'pembeli', 'aktif', NULL, NULL, '$2y$12$eFHihFi9F8tSAKTHwE31WOxN35SPhnf7bnJSgcHwyHLHVRhGlcdoi', '2026-06-27 15:01:33', NULL, '2026-06-27 08:01:33', '2026-06-27 08:01:33', NULL),
(4, 'Dani', '0833445566', '0833445566', NULL, 'Jawai Selatan', NULL, '/storage/poktan/profile/8uSDVlcbzlwlvpCMwycCYt3hknieGm1RBSDRTYxR.jpg', 'pembeli', 'aktif', NULL, NULL, '$2y$12$JoGmvbz9fET8lDJtxUQ5..0lCRab8QNgm1bcF0Qzmh3KQCXS9XlC6', '2026-06-29 08:23:31', NULL, '2026-06-29 01:23:32', '2026-06-29 01:26:16', NULL),
(5, 'manda', '081212121212', '081212121212', NULL, NULL, NULL, NULL, 'pembeli', 'aktif', NULL, NULL, '$2y$12$KZdRRp1bTnpwmnVS98cuy./4k6MLiYoxAK25228BJx4bVwpDW3fKi', '2026-06-29 08:23:49', NULL, '2026-06-29 01:23:50', '2026-06-29 01:23:50', NULL),
(6, 'manda', '6101010106434696', '081212121213', '6101010106434696', 'Sambas', NULL, '/storage/poktan/profile/HYxPOiDvo6g9ye0v8OVknTsvQGdT48ASc4QNKXeX.jpg', 'petani', 'aktif', NULL, NULL, '$2y$12$cGPwNBCjSncWIA3RthpRqeK5MmDtLRp7Rg93wbuo0PmAyrafWR8Oe', '2026-06-29 08:25:29', NULL, '2026-06-29 01:25:30', '2026-06-29 01:57:22', NULL),
(7, 'fakih', '082233445566', '082233445566', NULL, NULL, NULL, NULL, 'pembeli', 'aktif', NULL, NULL, '$2y$12$K4cLor0xOAuPDqI328tNXOR/SYpkseF56Xx.kjY5rv3z78K6o6skC', '2026-06-29 08:26:26', NULL, '2026-06-29 01:26:27', '2026-06-29 01:26:27', NULL),
(8, 'Fakih', '0612121212121212', '081345678910', '0612121212121212', NULL, NULL, NULL, 'petani', 'aktif', NULL, NULL, '$2y$12$KVeFEWbECqM7PTRQ3VIjGOUEYnzGCw19lNamSatIc3fgaO1TmCAfO', '2026-06-29 08:28:34', NULL, '2026-06-29 01:28:35', '2026-06-29 01:29:41', NULL),
(9, 'Tayo 123', '082454573268', '082454573268', NULL, NULL, NULL, NULL, 'pembeli', 'aktif', NULL, NULL, '$2y$12$ULvqA33VHfDxAi32TEMlE.auRBXPj2/KzaC044GvDDxHTYpn8NoCu', '2026-06-29 08:59:40', NULL, '2026-06-29 01:59:41', '2026-06-29 01:59:41', NULL),
(10, 'Tobi', '9024598694282498', '08476427109', '9024598694282498', NULL, NULL, NULL, 'petani', 'aktif', NULL, NULL, '$2y$12$KBwW/JUClm4qhDf6FCFSb.cCmpeGF5Lj1QcEDi6ACOMkgLZm7VgNO', '2026-06-29 13:19:41', NULL, '2026-06-29 06:18:07', '2026-06-29 06:19:42', NULL);

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `batas_pupuk_petani`
--
ALTER TABLE `batas_pupuk_petani`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `batas_pupuk_petani_id_petani_id_produk_pupuk_unique` (`id_petani`,`id_produk_pupuk`),
  ADD KEY `batas_pupuk_petani_id_produk_pupuk_foreign` (`id_produk_pupuk`),
  ADD KEY `batas_pupuk_petani_aktif_index` (`aktif`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `detail_pesanan_marketplace`
--
ALTER TABLE `detail_pesanan_marketplace`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_pesanan_marketplace_id_pesanan_marketplace_foreign` (`id_pesanan_marketplace`),
  ADD KEY `detail_pesanan_marketplace_id_produk_marketplace_foreign` (`id_produk_marketplace`);

--
-- Indeks untuk tabel `detail_pesanan_pupuk`
--
ALTER TABLE `detail_pesanan_pupuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_pesanan_pupuk_id_pesanan_pupuk_foreign` (`id_pesanan_pupuk`),
  ADD KEY `detail_pesanan_pupuk_id_produk_pupuk_foreign` (`id_produk_pupuk`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `hasil_panen_padi`
--
ALTER TABLE `hasil_panen_padi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hasil_panen_padi_id_petani_tanggal_panen_index` (`id_petani`,`tanggal_panen`);

--
-- Indeks untuk tabel `jadwal_tanam`
--
ALTER TABLE `jadwal_tanam`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_tanam_id_petani_status_index` (`id_petani`,`status`),
  ADD KEY `jadwal_tanam_id_petani_created_at_index` (`id_petani`,`created_at`),
  ADD KEY `jadwal_tanam_tanggal_semai_index` (`tanggal_semai`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `konten_aplikasi`
--
ALTER TABLE `konten_aplikasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `konten_aplikasi_slug_unique` (`slug`),
  ADD KEY `konten_aplikasi_dibuat_oleh_foreign` (`dibuat_oleh`),
  ADD KEY `konten_aplikasi_kategori_status_diterbitkan_pada_index` (`kategori`,`status`,`diterbitkan_pada`);

--
-- Indeks untuk tabel `lahan_petani`
--
ALTER TABLE `lahan_petani`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lahan_petani_id_petani_status_index` (`id_petani`,`status`);

--
-- Indeks untuk tabel `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `metode_pembayaran_konteks_metode_unique` (`konteks`,`metode`),
  ADD KEY `metode_pembayaran_aktif_index` (`aktif`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifikasi_aplikasi`
--
ALTER TABLE `notifikasi_aplikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifikasi_aplikasi_dibuat_oleh_foreign` (`dibuat_oleh`),
  ADD KEY `notifikasi_aplikasi_kategori_diterbitkan_pada_index` (`kategori`,`diterbitkan_pada`),
  ADD KEY `notifikasi_aplikasi_target_peran_diterbitkan_pada_index` (`target_peran`,`diterbitkan_pada`);

--
-- Indeks untuk tabel `penerima_notifikasi`
--
ALTER TABLE `penerima_notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `penerima_notifikasi_id_notifikasi_id_pengguna_unique` (`id_notifikasi`,`id_pengguna`),
  ADD KEY `penerima_notifikasi_id_pengguna_dibaca_pada_index` (`id_pengguna`,`dibaca_pada`);

--
-- Indeks untuk tabel `pengaturan_aplikasi`
--
ALTER TABLE `pengaturan_aplikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengaturan_aplikasi_updated_by_foreign` (`updated_by`);

--
-- Indeks untuk tabel `pesanan_marketplace`
--
ALTER TABLE `pesanan_marketplace`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pesanan_marketplace_nomor_pesanan_unique` (`nomor_pesanan`),
  ADD KEY `pesanan_marketplace_id_pembeli_status_pesanan_index` (`id_pembeli`,`status_pesanan`),
  ADD KEY `pesanan_marketplace_id_penjual_status_pesanan_index` (`id_penjual`,`status_pesanan`),
  ADD KEY `pesanan_marketplace_id_pembeli_dipesan_pada_index` (`id_pembeli`,`dipesan_pada`),
  ADD KEY `pesanan_marketplace_id_penjual_dipesan_pada_index` (`id_penjual`,`dipesan_pada`),
  ADD KEY `pesanan_marketplace_dipesan_pada_index` (`dipesan_pada`);

--
-- Indeks untuk tabel `pesanan_pupuk`
--
ALTER TABLE `pesanan_pupuk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pesanan_pupuk_nomor_pesanan_unique` (`nomor_pesanan`),
  ADD KEY `pesanan_pupuk_id_petani_status_pesanan_index` (`id_petani`,`status_pesanan`),
  ADD KEY `pesanan_pupuk_id_petani_dipesan_pada_index` (`id_petani`,`dipesan_pada`),
  ADD KEY `pesanan_pupuk_dipesan_pada_index` (`dipesan_pada`);

--
-- Indeks untuk tabel `produk_marketplace`
--
ALTER TABLE `produk_marketplace`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produk_marketplace_id_penjual_aktif_created_at_index` (`id_penjual`,`aktif`,`created_at`),
  ADD KEY `produk_marketplace_aktif_created_at_index` (`aktif`,`created_at`),
  ADD KEY `produk_marketplace_nama_produk_index` (`nama_produk`);

--
-- Indeks untuk tabel `produk_pupuk`
--
ALTER TABLE `produk_pupuk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `produk_pupuk_slug_unique` (`slug`),
  ADD KEY `produk_pupuk_dibuat_oleh_foreign` (`dibuat_oleh`),
  ADD KEY `produk_pupuk_aktif_nama_produk_index` (`aktif`,`nama_produk`);

--
-- Indeks untuk tabel `profil_pembeli`
--
ALTER TABLE `profil_pembeli`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `profil_pembeli_id_pengguna_unique` (`id_pengguna`),
  ADD KEY `profil_pembeli_nama_gudang_index` (`nama_gudang`);

--
-- Indeks untuk tabel `profil_petani`
--
ALTER TABLE `profil_petani`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `profil_petani_id_pengguna_unique` (`id_pengguna`);

--
-- Indeks untuk tabel `progres_tahap_tanam`
--
ALTER TABLE `progres_tahap_tanam`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `progres_tahap_tanam_id_jadwal_tanam_urutan_unique` (`id_jadwal_tanam`,`urutan`),
  ADD KEY `progres_tahap_tanam_status_index` (`status`);

--
-- Indeks untuk tabel `rdkk_detail_laporan`
--
ALTER TABLE `rdkk_detail_laporan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rdkk_detail_laporan_id_rdkk_laporan_id_petani_unique` (`id_rdkk_laporan`,`id_petani`),
  ADD KEY `rdkk_detail_laporan_id_petani_foreign` (`id_petani`),
  ADD KEY `rdkk_detail_laporan_nik_index` (`nik`);

--
-- Indeks untuk tabel `rdkk_laporan`
--
ALTER TABLE `rdkk_laporan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rdkk_laporan_dicetak_oleh_foreign` (`dicetak_oleh`),
  ADD KEY `rdkk_laporan_tahun_index` (`tahun`),
  ADD KEY `rdkk_laporan_kelompok_tani_index` (`kelompok_tani`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_nomor_hp_unique` (`nomor_hp`),
  ADD UNIQUE KEY `users_nik_unique` (`nik`),
  ADD KEY `users_name_index` (`name`),
  ADD KEY `users_peran_status_index` (`peran`,`status`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `batas_pupuk_petani`
--
ALTER TABLE `batas_pupuk_petani`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `detail_pesanan_marketplace`
--
ALTER TABLE `detail_pesanan_marketplace`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `detail_pesanan_pupuk`
--
ALTER TABLE `detail_pesanan_pupuk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `hasil_panen_padi`
--
ALTER TABLE `hasil_panen_padi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `jadwal_tanam`
--
ALTER TABLE `jadwal_tanam`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `konten_aplikasi`
--
ALTER TABLE `konten_aplikasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `lahan_petani`
--
ALTER TABLE `lahan_petani`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `notifikasi_aplikasi`
--
ALTER TABLE `notifikasi_aplikasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT untuk tabel `penerima_notifikasi`
--
ALTER TABLE `penerima_notifikasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT untuk tabel `pesanan_marketplace`
--
ALTER TABLE `pesanan_marketplace`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `pesanan_pupuk`
--
ALTER TABLE `pesanan_pupuk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `produk_marketplace`
--
ALTER TABLE `produk_marketplace`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `produk_pupuk`
--
ALTER TABLE `produk_pupuk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `profil_pembeli`
--
ALTER TABLE `profil_pembeli`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `profil_petani`
--
ALTER TABLE `profil_petani`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `progres_tahap_tanam`
--
ALTER TABLE `progres_tahap_tanam`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `rdkk_detail_laporan`
--
ALTER TABLE `rdkk_detail_laporan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rdkk_laporan`
--
ALTER TABLE `rdkk_laporan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `batas_pupuk_petani`
--
ALTER TABLE `batas_pupuk_petani`
  ADD CONSTRAINT `batas_pupuk_petani_id_petani_foreign` FOREIGN KEY (`id_petani`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `batas_pupuk_petani_id_produk_pupuk_foreign` FOREIGN KEY (`id_produk_pupuk`) REFERENCES `produk_pupuk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_pesanan_marketplace`
--
ALTER TABLE `detail_pesanan_marketplace`
  ADD CONSTRAINT `detail_pesanan_marketplace_id_pesanan_marketplace_foreign` FOREIGN KEY (`id_pesanan_marketplace`) REFERENCES `pesanan_marketplace` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pesanan_marketplace_id_produk_marketplace_foreign` FOREIGN KEY (`id_produk_marketplace`) REFERENCES `produk_marketplace` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_pesanan_pupuk`
--
ALTER TABLE `detail_pesanan_pupuk`
  ADD CONSTRAINT `detail_pesanan_pupuk_id_pesanan_pupuk_foreign` FOREIGN KEY (`id_pesanan_pupuk`) REFERENCES `pesanan_pupuk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pesanan_pupuk_id_produk_pupuk_foreign` FOREIGN KEY (`id_produk_pupuk`) REFERENCES `produk_pupuk` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `hasil_panen_padi`
--
ALTER TABLE `hasil_panen_padi`
  ADD CONSTRAINT `hasil_panen_padi_id_petani_foreign` FOREIGN KEY (`id_petani`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwal_tanam`
--
ALTER TABLE `jadwal_tanam`
  ADD CONSTRAINT `jadwal_tanam_id_petani_foreign` FOREIGN KEY (`id_petani`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `konten_aplikasi`
--
ALTER TABLE `konten_aplikasi`
  ADD CONSTRAINT `konten_aplikasi_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `lahan_petani`
--
ALTER TABLE `lahan_petani`
  ADD CONSTRAINT `lahan_petani_id_petani_foreign` FOREIGN KEY (`id_petani`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifikasi_aplikasi`
--
ALTER TABLE `notifikasi_aplikasi`
  ADD CONSTRAINT `notifikasi_aplikasi_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penerima_notifikasi`
--
ALTER TABLE `penerima_notifikasi`
  ADD CONSTRAINT `penerima_notifikasi_id_notifikasi_foreign` FOREIGN KEY (`id_notifikasi`) REFERENCES `notifikasi_aplikasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penerima_notifikasi_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengaturan_aplikasi`
--
ALTER TABLE `pengaturan_aplikasi`
  ADD CONSTRAINT `pengaturan_aplikasi_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pesanan_marketplace`
--
ALTER TABLE `pesanan_marketplace`
  ADD CONSTRAINT `pesanan_marketplace_id_pembeli_foreign` FOREIGN KEY (`id_pembeli`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `pesanan_marketplace_id_penjual_foreign` FOREIGN KEY (`id_penjual`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pesanan_pupuk`
--
ALTER TABLE `pesanan_pupuk`
  ADD CONSTRAINT `pesanan_pupuk_id_petani_foreign` FOREIGN KEY (`id_petani`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `produk_marketplace`
--
ALTER TABLE `produk_marketplace`
  ADD CONSTRAINT `produk_marketplace_id_penjual_foreign` FOREIGN KEY (`id_penjual`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `produk_pupuk`
--
ALTER TABLE `produk_pupuk`
  ADD CONSTRAINT `produk_pupuk_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `profil_pembeli`
--
ALTER TABLE `profil_pembeli`
  ADD CONSTRAINT `profil_pembeli_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `profil_petani`
--
ALTER TABLE `profil_petani`
  ADD CONSTRAINT `profil_petani_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `progres_tahap_tanam`
--
ALTER TABLE `progres_tahap_tanam`
  ADD CONSTRAINT `progres_tahap_tanam_id_jadwal_tanam_foreign` FOREIGN KEY (`id_jadwal_tanam`) REFERENCES `jadwal_tanam` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rdkk_detail_laporan`
--
ALTER TABLE `rdkk_detail_laporan`
  ADD CONSTRAINT `rdkk_detail_laporan_id_petani_foreign` FOREIGN KEY (`id_petani`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `rdkk_detail_laporan_id_rdkk_laporan_foreign` FOREIGN KEY (`id_rdkk_laporan`) REFERENCES `rdkk_laporan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rdkk_laporan`
--
ALTER TABLE `rdkk_laporan`
  ADD CONSTRAINT `rdkk_laporan_dicetak_oleh_foreign` FOREIGN KEY (`dicetak_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
