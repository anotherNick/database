<?php
class AreaModelTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once '../vendor/autoload.php';
        require_once 'models/WizardTestObjects.php';
        W::setupCleanRedBean();
    }

    public function testRequiredFields()
    {
        $table = W::setupAndGetActiveDataSet( 'area', W::addArea( '1' ) );
        $expectedColumns = array(
             'id',
             'name',
             'image'
         );
        $this->assertEquals(
            $expectedColumns, 
            $table->getTableMetaData()->getColumns()
        );
    }

}