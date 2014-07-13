<?php
namespace Migrate;

class Migrate
{
    private $environment;
    private $environments;
    private $files;
    private $migrationsDir;
    private $table;
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
        echo 'Using environment: ' . $this->environment . PHP_EOL;
        echo 'Using target: ' . ( if (is_null($this->target)) ? 'All' : $this->target ) . PHP_EOL;

        $dsnType = substr($env['dsn'], 0, strpos($env['dsn'], ':') );
        $env = $this->environments[$this->environment];
        
        // grab files in reverse order
        $this->files = rsort( array_merge(
                glob( $this->migrationsDir . '*-' . $dsnType . '-*' ),
                glob( $this->migrationsDir . '*-all-*' )
        ) );

        R:setup($env['dsn'], $env['user'], $env['password');
        if ( R::findOne($this->table) !== null ) {
            // no migrations in db, remove all migrations prior to last rollup
            foreach( $this->files as $file ) {
                if ( preg_match( '-rollup-', $file) ) {
                    popFilesBefore( $file );
                    break;
                }
            }
        } else {
            // if first migration is a rollup, remove all migrations before it
            $migration = R::load( $this->table, 1 );
            if ( preg_match( '-rollup-', $migration->file ) {
                popFilesBefore( $migration->file );
            }
        }
        // put files back in normal order
        $this->files = sort($this->files);

    }
    
    public function status()
    {
        $this->setup();
        $noMigrations = true;
        $done = R::getCol('SELECT migration FROM ?', [ $table ]);

        echo 'First migration in DB: '
            . ( if (isset($done[0])) ? $done[0] : 'None' ) . PHP_EOL
            . 'Migrations not in DB: ' . PHP_EOL;
        foreach( $this->files as $file) {
            if ( !in_array($file, $done) ) {
                echo ' ' . $file . PHP_EOL;
                $noMigrations = false;
            }
            if ( $file == $this->target ) {
                break;
            }
        }
        if ( $noMigrations ) {
            echo ' None' . PHP_EOL;
        }
    }

    public function update()
    {
        $this->setup();
        $done = R::getCol('SELECT migration FROM ?', [ $table ]);
        $todo = array();

        foreach( $this->files as $file) {
            if ( !in_array($file, $done) ) {
                $todo[] = $file;
            }
            if ( $file == $this->target ) {
                break;
            }
        }

        foreach( $todo as $file ) {
            switch ( substr($file, -4) ) {
                case '.sql':
                    $sql = file_get_contents( $this->migrationsDir . $file;
                    R::exec( $sql );
                    break;
                case '.php':
                    include $this->migrationsDir . $file;
                    break;
                default:
                    // throw error 
            }
        }
    }

}
