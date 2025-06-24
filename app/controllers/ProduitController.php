<?php

namespace app\controllers;
use app\service\ActionDAO;
use Flight;
use PDO;

class ProduitController {
    public static function index() {
        $db = Flight::db();
        $WelcomeController = new WelcomeController();
        $stmt = $db->prepare("SELECT * FROM Produit");
        $stmt->execute();
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Flight::render('liste.php', ['navbar' => $WelcomeController->get_navbar(),'produits' => $produits]);
    }

    public static function createForm() {
        Flight::render('formulaire.php');
    }

    public static function store() {
        // Récupère l’instance PDO
        $pdo  = Flight::db();
        $data = Flight::request()->data;
    
        // Nettoyage et typage des valeurs
        $nom       = trim($data['nom']);
        $categorie = trim($data['categorie']);
        $prix      = (float) $data['prix'];
        $stock     = (int)   $data['stock'];
    
        // Préparation de la requête avec paramètres nommés
        $sql = "
            INSERT INTO Produit (Nom, Categorie, Prix, Stock)
            VALUES (:nom, :categorie, :prix, :stock)
        ";
        $stmt = $pdo->prepare($sql);
    
        // Exécution en passant un tableau associatif
        $stmt->execute([
            ':nom'       => $nom,
            ':categorie' => $categorie,
            ':prix'      => $prix,
            ':stock'     => $stock,
        ]);
    
        // Redirection après insertion
        Flight::redirect('/produits');
    }
    

    public static function delete($id) {
        $db = Flight::db();
        
        try {
            // Check si le produit existe
            $check = $db->prepare("SELECT ProduitID FROM Produit WHERE ProduitID = :id");
            $check->execute([':id' => $id]);
            $exists = $check->fetch(PDO::FETCH_ASSOC);
            
            if(!$exists) {
                Flight::redirect('/produits?error=Produit non trouvé');
                return;
            }
    
            // Check commandes liées
            $cmd_prod = $db->prepare("SELECT CommandeID FROM Commande_Produit WHERE ProduitID = :id");
            $cmd_prod->execute([':id' => $id]);
            $has_dependencies = $cmd_prod->fetchAll();
    
            if(count($has_dependencies) > 0) {
                // Suppression des liaisons
                $del_liaisons = $db->prepare("DELETE FROM Commande_Produit WHERE ProduitID = :id");
                $del_liaisons->execute([':id' => $id]);
            }
    
            // Suppression principale
            $stmt = $db->prepare("DELETE FROM Produit WHERE ProduitID = :id");
            $stmt->execute([':id' => $id]);
    
            Flight::redirect('/produits?success=Supprimé');
    
        } catch (PDOException $e) {
            Flight::redirect("/produits?error=Erreur: ".urlencode($e->getMessage()));
        } finally {
            // Fermeture implicite avec PDO
            $db = null;
        }
    }
}
