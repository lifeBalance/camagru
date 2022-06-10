<?php
class Pics extends Controller
{
    public function index()
    {
        echo 'you are in Pics/index';
    }

    public function edit($id = 0)
    {
        echo '(' . getcwd() . ") you are in Pics/edit: $id";
    }
}