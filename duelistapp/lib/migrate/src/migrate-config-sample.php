<?php
namespace Migrate;
date_default_timezone_set('UTC');

return array(
    'defaults' => array(
        'environment' => 'development',
        'table' => 'migrations',
    ),
    'paths' => array(
        'migrations' => COMMAND_DIR . 'migrations',
        'redbean' => COMMAND_DIR . 'vendor/redbean/rb.php'
    ),
    'environments' => array(
        'development' => array(
            'dsn' => 'sqlite:' . COMMAND_DIR . 'data/test.sqlite'
        ),
        'test' => array(
            'dsn' => 'sqlite::memory:'
        )
    )
);
