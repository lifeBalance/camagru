<?php

/**
 * GLOBALS that can be accessed in other files by key, e.g. GLOBAL['DB_HOST']
 */
$DB_HOST = 'mysql';            // Name of the docker service
$DB_DSN  = 'mysql:host=' . $DB_HOST;
$DB_USER = 'root';
$DB_PASS ='1234';
$DB_NAME = 'camagru';
$DB_OPTS = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];