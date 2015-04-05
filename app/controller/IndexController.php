<?php
namespace App\Controller;


class IndexController extends ControllerBase
{

    public function index($lang='tr') {

//         $news = \App\Model\News::find(1)
//                     ->select('id')
//                     ->orderBy('start_date', 'desc')
//                     ->with(['contents' =>function($q)  {

//                         $q->select('description', 'title', 'seo_url');

//                         $q->where('lang', '=', $this->lang);
//                     }])
//                     ->take(5)
//                     ->get();

        $news = [];

        $this->app->render('index.twig', [

            'news'  => $news,
        ]);
    }
}