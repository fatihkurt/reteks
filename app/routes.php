<?php


$app->get('/', $initLanguage, function() use($app) {


    $app->response->redirect('/foo', 303);
});


$app->get('(/:lang)', $initLanguage, function() use($app) {

    $app->render('index.twig');
});