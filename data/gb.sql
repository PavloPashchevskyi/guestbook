-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Час створення: Сер 30 2016 р., 03:10
-- Версія сервера: 5.7.13-0ubuntu0.16.04.2
-- Версія PHP: 5.6.25-1+deb.sury.org~xenial+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `gb`
--

-- --------------------------------------------------------

--
-- Структура таблиці `tCats`
--

CREATE TABLE `tCats` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп даних таблиці `tCats`
--

INSERT INTO `tCats` (`cat_id`, `cat_name`) VALUES
(0, 'test'),
(1, 'IT'),
(2, 'economics'),
(3, 'politics'),
(4, 'culture'),
(5, 'sport');

-- --------------------------------------------------------

--
-- Структура таблиці `tUsers`
--

CREATE TABLE `tUsers` (
  `user_id` int(11) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `Category` int(11) NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Homepage` varchar(255) DEFAULT NULL,
  `MessageText` text NOT NULL,
  `MessageDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп даних таблиці `tUsers`
--

INSERT INTO `tUsers` (`user_id`, `UserName`, `Category`, `Email`, `Homepage`, `MessageText`, `MessageDate`) VALUES
(1, 'Pavlo Pashchevskyi', 0, 'googalltooth@gmail.com', 'http://www.vk.com/id11379573', 'Some message text line 1\r\nSome message text line 2', '2016-08-30 00:20:00'),
(2, 'Pavlo Pashchevskyi', 3, 'googalltooth@gmail.com', 'http://www.vk.com/id11379573', 'TEST POLITIC', '2016-08-30 01:45:11'),
(3, 'Pavlo Pashchevskyi', 2, 'googalltooth@gmail.com', 'http://www.vk.com/id11379573', 'TEST ECONOMIC ', '2016-08-30 01:45:59'),
(4, 'Pavlo Pashchevskyi', 2, 'googalltooth@gmail.com', 'http://www.vk.com/id11379573', 'TEST 3', '2016-08-30 02:47:20'),
(5, 'Pavlo Pashchevskyi', 2, 'googalltooth@gmail.com', 'http://www.vk.com/id11379573', 'TEST 3', '2016-08-30 02:48:27');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `tCats`
--
ALTER TABLE `tCats`
  ADD PRIMARY KEY (`cat_id`);

--
-- Індекси таблиці `tUsers`
--
ALTER TABLE `tUsers`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `Category` (`Category`);

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `tUsers`
--
ALTER TABLE `tUsers`
  ADD CONSTRAINT `tUsers_ibfk_1` FOREIGN KEY (`Category`) REFERENCES `tCats` (`cat_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
