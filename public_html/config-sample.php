<?php
namespace Duelist101;

/*
 * Holds config variables that are relative to index.php
 */

// Folders
define('Duelist101\APP_DIR', __DIR__ . '/../');
define('Duelist101\TEMPLATES_DIR', APP_DIR . 'templates/');

// Files
define('Duelist101\AUTOLOAD_FILE', APP_DIR . 'vendor/autoload.php');
define('Duelist101\WPLOAD_FILE', '/xampp/htdocs/duelist101/wp-load.php');
define('Duelist101\PROPEL_CONFIG_PROD', APP_DIR . 'generated-conf/config.php');
define('Duelist101\PROPEL_CONFIG_TEST', APP_DIR . 'generated-conf/test-config.php');

// IPs allowed for testing
define('Duelist101\ALLOWED_TEST_IPS', '127.0.0.1');
