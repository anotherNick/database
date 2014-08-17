<?php
namespace Duelist101;
use Duelist101\Db\Route as Route;
use R as R;

require 'config.php';
require AUTOLOAD_FILE;
require WPLOAD_DIR . 'wp-load.php';

R::setup( DB_DSN, DB_USERNAME, DB_PASSWORD, DB_FROZEN );
$app = new \Slim\Slim(array(
    'view' => new WordpressView(),
    'templates.path' => TEMPLATES_DIR
));
define('Duelist101\BASE_URL', $app->request()->getUrl() . $app->request()->getRootUri() . '/');

$app->post('/areafish', function () use ($app) { Route\Areafish::add( $app ); });
$app->post('/areafish/:id/vote-down', function ($id) use ($app) { Route\Areafish::vote( 'down', $id, $app ); });
$app->post('/areafish/:id/vote-up', function ($id) use ($app) { Route\Areafish::vote( 'up', $id, $app ); });

$app->post('/areareagents', function () use ($app) { Route\Areareagents::add( $app ); });
$app->post('/areareagents/:id/vote-down', function ($id) use ($app) { Route\Areareagents::vote( 'down', $id, $app ); });
$app->post('/areareagents/:id/vote-up', function ($id) use ($app) { Route\Areareagents::vote( 'up', $id, $app ); });
$app->post('/areareagentspawns', function () use ($app) { Route\Areareagentspawns::add( $app ); });

$app->get('/areas.json', function () use ($app) { Route\Areas::listJson( $app ); });
$app->get('/areas/', function () use ($app) { Route\Areas::listHtml( $app ); });
$app->get('/areas/:name', function ($name) use ($app) { Route\Areas::singleHtml( $name, $app ); });

$app->get('/fish/', function () use ($app) { Route\Fish::listHtml( $app ); });
$app->get('/fish/:name', function ($name) use ($app) { Route\Fish::singleHtml( $name, $app ); });

$app->get('/reagents/', function () use ($app) { Route\Reagents::listHtml( $app ); });
$app->get('/reagents/:name', function ($name) use ($app) { Route\Reagents::singleHtml( $name, $app ); });
$app->get('/reagents.json', function () use ($app) { Route\Reagents::listJson( $app ); });

$app->run();
