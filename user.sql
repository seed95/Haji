-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 07, 2021 at 05:27 PM
-- Server version: 10.3.27-MariaDB-cll-lve
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `khoshkba_bot`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(15) NOT NULL,
  `step` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `text` varchar(1000) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `step`, `text`) VALUES
(10036450, 'none', 'null'),
(66469061, 'resavephone', '09103583256'),
(118205890, 'resavephone', '09383843814'),
(128495437, 'none', 'null'),
(160600166, 'none', 'null'),
(201391427, 'none', 'null'),
(350486584, 'none', 'null'),
(416699090, 'none', 'null'),
(477628584, 'none', '09142323203'),
(741931691, 'none', 'null'),
(767440644, 'none', '09103241732'),
(878865272, 'none', 'null'),
(897617082, 'none', 'null'),
(1053059842, 'none', 'null'),
(1053736483, 'resavephone', '09134109893'),
(1147113599, 'resavephone', 'null'),
(1199000735, 'none', 'null'),
(1320786408, 'none', 'null'),
(1390375718, 'none', 'null'),
(1420584925, 'none', 'null');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD UNIQUE KEY `id` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
