<?php
namespace App\Controller;


use App,
    App\Model\Page,
    App\Model\CareerPosition as Position,
    App\Model\CareerApplication as Application,
    App\Model\CareerApplicationLanguage as ApplicationLanguage,
    App\Model\CareerApplicationEducation as ApplicationEducation,
    App\Model\ListLanguage,
    App\Model\ListLanguageLevel;

class CareerController extends ControllerBase
{

    use App\Plugin\AjaxResponse;


    public function position($lang, $seoUrl, $page) {

        $category = $page->page->category;

        $this->app->render('career_position.twig', [
            'menu_id'   => $category->id,
            'item'      => $page,
            'cpages'    => $category->pages,

            'positions' => Position::all(),

            'applicarion_url' => Page::where('module', '=', 'Career:application')->first()->content($this->lang)->seo_url,

            'breadjump' => [
                ['name' => $category->getName($this->lang), 'link' => ""],
                ['name' => $page->title, 'link' => "/$this->lang/$page->seo_url"]
            ],
            'footer_js' => ['application.js'],
        ]);
    }


    public function application($lang, $seoUrl, $page) {

        $category = $page->page->category;

        $this->app->render('career_application.twig', [
            'menu_id'   => $category->id,
            'item'      => $page,
            'cpages'    => $category->pages,

            'posId'     => $this->app->request->get('is'),

            'positions' => Position::all(),

            'languages' => ListLanguage::whereRaw('code NOT IN ("en","de","ru")')->orderBy("name_$this->lang")->get(),

            'language_levels' => ListLanguageLevel::orderBy("level")->get(),

            'default_languages' => [
                ['code' => 'en', 'title' => $this->app->t['career_engilish']],
                ['code' => 'de', 'title' => $this->app->t['career_german']],
                ['code' => 'ru', 'title' => $this->app->t['career_russian']],
            ],

            'breadjump' => [
                ['name' => $category->getName($this->lang), 'link' => ""],
                ['name' => $page->title, 'link' => "/$this->lang/$page->seo_url"]
            ],
            'footer_js' => ['main.js', 'vendor/jquery/jquery.form.min.js', 'application.js'],
        ]);
    }



    public function save() {

        $data = $this->app->request->post();


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
            $success = false;

            $feedback = $application->errors();

            $this->msg = $this->app->t['validation.form.message'];
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

            if ($data['school'] != '') {

                $education = new ApplicationEducation;

                $data['application_id'] = $applicationId;

                $education->bind($data);

                $education->save();
            }
        }
    }
}