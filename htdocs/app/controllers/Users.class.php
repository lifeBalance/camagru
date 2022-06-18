<?php

class Users extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->load('User');
    }

    /**
     * GET requests: 
     *      - Render the user's account confirmation form.
     * POST requests:
     *      - Check if the submitted email/password belong to a real user and:
     *          - Send activation email if the account is not confirmed.
     *          - Flash a message is the account is already confirmed.
     *      - In case of error, re-render the user's account confirmation form.
     */
    public function confirm()
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
                    Flash::addFlashes([
                        'Your account is already confirmed. Go log in!' => 'success'
                    ]);
                } else if ($this->send_mail($authenticatedUser->email, 'Activate your account', 'activate')) {
                    Flash::addFlashes([
                        'Activation mail is on the way!' => 'success'
                    ]);
                } else {
                    Flash::addFlashes([
                        "Don't hold your breath waiting for the email, dawg!" => 'error'
                    ]);
                }
                $this->redirect('/');
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
                'password'  => ''
            ];
            $this->render('users/confirm', $formData);
        }
    }

    /**
     * Send emails to users.
     *
     * @param email     Email address of the user. 
     * @param subject   Subject of the email.
     * @param action    One of: 
     *
     * @return true or false
     */
    public function send_mail($email, $subject, $action)
    {
        $subject = $subject;
        $token = $this->userModel->generateToken($email);
        $message = 'Click <a href="http://localhost/users/' . $action . '/' . 
                    $token . '">here</a> to: <b>' . $subject . "</b>.\r\n";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <camagru69@outlook.com>' . "\r\n";

        return mail("<$email>", $subject, $message, $headers);
    }

    /**
     * Use the token argument to call the 'verifyToken' method on the user 
     * model. Flashes informative message depending on if the account was
     * successfully activated, or the token was invalid.
     *
     * @param token     The token contained in the email.
     *
     */
    public function activate($token)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if ($this->userModel->verifyToken($token)) {
                Flash::addFlashes(['Account activated. You can log in!' => 'success']);
                $this->redirect('/users/login');
            } else {
                Flash::addFlashes(['That token is a bullshit' => 'error']);
                $this->redirect('/users/confirm');
            }
        }
    }

    /**
     * GET requests: 
     *      - Render an empty account registration form.
     * POST requests:
     *      - Save valid user details in the database.
     *      - Re-render incomplete form in case of errors.
     *      - Send confirmation email.
     *      - Flash informative messages according to the outcome of any
     *      of the operations described above.
     */
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
                // SEND CONFIRMATION EMAIL
                if ($this->send_mail($user->email, 'Activate your account', 'activate')) {
                    Flash::addFlashes(['Activation mail is on the way!' => 'success']);
                } else {
                    Flash::addFlashes(["Don't hold your breath waiting for the email, dawg!" => 'error']);
                }
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

    /**
     * Sanitize all the fields in the new account form.
     * 
     * @param formData  The $POST request
     * 
     * @return Array with the sanitized form fields.
     */
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

    /**
     * Empty the session global and call the 'flashlogout' helper to inform
     * the user about having been logged out.
     */
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
    public function newpwd()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize form
            $data = [
                'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
            ];
            // Render errors if any
            if ($this->userModel->findByEmail($data['email']))
            {
                if ($this->send_mail($data['email'], 'Reset your password', 'resetpwd'))
                    Flash::addFlashes(['Reset password email is on its way!' => 'success']);
                else
                    Flash::addFlashes(["Don't hold your breath waiting for that email!" => 'error']);
                $this->redirect('/');
            } else {
                Flash::addFlashes(['Wrong user!' => 'error']);
                $this->redirect('/users/newpwd');
            }
            // Send email with token for pwd reset
        } else {
            $data = [
                'email' => ''
            ];
            $this->render('users/email_form', $data);
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
    public function resetpwd($args)
    {
        $token = $args[0];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize form
            $data = [
                'password' => filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'pwdConfirm' => filter_var($_POST['pwdConfirm'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'token' => filter_var($token, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            ];
            // Submit if both pwd match
            if ($data['password'] == $data['pwdConfirm'])
            {
                // Update the password of user with that token
                if ($this->userModel->updatePwd($data['token'], $data['password'])) {
                    Flash::addFlashes(['Password has been changed!' => 'success']);
                    Flash::addFlashes(['You can now log in!' => 'success']);
                    $this->redirect('/');
                } else {
                    // Redirect to root if something went wrong
                    Flash::addFlashes(['That token was a bullshit!' => 'error']);
                    $this->redirect('/');
                }
            }
        } else {
            // Render form to input new pwd
            $data = [
                'password' => '',
                'pwdConfirm' => '',
                'token'     => $token
            ];
            $this->render('users/pwd_form', $data);
        }
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
     */
    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Handle user settings modification requests (user must be logged in).
     *
     * GET requests (if user is logged in):
     *      - Render a semi-filled "user settings" form.
     * POST requests (if user is logged in):
     *      - Sanitize form fields before handling them to the user model.
     *      - Re-render empty form in case of errors.
     *      - Send activation email in case a new email was added.
     *      - Flash informative message in case of:
     *          - Settings updated successfully.
     *          - Activation email sent successfully (or not).
     */
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
            array_merge($formData, ['action' => 'settings']); // Form view's action
            $oldSettings = $this->userModel->findById($_SESSION['user_id']);

            if ($this->userModel->edit($formData, $_SESSION['user_id']) === false) {
                // Load FAULTY form
                Flash::addFlashes($this->userModel->errors);
                $this->render('users/settings', $formData);
            } else {
                $newSettings = $this->userModel->findById($_SESSION['user_id']);
                Flash::addFlashes(['Your account settings have been updated' => 'warning']);
                $_SESSION['username'] = $newSettings->username; // For 'Welcome X' msg

                // Log out the user is the email setting was changed!
                if ($oldSettings->email != $newSettings->email) {
                    // Set account to not confirmed
                    $this->userModel->confirmEmail($newSettings->email, false);
                    // Send confirmation token and log the user out
                    if ($this->send_mail($newSettings->email, 'Update your new settings', 'activate')) {
                        Flash::addFlashes([
                            'Your account settings have been updated' => 'warning',
                            'Confirmation mail is on the way!' => 'success'
                        ]);
                    } else {
                        Flash::addFlashes([
                            'Your account settings have been updated' => 'warning',
                            "Don't hold your breath waiting for the email, dawg!" => 'error'
                        ]);
                    }
                }
                $this->redirect('/');
            }
        // Can't access the form if logged out!
        } else {
            $this->redirect('/');
        }
    }
}