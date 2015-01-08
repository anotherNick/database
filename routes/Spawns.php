<?php
namespace Duelist101\Db\Route;
use Duelist101\Db\View as View;
use Propel\Common\Pluralizer\StandardEnglishPluralizer as Pluralizer;
use \W101;

/**
 *
 * Create spawn points for Area Relationships.
 * Extend with a more specific class, and set $place, $thing to Table Names.
 *
 */

class Spawns
{
	public static $place;
	public static $thing;

    public static function add( $app )
    {
        $post = $app->request()->post();
        $output = array();

		// Check to see if spawn exists already.
		$spawn = self::getSpawn( static::$place, static::$thing, $post );
		print_r( $spawn );
		
        if ( empty( $spawn ) ) {
			$output = self::setSpawn( static::$place, static::$thing, $post );

			$app->response()->header('Content-Type', 'application/json');
			echo json_encode( $output );
		} else {
			$app->response()->status(409);
            echo "already in database";
        }
    }
	
	public static function getSpawn( $place, $thing, $post )
    {
		$SpawnQuery = "\\W101\\{$place}" . strtolower( $thing ) . "spawnQuery"; // Example: \W101\AreareagentspawnQuery
		$filterByPlaceId = "filterBy{$place}Id";                                // Example: filterByAreaId
		$filterByThingId = "filterBy{$thing}Id";
		$placeId = strtolower($place) . "-spawn-" . strtolower($place) . "-id"; // area-spawn-area-id
		$thingId = strtolower($place) . "-spawn-type-id";                       // area-spawn-type-id
		$xLoc = strtolower( $place ) . '-spawn-x';
		$yLoc = strtolower( $place ) . '-spawn-y';
		
		$spawn = $SpawnQuery::create()              // $spawn = \W101\AreareagentspawnQuery::create()
			->$filterByPlaceId( $post[ $placeId ] ) //   ->filterByAreaId( $post['area-spawn-area-id'] )
			->$filterByThingId( $post[ $thingId ] )
			->filterByXLoc( $post[ $xLoc ] )
			->filterByYLoc( $post[ $yLoc ] )
			->findOne();
		
		return $spawn;
	}
	
	public static function setSpawn( $place, $thing, $post )
    {
		$placeId = strtolower($place) . "-spawn-" . strtolower($place) . "-id"; // area-spawn-area-id
		$thingId = strtolower($place) . "-spawn-type-id";                       // area-spawn-type-id
		$placeQuery = "\\W101\\{$place}Query";
		$thingQuery = "\\W101\\{$thing}Query";
	
		// Get the related objects.
		$placeObject = $placeQuery::create()
			->filterById( $post[ $placeId ] )
			->findOne();
		$thingObject = $thingQuery::create()
			->filterById( $post[ $thingId ] )
			->findOne();
		
		if ( !empty( $placeObject ) && !empty( $thingObject ) ) {
			// Objects are valid, create relationship if none exists
			$relationshipQuery = "\\W101\\{$place}" . strtolower( $thing ) . "Query";
			$filterByPlace = "filterBy{$place}";
			$filterByThing = "filterBy{$thing}";
			$relationshipId = strtolower( $place ) . strtolower( $thing ) . "Id";
			
			$relationship = $relationshipQuery::create()
				->$filterByPlace( $placeObject )
				->$filterByThing( $thingObject )
				->findOneOrCreate();
				
			// If we just created this, set the default vote values.
			if ( $relationship->getVotesUp === null ) {
				$relationship->setVotesUp( 1 );
				$relationship->setVotesDown( 0 );
				$relationship->save();
			}
			
			// Set vars for Spawn
			$spawnClass = "\\W101\\" . $place . strtolower( $thing ) . 'Spawn'; // Example: \W101\AreareagentSpawn
			$setPlace = "set{$place}";
			$setThing = "set{$thing}";
			$setRelationship = $setPlace . strtolower( $thing );
			$xLoc = strtolower( $place ) . '-spawn-x';
			$yLoc = strtolower( $place ) . '-spawn-y';
			
			// Create Spawn
			$spawn = new $spawnClass(); // Example: $areareagent = new \W101\AreareagentSpawn;
			$spawn->$setPlace( $placeObject ); // Example: $areareagent->setArea( $area );
			$spawn->$setThing( $thingObject );
			$spawn->$setRelationship( $relationship );
			$spawn->setXLoc( $post[ $xLoc ] );
			$spawn->setYLoc( $post[ $yLoc ] );
			$spawn->setVotesUp( 1 );
			$spawn->setVotesDown( 0 );
			$spawn->save();
			
			
			// Set vars for output.
			$pluralizer = new Pluralizer; // Using Propel Plurals
			$places = strtolower( $pluralizer->getPluralForm( $place ) );
			$things = strtolower( $pluralizer->getPluralForm( $thing ) );
			$spawnLower = strtolower( $place ) . strtolower( $thing ) . 'spawns'; // Example: areareagent
			$placeName = strtolower( $place ) . 'Name'; // Example: areaName
			$thingName = strtolower( $thing ) . 'Name';
			$placeUrl = strtolower( $place ) . 'Url'; // Example: areaUrl
			$thingUrl = strtolower( $thing ) . 'Url';
			$placeSpawnX = strtolower( $place ) . 'SpawnX';
			$placeSpawnY = strtolower( $place ) . 'SpawnY';
			
			// Create output
			$output['id'] = $spawn->getId();
			$output['url'] = \Duelist101\BASE_URL . $spawnLower . '/' . urlencode( $spawn->getId() );
			$output[ $placeName ] = $placeObject->getName();
			$output[ $placeUrl ] = \Duelist101\BASE_URL . $places . '/' . urlencode( $placeObject->getName() );
			$output[ $thingName ] = $thingObject->getName();
			$output[ $thingUrl ] = \Duelist101\BASE_URL . $things . '/' . urlencode( $thingObject->getName() );
			$output[ $relationshipId ] = $relationship->getId();
			$output[ $placeSpawnX ] = $spawn->getXLoc();
			$output[ $placeSpawnY ] = $spawn->getYLoc();
			$output['voteUpUrl'] = \Duelist101\BASE_URL . $spawnLower . '/' . urlencode( $spawn->getId() ) . '/vote-up';
			$output['voteDownUrl'] = \Duelist101\BASE_URL . $spawnLower . '/' . urlencode( $spawn->getId() ) . '/vote-down';
			
			return $output;
		} else {
			echo "can't find {$a} or {$b}";
		}	
	}
    
}