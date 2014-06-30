<?php
class ReagentModelTest extends PHPUnit_Framework_TestCase
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
        if ( R::findOne( 'reagent' ) == NULL ) {
            $reagent = W::addReagent( '1' );
        }
        
        // pull into DBUnit Table
        $table =  new PHPUnit_Extensions_Database_DataSet_QueryTable(
            'reagent',
            'SELECT * FROM reagent limit 1',
            new PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection( new PDO( \Duelist101\DB_DSN ) )
        );
        
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