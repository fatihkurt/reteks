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
        'database'  => [
            'host' => '127.0.0.1',
        	'name' => 'reteks_db',
        	'user' => 'root',      //reteks
        	'pass' => 'zagreb14',  //kux8zAJE
        	'port' => 3306
        ]
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
