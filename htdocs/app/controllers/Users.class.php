<?php
class Users extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->load('User');
    }
    public function test()
    {
        $foundUsers = $this->userModel->getUsers();
        $this->render('users/test', $foundUsers);
    }

    public function edit($params)
    {
        echo '(' . getcwd() . ") you are in Users/edit: $params";
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
                header('Location: ' . URLROOT);
            // Authentication failure
            } else {
                $formData = [
                    'email' => 'Who dafak is that?',
                    'password' => 'Who dafak is that?',
                ];
                $this->render('users/login', $formData);
            }
        // Not a POST request (user just reloaded page)
        } else {
            // Load EMPTY form 
            $formData = [
                'email' => 'boop',
                'password' => 'boop',
            ];
            $this->render('users/login', $formData);
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . URLROOT);
    }

    public function createUserSession($foundUser)
    {
        $_SESSION['user'] = array(
            'user_id'   => $foundUser->id,
            'email'     => $foundUser->email,
            'username'  => $foundUser->username);
    }
}