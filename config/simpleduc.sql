-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 28, 2021 at 05:10 PM
-- Server version: 10.3.29-MariaDB-0+deb10u1
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simpleduc`
--

-- --------------------------------------------------------

--
-- Table structure for table `FichePaie`
--

CREATE TABLE `FichePaie` (
  `id` int(11) NOT NULL,
  `dateEmission` date NOT NULL,
  `cheminFichier` varchar(255) NOT NULL,
  `proprietaire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `id` int(11) NOT NULL,
  `nom` varchar(25) NOT NULL,
  `prenom` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dateEmbauche` date NOT NULL,
  `fonction` int(11) NOT NULL,
  `lastLogin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Fonction`
--

CREATE TABLE `Fonction` (
  `id` int(11) NOT NULL,
  `libelle` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Fonction`
--

INSERT INTO `Fonction` (`id`, `libelle`) VALUES
(1, 'Développeur'),
(2, 'Responsable Technique'),
(3, 'Comptable'),
(4, 'RH'),
(5, 'Directeur');

-- --------------------------------------------------------

--
-- Table structure for table `Qualification`
--

CREATE TABLE `Qualification` (
  `id` int(11) NOT NULL,
  `libelle` varchar(240) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `FichePaie`
--
ALTER TABLE `FichePaie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proprietaire` (`proprietaire`);

--
-- Indexes for table `Fonction`
--
ALTER TABLE `Fonction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Qualification`
--
ALTER TABLE `Qualification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fonction` (`fonction`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `FichePaie`
--
ALTER TABLE `FichePaie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Fonction`
--
ALTER TABLE `Fonction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Qualification`
--
ALTER TABLE `Qualification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `FichePaie`
--
ALTER TABLE `FichePaie`
  ADD CONSTRAINT `FichePaie_ibfk_1` FOREIGN KEY (`proprietaire`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Qualification`
--
ALTER TABLE `Qualification`
  ADD CONSTRAINT `Qualification_ibfk_1` FOREIGN KEY (`user`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `User`
--
ALTER TABLE `User`
  ADD CONSTRAINT `User_ibfk_1` FOREIGN KEY (`fonction`) REFERENCES `Fonction` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
