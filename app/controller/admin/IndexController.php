<?php
namespace App\Controller\Admin;

use App;

class IndexController extends App\Controller\Admin\ControllerBase
{


    public function index()
    {


        $this->app->render('admin/index.twig',[

            'menu_item' => ''
        ]);
    }


}