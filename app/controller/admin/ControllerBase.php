<?php
namespace App\Controller\Admin;

use App;

class ControllerBase extends App\Controller\ControllerBase
{

    use App\Plugin\AppHelper;

    public function __construct()
    {

        parent::__construct();

        if ($this->sessionGet(self::USER_LOGIN_KEY) == false) {

            $this->app->flash('error', 'Login olunuz.');

            $this->sessionSet('return_url', $_SERVER['REQUEST_URI']);

            $this->app->response->redirect('/login');
        }
        elseif ($url = $this->sessionGet('return_url')) {

            $this->sessionDestroy('return_url');

            $this->app->response->redirect($url);
        }
    }
}