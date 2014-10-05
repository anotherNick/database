<?php
class HousingtypeModelTest extends PHPUnit_Framework_TestCase
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
            'housingtype', 
            function () { 
                W::addHousingtype( '1' );
            }
        );
        $this->assertEmpty($message, $message);
    }

}