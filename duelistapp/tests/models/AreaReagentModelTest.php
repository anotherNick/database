<?php
class AreaReagentModelTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once '../vendor/autoload.php';
        require_once 'models/WizardTestObjects.php';
        W::setupTestDatabase();
    }

    public function testWizardTestObjectSchema()
    {
        W::addAreareagent();
        $sql = "SELECT sql FROM `sqlite_master` WHERE type='table' AND name='areareagent'";
        W::compareSchemas( $sql, $this );
    }
    
}