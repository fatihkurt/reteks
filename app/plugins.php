<?php


$initLanguage = function(\Slim\Route $route) {

    $lang = $route->getParam('lang');

    $app = \Slim\Slim::getInstance();

    if (! in_array($lang, $app->config('languages'))) {

        // @TODO log
        //$app->flash('error', 'Parametre hatası');

        $app->redirect("/$app->getLang");
    }

    // first set session
    $_SESSION['lang'] = $lang;

    // then set translate singleton
    $app->view()->setData('T', $app->t);
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


function upperTR($str) {

    if ($_SESSION['lang'] == 'tr') {

        $str = str_replace(array('i', 'ı', 'ü', 'ğ', 'ş', 'ö', 'ç'), array('İ', 'I', 'Ü', 'Ğ', 'Ş', 'Ö', 'Ç'), $str);
    }

    return strtoupper($str);
}

function lowerTR($str) {

    if ($_SESSION['lang'] == 'tr') {

        $str = str_replace(array('İ', 'I', 'Ü', 'Ğ', 'Ş', 'Ö', 'Ç'), array('i', 'ı', 'ü', 'ğ', 'ş', 'ö', 'ç'), $str);
    }

    return strtolower($str);
}

function upperFirstTR($str, $splitWords=true) {

    $returnStr = '';

    if ($splitWords) {
        foreach (explode(' ', $str) as $word) {

            if (strlen($word) <= 3 && strpos($word, '&') !== false) {

                $returnStr .= $word . ' ';
            }
            else {

                $returnStr .= upperFirstTR($word, false) . ' ';
            }
        }

        return rtrim($returnStr, ' ');
    }

    return upperTR(substr($str, 0, 1)) . lowerTR(substr($str, 1));
}