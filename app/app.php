<?php

require 'vendor/autoload.php';

$app = new \Slim\Slim([
    'debug'         => true,
    'mode'          => 'development',
    'view'          => new \Slim\Views\Twig(),
    'templates.path'=> APP_DIR . 'view',
    'cache.path'    => APP_DIR . 'cache',
]);


include APP_DIR . 'services.php';

include APP_DIR . 'config.php';

include APP_DIR . 'routes.php';

return $app;
