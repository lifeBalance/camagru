<?php

class Users extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->load('User');
    }

    public function confirm($user = null)
    {
        if ($user) {
            $this->send_mail($user);
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize data
            $sanitizedForm = [
                'email'     => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                'password'  => filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            ];
            // Authenticate user (Check her password)
            $authenticatedUser = $this->userModel->authenticate($sanitizedForm);

            // Authentication success
            if ($authenticatedUser) {
                $this->send_mail($authenticatedUser);
            } else {
                Flash::addFlashes($this->userModel->errors);
                // Load EMPTY form 
                $formData = [
                    'email'     => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                    'password'  => '',
                ];
                $this->render('users/confirm', $formData);
            }
        } else {
            // Load EMPTY form 
            $formData = [
                'email'     => '',
                'password'  => '',
            ];
            $this->render('users/confirm', $formData);
        }
    }

    public function send_mail($user)
    {
        $to = $user->email;
        $subject = 'Confirm you Camagru account, biatch';
        $token = $this->userModel->generateToken($user->email);
        $message = 'Click <a href="http://localhost/users/activate/' . $token .
                    '">here</a> to confirm your account..' . "\r\n";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <camagru69@outlook.com>' . "\r\n";

        if (mail("<$to>", $subject, $message, $headers)) {
            Flash::addFlashes(['Confirmation mail is on the way!' => 'success']);
        } else {
            Flash::addFlashes(["Don't hold your breath waiting for the email!" => 'error']);
        }
        $this->redirect('/');
    }

    public function activate($token)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if ($this->userModel->verifyToken($token)) {
                Flash::addFlashes(['Account confirmed. You can log in!' => 'success']);
                $this->redirect('/users/login');
            } else {
                Flash::addFlashes(['That token is a bullshit' => 'error']);
                $this->redirect('/users/confirm');
            }
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize data
            $formData = $this->sanitize($_POST);
            array_merge($formData, ['action' => 'register']);
            $user = $this->userModel->new($formData);
            if ($user === false) {
                Flash::addFlashes($this->userModel->errors);

                // Load FAULTY form
                $this->render('users/register', $formData);
            } else {
                Flash::addFlashes(['check your email to confirm your account' => 'success']);
                // SEND CONFIRMATION EMAIL
                $this->confirm($user);
                $this->redirect('/');
            }
        // Not a POST request (user just reloaded page)
        } else {
            // Load EMPTY form 
            $formData = [
                'action'         => 'register',
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
                    Flash::addFlashes(['login successful' => 'success']);
                    $this->createUserSession($authenticatedUser);
                    $this->redirect('/');
                // Authenticated but email NOT CONFIRMED (can't let you in dawg)
                } else {
                    Flash::addFlashes(['please confirm your account' => 'warning']);
                    $this->redirect('/');
                }
            // Authentication failure
            } else {
                Flash::addFlashes($this->userModel->errors);
                $data = [
                    'title'     => 'login',
                    'email'     => $sanitizedForm['email'],
                    'password'  => '',
                ];
                $this->render('users/login', $data);
            }
        // Not a POST request (user just reloaded page)
        } else {
            // Load EMPTY form 
            $formData = [
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

        $this->redirect('/users/flashlogout');
    }

    public function flashlogout()
    {
        Flash::addFlashes(['see ya later dawg!' => 'success']);
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
        // If it's logged in: GET request
        if ($this->isLoggedIn() &&$_SERVER['REQUEST_METHOD'] == 'GET') {
            $user = $this->userModel->findById($_SESSION['user_id']);
            $formData = [
                'action'         => 'settings',
                'email'         => $user->email,
                'username'      => $user->username,
                'password'      => '',
                'pwdConfirm'    => '',
                'pushNotif'     => ($user->push_notif) ? 'checked' : '',
            ];
            $this->render('users/settings', $formData);
        }
        // If it's logged in: POST request
        else if ($this->isLoggedIn() && $_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize data
            $formData = $this->sanitize($_POST);
            array_merge($formData, ['action' => 'settings']);
            $oldAccount = $this->userModel->findById($_SESSION['user_id']);

            if ($this->userModel->edit($formData, $_SESSION['user_id']) === false) {
                // Load FAULTY form
                Flash::addFlashes($this->userModel->errors);
                $this->render('users/settings', $formData);
            } else {
                $newAccount = $this->userModel->findById($_SESSION['user_id']);
                Flash::addFlashes(['Your account settings have been updated']);
                $_SESSION['username'] = $newAccount->username;
                if ($oldAccount->email != $newAccount->email) {
                    // SEND CONFIRMATION EMAIL
                    Flash::addFlashes(['check your email to confirm your account' => 'warning']);
                    $this->logout();
                }
                $this->redirect('/');
            }
        // Can't access the form if logged out!
        } else {
            $this->redirect('/');
        }
    }
}