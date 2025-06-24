<?php

namespace app\controllers;

use Flight;
use PDO;

class StatistiqueTicketController {

	public function __construct() {

	}
	
	public static function showDashboard() {
        $db = Flight::db();
        
        try {
            // 1. Statistiques générales par catégorie
            $statsByCategory = $db->query(
                "SELECT ct.Nom AS Categorie, COUNT(t.id_ticket) AS NombreTickets,
                AVG(t.duree) AS DureeMoyenneResolution, AVG(et.note) AS NoteMoyenne
                FROM Ticket t
                JOIN CategorieTicket ct ON t.id_categorie = ct.id_categorie
                LEFT JOIN EvaluationTicket et ON t.id_ticket = et.id_ticket
                GROUP BY ct.Nom"
            )->fetchAll(PDO::FETCH_ASSOC);

            // 2. Répartition par priorité
            $repartitionByPriority = $db->query(
                "SELECT ct.Nom AS Categorie, t.priorite, COUNT(*) AS NombreTickets
                FROM Ticket t
                JOIN CategorieTicket ct ON t.id_categorie = ct.id_categorie
                GROUP BY ct.Nom, t.priorite"
            )->fetchAll(PDO::FETCH_ASSOC);

            // 3. Temps de résolution
            $resolutionTime = $db->query(
                "SELECT ct.Nom AS Categorie, 
                AVG(TIMESTAMPDIFF(HOUR, at.date_affectation, e.date_evaluation)) AS TempsMoyenHeures
                FROM Ticket t
                JOIN CategorieTicket ct ON t.id_categorie = ct.id_categorie
                JOIN AffectationTicket at ON t.id_ticket = at.id_ticket
                JOIN EvaluationTicket e ON t.id_ticket = e.id_ticket
                WHERE at.is_valide = TRUE
                GROUP BY ct.Nom"
            )->fetchAll(PDO::FETCH_ASSOC);

            // 4. Taux de résolution
            $resolutionRate = $db->query(
                "SELECT ct.Nom AS Categorie, 
                ROUND(SUM(CASE WHEN r.id_etat = 4 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS TauxResolution
                FROM Ticket t
                JOIN CategorieTicket ct ON t.id_categorie = ct.id_categorie
                JOIN AffectationTicket at ON t.id_ticket = at.id_ticket
                JOIN RequeteClient r ON at.id_requete = r.id_requete
                GROUP BY ct.Nom"
            )->fetchAll(PDO::FETCH_ASSOC);

            // 5. Satisfaction client
            $satisfaction = $db->query(
                "SELECT ct.Nom AS Categorie, AVG(et.note) AS NoteMoyenne
                FROM EvaluationTicket et
                JOIN Ticket t ON et.id_ticket = t.id_ticket
                JOIN CategorieTicket ct ON t.id_categorie = ct.id_categorie
                GROUP BY ct.Nom"
            )->fetchAll(PDO::FETCH_ASSOC);

            // 6. Évolution mensuelle
            $monthlyTrends = $db->query(
                "SELECT DATE_FORMAT(r.Date_creation, '%Y-%m') AS Mois, 
                ct.Nom AS Categorie, COUNT(*) AS NombreTickets
                FROM RequeteClient r
                JOIN AffectationTicket at ON r.id_requete = at.id_requete
                JOIN Ticket t ON at.id_ticket = t.id_ticket
                JOIN CategorieTicket ct ON t.id_categorie = ct.id_categorie
                GROUP BY Mois, ct.Nom
                ORDER BY Mois"
            )->fetchAll(PDO::FETCH_ASSOC);

            // Préparation des données pour les graphiques
            $chartData = [
                'categories' => array_column($statsByCategory, 'Categorie'),
                'ticketsCount' => array_column($statsByCategory, 'NombreTickets'),
                'resolutionTimes' => array_column($resolutionTime, 'TempsMoyenHeures'),
                'resolutionRates' => array_column($resolutionRate, 'TauxResolution'),
                'satisfactionScores' => array_column($satisfaction, 'NoteMoyenne')
            ];

            $WelcomeController = new WelcomeController();

            return Flight::render('requeteClientStat', [
                'navbar' => $WelcomeController->get_navbar(),
                'statsByCategory' => $statsByCategory,
                'repartitionByPriority' => $repartitionByPriority,
                'resolutionTime' => $resolutionTime,
                'resolutionRate' => $resolutionRate,
                'satisfaction' => $satisfaction,
                'monthlyTrends' => $monthlyTrends,
                'chartData' => $chartData,
                'base_url' => Flight::get('flight.base_url')
            ]);

        } catch (PDOException $e) {
            Flight::error($e);
        }
    }

     public static function showStatsDashboard() {
        $db = Flight::db();
        
        // Récupération des paramètres de date
        $dateDebut = Flight::request()->query['date_debut'] ?? date('Y-m-d', strtotime('-30 days'));
        $dateFin = Flight::request()->query['date_fin'] ?? date('Y-m-d');
        
        try {
            // 1. Statistiques générales par catégorie
            $statsByCategory = $db->prepare(
                "SELECT ct.Nom AS Categorie, COUNT(t.id_ticket) AS NombreTickets,
                AVG(t.duree) AS DureeMoyenneResolution, 
                ROUND(AVG(et.note), 1) AS NoteMoyenne
                FROM Ticket t
                JOIN CategorieTicket ct ON t.id_categorie = ct.id_categorie
                JOIN AffectationTicket at ON t.id_ticket = at.id_ticket
                JOIN RequeteClient r ON at.id_requete = r.id_requete
                LEFT JOIN EvaluationTicket et ON t.id_ticket = et.id_ticket
                WHERE r.Date_creation BETWEEN :date_debut AND :date_fin
                GROUP BY ct.Nom"
            );
            $statsByCategory->execute(['date_debut' => $dateDebut, 'date_fin' => $dateFin]);
            $statsByCategory = $statsByCategory->fetchAll(PDO::FETCH_ASSOC);

            // 2. Répartition par priorité
            $repartitionByPriority = $db->prepare(
                "SELECT ct.Nom AS Categorie, 
                LOWER(t.priorite) AS priorite, 
                COUNT(*) AS NombreTickets
                FROM Ticket t
                JOIN CategorieTicket ct ON t.id_categorie = ct.id_categorie
                JOIN AffectationTicket at ON t.id_ticket = at.id_ticket
                JOIN RequeteClient r ON at.id_requete = r.id_requete
                WHERE r.Date_creation BETWEEN :date_debut AND :date_fin
                GROUP BY ct.Nom, t.priorite"
            );
            $repartitionByPriority->execute(['date_debut' => $dateDebut, 'date_fin' => $dateFin]);
            $repartitionByPriority = $repartitionByPriority->fetchAll(PDO::FETCH_ASSOC);

            // 3. Taux de résolution
            $resolutionRate = $db->prepare(
                "SELECT ct.Nom AS Categorie, 
                ROUND(SUM(CASE WHEN r.id_etat = 4 THEN 1 ELSE 0 END) * 100.0 / 
                NULLIF(COUNT(*), 0), 1) AS TauxResolution
                FROM Ticket t
                JOIN CategorieTicket ct ON t.id_categorie = ct.id_categorie
                JOIN AffectationTicket at ON t.id_ticket = at.id_ticket
                JOIN RequeteClient r ON at.id_requete = r.id_requete
                WHERE r.Date_creation BETWEEN :date_debut AND :date_fin
                GROUP BY ct.Nom"
            );
            $resolutionRate->execute(['date_debut' => $dateDebut, 'date_fin' => $dateFin]);
            $resolutionRate = $resolutionRate->fetchAll(PDO::FETCH_ASSOC);

            // 4. Satisfaction client
            $satisfaction = $db->prepare(
                "SELECT ct.Nom AS Categorie, 
                ROUND(AVG(et.note), 1) AS NoteMoyenne
                FROM EvaluationTicket et
                JOIN Ticket t ON et.id_ticket = t.id_ticket
                JOIN CategorieTicket ct ON t.id_categorie = ct.id_categorie
                JOIN AffectationTicket at ON t.id_ticket = at.id_ticket
                JOIN RequeteClient r ON at.id_requete = r.id_requete
                WHERE r.Date_creation BETWEEN :date_debut AND :date_fin
                AND et.note IS NOT NULL
                GROUP BY ct.Nom"
            );
            $satisfaction->execute(['date_debut' => $dateDebut, 'date_fin' => $dateFin]);
            $satisfaction = $satisfaction->fetchAll(PDO::FETCH_ASSOC);

            // 5. Taux de résolution global et détaillé
            $resolutionDetails = $db->prepare(
                "SELECT 
                    ct.Nom AS Categorie,
                    t.priorite AS Priorite,
                    ROUND(SUM(CASE WHEN r.id_etat = 4 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS TauxResolution,
                    COUNT(*) AS TotalRequetes
                 FROM RequeteClient r
                 JOIN AffectationTicket at ON r.id_requete = at.id_requete
                 JOIN Ticket t ON at.id_ticket = t.id_ticket
                 JOIN CategorieTicket ct ON t.id_categorie = ct.id_categorie
                 WHERE r.Date_creation BETWEEN :date_debut AND :date_fin
                 GROUP BY ct.Nom, t.priorite
                 ORDER BY ct.Nom, t.priorite"
            );
            $resolutionDetails->execute(['date_debut' => $dateDebut, 'date_fin' => $dateFin]);
            $resolutionDetails = $resolutionDetails->fetchAll(PDO::FETCH_ASSOC);

            $globalResolution = $db->prepare(
                "SELECT ROUND(COUNT(*) * 100.0 / NULLIF((SELECT COUNT(*) FROM RequeteClient 
                  WHERE Date_creation BETWEEN :date_debut AND :date_fin), 0), 2) AS taux
                 FROM RequeteClient 
                 WHERE id_etat = 4
                 AND Date_creation BETWEEN :date_debut AND :date_fin"
            );
            $globalResolution->execute(['date_debut' => $dateDebut, 'date_fin' => $dateFin]);
            $globalResolution = $globalResolution->fetchColumn();

            // Préparation des données pour les graphiques
            $chartData = [
                'categories' => array_column($statsByCategory, 'Categorie'),
                'ticketsCount' => array_column($statsByCategory, 'NombreTickets'),
                'resolutionRates' => array_column($resolutionRate, 'TauxResolution'),
                'satisfactionScores' => array_column($satisfaction, 'NoteMoyenne'),
                'priorities' => ['haute', 'moyenne', 'basse']
            ];

            $WelcomeController = new WelcomeController();

            return Flight::render('requeteClientStat', [
                'navbar' => $WelcomeController->get_navbar(),
                'statsByCategory' => $statsByCategory,
                'repartitionByPriority' => $repartitionByPriority,
                'resolutionRate' => $resolutionRate,
                'satisfaction' => $satisfaction,
                'resolutionDetails' => $resolutionDetails,
                'globalResolution' => $globalResolution,
                'chartData' => $chartData,
                'dateDebut' => $dateDebut,
                'dateFin' => $dateFin,
                'base_url' => Flight::get('flight.base_url')
            ]);

        } catch (PDOException $e) {
            Flight::error($e);
        }
    }

    /**
     * Endpoint API pour les données du dashboard (AJAX)
     */
    public static function getDashboardData() {
        $db = Flight::db();
        
        try {
            $data = [
                'statsByCategory' => $db->query("SELECT ...")->fetchAll(),
                'repartitionByPriority' => $db->query("SELECT ...")->fetchAll(),
                // ... autres requêtes
            ];

            Flight::json([
                'success' => true,
                'data' => $data
            ]);

        } catch (PDOException $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données'
            ], 500);
        }
    }
	
}