<?php
class WorldModelTest extends PHPUnit_Framework_TestCase
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
            'world', 
            function () { 
                W::addWorld( '1' );
            }
        );
        $this->assertEmpty($message, $message);
    }

}