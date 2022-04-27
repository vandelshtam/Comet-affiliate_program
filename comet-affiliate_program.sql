-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:8889
-- Время создания: Апр 27 2022 г., 05:35
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
('DoctrineMigrations\\Version20220331175644', '2022-03-31 17:56:50', 21),
('DoctrineMigrations\\Version20220401124902', '2022-04-01 12:49:12', 48),
('DoctrineMigrations\\Version20220401125132', '2022-04-01 12:51:38', 113),
('DoctrineMigrations\\Version20220401125927', '2022-04-01 12:59:32', 17),
('DoctrineMigrations\\Version20220401130255', '2022-04-01 13:02:58', 19),
('DoctrineMigrations\\Version20220401130613', '2022-04-01 13:06:17', 21),
('DoctrineMigrations\\Version20220401130857', '2022-04-01 13:09:01', 22),
('DoctrineMigrations\\Version20220401131505', '2022-04-01 13:15:08', 16),
('DoctrineMigrations\\Version20220401132019', '2022-04-01 13:20:23', 56),
('DoctrineMigrations\\Version20220402070255', '2022-04-02 07:03:01', 22),
('DoctrineMigrations\\Version20220404073435', '2022-04-04 07:34:52', 91),
('DoctrineMigrations\\Version20220404130428', '2022-04-04 13:04:34', 19),
('DoctrineMigrations\\Version20220404131809', '2022-04-04 13:18:13', 18),
('DoctrineMigrations\\Version20220405060611', '2022-04-05 06:06:15', 80),
('DoctrineMigrations\\Version20220405065525', '2022-04-05 06:55:29', 19),
('DoctrineMigrations\\Version20220405130719', '2022-04-05 13:07:26', 85),
('DoctrineMigrations\\Version20220405132312', '2022-04-05 13:23:17', 18),
('DoctrineMigrations\\Version20220406122550', '2022-04-06 12:25:58', 50),
('DoctrineMigrations\\Version20220406133320', '2022-04-06 13:33:24', 45),
('DoctrineMigrations\\Version20220406133529', '2022-04-06 13:35:33', 18),
('DoctrineMigrations\\Version20220407125044', '2022-04-07 12:50:48', 95),
('DoctrineMigrations\\Version20220407130021', '2022-04-07 13:00:26', 47),
('DoctrineMigrations\\Version20220408072638', '2022-04-08 07:26:43', 101),
('DoctrineMigrations\\Version20220411114657', '2022-04-11 11:47:13', 69),
('DoctrineMigrations\\Version20220411121212', '2022-04-11 12:12:17', 115),
('DoctrineMigrations\\Version20220411121552', '2022-04-11 12:15:58', 20),
('DoctrineMigrations\\Version20220412095325', '2022-04-12 09:53:30', 87),
('DoctrineMigrations\\Version20220414064423', '2022-04-14 06:44:30', 84),
('DoctrineMigrations\\Version20220414135557', '2022-04-14 13:56:00', 78),
('DoctrineMigrations\\Version20220414135659', '2022-04-14 13:57:02', 77),
('DoctrineMigrations\\Version20220414135734', '2022-04-14 13:57:37', 75),
('DoctrineMigrations\\Version20220414153520', '2022-04-14 15:35:25', 89),
('DoctrineMigrations\\Version20220415053319', '2022-04-15 05:33:25', 28),
('DoctrineMigrations\\Version20220415055113', '2022-04-15 05:51:17', 266),
('DoctrineMigrations\\Version20220415062825', '2022-04-15 06:28:53', 281),
('DoctrineMigrations\\Version20220415063244', '2022-04-15 06:32:51', 90),
('DoctrineMigrations\\Version20220415065755', '2022-04-15 06:58:00', 53),
('DoctrineMigrations\\Version20220415070545', '2022-04-15 07:05:50', 95),
('DoctrineMigrations\\Version20220415070658', '2022-04-15 07:07:02', 82),
('DoctrineMigrations\\Version20220415071146', '2022-04-15 07:11:51', 92),
('DoctrineMigrations\\Version20220415102343', '2022-04-15 10:23:47', 117),
('DoctrineMigrations\\Version20220415114855', '2022-04-15 11:48:59', 90),
('DoctrineMigrations\\Version20220415115607', '2022-04-15 11:56:12', 90),
('DoctrineMigrations\\Version20220416055238', '2022-04-16 05:52:51', 92),
('DoctrineMigrations\\Version20220416055359', '2022-04-16 05:54:05', 86),
('DoctrineMigrations\\Version20220416055449', '2022-04-16 05:54:54', 90),
('DoctrineMigrations\\Version20220416055607', '2022-04-16 05:56:11', 88),
('DoctrineMigrations\\Version20220416055831', '2022-04-16 05:58:36', 193),
('DoctrineMigrations\\Version20220416105154', '2022-04-16 10:51:59', 30),
('DoctrineMigrations\\Version20220416160752', '2022-04-16 16:07:58', 92),
('DoctrineMigrations\\Version20220416162739', '2022-04-16 16:27:42', 29),
('DoctrineMigrations\\Version20220416165720', '2022-04-16 16:57:25', 51),
('DoctrineMigrations\\Version20220416172905', '2022-04-16 17:29:10', 211),
('DoctrineMigrations\\Version20220420055002', '2022-04-20 05:50:08', 95),
('DoctrineMigrations\\Version20220420055304', '2022-04-20 05:53:08', 93),
('DoctrineMigrations\\Version20220420055547', '2022-04-20 05:55:51', 61),
('DoctrineMigrations\\Version20220420064623', '2022-04-20 06:46:27', 92),
('DoctrineMigrations\\Version20220420073525', '2022-04-20 07:35:29', 24),
('DoctrineMigrations\\Version20220420075928', '2022-04-20 07:59:32', 30),
('DoctrineMigrations\\Version20220420102226', '2022-04-20 10:22:30', 62),
('DoctrineMigrations\\Version20220421064212', '2022-04-21 06:42:19', 101),
('DoctrineMigrations\\Version20220421074156', '2022-04-21 07:42:01', 80),
('DoctrineMigrations\\Version20220421074331', '2022-04-21 07:43:36', 98),
('DoctrineMigrations\\Version20220421113223', '2022-04-21 11:32:28', 69),
('DoctrineMigrations\\Version20220422054347', '2022-04-22 05:43:50', 71),
('DoctrineMigrations\\Version20220422093414', '2022-04-22 09:34:17', 98),
('DoctrineMigrations\\Version20220424074839', '2022-04-24 07:48:48', 61),
('DoctrineMigrations\\Version20220424075033', '2022-04-24 07:50:38', 65),
('DoctrineMigrations\\Version20220426101514', '2022-04-26 10:15:18', 28),
('DoctrineMigrations\\Version20220426170226', '2022-04-26 17:02:31', 35);

-- --------------------------------------------------------

--
-- Структура таблицы `fast_consultation`
--

CREATE TABLE `fast_consultation` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `current_balance` int(11) DEFAULT NULL,
  `system_revenues` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `list_referral_networks`
--

INSERT INTO `list_referral_networks` (`id`, `name`, `referral_networks_id`, `owner_id`, `owner_name`, `pakege_id`, `unique_code`, `network_code`, `client_code`, `profit_network`, `payments_direct`, `payments_cash`, `current_balance`, `system_revenues`, `created_at`, `updated_at`) VALUES
(1, 'CoMeta Club', NULL, 37, 'Cometa Club 1', 571, 'orXB4z5jKmEDeS2k9WJ4', '571-orXB4z5jKmEDeS2k9WJ4', '37CP219593248', 17750, 4500, 1000, 7500, 3750, '2022-04-26 12:06:10', '2022-04-26 13:47:55');

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
  `referral_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `action` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `pakege`
--

INSERT INTO `pakege` (`id`, `name`, `user_id`, `price`, `referral_networks_id`, `client_code`, `token`, `activation`, `unique_code`, `referral_link`, `created_at`, `updated_at`, `action`) VALUES
(571, 'VIP', 37, 10000, '1-571-571-orXB4z5jKmEDeS2k9WJ4', '37CP219593248', 200000, 'активирован', 'orXB4z5jKmEDeS2k9WJ4', NULL, '2022-04-26 11:17:27', '2022-04-26 11:17:27', 0),
(576, 'VIP', 38, 10000, '571-orXB4z5jKmEDeS2k9WJ4', '38CP830004105', 200000, 'активирован', 'Dlu3Y7jhKtC94igtusCR', '1-571-571-orXB4z5jKmEDeS2k9WJ4', '2022-04-26 12:29:07', '2022-04-26 12:29:07', 0),
(577, 'VIP', 39, 10000, '571-orXB4z5jKmEDeS2k9WJ4', '39CP265431940', 200000, 'активирован', 'LvhQFA3cbG60Qbxr2R3N', '1-571-571-orXB4z5jKmEDeS2k9WJ4', '2022-04-26 13:39:05', '2022-04-26 13:39:05', 0),
(578, 'Business', 40, 2500, '571-orXB4z5jKmEDeS2k9WJ4', '40CP1784194723', 50000, 'активирован', 'tjA06QjJmRmzylyNZGrw', '1-576-571-orXB4z5jKmEDeS2k9WJ4', '2022-04-26 13:47:36', '2022-04-26 13:47:36', 0);

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
  `client_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `personal_data`
--

INSERT INTO `personal_data` (`id`, `name`, `surname`, `phone`, `state`, `region`, `city`, `street`, `house`, `frame`, `apartment`, `indexcity`, `user_id`, `users_id`, `tel`, `block`, `client_code`, `created_at`, `updated_at`) VALUES
(1, '111', '111', '111', 'Armenia', '111', '111', '111', 111, NULL, 1, 111, 37, NULL, NULL, '1', '37CP219593248', '2022-04-26 10:17:44', NULL),
(2, '222', '222', '222', 'Armenia', '222', '222', '222', 22, NULL, 2, 222, 38, NULL, NULL, '2', '38CP830004105', '2022-04-26 10:50:29', NULL),
(3, '333', '333', '333', 'Armenia', '333', '333', '333', 33, NULL, 3, 333, 39, NULL, NULL, '3', '39CP265431940', '2022-04-26 13:38:34', NULL),
(4, '444', '444', '444', 'Armenia', '444', '444', '444', 44, NULL, 4, 444, 40, NULL, NULL, '4', '40CP1784194723', '2022-04-26 13:47:01', NULL),
(5, '666', '666', '666', 'Turkey', '666', '666', '666', 66, NULL, 6, 666, 42, NULL, NULL, '6', '42CP1005135332', '2022-04-27 05:10:02', NULL),
(6, '555', '555', '555', 'Turkey', '555', '555', '555', 55, NULL, 5, 555, 41, NULL, NULL, '5', '41CP1543982127', '2022-04-27 05:13:28', NULL);

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
  `pakage` int(11) DEFAULT NULL,
  `reward_wallet` int(11) DEFAULT NULL,
  `withdrawal_to_wallet` int(11) DEFAULT NULL,
  `system_revenues` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `referral_network`
--

INSERT INTO `referral_network` (`id`, `name`, `user_id`, `user_status`, `personal_data_id`, `balance`, `network_code`, `member_code`, `pakege_id`, `network_id`, `user_referral_id`, `network_referral_id`, `reward`, `koef`, `cash`, `direct`, `my_team`, `current_network_profit`, `payments_network`, `payments_cash`, `pakage`, `reward_wallet`, `withdrawal_to_wallet`, `system_revenues`, `created_at`, `updated_at`) VALUES
(1, NULL, 37, 'owner', NULL, 7500, '571-orXB4z5jKmEDeS2k9WJ4', '1-571-571-orXB4z5jKmEDeS2k9WJ4', 571, 1, NULL, NULL, 5000, 1, 1000, 4000, '1-571-571-orXB4z5jKmEDeS2k9WJ4', 0, 0, 0, 10000, 5000, 0, NULL, '2022-04-26 12:06:10', '2022-04-26 13:39:27'),
(11, 'Cometa Club 2', 38, 'left', NULL, 0, '571-orXB4z5jKmEDeS2k9WJ4', '1-576-571-orXB4z5jKmEDeS2k9WJ4', 576, 1, 37, 1, 750, NULL, 250, 500, '1-571-571-orXB4z5jKmEDeS2k9WJ4', 0, 2000, 0, 10000, 750, 0, 0, '2022-04-26 13:19:04', '2022-04-26 13:47:55'),
(12, 'CoMeta 3', 39, 'right', NULL, 0, '571-orXB4z5jKmEDeS2k9WJ4', '1-577-571-orXB4z5jKmEDeS2k9WJ4', 577, 1, 37, 1, 0, NULL, 0, 0, '1-571-571-orXB4z5jKmEDeS2k9WJ4', 14000, 2000, 1000, 10000, 0, 0, 3000, '2022-04-26 13:39:27', NULL),
(13, 'Cometa 4', 40, 'left', NULL, 0, '571-orXB4z5jKmEDeS2k9WJ4', '1-578-571-orXB4z5jKmEDeS2k9WJ4', 578, 1, 38, 11, 0, NULL, 0, 0, '1-576-571-orXB4z5jKmEDeS2k9WJ4', 3750, 500, 0, 2500, 0, 0, 750, '2022-04-26 13:47:55', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `referral_to_email`
--

CREATE TABLE `referral_to_email` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `referral_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_to_client` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Структура таблицы `saving_mail`
--

CREATE TABLE `saving_mail` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `from_mail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_mail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `saving_mail`
--

INSERT INTO `saving_mail` (`id`, `user_id`, `from_mail`, `to_mail`, `status`, `text`, `created_at`, `updated_at`, `category`) VALUES
(1, 32, NULL, NULL, 'success', 'kkjkjkjjkjkjk', '2022-04-24 08:43:29', '2022-04-24 08:43:29', 'fast_consultation'),
(2, 32, 'mvlju977@gmail.com', 'Commet-AT@example.com', 'success', 'rrrrrrr', '2022-04-24 08:47:25', '2022-04-24 08:47:25', 'technical_support'),
(3, 32, 'mvlju977@gmail.com', 'Commet-AT@example.com', 'success', 'hhhh', '2022-04-24 08:58:16', '2022-04-24 08:58:16', 'technical_support'),
(4, 32, '888@mail.ru', 'mvlju977@gmail.com', 'success', 'Вы успешно отправлили реферальную ссылку новому кандидату!', '2022-04-24 09:02:10', '2022-04-24 09:02:10', 'referral_link'),
(5, 32, '888@mail.ru', 'mvlju977@gmail.com', 'success', 'http://164.92.159.123/register/1-321-321-HqmLiwO9jc4ZVTQHqLhR', '2022-04-24 09:04:57', '2022-04-24 09:04:57', 'referral_link'),
(6, 32, 'mvlju977@gmail.com', 'Commet-AT@example.com', 'success', 'eeeee', '2022-04-24 09:10:48', '2022-04-24 09:10:48', 'technical_support'),
(7, 32, '888@mail.ru', 'mvlju977@gmail.com', 'success', 'http://164.92.159.123/register/1-321-321-HqmLiwO9jc4ZVTQHqLhR', '2022-04-24 09:11:57', '2022-04-24 09:11:57', 'referral_link'),
(8, 32, 'mvlju977@gmail.com', 'Commet-AT@example.com', 'success', 'wwww', '2022-04-24 09:19:38', '2022-04-24 09:19:38', 'technical_support'),
(9, 32, 'mvlju977@gmail.com', 'Commet-AT@example.com', 'success', 'hhhh', '2022-04-24 09:21:23', '2022-04-24 09:21:23', 'technical_support'),
(10, 32, 'mvlju977@gmail.com', 'Commet-AT@example.com', 'success', 'hhhh', '2022-04-24 09:25:32', '2022-04-24 09:25:32', 'technical_support'),
(11, 32, NULL, NULL, 'success', 'Благодарим Вас за вступление в нашу сеть и приобретение пакета!', '2022-04-26 07:51:03', '2022-04-26 07:51:03', 'new_pakege'),
(12, 37, NULL, NULL, 'success', 'Благодарим Вас за вступление в нашу сеть и приобретение пакета!', '2022-04-26 11:10:21', '2022-04-26 11:10:21', 'new_pakege'),
(13, 37, NULL, NULL, 'success', 'Благодарим Вас за вступление в нашу сеть и приобретение пакета!', '2022-04-26 11:11:47', '2022-04-26 11:11:47', 'new_pakege'),
(14, 37, NULL, NULL, 'success', 'Благодарим Вас за вступление в нашу сеть и приобретение пакета!', '2022-04-26 11:14:18', '2022-04-26 11:14:18', 'new_pakege'),
(15, 37, NULL, NULL, 'success', 'Благодарим Вас за вступление в нашу сеть и приобретение пакета!', '2022-04-26 11:15:41', '2022-04-26 11:15:41', 'new_pakege'),
(16, 37, NULL, NULL, 'success', 'Благодарим Вас за вступление в нашу сеть и приобретение пакета!', '2022-04-26 11:16:33', '2022-04-26 11:16:33', 'new_pakege'),
(17, 37, NULL, NULL, 'success', 'Благодарим Вас за вступление в нашу сеть и приобретение пакета!', '2022-04-26 11:16:58', '2022-04-26 11:16:58', 'new_pakege'),
(18, 37, NULL, NULL, 'success', 'Благодарим Вас за вступление в нашу сеть и приобретение пакета!', '2022-04-26 11:17:27', '2022-04-26 11:17:27', 'new_pakege'),
(19, 37, NULL, NULL, 'success', 'Благодарим Вас за вступление в нашу сеть и приобретение пакета!', '2022-04-26 11:18:45', '2022-04-26 11:18:45', 'new_pakege'),
(20, 37, NULL, NULL, 'success', 'Благодарим Вас за вступление в нашу сеть и приобретение пакета!', '2022-04-26 11:51:54', '2022-04-26 11:51:54', 'new_pakege'),
(21, 37, NULL, NULL, 'success', 'Благодарим Вас за вступление в нашу сеть и приобретение пакета!', '2022-04-26 12:03:20', '2022-04-26 12:03:20', 'new_pakege'),
(22, 38, NULL, NULL, 'success', 'Благодарим Вас за вступление в нашу сеть и приобретение пакета!', '2022-04-26 12:22:24', '2022-04-26 12:22:24', 'new_pakege'),
(23, 38, NULL, NULL, 'success', 'Благодарим Вас за вступление в нашу сеть и приобретение пакета!', '2022-04-26 12:29:07', '2022-04-26 12:29:07', 'new_pakege'),
(24, 39, NULL, NULL, 'success', 'Благодарим Вас за вступление в нашу сеть и приобретение пакета!', '2022-04-26 13:39:05', '2022-04-26 13:39:05', 'new_pakege'),
(25, 39, '333@cometaclub.com', 'mvlju977@gmail.com', 'success', 'http://164.92.159.123/register/1-577-571-orXB4z5jKmEDeS2k9WJ4', '2022-04-26 13:44:08', '2022-04-26 13:44:08', 'referral_link'),
(26, 40, NULL, NULL, 'success', 'Благодарим Вас за вступление в нашу сеть и приобретение пакета!', '2022-04-26 13:47:36', '2022-04-26 13:47:36', 'new_pakege'),
(27, 37, 'mvlju977@gmail.com', 'Commet-AT@example.com', 'success', 'qqqqqqq', '2022-04-26 14:38:56', '2022-04-26 14:38:56', 'technical_support'),
(28, 37, 'mvlju977@gmail.com', 'Commet-AT@example.com', 'success', 'ff', '2022-04-26 14:40:09', '2022-04-26 14:40:09', 'technical_support'),
(29, 40, 'mvlju977@gmail.com', 'Commet-AT@example.com', 'error', 'eeee', '2022-04-26 16:09:09', '2022-04-26 16:09:09', 'technical_support'),
(30, 37, 'mvlju977@gmail.com', 'Commet-AT@example.com', 'error', 'jhjhhj', '2022-04-26 16:11:00', '2022-04-26 16:11:00', 'technical_support'),
(31, 37, 'mvlju977@gmail.com', 'Commet-AT@example.com', 'error', 'jj', '2022-04-26 16:17:04', '2022-04-26 16:17:04', 'technical_support'),
(32, 37, 'mvlju977@gmail.com', 'Commet-AT@example.com', 'success', 'hhhhhhh', '2022-04-26 16:56:58', '2022-04-26 16:56:58', 'technical_support'),
(33, 40, 'mvlju977@gmail.com', 'Commet-AT@example.com', 'success', 'hhhhhh', '2022-04-26 16:57:52', '2022-04-26 16:57:52', 'technical_support'),
(34, 42, 'mvlju977@gmail.com', 'Commet-AT@example.com', 'success', 'Vbnn', '2022-04-27 05:10:17', '2022-04-27 05:10:17', 'technical_support'),
(35, 40, '444@cometaclub.com', 'mvlju977@gmail.com', 'success', 'http://164.92.159.123/register/1-578-571-orXB4z5jKmEDeS2k9WJ4', '2022-04-27 05:15:22', '2022-04-27 05:15:22', 'referral_link');

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
  `accrual_limit` int(11) DEFAULT NULL,
  `system_revenues` int(11) DEFAULT NULL,
  `start_day` int(11) DEFAULT NULL,
  `fast_start` datetime DEFAULT NULL,
  `update_day` int(11) DEFAULT NULL,
  `limit_wallet_from_line` int(11) DEFAULT NULL,
  `payments_direct_fast` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `multi_pakage_day` datetime DEFAULT NULL,
  `name_multi_pakage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `setting_options`
--

INSERT INTO `setting_options` (`id`, `payments_singleline`, `payments_direct`, `cash_back`, `all_price_pakage`, `accrual_limit`, `system_revenues`, `start_day`, `fast_start`, `update_day`, `limit_wallet_from_line`, `payments_direct_fast`, `created_at`, `updated_at`, `multi_pakage_day`, `name_multi_pakage`) VALUES
(1, 10, 10, 300, 100000000, 70, 30, 15, '2022-05-05 14:32:35', 30, 70, 20, '2022-04-01 13:23:08', NULL, '2022-05-05 13:22:38', 'Business');

-- --------------------------------------------------------

--
-- Структура таблицы `table_pakage`
--

CREATE TABLE `table_pakage` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_pakage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `table_pakage`
--

INSERT INTO `table_pakage` (`id`, `name`, `price_pakage`, `created_at`, `updated_at`, `description`) VALUES
(1, 'Starter', '100', NULL, NULL, 'Максимальный доход пакета 300 USDT'),
(2, 'Basic', '500', NULL, NULL, 'Максимальный доход пакета 1500 USDT'),
(3, 'Networker', '1000', NULL, NULL, 'Максимальный доход пакета 3000 USDT'),
(4, 'Business', '2500', NULL, NULL, 'Максимальный доход пакета 7500 USDT'),
(5, 'Trader', '5000', NULL, NULL, 'Максимальный доход пакета 15000 USDT'),
(6, 'VIP', '10000', NULL, NULL, 'Максимальный доход пакета 30000 USDT');

-- --------------------------------------------------------

--
-- Структура таблицы `token_rate`
--

CREATE TABLE `token_rate` (
  `id` int(11) NOT NULL,
  `exchange_rate` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `token_rate`
--

INSERT INTO `token_rate` (`id`, `exchange_rate`, `created_at`, `updated_at`) VALUES
(1, 20, NULL, NULL);

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
  `referral_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pesonal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pakage_status` int(11) DEFAULT NULL,
  `pakage_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `multi_pakage` int(11) DEFAULT NULL,
  `secret_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `personal_data_id`, `username`, `role`, `referral_link`, `pesonal_code`, `pakage_status`, `pakage_id`, `updated_at`, `created_at`, `multi_pakage`, `secret_code`) VALUES
(37, '111@cometaclub.com', '[\"ROLE_SUPER_ADMIN\"]', '$2y$13$YCB0k5QqC.679gYT.QDI6urVil0ZSDAupN2KJ5HLvf1rnjUIuSjxm', 1, 1, 'Cometa Club 1', NULL, NULL, '37CP219593248', 1, 571, '2022-04-26 10:16:36', '2022-04-26 09:12:37', NULL, '1403533995-1301828488'),
(38, '222@cometaclub.com', '[\"ROLE_SUPER_ADMIN\"]', '$2y$13$u14Y40NILPIuUZHOUPDGz.EqiuaM7xrNnMx8lDIoaQRfQCQzorJIS', 1, 2, 'Cometa Club 2', NULL, '1-571-571-orXB4z5jKmEDeS2k9WJ4', '38CP830004105', 1, 576, '2022-04-26 10:52:58', '2022-04-26 09:14:12', NULL, '1944128214-25569961'),
(39, '333@cometaclub.com', '[\"ROLE_ADMIN\"]', '$2y$13$7dNH1D7oSdDgABzKhr.EduAvd.d.pXvF0SZpk/JefetqATF7vKT7q', 1, 3, NULL, NULL, '1-571-571-orXB4z5jKmEDeS2k9WJ4', '39CP265431940', 1, 577, NULL, '2022-04-26 09:15:00', NULL, '433477455-1593133099'),
(40, '444@cometaclub.com', '[\"ROLE_ADMIN\"]', '$2y$13$2BGD2oFReDxOeXclwbD9bOEnyGLbXiu.0LuRWiPR/wU1J0fOptr5K', 1, 4, NULL, NULL, '1-576-571-orXB4z5jKmEDeS2k9WJ4', '40CP1784194723', 1, 578, NULL, '2022-04-26 13:46:30', NULL, '1345822128-574980999'),
(41, '555@cometaclub.com', '[]', '$2y$13$0hwTS7ZcKnZl2DGBZpyhFe78NGJvxX9KhzdzTV/zIugn0pJfvvfzC', 1, 6, NULL, NULL, '1-577-571-orXB4z5jKmEDeS2k9WJ4', '41CP1543982127', 0, NULL, NULL, '2022-04-27 04:56:34', NULL, '1076651594-1112617851'),
(42, '666@cometaclub.com', '[]', '$2y$13$78Yn/OEX8Hxt3SyEXGumruTEEChxYVxwUMxipJPq.VCTMA7oorGFO', 1, 5, NULL, NULL, '1-576-571-orXB4z5jKmEDeS2k9WJ4', '42CP1005135332', 0, NULL, NULL, '2022-04-27 04:58:49', NULL, '154674455-204870965');

-- --------------------------------------------------------

--
-- Структура таблицы `wallet`
--

CREATE TABLE `wallet` (
  `id` int(11) NOT NULL,
  `usdt` double DEFAULT NULL,
  `etherium` double DEFAULT NULL,
  `bitcoin` double DEFAULT NULL,
  `cometpoin` double DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `wallet`
--

INSERT INTO `wallet` (`id`, `usdt`, `etherium`, `bitcoin`, `cometpoin`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 9919800, 0, 0, 0, 37, '2022-04-26 10:17:44', '2022-04-26 11:10:08'),
(2, 10000, 0, 0, 0, 38, '2022-04-26 10:50:29', '2022-04-26 12:28:59'),
(3, 10000, 0, 0, 0, 39, '2022-04-26 13:38:34', '2022-04-26 13:38:50'),
(4, 17500, 0, 0, 0, 40, '2022-04-26 13:47:01', '2022-04-26 13:47:25'),
(5, 0, 0, 0, 0, 42, '2022-04-27 05:10:02', NULL),
(6, 0, 0, 0, 0, 41, '2022-04-27 05:13:28', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `fast_consultation`
--
ALTER TABLE `fast_consultation`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `list_referral_networks`
--
ALTER TABLE `list_referral_networks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_87858524658941E6` (`pakege_id`);

--
-- Индексы таблицы `pakege`
--
ALTER TABLE `pakege`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `personal_data`
--
ALTER TABLE `personal_data`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `referral_network`
--
ALTER TABLE `referral_network`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `referral_to_email`
--
ALTER TABLE `referral_to_email`
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
-- Индексы таблицы `saving_mail`
--
ALTER TABLE `saving_mail`
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
-- Индексы таблицы `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_7C68921FA76ED395` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `fast_consultation`
--
ALTER TABLE `fast_consultation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `list_referral_networks`
--
ALTER TABLE `list_referral_networks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `pakege`
--
ALTER TABLE `pakege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=579;

--
-- AUTO_INCREMENT для таблицы `personal_data`
--
ALTER TABLE `personal_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `referral_network`
--
ALTER TABLE `referral_network`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `referral_to_email`
--
ALTER TABLE `referral_to_email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT для таблицы `saving_mail`
--
ALTER TABLE `saving_mail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT для таблицы `setting_options`
--
ALTER TABLE `setting_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT для таблицы `wallet`
--
ALTER TABLE `wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `list_referral_networks`
--
ALTER TABLE `list_referral_networks`
  ADD CONSTRAINT `FK_87858524658941E6` FOREIGN KEY (`pakege_id`) REFERENCES `pakege` (`id`);

--
-- Ограничения внешнего ключа таблицы `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ограничения внешнего ключа таблицы `wallet`
--
ALTER TABLE `wallet`
  ADD CONSTRAINT `FK_7C68921FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
