<?php
namespace App\Controller;


abstract class ControllerBase
{
    protected $app;
    protected $lang;

    CONST USER_LOGIN_KEY = 'lU_743';


    public function __construct() {

        // Get reference to application
        $this->app = \Slim\Slim::getInstance();

        $this->lang = $this->app->getLang;
    }

    protected function sessionGet($key) {

        return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
    }

    protected function sessionSet($key, $val) {

        $_SESSION[$key] = $val;
    }

    protected function sessionDestroy($key) {

        unset($_SESSION[$key]);
    }
}