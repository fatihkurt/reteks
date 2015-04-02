<?php
namespace App\Controller;

use App;

class AuthController extends ControllerBase
{

    use App\Plugin\AjaxResponse;


    const PASS_KEY = 'bXX;628<<pD[bfFZ';

    public function login()
    {

        if ($this->sessionGet(self::USER_LOGIN_KEY) != false) {

            $this->app->response->redirect('admin');
        }

        $this->app->render('login/login.twig');
    }


    public function auth()
    {

        $username = $this->app->request->post('username');
        $password = $this->app->request->post('password');

        if ($username == false || $password == false) {

            return $this->falseInput();
        }

        $user = \App\Model\User::select('id','password','name')
            ->where('username', '=', $username)
            ->take(1)
            ->first();


        $password = hash('sha256', self::PASS_KEY . $password);

        if ($user == null || $password != $user['password']) {

            return $this->falseInput();
        }
        else {

            $this->sessionSet(self::USER_LOGIN_KEY, $user['id']);

            $this->msg = 'Sayın ' . $user['name'] . ', yönetim sayfasına yönlendiriliyorsunuz..';

            return $this->jsonResponse(true, $user['name']);
        }
    }

    private function falseInput() {

        $this->msg = 'Lütfen bilgileri kontrol ediniz.';

        return $this->jsonResponse(false);
    }

    public function logout()
    {
        $this->sessionDestroy(self::USER_LOGIN_KEY);

        $this->app->response->redirect('/login');
    }
}