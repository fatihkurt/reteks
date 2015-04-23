<?php
namespace App\Controller;


use App,
    App\Model\CareerPosition as Position,
    App\Model\CareerApplication as Application,
    App\Model\CareerApplicationLanguage as ApplicationLanguage,
    App\Model\CareerApplicationEducation as ApplicationEducation,
    App\Model\ListLanguage;

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

        $success = false;

        try {

            $application = new Application;

            $data['birthdate'] = $data['birthdate_year'] . '-' . $data['birthdate_month'] . '-' . $data['birthdate_day'];

            $application->bind($data);

            $application->sess_id = session_id();
            $application->ip_addr = $this->app->request->getIp();

            if ($application->validate($data)) {

                $success = $application->save();

                $feedback = $application->id;

                $this->saveLanguage($application->id, $data['language']);

                $this->saveEducation($application->id, $data['education']);

                $this->msg = $this->app->t['validation.form.success'];
            }
            else {
                $feedback = $application->errors();

                $this->msg = $this->app->t['validation.form.message'];
            }
        }
        catch (\Exception $e) {

            $this->msg = $e->getMessage();

            $feedback = null;
        }


        $this->jsonResponse($success, $feedback);
    }


    private function saveLanguage($applicationId, $languageArr) {

        foreach ($languageArr as $code => $lang) {

            if (! is_numeric($code)) {

                $lang['language_id'] = ListLanguage::where('code', '=', $code)->first()->id;
            }

            if ($lang['language_id'] > 0) {

                $language = new ApplicationLanguage;

                $lang['application_id'] = $applicationId;

                $language->bind($lang);

                $language->save();
            }
        }
    }

    private function saveEducation($applicationId, $educationArr) {

        foreach ($educationArr as $level => $data) {

            if ($data['level'] > 0) {

                $education = new ApplicationEducation;

                $lang['application_id'] = $applicationId;

                $education->bind($data);

                $education->save();
            }
        }
    }
}