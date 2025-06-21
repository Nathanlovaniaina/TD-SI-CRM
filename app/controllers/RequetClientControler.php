<?php

namespace app\controllers;

use app\service\RequeteClientDAO;
use Flight;
use PDO;

class RequetClientControler {

	public function __construct() {

	}
	
	public static function verifieLogin($identifiant, $mdp) {
		$db = Flight::db(); // Connexion PDO
		$stmt = $db->prepare("SELECT * FROM User WHERE identifiant = :identifiant AND mdp = :mdp");
		$stmt->execute([':identifiant' => $identifiant , ':mdp' => $mdp]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		print_r($user);
	
		if ($user) {
			// Connexion réussie, tu peux stocker l'utilisateur en session
			$_SESSION['user_id'] = $user['id_user'];
			$_SESSION['identifiant'] = $user['identifiant'];
			$_SESSION['id_departement'] = $user['id_departement'];
			return true;
		}
	
		return false;
	}

    public static function getAllRequeteClients() {
    $db = Flight::db();
    
    // Construction de la requête SQL avec filtres
    $sql = "SELECT rc.*, 
                   c.Nom AS client_nom, 
                   c.Prenom AS client_prenom,
                   er.libelle AS etat_libelle
            FROM RequeteClient rc
            JOIN Client c ON rc.id_client = c.ClientID
            JOIN Etat_requete er ON rc.id_etat = er.id_etat
            WHERE 1=1"; // Toujours vrai pour faciliter l'ajout de conditions
    
    $params = [];
    
    // Filtre par état
    if (!empty($_GET['filter_etat'])) {
        $sql .= " AND rc.id_etat = :etat";
        $params[':etat'] = $_GET['filter_etat'];
    }
    
    // Filtre par client (recherche par nom)
    if (!empty($_GET['filter_client'])) {
        $sql .= " AND (c.Nom LIKE :client OR c.Prenom LIKE :client)";
        $params[':client'] = '%' . $_GET['filter_client'] . '%';
    }
    
    // Filtre par date exacte
    if (!empty($_GET['filter_date'])) {
        $sql .= " AND DATE(rc.Date_creation) = :date";
        $params[':date'] = $_GET['filter_date'];
    }
    
    // Tri et fin de requête
    $sql .= " ORDER BY rc.Date_creation DESC";
    
    // Préparation et exécution
    $stmt = $db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    
    // Récupération des états pour le filtre
    $etats = $db->query("SELECT * FROM Etat_requete ORDER BY id_etat")->fetchAll(PDO::FETCH_ASSOC);
    
    return Flight::render("requeteClients", [
        "requeteClient" => $stmt->fetchAll(PDO::FETCH_ASSOC),
        "etats" => $etats,
        "filters" => $_GET // Pour pré-remplir les champs du formulaire
    ]);
}

public static function getRequeteDetails($id_requete) {
    $db = Flight::db();
    
    try {
        // 1. Informations de base de la requête
        $sql = "SELECT rc.*, 
                       c.Nom AS client_nom, 
                       c.Prenom AS client_prenom,
                       c.Email AS client_email,
                       c.Telephone AS client_telephone,
                       er.libelle AS etat_libelle
                FROM RequeteClient rc
                JOIN Client c ON rc.id_client = c.ClientID
                JOIN Etat_requete er ON rc.id_etat = er.id_etat
                WHERE rc.id_requete = :id_requete";
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_requete', $id_requete, PDO::PARAM_INT);
        $stmt->execute();
        $requete = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$requete) {
            Flight::notFound();
            return;
        }

        // 2. Historique des agents assignés
        $sql = "SELECT at.*, 
                       e.Nom AS agent_nom,
                       e.Email AS agent_email
                FROM AffectationTicket at
                JOIN Agent a ON at.id_agent = a.id_agent
                JOIN Employe e ON a.id_employe = e.EmployeID
                WHERE at.id_requete = :id_requete
                ORDER BY at.date_affectation DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_requete', $id_requete, PDO::PARAM_INT);
        $stmt->execute();
        $agentsAssignes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 3. Historique des messages du chat
        $sql = "SELECT ct.*,
                       CASE 
                           WHEN ct.id_agent IS NOT NULL THEN e.Nom
                           ELSE c.Nom
                       END AS expediteur_nom
                FROM ChatTicket ct
                LEFT JOIN Agent a ON ct.id_agent = a.id_agent
                LEFT JOIN Employe e ON a.id_employe = e.EmployeID
                LEFT JOIN Client c ON ct.id_client = c.ClientID
                WHERE ct.id_ticket = :id_requete
                ORDER BY ct.date_envoi ASC";
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_requete', $id_requete, PDO::PARAM_INT);
        $stmt->execute();
        $messagesChat = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 4. Évaluations associées (version corrigée)
        $sql = "SELECT et.*, 
                       c.Nom AS evaluateur_nom
                FROM EvaluationTicket et
                JOIN AffectationTicket at ON et.id_affectation = at.id_affectation
                JOIN RequeteClient rc ON at.id_requete = rc.id_requete
                JOIN Client c ON rc.id_client = c.ClientID
                WHERE et.id_ticket = :id_requete
                ORDER BY et.date_evaluation DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_requete', $id_requete, PDO::PARAM_INT);
        $stmt->execute();
        $evaluations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 5. Liste des agents disponibles pour réassignation
        $sql = "SELECT a.id_agent, 
                       e.Nom, 
                       e.Email,
                       COUNT(at.id_affectation) AS nb_requetes_actives
                FROM Agent a
                JOIN Employe e ON a.id_employe = e.EmployeID
                LEFT JOIN AffectationTicket at ON a.id_agent = at.id_agent 
                    AND at.is_valide = 1
                    AND at.id_requete IN (
                        SELECT id_requete 
                        FROM RequeteClient 
                        WHERE id_etat IN (
                            SELECT id_etat 
                            FROM Etat_requete 
                            WHERE libelle IN ('assigné', 'en_cours')
                        )
                    )
                WHERE a.disponible = 1
                GROUP BY a.id_agent
                ORDER BY nb_requetes_actives ASC, e.Nom ASC";
        
        $agentsDisponibles = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        return Flight::render("requete_details", [
            'requete' => $requete,
            'agentsAssignes' => $agentsAssignes,
            'messagesChat' => $messagesChat,
            'evaluations' => $evaluations,
            'agentsDisponibles' => $agentsDisponibles,
            'base_url' => Flight::get('flight.base_url')
        ]);

    } catch (PDOException $e) {
        Flight::error($e);
    }
}
public static function assignerAgent($id_requete) {
    $db = Flight::db();
    $data = Flight::request()->data;
    
    try {
        // Désactiver les anciennes affectations
        $sql = "UPDATE AffectationTicket 
                SET is_valide = 0 
                WHERE id_requete = :id_requete";
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_requete', $id_requete, PDO::PARAM_INT);
        $stmt->execute();

        // Créer la nouvelle affectation
        $sql = "INSERT INTO AffectationTicket 
                (id_ticket, id_requete, id_agent, is_valide, date_affectation) 
                VALUES (
                    (SELECT id_ticket FROM Ticket WHERE id_requete = :id_requete),
                    :id_requete,
                    :id_agent,
                    1,
                    NOW()
                )";
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_requete', $id_requete, PDO::PARAM_INT);
        $stmt->bindValue(':id_agent', $data->id_agent, PDO::PARAM_INT);
        $stmt->execute();

        // Mettre à jour l'état de la requête
        $sql = "UPDATE RequeteClient 
                SET id_etat = (
                    SELECT id_etat 
                    FROM Etat_requete 
                    WHERE libelle = 'en_cours'
                ) 
                WHERE id_requete = :id_requete";
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_requete', $id_requete, PDO::PARAM_INT);
        $stmt->execute();

        // Retourner une réponse JSON
        Flight::json([
            'success' => true,
            'message' => 'Agent assigné avec succès'
        ]);

    } catch (PDOException $e) {
        Flight::json([
            'success' => false,
            'message' => 'Erreur lors de l\'assignation',
            'error' => $e->getMessage()
        ], 500);
    }
}
	
}