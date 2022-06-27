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
                $this->render('login/new', $data);
            }
        // Not a POST request (user just reloaded page)
        } else {
            // Load EMPTY form 
            $formData = [
                'email'     => '',
                'password'  => '',
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
        Flash::addFlashes(['see ya later dawg!' => 'success']);
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