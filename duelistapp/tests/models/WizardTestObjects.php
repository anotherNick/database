<?php

/* Handy code snippet for saving views
   file_put_contents( 'views/XXX.html', '<div>' . PHP_EOL . $stamp . PHP_EOL . '</div>' );
*/
use \RedBeanPHP\OODBBean as Bean;

Class W
{
    private static $areDatabasesSetup = false;
    private static $migrationConfig = array();

    // Helper functions
    
    public static function compareSchemas( $sql, $testCase )
    {
        R::selectDatabase( 'empty' );
        $empty = R::getCell( $sql );
        R::selectDatabase( 'tests' );
        $tests = R::getCell( $sql );
        R::selectDatabase( 'default' );
        $default = R::getCell( $sql );
        
        $testCase->assertNotNull( $empty, 'No schema created in EMPTY' );
        $testCase->assertEquals( 
            str_replace(',', ",\n", preg_replace('/\s+/', '', $tests)),
            str_replace(',', ",\n", preg_replace('/\s+/', '', $empty)),
            "---------- TESTS SCHEMA ----------\n"
            . $tests
            . "\n----------- EMPTY SCHEMA -----------\n"
            . $empty . "\n"
        );
        $testCase->assertEquals( 
            str_replace(',', ",\n", preg_replace('/\s+/', '', $default)),
            str_replace(',', ",\n", preg_replace('/\s+/', '', $empty)),
            "---------- DEFAULT SCHEMA ----------\n"
            . $default
            . "\n----------- EMPTY SCHEMA -----------\n"
            . $empty . "\n"
        );
    }
    
    private static function setProperties( $bean, $properties )
    {
        foreach ( (array) $properties as $key => $value ) {
            $bean->{$key} = $value;
        }
        return $bean;
    }

    public static function setupDatabases()
    {
        if ( !self::$areDatabasesSetup ) {
            R::setUp( \Duelist101\DB_DSN, \Duelist101\DB_USERNAME, \Duelist101\DB_PASSWORD, true );

            define('Migrate\COMMAND_DIR', \Duelist101\APP_DIR);
            self::$migrationConfig = include \Duelist101\APP_DIR . 'migrate-config.php';
            R::addDatabase(
                'tests', 
                self::$migrationConfig['environments']['tests']['dsn'], 
                \Duelist101\DB_USERNAME, 
                \Duelist101\DB_PASSWORD, 
                true );
            R::addDatabase( 'empty', 'sqlite::memory:');
            self::$areDatabasesSetup = true;
        }

        R::selectDatabase( 'empty' );
        R::nuke();
        
        R::selectDatabase( 'tests' );
        R::freeze(false);
        R::nuke();
        R::freeze(true);
        $migrate = new \Migrate\Migrate();
        $migrate->setConfigFromArray( self::$migrationConfig );
        $migrate->setEnvironment( 'tests' );
        $migrate->setQuiet( true );
        $migrate->setFiles();
        $migrate->update();
    }

    // add Models
    
    public static function addArea( Bean $world, $name='', array $properties=null  )
    {
        $a = R::dispense( 'area' );
        $a->name = 'Name ' . $name ;
        $a->image = 'Image' . $name . '.gif';
		$a->world = $world;
        $a = W::setProperties($a, $properties);
        R::store( $a );
 
        return $a;
    }
    
    public static function addAreareagent( Bean $area=null, Bean $reagent=null, array $properties=null  )
    {
        $ar = R::dispense( 'areareagent' );
        $ar->area = $area;
        $ar->reagent = $reagent;
        $ar->votesUp = 0;
        $ar->votesDown = 0;
        $ar = W::setProperties($ar, $properties);
        R::store( $ar );
 
        return $ar;
    }
	
    private static function addClass( string $name )
    {
        $class = R::dispense( 'class' );
        $class->name = $name;
        R::store ( $class );
    }
    
    public static function addReagent( $name='', array $properties=null )
    {
        $class = R::load( 'class', 1);
    
        $r = R::dispense( 'reagent' );
        $r->name = 'Name ' . $name ;
        $r->rank = 1;
        $r->image = 'Image' . $name . '.gif';
        $r->description = 'Description ' . $name;
        $r->can_auction = 0;
        $r->is_crowns_only = 0;
        $r->is_retired = 0;
        $r->class = $class;
        $r = W::setProperties($r, $properties);
        R::store( $r );
 
        return $r;
    }

    public static function addWorld( $name='', array $properties=null  )
    {
        $w = R::dispense( 'world' );
        $w->name = 'Name ' . $name ;
		$w->image = 'Image' . $name . '.gif';
        $w = W::setProperties($w, $properties);
        R::store( $w );
 
        return $w;
    }

}

/*
    To remember if want to use DBUnit again

    public static function getOrCreateDataSet( $table, $createFunction)
    {
        // call function to create table if doesn't exist
        if ( R::findOne( $table ) == NULL ) {
            W::$createFunction();
        }
        
        // pull into DBUnit Table
        return new PHPUnit_Extensions_Database_DataSet_QueryTable(
            $table,
            'SELECT * FROM ' . $table . ' limit 1',
            new PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection( new PDO( \Duelist101\DB_DSN ) )
        );
    }
*/