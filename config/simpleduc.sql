SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `FichePaie` (
  `id` int(11) NOT NULL,
  `dateEmission` date NOT NULL,
  `cheminFichier` varchar(255) NOT NULL,
  `proprietaire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `Fonction` (
  `id` int(11) NOT NULL,
  `libelle` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `Qualification` (
  `id` int(11) NOT NULL,
  `libelle` varchar(240) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `User` (
  `id` int(11) NOT NULL,
  `nom` varchar(25) NOT NULL,
  `prenom` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dateEmbauche` date NOT NULL,
  `fonction` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `FichePaie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proprietaire` (`proprietaire`);

ALTER TABLE `Fonction`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `Qualification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`);

ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fonction` (`fonction`);


ALTER TABLE `FichePaie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `Fonction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `Qualification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `FichePaie`
  ADD CONSTRAINT `FichePaie_ibfk_1` FOREIGN KEY (`proprietaire`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `Qualification`
  ADD CONSTRAINT `Qualification_ibfk_1` FOREIGN KEY (`user`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `User`
  ADD CONSTRAINT `User_ibfk_1` FOREIGN KEY (`fonction`) REFERENCES `Fonction` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
