<?php

namespace app\controllers;
use app\service\ActionDAO;
use Flight;
use PDO;

class StatController {

	public function __construct() {

	}


    function getClientsInscritsAvantDate($dateLimite )
    {
        $db = Flight::db();

        try {
            // Validation du format de date
            $date = \DateTime::createFromFormat('Y-m-d', $dateLimite);
            if (!$date) {
                throw new InvalidArgumentException("Format de date invalide");
            }

            $requete = "
                SELECT COUNT(*) AS total 
                FROM Client 
                WHERE DateInscription < :date_limite
                AND Statut != 'inactif'"; // Exclure les comptes inactifs si besoin

            $stmt = $db->prepare($requete);
            $stmt->bindValue(':date_limite', $dateLimite, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return (int)($result['total'] ?? 0);

        } catch (PDOException $e) {
            error_log('Erreur getClientsInscritsAvantDate : ' . $e->getMessage());
            return 0;
        } catch (InvalidArgumentException $e) {
            error_log('Erreur de date : ' . $e->getMessage());
            return 0;
        }
    }

    function getClientsInscritsEntreDates($dateDebut, $dateFin)
    {
        $db = Flight::db();

        try {
            // Validation des formats de date
            $dateStart = \DateTime::createFromFormat('Y-m-d', $dateDebut);
            $dateEnd = \DateTime::createFromFormat('Y-m-d', $dateFin);

            if (!$dateStart || !$dateEnd) {
                throw new InvalidArgumentException("Format de date invalide");
            }

            $requete = "
                SELECT COUNT(*) AS total 
                FROM Client 
                WHERE DateInscription BETWEEN :date_debut AND :date_fin
                AND Statut != 'inactif'";

            $stmt = $db->prepare($requete);
            $stmt->bindValue(':date_debut', $dateDebut, PDO::PARAM_STR);
            $stmt->bindValue(':date_fin', $dateFin, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return (int)($result['total'] ?? 0);

        } catch (PDOException $e) {
            error_log('Erreur getClientsInscritsEntreDates : ' . $e->getMessage());
            return 0;
        } catch (InvalidArgumentException $e) {
            error_log('Erreur de date : ' . $e->getMessage());
            return 0;
        }
    }

    function getStatsParCategorie($dateDebut, $dateFin) {
        $db = Flight::db();
    
        try {
            // Validation des dates
            $dateStart = \DateTime::createFromFormat('Y-m-d', $dateDebut);
            $dateEnd = \DateTime::createFromFormat('Y-m-d', $dateFin);
            
            if (!$dateStart || !$dateEnd) {
                throw new InvalidArgumentException("Format de date invalide");
            }
    
            $requete = "
                SELECT 
                    p.Categorie,
                    COUNT(DISTINCT c.CommandeID) AS nombre_commandes,
                    SUM(cp.Quantite) AS total_quantite,
                    ROUND(SUM(cp.Quantite * cp.PrixUnitaire), 2) AS montant_total
                FROM Commande_Produit cp
                INNER JOIN Produit p ON cp.ProduitID = p.ProduitID
                INNER JOIN Commande c ON cp.CommandeID = c.CommandeID
                WHERE c.DateCommande BETWEEN :date_debut AND :date_fin
                    AND c.Statut = 'livree'
                GROUP BY p.Categorie
                ORDER BY total_quantite DESC";
    
            $stmt = $db->prepare($requete);
            $stmt->bindValue(':date_debut', $dateDebut, PDO::PARAM_STR);
            $stmt->bindValue(':date_fin', $dateFin, PDO::PARAM_STR);
            $stmt->execute();
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        } catch (PDOException $e) {
            error_log('Erreur getStatsParCategorie : ' . $e->getMessage());
            return [];
        } catch (InvalidArgumentException $e) {
            error_log('Erreur de date : ' . $e->getMessage());
            return [];
        }
    }


    function getProduitVendus($dateDebut,$dateFin){
        $db = Flight::db();
        
        try {
            $requete = "
                SELECT
                    p.ProduitID, 
                    p.nom AS produit,
                    p.Prix AS prix,
                    COUNT(DISTINCT c.CommandeID) AS nombre_commandes,
                    SUM(lc.quantite) AS total_vendu,
                    ROUND(SUM(lc.quantite * lc.PrixUnitaire), 2) AS chiffre_affaire
                FROM Commande_Produit lc
                INNER JOIN Produit p ON lc.ProduitID = p.ProduitID
                INNER JOIN Commande c ON lc.CommandeID = c.CommandeID
                WHERE c.DateCommande BETWEEN :date_debut AND :date_fin
                    AND c.Statut = 'Livrée' 
                GROUP BY p.nom
                ORDER BY total_vendu DESC";
            
            $stmt = $db->prepare($requete);
            
            // Binding sécurisé des paramètres
            $stmt->bindValue(':date_debut', $dateDebut, PDO::PARAM_STR);
            $stmt->bindValue(':date_fin', $dateFin, PDO::PARAM_STR);
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log('Erreur dans getProduitVendus : ' . $e->getMessage());
            return [];
        }
    }

    public function promotion_tous_produit($date_debut,$date_fin,$dataCommande,$nombre_de_client){
        $commandes = array();
        $montant_par_moi = array(); 
        $date_debut_copyr = clone $date_debut;
        $date_debut_copyr->modify('+1 month');
        $init_client = $nombre_de_client;
        $clients = array();

        while ($date_debut != $date_fin) {
            $date_precedent = clone $date_debut;          
            $date_debut->modify('+1 month');
            $montant = 0;
            $nombre = 0;
            $dataCommande = $this->getProduitVendus($date_precedent->format('Y-m-d'),$date_debut->format('Y-m-d'));
            for ($i=0; $i < count($dataCommande) ; $i++) { 
                $prix = $dataCommande[$i]['prix'];
                $dataCommande[$i]['total_vendu'] += $dataCommande[$i]['total_vendu'];
                $montant += $dataCommande[$i]['total_vendu'] * $prix;
                $nombre += $dataCommande[$i]['total_vendu'];
            }
            $commandes[$date_debut->format('Y-m-d')] = [
                'value'=>$nombre,
                'date' =>$date_precedent->format('Y-m-d')
            ];
            $montant_par_moi[$date_debut->format('Y-m-d')] =[
                'value'=>$montant,
                'date' =>$date_precedent->format('Y-m-d')
            ] ;

            $nombre_de_client += $this->getClientsInscritsEntreDates($date_precedent->format('Y-m-d'), $date_debut->format('Y-m-d')); 

            $clients[$date_debut->format('Y-m-d')] = [
                'value'=>$this->getClientsInscritsEntreDates($date_precedent->format('Y-m-d'), $date_debut->format('Y-m-d')),
                'date' =>$date_precedent->format('Y-m-d')
            ];
        }
        return [
            'commandes' => $commandes,
            'montant_par_moi' => $montant_par_moi,
            'clients' => $clients,
            'client_obtenue' => $init_client != 0 
            ? number_format($nombre_de_client - $init_client,2) . " (" . number_format((($nombre_de_client - $init_client) / $init_client) * 100, 2) . " %)" 
            : "0 (0 %)",
            'commande_obtenue' => $commandes[$date_debut_copyr->format('Y-m-d')]['value'] != 0 
            ? number_format($commandes[$date_debut->format('Y-m-d')]['value'] - $commandes[$date_debut_copyr->format('Y-m-d')]['value'],2) . " (" . number_format((($commandes[$date_debut->format('Y-m-d')]['value'] - $commandes[$date_debut_copyr->format('Y-m-d')]['value']) / $commandes[$date_debut_copyr->format('Y-m-d')]['value']) * 100, 2) . " %)" 
            : "0 (0 %)"
        ];
        
    }

    function calculerNombreMoisEntreDates(string $dateDebut, string $dateFin): int
    {
        $debut = new \DateTime($dateDebut);
        $fin = new \DateTime($dateFin);

        $interval = $debut->diff($fin);

        // Calcul du nombre total de mois
        $totalMois = ($interval->y * 12) + $interval->m;

        // Gestion des jours restants pour les mois partiels
        if ($interval->invert == 0 && $interval->d > 0) {
            $totalMois += 1; // Ajoute 1 mois si des jours restants en avant
        } elseif ($interval->invert == 1 && $interval->d > 0) {
            $totalMois -= 1; // Retire 1 mois si des jours restants en arrière
        }

        return abs($totalMois); // Retourne la valeur absolue
    }

    public function simulationAction($idAction,$date_debut,$date_fin){
        $date_debut = new \DateTime($date_debut);
        $date_fin = new \DateTime($date_fin);
        $datePrecedent = clone $date_debut;
        $datePrecedent->modify('-1 month');
        $dataCommande = $this->getProduitVendus($datePrecedent->format('Y-m-d'),$date_debut->format('Y-m-d')); 
        $nombre_de_client = $this->getClientsInscritsAvantDate($datePrecedent->format('Y-m-d'));

        return $this->promotion_tous_produit($date_debut, $date_fin, $dataCommande,$nombre_de_client);

    }

    public function get_view(){
        $ActionDAO = new ActionDAO();
        return Flight::render("stat");
    }

    public function get_view_post(){
        $ActionDAO = new ActionDAO();
        $Post = Flight::request()->data;
        $tendance_categorie = $this->getStatsParCategorie($Post['date_debut'],$Post['date_fin']);
        return Flight::render("stat",[
            "stat" => $this->simulationAction($Post['idAction'],$Post['date_debut'],$Post['date_fin']),
            "action"=> $ActionDAO->getById($Post['idAction']),
            "tendance"=>$tendance_categorie
        ]);
    }
}