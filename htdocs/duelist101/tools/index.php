<?php
require '../wp-load.php';
require 'tools-config.php';
require APP_DIR . 'vendor/autoload.php';

require APP_DIR . 'vendor/stamp/StampTE.php';
require APP_DIR . 'lib/StampView.php';

require APP_DIR . 'vendor/redbean/rb.php';
R::setup('sqlite:' . DB_FILE );

$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\Stamp(),
    'templates.path' => TEMPLATES_DIR . 'stamp/'
));

$app->get('/reagents/', function () use ($app) {
	$reagents = R::find( 'reagent' );
	$content = $app->view->fetch('reagent-list',
            array('reagents' => $reagents));

	// Wordpress Print Routine
	get_header();
	echo $content;
	get_sidebar();
	get_footer();
});

$app->get('/reagents/:name', function ($name) use ($app) {
	$reagent = R::findOne( 'reagent', ' name = ? ', array( urldecode( $name ) ) );
	
	// Redbean returns null for no reagent. Eventually do something smarter here.
	if( $reagent === null ){ $app->notfound(); }
	
	$content = $app->view->fetch('reagent-single',
            array('reagent' => $reagent));

	// Wordpress Print Routine
	get_header();
	echo $content;
	get_sidebar();
	get_footer();
});

$app->run();
R::close();

