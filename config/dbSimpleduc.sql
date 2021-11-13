-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : sam. 13 nov. 2021 à 16:09
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
-- Structure de la table `FichePaie`
--

CREATE TABLE `FichePaie` (
  `id` int(11) NOT NULL,
  `proprietaire` int(11) NOT NULL,
  `dateEmission` date NOT NULL,
  `cheminFichier` varchar(255) NOT NULL,
  `heuresPayees` int(11) NOT NULL,
  `dateDebutPaie` date NOT NULL,
  `dateFinPaie` date NOT NULL,
  `tauxHoraire` double NOT NULL,
  `tauxCompIncap` double NOT NULL,
  `tauxCompSante` double NOT NULL,
  `tauxSecuPla` double NOT NULL,
  `tauxSecuDepla` double NOT NULL,
  `tauxCompTrancheFirst` double NOT NULL,
  `tauxCSGDeducIR` double NOT NULL,
  `tauxCSGnonDeducIR` double NOT NULL,
  `secuMaladie` double NOT NULL,
  `accidentTra` double NOT NULL,
  `famille` double NOT NULL,
  `chomage` double NOT NULL,
  `autresContrib` double NOT NULL,
  `prevoyance` double NOT NULL,
  `cotisStat` double NOT NULL,
  `exoEmp` double NOT NULL,
  `exoRegul` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `Fonction`
--

CREATE TABLE `Fonction` (
  `id` int(11) NOT NULL,
  `libelle` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `Qualification`
--

CREATE TABLE `Qualification` (
  `id` int(11) NOT NULL,
  `libelle` varchar(240) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `numSecu` varchar(15) NOT NULL,
  `otpKey` varchar(255) DEFAULT NULL,
  `dfaType` enum('email','otp') NOT NULL DEFAULT 'email'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `FichePaie`
--
ALTER TABLE `FichePaie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proprietaire` (`proprietaire`) USING BTREE;

--
-- Index pour la table `Fonction`
--
ALTER TABLE `Fonction`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Qualification`
--
ALTER TABLE `Qualification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`);

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
-- AUTO_INCREMENT pour la table `FichePaie`
--
ALTER TABLE `FichePaie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Fonction`
--
ALTER TABLE `Fonction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Qualification`
--
ALTER TABLE `Qualification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Qualification`
--
ALTER TABLE `Qualification`
  ADD CONSTRAINT `Qualification_ibfk_1` FOREIGN KEY (`user`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `User`
--
ALTER TABLE `User`
  ADD CONSTRAINT `User_ibfk_1` FOREIGN KEY (`fonction`) REFERENCES `Fonction` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
