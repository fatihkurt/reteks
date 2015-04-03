<?php

define('PUB_DIR', __DIR__ . '/');
define('APP_DIR', PUB_DIR . '../app/');


$app = include (APP_DIR . 'app.php');

$app->run();
