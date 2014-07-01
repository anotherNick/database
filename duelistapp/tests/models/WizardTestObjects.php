<?php

/* Handy code snippet for saving views
   file_put_contents( 'views/.html', $stamp );
*/

Class W
{
    private static $doneOnce;
    
    private function __construct() {}

    public static function setupCleanRedBean()
    {
        if ( !isset(self::$doneOnce) ) {
            $activeDbFile = \Duelist101\APP_DIR . \Duelist101\SQLITE_FILE;
            $testDbFile = \Duelist101\APP_DIR . 'data/testData.sqlite';
            if ( !copy($activeDbFile, $testDbFile) )  {
                throw new Exception('Failed to create data/testData.sqlite');
            }
            R::setUp( 'sqlite:' . $testDbFile);
            self::$doneOnce = true;
        }
        R::selectDatabase( 'default' );
        R::wipe( 'reagent' );
        R::wipe( 'area' );
        R::wipe( 'areareagent' );
    }

    public static function setupAndGetActiveDataSet( $table, $createTableFunction )
    {
        try {
            R::addDatabase( 'active', \Duelist101\DB_DSN, \Duelist101\DB_USERNAME, \Duelist101\DB_PASSWORD, \Duelist101\DB_FROZEN );
        }
        catch ( RedBeanPHP\RedException $e ) {}
        R::selectDatabase( 'active' );

        // call function to create table if none exist
        if ( R::findOne( $table ) == NULL ) {
            $createTableFunction();
        }
        
        // pull into DBUnit Table
        return new PHPUnit_Extensions_Database_DataSet_QueryTable(
            $table,
            'SELECT * FROM ' . $table . ' limit 1',
            new PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection( new PDO( \Duelist101\DB_DSN ) )
        );
    }
    
    private static function addClass( $name )
    {
        $class = R::dispense( 'class' );
        $class->name = $name;
        R::store ( $class );
    }
    
    private static function setProperties( $bean, $properties )
    {
        foreach ( (array) $properties as $key => $value ) {
            $bean->{$key} = $value;
        }
        return $bean;
    }
    
    public function addLookups()
    {
        if ( R::findOne('class') == NULL ) {
            W::addCLass( 'Fire' );
            W::addCLass( 'Ice' );
            W::addCLass( 'Storm' );
            W::addCLass( 'Balance' );
            W::addCLass( 'Life' );
            W::addCLass( 'Death' );
            W::addCLass( 'Myth' );
        }
    }

    public static function addReagent( $name, $properties=null )
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

    public static function addArea( $name, $properties=null  )
    {
        $a = R::dispense( 'area' );
        $a->name = 'Name ' . $name ;
        $a->image = 'Image' . $name . '.gif';
        $a = W::setProperties($a, $properties);
        R::store( $a );
 
        return $a;
    }

    
    public static function addAreaReagent( $area=null, $reagent=null, $properties=null  )
    {
        if ( $area == null && $reagent == null ) {
            $reagent = R::findOne( 'reagent' );
            if ( $reagent == NULL ) {
                $reagent = W::addReagent( '1' );
            }
            
            $area = R::findOne( 'area' );
            if ( $area == NULL ) {
                $area = W::addArea( '1' );
            }
        }

        $ar = R::dispense( 'areareagent' );
        $ar->area = $area;
        $ar->reagent = $reagent;
        $ar->votesUp = 0;
        $ar->votesDown = 0;
        $ar = W::setProperties($ar, $properties);
        R::store( $ar );
 
        return $ar;
    }

}
