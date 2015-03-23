<?php


$initLanguage = function(\Slim\Route $route) {

    $lang = $route->getParam('lang');

    $app = \Slim\Slim::getInstance();

    if (! in_array($lang, $app->config('languages'))) {

        // @TODO log
        //$app->flash('error', 'Parametre hatası');

        $app->redirect("/$app->getLang/");
    }

    // load ini file
    // @TODO cache results
    $section = $route->getName() ?: 'main';

    $translateD = parse_ini_file(APP_DIR . "locale/$lang/$section.ini");

    $app->view()->setData('T', $translateD);

    $_SESSION['lang'] = $lang;
};




$authenticateForRole = function ($role = 'admin') {
    return function () use ($role) {
        $user = User::fetchFromDatabaseSomehow();
        if ( $user->belongsToRole($role) === false ) {
            $app = \Slim\Slim::getInstance();
            $app->flash('error', 'Login required');
            $app->redirect('/login');
        }
    };
};