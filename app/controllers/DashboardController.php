<?php

namespace app\controllers;

use Flight;
use PDO;

class DashboardController {

	public function __construct() {

	}

    public function dernier_commande() {
        $db = Flight::db(); 
    
        try {
            $stmt = $db->prepare("
                SELECT *
                FROM Commande
                ORDER BY DateCommande DESC
                LIMIT 5
            ");
    
            $stmt->execute();
    
            $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $commandes;
    
        } catch (PDOException $e) {
            error_log('Erreur dernier_commande : ' . $e->getMessage());
            return [];
        }
    }
    

    public function nombre_clients() {
        // Récupère l'instance PDO
        $db = Flight::db();

        try {
            // Prépare et exécute la requête de comptage
            $stmt = $db->prepare("SELECT COUNT(*) AS total FROM Client");
            $stmt->execute();

            // Récupère le résultat
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return isset($row['total']) ? (int)$row['total'] : 0;

        } catch (PDOException $e) {
            error_log('Erreur nombre_clients : ' . $e->getMessage());
            return 0;
        }
    }

    public function nombre_clients_par_genre() {
        // Récupère l'instance PDO
        $db = Flight::db();
    
        try {
            // Prépare et exécute la requête de comptage groupé par genre
            $stmt = $db->prepare("
                SELECT 
                    Genre,
                    COUNT(*) AS total 
                FROM Client 
                GROUP BY Genre
            ");
            $stmt->execute();
    
            // Récupère tous les résultats dans un tableau
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Construit un tableau genre => total
            $result = [];
            foreach ($rows as $row) {
                // Assure-toi que Genre n'est pas NULL selon ta base
                $genre = $row['Genre'] ?? 'Inconnu';
                $result[$genre] = (int)$row['total'];
            }
    
            return $result;
    
        } catch (PDOException $e) {
            error_log('Erreur nombre_clients_par_genre : ' . $e->getMessage());
            return [];
        }
    }
    


    public function nombre_commandes() {
        // Récupère l'instance PDO
        $db = Flight::db();

        try {
            // Prépare et exécute la requête de comptage
            $stmt = $db->prepare("SELECT COUNT(*) AS total FROM Commande");
            $stmt->execute();

            // Récupère le résultat
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return isset($row['total']) ? (int)$row['total'] : 0;

        } catch (PDOException $e) {
            error_log('Erreur nombre_commandes : ' . $e->getMessage());
            return 0;
        }
    }


    public function produits_plus_vendus() {
        $db = Flight::db();

        try {
            $stmt = $db->prepare("
                SELECT 
                    p.nom AS produit,
                    SUM(lc.quantite) AS total_vendu
                FROM Commande_Produit lc
                INNER JOIN Produit p ON lc.ProduitID = p.ProduitID
                GROUP BY p.nom
                ORDER BY total_vendu DESC
                LIMIT 5
            ");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log('Erreur produits_plus_vendus : ' . $e->getMessage());
            return [];
        }
    }

    public function nombre_clients_par_tranche_age() {
        // Récupère l'instance PDO
        $db = Flight::db();
    
        try {
            // Pour chaque tranche d'âge, on compte les clients dont Age est entre AgeMin et AgeMax
            $sql = "
                SELECT 
                    t.Intitule,
                    COUNT(c.ClientID) AS total
                FROM TrancheAge AS t
                LEFT JOIN Client AS c
                  ON c.Age BETWEEN t.AgeMin AND t.AgeMax
                GROUP BY t.TrancheID, t.Intitule
                ORDER BY t.AgeMin
            ";
            $stmt = $db->prepare($sql);
            $stmt->execute();
    
            // Récupère tous les résultats sous forme associatif
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Prépare le retour sous forme ['Le Jeune' => 12, 'Parent' => 34, …]
            $result = [];
            foreach ($rows as $row) {
                $result[$row['Intitule']] = (int)$row['total'];
            }
    
            return $result;
    
        } catch (PDOException $e) {
            error_log('Erreur nombre_clients_par_tranche_age : ' . $e->getMessage());
            return [];
        }
    }
    
    public function nombre_produits_commandes_par_categorie() {
        // Récupère l'instance PDO
        $db = Flight::db();
    
        try {
            // On somme les quantités de chaque produit groupées par catégorie
            $sql = "
                SELECT 
                    p.Categorie,
                    SUM(cp.Quantite) AS total_commandes
                FROM Produit AS p
                INNER JOIN Commande_Produit AS cp
                  ON p.ProduitID = cp.ProduitID
                GROUP BY p.Categorie
                ORDER BY p.Categorie
            ";
            $stmt = $db->prepare($sql);
            $stmt->execute();
    
            // On récupère tous les résultats
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // On construit un tableau associatif [Categorie => total_commandes]
            $result = [];
            foreach ($rows as $row) {
                $result[$row['Categorie']] = (int)$row['total_commandes'];
            }
    
            return $result;
    
        } catch (PDOException $e) {
            error_log('Erreur nombre_produits_commandes_par_categorie : ' . $e->getMessage());
            return [];
        }
    }
    



    public function get_view(){
        $WelcomeController = new WelcomeController();
        return Flight::render('dashboard', [
            'navbar' => $WelcomeController->get_navbar(),
            'commandes'          => $this->dernier_commande(),
            'nb_clients'         => $this->nombre_clients(),
            'nb_commandes'       => $this->nombre_commandes(),
            'produits_plus_vendus' => $this->produits_plus_vendus(),
            'nd_clients_par_genre' => $this->nombre_clients_par_genre(),
            'nombre_clients_par_tranche_age' => $this->nombre_clients_par_tranche_age(),
            'nombre_produits_commandes_par_categorie' => $this->nombre_produits_commandes_par_categorie()
        ]);
    }
    
    

}