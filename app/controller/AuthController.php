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

        $this->app->render('admin/login.twig');
    }


    public function auth()
    {

        $username = $this->app->request->post('username');
        $password = $this->app->request->post('password');
        $captchaResponse = $this->app->request->post('g-recaptcha-response');

        if ($username == false || $password == false) {

            return $this->falseInput();
        }

        $user = \App\Model\User::select('id','password','name')
            ->where('username', '=', $username)
            ->take(1)
            ->first();


        $password = hash('sha256', self::PASS_KEY . $password);

        if ($this->captchaCheck($captchaResponse) == false) {

            return $this->falseInput('Captcha girişini tekrar deneyiniz');
        }
        else
        if ($user == null || $password != $user['password']) {

            return $this->falseInput();
        }
        else {

            $this->sessionSet(self::USER_LOGIN_KEY, $user['id']);

            $this->msg = 'Sayın ' . $user['name'] . ', yönetim sayfasına yönlendiriliyorsunuz..';

            return $this->jsonResponse(true, $user['name']);
        }
    }


    private function captchaCheck($response) {

        if ($this->app->config('mode') == 'development' || $this->app->config('mode') == 'aws')
            return  true;

        if ($response == false)
            return false;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "secret=" . $this->app->config('recaptcha_secret') . "&response=$response");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $jsonResult = curl_exec ($ch);

        curl_close ($ch);

        if ($jsonResult == false)
            return false;

        $result = json_decode($jsonResult);

        return isset($result->success) && $result->success;
    }

    private function falseInput($msg='Lütfen bilgileri kontrol ediniz.') {

        $this->msg = $msg;

        return $this->jsonResponse(false);
    }

    public function logout()
    {
        $this->sessionDestroy(self::USER_LOGIN_KEY);

        $this->app->response->redirect('/login');
    }
}