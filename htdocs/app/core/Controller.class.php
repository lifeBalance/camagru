<?php

abstract class Controller
{
    protected function load(string $model)
    {
        if (is_readable('../app/models/' . $model . '.class.php')) {
            require_once('../app/models/' . $model . '.class.php');
            return new $model();
        }
        else
            die('Model does not exist!<br>');
    }

    protected function render($view, $data)
    {
        extract($data); // To access variables by name in our views.
        if (is_readable('../app/views/' . $view . '.php'))
            require_once('../app/views/' . $view . '.php');
        else
            die('View does not exist!<br>');
    }
}
