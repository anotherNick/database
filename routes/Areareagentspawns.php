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

        $areareagentspawns = \W101\AreareagentspawnQuery::create()
			->filterByAreaId( $post['area-spawn-area-id'] )
			->filterByReagentId( $post['area-spawn-type-id'] )
			->filterByXLoc( $post['area-spawn-x'] )
			->filterByYLoc( $post['area-spawn-y'] )
			->findOne();
        if ( empty($areareagentspawns) ) {
			$reagent = \W101\ReagentQuery::create()
				->filterById( $post['area-spawn-type-id'] )
				->findOne();
			$area = \W101\AreaQuery::create()
				->filterById( $post['area-spawn-area-id'] )
				->findOne();
			
			// Create areareagent relationship if none exists
			$areareagent = \W101\AreareagentQuery::create()
				->filterByReagent( $reagent )
				->filterByArea( $area )
				->findOneOrCreate();

			// If we just created this, set the default vote values.
			if ( $areareagent->getVotesUp === null ) {
				$areareagent->setVotesUp( 1 );
				$areareagent->setVotesDown( 0 );
				$areareagent->save();
			}
			
			$output['areareagentId'] = $areareagent->getId();
			
			// Create areareagentspawn
			$areareagentspawn = new \W101\AreareagentSpawn();
			$areareagentspawn->setArea( $area );
			$areareagentspawn->setReagent( $reagent );
			$areareagentspawn->setAreareagent( $areareagent );
			$areareagentspawn->setXLoc( $post['area-spawn-x'] );
			$areareagentspawn->setYLoc( $post['area-spawn-y'] );
			$areareagentspawn->setVotesUp( 1 );
			$areareagentspawn->setVotesDown( 0 );
			$areareagentspawn->save();
			
			$output['id'] = $areareagentspawn->getId();
			$output['url'] = \Duelist101\BASE_URL . 'areareagentspawns/' . urlencode( $areareagentspawn->getId() );
			$output['areaName'] = $area->getName();
			$output['areaUrl'] = \Duelist101\BASE_URL . 'areas/' . urlencode( $area->getName() );
			$output['reagentName'] = $reagent->getName();
			$output['reagentUrl'] = \Duelist101\BASE_URL . 'reagentss/' . urlencode( $reagent->getName() );
			$output['areaSpawnX'] = $areareagentspawn->getXLoc();
			$output['areaSpawnY'] = $areareagentspawn->getYLoc();
			$output['voteUpUrl'] = \Duelist101\BASE_URL . 'areareagentspawns/' . urlencode( $areareagentspawn->getId() ) . '/vote-up';
			$output['voteDownUrl'] = \Duelist101\BASE_URL . 'areareagentspawns/' . urlencode( $areareagentspawn->getId() ) . '/vote-down';
			
			$app->response()->header('Content-Type', 'application/json');
			echo json_encode( $output );
		} else {
			$app->response()->status(409);
            echo "already in database";
        }
    }

    public static function vote( $type, $id, $app )
    {
        $post = $app->request()->post();
        $output = array();

		$areareagentspawn = \W101\AreareagentspawnQuery::create()
			->filterById( $id )
			->findOne();
        if ( $areareagentspawn->getId() != 0 ) {
            $output['id'] = $id;
            switch ( $type ) {
                case 'up':
					$votes = 1 + $areareagentspawn->getVotesUp();
                    $areareagentspawn->setVotesUp( $votes );
					$output['votesUp'] = $votes;
                    break;
                case 'down':
					$votes = 1 + $areareagentspawn->getVotesDown();
                    $areareagentspawn->setVotesDown( $votes );
					$output['votesDown'] = $votes;
                    break;
            }
            // TODO: add logging logic here
            $areareagentspawn->save();
            
            $app->response()->header('Content-Type', 'application/json');
            echo json_encode( $output );
            
        } else {
			$app->response()->status(409);
            echo "can't find areareagentspawn";
        }
    }
    
}