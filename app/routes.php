<?php


$app->get('/', function() use($app) {

    $app->render('index.twig', array('title' => 'Sahara'));
});