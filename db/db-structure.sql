-- phpMyAdmin SQL Dump
-- version 4.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 08, 2016 at 02:26 PM
-- Server version: 5.5.47-0+deb8u1
-- PHP Version: 7.0.6-1~dotdeb+8.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `LoL`
--

-- --------------------------------------------------------

--
-- Table structure for table `Champions`
--

CREATE TABLE `Champions` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Title` varchar(511) NOT NULL,
  `ChampKey` varchar(255) NOT NULL,
  `MainTag` varchar(255) NOT NULL,
  `SecondTag` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Leaderboard`
--

CREATE TABLE `Leaderboard` (
  `Region` varchar(255) NOT NULL,
  `Summoner` varchar(255) NOT NULL,
  `ChampionID` int(11) NOT NULL,
  `ChampionPoints` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Summoners`
--

CREATE TABLE `Summoners` (
  `Region` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `SummonerInfo` varchar(8091) NOT NULL,
  `MasteriesInfo` text NOT NULL,
  `lastTime` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Versions`
--

CREATE TABLE `Versions` (
  `Region` varchar(255) NOT NULL,
  `Version` varchar(255) NOT NULL,
  `Time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Champions`
--
ALTER TABLE `Champions`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `Versions`
--
ALTER TABLE `Versions`
  ADD PRIMARY KEY (`Region`),
  ADD UNIQUE KEY `Region` (`Region`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
