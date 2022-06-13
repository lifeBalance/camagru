<?php

class Users extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->load('User');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->userModel->save($_POST) === false) {
                 // Load EMPTY form 
                $formData = [
                    'email'         => $_POST['email'],
                    'username'      => $_POST['username'],
                    'password'      => $_POST['password'],
                    'pwdConfirm'    => $_POST['pwdConfirm'],
                    'errors'        => $this->userModel->errors,
                ];
                $this->render('users/register', $formData);
            } else {
                $data['confirm'] = 'check your email to confirm your account';
                $this->redirect('/', $data);
            }
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
            // Authenticate user (Check her password)
            $authenticatedUser = $this->userModel->authenticate($_POST);
            
            // Authentication success
            if ($authenticatedUser) {
                $data = [
                    'success' => 'login successful'
                ];
                $this->createUserSession($authenticatedUser);
                $this->redirect('/', $data);
            // Authentication failure
            } else {
                if (isset($this->userModel->errors['email_confirm'])) {
                    $data = [
                        'email'     => '',
                        'password'  => '',
                        'errors'    => $this->userModel->errors,
                    ];
                } else {
                    $data = [
                        'email'     => $_POST['email'],
                        'password'  => $_POST['password'],
                        'errors'    => $this->userModel->errors,
                    ];
                }
                $this->render('users/login', $data);
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
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        // Finally, destroy the session.
        session_destroy();

        $this->redirect('/');
    }

    public function createUserSession($foundUser)
    {
        session_regenerate_id(true);    // Prevent session-fixation attacks!
        $_SESSION['user_id'] = $foundUser->id;
        $_SESSION['username'] = $foundUser->username;
    }
}