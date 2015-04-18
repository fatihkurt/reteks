<?php
namespace App\Controller;


use App,
    App\Model\Page,
    App\Model\PageCategory,
    App\Model\PageTranslation,
    App\Controller\NewsController,
    App\Controller\ContactController;

class PageController extends ControllerBase
{

    public function index($lang, $seoUrl, $seoUrl2='') {

        $page = PageTranslation::with(['page' => function($query) {
                        $query->with(['gallery' => function($query) {
                            $query->orderBy('ordernum');
                        }]);
                    }])
                    ->where('lang', '=', $lang)
                    ->where('seo_url', '=', $seoUrl)
                    ->first();


        if ($page == null || $page->page->status == 0) {

            return $this->app->notFound();
        }

        if ($page->page->module != '') {

            $moduleD = explode(':', $page->page->module);

            $moduleName = 'App\Controller\\' . $moduleD[0] . 'Controller';
            $actionName = isset($moduleD[1]) ? $moduleD[1] : 'index';

            if ($page->page->module == 'News') {

                $controller = new NewsController;

                if ($seoUrl2 != '') {
                    return $controller->detail($lang, "$seoUrl/$seoUrl2", $page);
                }
                else {
                    return $controller->index($lang, $page);
                }
            }
            else {

                $e = 'ContactController';

                $controller = new $moduleName;

                return $controller->{$actionName}($lang, $seoUrl2, $page);
            }
        }

        $category = $page->page->category;

        $cpages = Page::with(['contents' => function($query) use($lang) {

                        $query->where('lang', '=', $lang);
                    }])
                    ->where('category_id', '=', $category->id)
                    ->orderBy('ordernum')
                    ->get();

        $this->app->render('/page.twig', [
            'seo_desc'  => $page->description,
            'seo_title' => $page->title,
            'item'      => $page,
            'category'  => $category,
            'cpages'    => $cpages,
            'breadjump' => [
                ['name' => $category->{"name_$this->lang"}, 'link' => ''],
                ['name' => $page->title, 'link' => "/$this->lang/" . $page->seo_url]
            ]
        ]);
    }
}