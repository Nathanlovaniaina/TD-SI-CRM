<?php

namespace app\controllers;

use Flight;
use PDO;

class ClientController {

    public function __construct() {
        // Constructeur vide pour le moment
    }

    public function clients() {
        $db = Flight::db();

        try {
            $stmt = $db->prepare("SELECT * FROM Client");
            $stmt->execute();
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            Flight::render('clients_view.php', ['clients' => $clients]);

        } catch (PDOException $e) {
            error_log('Erreur clients : ' . $e->getMessage());
            Flight::halt(500, 'Erreur interne serveur');
        }
    }

    public function client_detail($id) {
        $db = Flight::db();

        try {
            $stmt = $db->prepare("
                SELECT 'Client' AS source, TypeReaction, DateReaction, Contenu
                FROM ReactionClient
                WHERE ClientID = ?
                UNION
                SELECT 'Commerciale' AS source, TypeReaction, DateReaction, Contenu
                FROM ReactionCommerciale
                WHERE ClientID = ?
            ");
            $stmt->execute([$id, $id]);
            $reactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            Flight::render('client_detail.php', [
                'reactions' => $reactions,
                'client_id' => $id
            ]);

        } catch (PDOException $e) {
            error_log('Erreur client_detail : ' . $e->getMessage());
            Flight::halt(500, 'Erreur interne serveur');
        }
    }
}
