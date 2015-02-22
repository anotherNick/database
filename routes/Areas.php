<?php
namespace Duelist101\Db\Route;
use Duelist101\Db\View as View;

class Areas
{
	
    public static function add ( $app )
    {
        // TODO: check if authenticated

        $area = new \W101\Area();
        $result = $area->addNew( $app->request->post() );
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode( $result );
    }
	
    public static function listHtml ( $app )
    {
        $worlds = \W101\WorldQuery::create()->find();

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
    }

    public static function listJson( $app )
    {
		$areas = \W101\AreaQuery::create()
			->select( array( 'id' ) )
			->withColumn( 'name', 'text' )
			->find()
			->toArray();
		
		$app->response()->header('Content-Type', 'application/json');
		echo json_encode( $areas );
    }
	
    public static function newArea( $app, $post = null, $failures = null )
    {
        // TODO: prob want to put auth logic here
    
        $stamp = new View\AreaNew();
        $stamp->parse();
        $app->view->add( $stamp );

        $app->view->render();
    }

    public static function singleHtml( $name, $app )
    {
        $area = \W101\AreaQuery::create()
			->filterByName( urldecode( $name ) )
			->findOne();
        
        // Propel returns null for no Area. Eventually do something smarter here.
        if( $area === null ){ $app->notfound(); }
        
        $stamp = new View\AreaSingle();
        $stamp->parse( $area );
        $app->view->add( $stamp );

        $stamp = new View\DisqusFooter();
        $stamp->parse(
            'Wizard101 ' . $area->getName() . ' Info', 
            'http://www.duelist101.com' . $_SERVER['REQUEST_URI']
        );
        $app->view->add( $stamp );
        
        $app->view->render();
    }
    
}