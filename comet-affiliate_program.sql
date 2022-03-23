-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:8889
-- Время создания: Мар 23 2022 г., 11:14
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
('DoctrineMigrations\\Version20220322133513', '2022-03-22 13:35:22', 96);

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
  `client_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `list_referral_networks`
--

INSERT INTO `list_referral_networks` (`id`, `name`, `referral_networks_id`, `owner_id`, `owner_name`, `pakege_id`, `unique_code`, `network_code`, `client_code`) VALUES
(23, 'My network 1', NULL, 11, 'Nikos', 48, 'qZg78nGIOCPirhHijUxY', '48-qZg78nGIOCPirhHijUxY', '32010652754'),
(24, 'My network 2', NULL, 2, 'Nik', 48, 'qZg78nGIOCPirhHijUxY', '48-qZg78nGIOCPirhHijUxY', '32010652754'),
(25, 'My network 3', NULL, 3, 'Moro', 48, 'qZg78nGIOCPirhHijUxY', '48-qZg78nGIOCPirhHijUxY', '32010652754'),
(26, 'My network 4', NULL, 2, 'Vlad', 48, 'qZg78nGIOCPirhHijUxY', '48-qZg78nGIOCPirhHijUxY', '32010652754');

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
  `referral_networks_id` int(11) DEFAULT NULL,
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
(48, 'Starter', 3, 100, NULL, '32010652754', 2000, 'активирован', 'qZg78nGIOCPirhHijUxY', NULL),
(90, 'Basic', 2, 500, NULL, '21497043565', 10000, 'активирован', 'ENoTIMe44u4hc6u92Yib', '23-48-48-qZg78nGIOCPirhHijUxY'),
(91, 'Networker', 2, 1000, NULL, '21497043565', 20000, 'активирован', '7hpm0h27tBa5l4L6gq02', '23-48-48-qZg78nGIOCPirhHijUxY'),
(92, 'Basic', 2, 500, NULL, '21497043565', 10000, 'активирован', 'SgPmDrXAwi1pEwcnWta9', '23-90-48-qZg78nGIOCPirhHijUxY'),
(93, 'Networker', 2, 1000, NULL, '21497043565', 20000, 'активирован', 'jkyruQpjmcBIHXuDbEOK', '23-90-48-qZg78nGIOCPirhHijUxY'),
(94, 'Basic', 2, 500, NULL, '21497043565', 10000, 'активирован', 'oTKvW5cBu2q5RiZfeBuB', '23-91-48-qZg78nGIOCPirhHijUxY'),
(95, 'Networker', 2, 1000, NULL, '21497043565', 20000, 'активирован', 'RPLkD53K9OIjJFXnELIY', '23-91-48-qZg78nGIOCPirhHijUxY'),
(96, 'Starter', 2, 100, NULL, '21497043565', 2000, 'активирован', 'EgzHmQMrdBo4gYbDviyD', '23-92-48-qZg78nGIOCPirhHijUxY'),
(97, 'Starter', 2, 100, NULL, '21497043565', 2000, 'активирован', 'xj9ldUP62EsNjHacsIlN', '23-92-48-qZg78nGIOCPirhHijUxY'),
(98, 'Business', 2, 2500, NULL, '21497043565', 50000, 'активирован', 'qJHOvqNkeoLsAqrWMWtf', '23-92-48-qZg78nGIOCPirhHijUxY'),
(99, 'Basic', 2, 500, NULL, '21497043565', 10000, 'активирован', 'waetN1CSnMvpzviNvJV5', '23-96-48-qZg78nGIOCPirhHijUxY'),
(100, 'Networker', 2, 1000, NULL, '21497043565', 20000, 'активирован', 'wAeNT2XlVZA1T4OqfbRd', '23-96-48-qZg78nGIOCPirhHijUxY'),
(101, 'Starter', 2, 100, NULL, '21497043565', 2000, 'активирован', 'qCnbRITeS6u4NPcRUW2i', '23-97-48-qZg78nGIOCPirhHijUxY'),
(102, 'Starter', 2, 100, NULL, '21497043565', 2000, 'активирован', 'gxODYBDPaYQUyOuTWKN5', '23-96-48-qZg78nGIOCPirhHijUxY'),
(103, 'Basic', 2, 500, NULL, '21497043565', 10000, 'активирован', 'wn1627FnfRho5ErVCwNd', '23-97-48-qZg78nGIOCPirhHijUxY'),
(104, 'Starter', 2, 100, NULL, '21497043565', 2000, 'активирован', 'CVua2U3GSQQTwRObbZq7', '23-97-48-qZg78nGIOCPirhHijUxY'),
(105, 'Basic', 2, 500, NULL, '21497043565', 10000, 'активирован', 'hl2yVXU6fobe8zTYtxXY', '23-97-48-qZg78nGIOCPirhHijUxY'),
(106, 'Basic', 2, 500, NULL, '21497043565', 10000, 'активирован', 'qqmM1kjS53O6EOwekJd1', '23-97-48-qZg78nGIOCPirhHijUxY'),
(107, 'Starter', 2, 100, NULL, '21497043565', 2000, 'активирован', '4TwFv4oxpwyxlqDsr2lW', '23-97-48-qZg78nGIOCPirhHijUxY'),
(108, 'Networker', 2, 1000, NULL, '21497043565', 20000, 'активирован', 'DOTfWDbO1qvewTM55xDy', '23-103-48-qZg78nGIOCPirhHijUxY'),
(109, 'Business', 2, 2500, NULL, '21497043565', 50000, 'активирован', 'AuI4WXiODh02OWUJwivA', '23-103-48-qZg78nGIOCPirhHijUxY'),
(110, 'Basic', 2, 500, NULL, '21497043565', 10000, 'активирован', 'jyNP33yG5Kv3jNhTZ1FG', '23-103-48-qZg78nGIOCPirhHijUxY'),
(111, 'Basic', 2, 500, NULL, '21497043565', 10000, 'активирован', '1RsUQBtPjh3SlAgRXKPb', '23-103-48-qZg78nGIOCPirhHijUxY'),
(112, 'Basic', 2, 500, NULL, '21497043565', 10000, 'активирован', 'FF19HRzFeib0sFnagMDc', '23-103-48-qZg78nGIOCPirhHijUxY'),
(113, 'Business', 2, 2500, NULL, '21497043565', 50000, 'активирован', 'NfjNzs5CALsi3RC3D5XO', '23-103-48-qZg78nGIOCPirhHijUxY'),
(117, 'Basic', 2, 500, NULL, '21497043565', 10000, 'активирован', 'EZYi7LlL5vlfVPb8tIXc', '23-102-48-qZg78nGIOCPirhHijUxY'),
(118, 'Networker', 2, 1000, NULL, '21497043565', 20000, 'активирован', 'keCqNTBMu2bO35jVyPfW', '23-102-48-qZg78nGIOCPirhHijUxY');

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
  `koef` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `referral_network`
--

INSERT INTO `referral_network` (`id`, `name`, `user_id`, `user_status`, `personal_data_id`, `balance`, `network_code`, `member_code`, `pakege_id`, `network_id`, `user_referral_id`, `network_referral_id`, `reward`, `koef`) VALUES
(27, 'Anon', 11, 'owner', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-48-48-qZg78nGIOCPirhHijUxY', 48, 23, NULL, NULL, 1553, NULL),
(96, '1', 2, 'left', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-90-48-qZg78nGIOCPirhHijUxY', 90, 23, 11, 27, 1387, NULL),
(97, '2', 2, 'right', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-91-48-qZg78nGIOCPirhHijUxY', 91, 23, 11, 27, 1593, NULL),
(98, '3', 11, 'left', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-92-48-qZg78nGIOCPirhHijUxY', 92, 23, 2, 96, 1496, NULL),
(99, '4', 2, 'right', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-93-48-qZg78nGIOCPirhHijUxY', 93, 23, 2, 96, 1585, NULL),
(100, '5', 2, 'left', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-94-48-qZg78nGIOCPirhHijUxY', 94, 23, 2, 97, 1002, NULL),
(101, '6', 2, 'right', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-95-48-qZg78nGIOCPirhHijUxY', 95, 23, 2, 97, 1703, NULL),
(102, '7', 3, 'left', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-96-48-qZg78nGIOCPirhHijUxY', 96, 23, 2, 98, 1047, 0.0010341261633919),
(103, '8', 2, 'right', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-97-48-qZg78nGIOCPirhHijUxY', 97, 23, 2, 98, 1802, NULL),
(104, '9', 2, 'left', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-98-48-qZg78nGIOCPirhHijUxY', 98, 23, 2, 98, 770, 0.0010298661174047),
(105, '10', 2, 'right', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-99-48-qZg78nGIOCPirhHijUxY', 99, 23, 2, 102, 1932, NULL),
(106, '12', 2, 'left', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-100-48-qZg78nGIOCPirhHijUxY', 100, 23, 2, 102, 655, 0.0019960079840319),
(107, '13', 3, 'right', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-101-48-qZg78nGIOCPirhHijUxY', 101, 23, 2, 103, 2046, NULL),
(108, '14', 2, 'left', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-102-48-qZg78nGIOCPirhHijUxY', 102, 23, 2, 102, 689, 0.0010298661174047),
(109, '15', 2, 'right', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-103-48-qZg78nGIOCPirhHijUxY', 103, 23, 2, 103, 1677, NULL),
(110, 'ii', 2, 'left', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-104-48-qZg78nGIOCPirhHijUxY', 104, 23, 2, 103, 407, 0.0031023784901758),
(111, 'rr', 2, 'right', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-105-48-qZg78nGIOCPirhHijUxY', 105, 23, 2, 103, 2132, 1),
(112, 'u', 2, 'left', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-106-48-qZg78nGIOCPirhHijUxY', 106, 23, 2, 103, 281, 0.066184074457084),
(113, 'gfgf', 2, 'right', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-107-48-qZg78nGIOCPirhHijUxY', 107, 23, 2, 103, 2132, NULL),
(114, 'g', 2, 'left', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-108-48-qZg78nGIOCPirhHijUxY', 108, 23, 2, 109, 156, 0.15925542916236),
(115, 'sdsdsd', 2, 'right', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-109-48-qZg78nGIOCPirhHijUxY', 109, 23, 2, 109, 1427, 1),
(116, 'g', 2, 'left', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-110-48-qZg78nGIOCPirhHijUxY', 110, 23, 2, 109, 72, 0.25025853154085),
(120, 'fdfdf', 2, 'right', NULL, 0, '48-qZg78nGIOCPirhHijUxY', '23-117-48-qZg78nGIOCPirhHijUxY', 117, 23, 2, 108, NULL, 0.99800399201597),
(121, 'kjkjkj', 2, 'left', NULL, 690, '48-qZg78nGIOCPirhHijUxY', '23-118-48-qZg78nGIOCPirhHijUxY', 118, 23, 2, 108, NULL, 1);

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
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `personal_data_id`, `username`, `role`) VALUES
(2, 'mvlju977@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$OKjO/hiW3BLHx0Q.YabVz.h2z2tJa2cxrWhSC0axTSHO4YKrSzKPy', 1, 40, NULL, 'ROLE_ADMIN'),
(3, 'asdf@gmail.com', '[\"ROLE_SUPER_ADMIN\"]', '$2y$13$LqI6EzlLI5/34oDlfc82wePoTLSZ4jIKDi58MwIojBmfVQgcB7cGm', 1, 35, 'vandelshtam', 'ROLE_ADMIN'),
(11, 'bbb123@mail.ru', '[\"ROLE_ADMIN\"]', '$2y$13$iGmjxYHSe.q/3Om/K26Ww.dKCMJO9RKxGwCIS6F8mikfiJGGm9HqS', 1, 41, 'Nikos', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `pakages`
--
ALTER TABLE `pakages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `pakege`
--
ALTER TABLE `pakege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
