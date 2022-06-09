<?php
class Router {
    public function __construct()
    {
        echo 'Requested "' . $_SERVER['QUERY_STRING'] . '"';
    }
}