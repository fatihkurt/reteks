<?php

$app->config(array(
    'languages' => ['tr', 'en'],
    'languages.default' => 'tr',
));

// Only invoked if mode is "production"
$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'log.enable'=> true,
        'debug'     => false
    ));
});


// Only invoked if mode is "development"
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'log.enable'=> false,
        'debug'     => true
    ));
});