<?php

use app\controllers\ApiExampleController;
use app\controllers\WelcomeController;
use app\controllers\DashboardController;
use app\controllers\CaisseControleur;
use app\controllers\AchatController;
use app\controllers\ListeStatistiqueController;
use app\controllers\SimulationControllers;
use flight\Engine;
use flight\net\Router;
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

$router->get('/dashboard',function(){
	$DashboardController = new DashboardController();
	$DashboardController->get_view();
});