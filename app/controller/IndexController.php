<?php
namespace App\Controller;


use App,
    App\Model\News,
    App\Model\Page,
    App\Model\PageTranslation,
    App\Model\Setting;

class IndexController extends ControllerBase
{

    public function index($lang='tr') {


        $this->app->render('index.twig', [

            'news'  => $this->getNews(),
            'pages' => $this->getPages(),
            'footer_js' => ['main.js'],
        ]);
    }


    private function getPages() {

        $pageIds = explode(',', Setting::find(1)->value);

        $pages = [];

        for ($i=0; $i<5; $i++) {

            //$pages[] = PageTranslation::where('lang', '=', $this->lang)->where('page_id', '=', $pageIds[$i])->first();
            $pages[] = Page::find($pageIds[$i]);
        }

        return $pages;
    }



    private function getNews() {

        $news = News::with('contents')
        ->orderBy('start_date', 'desc')
        ->limit(5)
        ->get();


        foreach ($news as &$item) {

            foreach ($item->contents as $content) {

                if ($content->lang == $this->lang) {

                    $content->content = strip_tags(html_entity_decode(substr($content->content, 0, 140))) . '...';

                    $item->content = $content;
                    unset($item->contents);
                    break;
                }
            }
        }

        return $news;
    }
}