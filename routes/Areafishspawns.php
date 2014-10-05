<?php
namespace Duelist101\Db\Route;
use Duelist101\Db\View as View;
use R as R;

class Areafishspawns
{
    public static function add( $app )
    {
        $post = $app->request()->post();
        $output = array();

        $areafishspawns = R::find( 
            'areafishspawn', 
            'area_id = ? and fish_id = ? and x_loc = ? and y_loc = ?', 
            array( $post['area-spawn-area-id'], 
			  $post['area-spawn-type-id'],
			  $post['area-spawn-x'],
			  $post['area-spawn-y'] )
        );
        if ( empty($areafishspawns) ) {
            $fish = R::load( 'fish', $post['area-spawn-type-id'] );
            $area = R::load( 'area', $post['area-spawn-area-id'] );
			
			// Create areafish relationship if none exists
			$areafish = R::findOne( 
				'areafish', 
				'area_id = ? and fish_id = ?', 
				array( $post['area-spawn-area-id'], $post['area-spawn-type-id'] )
			);
			if ( empty($areafish) ) {
				if ( $fish->id != 0 && $area->id != 0 ) {
					$areafish = R::dispense( 'areafish' );
					$areafish->area = $area;
					$areafish->fish = $fish;
					$areafish->votesUp = 1;
					$areafish->votesDown = 0;
					$output['areafishId'] = R::store( $areafish );
				} else {
					$app->response()->status(409);
					echo "can't find area or fish";
				}
			} else {
				$output['areafishId'] = $areafish->id;
			}
			
			// Create areafishspawn
            if ( $fish->id != 0 && $area->id != 0 ) {
                $areafishspawn = R::dispense( 'areafishspawn' );
                $areafishspawn->area = $area;
                $areafishspawn->fish = $fish;
				$areafishspawn->areafish = $areafish;
				$areafishspawn->x_loc = $post['area-spawn-x'];
				$areafishspawn->y_loc = $post['area-spawn-y'];
                $areafishspawn->votesUp = 1;
                $areafishspawn->votesDown = 0;
                $output['id'] = R::store( $areafishspawn );
                
                $output['url'] = \Duelist101\BASE_URL . 'areafishspawns/' . urlencode($areafishspawn->id);
                $output['areaName'] = $area->name;
                $output['areaUrl'] = \Duelist101\BASE_URL . 'areas/' . urlencode($area->name);
                $output['fishName'] = $fish->name;
                $output['fishUrl'] = \Duelist101\BASE_URL . 'fishs/' . urlencode($fish->name);
				$output['areaSpawnX'] = $areafishspawn->x_loc;
				$output['areaSpawnY'] = $areafishspawn->y_loc;
                $output['voteUpUrl'] = \Duelist101\BASE_URL . 'areafishspawns/' . urlencode($areafishspawn->id) . '/vote-up';
                $output['voteDownUrl'] = \Duelist101\BASE_URL . 'areafishspawns/' . urlencode($areafishspawn->id) . '/vote-down';
                
                $app->response()->header('Content-Type', 'application/json');
                echo json_encode( $output );
            } else {
				$app->response()->status(409);
                echo "can't find area or fish";
            }
        } else {
			$app->response()->status(409);
            echo "already in database";
        }
    }

    public static function vote( $type, $id, $app )
    {
        $post = $app->request()->post();
        $output = array();

        $areafishspawn = R::load( 'areafishspawn', $id);
        if ( $areafishspawn->id != 0 ) {
            $output['id'] = $id;
            switch ( $type ) {
                case 'up':
                    $output['votesUp'] = ++$areafishspawn->votesUp;
                    break;
                case 'down':
                    $output['votesDown'] = ++$areafishspawn->votesDown;
                    break;
            }
            // TODO: add logging logic here
            R::store( $areafishspawn );
            
            $app->response()->header('Content-Type', 'application/json');
            echo json_encode( $output );
            
        } else {
			$app->response()->status(409);
            echo "can't find areafishspawn";
        }
    }
    
}