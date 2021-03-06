<?php
namespace App\Controller;


use App,
    App\Model\ContactForm;

class ContactController extends ControllerBase
{

    use App\Plugin\AjaxResponse;

    public function form($lang, $seoUrl, $page) {

        $category = $page->page->category;

        $this->app->render('contact_form.twig', [
            'menu_id'   => $category->id,
            'item'      => $page,
            'category'  => $category,
            'cpages'    => $category->pages,
            'breadjump' => [
                ['name' => $page->page->category->getName($this->lang), 'link' => ""],
                ['name' => $page->title, 'link' => "/$this->lang/$page->seo_url"]
            ],
        ]);
    }


    public function info($lang, $seoUrl, $page) {

        $category = $page->page->category;

        $this->app->render('contact_info.twig', [
            'menu_id'   => $category->id,
            'item'      => $page,
            'category'  => $category,
            'cpages'    => $category->pages,
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

            $this->msg = $this->app->t['contact_required'];

            return $this->jsonResponse(false);
        }


        try {
            $this->msg = $this->app->t['contact_success'];

            $contact = new ContactForm;

            $contact->name    = $data['name'];
            $contact->gsm     = $data['gsm'];
            $contact->email   = $data['email'];
            $contact->subject = $data['subject'];
            $contact->message = $data['message'];
            $contact->sess_id = session_id();
            $contact->ip_addr = $this->app->request->getIp();

            if ($success = $contact->save($data)) {

                $this->sendMail($contact);
            }

        }
        catch (\Exception $e) {

            $success = false;

            $this->msg = $e->getMessage();
        }

        $this->jsonResponse($success);
    }

    private function sendMail(& $contact) {

        $to = 'ik@regrup.com.tr';

        $subject = 'R&T Teks İletişim Formu >> ' . $contact->name . ' iletişim Talebi';

        $message = "
            İsim : $contact->name <br><br>
            Gsm : $contact->gsm <br><br>
            E-posta : $contact->email <br><br>
            Mesaj Konusu: $contact->subject <br><br>
            Mesaj : <br><br>$contact->message";

        @mail($to, $subject, $message);
    }
}