<?php
namespace App\Controller\Admin;

use App;

class PageController extends App\Controller\ControllerBase
{


    public function index()
    {


        $pages = \App\Model\Page::with('contents')->orderBy('ordernum')->get();

        $this->app->render('admin/page.twig',[

            'menu_item' => 'page',

            'pages' => $pages
        ]);
    }


}