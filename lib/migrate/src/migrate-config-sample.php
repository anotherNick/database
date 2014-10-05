<?php
namespace Migrate;
date_default_timezone_set('UTC');

return array(
    'defaults' => array(
        'environment' => 'dev',
        'table' => 'migrations',
    ),
    'paths' => array(
        'migrations' => COMMAND_DIR . 'migrations',
        'redbean' => COMMAND_DIR . 'vendor/redbeanphp/rb.php'
    ),
    'environments' => array(
        'dev' => array(
            'dsn' => 'sqlite:' . COMMAND_DIR . 'data/test.sqlite'
        ),
        'test' => array(
            'dsn' => 'sqlite::memory:'
        )
    )
);
