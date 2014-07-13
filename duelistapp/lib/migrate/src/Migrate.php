<?php
namespace Migrate;
use R as R;

class Migrate
{
    private $environment = null;
    private $environments = null;
    private $files = null;
    private $migrationsDir = null;
    private $table = null;
    private $target = null;

    public function setConfigFromArray( $config )
    {
        $this->environment = $config['defaults']['environment'];
        $this->environments = $config['environments'];
        $this->migrationsDir = $config['paths']['migrations'] . DIRECTORY_SEPARATOR;
        $this->table = $config['defaults']['table'];
    }

    public function setEnvironment( $environment )
    {
        $this->environment = $environment;
    }
    
    public function setTarget()
    {
        $this->target = $target;
    }

    private function popFilesBefore( $fileName )
    {
        while ( array_pop( $this->files ) != $fileName ) {}
        $this->files[] = $file;
    }
    
    private function setup()
    {

        $env = $this->environments[$this->environment];
        $dsn = $env['dsn'];
        $dsnType = substr($dsn, 0, strpos($dsn, ':'));
        $user = isset($env['user']) ? $env['user'] : null;
        $password = isset($env['user']) ? $env['password'] : null;

        echo PHP_EOL 
            . 'Target: ' . ( is_null($this->target) ? 'all' : $this->target ) . PHP_EOL
            . 'Environment: ' . $this->environment . PHP_EOL
            . 'DSN: ' . $dsn . PHP_EOL
            . '------------------------------' . PHP_EOL;
        
        // grab files in reverse order
        chdir( $this->migrationsDir );
        $this->files = array_merge(
                glob( '*--' . $dsnType . '*' ),
                glob( '*--all*' )
        );
        rsort($this->files);

        R::setup($dsn, $user, $password);
        if ( R::findOne($this->table) !== null ) {
            // no migrations in db, remove all migrations prior to last rollup
            foreach( $this->files as $file ) {
                if ( preg_match( '[\-\-rollup]', $file) ) {
                    popFilesBefore( $file );
                    break;
                }
            }
        } else {
            // if first migration is a rollup, remove all migrations before it
            $migration = R::load( $this->table, 1 );
            if ( preg_match( '[\-\-rollup]', $migration->file ) ) {
                popFilesBefore( $migration->file );
            }
        }
        // put files back in normal order
        sort($this->files);

    }
    
    public function status()
    {
        $this->setup();

        $hasTodo = false;
        $hasDoneMissing = false;
        $dones = R::getCol('SELECT file FROM ' . $this->table );
        
        echo 'First migration in DB: '
            . ( isset($dones[0]) ? $dones[0] : 'none' ) . PHP_EOL
            . 'Migrations not in DB: ' . PHP_EOL;
        foreach( $this->files as $file ) {
            if ( !in_array($file, $dones) ) {
                echo '  ' . $file . PHP_EOL;
                $hasTodo = true;
            }
            if ( $file == $this->target ) {
                break;
            }
        }
        if ( !$hasTodo ) {
            echo '  none' . PHP_EOL;
        }
        foreach( $dones as $done) {
            if ( !in_array($done, $this->files) ) {
                if ( !$hasDoneMissing ) {
                    echo PHP_EOL
                        . '**********' . PHP_EOL
                        . 'Warning: Migrations in DB but not in migrations path' . PHP_EOL;
                    $hasDoneMissing = true;
                }
                echo '  ' . $done . PHP_EOL;
            }
        }
        if ( $hasDoneMissing ) {
            echo '**********' . PHP_EOL;
        }

    }

    public function update()
    {
        $this->setup();

        // TODO: figure out why couldn't use parameter bindings here
        $done = R::getCol('SELECT file FROM ' . $this->table );
        $isFirstMigration = true;
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
                echo "Applied: " . $file . PHP_EOL;
            }
            catch(\RedBeanPHP\RedException $e) {
                R::rollback();
                echo PHP_EOL
                    . '**********' . PHP_EOL
                    . 'ERROR' . PHP_EOL
                    . '**********' . PHP_EOL
                    . 'Migration: ' . $file . PHP_EOL
                    . ( is_null($sql) ? '' : 'SQL: ' . $sql . PHP_EOL )
                    . 'Message: ' . strtok($e, "\n") . PHP_EOL;
                die();
            }
        }
        if ( $isFirstMigration ) {
            echo 'No migrations to apply.' . PHP_EOL;
        }
    }

}
