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
-- TABLE Etat_requete
-- ======================
CREATE TABLE IF NOT EXISTS Etat_requete (
    id_etat INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
);

-- ======================
-- TABLE RequeteClient
-- ======================
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

-- ======================
-- TABLE CategorieTicket
-- ======================
CREATE TABLE IF NOT EXISTS CategorieTicket (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    Nom VARCHAR(100) NOT NULL
);

-- ======================
-- TABLE Agent
-- ======================
CREATE TABLE IF NOT EXISTS Agent (
    id_agent INT AUTO_INCREMENT PRIMARY KEY,
    id_employe INT NOT NULL,
    disponible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_employe) REFERENCES Employe(EmployeID)
);

-- ======================
-- TABLE Ticket
-- ======================
CREATE TABLE IF NOT EXISTS Ticket (
    id_ticket INT AUTO_INCREMENT PRIMARY KEY,
    id_categorie INT NOT NULL,
    priorite VARCHAR(50) NOT NULL,
    prixPrestation DECIMAL(10, 2) NOT NULL,
    duree INT NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES CategorieTicket(id_categorie)
);

-- ======================
-- TABLE AffectationTicket
-- ======================
CREATE TABLE IF NOT EXISTS AffectationTicket (
    id_affectation INT AUTO_INCREMENT PRIMARY KEY,
    id_ticket INT NOT NULL,
    id_requete INT NOT NULL,
    id_agent INT NOT NULL,
    is_valide BOOLEAN DEFAULT FALSE,
    date_affectation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ticket) REFERENCES Ticket(id_ticket),
    FOREIGN KEY (id_requete) REFERENCES RequeteClient(id_requete),
    FOREIGN KEY (id_agent) REFERENCES Agent(id_agent)
);

-- ======================
-- TABLE ChatTicket
-- ======================
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

-- ======================
-- TABLE EvaluationTicket
-- ======================
CREATE TABLE IF NOT EXISTS EvaluationTicket (
    id_evaluation INT AUTO_INCREMENT PRIMARY KEY,
    id_ticket INT NOT NULL,
    note INT NOT NULL,
    commentaire TEXT,
    date_evaluation DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_affectation INT NOT NULL,
    FOREIGN KEY (id_ticket) REFERENCES Ticket(id_ticket),
    FOREIGN KEY (id_affectation) REFERENCES AffectationTicket(id_affectation),
    CONSTRAINT chk_note CHECK (note BETWEEN 1 AND 5)
);

-- ======================
-- INSERTION DES ÉTATS DE REQUÊTE
-- ======================
INSERT INTO Etat_requete (libelle) VALUES 
('ouvert'),
('assigné'),
('en_cours'),
('résolu'),
('fermé');

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
CREATE INDEX idx_affectation_ticket ON AffectationTicket(id_ticket);
CREATE INDEX idx_affectation_requete ON AffectationTicket(id_requete);
CREATE INDEX idx_affectation_agent ON AffectationTicket(id_agent);
CREATE INDEX idx_chat_ticket ON ChatTicket(id_ticket);
CREATE INDEX idx_evaluation_ticket ON EvaluationTicket(id_ticket);

-- ======================
-- TRIGGERS POUR LE WORKFLOW
-- ======================
DELIMITER //

-- Mise à jour de l'état quand une affectation est validée
CREATE TRIGGER after_affectation_validee
AFTER UPDATE ON AffectationTicket
FOR EACH ROW
BEGIN
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
END//

DELIMITER ;

-- ======================
-- VUES UTILES
-- ======================

-- Vue des requêtes avec état
CREATE VIEW Vue_RequetesAvecEtat AS
SELECT 
    r.id_requete, 
    r.Sujet, 
    r.Date_creation, 
    e.libelle AS etat,
    CONCAT(c.Nom, ' ', c.Prenom) AS client
FROM RequeteClient r
JOIN Etat_requete e ON r.id_etat = e.id_etat
JOIN Client c ON r.id_client = c.ClientID;

-- Vue des tickets avec affectations
CREATE VIEW Vue_TicketsAffectes AS
SELECT 
    t.id_ticket,
    c.Nom AS categorie,
    t.priorite,
    a.id_agent,
    at.is_valide,
    at.date_affectation,
    e.libelle AS etat_requete
FROM Ticket t
JOIN CategorieTicket c ON t.id_categorie = c.id_categorie
JOIN AffectationTicket at ON t.id_ticket = at.id_ticket
JOIN RequeteClient r ON at.id_requete = r.id_requete
JOIN Etat_requete e ON r.id_etat = e.id_etat
JOIN Agent a ON at.id_agent = a.id_agent;

INSERT INTO RequeteClient (id_client, Sujet, Description, Date_creation, FichierJoint, id_etat) VALUES
-- Requête 1 (ouverte)
(1, 'Problème de connexion', 'Je ne parviens pas à me connecter à mon compte depuis hier matin.', '2023-10-15 09:23:45', NULL, 1),

-- Requête 2 (assignée)
(3, 'Facture erronée', 'Ma facture du 10/10/2023 présente un montant incorrect de 150€ au lieu de 120€.', '2023-10-16 14:12:33', 'facture_erreur.pdf', 2),

-- Requête 3 (en cours)
(2, 'Retard de livraison', 'Ma commande #45879 devait être livrée hier mais n\'est toujours pas arrivée.', '2023-10-17 10:45:21', NULL, 3),

-- Requête 4 (résolue)
(5, 'Demande de fonctionnalité', 'Serait-il possible d\'ajouter un export Excel des historiques de commandes ?', '2023-10-10 16:30:15', 'exemple_export.xlsx', 4),

-- Requête 5 (fermée)
(4, 'Réclamation produit défectueux', 'Le produit reçu (réf. #P7854) est endommagé. Pièce jointe jointe.', '2023-10-05 11:20:05', 'produit_defect.jpg', 5),

-- Requête 6 (ouverte - urgence)
(7, 'Urgent! Commande bloquée', 'Ma commande #48750 est bloquée à l\'étape de paiement depuis 1h.', '2023-10-18 08:05:37', 'capture_ecran_paiement.png', 1);

ALTER TABLE Client
ADD COLUMN mot_de_passe VARCHAR(20);

ALTER TABLE Employe
ADD COLUMN mot_de_passe VARCHAR(20);

ALTER TABLE ChatTicket MODIFY id_client INT;
