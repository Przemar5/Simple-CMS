<?php

require_once('config.php');


class Connection extends PDO
{
    // For declaring single instance among all instances
	private static $instance = null;

	// Connection variables
    private $host = DB_HOST;
	private $user = DB_USER;
	private $pass = DB_PASSWORD;
	private $dbName = DB_NAME;

	// Returns instance of the object if it doesn't exist
    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __clone()
    {
        trigger_error('Cloning is not allowed.', E_USER_ERROR);
    }
	
    private function __construct()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->dbName;";
        parent::__construct($dsn, $this->user, $this->pass);

        //$this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }
}