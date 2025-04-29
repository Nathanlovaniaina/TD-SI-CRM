<?php

namespace app\controllers;
use app\service\ActionDAO;
use Flight;
use PDO;

class ProduitController {
    public static function index() {
        $db = Flight::db();
        $result = $db->query("SELECT * FROM Produits");
        $produits = $result->fetch_all(MYSQLI_ASSOC);
        Flight::render('produits/liste.php', ['produits' => $produits]);
    }

    public static function createForm() {
        Flight::render('formulaire.php');
    }

    public static function store() {
        $db = Flight::db();
        $Post = Flight::request()->data;

        $stmt = $db->prepare("INSERT INTO Produits (Designation, Prix, UniteID, QuantiteEnStock, SeuilAlerte, CategorieID, FournisseurID, CodeBarre, EstDiscontinu)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sdiiiissi",
            $Post['designation'],
            $Post['prix'],
            $Post['unite_id'],
            $Post['quantite_en_stock'],
            $Post['seuil_alerte'],
            $Post['categorie_id'],
            $Post['fournisseur_id'],
            $Post['code_barre'],
            isset($Post['est_discontinu']) ? 1 : 0
        );

        $stmt->execute();
        Flight::redirect('/formProduit');
    }

    public static function delete($id) {
        $db = Flight::db();
        $stmt = $db->prepare("DELETE FROM Produits WHERE ProduitID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        Flight::redirect('/produits');
    }
}
