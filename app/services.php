<?php

$view = $app->view();

$view->parserOptions = array(
    'debug' => $app->config('debug'),
    'cache' => $app->config('cache.path') . '/twig'
);

$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
);