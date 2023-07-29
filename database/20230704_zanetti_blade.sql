-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Lug 04, 2023 alle 17:14
-- Versione del server: 10.4.27-MariaDB
-- Versione PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zanetti`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `district` varchar(2) NOT NULL,
  `pics` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `cities`
--

INSERT INTO `cities` (`id`, `name`, `district`, `pics`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Lake Nelsport', 'BG', '1', 3, 1, '2023-07-04 13:07:18', '2023-07-04 13:07:18'),
(2, 'South Carlottaview', 'FI', '1', 3, 1, '2023-07-04 13:07:18', '2023-07-04 13:07:18'),
(3, 'Port Angelinefurt', 'BG', '1', 3, 1, '2023-07-04 13:07:19', '2023-07-04 13:07:19'),
(4, 'Willmouth', 'FI', '1', 3, 1, '2023-07-04 13:07:19', '2023-07-04 13:07:19'),
(5, 'Altenwerthhaven', 'NA', '1', 3, 1, '2023-07-04 13:07:19', '2023-07-04 13:07:19'),
(6, 'Melvinaville', 'NA', '1', 3, 1, '2023-07-04 13:07:19', '2023-07-04 13:07:19'),
(7, 'South Dylan', 'FI', '1', 3, 1, '2023-07-04 13:07:19', '2023-07-04 13:07:19'),
(8, 'South Lexus', 'BG', '1', 3, 1, '2023-07-04 13:07:19', '2023-07-04 13:07:19'),
(9, 'Lake Pearlinetown', 'BG', '1', 3, 1, '2023-07-04 13:07:19', '2023-07-04 13:07:19'),
(10, 'North Emoryville', 'BG', '1', 3, 1, '2023-07-04 13:07:19', '2023-07-04 13:07:19'),
(11, 'Kingfurt', 'BG', '1', 3, 1, '2023-07-04 13:07:19', '2023-07-04 13:07:19'),
(12, 'Lake Mertiemouth', 'NA', '1', 3, 1, '2023-07-04 13:07:19', '2023-07-04 13:07:19'),
(13, 'Port Diannabury', 'BG', '1', 3, 1, '2023-07-04 13:07:19', '2023-07-04 13:07:19'),
(14, 'Lake Gilesmouth', 'BG', '1', 3, 1, '2023-07-04 13:07:19', '2023-07-04 13:07:19'),
(15, 'Melanyport', 'FI', '1', 3, 1, '2023-07-04 13:07:19', '2023-07-04 13:07:19'),
(16, 'West Robbie', 'NA', '1', 3, 1, '2023-07-04 13:07:19', '2023-07-04 13:07:19'),
(17, 'New Alvah', 'NA', '1', 3, 1, '2023-07-04 13:07:19', '2023-07-04 13:07:19'),
(18, 'Port Presleyshire', 'BG', '1', 3, 1, '2023-07-04 13:07:19', '2023-07-04 13:07:19'),
(19, 'Zettaland', 'FI', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(20, 'West Bartonstad', 'BG', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(21, 'Brendenbury', 'BG', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(22, 'North Nicolette', 'NA', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(23, 'West Rooseveltberg', 'NA', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(24, 'Klingfurt', 'NA', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(25, 'East Rafaelshire', 'FI', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(26, 'Eulahton', 'NA', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(27, 'Wardville', 'NA', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(28, 'West Gaetano', 'BG', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(29, 'Camillaberg', 'FI', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(30, 'Felicitatown', 'FI', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(31, 'Armandoborough', 'BG', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(32, 'Handville', 'BG', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(33, 'North Reybury', 'BG', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(34, 'Abbotthaven', 'FI', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(35, 'South Dameonside', 'BG', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(36, 'Mireyaport', 'FI', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(37, 'Gislasontown', 'NA', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(38, 'Kubburgh', 'FI', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(39, 'Abbyview', 'BG', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(40, 'West Ozellafort', 'NA', '1', 3, 1, '2023-07-04 13:07:20', '2023-07-04 13:07:20'),
(41, 'North Mireille', 'NA', '1', 3, 1, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(42, 'Gregville', 'BG', '1', 3, 1, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(43, 'Lake Royce', 'BG', '1', 3, 1, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(44, 'Jerrellshire', 'NA', '1', 3, 1, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(45, 'New Jeanneside', 'BG', '1', 3, 1, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(46, 'Wilkinsonton', 'NA', '1', 3, 1, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(47, 'Deshaunhaven', 'BG', '1', 3, 1, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(48, 'Lake Lance', 'FI', '1', 3, 1, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(49, 'Welchfurt', 'NA', '1', 3, 1, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(50, 'Alenaberg', 'FI', '1', 3, 1, '2023-07-04 13:07:21', '2023-07-04 13:07:21');

-- --------------------------------------------------------

--
-- Struttura della tabella `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_sd` varchar(255) DEFAULT NULL,
  `id_da_app` varchar(255) DEFAULT NULL,
  `time_stamp_pulizia` timestamp NULL DEFAULT NULL,
  `civic` varchar(255) DEFAULT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `altitude` decimal(10,7) NOT NULL,
  `accuracy` decimal(10,7) NOT NULL,
  `height` decimal(10,7) NOT NULL,
  `width` decimal(10,7) NOT NULL,
  `depth` decimal(10,7) NOT NULL,
  `pic` varchar(500) DEFAULT NULL,
  `note` varchar(500) DEFAULT NULL,
  `street_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `cancellabile` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `items`
--

INSERT INTO `items` (`id`, `id_sd`, `id_da_app`, `time_stamp_pulizia`, `civic`, `longitude`, `latitude`, `altitude`, `accuracy`, `height`, `width`, `depth`, `pic`, `note`, `street_id`, `user_id`, `cancellabile`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'PQR678', '2023-06-05 09:03:32_user3_lat2_long4', '2023-06-05 07:03:32', '132', '139.3327040', '-21.7946210', '41.5092310', '106.2000000', '73.8000000', '85.7000000', '89.2000000', 'numquam_ut.jpg', 'Libero suscipit voluptatem corporis qui.', 3, 2, '2023-07-04 13:07:22', '2023-07-05 13:07:22', '2023-07-04 13:07:22', '2023-07-04 13:07:22'),
(2, 'YZA567', '2023-06-04 20:33:32_user3_lat4_long1', '2023-06-04 18:33:32', '132', '-52.4020240', '60.9895180', '-173.2816820', '101.5000000', '121.1000000', '63.5000000', '45.6000000', 'veritatis_delectus.jpg', 'Qui quia in aut.', 12, 2, NULL, NULL, '2023-07-04 13:07:22', '2023-07-04 13:07:22'),
(3, 'STU901', '2023-06-05 09:03:32_user1_lat4_long1', '2023-06-05 07:03:32', '53', '30.8988360', '23.3382900', '156.2801530', '52.2000000', '147.3000000', '122.7000000', '63.2000000', 'velit_est.jpg', 'Quod adipisci accusamus et assumenda omnis.', 6, 2, '2023-07-04 13:07:22', '2023-07-05 13:07:22', '2023-07-04 13:07:22', '2023-07-04 13:07:22'),
(4, 'BCD890', '2023-06-06 01:43:32_user1_lat1_long4', '2023-06-05 23:43:32', '11', '-57.6277960', '73.5553010', '-15.1383440', '105.8000000', '26.3000000', '55.5000000', '113.9000000', 'velit_velit.jpg', 'Est explicabo laboriosam pariatur placeat.', 13, 2, NULL, NULL, '2023-07-04 13:07:22', '2023-07-04 13:07:22'),
(5, 'JKL012', '2023-06-04 16:23:32_user4_lat4_long1', '2023-06-04 14:23:32', '232', '175.7950640', '42.5691900', '140.8904910', '79.5000000', '40.7000000', '60.0000000', '58.2000000', 'ducimus_aut.jpg', 'Tempora et est consequatur omnis.', 9, 2, '2023-07-04 13:07:22', '2023-07-05 13:07:22', '2023-07-04 13:07:22', '2023-07-04 13:07:22'),
(6, 'MNO345', '2023-06-05 17:23:32_user4_lat3_long4', '2023-06-05 15:23:32', '53', '153.2537830', '29.6724360', '93.0385280', '12.7000000', '127.7000000', '83.6000000', '130.3000000', 'facilis_repellat.jpg', 'Molestias autem blanditiis ipsa vero.', 13, 2, NULL, NULL, '2023-07-04 13:07:22', '2023-07-04 13:07:22'),
(7, 'PQR678', '2023-06-06 01:43:32_user4_lat1_long2', '2023-06-05 23:43:32', '243', '79.8975010', '-36.8068730', '-41.5680750', '26.9000000', '33.2000000', '98.6000000', '75.8000000', 'et_placeat.jpg', 'Enim eos nemo quaerat eos.', 10, 2, '2023-07-04 13:07:22', '2023-07-05 13:07:22', '2023-07-04 13:07:22', '2023-07-04 13:07:22'),
(8, 'DEF456', '2023-06-05 17:23:32_user4_lat2_long1', '2023-06-05 15:23:32', '132', '66.5198300', '-27.5042560', '-101.3013030', '50.3000000', '126.9000000', '59.1000000', '116.4000000', 'sed_fugit.jpg', 'Aut similique veniam sint dolore inventore odit.', 13, 2, '2023-07-04 13:07:23', '2023-07-05 13:07:23', '2023-07-04 13:07:22', '2023-07-04 13:07:23'),
(9, 'DEF456', '2023-06-05 00:43:32_user4_lat4_long4', '2023-06-04 22:43:32', '53', '177.7297220', '-2.0341510', '20.4754290', '91.3000000', '54.2000000', '66.5000000', '132.0000000', 'hic_doloribus.jpg', 'Dolores ex et nobis laborum.', 1, 2, '2023-07-04 13:07:23', '2023-07-05 13:07:23', '2023-07-04 13:07:23', '2023-07-04 13:07:23'),
(10, 'MNO345', '2023-06-05 21:33:32_user2_lat1_long2', '2023-06-05 19:33:32', '243', '-97.2424460', '82.2821150', '-100.7213080', '144.4000000', '92.7000000', '96.4000000', '71.7000000', 'fugit_molestias.jpg', 'Molestiae et aut sunt ea consequatur dicta minima.', 2, 2, NULL, NULL, '2023-07-04 13:07:23', '2023-07-04 13:07:23');

-- --------------------------------------------------------

--
-- Struttura della tabella `item_tag`
--

CREATE TABLE `item_tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2016_01_15_105324_create_roles_table', 1),
(4, '2016_01_15_114412_create_role_user_table', 1),
(5, '2016_01_26_115212_create_permissions_table', 1),
(6, '2016_01_26_115523_create_permission_role_table', 1),
(7, '2016_02_09_132439_create_permission_user_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(10, '2023_03_27_124438_create_cities_table', 1),
(11, '2023_03_27_130436_create_streets_table', 1),
(12, '2023_03_27_131643_create_items_table', 1),
(13, '2023_03_27_131749_create_tags_table', 1),
(14, '2023_03_29_082722_create_item_tag_table', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `permission_role`
--

CREATE TABLE `permission_role` (
  `id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `permission_user`
--

CREATE TABLE `permission_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `level`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin', 'admin', 'ruolo Admin', 5, '2023-07-04 13:07:16', '2023-07-04 13:07:16', NULL),
(2, 'Operatore', 'operatore', 'Ruolo operatore', 2, '2023-07-04 13:07:17', '2023-07-04 13:07:17', NULL),
(3, 'Cliente', 'cliente', 'Ruolo cliente', 1, '2023-07-04 13:07:17', '2023-07-04 13:07:17', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `role_user`
--

CREATE TABLE `role_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `role_user`
--

INSERT INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, '2023-07-04 13:07:17', '2023-07-04 13:07:17', NULL),
(2, 2, 2, '2023-07-04 13:07:17', '2023-07-04 13:07:17', NULL),
(3, 3, 3, '2023-07-04 13:07:17', '2023-07-04 13:07:17', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `streets`
--

CREATE TABLE `streets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(250) NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `streets`
--

INSERT INTO `streets` (`id`, `name`, `city_id`, `created_at`, `updated_at`) VALUES
(1, 'Michelle Port', 7, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(2, 'Lauriane Green', 29, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(3, 'Jast Isle', 45, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(4, 'Konopelski Shoals', 32, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(5, 'Johann Harbor', 40, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(6, 'Will Flat', 41, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(7, 'Clemmie Trafficway', 40, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(8, 'Grady Plaza', 14, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(9, 'Mallie Flat', 8, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(10, 'Constantin Fort', 31, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(11, 'Monte Plaza', 21, '2023-07-04 13:07:21', '2023-07-04 13:07:21'),
(12, 'Larkin Tunnel', 14, '2023-07-04 13:07:22', '2023-07-04 13:07:22'),
(13, 'Chyna Prairie', 42, '2023-07-04 13:07:22', '2023-07-04 13:07:22'),
(14, 'Schmeler Keys', 9, '2023-07-04 13:07:22', '2023-07-04 13:07:22'),
(15, 'Jarrell Circle', 18, '2023-07-04 13:07:22', '2023-07-04 13:07:22');

-- --------------------------------------------------------

--
-- Struttura della tabella `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `type` varchar(100) NOT NULL,
  `domain` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `tags`
--

INSERT INTO `tags` (`id`, `name`, `description`, `type`, `domain`, `created_at`, `updated_at`) VALUES
(1, 'Fognatura Bianca', '', 'Recapito', 'item', '2023-07-04 13:07:17', '2023-07-04 13:07:17'),
(2, 'Fognatura Nera', '', 'Recapito', 'item', '2023-07-04 13:07:17', '2023-07-04 13:07:17'),
(3, 'Fognatura Mista', '', 'Recapito', 'item', '2023-07-04 13:07:17', '2023-07-04 13:07:17'),
(4, 'Caditoia', '', 'Tipologia', 'item', '2023-07-04 13:07:17', '2023-07-04 13:07:17'),
(5, 'Bocca di Lupo', '', 'Tipologia', 'item', '2023-07-04 13:07:17', '2023-07-04 13:07:17'),
(6, 'Griglia', '', 'Tipologia', 'item', '2023-07-04 13:07:17', '2023-07-04 13:07:17'),
(7, 'Funzionante', '', 'Stato', 'item', '2023-07-04 13:07:17', '2023-07-04 13:07:17'),
(8, 'Rotta', '', 'Stato', 'item', '2023-07-04 13:07:17', '2023-07-04 13:07:17'),
(9, 'Bloccata', '', 'Stato', 'item', '2023-07-04 13:07:18', '2023-07-04 13:07:18'),
(10, 'Cemento', '', 'Stato', 'item', '2023-07-04 13:07:18', '2023-07-04 13:07:18'),
(11, 'Radici', '', 'Stato', 'item', '2023-07-04 13:07:18', '2023-07-04 13:07:18'),
(12, 'Non Scarica', '', 'Stato', 'item', '2023-07-04 13:07:18', '2023-07-04 13:07:18'),
(13, 'Fondo Rotto', '', 'Stato', 'item', '2023-07-04 13:07:18', '2023-07-04 13:07:18'),
(14, 'Macchina Sopra', '', 'Stato', 'item', '2023-07-04 13:07:18', '2023-07-04 13:07:18');

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `api_token` varchar(80) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `status`, `email_verified_at`, `password`, `api_token`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@admin.com', 1, NULL, '$2y$10$fRQkRcl4J.2CcWU1Vst7..c4BlEmqH7j.f3EDtPMGiqi5/qjvakVu', 'RXapteJ9QHBFKBsIla20UeSYEnDAqBGyy5RgXUeNjF1rOjNhUqpieS48IYZACGFHEBUN2tBorQON4YP7', NULL, '2023-07-04 13:07:17', '2023-07-04 13:07:17'),
(2, 'User', 'user@user.com', 1, NULL, '$2y$10$Cf3n/a29wmlhk6PiuhE9uucgXcYx6feGwyBhzPVqXWWioVQnIV2Oq', 'cJLp9L81JdwylCerixkVDEZscHNY4tv26gUD1idL1lH0shU09nIrCKqzlof78f9rpHNZPr9ae3iIUg9c', NULL, '2023-07-04 13:07:17', '2023-07-04 13:07:17'),
(3, 'Cliente', 'cliente@cliente.com', 1, NULL, '$2y$10$ShSCVi0x3O9qURB1BTsJX.xVREtLtuyKnLiUaR0jOpQOayDl5iif6', 'eRcdD1P6lyWSiLLJtfHsL2YD52Y752PD68rlnn2eFBKh9jF1es5kfWnVsKhhFmpmP1c2BQrVhFhoDHSp', NULL, '2023-07-04 13:07:17', '2023-07-04 13:07:17');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cities_user_id_foreign` (`user_id`);

--
-- Indici per le tabelle `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indici per le tabelle `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_street_id_foreign` (`street_id`),
  ADD KEY `items_user_id_foreign` (`user_id`);

--
-- Indici per le tabelle `item_tag`
--
ALTER TABLE `item_tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_tag_item_id_foreign` (`item_id`),
  ADD KEY `item_tag_tag_id_foreign` (`tag_id`);

--
-- Indici per le tabelle `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indici per le tabelle `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`);

--
-- Indici per le tabelle `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_role_permission_id_index` (`permission_id`),
  ADD KEY `permission_role_role_id_index` (`role_id`);

--
-- Indici per le tabelle `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_user_permission_id_index` (`permission_id`),
  ADD KEY `permission_user_user_id_index` (`user_id`);

--
-- Indici per le tabelle `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indici per le tabelle `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indici per le tabelle `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_user_role_id_index` (`role_id`),
  ADD KEY `role_user_user_id_index` (`user_id`);

--
-- Indici per le tabelle `streets`
--
ALTER TABLE `streets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `streets_city_id_foreign` (`city_id`);

--
-- Indici per le tabelle `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_api_token_unique` (`api_token`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT per la tabella `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `item_tag`
--
ALTER TABLE `item_tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `permission_user`
--
ALTER TABLE `permission_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `streets`
--
ALTER TABLE `streets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT per la tabella `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_street_id_foreign` FOREIGN KEY (`street_id`) REFERENCES `streets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `item_tag`
--
ALTER TABLE `item_tag`
  ADD CONSTRAINT `item_tag_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `permission_user`
--
ALTER TABLE `permission_user`
  ADD CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `streets`
--
ALTER TABLE `streets`
  ADD CONSTRAINT `streets_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
