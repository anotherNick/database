<?php
class ReagentModelTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once '../vendor/autoload.php';
        require_once 'models/WizardTestObjects.php';
        W::setupTestDatabase();
    }

    public function testRequiredFields()
    {
        W::setupActiveDatabase();
        $table = W::getOrCreateDataSet( 'reagent', 'addReagent' );
        $expectedColumns = array(
             'id',
             'name',
             'rank',
             'image',
             'description',
             'can_auction',
             'is_crowns_only',
             'is_retired',
             'class_id'
         );
        $this->assertEquals(
            $expectedColumns, 
            $table->getTableMetaData()->getColumns()
        );
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

}