<?php
require '../wp-load.php';
require 'tools-config.php';
require APP_DIR . 'vendor/autoload.php';
require APP_DIR . 'vendor/redbean/rb.php';

R::setup('sqlite:' . DB_FILE );
$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\Twig(),
    'templates.path' => TEMPLATES_DIR,
));
$app->view()->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
);

$app->get('/reagents/', function () use ($app) {
	$reagents = R::find( 'reagent' );
	$test = $app->view->fetch('reagent-list.html', array('reagents' => $reagents));
	
	// Wordpress Print Routing
	get_header();
	echo $test;
	get_sidebar();
	get_footer();
});

$app->run();
R::close();

