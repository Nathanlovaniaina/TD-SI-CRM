-- Création de la base de données
CREATE DATABASE IF NOT EXISTS SupermarcheDB;
USE SupermarcheDB;

-- 1. Table des Rôles
CREATE TABLE Roles (
    RoleID INT PRIMARY KEY AUTO_INCREMENT,
    NomRole VARCHAR(20) NOT NULL UNIQUE,
    Permissions TEXT
);

-- 2. Table des Unités
CREATE TABLE Unites (
    UniteID INT PRIMARY KEY AUTO_INCREMENT,
    NomUnite VARCHAR(10) NOT NULL UNIQUE,
    Symbole VARCHAR(5)
);

-- 3. Table des Catégories
CREATE TABLE Categories (
    CategorieID INT PRIMARY KEY AUTO_INCREMENT,
    NomCategorie VARCHAR(50) NOT NULL,
    Description TEXT
);

-- 4. Table des Fournisseurs
CREATE TABLE Fournisseurs (
    FournisseurID INT PRIMARY KEY AUTO_INCREMENT,
    Nom VARCHAR(50) NOT NULL,
    Contact VARCHAR(100),
    Adresse TEXT,
    Telephone VARCHAR(20)
);

-- 5. Table des Caisses
CREATE TABLE Caisse (
    CaisseID INT PRIMARY KEY AUTO_INCREMENT,
    NumeroCaisse VARCHAR(10) NOT NULL UNIQUE,
    Emplacement VARCHAR(50),
    EstActive BOOLEAN DEFAULT TRUE,
    DateMiseEnService DATE
);

-- 6. Table des Utilisateurs
CREATE TABLE Utilisateurs (
    UtilisateurID INT PRIMARY KEY AUTO_INCREMENT,
    RoleID INT NOT NULL,
    NomComplet VARCHAR(50) NOT NULL,
    Email VARCHAR(100) UNIQUE,
    Login VARCHAR(30) NOT NULL UNIQUE,
    MotDePasse VARCHAR(255) NOT NULL,
    DateCreation DATETIME DEFAULT CURRENT_TIMESTAMP,
    EstActif BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (RoleID) REFERENCES Roles(RoleID)
);

-- 7. Table des Clients
CREATE TABLE Clients (
    ClientID INT PRIMARY KEY AUTO_INCREMENT,
    Nom VARCHAR(50) NOT NULL,
    Prenom VARCHAR(50),
    Telephone VARCHAR(20),
    Email VARCHAR(100),
    Adresse TEXT,
    PointsFidelite INT DEFAULT 0,
    DateInscription DATE
);

-- 8. Table des Produits
CREATE TABLE Produits (
    ProduitID INT PRIMARY KEY AUTO_INCREMENT,
    Designation VARCHAR(100) NOT NULL,
    Prix DECIMAL(10,2) NOT NULL,
    UniteID INT NOT NULL,
    QuantiteEnStock INT NOT NULL DEFAULT 0,
    SeuilAlerte INT DEFAULT 10,
    CategorieID INT,
    FournisseurID INT,
    CodeBarre VARCHAR(50) UNIQUE,
    EstDiscontinu BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (UniteID) REFERENCES Unites(UniteID),
    FOREIGN KEY (CategorieID) REFERENCES Categories(CategorieID),
    FOREIGN KEY (FournisseurID) REFERENCES Fournisseurs(FournisseurID)
);

-- 9. Table des Achats (Transactions)
CREATE TABLE Achats (
    AchatID INT PRIMARY KEY AUTO_INCREMENT,
    DateHeure DATETIME DEFAULT CURRENT_TIMESTAMP,
    UtilisateurID INT NOT NULL,
    CaisseID INT NOT NULL,
    ClientID INT,
    TotalHT DECIMAL(10,2),
    TotalTTC DECIMAL(10,2),
    Remise DECIMAL(10,2) DEFAULT 0,
    MoyenPaiement VARCHAR(20) NOT NULL,
    NumeroTicket VARCHAR(20),
    FOREIGN KEY (UtilisateurID) REFERENCES Utilisateurs(UtilisateurID),
    FOREIGN KEY (CaisseID) REFERENCES Caisse(CaisseID),
    FOREIGN KEY (ClientID) REFERENCES Clients(ClientID)
);

-- 10. Table des Détails d'Achat
CREATE TABLE DetailsAchats (
    DetailID INT PRIMARY KEY AUTO_INCREMENT,
    AchatID INT NOT NULL,
    ProduitID INT NOT NULL,
    CaisseID INT NOT NULL,
    Quantite INT NOT NULL,
    PrixUnitaire DECIMAL(10,2) NOT NULL,
    RemiseUnitaire DECIMAL(10,2) DEFAULT 0,
    TauxTVA DECIMAL(5,2) NOT NULL,
    SousTotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (AchatID) REFERENCES Achats(AchatID),
    FOREIGN KEY (ProduitID) REFERENCES Produits(ProduitID),
    FOREIGN KEY (CaisseID) REFERENCES Caisse(CaisseID)
);

-- 11. Table des Mouvements de Stock
CREATE TABLE MouvementsStock (
    MouvementID INT PRIMARY KEY AUTO_INCREMENT,
    ProduitID INT NOT NULL,
    TypeMouvement VARCHAR(20) NOT NULL,
    Quantite INT NOT NULL,
    DateMouvement DATETIME DEFAULT CURRENT_TIMESTAMP,
    UtilisateurID INT,
    Raison VARCHAR(100),
    Reference VARCHAR(50),
    FOREIGN KEY (ProduitID) REFERENCES Produits(ProduitID),
    FOREIGN KEY (UtilisateurID) REFERENCES Utilisateurs(UtilisateurID)
);

-- 12. Table des Promotions
CREATE TABLE Promotions (
    PromotionID INT PRIMARY KEY AUTO_INCREMENT,
    ProduitID INT NOT NULL,
    Debut DATETIME NOT NULL,
    Fin DATETIME NOT NULL,
    TypePromotion VARCHAR(20) NOT NULL, -- 'Pourcentage' ou 'Montant'
    ValeurPromotion DECIMAL(10,2) NOT NULL,
    Description TEXT,
    FOREIGN KEY (ProduitID) REFERENCES Produits(ProduitID)
);

-- 13. Table des Paiements (Optionnelle)
CREATE TABLE Paiements (
    PaiementID INT PRIMARY KEY AUTO_INCREMENT,
    AchatID INT NOT NULL,
    TypePaiement VARCHAR(20) NOT NULL,
    Montant DECIMAL(10,2) NOT NULL,
    DatePaiement DATETIME DEFAULT CURRENT_TIMESTAMP,
    Reference VARCHAR(50),
    FOREIGN KEY (AchatID) REFERENCES Achats(AchatID)
);

