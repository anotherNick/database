<?php
namespace Duelist101\Db\Route;
use Duelist101\Db\View as View;
use R as R;

class Areareagentspawns
{
    public static function add( $app )
    {
        $post = $app->request()->post();
        $output = array();

        $areareagentspawns = R::find( 
            'areareagentspawn', 
            'area_id = ? and reagent_id = ? and x_loc = ? and y_loc = ?', 
            [ $post['area-spawn-area-id'], 
			  $post['area-spawn-type-id'],
			  $post['area-spawn-x'],
			  $post['area-spawn-y'] ]
        );
        if ( empty($areareagentspawns) ) {
            $reagent = R::load( 'reagent', $post['area-spawn-type-id'] );
            $area = R::load( 'area', $post['area-spawn-area-id'] );
			
			// Create Areareagent relationship if none exists
			$areareagent = R::findOne( 
				'areareagent', 
				'area_id = ? and reagent_id = ?', 
				[ $post['area-spawn-area-id'], $post['area-spawn-type-id'] ]
			);
			if ( empty($areareagent) ) {
				if ( $reagent->id != 0 && $area->id != 0 ) {
					$areareagent = R::dispense( 'areareagent' );
					$areareagent->area = $area;
					$areareagent->reagent = $reagent;
					$areareagent->votesUp = 1;
					$areareagent->votesDown = 0;
					$output['areaReagentId'] = R::store( $areareagent );
				} else {
					$app->response()->status(409);
					echo "can't find area or reagent";
				}
			} else {
				$output['areaReagentId'] = $areareagent->id;
			}
			
			// Create Areareagentspawn
            if ( $reagent->id != 0 && $area->id != 0 ) {
                $areareagentspawn = R::dispense( 'areareagentspawn' );
                $areareagentspawn->area = $area;
                $areareagentspawn->reagent = $reagent;
				$areareagentspawn->areareagent = $areareagent;
				$areareagentspawn->x_loc = $post['area-spawn-x'];
				$areareagentspawn->y_loc = $post['area-spawn-y'];
                $areareagentspawn->votesUp = 1;
                $areareagentspawn->votesDown = 0;
                $output['id'] = R::store( $areareagentspawn );
                
                $output['url'] = \Duelist101\BASE_URL . 'areareagentspawns/' . urlencode($areareagentspawn->id);
                $output['areaName'] = $area->name;
                $output['areaUrl'] = \Duelist101\BASE_URL . 'areas/' . urlencode($area->name);
                $output['reagentName'] = $reagent->name;
                $output['reagentUrl'] = \Duelist101\BASE_URL . 'reagents/' . urlencode($reagent->name);
				$output['areaSpawnX'] = $areareagentspawn->x_loc;
				$output['areaSpawnY'] = $areareagentspawn->y_loc;
                $output['voteUpUrl'] = \Duelist101\BASE_URL . 'areareagentspawns/' . urlencode($areareagentspawn->id) . '/vote-up';
                $output['voteDownUrl'] = \Duelist101\BASE_URL . 'areareagentspawns/' . urlencode($areareagentspawn->id) . '/vote-down';
                
                $app->response()->header('Content-Type', 'application/json');
                echo json_encode( $output );
            } else {
				$app->response()->status(409);
                echo "can't find area or reagent";
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

        $areareagentspawn = R::load( 'areareagentspawn', $id);
        if ( $areareagentspawn->id != 0 ) {
            $output['id'] = $id;
            switch ( $type ) {
                case 'up':
                    $output['votesUp'] = ++$areareagentspawn->votesUp;
                    break;
                case 'down':
                    $output['votesDown'] = ++$areareagentspawn->votesDown;
                    break;
            }
            // TODO: add logging logic here
            R::store( $areareagentspawn );
            
            $app->response()->header('Content-Type', 'application/json');
            echo json_encode( $output );
            
        } else {
			$app->response()->status(409);
            echo "can't find areareagentspawn";
        }
    }
    
}