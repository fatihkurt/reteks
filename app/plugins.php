<?php


$initLanguage = function(\Slim\Route $route) {

    $lang = $route->getParam('lang');

    $app = \Slim\Slim::getInstance();

    if (! in_array($lang, $app->config('languages'))) {

        $app->flash('error', 'Login required');

        $defaultLang = $app->getLang;

        $app->redirect("/$defaultLang/");
    }

    $_SESSION['lang'] = $lang;
};