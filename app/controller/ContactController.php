<?php
namespace App\Controller;


use App,
    App\Model\Contact;

class ContactController extends ControllerBase
{

    public function form($lang, $seoUrl, $page) {

        $this->app->render('contact_form.twig', [

            'item'      => $page,
            'cpages'    => $page->page->category->pages,
            'breadjump' => [
                ['name' => $page->page->category->getName($this->lang), 'link' => ""],
                ['name' => $page->title, 'link' => "/$this->lang/$page->seo_url"]
            ]
        ]);
    }


    public function info($lang, $seoUrl, $page) {

        $this->app->render('contact_info.twig', [

            'item'      => $page,
            'cpages'    => $page->page->category->pages,
            'breadjump' => [
                ['name' => $page->page->category->getName($this->lang), 'link' => ""],
                ['name' => $page->title, 'link' => "/$this->lang/$page->seo_url"]
            ]
        ]);
    }
}