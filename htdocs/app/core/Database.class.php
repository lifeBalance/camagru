<?php

require_once('../app/config/database.php');

class Database
{
    private $db_host = DB_HOST;
    private $db_user = DB_USER;
    private $db_pass = DB_PASS;
    private $db_name = DB_NAME;
    private $db_file = DB_SETUP_FILE;
    private $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    private $dbh;

    public function __construct()
    {
        try {
            $dsn = "mysql:host=$this->db_host;dbname=$this->db_name";
            // Connect to database
            $this->dbh = new PDO($dsn,
                                $this->db_user,
                                $this->db_pass,
                                $this->options);
            die('DB EXISTS!'); // remove later
        } catch(PDOException $e){
            if ($e->getCode() == 1049) {    // Code 1049: db does not exist.
                $dsn = "mysql:host=$this->db_host";
                // Set up database
                $this->dbh = new PDO($dsn,
                                    $this->db_user,
                                    $this->db_pass,
                                    $this->options);
                $sql = file_get_contents($this->db_file);
                $this->dbh->exec($sql);
                die('DB has been set the fuck up!'); // remove later
            }
            else
                die ($e->getMessage());
        }
    }
}
