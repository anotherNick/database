<?php
namespace Duelist101;
use Duelist101\Db\View as View;
use R as R;

require 'config.php';
require AUTOLOAD_FILE;
require WPLOAD_DIR . 'wp-load.php';

R::setup( DB_DSN, DB_USERNAME, DB_PASSWORD, DB_FROZEN );

$app = new \Slim\Slim(array(
    'view' => new WordpressView(),
    'templates.path' => TEMPLATES_DIR
));

$app->get('/reagents/', function () use ($app) {
	$reagents = R::find( 'reagent' );

    $stamp = new View\ReagentList();
    $stamp->parse( $reagents );
    $app->view->add( $stamp );
        
    $stamp = new View\DisqusFooter();
    $stamp->parse(
        'Wizard101 Reagents List', 
        'http://www.duelist101.com' . $_SERVER['REQUEST_URI']
    );
    $app->view->add( $stamp );
 
	$app->view->render();

});

$app->get('/reagents/:name', function ($name) use ($app) {
	$reagent = R::findOne( 'reagent', ' name = ? ', array( urldecode( $name ) ) );
	
	// Redbean returns null for no reagent. Eventually do something smarter here.
	if( $reagent === null ){ $app->notfound(); }
	
    $stamp = new View\ReagentSingle();
    $stamp->parse( $reagent, $app->request->getRootUri() );
    $app->view->add( $stamp );

    $stamp = new View\DisqusFooter();
    $stamp->parse(
        'Wizard101 ' . $reagent->name . ' Info', 
        'http://www.duelist101.com' . $_SERVER['REQUEST_URI']
    );
    $app->view->add( $stamp );
    
	$app->view->render();
	
});

$app->get('/areas/', function () use ($app) {
	$areas = R::find( 'area' );

    $stamp = new View\AreaList();
    $stamp->parse( $areas );
    $app->view->add( $stamp );
        
    $stamp = new View\DisqusFooter();
    $stamp->parse(
        'Wizard101 Areas List', 
        'http://www.duelist101.com' . $_SERVER['REQUEST_URI']
    );
    $app->view->add( $stamp );
 
	$app->view->render();

});

$app->get('/areas/:name', function ($name) use ($app) {
	$area = R::findOne( 'area', 'name = ?', array( urldecode( $name ) ) );
	
	// Redbean returns null for no reagent. Eventually do something smarter here.
	if( $area === null ){ $app->notfound(); }
	
    $stamp = new View\AreaSingle();
    $stamp->parse( $area );
    $app->view->add( $stamp );

    $stamp = new View\DisqusFooter();
    $stamp->parse(
        'Wizard101 ' . $area->name . ' Info', 
        'http://www.duelist101.com' . $_SERVER['REQUEST_URI']
    );
    $app->view->add( $stamp );
    
	$app->view->render();
	
});

$app->run();
R::close();
