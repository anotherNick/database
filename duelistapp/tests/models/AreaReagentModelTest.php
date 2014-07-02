<?php
class AreaReagentModelTest extends PHPUnit_Framework_TestCase
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
        $table = W::getOrCreateDataSet( 'areareagent', 'addAreaReagent' );
        $expectedColumns = array(
             'id',
             'votes_up',
             'votes_down',
             'area_id',
             'reagent_id'
         );
        $this->assertEquals(
            $expectedColumns, 
            $table->getTableMetaData()->getColumns()
        );
    }

}