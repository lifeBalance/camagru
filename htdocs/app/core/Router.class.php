<?php

class Router {
    protected $controller;
    protected $action;
    protected $params = [];

    public function __construct()
    {
        $url = $this->getUrl();
        // Set CONTROLLER (first array element)
        if (!empty($url[0])) {
            if (file_exists('../app/controllers/' . ucwords($url[0]) . '.class.php')) {
                $className = ucwords($url[0]);
                require_once '../app/controllers/' . $className . '.class.php';
                $this->controller = new $className();

                // Set ACTION (second array element)
                if (!empty($url[1]) && is_callable(array($this->controller, $url[1]))) {
                    $this->action = $url[1];

                    // Set action PARAMS (third array element)
                    if (!empty($url[2]))
                        $this->params = array_slice($url, 2);
                    // Call the Controller/Action passing the parameters
                    call_user_func_array([$this->controller, $this->action], [$this->params]);
                } else {
                    Router::notfound();
                }
            } else {
                Router::notfound();
            }
        } else {
            // Default route (First page of the gallery)
            require_once('../app/controllers/Posts.class.php');
            $this->controller   = new Posts ();
            $this->action       = 'page';
            $this->params       = [1];
            call_user_func_array([$this->controller, $this->action], [$this->params]);
        }
    }

    static function notfound()
    {
        http_response_code(404);
        require_once('../app/views/404.php');
        die();
    }

    protected function getUrl() {
        if (isset($_SERVER['QUERY_STRING'])) {
            // Trim whitespace at the end
            $url = rtrim($_SERVER['QUERY_STRING'], '/');
            // Replace ? by & (?foo=bar becomes &foo=bar)
            $url = filter_var($url, FILTER_SANITIZE_URL);
            // Remove anything after &
            $url = preg_replace('/&.*/', '', $url);
            // Remove anything after =
            $url = preg_replace('/=.*/', '', $url);
            // Explode the string into parts using the '/' as separator
            return  explode('/', $url);
        }
    }
}