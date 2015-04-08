<?php
namespace App\Controller;


use App,
    App\Model\PageTranslation;

class PageController extends ControllerBase
{

    public function index($lang, $seoUrl) {

        $page = PageTranslation::with('page')
                    ->where('lang', '=', $lang)
                    ->where('seo_url', '=', $seoUrl)
                    ->first();

        if ($page->page->status == 0) {

            return $this->app->notFound();
        }

        $category = $page->page->category;


        $breadjump[] = ['name' => $category->name, 'url' => $category->defaultPage($lang)->seo_url];

        if ($page->page_id != $category->default_page_id) {

            $breadjump[] = ['name' => $page->title, 'url' => $page->seo_url];
        }


        $this->app->render('/page.twig', [

            'item'  => $page,
            'category' => $category,
            'breadjump' => $breadjump
        ]);
    }
}