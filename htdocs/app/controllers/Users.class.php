<?php
class Users extends Controller
{
    public function __construct()
    {
        $this->user = $this->load('User');
    }
    public function test()
    {
        $data = $this->user->getUsers();
        $this->render('users/test', $data);
    }

    public function edit($params)
    {
        echo '(' . getcwd() . ") you are in Users/edit: $params";
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->user->findByEmail($_POST['email'])) {
                // success
                $data = [
                    'email' => 'logged in dawg',
                    'password' => 'logged in dawg',
                ];
                $this->render('users/login', $data);
            } else {
                // failure
                $data = [
                    'email' => 'dafak is that?',
                    'password' => 'dafak is that?',
                ];
                $this->render('users/login', $data);
            }
        } else {
            // load EMPTY form (user just reloaded page)
            $data = [
                'email' => 'boop',
                'password' => 'boop',
            ];
            $this->render('users/login', $data);
        }
    }
}