-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 29 2023 г., 22:37
-- Версия сервера: 8.0.29
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `adtech`
--

-- --------------------------------------------------------

--
-- Структура таблицы `clickthroughs`
--

CREATE TABLE `clickthroughs` (
  `click_id` int NOT NULL,
  `user_id` int NOT NULL,
  `offer_id` int NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `clickthroughs`
--

INSERT INTO `clickthroughs` (`click_id`, `user_id`, `offer_id`, `date_time`) VALUES
(1, 3, 1, '2023-05-29 09:59:30'),
(2, 3, 1, '2023-05-29 10:02:09'),
(3, 4, 2, '2023-05-29 10:09:29'),
(4, 4, 1, '2023-05-29 10:12:10'),
(5, 4, 3, '2023-05-29 10:20:40'),
(6, 7, 4, '2023-05-29 10:34:45');

-- --------------------------------------------------------

--
-- Структура таблицы `offers`
--

CREATE TABLE `offers` (
  `offer_id` int NOT NULL,
  `offer_name` varchar(50) NOT NULL,
  `offer_price` decimal(15,2) NOT NULL,
  `offer_url` varchar(150) NOT NULL,
  `offer_theme` varchar(150) NOT NULL,
  `offer_status` varchar(150) NOT NULL,
  `offer_creator_id` int NOT NULL,
  `offer_created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `offers`
--

INSERT INTO `offers` (`offer_id`, `offer_name`, `offer_price`, `offer_url`, `offer_theme`, `offer_status`, `offer_creator_id`, `offer_created_on`) VALUES
(1, 'VK переход', '9.00', 'http://vk.com', 'соцсеть', 'Yes', 2, '2023-05-29 09:56:34'),
(2, 'Авито', '6.00', 'http://avito.ru', 'объявления', 'Yes', 2, '2023-05-29 10:06:22'),
(3, 'РБК', '8.00', 'http://rbc.ru', 'СМИ', 'No', 5, '2023-05-29 10:19:10'),
(4, 'Пикабу', '10.00', 'http://picabu.ru', 'Развлечения', 'Yes', 6, '2023-05-29 10:31:43');

-- --------------------------------------------------------

--
-- Структура таблицы `subscriptions`
--

CREATE TABLE `subscriptions` (
  `user_id` int NOT NULL,
  `offer_id` int NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `subscriptions`
--

INSERT INTO `subscriptions` (`user_id`, `offer_id`, `created_on`) VALUES
(3, 1, '2023-05-29 09:58:57'),
(4, 1, '2023-05-29 10:11:51'),
(4, 3, '2023-05-29 10:20:17'),
(7, 4, '2023-05-29 10:34:26');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(250) NOT NULL,
  `user_password` varchar(150) NOT NULL,
  `user_hash` varchar(150) DEFAULT NULL,
  `user_role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`, `user_hash`, `user_role`) VALUES
(1, 'admin', 'admin@adtech.ru', '$2y$10$EBXxLpqsoe33BeT4KwiJQu.AYUtlmevR4Q1wMTj7LrhTG6bB/9JGC', '3f79d90834f6d0bff001d0e89c864a29', 'admin'),
(2, 'Joseph', 'joseph@mail.ru', '$2y$10$zXn1xNjo16fCN.7dNqZsS.ncmNyGZI92spu7vavBoKG/UySdie3R2', '6baa5cbb67a83da9574881420e65be7f', 'advertiser'),
(3, 'Bella', 'bellabella@gmail.com', '$2y$10$46j3PMLQIS3uLyg9rH4pCO8rFLwDBVkjvGQ7Z7CjbZmUk4lkoHbNe', '355ac8affac30a3c349686c2c3801396', 'webmaster'),
(4, 'Steven', 'stevies@hotmail.com', '$2y$10$Jk.P2E287KmuihiwPQupoel/SMm33FPfbygtjj9x48QeM5R6KFihu', '52d207f265cb389ea989b2572a87e5b8', 'webmaster'),
(5, 'Nataly', 'natty@gmail.com', '$2y$10$FYGvSVtBMgc0edIAakinMO6ZspBgIy0xtTXjGab2Hm0E.Db41WqkC', 'a9c23d1597785af24d89f5dca95e7ede', 'advertiser'),
(6, 'Patrick', 'patrick.evans@gmail.com', '$2y$10$/Me8cwdnr4rMRf5V/DzfW.WyARz.6mh1VD62xqEkuFRely9Yx/tVW', 'a4662ac3efb01a8be98f90622ba83089', 'advertiser'),
(7, 'Olga', 'olyalukyanova@inbox.ru', '$2y$10$VKNargoWD62HGipSZeQKquDo12dL6vholUCHPswZb0iwfUZ.GXiLa', '8f6b86442f94a69a02d2a313116e3064', 'webmaster');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `clickthroughs`
--
ALTER TABLE `clickthroughs`
  ADD PRIMARY KEY (`click_id`);

--
-- Индексы таблицы `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offer_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `clickthroughs`
--
ALTER TABLE `clickthroughs`
  MODIFY `click_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `offers`
--
ALTER TABLE `offers`
  MODIFY `offer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
