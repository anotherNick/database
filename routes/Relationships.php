<?php
namespace Duelist101\Db\Route;
use Duelist101\Db\View as View;
use Propel\Common\Pluralizer\StandardEnglishPluralizer as Pluralizer;
use \W101;

/**
 *
 * A generic relationship setter.
 * Extend with a more specific class, and set $A, $B to Table Names.
 *
 */
class Relationships
{
	public static $A;
	public static $B;
	
    public static function add( $app )
    {
        $post = $app->request()->post();
        $output = array();

		try{
			// Check to see if relationship exists already.
			$relationship = self::getRelationship( static::$A, static::$B, $post );
			
			// Relationship does not exist, create one.
			$output = self::setRelationship( static::$A, static::$B, $post );
			
			$app->response()->header('Content-Type', 'application/json');
			echo json_encode( $output );
			
		}catch( \Exception $e ){
			\Propel\Runtime\Propel::log( $e, \Monolog\Logger::INFO);
			$app->response()->status(409);
			
		}
    }
	
	public static function getRelationship( $a, $b, $post )
    {
		$AbQuery = "\\W101\\{$a}" . strtolower( $b ) . "Query"; // Example: \W101\AreareagentQuery
		$filterByAId = "filterBy{$a}Id"; // Example: filterByAreaId
		$filterByBId = "filterBy{$b}Id";
		$aId = strtolower($a) . "_id";
		$bId = strtolower($b) . "_id";
		
		$relationship = $AbQuery::create() // Example $areareagent = /W101/AreareagentQuery::create()
			->$filterByAId( $post[ $aId ] )// ->FilterByAreaId( $post[ 'area_id' ] )
			->$filterByBId( $post[ $bId ] )
			->findOne();
			
		if ( !empty( $relationship ) ) {
			$e = "Tried to add relationship for {static::$A} and {static::$B} but {$relationship->getId()} already in database";
			throw new \Exception( $e );
			
		}else{
			return $relationship;
			
		}
	}
	
	public static function setRelationship( $a, $b, $post )
    {
		$aId = strtolower($a) . "_id"; // For getting the ID's from $post
		$bId = strtolower($b) . "_id";
		$aQuery = "\\W101\\{$a}Query"; // Example: \W101\AreaQuery::create()
		$bQuery = "\\W101\\{$b}Query";
	
		// Get the related objects.
		$objectA = $aQuery::create()
			->filterById( $post[ $aId ] )
			->findOne();
		$objectB = $bQuery::create()
			->filterById( $post[ $bId ] )
			->findOne();
		
		if ( !empty( $objectA ) && !empty( $objectB ) ) {
			// Objects are valid, create relationship.
			
			$aBClass = "\\W101\\" . strtolower( $a ) . $b; // Example: \W101\areaReagent
			$setA = "set{$a}";
			$setB = "set{$b}";
			
			$objectAB = new $aBClass(); // Example: $areareagent = new \W101\areaReagent;
			$objectAB->$setA( $objectA ); // Example: $areareagent->setArea( $area );
			$objectAB->$setB( $objectB );
			$objectAB->setVotesUp( 1 );
			$objectAB->setVotesDown( 0 );
			$objectAB->save();

			$pluralizer = new Pluralizer; // Using Propel Plurals
			$aPlural = strtolower( $pluralizer->getPluralForm( $a ) );
			$bPlural = strtolower( $pluralizer->getPluralForm( $b ) );
			$aBLower = strtolower( $a ) . strtolower( $b ); // Example: areareagent
			$aName = strtolower( $a ) . 'Name'; // Example: areaName
			$bName = strtolower( $b ) . 'Name';
			$aUrl = strtolower( $a ) . 'Url'; // Example: areaUrl
			$bUrl = strtolower( $b ) . 'Url';
			
			$output['id'] = $objectAB->getId();
			$output['url'] = \Duelist101\BASE_URL . $aBLower . '/' . urlencode( $objectAB->getId() );
			$output[ $aName ] = $objectA->getName();
			$output[ $aUrl ] = \Duelist101\BASE_URL . $aPlural . '/' . urlencode( $objectA->getName() );
			$output[ $bName ] = $objectA->getName();
			$output[ $bUrl ] = \Duelist101\BASE_URL . $bPlural . '/' . urlencode( $objectB->getName() );
			$output['voteUpUrl'] = \Duelist101\BASE_URL . $aBLower . '/' . urlencode( $objectAB->getId() ) . '/vote-up';
			$output['voteDownUrl'] = \Duelist101\BASE_URL . $aBLower . '/' . urlencode( $objectAB->getId() ) . '/vote-down';
			
			return $output;
			
		} else {
			throw new \Exception("Invalid static::$A or static::$B");
			
		}	
	}
    
}