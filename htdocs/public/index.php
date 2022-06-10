<?php

require_once('../app/config/setup.php');

define('APPROOT', dirname(dirname(__FILE__)));
define('URLROOT', 'http://localhost');
define('SITENAME', 'camagru');
define('VERSION', '1.0.0');

// autoload core classes
spl_autoload_register(function ($className)
{
    require_once('../app/core/'. $className .'.class.php');
});

$router = new Router();

require_once('../app/models/User.class.php'); // test User class
$user = new User();
echo '<pre>';
var_dump($user->getUsers());
echo '</pre>';
