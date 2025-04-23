-- Clients
INSERT INTO Client (Nom, Prenom, Telephone, Email, Adresse, Statut) VALUES
('Rakoto', 'Jean', '0341234567', 'jean.rakoto@mail.com', 'Antananarivo', 'Actif'),
('Rasoanaivo', 'Lina', '0321123456', 'lina.rasoanaivo@mail.com', 'Fianarantsoa', 'Prospet'),
('Rasoanaivo', 'Naina', '0323323456', 'lina.rasoanaivo@mail.com', 'Toamasina', 'Inactif');

-- Produits
INSERT INTO Produit (Nom, Categorie, Prix, Stock) VALUES
('Ordinateur portable', 'Informatique', 1500000, 10),
('Imprimante', 'Informatique', 300000, 5),
('Chaise de bureau', 'Mobilier', 120000, 20);

-- Commandes
INSERT INTO Commande (ClientID, DateCommande, MontantTotal, Statut) VALUES
(1, '2025-04-01', 1800000, 'Validée'),
(2, '2025-04-10', 300000, 'En attente');

-- Commande_Produit
INSERT INTO Commande_Produit (CommandeID, ProduitID, Quantite, PrixUnitaire) VALUES
(1, 1, 1, 1500000),
(1, 3, 2, 150000), -- Prix promo pour chaise
(2, 2, 1, 300000);

-- Employés
INSERT INTO Employe (Nom, Poste, Email) VALUES
('Andriamatoa Hery', 'Responsable Commercial', 'hery@entreprise.mg'),
('Ravelo Tiana', 'Support Client', 'tiana@entreprise.mg');

-- Actions Client
INSERT INTO ActionClient (ClientID, EmployeID, TypeAction, CategorieAction, DateAction, Description, CoutAction, StatutAction, Resultat) VALUES
(1, 1, 'Appel', 'Suivi', '2025-04-02', 'Appel pour confirmer la commande', 0, 'Complétée', 'Commande confirmée'),
(2, 2, 'Email', 'Réclamation', '2025-04-11', 'Client se plaint du retard', 0, 'En cours', 'En attente de retour');

-- Réactions Client
INSERT INTO ReactionClient (ActionID, ClientID, TypeReaction, DateReaction, Contenu) VALUES
(1, 1, 'Satisfaction', '2025-04-03', 'Le client est satisfait'),
(2, 2, 'Mécontentement', '2025-04-12', 'Le client attend toujours la livraison');

-- Actions Commerciales
INSERT INTO ActionCommerciale (ActionID, Campagne, Objectif, EstConvertie, cout) VALUES
(1, 'Campagne Pâques', 'Relancer les clients inactifs', TRUE, 50000),
(2, 'Promo Avril', 'Vente de mobilier de bureau', FALSE, 25000);

-- Réactions Commerciales
INSERT INTO ReactionCommerciale (ActionCommercialeID, ClientID, TypeReaction, DateReaction, Contenu) VALUES
(1, 1, 'Achat effectué', '2025-04-04', 'Le client a passé commande'),
(2, 2, 'Intérêt', '2025-04-11', 'Souhaite recevoir plus d\'infos');

-- Requêtes budgétaires
INSERT INTO RequeteBudgetaire (valeur) VALUES
(2500000),
(1750000);
