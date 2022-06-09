<?php

define('APPROOT', dirname(dirname(__FILE__)));
define('URLROOT', 'http://localhost');
define('SITENAME', 'camagru');
define('VERSION', '1.0.0');

// autoload core classes
spl_autoload_register(function ($className)
{
    require_once('../app/core/'. $className .'.class.php');
});
