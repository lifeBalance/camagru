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
            if ($this->userModel->new($_POST) === false) {
                 // Load FAULTY form
                $formData = [
                    'title'         => 'register',
                    'email'         => $_POST['email'],
                    'username'      => $_POST['username'],
                    'password'      => $_POST['password'],
                    'pwd_confirm'   => $_POST['pwd_confirm'],
                    'pushNotif'     => empty($_POST['pushNotif']) ? '' : 'checked',
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
                'title'         => 'register',
                'email'         => '',
                'username'      => '',
                'password'      => '',
                'pwdConfirm'    => '',
                'pushNotif'     => 'checked',
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
                if ($authenticatedUser->confirmed) {
                    $data = [
                        'title'     => 'gallery',
                        'success'   => 'login successful'
                    ];
                    $this->createUserSession($authenticatedUser);
                    $this->render('pics/index', $data);
                } else {
                    $errors = [
                        'email_confirm' => 'please confirm your account'
                    ];
                    $data = [
                        'title'     => 'gallery',
                        'errors'   => $errors
                    ];
                    $this->render('pics/index', $data);
                }
            // Authentication failure
            } else {
                if (isset($this->userModel->errors['email_confirm'])) {
                    $data = [
                        'title'     => 'login',
                        'email'     => '',
                        'password'  => '',
                        'errors'    => $this->userModel->errors,
                    ];
                } else {
                    $data = [
                        'title'     => 'login',
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
                'title'     => 'login',
                'email'     => '',
                'password'  => '',
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

    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public function settings()
    {
        // If it's logged in
        if ($this->isLoggedIn() &&$_SERVER['REQUEST_METHOD'] == 'GET') {
            $user = $this->userModel->findById($_SESSION['user_id']);
            $formData = [
                'title'         => 'settings',
                'email'         => $user->email,
                'username'      => $user->username,
                'password'      => '',
                'pwdConfirm'    => '',
                'pushNotif'     => $user->push_notif,
            ];
            $this->render('users/settings', $formData);
        }
        else if ($this->isLoggedIn() &&$_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->userModel->edit($_POST, $_SESSION['user_id']) === false) {
                // Load FAULTY form
                $formData = [
                    'title'         => 'settings',
                    'email'         => $_POST['email'],
                    'username'      => $_POST['username'],
                    'password'      => '',
                    'pwdConfirm'    => '',
                    'pushNotif'     => isset($_POST['pushNotif']) ? 'checked' : '',
                    'errors'        => $this->userModel->errors,
                ];
                $this->render('users/settings', $formData);
            } else {
                $data = [
                    'title' => 'gallery',
                    'success' => 'Your account settings have been updated',
                ];
                $this->render('pics/index', $data);
            }
        } else {
            $this->redirect('/');
        }
    }
}