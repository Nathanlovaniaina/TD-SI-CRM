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
