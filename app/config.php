<?php

$app->config(array(
    'languages' => ['tr', 'en'],
    'languages.default' => 'tr',
    'recaptcha_secret'  => '6Le8EwYTAAAAAEtGDKHXG1DnP912M930b5ImygKS',
));

// Only invoked if mode is "production"
$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'log.enable'=> true,
        'debug'     => false,
        'database'  => [
            'host' => 'localhost',
        	'name' => 'reteksco_reteks_db',
        	'user' => 'reteksco_reteks',
        	'pass' => 'r_Z=hSSbzQt5',
        	'port' => 3306
        ]
    ));
});
$app->configureMode('aws', function () use ($app) {
    $app->config(array(
        'log.enable'=> true,
        'debug'     => false,
        'database'  => [
            'host' => '127.0.0.1',
        	'name' => 'reteks_db',
        	'user' => 'root',      //reteks //root
        	'pass' => 'zagreb14',  //kux8zAJE //zagreb14
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
