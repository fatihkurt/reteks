<?php
namespace App\Controller;


class ControllerBase
{
    protected $app;
    protected $lang;


    public function __construct() {

        // Get reference to application
        $this->app = \Slim\Slim::getInstance();

        $this->lang = $this->app->getLang;
    }
}