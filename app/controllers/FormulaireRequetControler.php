<?php
namespace app\controllers;

use Flight;
use PDO;
use PDOException;

class FormulaireRequetControler {

    public function afficherFormulaire() {
        $db = Flight::db();
        $WelcomeController = new WelcomeController();
        $clients = $db->query("SELECT ClientID, Nom FROM Client")->fetchAll(PDO::FETCH_ASSOC);
        $requete = null;
        $allRequetes = $db->query("SELECT * FROM RequeteClient")->fetchAll(PDO::FETCH_ASSOC);
        $navbar = $WelcomeController->get_navbar();
        Flight::render('requete_form.php', 
            compact('clients', 'requete', 'allRequetes', 'navbar')
        );
    }

    public function insererRequete() {
        $db = Flight::db();
        $data = Flight::request()->data;

        $fichier = $_FILES['fichier_joint'] ?? null;
        $nomFichier = null;

        if ($fichier && $fichier['error'] == UPLOAD_ERR_OK) {
            $nomFichier = basename($fichier['name']);
            $chemin = __DIR__ . '/../../public/uploads/' . $nomFichier;
            move_uploaded_file($fichier['tmp_name'], $chemin);
        }

        try {
            $stmt = $db->prepare("INSERT INTO RequeteClient (id_client, Sujet, Description, FichierJoint, id_etat) VALUES (?, ?, ?, ?, 1)");
            $stmt->execute([$data['id_client'], $data['sujet'], $data['description'], $nomFichier]);
        } catch (PDOException $e) {
            error_log('Erreur insertion requête : ' . $e->getMessage());
        }

        Flight::redirect('/requete-client');
    }

    public function modifierFormulaire($id) {
        $db = Flight::db();
        $WelcomeController = new WelcomeController();
        $clients = $db->query("SELECT ClientID, Nom FROM Client")->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $db->prepare("SELECT * FROM RequeteClient WHERE id_requete = ?");
        $stmt->execute([$id]);
        $requete = $stmt->fetch(PDO::FETCH_ASSOC);
        $allRequetes = $db->query("SELECT * FROM RequeteClient")->fetchAll(PDO::FETCH_ASSOC);
        $navbar = $WelcomeController->get_navbar();
        Flight::render('requete_form.php', compact('clients', 'requete', 'allRequetes','navbar'));
    }

    public function mettreAJour($id) {
        $db = Flight::db();
        $data = Flight::request()->data;

        $fichier = $_FILES['fichier_joint'] ?? null;
        $nomFichier = null;

        if ($fichier && $fichier['error'] == UPLOAD_ERR_OK) {
            $nomFichier = basename($fichier['name']);
            $chemin = __DIR__ . '/../../public/uploads/' . $nomFichier;
            move_uploaded_file($fichier['tmp_name'], $chemin);
        }

        try {
            if ($nomFichier) {
                $stmt = $db->prepare("UPDATE RequeteClient SET id_client = ?, Sujet = ?, Description = ?, FichierJoint = ? WHERE id_requete = ?");
                $stmt->execute([$data['id_client'], $data['sujet'], $data['description'], $nomFichier, $id]);
            } else {
                $stmt = $db->prepare("UPDATE RequeteClient SET id_client = ?, Sujet = ?, Description = ? WHERE id_requete = ?");
                $stmt->execute([$data['id_client'], $data['sujet'], $data['description'], $id]);
            }
        } catch (PDOException $e) {
            error_log('Erreur update requête : ' . $e->getMessage());
        }

        Flight::redirect('/requete-client');
    }
}
