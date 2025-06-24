-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 24 juin 2025 à 15:10
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_budget`
--

-- --------------------------------------------------------

--
-- Structure de la table `actionclient`
--

CREATE TABLE `actionclient` (
  `ActionID` int(11) NOT NULL,
  `ClientID` int(11) DEFAULT NULL,
  `EmployeID` int(11) DEFAULT NULL,
  `TypeAction` varchar(50) DEFAULT NULL,
  `CategorieAction` varchar(50) DEFAULT NULL,
  `DateAction` date DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `CoutAction` decimal(10,2) DEFAULT NULL,
  `StatutAction` varchar(50) DEFAULT NULL,
  `Resultat` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `actionclient`
--

INSERT INTO `actionclient` (`ActionID`, `ClientID`, `EmployeID`, `TypeAction`, `CategorieAction`, `DateAction`, `Description`, `CoutAction`, `StatutAction`, `Resultat`) VALUES
(1, 1, 1, 'Appel Téléphonique', 'Prospection', '2025-04-01', 'Appel de découverte', 5.00, 'Terminé', 'Intéressé'),
(2, 2, 2, 'Email', 'Suivi', '2025-04-05', 'Envoi de la brochure produit', 1.00, 'En attente', ''),
(3, 3, 1, 'Visite', 'Négociation', '2025-04-10', 'RDV sur site client', 20.00, 'Terminé', 'Contrat signé');

-- --------------------------------------------------------

--
-- Structure de la table `actioncommerciale`
--

CREATE TABLE `actioncommerciale` (
  `ActionCommercialeID` int(11) NOT NULL,
  `ActionID` int(11) NOT NULL,
  `Campagne` varchar(100) DEFAULT NULL,
  `Objectif` varchar(255) DEFAULT NULL,
  `EstConvertie` tinyint(1) DEFAULT NULL,
  `Cout` decimal(10,2) DEFAULT NULL,
  `ClientRate` decimal(5,2) DEFAULT NULL,
  `CommandeRate` decimal(5,2) DEFAULT NULL,
  `PrixRate` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `actioncommerciale`
--

INSERT INTO `actioncommerciale` (`ActionCommercialeID`, `ActionID`, `Campagne`, `Objectif`, `EstConvertie`, `Cout`, `ClientRate`, `CommandeRate`, `PrixRate`) VALUES
(1, 1, 'Promo printemps', 'Augmenter ventes de 10%', 1, 100.00, 0.50, 0.20, 0.10),
(2, 3, 'Offre spéciale', 'Fidélisation', 1, 200.00, 0.00, 0.50, 0.10);

-- --------------------------------------------------------

--
-- Structure de la table `affectationticket`
--

CREATE TABLE `affectationticket` (
  `id_affectation` int(11) NOT NULL,
  `id_ticket` int(11) NOT NULL,
  `id_requete` int(11) NOT NULL,
  `id_agent` int(11) NOT NULL,
  `is_valide` tinyint(1) DEFAULT 0,
  `date_affectation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `affectationticket`
--

INSERT INTO `affectationticket` (`id_affectation`, `id_ticket`, `id_requete`, `id_agent`, `is_valide`, `date_affectation`) VALUES
(2, 3, 3, 2, 0, '2023-10-17 11:00:00'),
(3, 4, 4, 3, 0, '2023-10-10 17:00:00'),
(5, 6, 6, 4, 0, '2023-10-18 08:30:00'),
(8, 7, 3, 4, 0, '2025-06-21 16:01:38'),
(9, 8, 6, 2, 0, '2025-06-21 16:04:15'),
(10, 9, 1, 4, 1, '2025-06-24 11:56:47');

--
-- Déclencheurs `affectationticket`
--
DELIMITER $$
CREATE TRIGGER `after_affectation_validee` AFTER UPDATE ON `affectationticket` FOR EACH ROW BEGIN
    IF NEW.is_valide = TRUE AND OLD.is_valide = FALSE THEN
        -- Mettre à jour l'état de la requête
        UPDATE RequeteClient 
        SET id_etat = (SELECT id_etat FROM Etat_requete WHERE libelle = 'en_cours')
        WHERE id_requete = NEW.id_requete;
        
        -- Ajouter un message dans le chat
        INSERT INTO ChatTicket (id_ticket, id_agent, id_client, contenu, id_affectation)
        VALUES (
            NEW.id_ticket,
            NEW.id_agent,
            (SELECT id_client FROM RequeteClient WHERE id_requete = NEW.id_requete),
            'Votre demande a été prise en charge par un agent',
            NEW.id_affectation
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `agent`
--

CREATE TABLE `agent` (
  `id_agent` int(11) NOT NULL,
  `id_employe` int(11) NOT NULL,
  `disponible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `agent`
--

INSERT INTO `agent` (`id_agent`, `id_employe`, `disponible`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 0),
(4, 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `autorisation`
--

CREATE TABLE `autorisation` (
  `id_autorisation` int(11) NOT NULL,
  `id_departement_proprietaire` int(11) NOT NULL,
  `id_departement_possession` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `autorisation`
--

INSERT INTO `autorisation` (`id_autorisation`, `id_departement_proprietaire`, `id_departement_possession`) VALUES
(1, 1, 2),
(2, 1, 3),
(3, 2, 3);

-- --------------------------------------------------------

--
-- Structure de la table `budget`
--

CREATE TABLE `budget` (
  `id_budget` int(11) NOT NULL,
  `montant` decimal(15,2) NOT NULL CHECK (`montant` >= 0),
  `date_budget` date DEFAULT NULL,
  `id_departement` int(11) NOT NULL,
  `id_type` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `budget`
--

INSERT INTO `budget` (`id_budget`, `montant`, `date_budget`, `id_departement`, `id_type`, `description`) VALUES
(1, 950.00, '2025-03-01', 3, 1, 'Action CRM'),
(2, 2000.00, '2025-02-15', 2, 2, 'Formation des employés en gestion'),
(3, 1500.00, '2025-01-20', 1, 3, 'Entretien des bureaux');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id_cate` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_cate`, `nom`) VALUES
(1, 'Frais de fonctionnement'),
(2, 'Investissements'),
(3, 'Salaires');

-- --------------------------------------------------------

--
-- Structure de la table `categorieticket`
--

CREATE TABLE `categorieticket` (
  `id_categorie` int(11) NOT NULL,
  `Nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorieticket`
--

INSERT INTO `categorieticket` (`id_categorie`, `Nom`) VALUES
(1, 'Technique'),
(2, 'Facturation'),
(3, 'Réclamation'),
(4, 'Demande d\'information'),
(5, 'Support');

-- --------------------------------------------------------

--
-- Structure de la table `chatticket`
--

CREATE TABLE `chatticket` (
  `id_message` int(11) NOT NULL,
  `id_ticket` int(11) NOT NULL,
  `id_agent` int(11) DEFAULT NULL,
  `id_client` int(11) ,
  `contenu` text NOT NULL,
  `date_envoi` datetime DEFAULT current_timestamp(),
  `id_affectation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `ClientID` int(11) NOT NULL,
  `Nom` varchar(100) DEFAULT NULL,
  `Prenom` varchar(100) DEFAULT NULL,
  `Telephone` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Adresse` text DEFAULT NULL,
  `DateInscription` date NOT NULL DEFAULT curdate(),
  `Statut` varchar(50) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Genre` varchar(20) DEFAULT NULL,
  `mot_de_passe` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`ClientID`, `Nom`, `Prenom`, `Telephone`, `Email`, `Adresse`, `DateInscription`, `Statut`, `Age`, `Genre`, `mot_de_passe`) VALUES
(1, 'Dupont', 'Jean', '0612345678', 'jean.dupont@example.com', '12 rue de la Paix, Paris', '2025-01-23', 'Prospect', 25, 'Homme', 'test'),
(2, 'Martin', 'Marie', '0623456789', 'marie.martin@example.com', '5 avenue Victor Hugo, Lyon', '2025-01-23', 'Client', 30, 'Femme', NULL),
(3, 'Durand', 'Luc', '0634567890', 'luc.durand@example.com', '8 boulevard Saint-Germain, Paris', '2025-01-23', 'Client', 18, 'Homme', NULL),
(4, 'Dupont', 'Jean', '0612345678', 'jean.dupont@example.com', '12 rue de la Paix, Paris', '2025-01-23', 'Prospect', 21, 'Homme', NULL),
(5, 'Martin', 'Marie', '0623456789', 'marie.martin@example.com', '5 avenue Victor Hugo, Lyon', '2025-01-23', 'Client', 31, 'Femme', NULL),
(6, 'Durand', 'Luc', '0634567890', 'luc.durand@example.com', '8 boulevard Saint-Germain, Paris', '2025-02-23', 'Client', 19, 'Homme', NULL),
(7, 'Ranaivoson', 'Miora', '0341234567', 'miora.r@example.com', 'Analakely, Antananarivo', '2025-01-15', 'Actif', 22, 'Femme', NULL),
(8, 'Rakotoarisoa', 'Jean', '0337654321', 'jean.r@example.com', 'Isoraka, Antananarivo', '2025-02-10', 'Actif', 34, 'Homme', NULL),
(9, 'Andriamanana', 'Lucie', '0321122334', 'lucie.a@example.com', 'Ankorondrano, Antananarivo', '2025-03-05', 'Inactif', 28, 'Femme', NULL),
(10, 'Rasoa', 'Hery', '0349988776', 'hery.r@example.com', 'Ambohizato, Antananarivo', '2025-04-01', 'Actif', 16, 'Homme', NULL),
(11, 'Rakotondrainy', 'Fanja', '0334455667', 'fanja.r@example.com', 'Ivandry, Antananarivo', '2025-04-15', 'Actif', 42, 'Femme', NULL),
(12, 'Ranaivoson', 'Miora', '0341234567', 'miora.r@example.com', 'Analakely, Antananarivo', '2025-01-15', 'Actif', 22, 'Femme', NULL),
(13, 'Rakotoarisoa', 'Jean', '0337654321', 'jean.r@example.com', 'Isoraka, Antananarivo', '2025-02-10', 'Actif', 34, 'Homme', NULL),
(14, 'Andriamanana', 'Lucie', '0321122334', 'lucie.a@example.com', 'Ankorondrano, Antananarivo', '2025-03-05', 'Inactif', 28, 'Femme', NULL),
(15, 'Rasoa', 'Hery', '0349988776', 'hery.r@example.com', 'Ambohizato, Antananarivo', '2025-04-01', 'Actif', 16, 'Homme', NULL),
(16, 'Rakotondrainy', 'Fanja', '0334455667', 'fanja.r@example.com', 'Ivandry, Antananarivo', '2025-04-15', 'Actif', 42, 'Femme', NULL),
(17, 'Ranaivoson', 'Miora', '0341234567', 'miora.r@example.com', 'Analakely, Antananarivo', '2025-01-15', 'Actif', 22, 'Femme', NULL),
(18, 'Rakotoarisoa', 'Jean', '0337654321', 'jean.r@example.com', 'Isoraka, Antananarivo', '2025-02-10', 'Actif', 34, 'Homme', NULL),
(19, 'Andriamanana', 'Lucie', '0321122334', 'lucie.a@example.com', 'Ankorondrano, Antananarivo', '2025-03-05', 'Inactif', 28, 'Femme', NULL),
(20, 'Rasoa', 'Hery', '0349988776', 'hery.r@example.com', 'Ambohizato, Antananarivo', '2025-04-01', 'Actif', 16, 'Homme', NULL),
(21, 'Rakotondrainy', 'Fanja', '0334455667', 'fanja.r@example.com', 'Ivandry, Antananarivo', '2025-04-15', 'Actif', 42, 'Femme', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `CommandeID` int(11) NOT NULL,
  `ClientID` int(11) DEFAULT NULL,
  `DateCommande` date DEFAULT NULL,
  `MontantTotal` decimal(10,2) DEFAULT NULL,
  `Statut` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`CommandeID`, `ClientID`, `DateCommande`, `MontantTotal`, `Statut`) VALUES
(1, 1, '2025-04-20', 145.00, 'Livrée'),
(2, 2, '2025-05-01', 210.00, 'En cours'),
(3, 3, '2025-04-28', 105.00, 'Annulée'),
(4, 4, '2025-05-02', 165.00, 'En cours'),
(5, 11, '2025-03-01', 400.00, 'Livrée'),
(6, 11, '2025-03-11', 500.00, 'Livrée');

-- --------------------------------------------------------

--
-- Structure de la table `commande_produit`
--

CREATE TABLE `commande_produit` (
  `CommandeID` int(11) NOT NULL,
  `ProduitID` int(11) NOT NULL,
  `Quantite` int(11) DEFAULT NULL,
  `PrixUnitaire` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande_produit`
--

INSERT INTO `commande_produit` (`CommandeID`, `ProduitID`, `Quantite`, `PrixUnitaire`) VALUES
(1, 1, 1, 75.00),
(1, 2, 2, 35.00),
(2, 3, 1, 120.00),
(2, 4, 1, 90.00),
(3, 2, 3, 35.00),
(4, 1, 1, 75.00),
(4, 4, 1, 90.00);

-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

CREATE TABLE `departement` (
  `id_departement` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `departement`
--

INSERT INTO `departement` (`id_departement`, `nom`) VALUES
(1, 'Finance'),
(2, 'Ressources Humaines'),
(3, 'Marketing');

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

CREATE TABLE `employe` (
  `EmployeID` int(11) NOT NULL,
  `Nom` varchar(100) DEFAULT NULL,
  `Poste` varchar(50) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `mot_de_passe` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `employe`
--

INSERT INTO `employe` (`EmployeID`, `Nom`, `Poste`, `Email`, `mot_de_passe`) VALUES
(1, 'Leroy', 'Commercial', 'alice.leroy@entreprise.com', NULL),
(2, 'Petit', 'Manager', 'bob.petit@entreprise.com', NULL),
(3, 'Roux', 'Support Client', 'support1@entreprise.com', 'test'),
(4, 'Lefevre', 'Technicien', 'tech1@entreprise.com', 'taybe'),
(5, 'Moreau', 'Gestionnaire', 'gestion1@entreprise.com', NULL),
(6, 'Fournier', 'Commercial', 'commercial1@entreprise.com', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `etat_requete`
--

CREATE TABLE `etat_requete` (
  `id_etat` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etat_requete`
--

INSERT INTO `etat_requete` (`id_etat`, `libelle`) VALUES
(1, 'ouvert'),
(2, 'assigné'),
(3, 'en_cours'),
(4, 'résolu'),
(5, 'fermé');

-- --------------------------------------------------------

--
-- Structure de la table `evaluationticket`
--

CREATE TABLE `evaluationticket` (
  `id_evaluation` int(11) NOT NULL,
  `id_ticket` int(11) NOT NULL,
  `note` int(11) NOT NULL,
  `commentaire` text DEFAULT NULL,
  `date_evaluation` datetime DEFAULT current_timestamp(),
  `id_affectation` int(11) NOT NULL
) ;

--
-- Déchargement des données de la table `evaluationticket`
--

INSERT INTO `evaluationticket` (`id_evaluation`, `id_ticket`, `note`, `commentaire`, `date_evaluation`, `id_affectation`) VALUES
(1, 4, 5, 'Excellente prise en charge, fonctionnalité très utile !', '2023-10-11 10:00:00', 3);

-- --------------------------------------------------------

--
-- Structure de la table `nature`
--

CREATE TABLE `nature` (
  `id_nature` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `id_departement` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `nature`
--

INSERT INTO `nature` (`id_nature`, `nom`, `id_departement`) VALUES
(1, 'Matériel informatique', 3),
(2, 'Formation du personnel', 2),
(3, 'Entretien des locaux', 1);

-- --------------------------------------------------------

--
-- Structure de la table `prevision`
--

CREATE TABLE `prevision` (
  `id_prevision` int(11) NOT NULL,
  `id_departement` int(11) NOT NULL,
  `id_type` int(11) NOT NULL,
  `montant` decimal(15,2) NOT NULL CHECK (`montant` >= 0),
  `date_prevision` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `prevision`
--

INSERT INTO `prevision` (`id_prevision`, `id_departement`, `id_type`, `montant`, `date_prevision`) VALUES
(1, 3, 1, 7000.00, '2025-04-01'),
(2, 2, 2, 3000.00, '2025-04-01'),
(3, 1, 3, 2000.00, '2025-04-01');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `ProduitID` int(11) NOT NULL,
  `Nom` varchar(100) DEFAULT NULL,
  `Categorie` varchar(50) DEFAULT NULL,
  `Prix` decimal(10,2) DEFAULT NULL,
  `Stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`ProduitID`, `Nom`, `Categorie`, `Prix`, `Stock`) VALUES
(1, 'AirMax Sneakers', 'Sneakers', 75.00, 50),
(2, 'Sunshine Sandales', 'Sandales', 35.00, 30),
(3, 'Urban Chic Mocassins', 'Chaussures de ville', 120.00, 20),
(4, 'TrailBlazer Running', 'Chaussures de sport', 90.00, 40);

-- --------------------------------------------------------

--
-- Structure de la table `reactionclient`
--

CREATE TABLE `reactionclient` (
  `ReactionID` int(11) NOT NULL,
  `ActionID` int(11) DEFAULT NULL,
  `ClientID` int(11) DEFAULT NULL,
  `TypeReaction` varchar(100) DEFAULT NULL,
  `DateReaction` date DEFAULT NULL,
  `Contenu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reactionclient`
--

INSERT INTO `reactionclient` (`ReactionID`, `ActionID`, `ClientID`, `TypeReaction`, `DateReaction`, `Contenu`) VALUES
(1, 1, 1, 'Positif', '2025-04-02', 'Demande de devis'),
(2, 2, 2, 'Neutre', '2025-04-06', 'Pas encore lu'),
(3, 3, 3, 'Très positif', '2025-04-11', 'Contrat signé');

-- --------------------------------------------------------

--
-- Structure de la table `reactioncommerciale`
--

CREATE TABLE `reactioncommerciale` (
  `ReactionCommercialeID` int(11) NOT NULL,
  `ActionID` int(11) NOT NULL,
  `ClientID` int(11) NOT NULL,
  `TypeReaction` varchar(100) DEFAULT NULL,
  `DateReaction` date DEFAULT NULL,
  `Contenu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reactioncommerciale`
--

INSERT INTO `reactioncommerciale` (`ReactionCommercialeID`, `ActionID`, `ClientID`, `TypeReaction`, `DateReaction`, `Contenu`) VALUES
(1, 1, 1, 'Acheté', '2025-04-03', '2 packs Lait'),
(2, 1, 2, 'Aucun intérêt', '2025-04-04', 'N/A'),
(3, 3, 3, 'Inscrit', '2025-04-12', 'Inscrit à la newsletter');

-- --------------------------------------------------------

--
-- Structure de la table `requetebudgetaire`
--

CREATE TABLE `requetebudgetaire` (
  `id_requete` int(11) NOT NULL,
  `valeur` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `requeteclient`
--

CREATE TABLE `requeteclient` (
  `id_requete` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `Sujet` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Date_creation` datetime DEFAULT current_timestamp(),
  `FichierJoint` varchar(255) DEFAULT NULL,
  `id_etat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `requeteclient`
--

INSERT INTO `requeteclient` (`id_requete`, `id_client`, `Sujet`, `Description`, `Date_creation`, `FichierJoint`, `id_etat`) VALUES
(1, 1, 'Problème de connexion', 'Je ne parviens pas à me connecter à mon compte depuis hier matin.', '2023-10-15 09:23:45', NULL, 2),
(3, 2, 'Retard de livraison', 'Ma commande #45879 devait être livrée hier mais n\'est toujours pas arrivée.', '2023-10-17 10:45:21', NULL, 5),
(4, 5, 'Demande de fonctionnalité', 'Serait-il possible d\'ajouter un export Excel des historiques de commandes ?', '2023-10-10 16:30:15', 'exemple_export.xlsx', 4),
(6, 7, 'Urgent! Commande bloquée', 'Ma commande #48750 est bloquée à l\'étape de paiement depuis 1h.', '2023-10-18 08:05:37', 'capture_ecran_paiement.png', 4);

-- --------------------------------------------------------

--
-- Structure de la table `ticket`
--

CREATE TABLE `ticket` (
  `id_ticket` int(11) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `priorite` varchar(50) NOT NULL,
  `prixPrestation` decimal(10,2) NOT NULL,
  `duree` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ticket`
--

INSERT INTO `ticket` (`id_ticket`, `id_categorie`, `priorite`, `prixPrestation`, `duree`) VALUES
(1, 1, 'haute', 50.00, 2),
(3, 3, 'normale', 45.00, 2),
(4, 4, 'basse', 60.00, 3),
(6, 5, 'haute', 75.00, 4),
(7, 3, 'faible', 45.00, 30),
(8, 2, 'haute', 23.00, 30),
(9, 1, 'moyenne', 150.00, 30);

-- --------------------------------------------------------

--
-- Structure de la table `trancheage`
--

CREATE TABLE `trancheage` (
  `TrancheID` int(11) NOT NULL,
  `Intitule` varchar(50) NOT NULL,
  `AgeMin` int(11) NOT NULL,
  `AgeMax` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `trancheage`
--

INSERT INTO `trancheage` (`TrancheID`, `Intitule`, `AgeMin`, `AgeMax`) VALUES
(1, 'Jeune', 18, 25),
(2, 'Parent', 30, 45),
(3, 'Professionnel Élégant', 35, 50),
(4, 'Adolescent(e)', 13, 17),
(5, 'Travailleur', 25, 55);

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

CREATE TABLE `type` (
  `id_type` int(11) NOT NULL,
  `id_cate` int(11) NOT NULL,
  `id_nature` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `type`
--

INSERT INTO `type` (`id_type`, `id_cate`, `id_nature`, `libelle`) VALUES
(1, 1, 1, 'Marketing'),
(2, 2, 2, 'Formation en gestion'),
(3, 3, 3, 'Réparation bureaux');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `identifiant` varchar(50) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `id_departement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `identifiant`, `mdp`, `id_departement`) VALUES
(1, 'admin_finance', 'hashedpassword1', 1),
(2, 'admin_rh', 'hashedpassword2', 2),
(3, 'admin_it', 'hashedpassword3', 3);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_requetesavecetat`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vue_requetesavecetat` (
`id_requete` int(11)
,`Sujet` varchar(255)
,`Date_creation` datetime
,`etat` varchar(50)
,`client` varchar(201)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_ticketsaffectes`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vue_ticketsaffectes` (
`id_ticket` int(11)
,`categorie` varchar(100)
,`priorite` varchar(50)
,`id_agent` int(11)
,`is_valide` tinyint(1)
,`date_affectation` datetime
,`etat_requete` varchar(50)
);

-- --------------------------------------------------------

--
-- Structure de la vue `vue_requetesavecetat`
--
DROP TABLE IF EXISTS `vue_requetesavecetat`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vue_requetesavecetat`  AS SELECT `r`.`id_requete` AS `id_requete`, `r`.`Sujet` AS `Sujet`, `r`.`Date_creation` AS `Date_creation`, `e`.`libelle` AS `etat`, concat(`c`.`Nom`,' ',`c`.`Prenom`) AS `client` FROM ((`requeteclient` `r` join `etat_requete` `e` on(`r`.`id_etat` = `e`.`id_etat`)) join `client` `c` on(`r`.`id_client` = `c`.`ClientID`)) ;

-- --------------------------------------------------------

--
-- Structure de la vue `vue_ticketsaffectes`
--
DROP TABLE IF EXISTS `vue_ticketsaffectes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vue_ticketsaffectes`  AS SELECT `t`.`id_ticket` AS `id_ticket`, `c`.`Nom` AS `categorie`, `t`.`priorite` AS `priorite`, `a`.`id_agent` AS `id_agent`, `at`.`is_valide` AS `is_valide`, `at`.`date_affectation` AS `date_affectation`, `e`.`libelle` AS `etat_requete` FROM (((((`ticket` `t` join `categorieticket` `c` on(`t`.`id_categorie` = `c`.`id_categorie`)) join `affectationticket` `at` on(`t`.`id_ticket` = `at`.`id_ticket`)) join `requeteclient` `r` on(`at`.`id_requete` = `r`.`id_requete`)) join `etat_requete` `e` on(`r`.`id_etat` = `e`.`id_etat`)) join `agent` `a` on(`at`.`id_agent` = `a`.`id_agent`)) ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `actionclient`
--
ALTER TABLE `actionclient`
  ADD PRIMARY KEY (`ActionID`),
  ADD KEY `ClientID` (`ClientID`),
  ADD KEY `EmployeID` (`EmployeID`);

--
-- Index pour la table `actioncommerciale`
--
ALTER TABLE `actioncommerciale`
  ADD PRIMARY KEY (`ActionCommercialeID`),
  ADD KEY `ActionID` (`ActionID`);

--
-- Index pour la table `affectationticket`
--
ALTER TABLE `affectationticket`
  ADD PRIMARY KEY (`id_affectation`),
  ADD KEY `idx_affectation_ticket` (`id_ticket`),
  ADD KEY `idx_affectation_requete` (`id_requete`),
  ADD KEY `idx_affectation_agent` (`id_agent`);

--
-- Index pour la table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`id_agent`),
  ADD KEY `id_employe` (`id_employe`);

--
-- Index pour la table `autorisation`
--
ALTER TABLE `autorisation`
  ADD PRIMARY KEY (`id_autorisation`),
  ADD KEY `id_departement_proprietaire` (`id_departement_proprietaire`),
  ADD KEY `id_departement_possession` (`id_departement_possession`);

--
-- Index pour la table `budget`
--
ALTER TABLE `budget`
  ADD PRIMARY KEY (`id_budget`),
  ADD KEY `id_departement` (`id_departement`),
  ADD KEY `id_type` (`id_type`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_cate`);

--
-- Index pour la table `categorieticket`
--
ALTER TABLE `categorieticket`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `chatticket`
--
ALTER TABLE `chatticket`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `id_agent` (`id_agent`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_affectation` (`id_affectation`),
  ADD KEY `idx_chat_ticket` (`id_ticket`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`ClientID`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`CommandeID`),
  ADD KEY `ClientID` (`ClientID`);

--
-- Index pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD PRIMARY KEY (`CommandeID`,`ProduitID`),
  ADD KEY `ProduitID` (`ProduitID`);

--
-- Index pour la table `departement`
--
ALTER TABLE `departement`
  ADD PRIMARY KEY (`id_departement`);

--
-- Index pour la table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`EmployeID`);

--
-- Index pour la table `etat_requete`
--
ALTER TABLE `etat_requete`
  ADD PRIMARY KEY (`id_etat`);

--
-- Index pour la table `evaluationticket`
--
ALTER TABLE `evaluationticket`
  ADD PRIMARY KEY (`id_evaluation`),
  ADD KEY `id_affectation` (`id_affectation`),
  ADD KEY `idx_evaluation_ticket` (`id_ticket`);

--
-- Index pour la table `nature`
--
ALTER TABLE `nature`
  ADD PRIMARY KEY (`id_nature`),
  ADD KEY `id_departement` (`id_departement`);

--
-- Index pour la table `prevision`
--
ALTER TABLE `prevision`
  ADD PRIMARY KEY (`id_prevision`),
  ADD KEY `id_departement` (`id_departement`),
  ADD KEY `id_type` (`id_type`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`ProduitID`);

--
-- Index pour la table `reactionclient`
--
ALTER TABLE `reactionclient`
  ADD PRIMARY KEY (`ReactionID`),
  ADD KEY `ActionID` (`ActionID`),
  ADD KEY `ClientID` (`ClientID`);

--
-- Index pour la table `reactioncommerciale`
--
ALTER TABLE `reactioncommerciale`
  ADD PRIMARY KEY (`ReactionCommercialeID`),
  ADD KEY `ActionID` (`ActionID`),
  ADD KEY `ClientID` (`ClientID`);

--
-- Index pour la table `requetebudgetaire`
--
ALTER TABLE `requetebudgetaire`
  ADD PRIMARY KEY (`id_requete`);

--
-- Index pour la table `requeteclient`
--
ALTER TABLE `requeteclient`
  ADD PRIMARY KEY (`id_requete`),
  ADD KEY `idx_requete_client` (`id_client`),
  ADD KEY `idx_requete_etat` (`id_etat`);

--
-- Index pour la table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id_ticket`),
  ADD KEY `idx_ticket_categorie` (`id_categorie`);

--
-- Index pour la table `trancheage`
--
ALTER TABLE `trancheage`
  ADD PRIMARY KEY (`TrancheID`);

--
-- Index pour la table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id_type`),
  ADD KEY `id_cate` (`id_cate`),
  ADD KEY `id_nature` (`id_nature`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `identifiant` (`identifiant`),
  ADD KEY `id_departement` (`id_departement`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `actionclient`
--
ALTER TABLE `actionclient`
  MODIFY `ActionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `actioncommerciale`
--
ALTER TABLE `actioncommerciale`
  MODIFY `ActionCommercialeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `affectationticket`
--
ALTER TABLE `affectationticket`
  MODIFY `id_affectation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `agent`
--
ALTER TABLE `agent`
  MODIFY `id_agent` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `autorisation`
--
ALTER TABLE `autorisation`
  MODIFY `id_autorisation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `budget`
--
ALTER TABLE `budget`
  MODIFY `id_budget` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_cate` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `categorieticket`
--
ALTER TABLE `categorieticket`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `chatticket`
--
ALTER TABLE `chatticket`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `ClientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `CommandeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `departement`
--
ALTER TABLE `departement`
  MODIFY `id_departement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `employe`
--
ALTER TABLE `employe`
  MODIFY `EmployeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `etat_requete`
--
ALTER TABLE `etat_requete`
  MODIFY `id_etat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `evaluationticket`
--
ALTER TABLE `evaluationticket`
  MODIFY `id_evaluation` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `nature`
--
ALTER TABLE `nature`
  MODIFY `id_nature` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `prevision`
--
ALTER TABLE `prevision`
  MODIFY `id_prevision` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `ProduitID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `reactionclient`
--
ALTER TABLE `reactionclient`
  MODIFY `ReactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `reactioncommerciale`
--
ALTER TABLE `reactioncommerciale`
  MODIFY `ReactionCommercialeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `requetebudgetaire`
--
ALTER TABLE `requetebudgetaire`
  MODIFY `id_requete` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `requeteclient`
--
ALTER TABLE `requeteclient`
  MODIFY `id_requete` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `trancheage`
--
ALTER TABLE `trancheage`
  MODIFY `TrancheID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `type`
--
ALTER TABLE `type`
  MODIFY `id_type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `actionclient`
--
ALTER TABLE `actionclient`
  ADD CONSTRAINT `actionclient_ibfk_1` FOREIGN KEY (`ClientID`) REFERENCES `client` (`ClientID`),
  ADD CONSTRAINT `actionclient_ibfk_2` FOREIGN KEY (`EmployeID`) REFERENCES `employe` (`EmployeID`);

--
-- Contraintes pour la table `actioncommerciale`
--
ALTER TABLE `actioncommerciale`
  ADD CONSTRAINT `actioncommerciale_ibfk_1` FOREIGN KEY (`ActionID`) REFERENCES `actionclient` (`ActionID`);

--
-- Contraintes pour la table `affectationticket`
--
ALTER TABLE `affectationticket`
  ADD CONSTRAINT `affectationticket_ibfk_1` FOREIGN KEY (`id_ticket`) REFERENCES `ticket` (`id_ticket`),
  ADD CONSTRAINT `affectationticket_ibfk_2` FOREIGN KEY (`id_requete`) REFERENCES `requeteclient` (`id_requete`),
  ADD CONSTRAINT `affectationticket_ibfk_3` FOREIGN KEY (`id_agent`) REFERENCES `agent` (`id_agent`);

--
-- Contraintes pour la table `agent`
--
ALTER TABLE `agent`
  ADD CONSTRAINT `agent_ibfk_1` FOREIGN KEY (`id_employe`) REFERENCES `employe` (`EmployeID`);

--
-- Contraintes pour la table `autorisation`
--
ALTER TABLE `autorisation`
  ADD CONSTRAINT `autorisation_ibfk_1` FOREIGN KEY (`id_departement_proprietaire`) REFERENCES `departement` (`id_departement`) ON DELETE CASCADE,
  ADD CONSTRAINT `autorisation_ibfk_2` FOREIGN KEY (`id_departement_possession`) REFERENCES `departement` (`id_departement`) ON DELETE CASCADE;

--
-- Contraintes pour la table `budget`
--
ALTER TABLE `budget`
  ADD CONSTRAINT `budget_ibfk_1` FOREIGN KEY (`id_departement`) REFERENCES `departement` (`id_departement`) ON DELETE CASCADE,
  ADD CONSTRAINT `budget_ibfk_2` FOREIGN KEY (`id_type`) REFERENCES `type` (`id_type`) ON DELETE CASCADE;

--
-- Contraintes pour la table `chatticket`
--
ALTER TABLE `chatticket`
  ADD CONSTRAINT `chatticket_ibfk_1` FOREIGN KEY (`id_ticket`) REFERENCES `ticket` (`id_ticket`),
  ADD CONSTRAINT `chatticket_ibfk_2` FOREIGN KEY (`id_agent`) REFERENCES `agent` (`id_agent`),
  ADD CONSTRAINT `chatticket_ibfk_3` FOREIGN KEY (`id_client`) REFERENCES `client` (`ClientID`),
  ADD CONSTRAINT `chatticket_ibfk_4` FOREIGN KEY (`id_affectation`) REFERENCES `affectationticket` (`id_affectation`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`ClientID`) REFERENCES `client` (`ClientID`);

--
-- Contraintes pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD CONSTRAINT `commande_produit_ibfk_1` FOREIGN KEY (`CommandeID`) REFERENCES `commande` (`CommandeID`),
  ADD CONSTRAINT `commande_produit_ibfk_2` FOREIGN KEY (`ProduitID`) REFERENCES `produit` (`ProduitID`);

--
-- Contraintes pour la table `evaluationticket`
--
ALTER TABLE `evaluationticket`
  ADD CONSTRAINT `evaluationticket_ibfk_1` FOREIGN KEY (`id_ticket`) REFERENCES `ticket` (`id_ticket`),
  ADD CONSTRAINT `evaluationticket_ibfk_2` FOREIGN KEY (`id_affectation`) REFERENCES `affectationticket` (`id_affectation`);

--
-- Contraintes pour la table `nature`
--
ALTER TABLE `nature`
  ADD CONSTRAINT `nature_ibfk_1` FOREIGN KEY (`id_departement`) REFERENCES `departement` (`id_departement`) ON DELETE CASCADE;

--
-- Contraintes pour la table `prevision`
--
ALTER TABLE `prevision`
  ADD CONSTRAINT `prevision_ibfk_1` FOREIGN KEY (`id_departement`) REFERENCES `departement` (`id_departement`) ON DELETE CASCADE,
  ADD CONSTRAINT `prevision_ibfk_2` FOREIGN KEY (`id_type`) REFERENCES `type` (`id_type`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reactionclient`
--
ALTER TABLE `reactionclient`
  ADD CONSTRAINT `reactionclient_ibfk_1` FOREIGN KEY (`ActionID`) REFERENCES `actionclient` (`ActionID`),
  ADD CONSTRAINT `reactionclient_ibfk_2` FOREIGN KEY (`ClientID`) REFERENCES `client` (`ClientID`);

--
-- Contraintes pour la table `reactioncommerciale`
--
ALTER TABLE `reactioncommerciale`
  ADD CONSTRAINT `reactioncommerciale_ibfk_1` FOREIGN KEY (`ActionID`) REFERENCES `actionclient` (`ActionID`),
  ADD CONSTRAINT `reactioncommerciale_ibfk_2` FOREIGN KEY (`ClientID`) REFERENCES `client` (`ClientID`);

--
-- Contraintes pour la table `requeteclient`
--
ALTER TABLE `requeteclient`
  ADD CONSTRAINT `requeteclient_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`ClientID`),
  ADD CONSTRAINT `requeteclient_ibfk_2` FOREIGN KEY (`id_etat`) REFERENCES `etat_requete` (`id_etat`);

--
-- Contraintes pour la table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categorieticket` (`id_categorie`);

--
-- Contraintes pour la table `type`
--
ALTER TABLE `type`
  ADD CONSTRAINT `type_ibfk_1` FOREIGN KEY (`id_cate`) REFERENCES `categorie` (`id_cate`) ON DELETE CASCADE,
  ADD CONSTRAINT `type_ibfk_2` FOREIGN KEY (`id_nature`) REFERENCES `nature` (`id_nature`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_departement`) REFERENCES `departement` (`id_departement`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
