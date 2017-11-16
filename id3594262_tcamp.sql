-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 16, 2017 at 12:07 PM
-- Server version: 10.1.24-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id3594262_tcamp`
--

-- --------------------------------------------------------

--
-- Table structure for table `journeys`
--

CREATE TABLE `journeys` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `startLocationX` int(11) NOT NULL,
  `startLocationY` int(11) NOT NULL,
  `endLocationX` int(11) NOT NULL,
  `endLocationY` int(11) NOT NULL,
  `goingDate` datetime NOT NULL,
  `seats` int(11) NOT NULL,
  `genderPrefer` tinyint(1) NOT NULL,
  `carDescription` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `journeys`
--

INSERT INTO `journeys` (`id`, `userId`, `startLocationX`, `startLocationY`, `endLocationX`, `endLocationY`, `goingDate`, `seats`, `genderPrefer`, `carDescription`) VALUES
(1, 1, 10, 0, 15, 0, '2017-11-12 00:00:00', 3, 0, 'test desc'),
(2, 1, 33, 0, 77, 0, '2017-11-14 00:00:00', 1, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `rides`
--

CREATE TABLE `rides` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `journeyId` int(11) NOT NULL,
  `meetingLocationX` int(11) NOT NULL,
  `meetingLocationY` int(11) NOT NULL,
  `orderStatus` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rides`
--

INSERT INTO `rides` (`id`, `userId`, `journeyId`, `meetingLocationX`, `meetingLocationY`, `orderStatus`) VALUES
(1, 2, 1, 30, 0, 1),
(2, 2, 2, 33, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `birthdate` date NOT NULL,
  `address` varchar(255) NOT NULL,
  `userType` int(11) NOT NULL COMMENT '1 : Normal , 2 : admin',
  `image` varchar(255) NOT NULL,
  `phone` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `fullname`, `gender`, `birthdate`, `address`, `userType`, `image`, `phone`) VALUES
(1, 'driver1', 'driver1', 'driver', 0, '1996-06-14', 'Jenin Haifa st', 1, '', 568831888),
(2, 'driver3', 'driver3', 'Ahmad', 1, '1980-01-02', 'palestine', 1, 'http://', 123333);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `journeys`
--
ALTER TABLE `journeys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId_i` (`userId`);

--
-- Indexes for table `rides`
--
ALTER TABLE `rides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId_i` (`userId`),
  ADD KEY `journeyId` (`journeyId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `journeys`
--
ALTER TABLE `journeys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `rides`
--
ALTER TABLE `rides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
