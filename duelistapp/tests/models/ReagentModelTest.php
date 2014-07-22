<?php
class ReagentModelTest extends PHPUnit_Framework_TestCase
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
            'reagent', 
            function () { 
                $class = W::addClass( '1' );
                W::addReagent( $class, '1' );
            }
        );
        $this->assertEmpty($message, $message);
    }

    public function testSetClass()
    {
        $class = R::findOne( 'class', 'name = ?', [ 'Fire' ] );
        $reagent = W::addReagent( $class, '1' );
        
        $newClass = R::findOne( 'class', 'name = ?', [ 'Storm' ] );
        $this->assertNotNull( $newClass );
        
        $reagent->class = $newClass;
        R::store ( $reagent );
        $this->assertEquals( $reagent->class->name, 'Storm');
    }

    public function testAreaReagents()
    {
        $class = R::findOne( 'class', 'name = ?', [ 'Fire' ] );
        $reagent = W::addReagent( $class, '1' );
        $world = W::addWorld( '1' );
        $area = W::addArea( $world, '1' );
        $areaReagent = W::addAreareagent( $area, $reagent);
        
        $areaList = $reagent->ownAreareagentList;
        $this->assertNotEmpty($areaList);
    }

}