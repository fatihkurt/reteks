<?php
namespace App\Controller;


use App,
    App\Plugin\AjaxResponse,
    App\Model\News,
    App\Model\Page,
    App\Model\PageCategory,
    App\Model\PageTranslation;

class SearchController extends ControllerBase
{

    use AjaxResponse;

    public function index($query) {

        $query = html_entity_decode($query);

        $results = PageTranslation
                        ::where('lang', '=', $this->lang)
                        ->where('title', 'LIKE', "%$query%")
                        ->get(['seo_url AS url', 'title'])
                        ->toArray();

        //$results= [];

        if ($category = PageCategory::where("name_$this->lang", 'LIKE', "%$query%")->first()) {

            $page = $category->defaultPage($this->lang);

            $results[] = ['url' => $page->seo_url, 'title' => $category->getName($this->lang)];
        }

        $this->jsonResponse(true, $results);
    }
}