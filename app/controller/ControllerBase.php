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
    }


    /*
     * @params $step has name,url keys
     *
     */
    protected function breadjump(array $steps) {

        $breadJumpHtml = $this->breadJumpStepHtml([
            'url' => '/' . $this->lang,
            'name'=> 'Anasayfa'
        ]);

        foreach ($steps as $step) {

            $breadJumpHtml .= self::BREADJUMP_SEP . $this->breadJumpStepHtml($step);
        }

        return $breadJumpHtml;
    }


    private function breadJumpStepHtml(array $step) {

        return '<a href="' . $step['url'] . '">' . $step['name'] . '</a>';
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