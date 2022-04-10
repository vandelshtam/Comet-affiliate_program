-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:8889
-- Время создания: Апр 10 2022 г., 08:14
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
('DoctrineMigrations\\Version20220408072638', '2022-04-08 07:26:43', 101);

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
  `current_balance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `list_referral_networks`
--

INSERT INTO `list_referral_networks` (`id`, `name`, `referral_networks_id`, `owner_id`, `owner_name`, `pakege_id`, `unique_code`, `network_code`, `client_code`, `profit_network`, `payments_direct`, `payments_cash`, `current_balance`) VALUES
(3, 'my name 1', NULL, 3, 'vandelshtam', 297, 'cmR8G8FDUOCh6RFe81NU', '297-cmR8G8FDUOCh6RFe81NU', '3CP2010652754', 3600, 700, 200, 13000);

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
(297, 'VIP', 3, 10000, '3-297-297-cmR8G8FDUOCh6RFe81NU', '3CP2010652754', 200000, 'активирован', 'cmR8G8FDUOCh6RFe81NU', NULL),
(302, 'Trader', 2, 5000, '297-cmR8G8FDUOCh6RFe81NU', '2CP1497043565', 100000, 'активирован', '7xZELS4eFQ2gHFXh2yQo', '3-297-297-cmR8G8FDUOCh6RFe81NU'),
(309, 'Networker', 2, 1000, '297-cmR8G8FDUOCh6RFe81NU', '2CP1497043565', 20000, 'активирован', 'J3pTd5YlaLcgzZeDePCs', '3-297-297-cmR8G8FDUOCh6RFe81NU'),
(310, 'Networker', 2, 1000, '297-cmR8G8FDUOCh6RFe81NU', '2CP1497043565', 20000, 'активирован', 'py6pv4hfVhzMmXlTvglu', '3-306-297-cmR8G8FDUOCh6RFe81NU');

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
(35, 'Vladimir', 'Morozov', '89957771877', 'Armenia', 'Dfg', 'Москва', 'Первомайское, Горчаково, Школьная Улица, Д.11, К.2', 3, '6', 3, 143396, 3, NULL, NULL, '6', '3CP2010652754'),
(40, 'Nnnn', 'Nnnnn', '8985756748', 'Armenia', 'vvv', 'bbb', 'gfddd', 1, NULL, 3, 789654, 2, NULL, NULL, 'c', '2CP1497043565'),
(51, 'ww', 'ww', '33', 'Armenia', '22', 'ww2', '222', 33, NULL, 3, 22, 8, NULL, NULL, '33', '8CP620277542');

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
  `reward` int(11) DEFAULT '0',
  `koef` double DEFAULT NULL,
  `cash` int(11) DEFAULT '0',
  `direct` int(11) DEFAULT '0',
  `my_team` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_network_profit` int(11) DEFAULT NULL,
  `payments_network` int(11) DEFAULT NULL,
  `payments_cash` int(11) DEFAULT NULL,
  `pakage` int(11) DEFAULT NULL,
  `reward_wallet` int(11) DEFAULT '0',
  `withdrawal_to_wallet` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `referral_network`
--

INSERT INTO `referral_network` (`id`, `name`, `user_id`, `user_status`, `personal_data_id`, `balance`, `network_code`, `member_code`, `pakege_id`, `network_id`, `user_referral_id`, `network_referral_id`, `reward`, `koef`, `cash`, `direct`, `my_team`, `current_network_profit`, `payments_network`, `payments_cash`, `pakage`, `reward_wallet`, `withdrawal_to_wallet`) VALUES
(6, NULL, 3, 'owner', NULL, 9000, '297-cmR8G8FDUOCh6RFe81NU', '3-297-297-cmR8G8FDUOCh6RFe81NU', 297, 3, NULL, NULL, 1700, 1, 100, 1600, '3-297-297-cmR8G8FDUOCh6RFe81NU', NULL, NULL, NULL, NULL, 700, NULL),
(9, 'My name 7', 2, 'left', NULL, 4000, '297-cmR8G8FDUOCh6RFe81NU', '3-306-297-cmR8G8FDUOCh6RFe81NU', 306, 3, 3, 6, 200, NULL, 100, 100, '3-297-297-cmR8G8FDUOCh6RFe81NU', 0, 500, 0, 5000, 200, NULL),
(10, 'My name 1', 2, 'right', NULL, 0, '297-cmR8G8FDUOCh6RFe81NU', '3-309-297-cmR8G8FDUOCh6RFe81NU', 309, 3, 3, 6, NULL, NULL, NULL, NULL, '3-297-297-cmR8G8FDUOCh6RFe81NU', 1800, 100, 100, 1000, NULL, NULL),
(11, 'My name 7', 2, 'left', NULL, 0, '297-cmR8G8FDUOCh6RFe81NU', '3-310-297-cmR8G8FDUOCh6RFe81NU', 310, 3, 2, 9, NULL, NULL, NULL, NULL, '3-306-297-cmR8G8FDUOCh6RFe81NU', 1800, 100, 100, 1000, NULL, NULL);

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
  `referral_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pesonal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pakage_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `personal_data_id`, `username`, `role`, `referral_link`, `pesonal_code`, `pakage_status`) VALUES
(2, 'admin@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$OKjO/hiW3BLHx0Q.YabVz.h2z2tJa2cxrWhSC0axTSHO4YKrSzKPy', 1, 40, NULL, 'ROLE_ADMIN', NULL, '3CP2010652754', 1),
(3, 'superadmin@gmail.com', '[\"ROLE_SUPER_ADMIN\"]', '$2y$13$LqI6EzlLI5/34oDlfc82wePoTLSZ4jIKDi58MwIojBmfVQgcB7cGm', 1, 35, 'vandelshtam', 'ROLE_ADMIN', NULL, '2CP1497043565', 1),
(8, '111@gmail.com', '[]', '$2y$13$8hSbrM7UQSSXKNE79icQoePZRmIkJyeZZ1Hk8LLm9pD3ctslqqh1.', 1, 51, NULL, NULL, NULL, '8CP620277542', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `wallet`
--

CREATE TABLE `wallet` (
  `id` int(11) NOT NULL,
  `usdt` int(11) DEFAULT '0',
  `etherium` double DEFAULT '0',
  `bitcoin` double DEFAULT '0',
  `cometpoin` double DEFAULT '0',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `wallet`
--

INSERT INTO `wallet` (`id`, `usdt`, `etherium`, `bitcoin`, `cometpoin`, `user_id`) VALUES
(1, 6000, 1, 1, 1, 2),
(3, 10000, 5, 1, 2000, 3),
(9, 5000, 0, 0, 0, 8);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `pakages`
--
ALTER TABLE `pakages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `pakege`
--
ALTER TABLE `pakege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=311;

--
-- AUTO_INCREMENT для таблицы `personal_data`
--
ALTER TABLE `personal_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT для таблицы `pkege`
--
ALTER TABLE `pkege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `referral_network`
--
ALTER TABLE `referral_network`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `wallet`
--
ALTER TABLE `wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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

--
-- Ограничения внешнего ключа таблицы `wallet`
--
ALTER TABLE `wallet`
  ADD CONSTRAINT `FK_7C68921FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
