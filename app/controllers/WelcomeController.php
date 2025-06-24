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
            ["href" => $base_url."commandes", "label" => "Commandes"],
            ["href" => $base_url."stat", "label" => "Statistique"],
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