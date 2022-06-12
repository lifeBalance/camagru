<?php

class Users extends Controller
{
    private $userModel;
    private $errors = [];

    public function __construct()
    {
        $this->userModel = $this->load('User');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$this->userModel->save($_POST)) {
                array_push($this->errors, 'existing user');
                // do something with the error
            } else
                $this->redirect('/');
        // Not a POST request (user just reloaded page)
        } else {
            // Load EMPTY form 
            $formData = [
                'email'         => '',
                'username'      => '',
                'password'      => '',
                'pwdConfirm'    => '',
            ];
            $this->render('users/register', $formData);
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize Form input data
            $formData = [
                'email' => $_POST['email'],
                'password' => $_POST['password'],
            ];

            // Authenticate user (Check her password)
            $authenticatedUser = $this->userModel->authenticate($formData['email'], $formData['password']);
            
            // Authentication success
            if ($authenticatedUser) {
                $this->createUserSession($authenticatedUser);
                $this->redirect('/');
            // Authentication failure
            } else {
                $formData = [
                    'email' => $_POST['email'],
                    'password' => '',
                ];
                $this->render('users/login', $formData);
            }
        // Not a POST request (user just reloaded page)
        } else {
            // Load EMPTY form 
            $formData = [
                'email' => '',
                'password' => '',
            ];
            $this->render('users/login', $formData);
        }
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }

    public function createUserSession($foundUser)
    {
        $_SESSION['user'] = array(
            'user_id'   => $foundUser->id,
            'email'     => $foundUser->email,
            'username'  => $foundUser->username);
    }
}