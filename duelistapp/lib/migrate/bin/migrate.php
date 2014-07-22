<?php
namespace Migrate;

define('Migrate\COMMAND_DIR', getcwd() . DIRECTORY_SEPARATOR);
define('Migrate\APP_DIR', __DIR__ . DIRECTORY_SEPARATOR);

$config = require_once COMMAND_DIR . 'migrate-config.php';
require_once $config['paths']['redbean'];
require_once APP_DIR . '../src/Migrate.php';

$migrate = new \Migrate\Migrate();
$migrate->setConfigFromArray( $config );

$opts = getopt('e:t:');
foreach (array_keys($opts) as $opt) {
    switch ($opt) {
        case 'e':
            $migrate->setEnvironment( $opts['e'] );
            break;

        case 't':
            $migrate->setTarget( $opts['t'] );
            break;
        }
}

switch ( $argv[$argc-1] ) {
    case 'update':
        $migrate->setup();
        $migrate->update();
        break;

    case 'status':
        $migrate->setup();
        $migrate->status();
        break;

    default :
        $msg = <<<EOM
Usage: migrate [options] command

Options:
  -e   environment   Use the specified environment
  -t   target        Migrate to the specified target

Available commands:
  status   Show migrations not in database
  update   Update database (to latest if no target specified)

EOM;
        echo $msg;
        die;
}
