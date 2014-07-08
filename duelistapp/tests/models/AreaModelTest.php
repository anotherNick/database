<?php
class AreaModelTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once '../vendor/autoload.php';
        require_once 'models/WizardTestObjects.php';
        W::setupTestDatabase();
    }

    public function testWizardTestObjectSchema()
    {
        W::addArea( '1' );
        $sql = "SELECT sql FROM `sqlite_master` WHERE type='table' AND name='area'";
        W::compareSchemas( $sql, $this );
    }
}