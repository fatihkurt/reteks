<?php



//************ Login ************//

$app->get('/login',  '\App\Controller\AuthController:login')->name('login');

$app->post('/login', '\App\Controller\AuthController:auth');

$app->get('/logout', '\App\Controller\AuthController:logout')->name('logout');




//*********** Admin ************//

$app->group('/admin', function () use ($app) {


    $app->get('/',  '\App\Controller\Admin\IndexController:index')->name('admin');

    $app->get('/page',  '\App\Controller\Admin\PageController:index');
    $app->get('/page/new',  '\App\Controller\Admin\PageController:create');
    $app->get('/page/:id',  '\App\Controller\Admin\PageController:edit');

    $app->post('/page/save',  '\App\Controller\Admin\PageController:save');

});




//*********** Anasafa ************//

$app->get('/', function() use($app) {

    $lang = $app->getLang;

    $app->response->redirect("/$lang", 303);
});


$app->get('(/:lang)/', $initLanguage, '\App\Controller\IndexController:index')->name('index');


$app->get('(/:lang)/:title', $initLanguage, '\App\Controller\PageController:index')->name('page');


$app->get('(/:lang)/news/:title', $initLanguage, function() use($app) {

    $app->render('news.twig');

})->name('news');





