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


    public function get_view(){
        return Flight::render('dashboard', [
            'commandes'    => $this->dernier_commande(),
            'nb_clients'   => $this->nombre_clients(),
            'nb_commandes' => $this->nombre_commandes()
        ]);
    }
    

}