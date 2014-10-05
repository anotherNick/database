<?php
class FishModelTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once '../vendor/autoload.php';
        require_once 'models/WizardTestObjects.php';
        W::setupDatabases();
    }

    public function testSchema()
    {
        $message = W::compareColumns ( 
            'fish', 
            function () {
                $rarity = W::addRarity( '1' );
                $class = W::addClass( '1' );
                $housingtype = W::addHousingtype( '1' );
                $housingitem = W::addHousingitem( $housingtype, '1' );
                W::addFish( $rarity, $class, $housingitem, '1');
            }
        );
        $this->assertEmpty($message, $message);
    }

}