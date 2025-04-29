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
	
}