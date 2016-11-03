<?php
require_once(__DIR__ . '/vendor/autoload.php');

//Create our SLIM app environment
$app = new \Slim\Slim(array(

	//Our app's current environment
	'mode' => 'dev',

	//Output errors in Dev
	'debug' => true,
	'view' 	=> new \Slim\Views\Twig()
));

//404 Handler
$app->notFound(function () use ($app) {
	$app->render('404.twig');
});

//TWIG
$view = $app->view();
$view->parserOptions = array(
	'debug' => true,
	//'cache' => dirname(__FILE__) . '/cache',
);
$view->setTemplatesDirectory('www/templates/');

//Setup our Session middleware
$app->add(new \Slim\Middleware\SessionCookie(array(
	'expires' 		=> '8765 hours',
	'path' 			=> '/',
	'domain' 		=> null,
	'secure' 		=> false,
	'httponly' 		=> false,
	'name' 			=> 'carrepair',
	'secret'	 	=> '1!jo)RxjQ1',
	'cipher' 		=> MCRYPT_RIJNDAEL_256,
	'cipher_mode' 	=> MCRYPT_MODE_CBC
)));

//Include our routes
require_once('routes.php');

$app->run();