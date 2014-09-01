<?php
namespace Duelist101\Db\Route;
use Duelist101\Db\View as View;
use R as R;

class Areareagents
{
    public static function add( $app )
    {
        $post = $app->request()->post();
        $output = array();

        $areareagents = R::find( 
            'areareagent', 
            'area_id = ? and reagent_id = ?', 
            array( $post['area_id'], $post['reagent_id'] )
        );
        if ( empty($areareagents) ) {
            $reagent = R::load( 'reagent', $post['reagent_id'] );
            $area = R::load( 'area', $post['area_id'] );
            if ( $reagent->id != 0 && $area->id != 0 ) {
                $areareagent = R::dispense( 'areareagent' );
                $areareagent->area = $area;
                $areareagent->reagent = $reagent;
                $areareagent->votesUp = 1;
                $areareagent->votesDown = 0;
                $output['id'] = R::store( $areareagent );
                
                $output['url'] = \Duelist101\BASE_URL . 'areareagents/' . urlencode($areareagent->id);
                $output['areaName'] = $area->name;
                $output['areaUrl'] = \Duelist101\BASE_URL . 'areas/' . urlencode($area->name);
                $output['reagentName'] = $reagent->name;
                $output['reagentUrl'] = \Duelist101\BASE_URL . 'reagents/' . urlencode($reagent->name);
                $output['voteUpUrl'] = \Duelist101\BASE_URL . 'areareagents/' . urlencode($areareagent->id) . '/vote-up';
                $output['voteDownUrl'] = \Duelist101\BASE_URL . 'areareagents/' . urlencode($areareagent->id) . '/vote-down';
                
                $app->response()->header('Content-Type', 'application/json');
                echo json_encode( $output );
            } else {
                echo "can't find area or reagent";
            }
        } else {
            echo "already in database";
        }
    }

    public static function vote( $type, $id, $app )
    {
        $post = $app->request()->post();
        $output = array();

        $areareagent = R::load( 'areareagent', $id);
        if ( $areareagent->id != 0 ) {
            $output['id'] = $id;
            switch ( $type ) {
                case 'up':
                    $output['votesUp'] = ++$areareagent->votesUp;
                    break;
                case 'down':
                    $output['votesDown'] = ++$areareagent->votesDown;
                    break;
            }
            // TODO: add logging logic here
            R::store( $areareagent );
            
            $app->response()->header('Content-Type', 'application/json');
            echo json_encode( $output );
            
        } else {
            echo "can't find areareagent";
        }
    }
    
}