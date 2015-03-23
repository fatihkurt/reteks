<?php
namespace App\Controller;


class AuthController extends ControllerBase
{

    public function login()
    {
        $this->app->render('login/login.twig',[


        ]);
    }

    public function postLogin()
    {
        // authentication & redirect
    }

    public function logout()
    {
        // logout functionality
    }
}