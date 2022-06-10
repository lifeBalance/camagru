<?php
class Users extends Controller
{
    public function index()
    {
        $userInstance = $this->load('User');
        $data = $userInstance->getUsers();
        $this->render('Users', $data);
    }

    public function edit($params)
    {
        echo '(' . getcwd() . ") you are in Users/edit: $params";
    }
}