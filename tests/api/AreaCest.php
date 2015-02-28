<?php
use \ApiTester;
use \Duelist101\W as W;
include_once( 'BasicApiCest.php' );

class AreaCest extends BasicApiCest
{
/**
 * AreaNew API Test.
 * - POST
 */
	public function addNewArea(ApiTester $I)
	{
	/**
	 * Populate the DB for testing.
	 * - World
	 */

		$world = W::addWorld( 'Wizard City' );
		$world_id = $world->getId();
		$I->seeInDatabase('world', array('id' => 1, 'name' => 'Wizard City')); 

		$imageDataURI = $I->produceDataURI();
		$baseURL = $I->getBaseURL();
		$expectedURL = $baseURL . '/areas/' . urlencode('Duelist Test Area');

	/**
	 * Test valid insertion.
	 */
		
		$I->dontSeeInDatabase('area', array('id' => 1, 'world_id' => 1, 'name' => 'Duelist Test Area'));
		
		$I->wantTo('Add a New Area via API');
		$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
		$I->sendPOST('/areas', ['world-id' => $world_id, 'name' => 'Duelist Test Area', 'imageDataUrl' => $imageDataURI]);
			// Ensure correct response.
		$I->seeResponseCodeIs(200);
		$I->seeResponseIsJson();
		$I->seeResponseContainsJson(array('status' => 'success'));
		$I->seeResponseContainsJson(array('redirect' => $expectedURL));
		$I->seeInDatabase('area', array('id' => 1, 'world_id' => 1, 'name' => 'Duelist Test Area'));
			// Ensure Image saved correctly.
		$I->seeFileFound('Duelist-Test-Area.jpg', 'public_html/images/w101_world_maps/');
			// Clean up after ourselves.
		$I->amInPath('public_html/images/w101_world_maps');
		$I->deleteFile('Duelist-Test-Area.jpg');

	/**
	 * Test duplicate entry.
	 */
		 
		$I->wantTo('Add a Duplicate Area via API');
		$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
		$I->sendPOST('/areas', ['world-id' => $world_id, 'name' => 'Duelist Test Area', 'imageDataUrl' => $imageDataURI]);
			// Ensure correct response.
		$I->seeResponseCodeIs(200);
		$I->seeResponseIsJson();
		$I->seeResponseContainsJson(array('status' => 'failures'));
		$I->seeResponseContainsJson(array('property' => 'name'));
		$I->seeResponseContainsJson(array('message' => 'This value is already stored in your database'));
		$I->dontSeeInDatabase('area', array('id' => 2, 'world_id' => 1));
			// Ensure Image did not save.
		$I->dontSeeFileFound('Duelist-Test-Area.jpg', 'public_html/images/w101_world_maps/');
		 
	 /**
	 * Test invalid entry.
	 */

			// Invalid world, no name, no image.
		$I->wantTo('Add Invalid Areas via API');
		$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
		$I->sendPOST('/areas', ['world-id' => 2, 'name' => '']);
			// Ensure correct response.
		$I->seeResponseCodeIs(200);
		$I->seeResponseIsJson();
		$I->seeResponseContainsJson(array('status' => 'failures'));
		$I->seeResponseContainsJson(array('property' => 'name'));
		$I->seeResponseContainsJson(array('property' => 'world-id'));
		$I->seeResponseContainsJson(array('property' => 'image'));
		$I->seeResponseContainsJson(array('message' => 'This value should not be blank.'));
		$I->seeResponseContainsJson(array('message' => 'Please select one.'));
		$I->seeResponseContainsJson(array('message' => 'Please upload a picture and then crop the area to use.'));
		$I->dontSeeInDatabase('area', array('id' => 2, 'world_id' => 1));

			// Only whitespace for name.
		$I->sendPOST('/areas', ['world-id' => 2, 'name' => ' ']);
			// Ensure correct response.
		$I->seeResponseCodeIs(200);
		$I->seeResponseIsJson();
		$I->seeResponseContainsJson(array('status' => 'failures'));
		$I->seeResponseContainsJson(array('property' => 'name'));
		$I->seeResponseContainsJson(array('message' => 'This value should not be blank.'));	

			// Only multiple whitespace for name.
		$I->sendPOST('/areas', ['world-id' => 2, 'name' => '      ']);
			// Ensure correct response.
		$I->seeResponseCodeIs(200);
		$I->seeResponseIsJson();
		$I->seeResponseContainsJson(array('status' => 'failures'));
		$I->seeResponseContainsJson(array('property' => 'name'));
		$I->seeResponseContainsJson(array('message' => 'This value should not be blank.'));			

	}	
	
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

		$I->wantTo('Add an AreaFish via API');
		$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
		$I->sendPOST('/areafish', ['area_id' => '1', 'fish_id' => '1']);
		$I->seeResponseCodeIs(200);
		$I->seeResponseIsJson();
		$I->seeInDatabase('areafish', array('id' => 1, 'area_id' => 1, 'fish_id' => 1));

	/**
	 * Test duplicate entry.
	 */
		 
		$I->wantTo('Add a duplicate Areafish via API');
		$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
		$I->sendPOST('/areafish', ['area_id' => '1', 'fish_id' => '1']);
		$I->seeResponseCodeIs(409);
		$I->dontSeeInDatabase('areafish', array('id' => 2));
		 
	 /**
	 * Test invalid entry.
	 */
		 
		$I->wantTo('Add an invalid Areafish via API');
		$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
		$I->sendPOST('/areafish', ['area_id' => '3', 'fish_id' => '1']);
		$I->seeResponseCodeIs(409);
		$I->dontSeeInDatabase('areafish', array('id' => 2));
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