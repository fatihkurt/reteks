<?php
namespace App\Controller;


use App,
    App\Model\CareerPosition as Position,
    App\Model\CareerApplication as Application,
    App\Model\CareerApplicationLanguage,
    App\Model\CareerApplicationEducation;

class CareerController extends ControllerBase
{

    use App\Plugin\AjaxResponse;


    public function application($lang, $seoUrl, $page) {

        $category = $page->page->category;

        $this->app->render('career_application.twig', [
            'menu_id'   => $category->id,
            'item'      => $page,
            'cpages'    => $category->pages,

            'positions' => Position::all(),

            'breadjump' => [
                ['name' => $category->getName($this->lang), 'link' => ""],
                ['name' => $page->title, 'link' => "/$this->lang/$page->seo_url"]
            ],
            'footer_js' => ['main.js', 'vendor/jquery/jquery.form.min.js', 'application.js'],
        ]);
    }



    public function save() {

        $data = $this->app->request->post();



        try {

            $application = new Application;

            foreach ($data as $key=>$val) {

                if (! is_array($val))
                $application->{$key} = $val;
            }

            $application->sess_id = session_id();
            $application->ip_addr = $this->app->request->getIp();

            $success = $application->save(['data' => $data]);
        }
        catch (\Exception $e) {

            $success = false;

            $this->msg = $e->getMessage();
        }


        if ($success == false) {

            $feedback = $application->errors();
        }
        else {
            $feedback = $application->id;
        }

        $this->jsonResponse($success, $feedback);
    }
}