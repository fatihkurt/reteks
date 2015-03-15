<?php


$initLanguage = function(\Slim\Route $route) {

    $lang = $route->getParam('lang');

    $app = \Slim\Slim::getInstance();

    if (! in_array($lang, $app->config('languages'))) {

        $app->flash('error', 'Login required');

        $defaultLang = $app->getLang;

        $app->redirect("/$defaultLang/");
    }

    // load ini file
    // @TODO cache results
    $section = $route->getName() ?: 'main';

    $translateD = parse_ini_file(APP_DIR . "locale/$lang/$section.ini");

    $app->view()->setData('T', $translateD);

    $_SESSION['lang'] = $lang;
};
