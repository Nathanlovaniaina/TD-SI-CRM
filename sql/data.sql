-- 1. Insertion des Rôles
INSERT INTO Roles (NomRole, Permissions) VALUES
('Administrateur', 'Toutes permissions'),
('Caissier', 'Gestion caisse, ventes'),
('Gestionnaire', 'Gestion stock, produits'),
('Responsable', 'Supervision, rapports');

-- 2. Insertion des Unités
INSERT INTO Unites (NomUnite, Symbole) VALUES
('Kilogramme', 'kg'),
('Gramme', 'g'),
('Litre', 'L'),
('Pièce', 'pce'),
('Pack', 'pk');

-- 3. Insertion des Catégories
INSERT INTO Categories (NomCategorie, Description) VALUES
('Fruits et Légumes', 'Produits frais'),
('Produits Laitiers', 'Lait, fromages, yaourts'),
('Viandes', 'Viandes fraîches et transformées'),
('Epicerie', 'Produits secs et conserves'),
('Boissons', 'Eaux, jus, sodas');

-- 4. Insertion des Fournisseurs
INSERT INTO Fournisseurs (Nom, Contact, Adresse, Telephone) VALUES
('FreshFood SA', 'Jean Dupont', '123 Rue des Fermiers, Paris', '0123456789'),
('Laitiers & Cie', 'Marie Lambert', '456 Avenue des Vaches, Lyon', '0478563412'),
('Primeur du Sud', 'Lucie Martin', '789 Boulevard Méditerranée, Marseille', '0498765432'),
('Epicerie Monde', 'Ahmed Khan', '321 Rue Globale, Lille', '0321654987');

-- 5. Insertion des Caisses
INSERT INTO Caisse (NumeroCaisse, Emplacement, EstActive, DateMiseEnService) VALUES
('CAISSE1', 'Entrée principale', TRUE, '2023-01-15'),
('CAISSE2', 'Entrée principale', TRUE, '2023-01-15'),
('CAISSE3', 'Rayon épicerie', FALSE, '2023-03-10'),
('CAISSE4', 'Rayon frais', TRUE, '2023-05-20');

-- 6. Insertion des Utilisateurs
INSERT INTO Utilisateurs (RoleID, NomComplet, Email, Login, MotDePasse, EstActif) VALUES
(1, 'Admin System', 'admin@supermarche.com', 'admin', SHA2('admin123', 256), TRUE),
(2, 'Pierre Caissier', 'pierre@supermarche.com', 'pierre', SHA2('pierre456', 256), TRUE),
(3, 'Sophie Stock', 'sophie@supermarche.com', 'sophie', SHA2('sophie789', 256), TRUE),
(2, 'Marc Accueil', 'marc@supermarche.com', 'marc', SHA2('marc101', 256), FALSE);

-- 7. Insertion des Clients
INSERT INTO Clients (Nom, Prenom, Telephone, Email, Adresse, PointsFidelite, DateInscription) VALUES
('Dupont', 'Alice', '0612345678', 'alice.dupont@mail.com', '10 Rue des Fleurs, Paris', 150, '2023-02-10'),
('Martin', 'Bernard', '0698765432', 'bernard.martin@mail.com', '25 Avenue du Soleil, Lyon', 75, '2023-03-15'),
('Petit', 'Claire', NULL, 'claire.petit@mail.com', '5 Boulevard Central, Marseille', 200, '2023-01-05'),
('Durand', NULL, '0687654321', NULL, '15 Rue Principale, Lille', 0, '2023-04-20');

-- 8. Insertion des Produits
INSERT INTO Produits (Designation, Prix, UniteID, QuantiteEnStock, SeuilAlerte, CategorieID, FournisseurID, CodeBarre) VALUES
('Pommes Golden', 2.99, 1, 50, 10, 1, 1, '123456789012'),
('Lait entier 1L', 1.20, 3, 100, 20, 2, 2, '234567890123'),
('Steak haché 5%', 5.50, 1, 30, 15, 3, 1, '345678901234'),
('Riz basmati 1kg', 2.30, 1, 80, 25, 4, 4, '456789012345'),
('Eau minérale 1.5L', 0.85, 3, 120, 30, 5, 4, '567890123456'),
('Yaourt nature x4', 1.75, 5, 60, 15, 2, 2, '678901234567'),
('Tomates', 3.20, 1, 40, 10, 1, 3, '789012345678'),
('Pâtes spaghetti 500g', 1.10, 2, 90, 20, 4, 4, '890123456789');

-- 9. Insertion des Achats (Transactions)
INSERT INTO Achats (UtilisateurID, CaisseID, ClientID, TotalHT, TotalTTC, Remise, MoyenPaiement, NumeroTicket) VALUES
(2, 1, 1, 15.50, 18.60, 0.00, 'Carte', 'TICKET-2023-001'),
(2, 1, NULL, 8.20, 9.84, 0.00, 'Espèces', 'TICKET-2023-002'),
(4, 2, 3, 22.75, 27.30, 2.00, 'Carte', 'TICKET-2023-003'),
(2, 1, 2, 5.30, 6.36, 0.00, 'Mobile', 'TICKET-2023-004');

-- 10. Insertion des Détails d'Achat
INSERT INTO DetailsAchats (AchatID, ProduitID, CaisseID, Quantite, PrixUnitaire, RemiseUnitaire, TauxTVA, SousTotal) VALUES
(1, 1, 1, 2, 2.99, 0.00, 5.5, 5.98),
(1, 3, 1, 1, 5.50, 0.00, 20.0, 5.50),
(1, 5, 1, 3, 0.85, 0.00, 5.5, 2.55),
(2, 2, 1, 2, 1.20, 0.00, 5.5, 2.40),
(2, 8, 1, 1, 1.10, 0.00, 5.5, 1.10),
(2, 6, 1, 2, 1.75, 0.00, 5.5, 3.50),
(3, 4, 2, 3, 2.30, 0.50, 5.5, 5.40),
(3, 7, 2, 2, 3.20, 0.00, 5.5, 6.40),
(3, 1, 2, 5, 2.99, 0.00, 5.5, 14.95),
(4, 5, 1, 4, 0.85, 0.00, 5.5, 3.40),
(4, 8, 1, 1, 1.10, 0.00, 5.5, 1.10),
(4, 2, 1, 1, 1.20, 0.00, 5.5, 1.20);

-- 11. Insertion des Mouvements de Stock
INSERT INTO MouvementsStock (ProduitID, TypeMouvement, Quantite, UtilisateurID, Raison, Reference) VALUES
(1, 'Réception', 100, 3, 'Livraison hebdomadaire', 'LIV-2023-001'),
(2, 'Réception', 150, 3, 'Commande standard', 'LIV-2023-001'),
(3, 'Réception', 50, 3, 'Commande viandes', 'LIV-2023-002'),
(1, 'Vente', -2, 2, 'Vente client', 'TICKET-2023-001'),
(3, 'Vente', -1, 2, 'Vente client', 'TICKET-2023-001'),
(5, 'Vente', -3, 2, 'Vente client', 'TICKET-2023-001'),
(1, 'Ajustement', -5, 3, 'Détérioration', 'AJUST-2023-001');

-- 12. Insertion des Promotions
INSERT INTO Promotions (ProduitID, Debut, Fin, TypePromotion, ValeurPromotion, Description) VALUES
(1, '2023-06-01 00:00:00', '2023-06-07 23:59:59', 'Pourcentage', 15.00, 'Promo pommes été'),
(6, '2023-06-15 00:00:00', '2023-06-30 23:59:59', 'Montant', 0.25, 'Réduction yaourts'),
(4, '2023-07-01 00:00:00', '2023-07-15 23:59:59', 'Pourcentage', 10.00, 'Promo riz été');

-- 13. Insertion des Paiements
INSERT INTO Paiements (AchatID, TypePaiement, Montant, Reference) VALUES
(1, 'Carte', 18.60, 'PAY-2023-001'),
(2, 'Espèces', 9.84, 'PAY-2023-002'),
(3, 'Carte', 27.30, 'PAY-2023-003'),
(4, 'Mobile', 6.36, 'PAY-2023-004');