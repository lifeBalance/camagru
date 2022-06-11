<?php
class Pics extends Controller
{
    public function index()
    {
        $this->render('pics/index', 
                        ['title' => 'Public Gallery']);
    }

    public function edit($id = 0)
    {
        echo '(' . getcwd() . ") you are in Pics/edit: $id";
    }
}