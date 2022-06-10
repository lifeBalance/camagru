<?php

$DB_HOST = 'db';            // Name of the docker service
$DB_DSN  = 'mysql:host=' . $DB_HOST;
$DB_USER = 'root';
$DB_PASS ='1234';
$DB_NAME = 'camagru';
$DB_SETUP_FILE = '../app/config/db_setup.sql';
$DB_OPTS = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];