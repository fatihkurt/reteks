<?php



//************ Login ************//

$app->get('/login',  '\App\Controller\AuthController:login')->name('login');
$app->post('/login', '\App\Controller\AuthController:auth');
$app->get('/logout', '\App\Controller\AuthController:logout')->name('logout');




//*********** Admin ************//

$app->get('/admin',  '\App\Controller\AdminController:index')->name('admin');



//*********** Anasafa ************//

$app->get('/', function() use($app) {

    $lang = $app->getLang;

    $app->response->redirect("/$lang", 303);
});


$app->get('(/:lang)', $initLanguage, '\App\Controller\IndexController:index')->name('index');


$app->get('(/:lang)/:title', $initLanguage, function() use($app) {

    $app->render('content.twig');

})->name('content');


$app->get('(/:lang)/news/:title', $initLanguage, function() use($app) {

    $app->render('news.twig');

})->name('news');





