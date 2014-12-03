<?php
namespace Duelist101\Db\Route;
use Duelist101\Db\View as View;
use R as R;

class Fish
{
    public static function listHtml( $app )
    {
		$fishList = \W101\FishQuery::create()->find();

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
        
}