<?php
class AreaReagentModelTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once '../vendor/autoload.php';
        require_once 'models/WizardTestObjects.php';
        W::setupCleanRedBean();
    }

    public function testRequiredFields()
    {
        $table = W::setupAndGetActiveDataSet( 'areareagent', W::addAreaReagent( null, null ) );
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