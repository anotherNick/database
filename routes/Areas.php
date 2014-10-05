<?php
namespace Duelist101\Db\Route;
use Duelist101\Db\View as View;
use R as R;

class Areas
{
    public static function listHtml ( $app )
    {
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
    }

    public static function listJson( $app )
    {
        $sql = 'SELECT id, name AS text FROM area ORDER BY name';
        $rows = R::getAll( $sql );

        $app->response()->header('Content-Type', 'application/json');
        echo json_encode($rows);
    }

    public static function singleHtml( $name, $app )
    {
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
    }
    
}