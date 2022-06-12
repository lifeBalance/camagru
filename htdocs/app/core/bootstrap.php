<?php

define('APPROOT', dirname(dirname(__FILE__)));
define('URLROOT', 'http://localhost');
define('SITENAME', 'camagru');
define('VERSION', '1.0.0');
define('DB_SETTINGS_FILE', APPROOT . '/config/database.php');
define('DB_SETUP_SCRIPT', APPROOT . '/config/setup.php');
define('DB_SQL_SCRIPT', APPROOT . '/config/db_setup.sql');

// DB settings must be required here!!!
require_once DB_SETTINGS_FILE;

// Autoload core classes
spl_autoload_register(function ($className)
{
    require_once(APPROOT .'/core/'. $className .'.class.php');
});