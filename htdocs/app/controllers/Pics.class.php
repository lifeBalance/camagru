<?php

class Pics extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'gallery'
        ];
        $this->render('pics/index', $data);
    }

    public function edit($id = 0)
    {
        $data = [
            'title' => 'editing',
            'id' => $id
        ];
        $this->render('pics/pic', $data);
    }
}