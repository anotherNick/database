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

		$areafishList = \W101\AreafishQuery::create()
			->filterByAreaId( $post['area_id'] )
			->filterByFishId( $post['fish_id'] )
			->findOne();
        if ( empty($areafishList) ) {
			$fish = \W101\FishQuery::create()
				->filterById( $post['fish_id'] )
				->findOne();
			$area = \W101\AreaQuery::create()
				->filterById( $post['area_id'] )
				->findOne();
            if ( $fish->getId() != 0 && $area->getId() != 0 ) {
				$areafish = new \W101\Areafish();
				$areafish->setFish( $fish );
				$areafish->setArea( $area );
				$areafish->setVotesUp( 1 );
				$areafish->setVotesDown( 0 );
				$areafish->save();

                $output['id'] = $areafish->getId();
                
                $output['url'] = \Duelist101\BASE_URL . 'areafish/' . urlencode( $areafish->getId() );
                $output['areaName'] = $area->getName();
                $output['areaUrl'] = \Duelist101\BASE_URL . 'areas/' . urlencode( $area->getName() );
                $output['fishName'] = $fish->getName();
                $output['fishUrl'] = \Duelist101\BASE_URL . 'fish/' . urlencode( $fish->getName() );
                $output['voteUpUrl'] = \Duelist101\BASE_URL . 'areafish/' . urlencode( $areafish->getId() ) . '/vote-up';
                $output['voteDownUrl'] = \Duelist101\BASE_URL . 'areafish/' . urlencode( $areafish->getId() ) . '/vote-down';
                
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
        $areafish = \W101\AreafishQuery::create()
			->filterById( $id )
			->findOne();
        if ( $areafish->getId() != 0 ) {
            $output['id'] = $id;
            switch ( $type ) {
                case 'up':
					$votes = ++$areafish->getVotesUp();
                    $areafish->setVotesUp( $votes );
					$output['votesUp'] = $votes();
                    break;
                case 'down':
                    $votes = ++$areafish->getVotesDown();
                    $areafish->setVotesDown( $votes );
					$output['votesDown'] = $votes;
                    break;
            }
            // TODO: add logging logic here
            $areafish->save()
            
            $app->response()->header('Content-Type', 'application/json');
            echo json_encode( $output );
            
        } else {
            echo "can't find areafish";
        }
    }
    
}