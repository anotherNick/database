<?php
namespace Duelist101\Db\Route;
use Duelist101\Db\View as View;

class Reagents
{
    public static function listHtml( $app )
    {
		print_r($q);
	
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
		$sql = 'SELECT id AS id, name AS text FROM reagent ORDER BY name;';
		$rows = R::getAll( $sql );
		
		$app->response()->header('Content-Type', 'application/json');
		echo json_encode($rows);
    }

    public static function singleHtml( $name, $app )
    {
        $reagent = R::findOne( 'reagent', ' name = ? ', array( urldecode( $name ) ) );
        
        // Redbean returns null for no reagent. Eventually do something smarter here.
        if( $reagent === null ){ $app->notfound(); }
        
        $stamp = new View\ReagentSingle();
        $stamp->parse( $reagent );
        $app->view->add( $stamp );

        $stamp = new View\DisqusFooter();
        $stamp->parse(
            'Wizard101 ' . $reagent->name . ' Info', 
            'http://www.duelist101.com' . $_SERVER['REQUEST_URI']
        );
        $app->view->add( $stamp );
        
        $app->view->render();
    }
    
}