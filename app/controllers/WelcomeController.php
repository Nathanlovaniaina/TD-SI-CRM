<?php

namespace app\controllers;

use Flight;
use PDO;

class WelcomeController {

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

     public function loginView() {
        Flight::render('login');
    }

    public function loginClient($username, $password) {
         if (session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $pdo = Flight::db();
        // Vérifie dans Client
        $stmt = $pdo->prepare("SELECT * FROM Client WHERE Email = :Email AND mot_de_passe =:mot_de_passe");
        $stmt->execute([':Email' => $username, ':mot_de_passe' => $password]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($client) {
            $_SESSION['role'] = 'client';
            $_SESSION['client_id'] = $client['ClientID'];
            print("tay");
            //Flight::redirect('/chat/client');
            return true;
        }
        return false;
    }

    public function loginAgent($username, $password) {
         if (session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $pdo = Flight::db();
        $stmt = $pdo->prepare("SELECT * FROM Employe WHERE Email = :Email AND mot_de_passe =:mot_de_passe");
        $stmt->execute([':Email' => $username, ':mot_de_passe' => $password]);
        $employe = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($employe) {
            $stmt2 = $pdo->prepare("SELECT * FROM Agent WHERE id_employe = ?");
            $stmt2->execute([$employe['EmployeID']]);
            $agent = $stmt2->fetch();

            if ($agent) {
                $_SESSION['role'] = 'agent';
                $_SESSION['agent_id'] = $agent['id_agent'];
                //Flight::redirect('/chat/agent');
                return true;
            }
        }
        return false;
    }

    public function logout() {
         if (session_status() === PHP_SESSION_NONE){
            session_start();
        }
        session_destroy();
        Flight::redirect('/');
    }

	public function get_navbar() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'client';
    $base_url = Flight::get('flight.base_url');

    $navItems = [];

    if ($role === 'admin') {
        $navItems = [
            ["href" => $base_url."dashboard", "label" => "Accueil"],
            ["href" => $base_url."clients", "label" => "Clients"],
            ["href" => $base_url."produits", "label" => "Produits"],
            ["href" => $base_url."commandes", "label" => "Commandes"],
            ["href" => $base_url."stat", "label" => "Statistique"],
            ["href" => $base_url."simulation", "label" => "Simulation"],
            ["href" => $base_url."actioncommercial", "label" => "Actions Commerciales"],
            ["href" => $base_url."requeteClient", "label" => "Requete Client"],
            ["href" => $base_url."/requeteClient_stats", "label" => "Statistique de Services Client"],
        ];
    } elseif ($role === 'agent') {
        $navItems = [
            ["href" => $base_url."requeteClient", "label" => "Requete Clients"],
        ];
    } elseif ($role === 'client') {
        $navItems = [
            ["href" => $base_url."requeteClient", "label" => "Mes requetes"],
        ];
    }

    $html = '<nav class="sidebar-nav">';
    foreach ($navItems as $item) {
        $html .= '<a href="'.$item['href'].'">'.$item['label'].'</a>';
    }
    $html .= '</nav>';

    return $html;
}
		

}	