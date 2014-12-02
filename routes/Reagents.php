<?php
namespace Duelist101\Db\Route;
use Duelist101\Db\View as View;

class Reagents
{
    public static function listHtml( $app )
    {
	
		$reagents = \W101\ReagentQuery::create()->find();

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
    }
	
    public static function listJson( $app )
    {
		$reagents = \W101\ReagentQuery::create()
			->select( array( 'id' ) )
			->withColumn( 'name', 'text' )
			->find()
			->toArray();
		
		$app->response()->header('Content-Type', 'application/json');
		echo json_encode( $reagents );
    }

    public static function singleHtml( $name, $app )
    {
		$reagent = \W101\ReagentQuery::create()
			->filterByName( urldecode( $name ) )
			->findOne();
        
        // Propel returns null for no Reagent. Eventually do something smarter here.
        if( $reagent === null ){ $app->notfound(); }
        
        $stamp = new View\ReagentSingle();
        $stamp->parse( $reagent );
        $app->view->add( $stamp );

        $stamp = new View\DisqusFooter();
        $stamp->parse(
            'Wizard101 ' . $reagent->getName() . ' Info', 
            'http://www.duelist101.com' . $_SERVER['REQUEST_URI']
        );
        $app->view->add( $stamp );
        
        $app->view->render();
    }
    
}