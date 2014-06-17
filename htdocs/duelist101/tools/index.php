<?php
error_reporting(E_ALL);
require '../wp-load.php';
require 'tools-config.php';
require APP_DIR . 'vendor/autoload.php';
require APP_DIR . 'vendor/redbean/rb.php';
require APP_DIR . 'vendor/stampte/StampTE.php';

R::setup('sqlite:' . DB_FILE );
$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\StampView()
));

$app->get('/reagents/', function () use ($app) {
	$template = file_get_contents( TEMPLATES_DIR . 'stamp-reagents.html' );
	$reagents = R::find( 'reagent' );
	$test = $app->view->render($template, $reagents);
	
	// Wordpress Print Routing
	get_header();
	echo $test;
	get_sidebar();
	get_footer();
});

$app->run();
R::close();

