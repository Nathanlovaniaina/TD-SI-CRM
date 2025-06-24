<?php
namespace app\controllers;

use Flight;

class EvaluationController {
    public function showForm($id_ticket,$id_affectation) {
        $WelcomeController = new WelcomeController();
        Flight::render('evaluation_form', [
            'navbar' => $WelcomeController->get_navbar(),
            'id_ticket' => $id_ticket,
            'id_affectation' => $id_affectation
        ]);
    }

    public function submit() {
        $pdo = Flight::db();
        $data = Flight::request()->data;

        $stmt = $pdo->prepare("INSERT INTO EvaluationTicket (id_ticket, note, commentaire, id_affectation)
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['id_ticket'],
            $data['note'],
            $data['commentaire'],
            $data['id_affectation']
        ]);

        Flight::redirect('/requeteClient');
    }

    public function moyenneParAgent($id_agent) {
        $pdo = Flight::db();
        $stmt = $pdo->prepare("
            SELECT AVG(e.note) as moyenne
            FROM EvaluationTicket e
            JOIN AffectationTicket a ON e.id_affectation = a.id_affectation
            WHERE a.id_agent = ?
        ");
        $stmt->execute([$id_agent]);
        $result = $stmt->fetch();

        Flight::json($result);
    }
}
?>