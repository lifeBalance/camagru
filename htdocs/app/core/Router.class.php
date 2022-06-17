<?php

class Router {
    protected $controller;
    protected $action;
    protected $params = [];

    public function __construct()
    {
        $url = $this->getUrl();
        // set CONTROLLER (first array element)
        if (isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]) . '.class.php')) {
            $className = ucwords($url[0]);
            require_once '../app/controllers/' . $className . '.class.php';
            $this->controller = new $className();
            unset($url[0]);
            
            // set ACTION (second array element)
            if (isset($url[1]) && is_callable(array($this->controller, $url[1]))) {
                $this->action = $url[1];
                unset($url[1]);

                // set action PARAMS (third array element)
                if (isset($url[2])) {
                    $this->params = array_values($url);
                    unset($url[2]);
                }
            } else {
                $this->action = 'index';        // set DEFAULT action
            }
        } else {
            require_once '../app/controllers/Pics.class.php';
            $this->controller = new Pics();     // set DEFAULT controller
            $this->action = 'index';            // set DEFAULT action
        }
        // call the whole thing
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    protected function getUrl() {
        // if (isset($_SERVER['QUERY_STRING'])) {
        //     $url = rtrim($_SERVER['QUERY_STRING'], '/');
        //     $url = filter_var($url, FILTER_SANITIZE_URL);
        //     // Remove query string variables (?foo=bar) from the end of the URL
        //     if ($url != '') {
        //         $parts = explode('&', $url, 2);
    
        //         if (strpos($parts[0], '=') === false)
        //             $url = $parts[0];
        //         else
        //             $url = '';
        //     }
        //     $url = explode('/', $url);
        //     return $url;
        // }
        if (isset($_SERVER['QUERY_STRING'])) {
            $url = rtrim($_SERVER['QUERY_STRING']);
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}