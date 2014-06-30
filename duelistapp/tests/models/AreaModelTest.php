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
        try {
            R::addDatabase( 'active', \Duelist101\DB_DSN, \Duelist101\DB_USERNAME, \Duelist101\DB_PASSWORD, \Duelist101\DB_FROZEN );
        }
        catch ( RedBeanPHP\RedException $e ) {}
        R::selectDatabase( 'active' );

        // create table if none exist
        if ( R::findOne( 'area' ) == NULL ) {
            W::addArea( '1' );
        }
        
        // pull into DBUnit Table
        $table =  new PHPUnit_Extensions_Database_DataSet_QueryTable(
            'area',
            'SELECT * FROM area limit 1',
            new PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection( new PDO( \Duelist101\DB_DSN ) )
        );
        
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