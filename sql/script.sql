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


-- ======================
-- TABLES DE BASE
-- ======================

-- Table Etat_requete
CREATE TABLE IF NOT EXISTS Etat_requete (
    id_etat INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

-- Table RequeteClient
CREATE TABLE IF NOT EXISTS RequeteClient (
    id_requete INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NOT NULL,
    Sujet VARCHAR(255) NOT NULL,
    Description TEXT NOT NULL,
    Date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FichierJoint VARCHAR(255),
    id_etat INT NOT NULL,
    FOREIGN KEY (id_client) REFERENCES Client(ClientID),
    FOREIGN KEY (id_etat) REFERENCES Etat_requete(id_etat)
);

-- Table CategorieTicket
CREATE TABLE IF NOT EXISTS CategorieTicket (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    Nom VARCHAR(100) NOT NULL
);

-- Table Agent
CREATE TABLE IF NOT EXISTS Agent (
    id_agent INT AUTO_INCREMENT PRIMARY KEY,
    id_employe INT NOT NULL,
    disponible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_employe) REFERENCES Employe(EmployeID)
);

-- Table Ticket
CREATE TABLE IF NOT EXISTS Ticket (
    id_ticket INT AUTO_INCREMENT PRIMARY KEY,
    id_categorie INT NOT NULL,
    priorite ENUM('basse', 'normale', 'haute') NOT NULL,
    prixPrestation DECIMAL(10, 2) NOT NULL,
    duree INT NOT NULL COMMENT 'Durée estimée en heures',
    id_requete INT NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('ouvert', 'assigné', 'en_cours', 'résolu', 'fermé') DEFAULT 'ouvert',
    FOREIGN KEY (id_categorie) REFERENCES CategorieTicket(id_categorie),
    FOREIGN KEY (id_requete) REFERENCES RequeteClient(id_requete)
);

-- Table AffectationTicket
CREATE TABLE IF NOT EXISTS AffectationTicket (
    id_affectation INT AUTO_INCREMENT PRIMARY KEY,
    id_ticket INT NOT NULL,
    id_agent INT NOT NULL,
    date_affectation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ticket) REFERENCES Ticket(id_ticket),
    FOREIGN KEY (id_agent) REFERENCES Agent(id_agent)
);

-- Table ChatTicket
CREATE TABLE IF NOT EXISTS ChatTicket (
    id_message INT AUTO_INCREMENT PRIMARY KEY,
    id_ticket INT NOT NULL,
    id_agent INT,
    id_client INT NOT NULL,
    contenu TEXT NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_affectation INT NOT NULL,
    FOREIGN KEY (id_ticket) REFERENCES Ticket(id_ticket),
    FOREIGN KEY (id_agent) REFERENCES Agent(id_agent),
    FOREIGN KEY (id_client) REFERENCES Client(ClientID),
    FOREIGN KEY (id_affectation) REFERENCES AffectationTicket(id_affectation)
);

-- Table EvaluationTicket
CREATE TABLE IF NOT EXISTS EvaluationTicket (
    id_evaluation INT AUTO_INCREMENT PRIMARY KEY,
    id_ticket INT NOT NULL,
    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    date_evaluation DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_affectation INT NOT NULL,
    FOREIGN KEY (id_ticket) REFERENCES Ticket(id_ticket),
    FOREIGN KEY (id_affectation) REFERENCES AffectationTicket(id_affectation)
);

-- ======================
-- DONNÉES DE BASE
-- ======================

-- Insertion des états de requête
INSERT INTO Etat_requete (libelle) VALUES 
('Nouvelle'),
('En cours de validation'),
('Validee'),
('Rejetee'),
('Convertie en ticket');

-- Insertion des catégories de tickets
INSERT INTO CategorieTicket (Nom) VALUES 
('Technique'),
('Facturation'),
('Réclamation'),
("Demande d'information"),
('Support');

-- ======================
-- INDEX POUR LES PERFORMANCES
-- ======================

CREATE INDEX idx_requete_client ON RequeteClient(id_client);
CREATE INDEX idx_requete_etat ON RequeteClient(id_etat);
CREATE INDEX idx_ticket_categorie ON Ticket(id_categorie);
CREATE INDEX idx_ticket_requete ON Ticket(id_requete);
CREATE INDEX idx_affectation_ticket ON AffectationTicket(id_ticket);
CREATE INDEX idx_affectation_agent ON AffectationTicket(id_agent);
CREATE INDEX idx_chat_ticket ON ChatTicket(id_ticket);
CREATE INDEX idx_evaluation_ticket ON EvaluationTicket(id_ticket);

-- ======================
-- TRIGGERS POUR LE WORKFLOW
-- ======================

DELIMITER //

-- Trigger après insertion d'un ticket
CREATE TRIGGER after_ticket_insert
AFTER INSERT ON Ticket
FOR EACH ROW
BEGIN
    -- Mettre à jour l'état de la requête associée
    UPDATE RequeteClient 
    SET id_etat = (SELECT id_etat FROM Etat_requete WHERE libelle = 'Convertie en ticket')
    WHERE id_requete = NEW.id_requete;
END//

-- Trigger après évaluation
CREATE TRIGGER after_evaluation_insert
AFTER INSERT ON EvaluationTicket
FOR EACH ROW
BEGIN
    -- Mettre à jour le statut du ticket
    UPDATE Ticket
    SET statut = 'fermé'
    WHERE id_ticket = NEW.id_ticket;
END//

DELIMITER ;

-- ======================
-- VUES UTILES
-- ======================

-- Vue des requêtes non traitées
CREATE VIEW Vue_RequetesNonTraitees AS
SELECT r.id_requete, r.Sujet, r.Date_creation, 
       c.Nom, c.Prenom, e.libelle AS etat
FROM RequeteClient r
JOIN Client c ON r.id_client = c.ClientID
JOIN Etat_requete e ON r.id_etat = e.id_etat
WHERE e.libelle IN ('Nouvelle', 'En cours de validation')
ORDER BY r.Date_creation;

-- Vue des tickets ouverts avec affectation
CREATE VIEW Vue_TicketsOuverts AS
SELECT t.id_ticket, t.priorite, c.Nom AS categorie,
       a.id_agent, e.Nom AS nom_agent, e.Prenom AS prenom_agent,
       rc.Sujet, cl.Nom AS nom_client, cl.Prenom AS prenom_client
FROM Ticket t
JOIN CategorieTicket c ON t.id_categorie = c.id_categorie
JOIN RequeteClient rc ON t.id_requete = rc.id_requete
JOIN Client cl ON rc.id_client = cl.ClientID
LEFT JOIN AffectationTicket at ON t.id_ticket = at.id_ticket
LEFT JOIN Agent a ON at.id_agent = a.id_agent
LEFT JOIN Employe e ON a.id_employe = e.EmployeID
WHERE t.statut != 'fermé'
ORDER BY t.priorite DESC, t.date_creation;