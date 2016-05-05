-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Loomise aeg: Mai 03, 2016 kell 05:29 PL
-- Serveri versioon: 5.6.15-log
-- PHP versioon: 5.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Andmebaas: `maatriksid`
--

-- --------------------------------------------------------

--
-- Tabeli struktuur tabelile `tüüp`
--

CREATE TABLE IF NOT EXISTS `tüüp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nimi` varchar(200) COLLATE utf8_estonian_ci NOT NULL,
  `kirjeldus` longtext COLLATE utf8_estonian_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci AUTO_INCREMENT=5 ;

--
-- Andmete tõmmistamine tabelile `tüüp`
--

INSERT INTO `tüüp` (`id`, `nimi`, `kirjeldus`) VALUES
(1, 'Determinant', 'Determinandi leidmine'),
(2, 'Pöördmaatriks', 'Pöördmaatriksi leidmine'),
(3, 'Lineaarvõrrandisüsteem', 'Lineaarvõrrandisüsteemi üldlahendi leidmine'),
(4, 'Astak', 'Astaku leidmine');

-- --------------------------------------------------------

--
-- Tabeli struktuur tabelile `viited`
--

CREATE TABLE IF NOT EXISTS `viited` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `pealkiri` varchar(400) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id` (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci AUTO_INCREMENT=4 ;

--
-- Andmete tõmmistamine tabelile `viited`
--

INSERT INTO `viited` (`Id`, `pealkiri`) VALUES
(1, 'TÜ Algebra I, praktikumiülesannete kogu 2015 a. kevadsemester\n'),
(2, 'TÜ Algebra I 1. järeltöö, variant A, 24.04.2014'),
(3, 'TÜ Algebra I 1. kontrolltöö, variant B, 10. aprill 2014');

-- --------------------------------------------------------

--
-- Tabeli struktuur tabelile `ülesanne`
--

CREATE TABLE IF NOT EXISTS `ülesanne` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `sisu` mediumtext COLLATE utf8_estonian_ci NOT NULL,
  `tüüp` int(11) NOT NULL,
  `sammud` int(11) DEFAULT NULL,
  `vastus` mediumtext COLLATE utf8_estonian_ci,
  `peida_kataloogist` int(11) NOT NULL DEFAULT '0',
  `allikas` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci AUTO_INCREMENT=114 ;

--
-- Andmete tõmmistamine tabelile `ülesanne`
--

INSERT INTO `ülesanne` (`Id`, `sisu`, `tüüp`, `sammud`, `vastus`, `peida_kataloogist`, `allikas`) VALUES
(1, '3 2 7 5\r\n0 6 4 3\r\n0 6 4 3\r\n0 1 1 2', 1, NULL, '0', 0, NULL),
(2, '3 2 7 5 1 1 1 1 1 1 1\r\n2 6 4 3 1 1 1 1 3 1 1\r\n2 6 4 3 1 4 132 1 1 1 1\r\n2 6 4 3 3 1 1 1 2 1 1\r\n2 6 4 3 1 1 4 1 1 1 1\r\n2 6 4 3 1 1 1 1 1 1 1\r\n0 1 1 2 1 1 1 1 -2 1 1\r\n3 1 1 2 1 1 1 1 1 1 1\r\n0 5 1 3 1 1 1 9 1 1 1\r\n0 1 1 2 1 1 1 1 1 1 1\r\n0 1 1 2 1 1 1 1 1 13 1', 1, 34, '0', 0, NULL),
(3, '-4 3 -1\r\n-2 4 2\r\n1 10 1', 1, 6, '100', 0, 1),
(4, '-2 3 1\r\n3 6 2\r\n1 2 1', 2, NULL, '-2/7 1/7 0\r\n1/7 3/7 -1\r\n0 -1 3', 0, 1),
(5, '2 -3 5 7 1\r\n4 -6 2 3 2\r\n2 -3 -11 -15 1', 3, NULL, NULL, 0, 1),
(6, '1 -2\r\n-3 6\r\n5 -10', 4, NULL, '1', 0, 1),
(7, '73', 1, 5, '73', 0, NULL),
(8, '1 0 0 0\r\n0 1 0 0\r\n0 0 1 0\r\n0 0 0 1', 2, NULL, '1 0 0 0\r\n0 1 0 0\r\n0 0 1 0\r\n0 0 0 1', 0, NULL),
(9, '2 3 6 7 3 7\r\n0 0 3 6 4 3\r\n0 0 0 0 0 0', 3, NULL, NULL, 0, NULL),
(10, '1 2 5\r\n0 4 2\r\n0 3 5\r\n0 0 3\r\n0 0 0', 4, NULL, '3', 0, NULL),
(11, '1/2 2 3 98/1002\n-5/3 2/7 -30 1\n0/3 -39 9/4 7\n0.4 -23/7 -0.33333 99999', 1, 2, '-38216958.585422985', 0, NULL),
(12, '3000 -5000 -2000 2\r\n-4 7 4 4/1000\r\n4 -9 -3 7/1000\r\n2 -6 -3 2/1000', 1, 10, '27', 0, 2),
(13, '2 4 2 2 0\r\n2 1 3 4 0\r\n3 1 4 4 0\r\n6 2 2 4 1\r\n6 3 9 13 0', 1, 14, '-4', 0, 3),
(14, '27 0\r\n0 1', 1, 1, '27', 0, NULL),
(15, '1 0 0 -2 2\r\n0 4 0 1 0\r\n0 0 -1 0 0\r\n0 0 0 0 -3\r\n0 5 0 0 0', 1, 5, '15', 0, 1),
(16, '1 3 0 -2\r\n1 0 4 3\r\n0 -1 1 5\r\n1 2 0 1', 1, 5, '-12', 0, 1),
(17, '35 59 71 52\r\n42 70 77 54\r\n49 68 72 52\r\n29 49 65 50', 1, 5, '-1010', 0, 1),
(18, '1 30 94 46 14 2\r\n0 7 6 9 4 0\r\n0 0 3 5 0 0\r\n0 0 2 3 0 0\r\n0 5 1 4 3 0\r\n2 7 47 23 15 1', 1, 5, '3', 0, 1),
(20, '1 -3 2 -2\r\n-1 3 -4 1\r\n-4 -3 -1 0\r\n4 -1 1 -3', 2, NULL, '-41/2 -19/2 15/2 21/2\r\n45/2 21/2 -17/2 -23/2\r\n29/2 13/2 -11/2 -15/2\r\n-30 -14 11 15', 0, 1),
(21, '2 -1 1 2 -6\r\n1 5 -2 3 4\r\n3 4 -1 5 7\r\n3 -7 4 1 -7\r\n0 11 -5 4 -4', 4, 5, '3', 0, 1),
(22, '2 -3 5 7 1\r\n0 0 -8 -11 0\r\n0 0 -16 -22 0', 3, NULL, NULL, 0, NULL),
(23, '1 -1 1 0\r\n2 1 -1 0\r\n-4 -2 2 0', 3, 4, '0 C1 C1', 0, 1),
(24, '43', 2, 1, '1/43', 0, NULL),
(25, '25 25', 3, 1, '1', 0, NULL),
(26, '56', 4, 1, '1', 0, NULL),
(27, '0 1 1 0 0\r\n1 1 0 0 0\r\n1 0 1 0 0\r\n0 0 1 0 1\r\n1 0 0 1 1', 4, 10, '5', 0, 1),
(28, '128 256 384 512\r\n1/4 3/8 1/8 1/4\r\n1/64 1/64 1/64 -1/64\r\n2 0 -4 -12', 1, 0, '-1/2', 0, 1),
(29, '1 3 0 -2\r\n1 0 4 3\r\n0 -1 1 5\r\n1 2 0 1', 1, NULL, '-12', 0, 1),
(30, '1 0 0 -2 2\r\n0 4 0 1 0\r\n0 0 -1 0 0\r\n0 0 0 0 -3\r\n0 5 0 0 0', 1, NULL, '15', 0, 1),
(31, '5 3\r\n7 3', 1, 2, '1', 0, 1),
(32, '1 3\r\n-4 6', 1, 2, '15', 0, 1),
(33, '2 1 3\r\n5 3 2\r\n1 4 3', 1, NULL, '40', 0, 1),
(34, '-2 2 3\r\n-1 1 3\r\n2 0 1', 1, NULL, '6', 0, 1),
(35, '-4 3 -1\r\n-2 4 2\r\n1 10 1', 1, NULL, '100', 0, 1),
(36, '1 1 1\r\n1 2 3\r\n1 3 6', 1, NULL, '1', 0, 1),
(37, '-2 2 -3\r\n-1 1 3\r\n2 0 -1', 1, NULL, '18', 0, 1),
(38, '1 1 1 1\r\n1 -1 1 1\r\n1 1 -1 1\r\n1 1 1 -1', 1, NULL, '-8', 0, 1),
(39, '0 1 1 1\r\n0 0 1 1\r\n1 1 0 1\r\n1 1 1 0', 1, NULL, '-2', 0, 1),
(40, '3/2 -9/2 -3/2 -3\r\n5/3 -8/3 -2/3 -7/3\r\n4/3 -5/3 -1 -2/3\r\n7 -8 -4 -5', 1, NULL, '1', 0, 1),
(41, '27 44 40 55\r\n20 64 21 40\r\n13 -20 -13 24\r\n46 45 -55 84', 1, NULL, '1', 0, 1),
(42, '3/4 2 -1/2 -5\r\n1 -2 3/2 8\r\n5/6 -4/3 4/3 14/3\r\n2/5 -4/5 1/2 12/5', 1, NULL, '1', 0, 1),
(43, '1 3 2\r\n-1 2 -1\r\n0 1 2', 1, NULL, '9', 0, 1),
(44, '2 -5 4 3\r\n3 -4 7 5\r\n4 -9 8 5\r\n-3 2 -5 3', 1, NULL, '4', 0, 1),
(45, '3 -5 -2 2\r\n-4 7 4 4\r\n4 -9 -3 7\r\n2 -6 -3 2', 1, NULL, '27', 0, 1),
(46, '3 -3 -5 8\r\n-3 2 4 -6\r\n2 -5 -7 5\r\n-4 3 5 -6', 1, NULL, '18', 0, 1),
(47, '3 -3 -2 -5\r\n2 5 4 6\r\n5 5 8 7\r\n4 4 5 6', 1, NULL, '90', 0, 1),
(48, '3 -5 2 -4\r\n-3 4 -5 3\r\n-5 7 -7 5\r\n8 -8 5 -6', 1, NULL, '17', 0, 1),
(49, '1 2 -1 -0.002\r\n-3 8 0 -0.004\r\n2 2 -4 -0.003\r\n3000 8000 -1000 -6', 1, NULL, '-34', 0, 1),
(50, '1 10 100 1000 10000 100000\r\n0.1 2 30 400 5000 60000\r\n0 0.1 3 60 1000 15000\r\n0 0 0.1 4 100 2000\r\n0 0 0 0.1 5 150\r\n0 0 0 0 0.1 6', 1, NULL, '1', 0, 1),
(51, '9 7 6 8 5\r\n3 0 0 2 0\r\n5 3 0 4 0\r\n1 0 0 0 0\r\n7 5 4 6 0', 1, NULL, '120', 0, 1),
(52, '7 3 4\r\n1 2 1\r\n3 0 2', 1, NULL, '7', 0, 1),
(53, '1 -3 2\r\n4 5 6\r\n5 2 8', 1, NULL, '0', 0, 1),
(54, '0 1 0\r\n1 0 0\r\n0 0 1', 1, NULL, '-1', 0, 1),
(55, '1 2 0\r\n3 -4 5\r\n0 6 0', 1, NULL, '-30', 0, 1),
(56, '2 0 -1 3\r\n1 4 9 0\r\n-2 1 3 -1\r\n4 0 3 2', 1, NULL, '-83', 0, 1),
(57, '2 1 0 1\r\n3 2 4 2\r\n1 2 1 3\r\n0 3 1 1', 1, NULL, '45', 0, 1),
(58, '1 1 1 1\r\n1 2 1 2\r\n1 1 2 3\r\n1 1 1 4', 1, NULL, '3', 0, 1),
(59, '2 1 3 4 2\r\n6 2 1 4 1\r\n6 3 9 12 6\r\n2 1 3 4 1\r\n1 4 2 1 1', 1, NULL, '0', 0, 1),
(60, '1 2 3 4 5\r\n2 3 4 5 6\r\n3 4 5 6 7\r\n4 5 6 7 8\r\n5 6 7 8 9', 1, NULL, '0', 0, 1),
(61, '8 88 888\r\n8888 88888 888888\r\n8888888 88888888 888888888', 1, 6, '0', 0, 1),
(62, '5 -4\r\n-8 6', 2, 9, '-3 -2\r\n-4 -5/2', 0, 1),
(63, '2 2 -1\r\n2 -1 2\r\n-1 2 2', 2, NULL, '2/9 2/9 -1/9\r\n2/9 -1/9 2/9\r\n-1/9 2/9 2/9', 0, 1),
(64, '2 1\r\n1 1', 2, NULL, '1 -1\r\n-1 2', 0, 1),
(65, '1 1 0\r\n4 5 3\r\n1 0 2', 2, NULL, '2 -2/5 3/5\r\n-1 2/5 -3/5\r\n-1 1/5 1/5', 0, 1),
(66, '2 2 0 -1\r\n0 3 -1 -2\r\n0 0 1 -2\r\n0 0 0 1', 2, NULL, '1/2 -1/3 -1/3 -5/6\r\n0 1/3 1/3 4/3\r\n0 0 1 2\r\n0 0 0 1', 0, 1),
(67, '2 -3 1\r\n4 3 -2\r\n1 2 -1', 2, NULL, '1 -1 3\r\n2 -3 8\r\n5 -7 18', 0, 1),
(68, '1 4 2\r\n2 3 2\r\n1 0 -1', 2, NULL, '-3/7 4/7 2/7\r\n4/7 -3/7 2/7\r\n-3/7 4/7 -5/7', 0, 1),
(69, '1 4 -2\r\n4 -1 3\r\n-2 3 4', 2, NULL, '13/121 2/11 -10/121\r\n2/11 0 1/11\r\n-10/121 1/11 17/121', 0, 1),
(70, '3 -9 1\r\n0 9 -6\r\n-3 -3 7', 2, NULL, NULL, 0, 1),
(71, '0 1 1 1\r\n-1 0 1 1\r\n-1 -1 0 1\r\n-1 -1 -1 0', 2, NULL, '0 -1 1 -1\r\n1 0 -1 1\r\n-1 1 0 -1\r\n1 -1 1 0', 0, 1),
(72, '1 2 2 1\r\n1 2 3 -1\r\n2 4 5 0', 4, NULL, '2', 0, 1),
(73, '0 1 1 0 0\r\n1 1 0 0 0\r\n1 0 1 0 0\r\n0 0 1 0 1\r\n1 0 0 1 1', 4, NULL, '5', 0, 1),
(74, '1 1 0 0 1\r\n2 1 0 1 0\r\n3 1 1 0 0\r\n3 2 0 1 1\r\n4 2 1 0 1', 4, NULL, '3', 0, 1),
(75, '2 1 4 -4 7\r\n0 0 5 -7 9\r\n2 1 -1 3 -2\r\n2 1 9 -11 16\r\n8 4 1 5 1', 4, NULL, '2', 0, 1),
(76, '17 51 27 31\r\n93 25 14 121\r\n1 2 1 -1\r\n35 104 55 61', 4, NULL, '3', 0, 1),
(77, '2 -1 1 -1 1', 3, NULL, NULL, 0, 1),
(78, '0 0 1 1 2\r\n0 1 -1 1 3\r\n1 1 0 0 3\r\n-1 1 -1 0 -2', 3, NULL, NULL, 0, 1),
(79, '1 0 1\r\n0 3 6', 3, NULL, NULL, 0, 1),
(80, '1 0 0 0\r\n0 2 0 0\r\n0 0 0 1', 3, NULL, NULL, 0, 1),
(81, '1 0 2 3\r\n0 1 1 2\r\n0 0 0 1', 3, NULL, NULL, 0, 1),
(82, '0 0 1 2\r\n1 0 0 3\r\n0 2 0 4', 3, NULL, NULL, 0, 1),
(83, '2 -3 0 0 2\r\n0 0 3 0 1\r\n0 0 0 3 1', 3, NULL, NULL, 0, 1),
(84, '2 1 3 2 4 1\r\n0 0 0 0 0 0\r\n0 0 0 0 0 0', 3, NULL, NULL, 0, 1),
(85, '1 -2 3\r\n2 -4 6', 3, NULL, NULL, 0, 1),
(86, '1 -2 3\r\n2 -4 5', 3, NULL, NULL, 0, 1),
(87, '1 2 -1 5\r\n3 -1 1 -4', 3, NULL, NULL, 0, 1),
(88, '1 2 -1 1\r\n3 6 -3 2', 3, NULL, NULL, 0, 1),
(89, '2 3 -1 0\r\n1 -2 4 9\r\n0 1 1 2', 3, NULL, NULL, 0, 1),
(90, '2 3 -1 -1\r\n1 2 -4 9\r\n-1 -12 14 1', 3, NULL, NULL, 0, 1),
(91, '2 1 1 1 3\r\n3 4 -1 -1 2\r\n1 3 -1 1 4\r\n5 -3 6 3 5', 3, NULL, NULL, 0, 1),
(92, '1 2 -1 -3 4 2\r\n2 -1 3 2 -1 4\r\n1 4 2 -5 3 6\r\n1 15 6 -19 9 2', 3, NULL, NULL, 0, 1),
(93, '3 1 1 -2 3 4\r\n2 3 -2 1 -4 5\r\n1 2 3 4 1 3\r\n1 -2 3 -3 7 -1', 3, NULL, NULL, 0, 1),
(94, '-1 2 1 -1 3\r\n0 4 2 -2 1', 3, NULL, NULL, 0, 1),
(95, '4 -6 7 7\r\n6 -9 1 8', 3, NULL, NULL, 0, 1),
(96, '1 -1 0\r\n2 -2 0', 3, NULL, NULL, 0, 1),
(97, '1 -1 1 0\r\n2 1 -1 0', 3, NULL, NULL, 0, 1),
(98, '1 -2 1 -1 1 0\r\n1 3 -2 3 -4 0\r\n2 -5 1 -2 2 0\r\n4 -4 0 0 -1 0', 3, NULL, NULL, 0, 1),
(99, '3 2 2 2 2 0\r\n3 2 3 1 -1 0\r\n6 4 3 5 7 0\r\n6 4 5 3 1 0', 3, NULL, NULL, 0, 1),
(100, '2 4 6 0\r\n3 6 9 0\r\n1 1 -4 0\r\n0 1 2 0', 3, NULL, NULL, 0, 1),
(101, '2 2 -1 1 0\r\n4 3 -1 2 0\r\n8 5 -3 4 0\r\n3 3 -2 2 0', 3, NULL, NULL, 0, 1),
(102, '1 1 1 1 0\r\n1 1 -1 -2 0\r\n1 1 -7 -11 0\r\n1 1 -9 -14 0', 3, NULL, NULL, 0, 1),
(103, '1 2 3 1 0\r\n1 -2 1 1 0\r\n3 -3 0 -7 0\r\n0 4 2 5 0', 3, NULL, NULL, 0, 1),
(104, '5 3 5 12 10\r\n2 2 3 5 4\r\n1 7 9 4 2', 3, NULL, NULL, 0, 1),
(105, '-9 6 7 10 3\r\n-6 4 2 3 2\r\n-3 2 -11 -15 1', 3, NULL, NULL, 0, 1),
(106, '-9 10 3 7 7\r\n-4 7 1 3 5\r\n7 5 -4 -6 3', 3, NULL, NULL, 0, 1),
(107, '12 9 3 10 13\r\n4 3 1 2 3\r\n8 6 2 5 7', 3, NULL, NULL, 0, 1),
(108, '-6 9 3 2 4\r\n-2 3 5 4 2\r\n-4 6 4 3 3', 3, NULL, NULL, 0, 1),
(109, '8 -16 9 1\r\n-6 7 -3 -2\r\n7 -13 7 1', 3, NULL, NULL, 0, 1),
(110, '8 -16 9 3\r\n-6 7 -3 -1\r\n7 -13 7 2', 3, NULL, NULL, 0, 1),
(111, '8 -16 9 2\r\n-6 7 -3 1\r\n7 -13 7 2', 3, NULL, NULL, 0, 1),
(112, '3 -9 1\r\n0 9 -6\r\n-3 -3 7', 2, NULL, '', 0, 1),
(113, '2 3 5\r\n4 6 12', 3, 1, NULL, 0, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
