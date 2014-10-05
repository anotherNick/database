<?php
class RarityModelTest extends PHPUnit_Framework_TestCase
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
            'rarity', 
            function () { 
                W::addRarity( '1' );
            }
        );
        $this->assertEmpty($message, $message);
    }

}