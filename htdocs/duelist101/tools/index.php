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

	// Wordpress Print Routing
	get_header();
	echo $content;
	get_sidebar();
	get_footer();
});

$app->run();
R::close();

