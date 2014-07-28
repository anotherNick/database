<?php
class AreaReagentSpawnModelTest extends PHPUnit_Framework_TestCase
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
            'areareagentspawn', 
            function () { 
                $world = W::addWorld( '1' );
                $area = W::addArea( $world, '1' );
                $class = W::addClass( '1' );
                $reagent = W::addReagent( $class, '1' );
                W::addAreareagentspawn( $area, $reagent );
            }
        );
        $this->assertEmpty( $message, $message );
    }

}