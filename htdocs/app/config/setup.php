<?php

function setup_db()
{
    try {
        $dbh = new PDO( $GLOBALS['DB_DSN'],
                        $GLOBALS['DB_USER'],
                        $GLOBALS['DB_PASS'],
                        $GLOBALS['DB_OPTS']);
        $dbh->exec(file_get_contents(DB_SQL_SCRIPT));
    } catch(PDOException $e){
        die ($e->getMessage());
    }
    return $dbh;
}
