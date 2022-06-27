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
                } else if (Mail::send($authenticatedUser->email, 'Activate your account', 'users', 'activate')) {
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
                $this->redirect('/login/new');
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
                if (Mail::send($user->email, 'Activate your account', 'users', 'activate')) {
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
                    if (Mail::send($newSettings->email, 'Update your new settings', 'users', 'activate')) {
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