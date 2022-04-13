-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:8889
-- Время создания: Апр 13 2022 г., 05:53
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
('DoctrineMigrations\\Version20220412095325', '2022-04-12 09:53:30', 87);

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
(1, 'My Network 1', NULL, 9, NULL, 313, 'JAjHikwlH7K0Nl2I19QN', '313-JAjHikwlH7K0Nl2I19QN', '9CP907927910', 12750, 1250, 1000, 9998);

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
(313, 'VIP', 9, 10000, '1-313-313-JAjHikwlH7K0Nl2I19QN', '9CP907927910', 200000, 'активирован', 'JAjHikwlH7K0Nl2I19QN', NULL),
(314, 'Trader', 10, 5000, '313-JAjHikwlH7K0Nl2I19QN', '10CP274970529', 100000, 'активирован', 'buXQ1A9hJ86bgXCbHYnA', '1-313-313-JAjHikwlH7K0Nl2I19QN'),
(315, 'VIP', 11, 10000, '313-JAjHikwlH7K0Nl2I19QN', '11CP1988326848', 200000, 'активирован', 'KXbWeTRfNv3tyU1VAY9B', '1-313-313-JAjHikwlH7K0Nl2I19QN'),
(316, 'Business', 12, 2500, '313-JAjHikwlH7K0Nl2I19QN', '12CP1927876844', 50000, 'активирован', 'pYyqFPhW3skwndqZBUMb', '1-314-313-JAjHikwlH7K0Nl2I19QN');

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
(52, 'xxx', 'xxx', '3333333', 'Armenia', 'xxx', 'xxx', 'xxx', 33, NULL, 3, 3333, 9, NULL, NULL, '3', '9CP907927910'),
(53, 'aaaaa', 'aaaaaa', '3333333', 'Armenia', 'aaaaa', 'aaaaaa', 'aaaaa', 33, NULL, 3, 33333, 10, NULL, NULL, '3', '10CP274970529'),
(54, 'hhh', 'hhhh', '44444', 'Armenia', 'hhhhh', 'hhhhh', 'hhhh', 44, NULL, 44, 4444, 11, NULL, NULL, '4', '11CP1988326848'),
(55, '555', '5555', '5555', 'Armenia', '5555', '5555', '5555', 5555, NULL, 55, 5555, 12, NULL, NULL, '5555', '12CP1927876844');

-- --------------------------------------------------------

--
-- Структура таблицы `pkege`
--

CREATE TABLE `pkege` (
  `id` int(11) NOT NULL,
  `activation` int(11) DEFAULT NULL,
  `token` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL
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
  `pakage` int(11) DEFAULT NULL,
  `reward_wallet` int(11) DEFAULT NULL,
  `withdrawal_to_wallet` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `referral_network`
--

INSERT INTO `referral_network` (`id`, `name`, `user_id`, `user_status`, `personal_data_id`, `balance`, `network_code`, `member_code`, `pakege_id`, `network_id`, `user_referral_id`, `network_referral_id`, `reward`, `koef`, `cash`, `direct`, `my_team`, `current_network_profit`, `payments_network`, `payments_cash`, `pakage`, `reward_wallet`, `withdrawal_to_wallet`) VALUES
(1, NULL, 9, 'owner', NULL, 6666, '313-JAjHikwlH7K0Nl2I19QN', '1-313-313-JAjHikwlH7K0Nl2I19QN', 313, 1, NULL, NULL, 2500, 0.66669333546684, 1000, 1500, '1-313-313-JAjHikwlH7K0Nl2I19QN', 0, 0, 0, 10000, 2500, 0),
(3, 'My name 1', 10, 'left', NULL, 0, '313-JAjHikwlH7K0Nl2I19QN', '1-314-313-JAjHikwlH7K0Nl2I19QN', 314, 1, 9, 1, 1000, NULL, 500, 500, '1-313-313-JAjHikwlH7K0Nl2I19QN', 0, 0, 0, 5000, 1000, 0),
(4, 'My name 7', 11, 'right', NULL, 3332, '313-JAjHikwlH7K0Nl2I19QN', '1-315-313-JAjHikwlH7K0Nl2I19QN', 315, 1, 9, 1, 0, 0.33330666453316, 0, 0, '1-313-313-JAjHikwlH7K0Nl2I19QN', 8500, 1000, 500, 10000, 0, 0),
(10, 'Uhfgft', 12, 'left', NULL, 0, '313-JAjHikwlH7K0Nl2I19QN', '1-316-313-JAjHikwlH7K0Nl2I19QN', 316, 1, 10, 3, 0, NULL, 0, 0, '1-314-313-JAjHikwlH7K0Nl2I19QN', 4250, 250, 500, 2500, 0, 0);

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
  `pakage_status` int(11) DEFAULT NULL,
  `pakage_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `personal_data_id`, `username`, `role`, `referral_link`, `pesonal_code`, `pakage_status`, `pakage_id`) VALUES
(3, '', '0', '0', 0, NULL, 'vandelshtam', 'ROLE_ADMIN', NULL, '2CP1497043565', 1, 0),
(9, '222@mail.ru', '[\"ROLE_SUPER_ADMIN\"]', '$2y$13$EiQtTwoftE4BtUx/b8AMdO.Z/rUnB58V0hcCiAl8oSFHmvahz039O', 1, 52, NULL, NULL, NULL, '9CP907927910', 1, 313),
(10, '333@mail.ru', '[\"ROLE_ADMIN\"]', '$2y$13$vMWEDDkM73tw8DQDqTm7q.BBFXq4ApqTIn069o9FYyQ2/ZqYDC/Zi', 1, 53, NULL, NULL, '1-313-313-JAjHikwlH7K0Nl2I19QN', '10CP274970529', 1, 314),
(11, '444@mail.ru', '[\"ROLE_ADMIN\"]', '$2y$13$0vNFkMy7ilnAjfhvL1rRr.guGA0lHIBPZtd7zENgwLJweDwblM4Qq', 1, 54, NULL, NULL, '1-313-313-JAjHikwlH7K0Nl2I19QN', '11CP1988326848', 1, 315),
(12, '555@mail.ru', '[]', '$2y$13$K8mQVKG1F590VHRRvuzXUek7el0rfbJFmDfBR1YUFX5eChzb6fwFG', 1, 55, 'Uhfgft', NULL, '1-314-313-JAjHikwlH7K0Nl2I19QN', '12CP1927876844', 1, 316);

-- --------------------------------------------------------

--
-- Структура таблицы `wallet`
--

CREATE TABLE `wallet` (
  `id` int(11) NOT NULL,
  `usdt` int(11) DEFAULT NULL,
  `etherium` double DEFAULT NULL,
  `bitcoin` double DEFAULT NULL,
  `cometpoin` double DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `wallet`
--

INSERT INTO `wallet` (`id`, `usdt`, `etherium`, `bitcoin`, `cometpoin`, `user_id`) VALUES
(10, 5000, NULL, NULL, 0, 9),
(11, 5000, 0, 0, 0, 10),
(12, 0, 0, 0, 0, 11),
(13, 10000, 0, 0, 0, 12);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8AAF06ACA76ED395` (`user_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `pakages`
--
ALTER TABLE `pakages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `pakege`
--
ALTER TABLE `pakege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=317;

--
-- AUTO_INCREMENT для таблицы `personal_data`
--
ALTER TABLE `personal_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT для таблицы `pkege`
--
ALTER TABLE `pkege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `referral_network`
--
ALTER TABLE `referral_network`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `wallet`
--
ALTER TABLE `wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
-- Ограничения внешнего ключа таблицы `pkege`
--
ALTER TABLE `pkege`
  ADD CONSTRAINT `FK_8AAF06ACA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

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
