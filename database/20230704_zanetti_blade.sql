-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Lug 04, 2023 alle 17:08
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
(4, 'Caditoia', '', 'Tipo Pozzetto', 'item', '2023-07-04 13:07:17', '2023-07-04 13:07:17'),
(5, 'Bocca di Lupo', '', 'Tipo Pozzetto', 'item', '2023-07-04 13:07:17', '2023-07-04 13:07:17'),
(6, 'Griglia', '', 'Tipo Pozzetto', 'item', '2023-07-04 13:07:17', '2023-07-04 13:07:17'),
(7, 'Funzionante', '', 'Stato', 'item', '2023-07-04 13:07:17', '2023-07-04 13:07:17'),
(8, 'Rotta', '', 'Stato', 'item', '2023-07-04 13:07:17', '2023-07-04 13:07:17'),
(9, 'Bloccata', '', 'Stato', 'item', '2023-07-04 13:07:18', '2023-07-04 13:07:18'),
(10, 'Cemento', '', 'Stato', 'item', '2023-07-04 13:07:18', '2023-07-04 13:07:18'),
(11, 'Radici', '', 'Stato', 'item', '2023-07-04 13:07:18', '2023-07-04 13:07:18'),
(12, 'Non Scarica', '', 'Stato', 'item', '2023-07-04 13:07:18', '2023-07-04 13:07:18'),
(13, 'Fondo Rotto', '', 'Stato', 'item', '2023-07-04 13:07:18', '2023-07-04 13:07:18'),
(14, 'Macchina Sopra', '', 'Stato', 'item', '2023-07-04 13:07:18', '2023-07-04 13:07:18');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
