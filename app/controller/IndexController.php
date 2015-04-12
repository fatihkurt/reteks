<?php
namespace App\Controller;


use App,
    App\Model\News;

class IndexController extends ControllerBase
{

    public function index($lang='tr') {

        $news = News::with('contents')
                    ->orderBy('start_date', 'desc')
                    ->limit(5)
                    ->get();


        foreach ($news as &$item) {

            foreach ($item->contents as $content) {

                if ($content->lang == $this->lang) {

                    $content->content = substr($content->content, 0, 140) . '...';

                    $item->content = $content;
                    unset($item->contents);
                    break;
                }
            }
        }


        $this->app->render('index.twig', [

            'news'  => $news,
            'footer_js' => ['main.js'],
        ]);
    }
}