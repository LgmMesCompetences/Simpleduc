-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 19 oct. 2021 à 16:16
-- Version du serveur : 10.3.29-MariaDB-0+deb10u1
-- Version de PHP : 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dbSimpleduc`
--

-- --------------------------------------------------------

--
-- Structure de la table `User`
--

CREATE TABLE `User` (
  `id` int(11) NOT NULL,
  `nom` varchar(25) NOT NULL,
  `prenom` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dateEmbauche` date NOT NULL,
  `fonction` int(11) NOT NULL,
  `lastLogin` datetime DEFAULT NULL,
  `numSecu` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `User`
--

INSERT INTO `User` (`id`, `nom`, `prenom`, `email`, `password`, `dateEmbauche`, `fonction`, `lastLogin`, `numSecu`) VALUES
(1, 'BECQUAERT', 'Rémi', 'remi.becquaert35@gmail.com', '$2y$10$p6cQYqTfe6lJcC95hUmbne853EGWfJ0D4KHyxmWDYAZsxI6wlfTD6', '2001-02-04', 5, '2021-10-19 16:08:11', 0),
(4, 'test', 'Comptable', 'comptable@test.fr', '$2y$10$DiHM8WLTAe83V99NlR61lee0rFKlLbS1tp48ft7lABZ0Qjepe08EC', '2021-10-04', 3, '2021-10-19 16:11:24', 0),
(7, 'Dehaffreingue', 'Marc', 'marc@monika.fr', '$2y$10$Qc.oydOgsti5glFaFDcjL.ByWQNSV3X.44BTKbyBvjy1n5SuRG.6q', '2021-10-06', 1, '2021-10-07 12:25:22', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fonction` (`fonction`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `User`
--
ALTER TABLE `User`
  ADD CONSTRAINT `User_ibfk_1` FOREIGN KEY (`fonction`) REFERENCES `Fonction` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
