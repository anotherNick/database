<?php
namespace Duelist101\Db\Route;
use Duelist101\Db\View as View;

class Fish
{
    public static function add ( $app )
    {
        // TODO: check if authenticated

        $fish = new \W101\Fish();
        $result = $fish->addNew( $app->request->post() );
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode( $result );
    }

    public static function listHtml( $app )
    {
		// TODO: sorting by case -- don't want it to do that
        $fishList = \W101\FishQuery::create()
            ->orderByName()
            ->find();
            
        $stamp = new View\FishList();
        $stamp->parse( $fishList );
        $app->view->add( $stamp );
            
        $stamp = new View\DisqusFooter();
        $stamp->parse(
            'Wizard101 Fish List', 
            'http://www.duelist101.com' . $_SERVER['REQUEST_URI']
        );
        $app->view->add( $stamp );
     
        $app->view->render();
    }

    public static function listJson( $app )
    {
		$fish = \W101\FishQuery::create()
			->select( array( 'id' ) )
			->withColumn( 'name', 'text' )
			->find()
			->toArray();
		
		$app->response()->header('Content-Type', 'application/json');
		echo json_encode( $fish );
    }

    public static function newFish( $app, $post = null, $failures = null )
    {
        // TODO: prob want to put auth logic here
    
        $stamp = new View\FishNew();
        $stamp->parse();
        $app->view->add( $stamp );

        $app->view->render();
    }

    public static function singleHtml( $name, $app )
    {
		$fish = \W101\FishQuery::create()
			->filterByName( urldecode( $name ) )
			->findOne();
        
        // Propel returns null for no fish. Eventually do something smarter here.
        if( $fish === null ){ $app->notfound(); }
        
        $stamp = new View\FishSingle();
        $stamp->parse( $fish );
        $app->view->add( $stamp );

        $stamp = new View\DisqusFooter();
        $stamp->parse(
            'Wizard101 ' . $fish->getName() . ' Info', 
            'http://www.duelist101.com' . $_SERVER['REQUEST_URI']
        );
        $app->view->add( $stamp );
        
        $app->view->render();
    }
    
}