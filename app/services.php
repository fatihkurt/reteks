<?php

mb_internal_encoding("UTF-8");

$view = $app->view();

$view->parserOptions = array(
    'debug' => $app->config('debug'),
    'cache' => $app->config('cache.path') . '/twig'
);

$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
    new \Umpirsky\Twig\Extension\PhpFunctionExtension(['mb_strtoupper', 'mb_strtolower', 'ucfirst', 'substr', 'strpos', 'strip_tags', 'strpos'])
);






$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires'   => '30 minutes',
    'path'      => '/',
    'domain'    => null,
    'secure'    => false,
    'httponly'  => false,
    'name'      => 'slim_session',
    'secret'    => 'J?6Y^NCvqEBgc\.)',
    'cipher'    => MCRYPT_RIJNDAEL_256,
    'cipher_mode'=> MCRYPT_MODE_CBC
)));


// $app->error(function (\Exception $e) use ($app) {

//     //var_dump($app);
// });


$app->notFound(function () use ($app) {
    $app->render('404.twig', [
       'footer_js' => ['notfound.js']
    ]);
});


$app->getLang = function() use($app){

    return isset($_SESSION['lang']) ? $_SESSION['lang'] : $app->config('languages.default');
};


$app->container->singleton('t', function() use ($app) {

    $lang = $app->getLang;

    // @TODO cache results

    return parse_ini_file(APP_DIR . "locale/$lang.ini");
});


$app->container->singleton('db', function() use ($app) {

    //initialize orm

    $capsule = new \Illuminate\Database\Capsule\Manager;

    $dbConf = $app->config('database');

    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => $dbConf['host'],
        'database'  => $dbConf['name'],
        'username'  => $dbConf['user'],
        'password'  => $dbConf['pass'],
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);

    // Make this Capsule instance available globally via static methods... (optional)
    $capsule->setAsGlobal();

    // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
    $capsule->bootEloquent();

    //$capsule->setFetchMode(PDO::FETCH_OBJ);

    return $capsule->getConnection();
});


$app->db->statement("SET NAMES utf8");

// $app->contentType('text/html; charset=utf-8');
// $app->response()->header('Content-Type', 'text/html;charset=utf-8');

header('Content-type: text/html; charset=utf-8');

