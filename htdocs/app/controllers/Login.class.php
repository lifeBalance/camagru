<?php

class Login extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->load('User');
    }

    /**
     * GET requests: 
     *      - Render an empty login form.
     * POST requests:
     *      - Sanitize user details before authenticate them in the database.
     *      - Re-render incomplete form in case of errors.
     *      - Flash informative message in case of:
     *          - Successful login.
     *          - Authentication errors.
     *          - Non-activated accounts.
     *
     * @param formData  The $POST request
     */
    public function new()
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
                    $link = '<a href="' . URLROOT . '/users/confirm">Click Here!</a>';
                    Flash::addFlashes(["No confirmation email? $link" => 'warning']);
                    $this->redirect('/');
                }
            // Authentication failure
            } else {
                Flash::addFlashes($this->userModel->errors);
                $data = [
                    'title'     => 'login',
                    'email'     => $sanitizedForm['email'],
                    'password'  => '',
                    'scripts' => [
                        'main.js',
                    ],
                ];
                $this->render('login/new', $data);
            }
        // Not a POST request (user just reloaded page)
        } else {
            // Load EMPTY form 
            $formData = [
                'email'     => '',
                'password'  => '',
                'scripts' => [
                    'main.js',
                ],
            ];
            $this->render('login/new', $formData);
        }
    }

    /**
     * Empty the session global and call the 'flashlogout' helper to inform
     * the user about having been logged out.
     */
    public function out()
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

        $this->redirect('/login/flashlogout');
    }

    /**
     * Helper function that calls 'addFlashes' to be able to inform the user
     * about having been logged out.
     */
    public function flashlogout()
    {
        Flash::addFlashes(['See ya later dawg!' => 'success']);
        $this->redirect('/');
    }

    /**
     * Helper function to create user session.
     *
     * @param   foundUser User found in the database
     */
    public function createUserSession($foundUser)
    {
        session_regenerate_id(true);    // Prevent session-fixation attacks!
        $_SESSION['user_id'] = $foundUser->id;
        $_SESSION['username'] = $foundUser->username;
    }

    /**
     * Handle "Forgot your password?" requests.
     *
     * GET requests: 
     *      - Render an empty request new password form.
     * POST requests:
     *      - Sanitize user email before searching in the database.
     *      - Re-render incomplete form in case of errors.
     *      - Flash informative message in case of:
     *          - Non-existing user.
     *          - Reset password email sent successfully.
     *          - Error when sending email.
     */
    public function forgot()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize form
            $data = [
                'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
            ];
            // Render errors if any
            if ($this->userModel->findByEmail($data['email']))
            {
                $data = [
                    'address'       => $data['email'],
                    'subject'       => 'Reset your password',
                    'controller'    => 'login',
                    'action'        => 'reset',
                    'token'         => $this->userModel->generateToken($data['email'])
                ];
                // Send email with token for pwd reset
                if (Mail::send($data))
                    Flash::addFlashes(['Reset password email is on its way!' => 'success']);
                else
                    Flash::addFlashes(["Don't hold your breath waiting for that email!" => 'danger']);
                $this->redirect('/');
            } else {
                Flash::addFlashes(['Wrong user!' => 'danger']);
                $this->redirect('/login/forgot');
            }
        } else {
            $data = [
                'email' => ''
            ];
            $this->render('login/forgot', $data);
        }
    }

    /**
     * Handle when the user clicks on 'Reset your password' link (on email).
     *
     * GET requests: 
     *      - Render an empty "reset your password" form.
     * POST requests:
     *      - Sanitize user's email, password and token
     *      before handling them to the model.
     *      - Re-render empty form in case of errors.
     *      - Flash informative message in case of:
     *          - Non-matching passwords.
     *          - Password updated successfully.
     *          - Error when updating db (invalid token).
     */
    public function reset($args)
    {
        // The router reads: URL/controller/action/args
        $token = $args[0]; // Router put args in an array (even if only 1)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize form
            $data = [
                'password' => filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'pwdConfirm' => filter_var($_POST['pwdConfirm'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'token' => filter_var($token, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            ];
            // var_dump('token is ' . $token . "<br>". $data['token']);
            // Submit if both pwd match
            if ($data['password'] == $data['pwdConfirm'])
            {
                // VERIFY TOKEN IS GOOD!!
                if ($this->userModel->validToken($data['token'])) {
                    // Update the password of user with that token
                    $this->userModel->updatePwd($data['token'], $data['password']);
                    Flash::addFlashes(['Password has been changed!' => 'success']);
                    Flash::addFlashes(['You can now log in!' => 'success']);
                } else
                    Flash::addFlashes(['Invalid token!' => 'danger']);
                $this->redirect('/');
            }
        } else {
            // Render form to input new pwd
            $data = [
                'password' => '',
                'pwdConfirm' => '',
                'token'     => $token
            ];
            if (!$this->userModel->validToken($data['token']))
                Flash::addFlashes(['Invalid token!' => 'danger']);
            $this->render('login/reset', $data);
        }
    }

    /**
     * Helper function to check if a user is logged in using the session.
     *
     * @return   true/false
     * (Currently not using it bc need to learn how to autoload/namespace!!)
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
}