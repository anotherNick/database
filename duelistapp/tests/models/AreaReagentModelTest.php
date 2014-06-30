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
        try {
            R::addDatabase( 'active', \Duelist101\DB_DSN, \Duelist101\DB_USERNAME, \Duelist101\DB_PASSWORD, \Duelist101\DB_FROZEN );
        }
        catch ( RedBeanPHP\RedException $e ) {}
        R::selectDatabase( 'active' );

        // create table if none exist
        if ( R::findOne( 'areareagent' ) == NULL ) {

            $reagent = R::findOne( 'reagent' );
            if ( $reagent == NULL ) {
                $reagent = W::addReagent( '1' );
            }
            
            $area = R::findOne( 'area' );
            if ( $area == NULL ) {
                $area = W::addArea( '1' );
            }
            
            W::addAreaReagent( $area, $reagent );
        }
        
        // pull into DBUnit Table
        $table =  new PHPUnit_Extensions_Database_DataSet_QueryTable(
            'areareagent',
            'SELECT * FROM areareagent limit 1',
            new PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection( new PDO( \Duelist101\DB_DSN ) )
        );
        
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