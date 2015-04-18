<?php
namespace App\Controller;


use App,
    App\Plugin\AjaxResponse,
    App\Model\News,
    App\Model\Page,
    App\Model\PageTranslation,
    App\Model\Newsletter,
    App\Model\Setting;

class IndexController extends ControllerBase
{

    use AjaxResponse;

    public function index($lang='tr') {


        $this->app->render('index.twig', [

            'news'  => $this->getNews(),
            'pages' => $this->getPages(),
            'thePage' => $this->getCompanyPage(),
        ]);
    }


    public function newsletterSave() {

        $email = $this->app->request->post('email');

        if ($email == '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {

            $success = false;

            $this->msg = $this->app->t['invalid_email'];
        }
        else {
            $newsletter = Newsletter::firstOrCreate(['email' => $email]);

            $newsletter->email   = $email;
            $newsletter->sess_id = session_id();
            $newsletter->ip_addr = $this->app->request->getIp();

            $success = $newsletter->save();

            $this->msg = $this->app->t['ebulten_message'];
        }

        $this->jsonResponse($success);
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

    private function getCompanyPage() {

        $pageId = Setting::find(2)->value;

        return  Page::find($pageId);
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