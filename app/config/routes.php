<?php

use app\controllers\ApiExampleController;
use app\controllers\WelcomeController;
use app\controllers\DashboardController;
use app\controllers\CaisseControleur;
use app\controllers\AchatController;
use app\controllers\ListeStatistiqueController;
use app\controllers\SimulationControllers;
use app\controllers\StatController;
use app\controllers\ProduitController;
use flight\Engine;
use flight\net\Router;
use app\controllers\ClientController;
use app\controllers\CommandeController;

// use Flight;

/** 
 * @var Router $router 
 * @var Engine $app
 */
/*$router->get('/', function() use ($app) {
	$Welcome_Controller = new WelcomeController($app);
	$app->render('welcome', [ 'message' => 'It works!!' ]);
});*/

$Welcome_Controller = new WelcomeController();
//$router->get('/', [ $Welcome_Controller, 'home' ]); 
//$router->get('/', [ 'WelcomeController', 'home' ]); 

//$router->get('/', \app\controllers\WelcomeController::class.'->home'); 

$router->get('/',function(){
	Flight::render('exemple_dolibarr_style');
});

$router->get('/simulation',function(){
	$SimulationControllers =new SimulationControllers();
	$SimulationControllers->get_view();
});

$router->post('/simulation',function(){
	$SimulationControllers =new SimulationControllers();
	$SimulationControllers->get_view_post();
});

$router->get('/stat',function(){
	$StatController =new StatController();
	$StatController->get_view();
});

$router->post('/stat',function(){
	$StatController =new StatController();
	$StatController->get_view_post();
});

$router->get('/dashboard',function(){
	$DashboardController = new DashboardController();
	$DashboardController->get_view();
});

$router->get('/formProduit',function(){
	$ProduitController = new ProduitController();
	$ProduitController->createForm();
});

$router->post('/formProduit',function(){
	$ProduitController = new ProduitController();
	$ProduitController->store();
});


// Route page d'accueil
Flight::route('/', function() {
    echo 'Bienvenue sur l\'application !';
});

// ----------- CLIENTS ----------- //

// Liste des clients
Flight::route('GET /clients', function() {
    $controller = new ClientController();
    $controller->clients();
});

// Détail d'un client (réactions client + commerciale)
Flight::route('GET /client/@id', function($id) {
    $controller = new ClientController();
    $controller->client_detail($id);
});

// ----------- COMMANDES ----------- //

// Liste des commandes
Flight::route('GET /commandes', function() {
    $controller = new CommandeController();
    $controller->commandes();
});

// Ajouter une commande (form POST)
Flight::route('POST /commande/add', function() {
    $controller = new CommandeController();
    $controller->add_commande();
});

// Supprimer une commande (form POST)
Flight::route('POST /commande/delete', function() {
    $controller = new CommandeController();
    $controller->delete_commande();
});
