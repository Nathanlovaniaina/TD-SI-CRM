<?php

namespace app\controllers;

use Flight;
use PDO;

class CommandeController {

    public function __construct() {
        // Constructeur vide
    }

    public function commandes() {
        $db = Flight::db();
        $WelcomeController = new WelcomeController();
        try {
            $stmt = $db->prepare("SELECT * FROM Commande");
            $stmt->execute();
            $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            Flight::render('commandes_view.php', ['navbar' => $WelcomeController->get_navbar(),'commandes' => $commandes]);

        } catch (PDOException $e) {
            error_log('Erreur commandes : ' . $e->getMessage());
            Flight::halt(500, 'Erreur interne serveur');
        }
    }

    public function add_commande() {
        $db = Flight::db();

        $clientID = $_POST['ClientID'] ?? null;
        $montant = $_POST['MontantTotal'] ?? null;
        $statut = $_POST['Statut'] ?? null;

        if ($clientID && $montant && $statut) {
            try {
                $stmt = $db->prepare('INSERT INTO Commande (ClientID, DateCommande, MontantTotal, Statut) VALUES (?, NOW(), ?, ?)');
                $stmt->execute([$clientID, $montant, $statut]);

                Flight::redirect('/commandes');
            } catch (PDOException $e) {
                error_log('Erreur add_commande : ' . $e->getMessage());
                Flight::halt(500, 'Erreur lors de l\'ajout');
            }
        } else {
            Flight::halt(400, 'Paramètres manquants');
        }
    }

    public function delete_commande() {
        $db = Flight::db();

        $commandeID = $_POST['CommandeID'] ?? null;

        if ($commandeID) {
            try {
                $stmt = $db->prepare('DELETE FROM Commande WHERE CommandeID = ?');
                $stmt->execute([$commandeID]);

                Flight::redirect('/commandes');
            } catch (PDOException $e) {
                error_log('Erreur delete_commande : ' . $e->getMessage());
                Flight::halt(500, 'Erreur lors de la suppression');
            }
        } else {
            Flight::halt(400, 'ID manquant');
        }
    }
}
