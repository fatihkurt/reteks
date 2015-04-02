<?php
namespace App\Controller;

use App;

class adminController extends ControllerBase
{


    public function index()
    {

        if ($this->sessionGet(self::USER_LOGIN_KEY) == false) {

            $this->app->flash('error', 'Login olunuz.');

            $this->app->response->redirect('login');
        }

        $this->app->render('admin/index.twig',[


        ]);
    }


}