<?php

abstract class Controller
{
    protected function load(string $model)
    {
        $modelFile = APPROOT . '/models/' . $model . '.class.php';
        if (is_readable($modelFile)) {
            require_once($modelFile);
            return new $model();
        }
        else
            die($modelFile . ' model does not exist!<br>');
    }

    protected function render($view, $data)
    {
        extract($data); // To access variables by name in our views.
        $viewFile = APPROOT . '/views/' . $view . '.php';
        if (is_readable($viewFile))
            require_once($viewFile);
        else
            die($viewFile . 'view does not exist!<br>');
    }

    public function redirect($url) {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $url, true, 303);
    }
}
