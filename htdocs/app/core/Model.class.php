<?php

require_once('../app/config/database.php');
require_once('../app/config/setup.php');

abstract class Model
{
    static function getDB()
    {
        static $dbh;
        
        if ($dbh === null)
        {
            try {
                // Connect to existing database
                $dbh = new PDO('mysql:host='. $GLOBALS['DB_HOST'] . ';dbname=' . $GLOBALS['DB_NAME'],
                $GLOBALS['DB_USER'],
                $GLOBALS['DB_PASS'],
                $GLOBALS['DB_OPTS']);
            } catch(PDOException $e){
                if ($e->getCode() == 1049)  // Code 1049: db does not exist.
                    // Set up database
                    $dbh = setup_db();
            }
        }
        return $dbh;
    }
}