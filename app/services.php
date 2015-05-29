<?php

mb_internal_encoding("UTF-8");

$view = $app->view();

$view->parserOptions = array(
    'debug' => $app->config('debug'),
    'cache' => $app->config('cache.path') . '/twig'
);



$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
    new Twig_Extensions_Extension_Text(),
    new \Umpirsky\Twig\Extension\PhpFunctionExtension(['mb_strtoupper', 'mb_strtolower', 'ucfirst', 'substr', 'strpos', 'strip_tags', 'strpos', 'upperTR', 'lowerTR', 'upperFirstTR'])
);

session_cache_limiter(false);
session_start();

// $app->add(new \Slim\Middleware\SessionCookie(array(
//     'expires'   => '30 minutes',
//     'path'      => '/',
//     'domain'    => null,
//     'secure'    => false,
//     'httponly'  => false,
//     'name'      => 'slim_session',
//     'secret'    => 'J?6Y^NCvqEBgc\.)',
//     'cipher'    => MCRYPT_RIJNDAEL_256,
//     'cipher_mode'=> MCRYPT_MODE_CBC
// )));


// $app->error(function (\Exception $e) use ($app) {
//      var_dump($app);
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

    //var_dump($app->router->getCurrentRoute()->getParam('lang'));

    // @TODO cache results

    return parse_ini_string(file_get_contents(APP_DIR . "locale/$lang.ini"));
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

function readINIfile ($filename, $commentchar='#') {
    $array1 = file($filename);
    $section = '';
    for ($line_num = 0; $line_num <= sizeof($array1); $line_num++) {
        $filedata = $array1[$line_num];
        $dataline = trim($filedata);
        $firstchar = substr($dataline, 0, 1);
        if ($firstchar!=$commentchar && $dataline!='') {
            //It's an entry (not a comment and not a blank line)
            if ($firstchar == '[' && substr($dataline, -1, 1) == ']') {
                //It's a section
                $section = strtolower(substr($dataline, 1, -1));
            }else{
                //It's a key...
                $delimiter = strpos($dataline, '=');
                if ($delimiter > 0) {
                    //...with a value
                    $key = strtolower(trim(substr($dataline, 0, $delimiter)));
                    $array2[$section][$key] = '';
                    $value = trim(substr($dataline, $delimiter + 1));
                    while (substr($value, -1, 1) == '\\') {
                        //...value continues on the next line
                        $value = substr($value, 0, strlen($value)-1);
                        $array2[$section][$key] .= stripcslashes($value);
                        $line_num++;
                        $value = trim($array1[$line_num]);
                    }
                    $array2[$section][$key] .= stripcslashes($value);
                    $array2[$section][$key] = trim($array2[$section][$key]);
                    if (substr($array2[$section][$key], 0, 1) == '"' && substr($array2[$section][$key], -1, 1) == '"') {
                        $array2[$section][$key] = substr($array2[$section][$key], 1, -1);
                    }
                }else{
                    //...without a value
                    $array2[$section][strtolower(trim($dataline))]='';
                }
            }
        }else{
            //It's a comment or blank line.  Ignore.
        }
    }
    return $array2;
}