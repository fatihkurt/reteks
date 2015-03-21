<?php

$app->config(array(
    'languages' => ['tr', 'en'],
    'languages.default' => 'tr',
));

// Only invoked if mode is "production"
$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'log.enable'=> true,
        'debug'     => false,
        'database'  => []
    ));
});


// Only invoked if mode is "development"
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'log.enable'=> false,
        'debug'     => true,
        'database'  => [
        	'host' => 'localhost',
        	'name' => 'reteks_db', 
        	'user' => 'root',
        	'pass' => '123',
        	'port' => 3306
        ],
    ));
});