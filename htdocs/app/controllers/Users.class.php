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
            // Sanitize data
            $formData = $this->sanitize($_POST);
            if ($this->userModel->new($formData) === false) {
                 // Load FAULTY form
                $data = [
                    'title'         => 'register',
                    'flashes'       => $this->userModel->errors,
                ];
                $this->render('users/register', array_merge($data, $formData));
            } else {
                $data = [
                    'confirm' => 'check your email to confirm your account'
                ];
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
                'pushNotif'     => 'on',
            ];
            $this->render('users/register', $formData);
        }
    }

    public function sanitize($formData)
    {
        $sanitizedForm = [
            'email'         => filter_var($formData['email'], FILTER_SANITIZE_EMAIL),
            'username'      => filter_var($formData['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'password'      => filter_var($formData['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'pwdConfirm'    => filter_var($formData['pwdConfirm'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'pushNotif'     => isset($formData['pushNotif']) ? 'on' : '',
        ];
        return $sanitizedForm;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize data
            $sanitizedForm = [
                'email'     => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                'password'  => filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            ];
            // Authenticate user (Check her password)
            $authenticatedUser = $this->userModel->authenticate($sanitizedForm);
            
            // Authentication success
            if ($authenticatedUser) {
                if ($authenticatedUser->confirmed) {
                    $data = [
                        'title'     => 'gallery',
                        'flashes'   => ['login successful'],
                    ];
                    $this->createUserSession($authenticatedUser);
                    $this->render('pics/index', $data);
                } else {
                    $data = [
                        'title'     => 'gallery',
                        'flashes'   => ['please confirm your account']
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
                        'flashes'   => $this->userModel->errors,
                    ];
                } else {
                    $data = [
                        'title'     => 'login',
                        'email'     => $_POST['email'],
                        'password'  => $_POST['password'],
                        'flashes'   => $this->userModel->errors,
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

    public function logout($data = [])
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
        if (empty($data))
            $data = ['title' => 'gallery'];
        $this->render('pics/index', $data);
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
        // If it's logged in: GET request
        if ($this->isLoggedIn() &&$_SERVER['REQUEST_METHOD'] == 'GET') {
            $user = $this->userModel->findById($_SESSION['user_id']);
            $formData = [
                'title'         => 'settings',
                'email'         => $user->email,
                'username'      => $user->username,
                'password'      => '',
                'pwdConfirm'    => '',
                'pushNotif'     => ($user->push_notif) ? 'checked' : '',
            ];
            $this->render('users/register', $formData);
        }
        // If it's logged in: POST request
        else if ($this->isLoggedIn() &&$_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize data
            $formData = $this->sanitize($_POST);
            $oldAccount = $this->userModel->findById($_SESSION['user_id']);

            if ($this->userModel->edit($formData, $_SESSION['user_id']) === false) {
                // Load FAULTY form
                $data = [
                    'title'     => 'settings',
                    'flashes'   => $this->userModel->errors,
                ];
                $this->render('users/register', array_merge($formData, $data));
            } else {
                $newAccount = $this->userModel->findById($_SESSION['user_id']);
                $data = [
                    'title' => 'gallery',
                    'flashes' => ['Your account settings have been updated'],
                ];
                $_SESSION['username'] = $newAccount->username;
                if ($oldAccount->email != $newAccount->email) {
                    array_push($data['flashes'], 'check your email to confirm your account');
                    $this->logout($data);
                }
                $this->render('pics/index', $data);
            }
        // Can't access the form if logged out!
        } else {
            $this->redirect('/');
        }
    }
}