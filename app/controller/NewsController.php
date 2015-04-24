<?php
namespace App\Controller;


use App,
    App\Model\News,
    App\Model\NewsTranslation;

class NewsController extends ControllerBase
{

    public function index($lang, $page) {

        $news = News::with('contents')
                    ->orderBy('start_date', 'desc')
                    ->get();


        foreach ($news as &$item) {

            foreach ($item->contents as $content) {

                if ($content->lang == $this->lang) {
                    $item->content = $content;
                    unset($item->contents);
                    break;
                }
            }
        }

        $this->app->render('news.twig', [

            'menu_id'   => $page->page->category_id,
            'items'     => $news,
            'content'   => $page,
            'breadjump' => [['name' => $page->title, 'link' => "/$this->lang/$page->seo_url"]]
        ]);
    }


    public function detail($lang, $seoUrl, $page) {

        $news = NewsTranslation::with('news')
                    ->where('lang', '=', $this->lang)
                    ->where('seo_url', '=', $seoUrl)
                    ->first();

        if (strtotime($news->news->end_date) < time() || strtotime($news->news->start_date) > time()) {

            return $this->app->notFound();
        }

        $this->app->render('news_detail.twig', [
            'menu_id'   => $page->page->category_id,
            'item'      => $news,
            'content'   => $page,
            'breadjump' => [['name' => $page->title, 'link' => "/$this->lang/$page->seo_url"]]
        ]);
    }
}