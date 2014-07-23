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
define('Duelist101\BASE_URL', $app->request()->getUrl() . $app->request()->getRootUri() . '/');

$app->post('/areareagent', function () use ($app) {
    $post = $app->request()->post();
    $output = array();

    $areareagents = R::find( 
        'areareagent', 
        'area_id = ? and reagent_id = ?', 
        [ $post['area_id'], $post['reagent_id'] ]
    );
    if ( empty($areareagents) ) {
        $reagent = R::load( 'reagent', $post['reagent_id'] );
        $area = R::load( 'area', $post['area_id'] );
        if ( !is_null( $reagent ) && !is_null( $area ) ) {
            $areareagent = R::dispense( 'areareagent' );
            $areareagent->area = $area;
            $areareagent->reagent = $reagent;
            $areareagent->votesUp = 1;
            $output['id'] = R::store( $areareagent );
            
            $output['url'] = \Duelist101\BASE_URL . 'areareagents/' . urlencode($areareagent->id);
            $output['areaName'] = $area->name;
            $output['reagentName'] = $reagent->name;
            $output['voteUpUrl'] = \Duelist101\BASE_URL . 'areareagents/' . urlencode($areareagent->id) . '/vote-up';
            $output['voteDown'] = \Duelist101\BASE_URL . 'areareagents/' . urlencode($areareagent->id) . '/vote-down';
            
            $app->response()->header('Content-Type', 'application/json');
            echo json_encode( $output );
        } else {
            echo "can't find area or reagent";
        }
    } else {
        echo "already in database";
    }
        
});

$app->get('/areas.json', function () use ($app) {
    $app->response()->header('Content-Type', 'application/json');

    $sql = 'SELECT name AS label, id AS value FROM area ORDER BY name;';
    $rows = R::getAll( $sql );
    echo json_encode($rows);
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
	
    $areas = R::findAll( 'area', 'ORDER BY name');
    
    $stamp = new View\ReagentSingle();
    $stamp->parse( $reagent, $areas );
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
	$worlds = R::find( 'world' );

    $stamp = new View\AreaList();
    $stamp->parse( $worlds );
    $app->view->add( $stamp );
        
    $stamp = new View\DisqusFooter();
    $stamp->parse(
        'Wizard101 Areas List', 
        'http://www.duelist101.com' . $_SERVER['REQUEST_URI']
    );
    $app->view->add( $stamp );
 
	$app->view->render();

});

$app->run();
R::close();
