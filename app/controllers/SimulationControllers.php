<?php

namespace app\controllers;
use app\service\ActionDAO;
use Flight;
use PDO;

class SimulationControllers {

	public function __construct() {

	}

    /**
 * Récupère les produits les plus vendus sur une période donnée
 * 
 * @param string $dateDebut Date de début au format YYYY-MM-DD
 * @param string $dateFin Date de fin au format YYYY-MM-DD
 * @param int $limit Nombre de résultats à retourner
 * @return array Tableau des produits avec leurs statistiques
 */
    function getProduitsPlusVendus($dateDebut,$dateFin){
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
            error_log('Erreur dans getProduitsPlusVendus : ' . $e->getMessage());
            return [];
        }
    }

    public function promotion_tous_produit($date_debut,$date_fin,$dataCommande){
        $commandes = array();
        $montant_par_moi = array(); 

        while ($date_debut != $date_fin) {          
            $date_debut->modify('+1 month');
            $montant = 0;
            $nombre = 0;

            for ($i=0; $i < count($dataCommande) ; $i++) { 
                $prix = $dataCommande[$i]['prix'] - ($dataCommande[$i]['prix'] * 0.1);
                $dataCommande[$i]['total_vendu'] = $dataCommande[$i]['total_vendu'] * 1.2;
                $montant += $dataCommande[$i]['total_vendu'] * $prix;
                $nombre += $dataCommande[$i]['total_vendu'];
            }
            $commandes[$date_debut->format('Y-m-d')] = [
                'value'=>$nombre,
                'date' =>$date_debut->format('Y-m-d')
            ];
            $montant_par_moi[$date_debut->format('Y-m-d')] =[
                'value'=>$montant,
                'date' =>$date_debut->format('Y-m-d')
            ] ;
        }
        return [
            'commandes' => $commandes,
            'montant_par_moi' => $montant_par_moi
        ];
        
    }

    public function simulationAction($idAction,$date_debut,$date_fin){
        $date_debut = new \DateTime($date_debut);
        $date_fin = new \DateTime($date_fin);
        $datePrecedent = clone $date_debut;
        $datePrecedent->modify('-1 month');
        $dataCommande = $this->getProduitsPlusVendus($datePrecedent->format('Y-m-d'),$date_debut->format('Y-m-d')); 

        return $this->promotion_tous_produit($date_debut, $date_fin, $dataCommande);

    }

    public function get_view(){
        $ActionDAO = new ActionDAO();
        return Flight::render("simulation",[
            "actions"=> $ActionDAO->getAll()
        ]);
    }

    public function get_view_post(){
        $ActionDAO = new ActionDAO();
        $Post = Flight::request()->data;
        return Flight::render("simulation",[
            "actions"=> $ActionDAO->getAll(),
            "stat" => $this->simulationAction($Post['idAction'],$Post['date_debut'],$Post['date_fin'])
        ]);
    }
}