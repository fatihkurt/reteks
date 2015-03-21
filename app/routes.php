<?php


$app->get('/', function() use($app) {

    $lang = $app->getLang;

    $app->response->redirect("/$lang", 303);
});


$app->get('(/:lang)', $initLanguage, '\App\Controller\IndexController:index');


$app->get('(/:lang)/:title', $initLanguage, function() use($app) {

    $app->render('content.twig');

})->name('content');


$app->get('(/:lang)/news/:title', $initLanguage, function() use($app) {

    $app->render('news.twig');

})->name('news');