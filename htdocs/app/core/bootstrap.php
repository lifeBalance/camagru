<?php

define('APPROOT', dirname(dirname(__FILE__)));
define('URLROOT', 'http://localhost');
define('SITENAME', 'camagru');
define('VERSION', '1.0.0');
define('DB_SETTINGS_FILE', APPROOT . '/config/database.php');
define('DB_SETUP_SCRIPT', APPROOT . '/config/setup.php');
define('DB_SQL_SCRIPT', APPROOT . '/config/db_setup.sql');
define('SECRET_HASHING_KEY', 'BBB235ACCAC1FE7EE7328F3587FE9');  // 128 bit = 16 bytes =  32 hex characters (max.)
define('UPLOADS_DIR', dirname(APPROOT). '/public/uploads'); // just 'uploads' is also fine ;-)

// DB settings must be required here!!!
require_once DB_SETTINGS_FILE;

// Autoload core classes
spl_autoload_register(function ($className)
{
    require_once(APPROOT .'/core/'. $className .'.class.php');
});