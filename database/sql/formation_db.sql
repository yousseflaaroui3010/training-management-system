-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 04:59 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `formation_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cours`
--

CREATE TABLE `cours` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `content` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `audience` varchar(255) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `testIncluded` tinyint(1) DEFAULT 0,
  `testContent` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `sujet_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cours`
--

INSERT INTO `cours` (`id`, `name`, `content`, `description`, `audience`, `duration`, `testIncluded`, `testContent`, `logo`, `sujet_id`) VALUES
(1, 'Scrum Master', 'Contenu du cours Scrum Master', 'Formation complète Scrum Master', 'Chefs de projet, Développeurs', 3, 1, NULL, NULL, 5),
(2, 'Prince 2 Foundation', 'Contenu Prince 2', 'Formation Prince 2 niveau Foundation', 'Managers, Chefs de projet', 3, 1, NULL, NULL, 6),
(3, 'JEE Avancé', 'Développement JEE', 'Formation Java Enterprise Edition', 'Développeurs Java', 5, 0, NULL, NULL, 2),
(4, 'Hadoop Administration', 'Administration Hadoop', 'Gérer un cluster Hadoop', 'Administrateurs système', 4, 1, NULL, NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `domaines`
--

CREATE TABLE `domaines` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `domaines`
--

INSERT INTO `domaines` (`id`, `name`, `description`) VALUES
(1, 'Management', 'Cours de gestion et management'),
(2, 'Computer Science', 'Cours informatiques et technologies');

-- --------------------------------------------------------

--
-- Table structure for table `formateurs`
--

CREATE TABLE `formateurs` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formateurs`
--

INSERT INTO `formateurs` (`id`, `firstName`, `lastName`, `description`, `photo`) VALUES
(1, 'Ahmed', 'Benali', 'Expert en management de projet avec 15 ans d\'expérience', NULL),
(2, 'Marie', 'Dupont', 'Consultante certifiée Scrum Master', NULL),
(3, 'Karim', 'El Amrani', 'Architecte JEE senior', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `formations`
--

CREATE TABLE `formations` (
  `id` int(11) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `mode` enum('Presentiel','Distanciel') NOT NULL,
  `cours_id` int(11) DEFAULT NULL,
  `ville_id` int(11) DEFAULT NULL,
  `formateur_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formations`
--

INSERT INTO `formations` (`id`, `price`, `mode`, `cours_id`, `ville_id`, `formateur_id`) VALUES
(1, 5000.00, 'Presentiel', 1, 1, 2),
(2, 4500.00, 'Distanciel', 1, 1, 2),
(3, 6000.00, 'Presentiel', 2, 1, 1),
(4, 7500.00, 'Presentiel', 3, 2, 3),
(5, 5500.00, 'Distanciel', 4, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `formation_dates`
--

CREATE TABLE `formation_dates` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `formation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formation_dates`
--

INSERT INTO `formation_dates` (`id`, `date`, `formation_id`) VALUES
(1, '2025-06-15', 1),
(2, '2025-06-22', 1),
(3, '2025-07-01', 2),
(4, '2025-07-15', 3),
(5, '2025-08-01', 4);

-- --------------------------------------------------------

--
-- Table structure for table `inscriptions`
--

CREATE TABLE `inscriptions` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `company` varchar(100) DEFAULT NULL,
  `paid` tinyint(1) DEFAULT 0,
  `formation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inscriptions`
--

INSERT INTO `inscriptions` (`id`, `firstName`, `lastName`, `phone`, `email`, `company`, `paid`, `formation_id`) VALUES
(1, 'Meriem ', 'Benaatit', '0634567890', 'meriembenaatit@gmail.com', 'Capgemini Engineering', 0, 4),
(2, 'Youssef', 'Laaroui', '0634567890', 'yousseflaaroui1@gmail.com', 'Capgemini Engineering', 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `pays`
--

CREATE TABLE `pays` (
  `id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pays`
--

INSERT INTO `pays` (`id`, `value`) VALUES
(1, 'Maroc'),
(2, 'France'),
(3, 'Tunisie');

-- --------------------------------------------------------

--
-- Table structure for table `sujets`
--

CREATE TABLE `sujets` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `shortDescription` text DEFAULT NULL,
  `longDescription` text DEFAULT NULL,
  `individualBenefit` text DEFAULT NULL,
  `businessBenefit` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `domaine_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sujets`
--

INSERT INTO `sujets` (`id`, `name`, `shortDescription`, `longDescription`, `individualBenefit`, `businessBenefit`, `logo`, `domaine_id`) VALUES
(1, 'Management de Projet', 'Gestion efficace de projets', NULL, NULL, NULL, NULL, 1),
(2, 'IT', 'Technologies de l\'information', NULL, NULL, NULL, NULL, 2),
(3, 'Big Data', 'Analyse de données massives', NULL, NULL, NULL, NULL, 2),
(4, 'Réseau', 'Infrastructure réseau', NULL, NULL, NULL, NULL, 2),
(5, 'Scrum', 'Méthodologie Agile Scrum', 'Formation complète sur la méthodologie Scrum', 'Améliorer vos compétences en gestion de projet agile', 'Augmenter la productivité de vos équipes', NULL, 1),
(6, 'Prince 2', 'Certification Prince 2', 'Formation certifiante Prince 2', 'Obtenir une certification reconnue', 'Standardiser la gestion de projets', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `villes`
--

CREATE TABLE `villes` (
  `id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  `pays_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `villes`
--

INSERT INTO `villes` (`id`, `value`, `pays_id`) VALUES
(1, 'Casablanca', 1),
(2, 'Rabat', 1),
(3, 'Fès', 1),
(4, 'Paris', 2),
(5, 'Lyon', 2),
(6, 'Tunis', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cours`
--
ALTER TABLE `cours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sujet_id` (`sujet_id`);

--
-- Indexes for table `domaines`
--
ALTER TABLE `domaines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `formateurs`
--
ALTER TABLE `formateurs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `formations`
--
ALTER TABLE `formations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cours_id` (`cours_id`),
  ADD KEY `ville_id` (`ville_id`),
  ADD KEY `formateur_id` (`formateur_id`);

--
-- Indexes for table `formation_dates`
--
ALTER TABLE `formation_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formation_id` (`formation_id`);

--
-- Indexes for table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formation_id` (`formation_id`);

--
-- Indexes for table `pays`
--
ALTER TABLE `pays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sujets`
--
ALTER TABLE `sujets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `domaine_id` (`domaine_id`);

--
-- Indexes for table `villes`
--
ALTER TABLE `villes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pays_id` (`pays_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cours`
--
ALTER TABLE `cours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `domaines`
--
ALTER TABLE `domaines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `formateurs`
--
ALTER TABLE `formateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `formations`
--
ALTER TABLE `formations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `formation_dates`
--
ALTER TABLE `formation_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inscriptions`
--
ALTER TABLE `inscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pays`
--
ALTER TABLE `pays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sujets`
--
ALTER TABLE `sujets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `villes`
--
ALTER TABLE `villes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cours`
--
ALTER TABLE `cours`
  ADD CONSTRAINT `cours_ibfk_1` FOREIGN KEY (`sujet_id`) REFERENCES `sujets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `formations`
--
ALTER TABLE `formations`
  ADD CONSTRAINT `formations_ibfk_1` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `formations_ibfk_2` FOREIGN KEY (`ville_id`) REFERENCES `villes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `formations_ibfk_3` FOREIGN KEY (`formateur_id`) REFERENCES `formateurs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `formation_dates`
--
ALTER TABLE `formation_dates`
  ADD CONSTRAINT `formation_dates_ibfk_1` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD CONSTRAINT `inscriptions_ibfk_1` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sujets`
--
ALTER TABLE `sujets`
  ADD CONSTRAINT `sujets_ibfk_1` FOREIGN KEY (`domaine_id`) REFERENCES `domaines` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `villes`
--
ALTER TABLE `villes`
  ADD CONSTRAINT `villes_ibfk_1` FOREIGN KEY (`pays_id`) REFERENCES `pays` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
