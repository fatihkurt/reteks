<?php
namespace App\Controller;


use App,
    App\Model\PageTranslation;

class PageController extends ControllerBase
{

    public function index($lang, $seoUrl) {

        $page = PageTranslation::
                    select('id', 'title', 'description', 'content')
                    ->where('lang', '=', $lang)
                    ->where('seo_url', '=', $seoUrl)
                    //->take(1)
                    ->first();

        $this->app->render('/page/index.twig', [

            'page'  => $page,
        ]);
    }
}