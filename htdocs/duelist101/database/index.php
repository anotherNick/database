<?php
require 'config.php';
require \Duelist101\APP_DIR . 'vendor/autoload.php';
require \Duelist101\WPLOAD_DIR . 'wp-load.php';

R::setup( \Duelist101\DB_DSN, \Duelist101\DB_USERNAME, \Duelist101\DB_PASSWORD, \Duelist101\DB_FROZEN );

$app = new \Slim\Slim(array(
    'view' => new \Duelist101\StampView(),
    'templates.path' => \Duelist101\TEMPLATES_DIR . 'stamp/'
));
$view = $app->view;

$app->get('/reagents/', function () use ($app) {
	$reagents = R::find( 'reagent' );
    
	$content = $app->view->fetch('reagent-list',
            array('reagents' => $reagents));
	$content .= $app->view->fetch('disqus-footer',
			array('url' => 'http://www.duelist101.com' . $_SERVER['REQUEST_URI'],
			      'title' => 'Wizard101 Reagents List'));
	$app->view->renderWordpress($content);

});

$app->get('/reagents/:name', function ($name) use ($app) {
	$reagent = R::findOne( 'reagent', ' name = ? ', array( urldecode( $name ) ) );
	
	// Redbean returns null for no reagent. Eventually do something smarter here.
	if( $reagent === null ){ $app->notfound(); }
	
	$content = $app->view->fetch('reagent-single',
            array('reagent' => $reagent));
	$content .= $app->view->fetch('disqus-footer',
			array('url' => 'http://www.duelist101.com' . $_SERVER['REQUEST_URI'],
			      'title' => 'Wizard101 ' . $reagent->name . ' Info'));
	$app->view->renderWordpress($content);
	
});

$app->get('/areas/:name', function ($name) use ($app) {
	$area = R::findOne( 'area', ' name = ? ', array( urldecode( $name ) ) );
	
	// Redbean returns null for no reagent. Eventually do something smarter here.
	if( $reagent === null ){ $app->notfound(); }
	
	$content = $app->view->fetch('area-single',
            array('area' => $area));
	$content .= $app->view->fetch('disqus-footer',
			array('url' => 'http://www.duelist101.com' . $_SERVER['REQUEST_URI'],
			      'title' => 'Wizard101 ' . $area->name . ' Info'));
	$app->view->renderWordpress($content);
	
});

$app->run();
R::close();

