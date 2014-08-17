<?php
namespace Duelist101;

/*
 * Config options relative to the Duelistapp folder
 */

// Folders
define('Duelist101\APP_DIR', __DIR__ . '/');
define('Duelist101\TEMPLATES_DIR', APP_DIR . 'templates/');

// Models
define('REDBEAN_MODEL_PREFIX', '\\Duelist101\\Db\\Model\\');

// Database
define('Duelist101\SQLITE_FILE', 'data/wizdata.sqlite');
define('Duelist101\DB_DSN', 'sqlite:' . APP_DIR . SQLITE_FILE);
define('Duelist101\DB_USERNAME', null);
define('Duelist101\DB_PASSWORD', null);
define('Duelist101\DB_FROZEN', false);

