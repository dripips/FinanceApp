-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Окт 14 2023 г., 01:00
-- Версия сервера: 5.5.68-MariaDB
-- Версия PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `financeapp`
--

-- --------------------------------------------------------

--
-- Структура таблицы `expenses`
--

CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` datetime NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `amount`, `date`, `description`) VALUES
(1, 1, 1200.00, '2023-10-13 21:24:44', NULL),
(2, 1, 4500.00, '2023-10-13 22:10:18', NULL),
(3, 1, 150.00, '2023-10-13 22:50:11', NULL),
(4, 1, 56.00, '2023-10-13 23:00:59', NULL),
(5, 1, 124.00, '2023-10-14 00:38:28', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `incomes`
--

CREATE TABLE IF NOT EXISTS `incomes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` datetime NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `incomes`
--

INSERT INTO `incomes` (`id`, `user_id`, `amount`, `date`, `description`) VALUES
(1, 1, 15000.00, '2023-10-13 21:24:32', NULL),
(2, 1, 1500.00, '2023-10-13 22:51:43', NULL),
(3, 1, 2500.00, '2023-10-13 22:53:18', NULL),
(4, 1, 15.00, '2023-10-13 22:53:57', NULL),
(5, 1, 17.00, '2023-10-13 22:54:42', NULL),
(6, 1, 22.00, '2023-09-13 22:55:27', NULL),
(7, 1, 2.00, '2023-10-13 22:55:46', NULL),
(8, 1, 23.00, '2023-10-13 22:57:04', NULL),
(9, 1, 22.00, '2023-10-13 22:57:23', NULL),
(10, 1, 22.00, '2023-10-13 22:58:05', NULL),
(11, 1, 2.00, '2023-10-13 22:59:02', NULL),
(12, 1, 3.00, '2023-10-13 22:59:21', NULL),
(13, 1, 2.00, '2023-10-13 23:00:28', NULL),
(14, 1, 13.00, '2023-10-13 23:45:53', NULL),
(15, 1, 56.00, '2023-10-13 23:49:36', NULL),
(16, 1, 33.00, '2023-10-14 00:26:34', NULL),
(17, 1, 199.00, '2023-10-14 00:29:39', NULL),
(18, 1, 22.00, '2023-10-14 00:30:06', NULL),
(19, 1, 22.00, '2023-10-14 00:34:54', NULL),
(20, 1, 33.00, '2023-10-14 00:39:08', NULL),
(21, 1, 3223.00, '2023-10-14 00:39:29', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `username` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'drip', '$2y$10$f0nZ1crcn8EhoTkt2X9p8O5Q2fRfrLunK.knlj9Tzc1jB4yKMHz1G'),
(2, 'test', '$2y$10$kqptsT9CEw2D0Vl0SAY8Ied.Y4fmVkF5dvvxA7gPQfvwXs9CCwW7K');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `incomes`
--
ALTER TABLE `incomes`
  ADD CONSTRAINT `incomes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
