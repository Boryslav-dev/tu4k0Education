<?php

namespace src\controllers;

use Framework\Arr\Arr;
use Framework\MVC\BaseController;
use Framework\Response\Response;
use Framework\Session\Session;
use src\models\Users;

class LoginController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function auth(): void
    {
        if ($this->request->getMethod() === 'POST') {
            $authData = $this->request->getAllParams();
            if (
                $authData['password'] === $authData['passwordRepeat']
                && $authData['password'] != ''
                && $authData['login'] != ''
                && $authData['name'] != ''
                && $authData['email'] != ''
            ) {
                $user = new Users();
                $user->loadData($authData);
                if ($user->insert()) {
                    self::render('/home/tu4k0/PhpstormProjects/tu4k0Education/' .
                    'src/views/templates/authorization/login.php');
                }
            } else {
                self::render('/home/tu4k0/PhpstormProjects/tu4k0Education/' .
                'src/views/templates/authorization/register.php');
            }
        } elseif ($this->request->getMethod() === 'GET') {
            self::render('/home/tu4k0/PhpstormProjects/tu4k0Education/' .
                'src/views/templates/authorization/register.php');
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function home(): void
    {
        self::render('/home/tu4k0/PhpstormProjects/tu4k0Education/src/views/layouts/app.php');
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function login(): void
    {
        if ($this->request->getMethod() === 'POST') {
            $authData = $this->request->getAllParams();
            $login = $authData['login'];
            $password = $authData['password'];
            $user = Users::getUser($login, $password);
            if (Users::validateUser($user)) {
                switch (Arr::firstValue($user)['role_id']) {
                    case 1:
                        $sessionKey = 'User';
                        break;
                    case 2:
                        $sessionKey = 'Admin';
                        break;
                }
                Session::start();
                Session::set($sessionKey, Arr::firstValue($user));
                Response::redirect('/home');
            } else {
                Session::set('loginStatus', false);
                self::render('/home/tu4k0/PhpstormProjects/tu4k0Education/' .
                'src/views/templates/authorization/login.php');
            }
        } elseif ($this->request->getMethod() === 'GET') {
            self::render('/home/tu4k0/PhpstormProjects/tu4k0Education/src/views/templates/authorization/login.php');
        }
    }
}
