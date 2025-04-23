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
    

    public function get_view(){
        return Flight::render('dashboard', ['commandes' => $this->dernier_commande()]);
    }

}