-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Апр 21 2017 г., 11:56
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `advalorem`
--

-- --------------------------------------------------------

--
-- Структура таблицы `rem_ad_address`
--

CREATE TABLE IF NOT EXISTS `#__ad_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ',
  `CLIENT` int(11) DEFAULT NULL COMMENT 'Связь с таблицей клиентов',
  `COUNTRY` varchar(50) NOT NULL COMMENT 'Страна',
  `REGION` varchar(50) NOT NULL COMMENT 'Регион с типом',
  `CITY` varchar(50) NOT NULL COMMENT 'Город с типом',
  `ADDRESS` text NOT NULL COMMENT 'Адрес в городе',
  `GPS` varchar(30) NOT NULL COMMENT 'GPS координаты для показа на карте',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Адреса' AUTO_INCREMENT=244 ;

-- --------------------------------------------------------

--
-- Структура таблицы `rem_ad_client`
--

CREATE TABLE IF NOT EXISTS `#__ad_client` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ',
  `JUSER_ID` int(11) DEFAULT NULL COMMENT 'Связь с пользователем Joomla',
  `SIRNAME` varchar(30) NOT NULL COMMENT 'Фамилия',
  `NAME` varchar(30) NOT NULL COMMENT 'Имя',
  `PATRONYMIC` varchar(30) NOT NULL COMMENT 'Отчество',
  `GENDER` varchar(11) NOT NULL COMMENT 'Пол (M, F)',
  `BIRTH_DATE` date NOT NULL COMMENT 'Дата рождения',
  `EMAIL` varchar(100) NOT NULL COMMENT 'E-mail',
  `PHONE` varchar(24) NOT NULL COMMENT 'Телефон',
  `CITY` varchar(256) NOT NULL COMMENT 'Город',
  `PROFILE` varchar(30) NOT NULL COMMENT 'Тип профиля 1 – пациент, 0 – оператор',
  `PRICE` mediumint(9) DEFAULT NULL COMMENT 'Стоимость приема или часа',
  `DESCRIPTION` text COMMENT 'Краткое описание (255 симв))',
  `DESC_FULL` text COMMENT 'Подробное описание',
  `DESC_CONSULT` text COMMENT 'Описание специфики приема',
  `PHOTO` text COMMENT 'Название файла фотографии',
  `EXP` date DEFAULT NULL COMMENT 'Общий опыт работы (лет)',
  `EDUCATION` text COMMENT 'Образование',
  `COMPLETENESS` smallint(6) NOT NULL COMMENT 'Полнота заполненности анкеты в баллах',
  `BLOCKED` tinyint(4) NOT NULL COMMENT 'Признак блокировки: 1/0',
  `POINTS` int(11) NOT NULL DEFAULT '0' COMMENT 'Внутрисистемная валюта пользователей',
  `LIST` int(5) DEFAULT NULL COMMENT 'Ссылка на главный реестр',
  PRIMARY KEY (`ID`),
  KEY `PROFILE` (`PROFILE`),
  KEY `GENDER` (`GENDER`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Личная информация специалистов и пациентов' AUTO_INCREMENT=245 ;

-- --------------------------------------------------------

--
-- Структура таблицы `rem_ad_comments`
--

CREATE TABLE IF NOT EXISTS `#__ad_comments` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ',
  `UID` int(11) NOT NULL COMMENT 'Код специалиста к которому привязан комментарий',
  `UID_FROM` int(11) DEFAULT NULL COMMENT 'Код комментатора (если не анонимный)',
  `NAME_FROM` varchar(255) NOT NULL COMMENT 'Имя комментатора',
  `COMMTYPE` varchar(11) NOT NULL COMMENT 'Тип комментария (+, -, нейтральный)',
  `TEXT` text NOT NULL COMMENT 'Тест комментария',
  `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата комментария',
  `STATUS` varchar(11) DEFAULT NULL COMMENT 'Статус: проверен, удален и т.п.',
  `CONTACT` text COMMENT 'Контакт комментатора для верификации',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Комментарии для специалистов' AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Структура таблицы `rem_ad_country`
--

CREATE TABLE IF NOT EXISTS `#__ad_country` (
  `CODE` varchar(40) NOT NULL COMMENT 'ISO Код страны',
  `NAME` varchar(40) NOT NULL COMMENT 'Название',
  `ICO` text NOT NULL COMMENT 'Иконка флага',
  `REGION` varchar(80) DEFAULT NULL COMMENT 'Регион',
  `RCODE` varchar(40) DEFAULT NULL COMMENT 'Код региона',
  PRIMARY KEY (`CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Справочник стран и регионов';

-- --------------------------------------------------------

--
-- Структура таблицы `rem_ad_history`
--

CREATE TABLE IF NOT EXISTS `#__ad_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ',
  `EVENT` varchar(30) NOT NULL COMMENT 'Тип события',
  `EVENT_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата/время события',
  `ENTITY` varchar(30) NOT NULL COMMENT 'Тип объекта',
  `ENTITY_ID` int(11) NOT NULL COMMENT 'Ключ связанной сущности',
  `VALUE` text,
  `UID` int(11) DEFAULT NULL COMMENT 'Клиент - инициатор события',
  `EVENT_TEXT` text COMMENT 'Текст события, если индивидуален',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='История выполняемых операций в системе' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Структура таблицы `rem_ad_lists`
--

CREATE TABLE IF NOT EXISTS `#__ad_lists` (
  `ID` int(5) NOT NULL COMMENT 'Первичный ключ',
  `NAME` varchar(255) NOT NULL COMMENT 'Название',
  `ABRV` varchar(30) NOT NULL COMMENT 'Абревиатура',
  `DESCRIPTION` text NOT NULL COMMENT 'Описание',
  `URL` varchar(255) NOT NULL,
  `LAST_UPDATE` datetime NOT NULL COMMENT 'Дата последнего обновления',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Реестры специалистов';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
