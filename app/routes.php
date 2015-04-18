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
    $app->delete('/page/delete',  '\App\Controller\Admin\PageController:delete');


    $app->get('/news',  '\App\Controller\Admin\NewsController:index');
    $app->get('/news/new',  '\App\Controller\Admin\NewsController:create');
    $app->get('/news/:id',  '\App\Controller\Admin\NewsController:edit');

    $app->post('/news/save',  '\App\Controller\Admin\NewsController:save');
    $app->delete('/news/delete',  '\App\Controller\Admin\NewsController:delete');

    $app->get('/category',  '\App\Controller\Admin\PageCategoryController:index');
    $app->get('/category/new',  '\App\Controller\Admin\PageCategoryController:create');
    $app->get('/category/:id',  '\App\Controller\Admin\PageCategoryController:edit');

    $app->post('/category/save',  '\App\Controller\Admin\PageCategoryController:save');
    $app->delete('/category/delete',  '\App\Controller\Admin\PageCategoryController:delete');
});




//*********** Anasafa ************//

$app->get('/', function() use($app) {

    $lang = $app->getLang;

    $app->response->redirect("/$lang", 303);
});


$app->get('(/:lang)/', $initLanguage, '\App\Controller\IndexController:index')->name('index');


$app->get('(/:lang)/:title', $initLanguage, '\App\Controller\PageController:index')->name('page');
$app->get('(/:lang)/:title/:title2', $initLanguage, '\App\Controller\PageController:index')->name('page');



//*********** Ajax Requests ************//

$app->post('/contact/form', '\App\Controller\ContactController:save');

$app->post('/newsletter/save',  '\App\Controller\IndexController:newsletterSave');








