<?php
namespace App\Controller;


use App,
    App\Model\CareerApplication;

class CareerController extends ControllerBase
{

    use App\Plugin\AjaxResponse;


    public function application($lang, $seoUrl, $page) {

        $this->app->render('career_application.twig', [

            'item'      => $page,
            'cpages'    => $page->page->category->pages,
            'breadjump' => [
                ['name' => $page->page->category->getName($this->lang), 'link' => ""],
                ['name' => $page->title, 'link' => "/$this->lang/$page->seo_url"]
            ],
            'footer_js' => ['main.js'],
        ]);
    }


    public function info($lang, $seoUrl, $page) {

        $this->app->render('contact_info.twig', [
            'menu_id'   => 6,
            'item'      => $page,
            'cpages'    => $page->page->category->pages,
            'breadjump' => [
                ['name' => $page->page->category->getName($this->lang), 'link' => ""],
                ['name' => $page->title, 'link' => "/$this->lang/$page->seo_url"]
            ]
        ]);
    }


    public function save() {

        $data = $this->app->request->post();

        $required = ['name', 'gsm', 'email', 'message'];

        $pass = true;

        foreach ($required as $field) {

            if (empty($data[$field])) {

                $pass = false;
            }
        }

        if ($pass == false) {

            $this->msg = $this->app->getLang == 'tr' ? 'Lütfen zorunlu alanları doldurunuz.' : 'Please fill required fields.' ;

            return $this->jsonResponse(false);
        }


        try {
            $this->msg = $this->app->getLang == 'tr'
                    ? 'Mesajınız başarıyla gönderilmiştir.'
                    : 'Your message has been send successfully.';

            $contact = new ContactForm;

            $contact->name = $data['name'];
            $contact->gsm = $data['gsm'];
            $contact->email = $data['email'];
            $contact->subject = $data['subject'];
            $contact->message = $data['message'];
            $contact->sess_id = session_id();
            $contact->ip_addr = $this->app->request->getIp();

            $success = $contact->save($data);
        }
        catch (\Exception $e) {

            $success = false;

            $this->msg = $e->getMessage();
        }

        $this->jsonResponse($success);
    }
}