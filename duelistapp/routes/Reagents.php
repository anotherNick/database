<?php
namespace Duelist101\Db\Route;
use Duelist101\Db\View as View;
use R as R;

class Reagents
{
    public static function listHtml( $app )
    {
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
    }

    public static function singleHtml( $name, $app )
    {
        $reagent = R::findOne( 'reagent', ' name = ? ', array( urldecode( $name ) ) );
        
        // Redbean returns null for no reagent. Eventually do something smarter here.
        if( $reagent === null ){ $app->notfound(); }
        
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
    }
    
}