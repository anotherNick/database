<?php
class ClassModelTest extends PHPUnit_Framework_TestCase
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
            'class', 
            function () { 
                W::addClass( '1' );
            }
        );
        $this->assertEmpty($message, $message);
    }

}