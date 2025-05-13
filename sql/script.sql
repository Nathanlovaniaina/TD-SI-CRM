-- Création de la base et sélection
CREATE DATABASE IF NOT EXISTS gestion_budget;
USE gestion_budget;

-- ======================
-- TABLE Client
-- ======================
CREATE TABLE Client (
    ClientID INT PRIMARY KEY AUTO_INCREMENT,
    Nom VARCHAR(100),
    Prenom VARCHAR(100),
    Telephone VARCHAR(20),
    Email VARCHAR(100),
    Adresse TEXT,
    Statut VARCHAR(50)
);

-- ======================
-- TABLE Produit
-- ======================
CREATE TABLE Produit (
    ProduitID INT PRIMARY KEY AUTO_INCREMENT,
    Nom VARCHAR(100),
    Categorie VARCHAR(50),
    Prix DECIMAL(10, 2),
    Stock INT
);

-- ======================
-- TABLE Commande
-- ======================
CREATE TABLE Commande (
    CommandeID INT PRIMARY KEY AUTO_INCREMENT,
    ClientID INT,
    DateCommande DATE,
    MontantTotal DECIMAL(10, 2),
    Statut VARCHAR(50),
    FOREIGN KEY (ClientID) REFERENCES Client(ClientID)
);

-- ======================
-- TABLE Commande_Produit
-- ======================
CREATE TABLE Commande_Produit (
    CommandeID INT,
    ProduitID INT,
    Quantite INT,
    PrixUnitaire DECIMAL(10, 2),
    PRIMARY KEY (CommandeID, ProduitID),
    FOREIGN KEY (CommandeID) REFERENCES Commande(CommandeID),
    FOREIGN KEY (ProduitID) REFERENCES Produit(ProduitID)
);

-- ======================
-- TABLE Employe
-- ======================
CREATE TABLE Employe (
    EmployeID INT PRIMARY KEY AUTO_INCREMENT,
    Nom VARCHAR(100),
    Poste VARCHAR(50),
    Email VARCHAR(100)
);

-- ======================
-- TABLE ActionClient
-- ======================
CREATE TABLE ActionClient (
    ActionID INT PRIMARY KEY AUTO_INCREMENT,
    ClientID INT,
    EmployeID INT,
    TypeAction VARCHAR(50),
    CategorieAction VARCHAR(50),
    DateAction DATE,
    Description TEXT,
    CoutAction DECIMAL(10, 2),
    StatutAction VARCHAR(50),
    Resultat VARCHAR(100),
    FOREIGN KEY (ClientID)   REFERENCES Client(ClientID),
    FOREIGN KEY (EmployeID)  REFERENCES Employe(EmployeID)
);

-- ======================
-- TABLE ReactionClient
-- ======================
CREATE TABLE ReactionClient (
    ReactionID INT PRIMARY KEY AUTO_INCREMENT,
    ActionID INT,
    ClientID INT,
    TypeReaction VARCHAR(100),
    DateReaction DATE,
    Contenu TEXT,
    FOREIGN KEY (ActionID) REFERENCES ActionClient(ActionID),
    FOREIGN KEY (ClientID)  REFERENCES Client(ClientID)
);

-- ======================
-- TABLE ActionCommerciale
-- ======================
CREATE TABLE ActionCommerciale (
    ActionCommercialeID INT PRIMARY KEY AUTO_INCREMENT,
    ActionID INT NOT NULL,
    Campagne VARCHAR(100),
    Objectif VARCHAR(255),
    EstConvertie BOOLEAN,
    Cout DECIMAL(10, 2),
    FOREIGN KEY (ActionID) REFERENCES ActionClient(ActionID)
);

-- ======================
-- TABLE ReactionCommerciale
-- ======================
CREATE TABLE ReactionCommerciale (
    ReactionCommercialeID INT PRIMARY KEY AUTO_INCREMENT,
    ActionID INT NOT NULL,               -- lien direct vers ActionClient
    ClientID INT NOT NULL,
    TypeReaction VARCHAR(100),
    DateReaction DATE,
    Contenu TEXT,
    FOREIGN KEY (ActionID) REFERENCES ActionClient(ActionID),
    FOREIGN KEY (ClientID)  REFERENCES Client(ClientID)
);

-- ======================
-- TABLE RequeteBudgetaire
-- ======================
CREATE TABLE RequeteBudgetaire (
    id_requete INT PRIMARY KEY AUTO_INCREMENT,
    valeur DECIMAL(10, 2)
);

ALTER TABLE Client
  ADD COLUMN DateInscription DATE NOT NULL
    DEFAULT CURRENT_DATE
    AFTER Adresse;

ALTER TABLE Client
ADD COLUMN Age INT;

ALTER TABLE Client
ADD COLUMN Genre VARCHAR(20);

CREATE TABLE TrancheAge (
    TrancheID       INT            PRIMARY KEY AUTO_INCREMENT,
    Intitule        VARCHAR(50)    NOT NULL,
    AgeMin          INT            NOT NULL,
    AgeMax          INT            NOT NULL
);

ALTER TABLE ActionCommerciale
ADD COLUMN ClientRate DECIMAL(5,2) NULL,
ADD COLUMN CommandeRate DECIMAL(5,2) NULL,
ADD COLUMN PrixRate DECIMAL(5,2) NULL;