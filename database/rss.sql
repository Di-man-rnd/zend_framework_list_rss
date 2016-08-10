-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2016 at 07:38 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.5.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rss`
--

-- --------------------------------------------------------

--
-- Table structure for table `like`
--

CREATE TABLE `like` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `count` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `like`
--

INSERT INTO `like` (`id`, `name`, `count`) VALUES
(21, 'Автодилерыпридумалиспособборьбысоскрученнымпробегомумашин', 9),
(22, 'Натольяттинскомзаводе2месяцаневыпускалиавтомобилиDatsun', 3),
(23, 'Appleсообщилаосбояхвработесвоихсервисов', 1),
(24, 'Ученыепредставиликонцепциютелефонатрансформера', 4),
(25, 'Experianпредставилановуюсистемузащитыданных', 1),
(26, 'НароссийскомрынкечащепокупаютавтомобилиВкласса', 1),
(27, 'Какизменилсясреднийценникнаавтомобиливапреле', 3),
(28, 'КомпанияAudiпредставилановоепоколениекупеA5', 10),
(29, 'ВработесервисовAppleпроизошёлмасштабныйсбой', 2),
(30, 'ВСанктПетербургепредставилиновыйкроссоверHyundaiCreta', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `like`
--
ALTER TABLE `like`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
