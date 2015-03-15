<?php

$view = $app->view();

$view->parserOptions = array(
    'debug' => $app->config('debug'),
    'cache' => $app->config('cache.path') . '/twig'
);

$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
);


$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires'   => '30 minutes',
    'path'      => '/',
    'domain'    => null,
    'secure'    => false,
    'httponly'  => false,
    'name'      => 'slim_session',
    'secret'    => 'RTex123.',
    'cipher'    => MCRYPT_RIJNDAEL_256,
    'cipher_mode'=> MCRYPT_MODE_CBC
)));


$app->getLang = function() use($app){

    return isset($_SESSION['lang']) ? $_SESSION['lang'] : $app->config('languages.default');
};