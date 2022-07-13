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
        // If user is logged in, redirect her to URLROOT
        if (isset($_SESSION['user_id'])) {
            Controller::redirect('/');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize data
            $data = [
                'action' => 'register',
                'email'         => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                'username'      => filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'gravatar'      => filter_var($_POST['gravatar'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'password'      => filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'pwdConfirm'    => filter_var($_POST['pwdConfirm'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'pushNotif'     => isset($_POST['pushNotif']) ? true : false,
                // 'profile_pic'   => , // Write logic to save a user profile pic
                'scripts' => [
                    'main.js',
                ],
            ];
            $user = $this->userModel->new($data);
            if ($user === false) {
                Flash::addFlashes($this->userModel->errors);
                // Load FAULTY form
                $this->render('users/register', $data);
            } else {
                // SEND CONFIRMATION EMAIL
                $mail_data = [
                    'address'       => $user->email,
                    'subject'       => 'Activate your new account',
                    'controller'    => 'users',
                    'action'        => 'activate',
                    'token'         => $this->userModel->generateToken($user->email)
                ];
                if (Mail::send($mail_data)) {
                    Flash::addFlashes(['Activation mail is on the way!' => 'success']);
                } else {
                    Flash::addFlashes(["Don't hold your breath waiting for the email, dawg!" => 'danger']);
                }
                Controller::redirect('/');
            }
        // Not a POST request (user just reloaded page)
        } else {
            // Load EMPTY form 
            $data = [
                'action'         => 'register',
                'email'         => '',
                'username'      => '',
                'gravatar'      => '',
                'password'      => '',
                'pwdConfirm'    => '',
                'pushNotif'     => true,
                'scripts' => [
                    'main.js',
                ],
            ];
            $this->render('users/register', $data);
        }
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
                } else {
                    $data = [
                        'address'       => $sanitizedForm['email'],
                        'subject'       => 'Activate your account',
                        'controller'    => 'users',
                        'action'        => 'activate',
                        'token'         => $this->userModel->generateToken($sanitizedForm['email']),
                        'scripts'   => [
                            'main.js'
                        ],
                    ];
                    $success = Mail::send($data);
                    if ($success) 
                        Flash::addFlashes([
                            'Activation mail is on the way!' => 'success'
                        ]);
                    else
                        Flash::addFlashes([
                            "Don't hold your breath waiting for the email, dawg!" => 'danger'
                        ]);
                }
                Controller::redirect('/');
            } else {
                Flash::addFlashes($this->userModel->errors);
                // Load EMPTY form 
                $formData = [
                    'email'     => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                    'password'  => '',
                    'scripts'   => [
                        'main.js'
                    ],
                ];
                $this->render('users/confirm', $formData);
            }
        } else {
            // Load EMPTY form 
            $formData = [
                'email'     => '',
                'password'  => '',
                'scripts'   => [
                    'main.js'
                ],
            ];
            $this->render('users/confirm', $formData);
        }
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
                Controller::redirect('/login/new');
            } else {
                Flash::addFlashes(['That token is a bullshit' => 'danger']);
                Controller::redirect('/');
            }
        }
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
        if ($this->isLoggedIn() && $_SERVER['REQUEST_METHOD'] == 'GET') {
            $user = $this->userModel->findById($_SESSION['user_id']);
            // Pre-fill the form with intel from the DB.
            $formData = [
                'action'         => 'settings',
                'email'         => $user->email,
                'username'      => $user->username,
                'gravatar'      => $user->profile_pic,
                'password'      => '',
                'pwdConfirm'    => '',
                'pushNotif'     => $user->push_notif,
                'scripts' => [
                    'main.js',
                ],
            ];
            $this->render('users/settings', $formData);
        }
        // If it's logged in: POST request
        else if ($this->isLoggedIn() && $_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize data
            $data = [
                'action' => 'register',
                'email'         => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                'username'      => filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'password' => filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'pwdConfirm' => filter_var($_POST['pwdConfirm'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'gravatar'      => filter_var($_POST['gravatar'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'pushNotif'     => isset($_POST['pushNotif']) ? true : false,
                'scripts' => [
                    'main.js',
                ],
            ];
            // If the form 'password' field was empty, don't pass to model
            if (strlen($data['password']) == 0) {
                // var_dump('pwd was not set');
                // die();
                unset($data['password']);
                unset($data['pwdConfirm']);
            }
            $oldSettings = $this->userModel->findById($_SESSION['user_id']);
            // When email has been changed, check that new email doesn't already exist in the db
            if ($data['email'] != $oldSettings->email &&
                $this->userModel->findByEmail($data['email']))
            {
                // Load FAULTY form
                Flash::addFlashes(['That email is already taken' => 'danger']);
                $this->render('users/settings', $data);
            }
            else if ($this->userModel->edit($data, $_SESSION['user_id']) === false)
            {
                // Load FAULTY form
                Flash::addFlashes($this->userModel->errors);
                $this->render('users/settings', $data);
            }
            else
            {
                // No errors in the form
                $newSettings = $this->userModel->findById($_SESSION['user_id']);
                Flash::addFlashes(['Your account settings have been updated' => 'warning']);
                $_SESSION['username'] = $newSettings->username; // So we don't lose 'Welcome X' msg

                // Log out the user is the email setting was changed!
                if ($oldSettings->email != $newSettings->email) {
                    // Set account to not confirmed
                    $this->userModel->confirmEmail($newSettings->email, false);
                    // Send confirmation token and log the user out
                    $data = [
                        'address'       => $newSettings->email,
                        'subject'       => 'Confirm your new settings',
                        'controller'    => 'users',
                        'action'        => 'activate',
                        'token'         => $this->userModel->generateToken($newSettings->email)
                    ];
                    if (Mail::send($data)) {
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
                    Controller::redirect('/login/kickout');
                    die();
                }
                Controller::redirect('/');
            }
        // Can't access the form if logged out!
        } else {
            Controller::redirect('/login/new');
        }
    }
}