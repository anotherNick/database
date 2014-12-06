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

        $areafishspawns = \W101\AreafishspawnQuery::create()
			->filterByAreaId( $post['area-spawn-area-id'] )
			->filterByFishId( $post['area-spawn-type-id'] )
			->filterByXLoc( $post['area-spawn-x'] )
			->filterByYLoc( $post['area-spawn-y'] )
			->findOne();
        if ( empty($areafishspawns) ) {
			$fish = \W101\FishQuery::create()
				->filterById( $post['area-spawn-type-id'] )
				->findOne();
			$area = \W101\AreaQuery::create()
				->filterById( $post['area-spawn-area-id'] )
				->findOne();
			
			// Create areafish relationship if none exists
			$areafish = \W101\AreafishQuery::create()
				->filterByFish( $fish )
				->filterByArea( $area )
				->findOneOrCreate();

			// If we just created this, set the default vote values.
			if ( $areafish->getVotesUp === null ) {
				$areafish->setVotesUp( 1 );
				$areafish->setVotesDown( 0 );
				$areafish->save();
			}
			
			$output['areafishId'] = $areafish->getId();
			
			// Create areafishspawn
			$areafishspawn = new \W101\Areafishspawn();
			$areafishspawn->setArea( $area );
			$areafishspawn->setFish( $fish );
			$areafishspawn->setAreafish( $areafish );
			$areafishspawn->setXLoc( $post['area-spawn-x'] );
			$areafishspawn->setYLoc( $post['area-spawn-y'] );
			$areafishspawn->setVotesUp( 1 );
			$areafishspawn->setVotesDown( 0 );
			$areafishspawn->save();
			
			$output['id'] = $areafishspawn->getId();
			$output['url'] = \Duelist101\BASE_URL . 'areafishspawns/' . urlencode( $areafishspawn->getId() );
			$output['areaName'] = $area->getName();
			$output['areaUrl'] = \Duelist101\BASE_URL . 'areas/' . urlencode( $area->getName() );
			$output['fishName'] = $fish->getName();
			$output['fishUrl'] = \Duelist101\BASE_URL . 'fishs/' . urlencode( $fish->getName() );
			$output['areaSpawnX'] = $areafishspawn->getXLoc();
			$output['areaSpawnY'] = $areafishspawn->getYLoc();
			$output['voteUpUrl'] = \Duelist101\BASE_URL . 'areafishspawns/' . urlencode( $areafishspawn->getId() ) . '/vote-up';
			$output['voteDownUrl'] = \Duelist101\BASE_URL . 'areafishspawns/' . urlencode( $areafishspawn->getId() ) . '/vote-down';
			
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

		$areafishspawn = \W101\AreafishspawnQuery::create()
			->filterById( $id )
			->findOne();
        if ( $areafishspawn->getId() != 0 ) {
            $output['id'] = $id;
            switch ( $type ) {
                case 'up':
					$votes = 1 + $areafishspawn->getVotesUp();
                    $areafishspawn->setVotesUp( $votes );
					$output['votesUp'] = $votes;
                    break;
                case 'down':
					$votes = 1 + $areafishspawn->getVotesDown();
                    $areafishspawn->setVotesDown( $votes );
					$output['votesDown'] = $votes;
                    break;
            }
            // TODO: add logging logic here
            $areafishspawn->save();
            
            $app->response()->header('Content-Type', 'application/json');
            echo json_encode( $output );
            
        } else {
			$app->response()->status(409);
            echo "can't find areafishspawn";
        }
    }
    
}