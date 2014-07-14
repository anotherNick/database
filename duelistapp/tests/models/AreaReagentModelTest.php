<?php
class AreaReagentModelTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once '../vendor/autoload.php';
        require_once 'models/WizardTestObjects.php';
        W::setupDatabases();
    }

    public function testWizardTestObjectSchema()
    {
        R::selectDatabase( 'empty' );
        $world = W::addWorld( '1' );
        $area = W::addArea( $world, '1' );
        $reagent = W::addReagent( '1' );
        W::addAreareagent( $area, $reagent);
        $sql = "SELECT sql FROM `sqlite_master` WHERE type='table' AND name='areareagent'";
        W::compareSchemas( $sql, $this );
    }
    
}