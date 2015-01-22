<?php
use \ApiTester;
use \Duelist101\W as W;
include_once( 'BasicApiCest.php' );

class AreaCest extends BasicApiCest
{
	/**
	 * AreaFish API Test.
	 * - POST
	 */
	public function addAreafish(ApiTester $I)
	{
		/**
		 * Populate the DB for testing.
		 * - World->Area
		 * - Class
		 * - Rarity
		 * - Fish
		 *
		 */

		$world = W::addWorld( 'Wizard City' );
		W::addArea( 'The Commons', $world );
		$class = W::addClass( 'Fire' );
		$rarity = W::addRarity( 'Rare' );
		W::addFish( $rarity, $class, 'Archerfish' );

		/**
		 * Test valid insertion.
		 */

		$I->wantTo('Add an Areafish via API');
		$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
		$I->sendPOST('/areafish', ['area_id' => '1', 'fish_id' => '1']);
		$I->seeResponseCodeIs(200);
		$I->seeResponseIsJson();

		/**
		 * Test duplicate entry.
		 */
		 
		$I->wantTo('Add a duplicate Areafish via API');
		$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
		$I->sendPOST('/areafish', ['area_id' => '1', 'fish_id' => '1']);
		$I->seeResponseCodeIs(409);
		 
		 /**
		 * Test invalid entry.
		 */
		 
		$I->wantTo('Add an invalid Areafish via API');
		$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
		$I->sendPOST('/areafish', ['area_id' => '3', 'fish_id' => '1']);
		$I->seeResponseCodeIs(409);
	}
	
	/**
	 * AreaReagents API Test.
	 * - POST
	 */
	public function addAreareagent(ApiTester $I)
	{
		/**
		 * Populate the DB for testing.
		 * - World->Area
		 * - Class
		 * - Reagent
		 *
		 */

		$world = W::addWorld( 'Wizard City' );
		W::addArea( 'The Commons', $world );
		$class = W::addClass( 'Fire' );
		W::addReagent( 'Acorn', $class, 'A small acorn.' );

		/**
		 * Test valid insertion.
		 */

		$I->wantTo('Add an Areareagent via API');
		$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
		$I->sendPOST('/areareagents', ['area_id' => '1', 'reagent_id' => '1']);
		$I->seeResponseCodeIs(200);
		$I->seeResponseIsJson();

		/**
		 * Test duplicate entry.
		 */
		 
		$I->wantTo('Add a duplicate Areareagent via API');
		$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
		$I->sendPOST('/areareagents', ['area_id' => '1', 'reagent_id' => '1']);
		$I->seeResponseCodeIs(409);

		/**
		 * Test invalid entry.
		 */
		 
		$I->wantTo('Add an invalid Areareagent via API');
		$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
		$I->sendPOST('/areareagents', ['area_id' => '3', 'reagent_id' => '1']);
		$I->seeResponseCodeIs(409);
	}

	/**
	 * AreaReagentSpawns API Test.
	 * - POST
	 */
	public function addAreareagentspawn(ApiTester $I)
	{
		/**
		 * Populate the DB for testing.
		 * - World->Area
		 * - Class
		 * - Reagent
		 * - Areareagent
		 *
		 */

		$world = W::addWorld( 'Wizard City' );
		$area = W::addArea( 'The Commons', $world );
		$class = W::addClass( 'Fire' );
		$reagent = W::addReagent( 'Acorn', $class, 'A small acorn.' );
		W::addAreareagent( $area, $reagent );

		/**
		 * Test valid insertion.
		 */

		$I->wantTo('Add an Areareagentspawn via API');
		$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
		$I->sendPOST('/areareagentspawns', ['area-spawn-area-id' => '1', 'area-spawn-type-id' => '1', 'area-spawn-x' => '58', 'area-spawn-y' => '50']);
		$I->seeResponseCodeIs(200);
		$I->seeResponseIsJson();

		/**
		 * Test duplicate entry.
		 */

		$I->wantTo('Add a duplicate Areareagentspawn via API');
		$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
		$I->sendPOST('/areareagentspawns', ['area-spawn-area-id' => '1', 'area-spawn-type-id' => '1', 'area-spawn-x' => '58', 'area-spawn-y' => '50']);
		$I->seeResponseCodeIs(409);
	}
	
	
}





?>