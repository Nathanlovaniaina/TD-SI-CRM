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
use app\controllers\ActionCommercialeController;
use app\controllers\RequetClientControler;
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
	Flight::render('login');
});


$router->post('/login',function(){
	$WelcomeController = new WelcomeController();
	$Post = Flight::request()->data;
	$identifiant = $Post['username'];
	$mdp = $Post['password'];
	if($WelcomeController->verifieLogin($identifiant, $mdp)){
		Flight::redirect('/dashboard');
		return;
	}else{
		Flight::redirect('/');
	}
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

$router->get('/produits',function(){
	$ProduitController = new ProduitController();
	$ProduitController->index();
});

$router->get('/produits/supprimer/@id',function($id){
	$ProduitController = new ProduitController();
	$ProduitController->delete($id);
});
// ----------- CLIENTS ----------- //

// Liste des clients
Flight::route('GET /clients', function() {
    $controller = new ClientController();
    $controller->clients();
});

//Action commercial
Flight::route('GET /actioncommercial', function() {
    $controller = new ActionCommercialeController();
    $controller->actionsCommerciales();
});

$router->get('/actioncommercialForm',function(){
	Flight::render('actioncommercialForm');
});

$router->post('/action-commerciale/add',function(){
	$ActionCommercialeController = new ActionCommercialeController();
	$ActionCommercialeController->add_action_commerciale();
});

$router->post('/actioncommercial/delete',function(){
	$ActionCommercialeController = new ActionCommercialeController();
	$ActionCommercialeController->delete_action_commerciale();
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

Flight::route('POST /commande/delete', function() {
    $controller = new CommandeController();
    $controller->delete_commande();
});

Flight::route('GET /requeteClient', function() {
    $controller = new RequetClientControler();
    $controller->getAllRequeteClients();
});

Flight::route('GET /requeteClient/details/@id', function($id) {
    $controller = new RequetClientControler();
    $controller->getRequeteDetails($id);
});

Flight::route('POST /requeteClient/assigner/@id', function($id) {
    $controller = new RequetClientControler();
    $controller->assignerAgent($id);
});