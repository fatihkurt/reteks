<?php
namespace App\Controller;


abstract class ControllerBase
{
    protected $app;
    protected $lang;

    CONST USER_LOGIN_KEY = 'lU_743';

    CONST BREADJUMP_SEP = ' > ';


    public function __construct() {

        // Get reference to application
        $this->app = \Slim\Slim::getInstance();

        $this->lang = $this->app->getLang;

        $this->app->view->setData('lang', $this->lang);
        $this->app->view->setData('menu', $this->getMenuCategories());
    }


    function getMenuCategories() {

        $menus = [];

        foreach (\App\Model\PageCategory::orderBy('ordernum')->get() as $cat) {

            $menus[] = [
                'name' => $cat->{"name_$this->lang"},
                'link' => '/' . $this->lang . '/' . $cat->defaultPage($this->lang)->seo_url
            ];
        }

        return $menus;
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