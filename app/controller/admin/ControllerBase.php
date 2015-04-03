<?php
namespace App\Controller\Admin;

use App;

class ControllerBase extends App\Controller\ControllerBase
{


    public function __construct()
    {

        parent::__construct();

        if ($this->sessionGet(self::USER_LOGIN_KEY) == false) {

            $this->app->flash('error', 'Login olunuz.');

            $this->app->response->redirect('/login');
        }
    }
}