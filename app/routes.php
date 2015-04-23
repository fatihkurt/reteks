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


    $app->get('/application',  '\App\Controller\Admin\CareerController:index');
    $app->get('/application/form/:id',  '\App\Controller\Admin\CareerController:form');

    $app->get('/application/position',  '\App\Controller\Admin\CareerController:position');
    $app->get('/application/position/edit/:id',  '\App\Controller\Admin\CareerController:positionEdit');
    $app->get('/application/position/new',  '\App\Controller\Admin\CareerController:positionNew');

    $app->post('/application/position/save',  '\App\Controller\Admin\CareerController:positionSave');
    $app->delete('/application/position/delete',  '\App\Controller\Admin\CareerController:positionDelete');
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

$app->post('/search(/:query)',  '\App\Controller\SearchController:index');

$app->post('/application/save',  '\App\Controller\CareerController:save');








