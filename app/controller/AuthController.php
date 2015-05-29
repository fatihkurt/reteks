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


        $password = $this->generateSecurePassword($password);

        if ($this->captchaCheck($captchaResponse) == false) {

            return $this->falseInput('Captcha girişini tekrar deneyiniz');
        }
        else
        if ($user == null || $password != $user['password']) {

            return $this->falseInput('Lütfen bilgileri kontrol ediniz.', ['f' => true]);
        }
        else {

            $this->sessionSet(self::USER_LOGIN_KEY, $user['id']);

            $this->msg = 'Sayın ' . $user['name'] . ', yönetim sayfasına yönlendiriliyorsunuz..';

            return $this->jsonResponse(true, $user['name']);
        }
    }


    public function generateForgotLink() {

        $username = $this->app->request->post('username');

        if ($username == false) {

            return $this->falseInput();
        }

        $user = \App\Model\User::select('id','password','username','name')
            ->where('username', '=', $username)
            ->take(1)
            ->first();

        if ($user == false) {

            return $this->falseInput();
        }
        else {

            $uid = $user['id'];

            $hash = $this->getForgotAuthKey($user);

            $link = "http://" . $_SERVER['SERVER_NAME'] .  "/login/forgot?uid=$uid&auth=$hash";

            $message = 'Şifreyi yeniden almak için <a href="' . $link . '">tıklayınız</a>';

            mail($user, 'Re-teks Admin Şifre Yenileme', $message);

            $this->msg = 'Sayın ' . $user['name'] . ', e-posta adresinize gelen bağlantıyla şifrenizi yenileyebilirsiniz..';

            return $this->jsonResponse(true, $link);
        }
    }


    public function forgot() {

        if ($this->app->request->isPost()) {


            $uid = $this->app->request->post('uid');
            $key = $this->app->request->post('auth');

            $pass1 = $this->app->request->post('password');
            $pass2 = $this->app->request->post('password_repeat');


            $user = $this->checkUserByAuthKey($uid, $key);

            if ($user === false) {

                return $this->falseInput();
            }


            if ($pass1 != $pass2) {

                return $this->falseInput('Şifreler uyuşmuyor. Lütfen kontrol ediniz..');
            }

            if (strlen($pass1) < 5) {

                return $this->falseInput('Lütfen en az 5 karakter giriniz..');
            }

            $user->password = $this->generateSecurePassword($pass1);


            return $this->jsonResponse($user->save());
        }
        else {

            $uid = $this->app->request->get('uid');
            $key = $this->app->request->get('auth');


            if ($this->checkUserByAuthKey($uid, $key) === false) {

                $this->app->flash('error', 'Geçersiz deneme');

                $this->app->redirect('/');

                return false;
            }


            $this->app->render('admin/login_forgot.twig', [
                'uid' => $uid,
                'auth'=> $key,
            ]);
        }
    }


    private function checkUserByAuthKey($uid, $key) {

        $user = \App\Model\User::find($uid, ['id','username']);

        if ($user == false) {

            return false;
        }

        $authKey = $this->getForgotAuthKey($user->toArray());

        if ($key != $authKey) {

            return false;
        }

        return $user;
    }


    private function getForgotAuthKey($user) {

        return hash('sha256', $user['id'] . self::PASS_KEY . $user['username']);
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

    private function falseInput($msg='Lütfen bilgileri kontrol ediniz.', $data=null) {

        $this->msg = $msg;

        return $this->jsonResponse(false, $data);
    }


    private function generateSecurePassword($password) {

        return hash('sha256', self::PASS_KEY . $password);
    }

    public function logout()
    {
        $this->sessionDestroy(self::USER_LOGIN_KEY);

        $this->app->response->redirect('/login');
    }
}