<?php

// $app->contentType('text/html; charset=utf-8');
//$app->response()->header('Content-Type', 'text/html;charset=utf-8');

header('Content-type: text/html; charset=utf-8');

require '../vendor/autoload.php';


$app = new \Slim\Slim([
    'debug'         => isset($_SERVER['APPLICATION_ENV']) && $_SERVER['APPLICATION_ENV'] == 'development',
    'mode'          => isset($_SERVER['APPLICATION_ENV']) ? $_SERVER['APPLICATION_ENV'] :  'production',
    'view'          => new \Slim\Views\Twig(),
    'templates.path'=> APP_DIR . 'view',
    'cache.path'    => APP_DIR . 'cache',
]);

$app->contentType('text/html; charset=utf-8');


include APP_DIR . 'config.php';

include APP_DIR . 'services.php';

include APP_DIR . 'plugins.php';

include APP_DIR . 'routes.php';

return $app;
