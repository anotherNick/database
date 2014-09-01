<?php
namespace Duelist101\Db\Route;
use Duelist101\Db\View as View;
use R as R;

class Areafish
{
    public static function add( $app )
    {
        $post = $app->request()->post();
        $output = array();

        $areafishList = R::find( 
            'areafish', 
            'area_id = ? and fish_id = ?', 
            array( $post['area_id'], $post['fish_id'] )
        );
        if ( empty($areafishList) ) {
            $fish = R::load( 'fish', $post['fish_id'] );
            $area = R::load( 'area', $post['area_id'] );
            if ( $fish->id != 0 && $area->id != 0 ) {
                $areafish = R::dispense( 'areafish' );
                $areafish->area = $area;
                $areafish->fish = $fish;
                $areafish->votesUp = 1;
                $areafish->votesDown = 0;
                $output['id'] = R::store( $areafish );
                
                $output['url'] = \Duelist101\BASE_URL . 'areafish/' . urlencode($areafish->id);
                $output['areaName'] = $area->name;
                $output['areaUrl'] = \Duelist101\BASE_URL . 'areas/' . urlencode($area->name);
                $output['fishName'] = $fish->name;
                $output['fishUrl'] = \Duelist101\BASE_URL . 'fish/' . urlencode($fish->name);
                $output['voteUpUrl'] = \Duelist101\BASE_URL . 'areafish/' . urlencode($areafish->id) . '/vote-up';
                $output['voteDownUrl'] = \Duelist101\BASE_URL . 'areafish/' . urlencode($areafish->id) . '/vote-down';
                
                $app->response()->header('Content-Type', 'application/json');
                echo json_encode( $output );
            } else {
                echo "can't find area or fish";
            }
        } else {
            echo "already in database";
        }
    }

    public static function vote( $type, $id, $app )
    {
        $post = $app->request()->post();
        $output = array();

        $areafish = R::load( 'areafish', $id);
        if ( $areafish->id != 0 ) {
            $output['id'] = $id;
            switch ( $type ) {
                case 'up':
                    $output['votesUp'] = ++$areafish->votesUp;
                    break;
                case 'down':
                    $output['votesDown'] = ++$areafish->votesDown;
                    break;
            }
            // TODO: add logging logic here
            R::store( $areafish );
            
            $app->response()->header('Content-Type', 'application/json');
            echo json_encode( $output );
            
        } else {
            echo "can't find areafish";
        }
    }
    
}