<?php
namespace Duelist101\Db\Route;
use Duelist101\Db\View as View;

class Fish
{
    public static function addOrUpdate ( $app )
    {

        // TODO: need to delete old picture if changing name and replacing picture?
    
        $result = array();

        if ( is_user_logged_in() && current_user_can('edit_posts') ) {
            if ( !empty($app->request->post('id')) ) {
                $fish = \W101\FishQuery::create()
                    ->findPk( $app->request->post('id') );
                if ( $fish === null ) {
                    $result['status'] = 'error';
                } else {
                    $result = $fish->addOrUpdate( $app->request->post() );
                }
            } else {
                $fish = new \W101\Fish();
                $result = $fish->addOrUpdate( $app->request->post() );
            }
        } else {
            $result['status'] = 'not authorized';
        }
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode( $result );
    }

    public static function listHtml( $app )
    {
		// TODO: currently sorting by case, think due to Propel issue -- don't want it to do that
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

    // creating a new fish if name is null
    public static function newOrEdit( $app, $name )
    {
        if ( is_user_logged_in() && current_user_can('edit_posts') ) {
            if ($name === null) {
                $stamp = new View\FishNewOrEdit();
                $stamp->parse( null );
            } else {
                $fish = \W101\FishQuery::create()
                    ->filterByName( urldecode( $name ) )
                    ->findOne();
                // TODO: Propel returns null for no fish. Eventually do something smarter here.
                if( $fish === null ){ $app->notfound(); }
                $stamp = new View\FishNewOrEdit();
                $stamp->parse( $fish );
            }
        } else {
            $stamp = new View\NotAuthorized();
            $stamp->parse();
        }
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