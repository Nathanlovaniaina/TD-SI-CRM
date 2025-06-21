-- 1. Clients
INSERT INTO Client (Nom, Prenom, Telephone, Email, Adresse, Statut) VALUES
('Dupont', 'Jean', '0612345678', 'jean.dupont@example.com', '12 rue de la Paix, Paris', 'Prospect'),
('Martin', 'Marie', '0623456789', 'marie.martin@example.com', '5 avenue Victor Hugo, Lyon', 'Client'),
('Durand', 'Luc', '0634567890', 'luc.durand@example.com', '8 boulevard Saint-Germain, Paris', 'Client');

-- 2. Produits
INSERT INTO Produit (Nom, Categorie, Prix, Stock) VALUES
('Lait', 'Produits Laitiers', 1.20, 100),
('Pain', 'Boulangerie', 0.80, 200),
('Chocolat', 'Confiserie', 2.50,  50);

-- 3. Employés
INSERT INTO Employe (Nom, Poste, Email) VALUES
('Leroy', 'Commercial', 'alice.leroy@entreprise.com'),
('Petit', 'Manager',    'bob.petit@entreprise.com');

-- 4. Actions clients
INSERT INTO ActionClient (ClientID, EmployeID, TypeAction, CategorieAction, DateAction, Description, CoutAction, StatutAction, Resultat) VALUES
(1, 1, 'Appel Téléphonique', 'Prospection', '2025-04-01', 'Appel de découverte',              5.00, 'Terminé',       'Intéressé'),
(2, 2, 'Email',             'Suivi',       '2025-04-05', 'Envoi de la brochure produit',     1.00, 'En attente',    ''),
(3, 1, 'Visite',            'Négociation', '2025-04-10', 'RDV sur site client',              20.00,'Terminé',       'Contrat signé');

-- 5. Réactions clients
INSERT INTO ReactionClient (ActionID, ClientID, TypeReaction, DateReaction, Contenu) VALUES
(1, 1, 'Positif',      '2025-04-02', 'Demande de devis'),
(2, 2, 'Neutre',       '2025-04-06', 'Pas encore lu'),
(3, 3, 'Très positif', '2025-04-11', 'Contrat signé');

-- 6. Actions commerciales (spécialisation)
INSERT INTO ActionCommerciale (ActionID, Campagne, Objectif, EstConvertie, Cout) VALUES
(1, 'Promo printemps', 'Augmenter ventes de 10%',  TRUE, 100.00),
(3, 'Offre spéciale',  'Fidélisation',           TRUE, 200.00);

-- 7. Réactions commerciales (liées directement à ActionClient)
INSERT INTO ReactionCommerciale (ActionID, ClientID, TypeReaction, DateReaction, Contenu) VALUES
(1, 1, 'Acheté',             '2025-04-03', '2 packs Lait'),
(1, 2, 'Aucun intérêt',      '2025-04-04', 'N/A'),
(3, 3, 'Inscrit',            '2025-04-12', 'Inscrit à la newsletter');

-- 8. Commandes
INSERT INTO Commande (ClientID, DateCommande, MontantTotal, Statut) VALUES
(1, '2025-04-02',  3.20, 'Livrée'),
(2, '2025-04-07',  7.50, 'En cours');

-- 9. Détails de commandes
INSERT INTO Commande_Produit (CommandeID, ProduitID, Quantite, PrixUnitaire) VALUES
(1, 1, 2, 1.20),  -- 2 x Lait
(1, 2, 1, 0.80),  -- 1 x Pain
(2, 3, 3, 2.50);  -- 3 x Chocolat

-- 10. Requêtes budgétaires
INSERT INTO RequeteBudgetaire (valeur) VALUES
(5000.00),
(12000.00);

INSERT INTO TrancheAge (Intitule, AgeMin, AgeMax) VALUES
  ('Jeune',               18, 25),
  ('Parent',                 30, 45),
  ('Professionnel Élégant', 35, 50),
  ('Adolescent(e)',       13, 17),
  ('Travailleur',         25, 55);

-- 1. Clients
INSERT INTO Client (Nom, Prenom, Telephone, Email, Adresse, Statut, DateInscription, Age, Genre) VALUES
  ('Ranaivoson',    'Miora', '0341234567', 'miora.r@example.com',    'Analakely, Antananarivo',     'Actif', '2025-01-15', 22, 'Femme'),
  ('Rakotoarisoa',  'Jean',   '0337654321', 'jean.r@example.com',     'Isoraka, Antananarivo',       'Actif', '2025-02-10', 34, 'Homme'),
  ('Andriamanana',  'Lucie',  '0321122334', 'lucie.a@example.com',    'Ankorondrano, Antananarivo',  'Inactif','2025-03-05', 28, 'Femme'),
  ('Rasoa',         'Hery',   '0349988776', 'hery.r@example.com',     'Ambohizato, Antananarivo',    'Actif', '2025-04-01', 16, 'Homme'),
  ('Rakotondrainy','Fanja',   '0334455667', 'fanja.r@example.com',    'Ivandry, Antananarivo',       'Actif', '2025-04-15', 42, 'Femme');

-- 2. Produits
INSERT INTO Produit (Nom, Categorie, Prix, Stock) VALUES
  ('AirMax Sneakers',          'Sneakers',            75.00, 50),
  ('Sunshine Sandales',        'Sandales',            35.00, 30),
  ('Urban Chic Mocassins',     'Chaussures de ville', 120.00, 20),
  ('TrailBlazer Running',      'Chaussures de sport', 90.00,  40);

-- 3. Commandes
-- On précise MontantTotal calculé à l’avance pour éviter les expressions en SQL
INSERT INTO Commande (ClientID, DateCommande, MontantTotal, Statut) VALUES
  (1, '2025-04-20', 145.00, 'Livrée'),    -- 1×75 + 2×35  = 145
  (2, '2025-05-01', 210.00, 'En cours'),  -- 1×120 + 1×90 = 210
  (3, '2025-04-28', 105.00, 'Annulée'),   -- 3×35         = 105
  (4, '2025-05-02', 165.00, 'En cours');  -- 1×90 + 1×75  = 165

-- 4. Détails des Commandes (Commande_Produit)
INSERT INTO Commande_Produit (CommandeID, ProduitID, Quantite, PrixUnitaire) VALUES
  -- Commande #1 (ID=1)
  (1, 1, 1, 75.00),
  (1, 2, 2, 35.00),

  -- Commande #2 (ID=2)
  (2, 3, 1, 120.00),
  (2, 4, 1, 90.00),

  -- Commande #3 (ID=3)
  (3, 2, 3, 35.00),

  -- Commande #4 (ID=4)
  (4, 4, 1, 90.00),
  (4, 1, 1, 75.00);


















INSERT INTO Client (ClientID, Nom, Prenom, Email, Telephone, Adresse, Statut, DateInscription, Age, Genre) VALUES
(1, 'Dupont', 'Jean', 'jean.dupont@email.com', '0612345678', '12 Rue des Lilas, Paris', 'actif', '2023-01-15', 35, 'M'),
(2, 'Martin', 'Sophie', 'sophie.martin@email.com', '0623456789', '25 Avenue des Roses, Lyon', 'actif', '2023-02-20', 28, 'F'),
(3, 'Bernard', 'Pierre', 'pierre.bernard@email.com', '0634567890', '8 Boulevard des Chênes, Marseille', 'actif', '2023-03-10', 42, 'M'),
(4, 'Petit', 'Marie', 'marie.petit@email.com', '0645678901', '33 Rue des Tulipes, Lille', 'inactif', '2023-04-05', 31, 'F'),
(5, 'Durand', 'Thomas', 'thomas.durand@email.com', '0656789012', '5 Allée des Sapins, Bordeaux', 'actif', '2023-05-12', 45, 'M'),
(7, 'Leroy', 'Julie', 'julie.leroy@email.com', '0667890123', '17 Impasse des Orchidées, Toulouse', 'actif', '2023-06-18', 26, 'F');

INSERT INTO Employe (EmployeID, Nom, Poste, Email) VALUES
(3, 'Roux', 'Support Client', 'support1@entreprise.com'),
(4, 'Lefevre', 'Technicien', 'tech1@entreprise.com'),
(5, 'Moreau', 'Gestionnaire', 'gestion1@entreprise.com'),
(6, 'Fournier', 'Commercial', 'commercial1@entreprise.com');

INSERT INTO Agent (id_agent, id_employe, disponible) VALUES
(1, 1, TRUE),
(2, 2, TRUE),
(3, 3, FALSE),
(4, 4, TRUE);


INSERT INTO Ticket (id_ticket, id_categorie, priorite, prixPrestation, duree) VALUES
(1, 1, 'haute', 50.00, 2),
(2, 2, 'normale', 30.00, 1),
(3, 3, 'normale', 45.00, 2),
(4, 4, 'basse', 60.00, 3),
(5, 3, 'normale', 35.00, 1),
(6, 5, 'haute', 75.00, 4);

INSERT INTO AffectationTicket (id_affectation, id_ticket, id_requete, id_agent, is_valide, date_affectation) VALUES
-- Requête 2 (assignée à l'agent 1)
(1, 2, 2, 1, TRUE, '2023-10-16 14:30:00'),

-- Requête 3 (assignée à l'agent 2)
(2, 3, 3, 2, TRUE, '2023-10-17 11:00:00'),

-- Requête 4 (anciennement assignée à l'agent 3, maintenant résolue)
(3, 4, 4, 3, FALSE, '2023-10-10 17:00:00'),

-- Requête 5 (assignée puis fermée)
(4, 5, 5, 1, FALSE, '2023-10-05 12:00:00'),

-- Requête 6 (urgence assignée à l'agent 4)
(5, 6, 6, 4, TRUE, '2023-10-18 08:30:00');

INSERT INTO EvaluationTicket (id_evaluation, id_ticket, note, commentaire, date_evaluation, id_affectation) VALUES
-- Évaluation pour la requête 4 (résolue)
(1, 4, 5, 'Excellente prise en charge, fonctionnalité très utile !', '2023-10-11 10:00:00', 3),

-- Évaluation pour la requête 5 (fermée)
(2, 5, 3, 'Problème résolu mais le délai était un peu long', '2023-10-06 15:30:00', 4);

-- Commandes référencées dans les requêtes
INSERT INTO Commande (CommandeID, ClientID, DateCommande, MontantTotal, Statut) VALUES
(45879, 2, '2023-10-10', 120.50, 'livrée'),
(48750, 7, '2023-10-17', 89.99, 'payée');

-- Produits pour les réclamations
INSERT INTO Produit (ProduitID, Nom, Categorie, Prix, Stock) VALUES
(7854, 'Smartphone X200', 'Électronique', 599.99, 15),
(1245, 'Casque Bluetooth', 'Accessoires', 89.99, 30);