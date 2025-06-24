<?php
namespace app\controllers;

use Flight;
use PDO;

class ChatController {
   public function clientChat($id) {
    $WelcomeController = new WelcomeController();
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if ($_SESSION['role'] !== 'client') {
        Flight::redirect('/');
    }

    // Récupérer l'affectation active liée à cette requête client
    $db = Flight::db();
    $query = "
        SELECT at.id_affectation, at.id_ticket
        FROM AffectationTicket at
        JOIN RequeteClient rc ON at.id_requete = rc.id_requete
        WHERE rc.id_requete = :id_requete
        AND rc.id_etat IN (
            SELECT id_etat FROM Etat_requete 
            WHERE libelle IN ('assigné', 'en_cours')
        )
        ORDER BY at.date_affectation DESC
        LIMIT 1
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute([':id_requete' => $id]);
    $affectation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$affectation) {
        // Aucune affectation active trouvée
        Flight::redirect('/requeteClient');
        return;
    }

    Flight::render('chat_client.php', [
        'navbar' => $WelcomeController->get_navbar(),
        'id_requete' => $id,
        'id_affectation' => $affectation['id_affectation'],
        'id_ticket' => $affectation['id_ticket']
    ]);
}

private function getActiveAffectation($id_requete) {
    // Requête pour récupérer l'affectation active
    $sql = "SELECT at.id_affectation 
            FROM AffectationTicket at
            JOIN RequeteClient rc ON at.id_requete = rc.id_requete
            WHERE at.id_requete = :id_requete
            AND at.is_valide = TRUE
            AND rc.id_etat IN (
                SELECT id_etat FROM Etat_requete 
                WHERE libelle IN ('assigné', 'en_cours')
            )
            ORDER BY at.date_affectation DESC
            LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([':id_requete' => $id_requete]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function agentChat($id) {
    $WelcomeController = new WelcomeController();
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // if ($_SESSION['role'] !== 'agent') {
    //     Flight::redirect('/');
    // }

    // Récupérer l'affectation active liée à cette requête client
    // et vérifier que l'agent connecté est bien celui affecté
    $db = Flight::db();
    $query = "
        SELECT at.id_affectation, at.id_ticket, t.priorite, ct.Nom AS categorie
        FROM AffectationTicket at
        JOIN Ticket t ON at.id_ticket = t.id_ticket
        JOIN CategorieTicket ct ON t.id_categorie = ct.id_categorie
        JOIN RequeteClient rc ON at.id_requete = rc.id_requete
        JOIN Agent a ON at.id_agent = a.id_agent
        WHERE rc.id_requete = :id_requete
        AND a.id_employe = :agent_id
        AND rc.id_etat IN (
            SELECT id_etat FROM Etat_requete 
            WHERE libelle IN ('assigné', 'en_cours')
        )
        ORDER BY at.date_affectation DESC
        LIMIT 1
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':id_requete' => $id,
        ':agent_id' => $_SESSION['agent_id']
    ]);
    $affectation = $stmt->fetch(\PDO::FETCH_ASSOC);


    if (!$affectation) {
        // Aucune affectation active trouvée ou agent non autorisé
        Flight::redirect('/requeteClient');
        return;
    }

    Flight::render('chat_agent', [
        'navbar' => $WelcomeController->get_navbar(),
        'id_requete' => $id,
        'id_affectation' => $affectation['id_affectation'],
        'id_ticket' => $affectation['id_ticket'],
        'priorite' => $affectation['priorite'],
        'categorie' => $affectation['categorie']
    ]);
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
