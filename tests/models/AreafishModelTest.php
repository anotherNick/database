<?php
class AreaFishModelTest extends PHPUnit_Framework_TestCase
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
            'areafish', 
            function () { 
            
                $world = W::addWorld( '1' );
                $area = W::addArea( $world, '1' );
                
                $rarity = W::addRarity( '1' );
                $class = W::addClass( '1' );
                $housingtype = W::addHousingtype( '1' );
                $housingitem = W::addHousingitem( $housingtype, '1' );
                $fish = W::addFish( $rarity, $class, $housingitem, '1');
                
                W::addAreafish( $area, $fish);
            }
        );
        $this->assertEmpty($message, $message);
    }

}