<?php
namespace app\controllers;

use Flight;

class ChatController {
    public function clientChat() {
        if (session_status() === PHP_SESSION_NONE){
            session_start();
        }
        if ($_SESSION['role'] !== 'client') Flight::redirect('/');
        Flight::render('chat_client.php');
    }

    public function agentChat() {
         if (session_status() === PHP_SESSION_NONE){
            session_start();
        }
        if ($_SESSION['role'] !== 'agent') Flight::redirect('/');
        Flight::render('chat_agent.php');
    }

    public function getMessages($id_affectation) {
        $pdo = Flight::db();
        $stmt = $pdo->prepare("SELECT * FROM ChatTicket WHERE id_affectation = ? ORDER BY date_envoi ASC");
        $stmt->execute([$id_affectation]);
        echo json_encode($stmt->fetchAll());
    }

    public function sendMessage() {
         if (session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $pdo = Flight::db();
        $data = Flight::request()->data;

        $id_ticket = $data->id_ticket;
        $contenu = $data->contenu;
        $id_affectation = $data->id_affectation;

        $id_client = $_SESSION['client_id'] ?? null;
        $id_agent = $_SESSION['agent_id'] ?? null;

        $stmt = $pdo->prepare("INSERT INTO ChatTicket (id_ticket, id_agent, id_client, contenu, id_affectation)
            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id_ticket, $id_agent, $id_client, $contenu, $id_affectation]);

        echo "ok";
    }
}
