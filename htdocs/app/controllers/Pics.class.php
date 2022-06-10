<?php
class Pics
{
    public function __construct()
    {
    }
    public function index()
    {
        echo 'you are in Pics/index';
    }
    public function edit($params)
    {
        echo "you are in Pics/edit: $params";
    }
}