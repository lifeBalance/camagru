<?php

class Pics extends Controller
{
    public function index()
    {
        $this->render(  'pics/index', 
                        [
                            'title' => 'Public Gallery'
                        ]);
    }

    public function edit($id = 0)
    {
        $this->render(  'pics/pic', 
                        [
                            'title' => 'Editing',
                            'id' => $id
                        ]);
    }
}