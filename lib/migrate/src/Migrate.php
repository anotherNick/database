<?php
namespace Migrate;
use R as R;

class Migrate
{
    private $environment = null;
    private $environments = null;
    private $files = null;
    private $migrationsDir = null;
    private $quiet = false;
    private $table = null;
    private $target = null;

    private function connectDb()
    {
        $env = $this->environments[$this->environment];
        $dsn = $env['dsn'];
        $user = isset($env['user']) ? $env['user'] : null;
        $password = isset($env['user']) ? $env['password'] : null;
        R::setup($dsn, $user, $password);
    }

    public function setConfigFromArray( $config )
    {
        $this->environment = $config['defaults']['environment'];
        $this->environments = $config['environments'];
        $this->migrationsDir = $config['paths']['migrations'] . DIRECTORY_SEPARATOR;
        $this->table = $config['defaults']['table'];
    }

    private function popFilesBefore( $fileName )
    {
        while ( array_pop( $this->files ) != $fileName ) {}
        $this->files[] = $file;
    }
    
    public function setEnvironment( $environment )
    {
        $this->environment = $environment;
    }

    public function setFiles( $show=true )
    {
        $dsn = $this->environments[$this->environment]['dsn'];
        $driver = substr($dsn, 0, strpos($dsn, ':'));
        $curDir = getcwd();
        
        if ( !$this->quiet ) {
            echo PHP_EOL 
                . 'Target: ' . ( is_null($this->target) ? 'all' : $this->target ) . PHP_EOL
                . 'Environment: ' . $this->environment . PHP_EOL
                . 'DSN: ' . $dsn . PHP_EOL
                . '------------------------------' . PHP_EOL;
        }
                
        // grab files in reverse order
        chdir( $this->migrationsDir );
        $this->files = array_merge(
                glob( '*--' . $driver . '*' ),
                glob( '*--all*' )
        );
        rsort($this->files);

        $done = null;
        R::freeze(true);
        try {
            $done = R::load( $this->table, 1);
            if ( preg_match( '[\-\-rollup]', $done->file ) ) {
                popFilesBefore( $done->file );
            }
        }
        catch (\RedBeanPHP\RedException $e) {
            // no migrations in db, remove all migrations prior to last rollup
            foreach( $this->files as $file ) {
                if ( preg_match( '[\-\-rollup]', $file) ) {
                    popFilesBefore( $file );
                    break;
                }
            }
        }

        // put files back in normal order
        sort($this->files);

        chdir( $curDir );
    }
    
    public function setQuiet( $quiet )
    {
        $this->quiet = $quiet;
    }

    public function setTarget()
    {
        $this->target = $target;
    }

    public function setup()
    {
        $this->connectDb();
        $this->setFiles();
    }
    
    public function status()
    {
        
        $hasTodo = false;
        $hasDoneMissing = false;
        try {
            $dones = R::getCol('SELECT file FROM ' . $this->table );
        }
        catch (\RedBeanPHP\RedException $e) {
            $dones = array();
        }
        
        $output = 'First migration in DB: '
            . ( isset($dones[0]) ? $dones[0] : 'none' ) . PHP_EOL
            . 'Migrations not in DB: ' . PHP_EOL;
        foreach( $this->files as $file ) {
            if ( !in_array($file, $dones) ) {
                $output .=  '  ' . $file . PHP_EOL;
                $hasTodo = true;
            }
            if ( $file == $this->target ) {
                break;
            }
        }
        if ( !$hasTodo ) {
            $output .= '  none' . PHP_EOL;
        }
        foreach( $dones as $done ) {
            if ( !in_array($done, $this->files) ) {
                if ( !$hasDoneMissing ) {
                    $output .= PHP_EOL
                        . '**********' . PHP_EOL
                        . 'Warning: Migrations in DB but not in migrations path' . PHP_EOL;
                    $hasDoneMissing = true;
                }
                $output .= '  ' . $done . PHP_EOL;
            }
        }
        if ( $hasDoneMissing ) {
            $output .= '**********' . PHP_EOL;
        }

        if ( !$this->quiet ) {
            echo $output;
        }

    }

    public function update()
    {
        // TODO: figure out why couldn't use parameter bindings here
        try {
            $done = R::getCol('SELECT file FROM ' . $this->table );
        }
        catch (\RedBeanPHP\RedException $e) {
            $done = array();
        }
        $isFirstMigration = true;
        $output = '';
        $todo = array();

        foreach( $this->files as $file) {
            if ( !in_array($file, $done) ) {
                $todo[] = $file;
            }
            if ( $file == $this->target ) {
                break;
            }
        }
        R::freeze( true );
        foreach( $todo as $file ) {
            R::begin();
            try {
                switch ( substr($file, -4) ) {
                    case '.sql':
                        $contents = file_get_contents( $this->migrationsDir . $file );
                        $sql_array = explode(";", $contents);
                        foreach( $sql_array as $sql) {
                            $sql = trim($sql);
                            R::exec( $sql . ';');
                        }
                        break;
                    case '.php':
                        include $this->migrationsDir . $file;
                        break;
                    default:
                        throw new \RedBeanPHP\RedException('Unrecognized file extension for: ' . $file);
                }
                R::commit();
                $migration = R::dispense( $this->table );
                $migration->file = $file;
                if ( $isFirstMigration ) {
                    R::freeze( false );
                    R::store( $migration );
                    R::freeze( true );
                    $isFirstMigration = false;
                } else {
                    R::store( $migration );
                }
                $output .= "Applied: " . $file . PHP_EOL;
            }
            catch(\RedBeanPHP\RedException $e) {
                R::rollback();
                $output .= PHP_EOL
                    . '**********' . PHP_EOL
                    . 'ERROR' . PHP_EOL
                    . '**********' . PHP_EOL
                    . 'Migration: ' . $file . PHP_EOL
                    . ( is_null($sql) ? '' : 'SQL: ' . $sql . PHP_EOL )
                    . 'Message: ' . strtok($e, "\n") . PHP_EOL;
                echo $output;
                die();
            }
        }
        if ( $isFirstMigration ) {
            $output .= 'No migrations to apply.' . PHP_EOL;
        }

        if ( !$this->quiet ) {
            echo $output;
        }
    }

}
