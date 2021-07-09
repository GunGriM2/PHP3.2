<?php

namespace App\controllers;

use Delight\Auth\Auth;
use App\QueryBuilder;
use App\Input;
use League\Plates\Engine;
use PDO;
use \Valitron\Validator;
use Plasticbrain\FlashMessages\FlashMessages;

Validator::langDir();
Validator::lang('ru');

class HomeController
{
    private $templates, $pdo, $auth, $qb;

    public function __construct(QueryBuilder $qb, Engine $engine, PDO $pdo, Auth $auth, FlashMessages $msg)
    {
        $this->auth = $auth;
        $this->qb = $qb;
        $this->templates = $engine;
        $this->pdo = $pdo;
        $this->msg = $msg;
    }

    public function index()
    {

        if ($this->auth->isLoggedIn()) {
            header('Location: /profile');
        }

        $users = $this->qb->getAll('users');
        $loggedIn = $this->auth->isLoggedIn();
        $admin = $this->auth->hasRole(\Delight\Auth\Role::ADMIN);

        echo $this->templates->render('index', [
            'users' => $users,
            'loggedIn' => $loggedIn,
            'admin' => $admin
        ]);
    }

    public function userProfile($vars)
    {
        $user = $this->qb->getOne($vars['id'], 'users');

        $register_date = date('d/m/Y', $user['registered']);

        $loggedIn = $this->auth->isLoggedIn();
        $admin = $this->auth->hasRole(\Delight\Auth\Role::ADMIN);


        echo $this->templates->render('user-profile', [
            'username' => $user['username'],
            'id' => $vars['id'],
            'register_date' => $register_date,
            'profile_status' => $user['profile_status'],
            'loggedIn' => $loggedIn,
            'admin' => $admin
        ]);
    }

    public function register()
    {
        if (Input::exists()) {

            $validator = new \Valitron\Validator($_POST);
            $validator->rule('required', ['username', 'email', 'password', 'password_again', 'rules']);
            $validator->rule('email', 'email');
            $validator->rule('lengthBetween', 'username', 3, 15);
            $validator->rule('lengthMin', 'password', 3);
            $validator->rule('equals', 'password', 'password_again');


            if ($validator->validate()) {
                try {
                    $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username']);
                    $this->msg->success('You are successfully registered', null, true);
                    header('Location: /login');
                } catch (\Delight\Auth\InvalidEmailException $e) {
                    $this->msg->warning('Invalid email address', null, true);
                } catch (\Delight\Auth\InvalidPasswordException $e) {
                    $this->msg->warning('Invalid password', null, true);
                } catch (\Delight\Auth\UserAlreadyExistsException $e) {
                    $this->msg->warning('User already exists', null, true);
                } catch (\Delight\Auth\TooManyRequestsException $e) {
                    $this->msg->warning('Too many requests', null, true);
                }
            } else {
                // Errors
                $errors = [];
                foreach ($validator->errors() as $rule) {
                    foreach ($rule as $error) {
                        $this->msg->error($error, null, true);
                    }
                }
            }
        }


        $loggedIn = $this->auth->isLoggedIn();
        $admin = $this->auth->hasRole(\Delight\Auth\Role::ADMIN);

        echo $this->templates->render('registration', [
            'email' => Input::get('email'),
            'username' => Input::get('username'),
            'errors' => $this->msg,
            'loggedIn' => $loggedIn,
            'admin' => $admin
        ]);
    }

    public function login()
    {

        if (Input::exists()) {

            $validator = new \Valitron\Validator($_POST);
            $validator->rule('required', 'email');
            $validator->rule('required', 'password');
            $validator->rule('email', 'email');


            if ($validator->validate()) {
                try {

                    if ($_POST['remember'] === 'on') {
                        $rememberDuration = 60 * 60 * 24 * 14;
                    } else {
                        $rememberDuration = NULL;
                    }

                    $this->auth->login($_POST['email'], $_POST['password'], $rememberDuration);

                    // $this->msg->success('You are successfully logged in', null, true);
                    header('Location: /profile');
                } catch (\Delight\Auth\InvalidEmailException $e) {
                    $this->msg->warning('Wrong email address', null, true);
                } catch (\Delight\Auth\InvalidPasswordException $e) {
                    $this->msg->warning('Wrong password', null, true);
                } catch (\Delight\Auth\EmailNotVerifiedException $e) {
                    $this->msg->warning('Email not verified', null, true);
                } catch (\Delight\Auth\TooManyRequestsException $e) {
                    $this->msg->warning('Too many requests', null, true);
                }
            } else {
                // Errors
                $errors = [];
                foreach ($validator->errors() as $rule) {
                    foreach ($rule as $error) {
                        $this->msg->error($error, null, true);
                    }
                }
            }
        }

        $loggedIn = $this->auth->isLoggedIn();
        $admin = $this->auth->hasRole(\Delight\Auth\Role::ADMIN);

        echo $this->templates->render('login', [
            'email' => Input::get('email'),
            'errors' => $this->msg,
            'loggedIn' => $loggedIn,
            'admin' => $admin
        ]);
    }

    public function logout()
    {
        try {
            $this->auth->logout();
        } catch (\Delight\Auth\NotLoggedInException $e) {
            die('Not logged in');
        }

        header('Location: /home');
    }

    public function profile()
    {

        if (!$this->auth->isLoggedIn()) {
            header('Location: /home');
        }

        if (Input::exists()) {

            $validator = new \Valitron\Validator($_POST);
            $validator->rule('required', 'username');
            $validator->rule('lengthMax', 'status', 500);
            $validator->rule('lengthBetween', 'username', 3, 15);


            if ($validator->validate()) {

                $this->qb->update([
                    'username' => $_POST['username'],
                    'profile_status' => $_POST['profile_status']
                ], $this->auth->getUserId(), 'users');

                // flash()->success('You are successfully updated your info');
                header('Location: /profile');
            } else {
                // Errors
                $errors = [];
                foreach ($validator->errors() as $rule) {
                    foreach ($rule as $error) {
                        $this->msg->error($error);
                    }
                }
            }
        }

        $admin = $this->auth->hasRole(\Delight\Auth\Role::ADMIN);
        $profile_status = $this->qb->getOne($this->auth->getUserId(), 'users')['profile_status'];
        $username = $this->auth->getUsername();
        $loggedIn = $this->auth->isLoggedIn();

        $profile = $this->templates->make('profile');
        $profile->data([
            'username' => $username,
            'profile_status' => $profile_status,
            'admin' => $admin,
            'error' => $this->msg,
            'loggedIn' => $loggedIn
        ]);

        echo $profile;
    }

    public function changepassword()
    {

        if (!$this->auth->isLoggedIn()) {
            header('Location: /home');
        }

        if (Input::exists()) {

            $validator = new \Valitron\Validator($_POST);
            $validator->rule('required', ['current_password', 'new_password', 'new_password_again']);
            $validator->rule('lengthMin', 'current_password', 3);
            $validator->rule('lengthMin', 'new_password', 3);
            $validator->rule('lengthMin', 'new_password_again', 3);
            $validator->rule('equals', 'new_password', 'new_password_again');
            $validator->rule('different', 'current_password', 'new_password');

            if ($validator->validate()) {

                try {
                    $this->auth->changePassword($_POST['current_password'], $_POST['new_password']);

                    // flash()->success('You are successfully updated your password');
                } catch (\Delight\Auth\NotLoggedInException $e) {
                    $this->msg->warning('Not logged in');
                } catch (\Delight\Auth\InvalidPasswordException $e) {
                    $this->msg->warning('Invalid password(s)');
                } catch (\Delight\Auth\TooManyRequestsException $e) {
                    $this->msg->warning('Too many requests');
                }
            } else {
                // Errors
                $errors = [];
                foreach ($validator->errors() as $rule) {
                    foreach ($rule as $error) {
                        $this->msg->error($error);
                    }
                }
            }
        }

        $admin = $this->auth->hasRole(\Delight\Auth\Role::ADMIN);
        $loggedIn = $this->auth->isLoggedIn();

        echo $this->templates->render('changepassword', ['error' => $this->msg, 'admin' => $admin, 'loggedIn' => $loggedIn]);
    }

    public function users()
    {
        if (!$this->auth->isLoggedIn()) {
            if (!$this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
                header('Location: /home');
            }
        }

        $users = $this->qb->getAll('users');
        $loggedIn = $this->auth->isLoggedIn();
        $admin = $this->auth->hasRole(\Delight\Auth\Role::ADMIN);

        echo $this->templates->render('/user-managment/index', [
            'auth' => $this->auth,
            'users' => $users,
            'admin' => $admin,
            'loggedIn' => $loggedIn
        ]);
    }

    public function changeUserPermission($vars)
    {

        if (!$this->auth->isLoggedIn()) {
            if (!$this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
                header('Location: /home');
            }
        }

        $userId = $vars['id'];

        if ($this->auth->admin()->doesUserHaveRole($userId, \Delight\Auth\Role::ADMIN)) {
            try {
                $this->auth->admin()->removeRoleForUserById($userId, \Delight\Auth\Role::ADMIN);
            } catch (\Delight\Auth\UnknownIdException $e) {
                die('Unknown user ID');
            }
        } else {
            try {
                $this->auth->admin()->addRoleForUserById($userId, \Delight\Auth\Role::ADMIN);
            } catch (\Delight\Auth\UnknownIdException $e) {
                die('Unknown user ID');
            }
        }

        header('Location: /users');
    }

    public function userEdit($vars)
    {

        if (!$this->auth->isLoggedIn()) {
            if (!$this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
                header('Location: /home');
            }
        }
        $userId = $vars['id'];

        if (Input::exists()) {

            $validator = new \Valitron\Validator($_POST);
            $validator->rule('required', 'username');
            $validator->rule('lengthMax', 'status', 500);
            $validator->rule('lengthBetween', 'username', 3, 15);


            if ($validator->validate()) {

                $this->qb->update([
                    'username' => $_POST['username'],
                    'profile_status' => $_POST['profile_status']
                ], $userId, 'users');

                // $this->msg->success('You are successfully updated your info');

                header('Location: /users/edit/' . $userId);
            } else {
                // Errors
                $errors = [];
                foreach ($validator->errors() as $rule) {
                    foreach ($rule as $error) {
                        $this->msg->error($error);
                    }
                }
            }
        }

        $user = $this->qb->getOne($userId, 'users');
        $username = $user['username'];
        $profile_status = $user['profile_status'];

        $admin = $this->auth->hasRole(\Delight\Auth\Role::ADMIN);
        $loggedIn = $this->auth->isLoggedIn();

        $edit = $this->templates->make('/user-managment/edit');
        $edit->data([
            'userId' => $userId,
            'username' => $username,
            'profile_status' => $profile_status,
            'error' => $this->msg,
            'admin' => $admin,
            'loggedIn' => $loggedIn
        ]);

        echo $edit;
    }

    public function userDelete($vars)
    {
        if (!$this->auth->isLoggedIn()) {
            if (!$this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
                header('Location: /home');
            }
        }

        try {
            $this->auth->admin()->deleteUserById($vars['id']);
        } catch (\Delight\Auth\UnknownIdException $e) {
            die('Unknown ID');
        }

        header('Location: /users');
    }
}
