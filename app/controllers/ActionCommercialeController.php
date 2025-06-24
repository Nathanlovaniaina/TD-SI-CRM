<?php

namespace app\controllers;

use Flight;
use PDO;

class ActionCommercialeController {

    public function __construct() {
        // Constructeur vide
    }

    public function actionsCommerciales() {
        $db = Flight::db();
        $WelcomeController = new WelcomeController();
        try {
            $stmt = $db->prepare("SELECT * FROM ActionCommerciale");
            $stmt->execute();
            $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            Flight::render('actions_commerciales_view.php', ['actions' => $actions, 'navbar' => $WelcomeController->get_navbar()]);

        } catch (PDOException $e) {
            error_log('Erreur actionsCommerciales : ' . $e->getMessage());
            Flight::halt(500, 'Erreur interne serveur');
        }
    }

    public function add_action_commerciale() {
        $db = Flight::db();

        $_POST = Flight::request()->data;
        $actionID = 1;
        $campagne = $_POST['Campagne'] ?? null;
        $objectif = $_POST['Objectif'] ?? null;
        $estConvertie = isset($_POST['EstConvertie']) ? (bool)$_POST['EstConvertie'] : false;
        $cout = $_POST['Cout'] ?? null;
        $clientRate = $_POST['ClientRate'] ?? null;
        $commandeRate = $_POST['CommandeRate'] ?? null;
        $prixRate = $_POST['PrixRate'] ?? null;

        if ($actionID && $campagne && $objectif && $cout !== null && 
            $clientRate !== null && $commandeRate !== null && $prixRate !== null) {
            try {
                $stmt = $db->prepare('INSERT INTO ActionCommerciale 
                                    (ActionID, Campagne, Objectif, EstConvertie, Cout, ClientRate, CommandeRate, PrixRate) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([$actionID, $campagne, $objectif, $estConvertie, $cout, $clientRate, $commandeRate, $prixRate]);

                Flight::redirect('/actioncommercial');
            } catch (PDOException $e) {
                error_log('Erreur add_action_commerciale : ' . $e->getMessage());
                Flight::halt(500, 'Erreur lors de l\'ajout');
            }
        } else {
            Flight::halt(400, 'Paramètres manquants');
        }
    }

    public function update_action_commerciale() {
        $db = Flight::db();

        $actionCommercialeID = $_POST['ActionCommercialeID'] ?? null;
        $actionID = $_POST['ActionID'] ?? null;
        $campagne = $_POST['Campagne'] ?? null;
        $objectif = $_POST['Objectif'] ?? null;
        $estConvertie = isset($_POST['EstConvertie']) ? (bool)$_POST['EstConvertie'] : false;
        $cout = $_POST['Cout'] ?? null;
        $clientRate = $_POST['ClientRate'] ?? null;
        $commandeRate = $_POST['CommandeRate'] ?? null;
        $prixRate = $_POST['PrixRate'] ?? null;

        if ($actionCommercialeID && $actionID && $campagne && $objectif && $cout !== null &&
            $clientRate !== null && $commandeRate !== null && $prixRate !== null) {
            try {
                $stmt = $db->prepare('UPDATE ActionCommerciale 
                                    SET ActionID = ?, Campagne = ?, Objectif = ?, 
                                        EstConvertie = ?, Cout = ?, ClientRate = ?, CommandeRate = ?, PrixRate = ?
                                    WHERE ActionCommercialeID = ?');
                $stmt->execute([$actionID, $campagne, $objectif, $estConvertie, $cout, $clientRate, $commandeRate, $prixRate, $actionCommercialeID]);

                Flight::redirect('/actions-commerciales');
            } catch (PDOException $e) {
                error_log('Erreur update_action_commerciale : ' . $e->getMessage());
                Flight::halt(500, 'Erreur lors de la mise à jour');
            }
        } else {
            Flight::halt(400, 'Paramètres manquants');
        }
    }

    public function delete_action_commerciale() {
        $db = Flight::db();

        $_POST = Flight::request()->data;
        $actionCommercialeID = $_POST['ActionCommercialeID'] ?? null;

        if ($actionCommercialeID) {
            try {
                $stmt = $db->prepare('DELETE FROM ActionCommerciale WHERE ActionCommercialeID = ?');
                $stmt->execute([$actionCommercialeID]);

                Flight::redirect('/actioncommercial');
            } catch (PDOException $e) {
                error_log('Erreur delete_action_commerciale : ' . $e->getMessage());
                Flight::halt(500, 'Erreur lors de la suppression');
            }
        } else {
            Flight::halt(400, 'ID manquant');
        }
    }

}