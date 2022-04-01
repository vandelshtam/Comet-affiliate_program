-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:8889
-- Время создания: Апр 01 2022 г., 07:09
-- Версия сервера: 5.7.34
-- Версия PHP: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `comet-affiliate_program`
--

-- --------------------------------------------------------

--
-- Структура таблицы `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220311063859', '2022-03-11 06:39:17', 48),
('DoctrineMigrations\\Version20220311081751', '2022-03-11 08:17:59', 20),
('DoctrineMigrations\\Version20220311082702', '2022-03-11 08:27:12', 81),
('DoctrineMigrations\\Version20220312073436', '2022-03-12 07:34:51', 51),
('DoctrineMigrations\\Version20220312073650', '2022-03-12 07:36:56', 52),
('DoctrineMigrations\\Version20220312081501', '2022-03-12 08:15:05', 88),
('DoctrineMigrations\\Version20220312140738', '2022-03-12 14:07:45', 88),
('DoctrineMigrations\\Version20220312154856', '2022-03-12 15:49:00', 89),
('DoctrineMigrations\\Version20220312171525', '2022-03-12 17:15:29', 25),
('DoctrineMigrations\\Version20220312183431', '2022-03-12 18:34:35', 25),
('DoctrineMigrations\\Version20220312183505', '2022-03-12 18:35:09', 84),
('DoctrineMigrations\\Version20220312183846', '2022-03-12 18:38:51', 85),
('DoctrineMigrations\\Version20220312183934', '2022-03-12 18:39:48', 21),
('DoctrineMigrations\\Version20220312184134', '2022-03-12 18:41:37', 84),
('DoctrineMigrations\\Version20220312184330', '2022-03-12 18:43:37', 139),
('DoctrineMigrations\\Version20220312184500', '2022-03-12 18:45:04', 85),
('DoctrineMigrations\\Version20220313062212', '2022-03-13 06:22:17', 57),
('DoctrineMigrations\\Version20220313062501', '2022-03-13 06:36:43', 23),
('DoctrineMigrations\\Version20220313063636', '2022-03-13 06:36:43', 0),
('DoctrineMigrations\\Version20220315060837', '2022-03-15 06:08:45', 95),
('DoctrineMigrations\\Version20220315084733', '2022-03-15 08:47:38', 89),
('DoctrineMigrations\\Version20220315145944', '2022-03-15 14:59:51', 84),
('DoctrineMigrations\\Version20220315164944', '2022-03-15 16:49:50', 24),
('DoctrineMigrations\\Version20220315165606', '2022-03-15 16:56:11', 84),
('DoctrineMigrations\\Version20220316061619', '2022-03-16 06:16:35', 23),
('DoctrineMigrations\\Version20220316062510', '2022-03-16 06:25:16', 56),
('DoctrineMigrations\\Version20220316062748', '2022-03-16 06:27:51', 70),
('DoctrineMigrations\\Version20220316063137', '2022-03-16 06:31:40', 84),
('DoctrineMigrations\\Version20220316143747', '2022-03-16 14:37:54', 47),
('DoctrineMigrations\\Version20220316150613', '2022-03-16 15:06:19', 45),
('DoctrineMigrations\\Version20220316150753', '2022-03-16 15:07:58', 83),
('DoctrineMigrations\\Version20220316152806', '2022-03-16 15:28:13', 79),
('DoctrineMigrations\\Version20220316153042', '2022-03-16 15:30:46', 20),
('DoctrineMigrations\\Version20220316154802', '2022-03-16 15:48:15', 48),
('DoctrineMigrations\\Version20220316155310', '2022-03-16 15:53:21', 114),
('DoctrineMigrations\\Version20220316155329', '2022-03-16 15:53:33', 20),
('DoctrineMigrations\\Version20220316155412', '2022-03-16 15:54:17', 20),
('DoctrineMigrations\\Version20220317060624', '2022-03-17 06:06:29', 23),
('DoctrineMigrations\\Version20220317080935', '2022-03-17 08:09:38', 79),
('DoctrineMigrations\\Version20220317081512', '2022-03-17 08:15:18', 87),
('DoctrineMigrations\\Version20220317081523', '2022-03-17 08:15:27', 19),
('DoctrineMigrations\\Version20220317105608', '2022-03-17 10:56:14', 49),
('DoctrineMigrations\\Version20220317111246', '2022-03-17 11:12:52', 46),
('DoctrineMigrations\\Version20220317111809', '2022-03-17 11:18:13', 44),
('DoctrineMigrations\\Version20220317112507', '2022-03-17 11:25:11', 47),
('DoctrineMigrations\\Version20220317112557', '2022-03-17 11:26:01', 23),
('DoctrineMigrations\\Version20220317112924', '2022-03-17 11:29:30', 87),
('DoctrineMigrations\\Version20220317135155', '2022-03-17 13:52:00', 88),
('DoctrineMigrations\\Version20220317174910', '2022-03-17 17:49:21', 116),
('DoctrineMigrations\\Version20220317175951', '2022-03-17 17:59:55', 23),
('DoctrineMigrations\\Version20220317180128', '2022-03-17 18:01:32', 20),
('DoctrineMigrations\\Version20220317181121', '2022-03-17 18:11:25', 23),
('DoctrineMigrations\\Version20220317181517', '2022-03-17 18:15:20', 23),
('DoctrineMigrations\\Version20220317181608', '2022-03-17 18:16:12', 23),
('DoctrineMigrations\\Version20220317182206', '2022-03-17 18:22:10', 21),
('DoctrineMigrations\\Version20220317182238', '2022-03-17 18:22:44', 20),
('DoctrineMigrations\\Version20220317190607', '2022-03-17 19:06:13', 105),
('DoctrineMigrations\\Version20220317191214', '2022-03-17 19:12:19', 113),
('DoctrineMigrations\\Version20220318095843', '2022-03-18 09:58:53', 62),
('DoctrineMigrations\\Version20220318095959', '2022-03-18 10:00:03', 53),
('DoctrineMigrations\\Version20220318105120', '2022-03-18 10:51:24', 59),
('DoctrineMigrations\\Version20220318105330', '2022-03-18 10:53:35', 51),
('DoctrineMigrations\\Version20220318112410', '2022-03-18 11:24:14', 58),
('DoctrineMigrations\\Version20220318112606', '2022-03-18 11:26:10', 21),
('DoctrineMigrations\\Version20220318112752', '2022-03-18 11:27:56', 23),
('DoctrineMigrations\\Version20220318122010', '2022-03-18 12:20:15', 23),
('DoctrineMigrations\\Version20220318122049', '2022-03-18 12:20:54', 22),
('DoctrineMigrations\\Version20220318122219', '2022-03-18 12:22:22', 58),
('DoctrineMigrations\\Version20220318123206', '2022-03-18 12:32:09', 63),
('DoctrineMigrations\\Version20220318135618', '2022-03-18 13:56:22', 60),
('DoctrineMigrations\\Version20220318153008', '2022-03-18 15:30:12', 103),
('DoctrineMigrations\\Version20220318161110', '2022-03-18 16:11:14', 87),
('DoctrineMigrations\\Version20220319105540', '2022-03-19 10:55:45', 94),
('DoctrineMigrations\\Version20220319105700', '2022-03-19 10:57:05', 85),
('DoctrineMigrations\\Version20220322133513', '2022-03-22 13:35:22', 96),
('DoctrineMigrations\\Version20220325132922', '2022-03-25 13:29:55', 98),
('DoctrineMigrations\\Version20220328150139', '2022-03-28 15:01:52', 90),
('DoctrineMigrations\\Version20220328160031', '2022-03-28 16:00:36', 84),
('DoctrineMigrations\\Version20220328165751', '2022-03-28 16:57:56', 58),
('DoctrineMigrations\\Version20220329160753', '2022-03-29 16:07:58', 53),
('DoctrineMigrations\\Version20220330085242', '2022-03-30 08:52:47', 54),
('DoctrineMigrations\\Version20220330105509', '2022-03-30 10:55:13', 80),
('DoctrineMigrations\\Version20220330120035', '2022-03-30 12:00:38', 78),
('DoctrineMigrations\\Version20220330135615', '2022-03-30 13:56:19', 49),
('DoctrineMigrations\\Version20220330173026', '2022-03-30 17:30:30', 82),
('DoctrineMigrations\\Version20220331053247', '2022-03-31 05:32:52', 85),
('DoctrineMigrations\\Version20220331084343', '2022-03-31 08:43:47', 81),
('DoctrineMigrations\\Version20220331152421', '2022-03-31 15:24:25', 19),
('DoctrineMigrations\\Version20220331175622', '2022-03-31 17:56:32', 22),
('DoctrineMigrations\\Version20220331175644', '2022-03-31 17:56:50', 21);

-- --------------------------------------------------------

--
-- Структура таблицы `list_referral_networks`
--

CREATE TABLE `list_referral_networks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_networks_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `owner_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pakege_id` int(11) NOT NULL,
  `unique_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `network_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profit_network` int(11) DEFAULT NULL,
  `payments_direct` int(11) DEFAULT NULL,
  `payments_cash` int(11) DEFAULT NULL,
  `current_balance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `list_referral_networks`
--

INSERT INTO `list_referral_networks` (`id`, `name`, `referral_networks_id`, `owner_id`, `owner_name`, `pakege_id`, `unique_code`, `network_code`, `client_code`, `profit_network`, `payments_direct`, `payments_cash`, `current_balance`) VALUES
(31, 'оп67па', NULL, 2, NULL, 239, 'On6J4PMaz0IQVr2ioy9z', '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 18595, 2200, 8819, 3363);

-- --------------------------------------------------------

--
-- Структура таблицы `pakages`
--

CREATE TABLE `pakages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `pakege`
--

CREATE TABLE `pakege` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `referral_networks_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` int(11) DEFAULT NULL,
  `activation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unique_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `pakege`
--

INSERT INTO `pakege` (`id`, `name`, `user_id`, `price`, `referral_networks_id`, `client_code`, `token`, `activation`, `unique_code`, `referral_link`) VALUES
(239, 'VIP', 2, 10000, '31-239-239-On6J4PMaz0IQVr2ioy9z', '21497043565', 200000, 'активирован', 'On6J4PMaz0IQVr2ioy9z', NULL),
(255, 'Basic', 2, 500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 10000, 'активирован', 'H50W1HaOr0bvCeR5BXl2', '31-239-239-On6J4PMaz0IQVr2ioy9z'),
(256, 'Basic', 2, 500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 10000, 'активирован', 'QnyQAFuR9Yf142Kg2C2i', '31-239-239-On6J4PMaz0IQVr2ioy9z'),
(257, 'Networker', 2, 1000, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 20000, 'активирован', '7hUyulP3bycQUA16mnj0', '31-239-239-On6J4PMaz0IQVr2ioy9z'),
(258, 'Basic', 2, 500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 10000, 'активирован', 'aoxC02fmwzhqvLlWmcUD', '31-256-239-On6J4PMaz0IQVr2ioy9z'),
(259, 'Basic', 2, 500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 10000, 'активирован', 'mL2CHmcMc7eSw2rnMpbv', '31-256-239-On6J4PMaz0IQVr2ioy9z'),
(260, 'Networker', 2, 1000, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 20000, 'активирован', 'P3JgmXoMmFG1UEgqK1GX', '31-256-239-On6J4PMaz0IQVr2ioy9z'),
(261, 'Networker', 2, 1000, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 20000, 'активирован', 'RDVDCRUDLUInsg1rJ2bL', '31-256-239-On6J4PMaz0IQVr2ioy9z'),
(262, 'Networker', 2, 1000, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 20000, 'активирован', 'ntJW0yhf5l287Jj3s0ek', '31-256-239-On6J4PMaz0IQVr2ioy9z'),
(263, 'Basic', 2, 500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 10000, 'активирован', '7xNwEPXvRyK3Hs0BCh7K', '31-257-239-On6J4PMaz0IQVr2ioy9z'),
(264, 'Basic', 2, 500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 10000, 'активирован', 'TQlMpqAflteXgsHPQ9rP', '31-257-239-On6J4PMaz0IQVr2ioy9z'),
(265, 'Networker', 2, 1000, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 20000, 'активирован', '63PwxeczR1s2BWIED29n', '31-257-239-On6J4PMaz0IQVr2ioy9z'),
(266, 'Basic', 2, 500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 10000, 'активирован', 'ELz2n3SUzSme04AL8xD3', '31-259-239-On6J4PMaz0IQVr2ioy9z'),
(267, 'Networker', 2, 1000, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 20000, 'активирован', 'k3FVR5L3jPlRS62XwSGn', '31-259-239-On6J4PMaz0IQVr2ioy9z'),
(268, 'Business', 2, 2500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 50000, 'активирован', 'fLiZGj6MNJxudc8qpGDw', '31-259-239-On6J4PMaz0IQVr2ioy9z'),
(269, 'Networker', 2, 1000, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 20000, 'активирован', 'P2r5pZlCgYkFDnsJNKPG', '31-262-239-On6J4PMaz0IQVr2ioy9z'),
(270, 'Networker', 2, 1000, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 20000, 'активирован', 'dX03NbmaDx8acz6gInKy', '31-262-239-On6J4PMaz0IQVr2ioy9z'),
(271, 'Business', 2, 2500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 50000, 'активирован', 'nqzujxB3r3n7HZFLuaAN', '31-264-239-On6J4PMaz0IQVr2ioy9z'),
(272, 'Trader', 2, 5000, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 100000, 'активирован', 'HamtOjlEIVrLFDziVWJW', '31-264-239-On6J4PMaz0IQVr2ioy9z'),
(273, 'Trader', 2, 5000, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 100000, 'активирован', 'KI1XAkOkGBDnJ9Z2wpi1', '31-264-239-On6J4PMaz0IQVr2ioy9z'),
(274, 'Trader', 2, 5000, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 100000, 'активирован', 'VVTP6D46qniRGuCJo6Z0', '31-264-239-On6J4PMaz0IQVr2ioy9z'),
(275, 'Basic', 2, 500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 10000, 'активирован', 'GyLkQUCqJlSllkBooqOf', '31-268-239-On6J4PMaz0IQVr2ioy9z'),
(276, 'Basic', 2, 500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 10000, 'активирован', 'ixLmESc9kjHnCFM9ncSx', '31-268-239-On6J4PMaz0IQVr2ioy9z'),
(277, 'Basic', 2, 500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 10000, 'активирован', 'Ejz9ImwNVDoourSun2kj', '31-262-239-On6J4PMaz0IQVr2ioy9z'),
(278, 'Basic', 2, 500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 10000, 'активирован', 'oIEg89k0nVhiLIu2yWT2', '31-262-239-On6J4PMaz0IQVr2ioy9z'),
(279, 'Basic', 2, 500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 10000, 'активирован', 'ZNLyDHW8t7TtPnXsKMU9', '31-262-239-On6J4PMaz0IQVr2ioy9z'),
(280, 'Networker', 2, 1000, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 20000, 'активирован', 'L2mNDm04LHVTPUbWsvR8', '31-271-239-On6J4PMaz0IQVr2ioy9z'),
(281, 'Basic', 2, 500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 10000, 'активирован', 'eFZkIdSDIv4Uph9QOlXA', '31-276-239-On6J4PMaz0IQVr2ioy9z'),
(282, 'Basic', 2, 500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 10000, 'активирован', '5jwrsqQV3TzcHexiCzp2', '31-276-239-On6J4PMaz0IQVr2ioy9z'),
(283, 'Basic', 2, 500, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 10000, 'активирован', 'yvgrVOlEGIMAU4Pg5ttV', '31-274-239-On6J4PMaz0IQVr2ioy9z'),
(286, 'Networker', 2, 1000, '239-On6J4PMaz0IQVr2ioy9z', '21497043565', 20000, 'активирован', 'jo2AqRYaMUQhjYmxr5Am', '31-264-239-On6J4PMaz0IQVr2ioy9z');

-- --------------------------------------------------------

--
-- Структура таблицы `personal_data`
--

CREATE TABLE `personal_data` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `house` int(11) NOT NULL,
  `frame` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apartment` int(11) NOT NULL,
  `indexcity` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `users_id` int(11) DEFAULT NULL,
  `tel` int(11) DEFAULT NULL,
  `block` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `personal_data`
--

INSERT INTO `personal_data` (`id`, `name`, `surname`, `phone`, `state`, `region`, `city`, `street`, `house`, `frame`, `apartment`, `indexcity`, `user_id`, `users_id`, `tel`, `block`, `client_code`) VALUES
(35, 'Vladimir', 'Morozov', '89957771877', 'Armenia', 'Dfg', 'Москва', 'Первомайское, Горчаково, Школьная Улица, Д.11, К.2', 3, '6', 3, 143396, 3, NULL, NULL, '6', '32010652754'),
(40, 'Nnnn', 'Nnnnn', '8985756748', 'Armenia', 'vvv', 'bbb', 'gfddd', 1, NULL, 3, 789654, 2, NULL, NULL, 'c', '21497043565'),
(41, 'vvvvv', 'bbbbbb', '666666666', 'Georgia', 'hhhh', 'kkkkkk', 'wwwwww', 2, NULL, 4, 876543, 11, NULL, NULL, '3', '11155039691');

-- --------------------------------------------------------

--
-- Структура таблицы `pkege`
--

CREATE TABLE `pkege` (
  `id` int(11) NOT NULL,
  `activation` int(11) DEFAULT NULL,
  `token` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `referral_network`
--

CREATE TABLE `referral_network` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personal_data_id` int(11) DEFAULT NULL,
  `balance` int(11) DEFAULT NULL,
  `network_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pakege_id` int(11) DEFAULT NULL,
  `network_id` int(11) DEFAULT NULL,
  `user_referral_id` int(11) DEFAULT NULL,
  `network_referral_id` int(11) DEFAULT NULL,
  `reward` int(11) DEFAULT NULL,
  `koef` double DEFAULT NULL,
  `cash` int(11) DEFAULT NULL,
  `direct` int(11) DEFAULT NULL,
  `my_team` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_network_profit` int(11) DEFAULT NULL,
  `payments_network` int(11) DEFAULT NULL,
  `payments_cash` int(11) DEFAULT NULL,
  `pakage` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `referral_network`
--

INSERT INTO `referral_network` (`id`, `name`, `user_id`, `user_status`, `personal_data_id`, `balance`, `network_code`, `member_code`, `pakege_id`, `network_id`, `user_referral_id`, `network_referral_id`, `reward`, `koef`, `cash`, `direct`, `my_team`, `current_network_profit`, `payments_network`, `payments_cash`, `pakage`) VALUES
(304, NULL, 2, 'owner', NULL, 0, '239-On6J4PMaz0IQVr2ioy9z', '31-239-239-On6J4PMaz0IQVr2ioy9z', 239, 31, NULL, NULL, 594, 0.94771380186283, 444, 150, '31-239-239-On6J4PMaz0IQVr2ioy9z', NULL, NULL, NULL, 10000),
(326, 'My name 7', 2, 'left', NULL, 0, '239-On6J4PMaz0IQVr2ioy9z', '31-256-239-On6J4PMaz0IQVr2ioy9z', 256, 31, 2, 304, 1589, NULL, 1139, 450, '31-239-239-On6J4PMaz0IQVr2ioy9z', 0, 50, 0, 500),
(327, 'My name 1', 2, 'right', NULL, 0, '239-On6J4PMaz0IQVr2ioy9z', '31-257-239-On6J4PMaz0IQVr2ioy9z', 257, 31, 2, 304, 492, 0.052286198137172, 292, 200, '31-239-239-On6J4PMaz0IQVr2ioy9z', 850, 100, 50, 500),
(330, 'My name 1', 2, 'left', NULL, 0, '239-On6J4PMaz0IQVr2ioy9z', '31-259-239-On6J4PMaz0IQVr2ioy9z', 259, 31, 2, 326, 1794, NULL, 1394, 400, '31-256-239-On6J4PMaz0IQVr2ioy9z', 850, 50, 100, 1000),
(333, 'My name 1', 2, 'right', NULL, 0, '239-On6J4PMaz0IQVr2ioy9z', '31-262-239-On6J4PMaz0IQVr2ioy9z', 262, 31, 2, 326, 955, NULL, 605, 350, '31-256-239-On6J4PMaz0IQVr2ioy9z', 0, 100, 0, 500),
(335, 'My name 1', 2, 'left', NULL, 0, '239-On6J4PMaz0IQVr2ioy9z', '31-264-239-On6J4PMaz0IQVr2ioy9z', 264, 31, 2, 327, 2839, 0.049885263893046, 989, 1850, '31-257-239-On6J4PMaz0IQVr2ioy9z', 1307, 50, 642, 500),
(337, 'My name 1', 2, 'right', NULL, 0, '239-On6J4PMaz0IQVr2ioy9z', '31-266-239-On6J4PMaz0IQVr2ioy9z', 266, 31, 2, 330, 561, 0.041500664010624, 561, NULL, '31-259-239-On6J4PMaz0IQVr2ioy9z', 0, 50, 0, 1000),
(338, 'My name 1', 2, 'left', NULL, 0, '239-On6J4PMaz0IQVr2ioy9z', '31-267-239-On6J4PMaz0IQVr2ioy9z', 267, 31, 2, 330, 788, NULL, 788, NULL, '31-259-239-On6J4PMaz0IQVr2ioy9z', 0, 100, 0, 1000),
(339, 'My name 1', 2, 'right', NULL, 0, '239-On6J4PMaz0IQVr2ioy9z', '31-268-239-On6J4PMaz0IQVr2ioy9z', 268, 31, 2, 330, 705, 0.20750332005312, 555, 150, '31-259-239-On6J4PMaz0IQVr2ioy9z', 2650, 250, 0, 500),
(340, 'My name 1', 2, 'left', NULL, 0, '239-On6J4PMaz0IQVr2ioy9z', '31-269-239-On6J4PMaz0IQVr2ioy9z', 269, 31, 2, 333, 618, 0.11158594491928, 618, NULL, '31-262-239-On6J4PMaz0IQVr2ioy9z', 2931, 100, 2244, 1000),
(341, 'My name 7', 2, 'right', NULL, 0, '239-On6J4PMaz0IQVr2ioy9z', '31-270-239-On6J4PMaz0IQVr2ioy9z', 270, 31, 2, 333, 455, NULL, 455, NULL, '31-262-239-On6J4PMaz0IQVr2ioy9z', 788, 100, 1111, 1000),
(342, 'My name 1', 2, 'left', NULL, 0, '239-On6J4PMaz0IQVr2ioy9z', '31-271-239-On6J4PMaz0IQVr2ioy9z', 271, 31, 2, 335, 414, NULL, 314, 100, '31-264-239-On6J4PMaz0IQVr2ioy9z', 4924, 250, 1011, 2500),
(345, 'My name 1', 2, 'right', NULL, 1820, '239-On6J4PMaz0IQVr2ioy9z', '31-274-239-On6J4PMaz0IQVr2ioy9z', 274, 31, 2, 335, 377, 0.54089347079038, 327, 50, '31-264-239-On6J4PMaz0IQVr2ioy9z', 0, 500, 0, 5000),
(346, 'My name 1', 2, 'right', NULL, 180, '239-On6J4PMaz0IQVr2ioy9z', '31-275-239-On6J4PMaz0IQVr2ioy9z', 275, 31, 2, 339, 319, 0.05360824742268, 319, NULL, '31-268-239-On6J4PMaz0IQVr2ioy9z', 1847, 50, 1362, 500),
(348, 'My name 1', 2, 'left', NULL, 0, '239-On6J4PMaz0IQVr2ioy9z', '31-276-239-On6J4PMaz0IQVr2ioy9z', 276, 31, 2, 339, 192, NULL, 92, 100, '31-268-239-On6J4PMaz0IQVr2ioy9z', 814, 50, 135, 500),
(350, 'My name 1', 2, 'right', NULL, 296, '239-On6J4PMaz0IQVr2ioy9z', '31-278-239-On6J4PMaz0IQVr2ioy9z', 278, 31, 2, 333, 230, 0.088201603665521, 230, NULL, '31-262-239-On6J4PMaz0IQVr2ioy9z', 0, 50, 0, 500),
(351, 'My name 7', 2, 'left', NULL, 0, '239-On6J4PMaz0IQVr2ioy9z', '31-279-239-On6J4PMaz0IQVr2ioy9z', 279, 31, 2, 333, 92, NULL, 92, NULL, '31-262-239-On6J4PMaz0IQVr2ioy9z', 650, 50, 300, 500),
(352, 'My name 1', 2, 'right', NULL, 682, '239-On6J4PMaz0IQVr2ioy9z', '31-280-239-On6J4PMaz0IQVr2ioy9z', 280, 31, 2, 342, 50, 0.20274914089347, 50, NULL, '31-271-239-On6J4PMaz0IQVr2ioy9z', 0, 100, 0, 1000),
(354, 'My name 7', 2, 'left', NULL, 0, '239-On6J4PMaz0IQVr2ioy9z', '31-282-239-On6J4PMaz0IQVr2ioy9z', 282, 31, 2, 348, 0, NULL, 0, NULL, '31-276-239-On6J4PMaz0IQVr2ioy9z', 250, 50, 699, 500),
(355, 'My name 1', 2, 'right', NULL, 385, '239-On6J4PMaz0IQVr2ioy9z', '31-283-239-On6J4PMaz0IQVr2ioy9z', 283, 31, 2, 345, NULL, 0.11454753722795, NULL, NULL, '31-274-239-On6J4PMaz0IQVr2ioy9z', 0, 50, 0, 500),
(356, 'My name 1', 2, 'left', NULL, 0, '239-On6J4PMaz0IQVr2ioy9z', '31-286-239-On6J4PMaz0IQVr2ioy9z', 286, 31, 2, 335, 0, NULL, 0, NULL, '31-264-239-On6J4PMaz0IQVr2ioy9z', 734, 100, 1165, 1000);

-- --------------------------------------------------------

--
-- Структура таблицы `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `reset_password_request`
--

INSERT INTO `reset_password_request` (`id`, `user_id`, `selector`, `hashed_token`, `requested_at`, `expires_at`) VALUES
(2, 3, 'LMZ5rf0jK5rXOVb4TQge', 'kePJ8YXkDtO4uD8Xclo4745jwZ/ybRCrOxGqAalnZXE=', '2022-03-15 15:29:18', '2022-03-15 16:29:18');

-- --------------------------------------------------------

--
-- Структура таблицы `rewards`
--

CREATE TABLE `rewards` (
  `id` int(11) NOT NULL,
  `rewards_usdt` int(11) DEFAULT NULL,
  `rewards_token` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `personal_data_id` int(11) DEFAULT NULL,
  `referral_network_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `setting_options`
--

CREATE TABLE `setting_options` (
  `id` int(11) NOT NULL,
  `payments_singleline` int(11) DEFAULT NULL,
  `payments_direct` int(11) DEFAULT NULL,
  `cash_back` int(11) DEFAULT NULL,
  `all_price_pakage` int(11) DEFAULT NULL,
  `accrual_limit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `setting_options`
--

INSERT INTO `setting_options` (`id`, `payments_singleline`, `payments_direct`, `cash_back`, `all_price_pakage`, `accrual_limit`) VALUES
(1, 10, 10, 300, 100000000, 70);

-- --------------------------------------------------------

--
-- Структура таблицы `table_pakage`
--

CREATE TABLE `table_pakage` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_pakage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `table_pakage`
--

INSERT INTO `table_pakage` (`id`, `name`, `price_pakage`) VALUES
(1, 'Starter', '100'),
(2, 'Basic', '500'),
(3, 'Networker', '1000'),
(4, 'Business', '2500'),
(5, 'Trader', '5000'),
(6, 'VIP', '10000');

-- --------------------------------------------------------

--
-- Структура таблицы `token_rate`
--

CREATE TABLE `token_rate` (
  `id` int(11) NOT NULL,
  `exchange_rate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `token_rate`
--

INSERT INTO `token_rate` (`id`, `exchange_rate`) VALUES
(1, 20);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `personal_data_id` int(11) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `personal_data_id`, `username`, `role`, `referral_link`) VALUES
(2, 'mvlju977@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$OKjO/hiW3BLHx0Q.YabVz.h2z2tJa2cxrWhSC0axTSHO4YKrSzKPy', 1, 40, NULL, 'ROLE_ADMIN', NULL),
(3, 'asdf@gmail.com', '[\"ROLE_SUPER_ADMIN\"]', '$2y$13$LqI6EzlLI5/34oDlfc82wePoTLSZ4jIKDi58MwIojBmfVQgcB7cGm', 1, 35, 'vandelshtam', 'ROLE_ADMIN', NULL),
(11, 'bbb123@mail.ru', '[\"ROLE_ADMIN\"]', '$2y$13$iGmjxYHSe.q/3Om/K26Ww.dKCMJO9RKxGwCIS6F8mikfiJGGm9HqS', 1, 41, 'Nikos', NULL, NULL),
(12, '111@gmail.com', '[]', '$2y$13$dJlbrXU9aTpoR41xQOzsvO6c5jIH2qZLiPtXO.18b3j/DLSGgWZ.2', 1, 0, NULL, NULL, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `list_referral_networks`
--
ALTER TABLE `list_referral_networks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_87858524658941E6` (`pakege_id`);

--
-- Индексы таблицы `pakages`
--
ALTER TABLE `pakages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_43CA6751A76ED395` (`user_id`);

--
-- Индексы таблицы `pakege`
--
ALTER TABLE `pakege`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_ABEDE3E1A76ED395` (`user_id`);

--
-- Индексы таблицы `personal_data`
--
ALTER TABLE `personal_data`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pkege`
--
ALTER TABLE `pkege`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `referral_network`
--
ALTER TABLE `referral_network`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

--
-- Индексы таблицы `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `setting_options`
--
ALTER TABLE `setting_options`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `table_pakage`
--
ALTER TABLE `table_pakage`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `token_rate`
--
ALTER TABLE `token_rate`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `list_referral_networks`
--
ALTER TABLE `list_referral_networks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT для таблицы `pakages`
--
ALTER TABLE `pakages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `pakege`
--
ALTER TABLE `pakege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=287;

--
-- AUTO_INCREMENT для таблицы `personal_data`
--
ALTER TABLE `personal_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT для таблицы `pkege`
--
ALTER TABLE `pkege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `referral_network`
--
ALTER TABLE `referral_network`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=357;

--
-- AUTO_INCREMENT для таблицы `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `rewards`
--
ALTER TABLE `rewards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `setting_options`
--
ALTER TABLE `setting_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `table_pakage`
--
ALTER TABLE `table_pakage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `token_rate`
--
ALTER TABLE `token_rate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `list_referral_networks`
--
ALTER TABLE `list_referral_networks`
  ADD CONSTRAINT `FK_87858524658941E6` FOREIGN KEY (`pakege_id`) REFERENCES `pakege` (`id`);

--
-- Ограничения внешнего ключа таблицы `pakages`
--
ALTER TABLE `pakages`
  ADD CONSTRAINT `FK_43CA6751A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ограничения внешнего ключа таблицы `pakege`
--
ALTER TABLE `pakege`
  ADD CONSTRAINT `FK_ABEDE3E1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ограничения внешнего ключа таблицы `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
