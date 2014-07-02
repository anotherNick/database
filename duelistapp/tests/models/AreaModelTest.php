<?php
class AreaModelTest extends PHPUnit_Framework_TestCase
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
        $table = W::getOrCreateDataSet( 'area', 'addArea' );
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