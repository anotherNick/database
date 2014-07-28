<?php
use \RedBeanPHP\OODBBean as Bean;

Class W
{
    private static $areDatabasesSetup = false;
    private static $migrationConfig = array();

    // Helper functions
    
    public static function compareColumns( $table, $callback )
    {
        R::selectDatabase( 'empty' );
        $callback();
        $allEmpty = R::inspect( $table );

        R::selectDatabase( 'tests' );
        $allTests = R::inspect( $table );

        $missingInEmpty = array_diff_key( $allTests, $allEmpty );
        $missingInTests = array_diff_key( $allEmpty, $allTests );

        $message = '';
        foreach ( $missingInTests as $key => $value) {
            $message .= '* Column missing in TESTS: ' . $key . PHP_EOL;
        }
        foreach ( $missingInEmpty as $key => $value) {
            $message .= '* Column missing in EMPTY: ' . $key . PHP_EOL;
        }

        if ( strlen($message) > 0 ) {
            $message = '**************************' . PHP_EOL . $message;
            $dsn = self::$migrationConfig['environments']['tests']['dsn'];
            R::selectDatabase( 'tests' );
            R::freeze( false );
            R::debug(true, 1);
            $callback();
            $logs = R::getDatabaseAdapter()->getDatabase()->getLogger()->getLogs();

            $message .= '**************************' . PHP_EOL
                . '* Possible migration: ' . date('YmdHis') . '--' . substr($dsn, 0, strpos($dsn, ':')) . '-' . $table . '.sql' . PHP_EOL
                . '**************************' . PHP_EOL;
            foreach( $logs as $logEntry ) {
                if ( preg_match( '/(CREATE|ALTER|DROP|INSERT.*tmp_backup)/', $logEntry ) ) {
                    $message .= $logEntry . ';' . PHP_EOL;
                }
            }
        }
        return $message;
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
            define('Migrate\COMMAND_DIR', \Duelist101\APP_DIR);
            define('Duelist101\BASE_URL', '/');
            self::$migrationConfig = include \Duelist101\APP_DIR . 'migrate-config.php';
            R::addDatabase('empty', 'sqlite::memory:');
            R::addDatabase('tests', self::$migrationConfig['environments']['tests']['dsn']);
            self::$areDatabasesSetup = true;
        }

        R::selectDatabase( 'empty' );
        R::nuke();
        
        R::selectDatabase( 'tests' );
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
        $bean = R::dispense( 'area' );
        $bean->name = 'Name ' . $name ;
        $bean->image = 'Image' . $name . '.gif';
		$bean->world = $world;
        $bean = W::setProperties($bean, $properties);
        R::store( $bean );
 
        return $bean;
    }
    
    public static function addAreareagent( Bean $area=null, Bean $reagent=null, array $properties=null  )
    {
        $bean = R::dispense( 'areareagent' );
        $bean->area = $area;
        $bean->reagent = $reagent;
        $bean->votesUp = 0;
        $bean->votesDown = 0;
        $bean = W::setProperties($bean, $properties);
        R::store( $bean );
 
        return $bean;
    }
	
    public static function addClass( $name='' )
    {
        $bean = R::dispense( 'class' );
        $bean->name = 'Class ' . $name;
        R::store ( $bean );
        
        return $bean;
    }
 
    public static function addFish( Bean $rarity, Bean $class, Bean $housingitem, $name='', array $properties=null )
    {
        $bean = R::dispense( 'fish' );
        $bean->name = 'Name ' . $name ;
        $bean->image = 'Image' . $name . '.gif';
        $bean->rarity = $rarity;
        $bean->class = $class;
        $bean->aquarium = $housingitem;
        $bean->rank = 1;
        $bean->description = 'Description ' . $name;
        $bean->initialXp = 100;
        $bean = W::setProperties($bean, $properties);
        R::store( $bean );
 
        return $bean;
    }

    public static function addHousingitem( Bean $housingtype, $name='', array $properties=null )
    {
        $bean = R::dispense( 'housingitem' );
        $bean->name = 'Name ' . $name;
        $bean->housingtype = $housingtype;
        $bean->canTrade = true;
        $bean->canAuction = true;
        $bean = W::setProperties($bean, $properties);
        R::store ( $bean );
        
        return $bean;
    }
 
     public static function addHousingtype( $name='' )
    {
        $bean = R::dispense( 'housingtype' );
        $bean->name = 'Name ' . $name;
        R::store ( $bean );
        
        return $bean;
    }
    
    public static function addMock( $name='' )
    {
        $bean = R::dispense( 'mock' );
        $bean->name = 'Mock ' . $name;
        R::store ( $bean );
        
        return $bean;
    }
    
    public static function addRarity( $name='' )
    {
        $bean = R::dispense( 'rarity' );
        $bean->name = 'Rarity ' . $name;
        R::store ( $bean );
        
        return $bean;
    }
 
    public static function addReagent( Bean $class, $name='', array $properties=null )
    {
        $bean = R::dispense( 'reagent' );
        $bean->name = 'Name ' . $name ;
        $bean->rank = 1;
        $bean->image = 'Image' . $name . '.gif';
        $bean->description = 'Description ' . $name;
        $bean->can_auction = 0;
        $bean->is_crowns_only = 0;
        $bean->is_retired = 0;
        $bean->class = $class;
        $bean = W::setProperties($bean, $properties);
        R::store( $bean );
 
        return $bean;
    }

    public static function addWorld( $name='', array $properties=null  )
    {
        $bean = R::dispense( 'world' );
        $bean->name = 'Name ' . $name ;
		$bean->image = 'Image' . $name . '.gif';
        $bean = W::setProperties($bean, $properties);
        R::store( $bean );
 
        return $bean;
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