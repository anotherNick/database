<?php
class ReagentModelTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once '../vendor/autoload.php';
        require_once 'models/WizardTestObjects.php';
        W::setupTestDatabase();
    }

    public function testWizardTestObjectSchema()
    {
        W::addReagent( '1' );
        $sql = "SELECT sql FROM `sqlite_master` WHERE type='table' AND name='reagent'";
        W::compareSchemas( $sql, $this );
    }

    public function testSetClass()
    {
        $reagent = W::addReagent( '1' );
        
        $newClass = R::findOne( 'class', 'name = ?', [ 'Storm' ] );
        $this->assertNotNull( $newClass );
        
        $reagent->class = $newClass;
        R::store ( $reagent );
        $this->assertEquals( $reagent->class_id, 3);
    }

    public function testAreaReagentss()
    {
        $reagent = W::addReagent( '1' );
        $area = W::addArea( '1' );
        $areaReagent = W::addAreareagent( $area, $reagent);
        
        $areaList = $reagent->ownAreareagentList;
        $this->assertNotEmpty($areaList);
    }
       
}