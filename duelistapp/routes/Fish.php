<?php
namespace Duelist101\Db\Route;
use Duelist101\Db\View as View;
use R as R;

class Fish
{
    public static function listHtml( $app )
    {
        $fishList = R::find( 'fish' );

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
    
}