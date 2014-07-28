<?php
class HousingitemModelTest extends PHPUnit_Framework_TestCase
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
            'housingitem', 
            function () { 
                $housingtype = W::addHousingtype( '1' );
                W::addHousingitem( $housingtype, '1' );
            }
        );
        $this->assertEmpty($message, $message);
    }

}