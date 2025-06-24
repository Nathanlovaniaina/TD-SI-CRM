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
        
        if (session_status() === PHP_SESSION_NONE){
            session_start();
        }

        $role = $_SESSION['role'] ?? 'client'; // Exemple basique
        if($role == "client"){
            $userId = (string)$_SESSION['client_id'];
        }
        if($role == "agent"){
            $userId =  (string)$_SESSION['agent_id'];
        }

        
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
        
        // Si l'utilisateur est un agent, on filtre pour ne voir que ses requêtes affectées
        if ($role === 'agent' && $userId) {
            $sql .= " AND EXISTS (
                        SELECT 1 FROM AffectationTicket at
                        JOIN Agent a ON at.id_agent = a.id_agent
                        WHERE at.id_requete = rc.id_requete
                        AND a.id_employe = :user_id
                    )";
            $params[':user_id'] = $userId;
        }

        // Si l'utilisateur est un client, on filtre pour ne voir que ses propres requêtes
        if ($role === 'client' && $userId) {
            $sql .= " AND rc.id_client = :user_id";
            $params[':user_id'] = $userId;
        }

        // Filtre par état
        if (!empty($_GET['filter_etat'])) {
            $sql .= " AND rc.id_etat = :etat";
            $params[':etat'] = $_GET['filter_etat'];
        }

        // Filtre par client (recherche par nom) - seulement pour les admins/agents
        if (!empty($_GET['filter_client']) && $role !== 'client') {
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
        $WelcomeController = new WelcomeController();

        return Flight::render("requeteClients", [
            'navbar' => $WelcomeController->get_navbar(),
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

        // 2. Historique des agents assignés (sans Prenom)
        $sql = "SELECT at.*, 
                       e.Nom AS agent_nom,
                       e.Email AS agent_email,
                       t.priorite,
                       t.prixPrestation,
                       t.duree,
                       ct.Nom AS categorie_nom
                FROM AffectationTicket at
                JOIN Agent a ON at.id_agent = a.id_agent
                JOIN Employe e ON a.id_employe = e.EmployeID
                LEFT JOIN Ticket t ON at.id_ticket = t.id_ticket
                LEFT JOIN CategorieTicket ct ON t.id_categorie = ct.id_categorie
                WHERE at.id_requete = :id_requete
                ORDER BY at.date_affectation DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_requete', $id_requete, PDO::PARAM_INT);
        $stmt->execute();
        $agentsAssignes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 3. Historique des messages du chat (sans Prenom)
        $sql = "SELECT ct.*,
                       CASE 
                           WHEN ct.id_agent IS NOT NULL THEN e.Nom
                           ELSE c.Nom
                       END AS expediteur_nom
                FROM ChatTicket ct
                LEFT JOIN Agent a ON ct.id_agent = a.id_agent
                LEFT JOIN Employe e ON a.id_employe = e.EmployeID
                LEFT JOIN Client c ON ct.id_client = c.ClientID
                WHERE ct.id_affectation IN (
                    SELECT id_affectation FROM AffectationTicket WHERE id_requete = :id_requete
                )
                ORDER BY ct.date_envoi ASC";
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_requete', $id_requete, PDO::PARAM_INT);
        $stmt->execute();
        $messagesChat = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 4. Évaluations associées (sans Prenom)
        $sql = "SELECT et.*, 
                       c.Nom AS evaluateur_nom
                FROM EvaluationTicket et
                JOIN AffectationTicket at ON et.id_affectation = at.id_affectation
                JOIN RequeteClient rc ON at.id_requete = rc.id_requete
                JOIN Client c ON rc.id_client = c.ClientID
                WHERE at.id_requete = :id_requete
                ORDER BY et.date_evaluation DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_requete', $id_requete, PDO::PARAM_INT);
        $stmt->execute();
        $evaluations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 5. Liste des agents disponibles (sans Prenom)
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

        // 6. Récupérer la dernière affectation valide
        $affectationActive = null;
        foreach ($agentsAssignes as $affectation) {
            if ($affectation['is_valide']) {
                $affectationActive = $affectation;
                break;
            }
        }

        // 7. Liste des catégories de tickets
        $categories = $db->query("SELECT * FROM CategorieTicket")->fetchAll(PDO::FETCH_ASSOC);
        $WelcomeController = new WelcomeController();

        return Flight::render("requete_details", [
            'navbar' => $WelcomeController->get_navbar(),
            'requete' => $requete,
            'agentsAssignes' => $agentsAssignes,
            'affectationActive' => $affectationActive,
            'messagesChat' => $messagesChat,
            'evaluations' => $evaluations,
            'agentsDisponibles' => $agentsDisponibles,
            'categories' => $categories,
            'base_url' => Flight::get('flight.base_url')
        ]);

    } catch (PDOException $e) {
        Flight::error($e);
    }
}

public static function affecterRequete() {
    $db = Flight::db();
    $data = Flight::request()->data;
    
    try {
        $db->beginTransaction();
        
        // 1. Créer ou mettre à jour le ticket
        if (!empty($data->id_ticket)) {
            // Mise à jour du ticket existant
            $sql = "UPDATE Ticket SET 
                    id_categorie = :id_categorie,
                    priorite = :priorite,
                    prixPrestation = :prixPrestation,
                    duree = :duree
                    WHERE id_ticket = :id_ticket";
            
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id_ticket', $data->id_ticket, PDO::PARAM_INT);
        } else {
            // Création d'un nouveau ticket
            $sql = "INSERT INTO Ticket (id_categorie, priorite, prixPrestation, duree)
                    VALUES (:id_categorie, :priorite, :prixPrestation, :duree)";
            
            $stmt = $db->prepare($sql);
        }
        
        $stmt->bindValue(':id_categorie', $data->id_categorie, PDO::PARAM_INT);
        $stmt->bindValue(':priorite', $data->priorite);
        $stmt->bindValue(':prixPrestation', $data->prixPrestation);
        $stmt->bindValue(':duree', $data->duree, PDO::PARAM_INT);
        $stmt->execute();
        
        $ticketId = !empty($data->id_ticket) ? $data->id_ticket : $db->lastInsertId();
        
        // 2. Invalider les anciennes affectations
        $sql = "UPDATE AffectationTicket 
                SET is_valide = 0 
                WHERE id_requete = :id_requete";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_requete', $data->id_requete, PDO::PARAM_INT);
        $stmt->execute();
        
        // 3. Créer la nouvelle affectation
        $sql = "INSERT INTO AffectationTicket 
                (id_ticket, id_requete, id_agent, is_valide, date_affectation)
                VALUES (:id_ticket, :id_requete, :id_agent, 1, NOW())";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_ticket', $ticketId, PDO::PARAM_INT);
        $stmt->bindValue(':id_requete', $data->id_requete, PDO::PARAM_INT);
        $stmt->bindValue(':id_agent', $data->id_agent, PDO::PARAM_INT);
        $stmt->execute();
        
        // 4. Mettre à jour l'état de la requête
        $sql = "UPDATE RequeteClient 
                SET id_etat = (SELECT id_etat FROM Etat_requete WHERE libelle = 'assigné')
                WHERE id_requete = :id_requete";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_requete', $data->id_requete, PDO::PARAM_INT);
        $stmt->execute();
        
        $db->commit();
        
        Flight::json([
            'success' => true,
            'message' => 'Affectation enregistrée avec succès'
        ]);

        
        Flight::redirect("/requeteClient/details/{$data->id_requete}");
        
    } catch (PDOException $e) {
        $db->rollBack();
        Flight::json([
            'success' => false,
            'message' => 'Erreur lors de l\'affectation: ' . $e->getMessage()
        ], 500);
    }
}

public static function cloturerRequete(int $id_requete, string $statut = 'résolu'): void {
    $db = Flight::db();
    
    // Validation des paramètres avec conversion explicite
    $id_requete = (int)$id_requete;
    $statut = (string)mb_strtolower(trim($statut));

    if ($id_requete <= 0) {
        Flight::flash('error', 'ID de requête invalide');
        Flight::redirect("/requeteClient");
        return;
    }

    $statutsValides = ['résolu', 'fermé'];
    if (!in_array($statut, $statutsValides)) {
        Flight::flash('error', 'Statut invalide. Choisissez: ' . implode(' ou ', $statutsValides));
        Flight::redirect("/requeteClient/details/{$id_requete}");
        return;
    }

    try {
        $db->beginTransaction();

        // 1. Vérification requête (version optimisée pour str_replace)
        $sql = str_replace(
            [':id_requete'], 
            [(string)$id_requete], 
            "SELECT rc.id_etat, er.libelle 
             FROM RequeteClient rc
             JOIN Etat_requete er ON rc.id_etat = er.id_etat
             WHERE rc.id_requete = :id_requete
             FOR UPDATE"
        );
        $requete = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

        if (empty($requete)) {
            throw new RuntimeException("Requête introuvable (ID: {$id_requete})");
        }

        // 2. Vérification statut actuel
        if (in_array($requete['libelle'], $statutsValides)) {
            throw new LogicException("Statut actuel: {$requete['libelle']}. Déjà clôturée.");
        }

        // 3. Mise à jour avec str_replace sécurisé
        $updateSql = str_replace(
            [':statut', ':id_requete'],
            [$db->quote($statut), (string)$id_requete],
            "UPDATE RequeteClient 
             SET id_etat = (SELECT id_etat FROM Etat_requete WHERE libelle = :statut LIMIT 1)
             WHERE id_requete = :id_requete"
        );
        $db->exec($updateSql);

        // 4. Invalidation des affectations
        $invalidateSql = str_replace(
            ':id_requete',
            (string)$id_requete,
            "UPDATE AffectationTicket
             SET is_valide = FALSE
             WHERE id_requete = :id_requete
             AND is_valide = TRUE"
        );
        $db->exec($invalidateSql);

        // 5. Message système
        $message = str_replace(
            ['{statut}', '{id}'],
            [$statut, $id_requete],
            "[SYSTÈME] Ticket {statut} (ID: {id})"
        );

        // $insertSql = str_replace(
        //     [':message', ':id_requete'],
        //     [$db->quote($message), (string)$id_requete],
        //     "INSERT INTO ChatTicket 
        //      (id_ticket, id_client, contenu, date_envoi)
        //      SELECT 
        //          t.id_ticket,
        //          rc.id_client,
        //          :message,
        //          NOW()
        //      FROM RequeteClient rc
        //      JOIN Ticket t ON rc.id_requete = t.id_ticket
        //      WHERE rc.id_requete = :id_requete"
        // );
        // $db->exec($insertSql);

        $db->commit();
        Flight::redirect("/requeteClient/details/{$id_requete}");

    } catch (PDOException $e) {
        $db->rollBack();
        Flight::flash('error', "Erreur technique: " . $e->getMessage());
        Flight::log($e);
    } catch (Exception $e) {
        $db->rollBack();
        Flight::flash('error', $e->getMessage());
    }

    Flight::redirect("/requeteClient/details/{$id_requete}");
}

public static function supprimerRequete(int $id_requete): void {
    $db = Flight::db();

    // Validation de l'ID avec conversion explicite en string pour le débogage
    $id_requete_str = (string)$id_requete;
    if ($id_requete <= 0) {
        Flight::flash('error', 'ID de requête invalide');
        Flight::redirect("/requeteClient");
        return;
    }

    try {
        $db->beginTransaction();

        // 1. Vérification existence de la requête (version sécurisée pour str_replace)
        $sql = "SELECT id_requete FROM RequeteClient WHERE id_requete = " . $db->quote($id_requete_str) . " FOR UPDATE";
        $result = $db->query($sql);
        
        if (!$result->fetch()) {
            throw new Exception("Requête introuvable (ID: {$id_requete_str})");
        }

        // 2. Suppression des messages du chat (version convertissant explicitement les types)
        $sql = "DELETE FROM ChatTicket WHERE id_ticket = " . $db->quote($id_requete_str);
        $db->exec($sql);

        // 3. Suppression des évaluations
        $sql = "DELETE FROM EvaluationTicket WHERE id_ticket = " . $db->quote($id_requete_str);
        $db->exec($sql);

        // 4. Suppression des affectations
        $sql = "DELETE FROM AffectationTicket WHERE id_requete = " . $db->quote($id_requete_str);
        $db->exec($sql);

        // 5. Suppression du ticket
        $sql = "DELETE FROM Ticket WHERE id_ticket = " . $db->quote($id_requete_str);
        $db->exec($sql);

        // 6. Suppression finale de la requête
        $sql = "DELETE FROM RequeteClient WHERE id_requete = " . $db->quote($id_requete_str);
        $db->exec($sql);

        $db->commit();

        Flight::redirect("/requeteClient");

    } catch (PDOException $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        Flight::flash('error', "Erreur technique lors de la suppression : " . $e->getMessage());
        Flight::log($e);
        Flight::redirect("/requeteClient/details/{$id_requete_str}");
    } catch (Exception $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        Flight::flash('error', $e->getMessage());
        Flight::redirect("/requeteClient/details/{$id_requete_str}");
    }
}
}