<?php

require_once('../app/config/database.php');

function setup_db()
{
    try {
        $dbh = new PDO( $GLOBALS['DB_DSN'],
                        $GLOBALS['DB_USER'],
                        $GLOBALS['DB_PASS'],
                        $GLOBALS['DB_OPTS']);
        $dbh->exec(file_get_contents($GLOBALS['DB_SETUP_FILE']));
    } catch(PDOException $e){
        die ($e->getMessage());
    }
    return $dbh;
}
