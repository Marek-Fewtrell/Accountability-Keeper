-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 22, 2017 at 08:14 PM
-- Server version: 5.5.54-0+deb8u1
-- PHP Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `accountibilityKeeper`
--
CREATE DATABASE IF NOT EXISTS `accountibilityKeeper` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `accountibilityKeeper`;

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
CREATE TABLE IF NOT EXISTS `activities` (
`id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `description` text,
  `time` time DEFAULT NULL,
  `day` varchar(10) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `name`, `description`, `time`, `day`) VALUES
(1, 'act 1', 'it is act 1.', '08:00:00', 'daily'),
(2, 'act 2', 'act 2 desc', '07:00:00', 'daily'),
(3, 'act 3', 'act 3 desc', '10:00:00', 'weekly');

-- --------------------------------------------------------

--
-- Table structure for table `record`
--

DROP TABLE IF EXISTS `record`;
CREATE TABLE IF NOT EXISTS `record` (
  `userId` int(11) NOT NULL,
  `activityId` int(11) NOT NULL,
  `status` text,
  `date` datetime NOT NULL,
`id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `record`:
--   `activityId`
--       `activities` -> `id`
--   `userId`
--       `user` -> `id`
--

--
-- Dumping data for table `record`
--

INSERT INTO `record` (`userId`, `activityId`, `status`, `date`, `id`) VALUES
(2, 2, 'COmplete', '2017-02-22 09:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

DROP TABLE IF EXISTS `schedule`;
CREATE TABLE IF NOT EXISTS `schedule` (
`id` int(11) NOT NULL,
  `activityId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `startDate` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `schedule`:
--   `activityId`
--       `activities` -> `id`
--   `userId`
--       `user` -> `id`
--

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `activityId`, `userId`, `startDate`) VALUES
(1, 1, 1, NULL),
(2, 2, 1, NULL),
(3, 3, 2, NULL),
(4, 2, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL COMMENT 'username',
  `password` varchar(10) NOT NULL COMMENT 'password'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `password`) VALUES
(1, 'marek', 'pass'),
(2, 'bob', 'bob'),
(3, 'alice', 'alice');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `record`
--
ALTER TABLE `record`
 ADD PRIMARY KEY (`id`), ADD KEY `userId` (`userId`), ADD KEY `activityId` (`activityId`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
 ADD PRIMARY KEY (`id`), ADD KEY `activityId` (`activityId`,`userId`), ADD KEY `userId` (`userId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `record`
--
ALTER TABLE `record`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `record`
--
ALTER TABLE `record`
ADD CONSTRAINT `activityFK` FOREIGN KEY (`activityId`) REFERENCES `activities` (`id`),
ADD CONSTRAINT `userFK` FOREIGN KEY (`userId`) REFERENCES `user` (`id`);

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
ADD CONSTRAINT `activitiesLink` FOREIGN KEY (`activityId`) REFERENCES `activities` (`id`),
ADD CONSTRAINT `userLink` FOREIGN KEY (`userId`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
