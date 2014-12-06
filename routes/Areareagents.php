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

        $areareagents = \W101\AreareagentQuery::create()
			->filterByAreaId( $post['area_id'] )
			->filterByReagentId( $post['reagent_id'] )
			->findOne();

        if ( empty($areareagents) ) {
			$reagent = \W101\ReagentQuery::create()
				->filterById( $post['reagent_id'] )
				->findOne();
			$area = \W101\AreaQuery::create()
				->filterById( $post['area_id'] )
				->findOne();
            if ( $reagent->getId() != 0 && $area->getId() != 0 ) {
				$areareagent = new \W101\areaReagent();
				$areareagent->setReagent( $reagent );
				$areareagent->setArea( $area );
				$areareagent->setVotesUp( 1 );
				$areareagent->setVotesDown( 0 );
				$areareagent->save();

                $output['id'] = $areareagent->getId();
                
                $output['url'] = \Duelist101\BASE_URL . 'areareagent/' . urlencode( $areareagent->getId() );
                $output['areaName'] = $area->getName();
                $output['areaUrl'] = \Duelist101\BASE_URL . 'areas/' . urlencode( $area->getName() );
                $output['reagentName'] = $reagent->getName();
                $output['reagentUrl'] = \Duelist101\BASE_URL . 'reagent/' . urlencode( $reagent->getName() );
                $output['voteUpUrl'] = \Duelist101\BASE_URL . 'areareagent/' . urlencode( $areareagent->getId() ) . '/vote-up';
                $output['voteDownUrl'] = \Duelist101\BASE_URL . 'areareagent/' . urlencode( $areareagent->getId() ) . '/vote-down';
                
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
        $areareagent = \W101\AreareagentQuery::create()
			->filterById( $id )
			->findOne();
        if ( $areareagent->getId() != 0 ) {
            $output['id'] = $id;
            switch ( $type ) {
                case 'up':
					$votes = 1 + $areareagent->getVotesUp();
                    $areareagent->setVotesUp( $votes );
					$output['votesUp'] = $votes;
                    break;
                case 'down':
                    $votes = 1 + $areareagent->getVotesDown();
                    $areareagent->setVotesDown( $votes );
					$output['votesDown'] = $votes;
                    break;
            }
            // TODO: add logging logic here
            $areareagent->save();
            
            $app->response()->header('Content-Type', 'application/json');
            echo json_encode( $output );
            
        } else {
            echo "can't find areareagent";
        }
    }
    
}