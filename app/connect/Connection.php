<?php

require_once('config.php');


class Connection extends PDO
{
	private static $instance = null;
	private static $buffer;
	public static $query;
	public static $params;

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
		$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName;
		
		try {
			parent::__construct($dsn, $this->user, $this->pass);
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
	
	public static function select($items = [])
	{
		self::getInstance();
		
		self::$query = "SELECT";
		$arrSize = sizeof($items);
		
		if ($arrSize > 0) {
			for ($i = 0; $i < $arrSize; $i++) {
				self::$query .= " $items[$i]";

				if ($i < $arrSize - 1) {
					self::$query .= ",";
				}
			}
		}
		else {
			self::$query .= " *";
		}
		
		return self::$instance;
	}
	
	
	public static function insert($valuesList)
	{
		self::getInstance();
		self::$query = " (";
		$valuesPart = " VALUES (";
		$arrSize = sizeof($valuesList);
		
		for ($i = 0; $i < $arrSize; $i++) {
			reset($valuesList);
			$key = key($valuesList);
			
			self::$query .= " $key";
			$valuesPart .= (is_numeric($valuesList[$key])) 
				? " $valuesList[$key]" 
				: " '$valuesList[$key]'";

			if ($i < $arrSize - 1) {
				self::$query .= ",";
				$valuesPart .= ",";
			}
			
			array_shift($valuesList);
		}
		
		self::$query .= ")";
		$valuesPart .="')";
		self::$query .= $valuesPart;
		
		return self::$instance;
	}
	
	
	public static function update($table)
	{
		self::getInstance();
		self::$query = "UPDATE $table";
		
		return self::$instance;
	}
	
	
	public static function delete()
	{
		self::getInstance();
		self::$query = "DELETE";
		
		return self::$instance;
	}
	
	
	public static function count()
	{
		self::getInstance();
		self::$query = "SELECT COUNT(*)";
		
		return self::$instance;
	}
	
	
	public static function from($table)
	{
		self::$query .= " FROM $table";
		
		return self::$instance;
	}
	
	
	public static function into($table)
	{
		$query = "INSERT INTO $table" . self::$query;
		self::$query = $query;
		
		return self::$instance;
	}
	
	
	public static function set($valuesList, $string = false)
	{
		self::$query .= " SET";
		$arrSize = sizeof($valuesList);
		
		for ($i = 0; $i < $arrSize; $i++) {
			reset($valuesList);
			$key = key($valuesList);
			
			self::$query .= " $key =";
			
			if (!empty($valuesList[$key])) {
				self::$query .= ($string === true) 
					? " '$valuesList[$key]'" 
					: " $valuesList[$key]";
			}
			else {
				self::$query .= " NULL";
			}
			

			if ($i < $arrSize - 1) {
				self::$query .= ",";
			}
			
			array_shift($valuesList);
		}
		
		return self::$instance;
	}
	
	
	public static function where($items)
	{
		self::$query .= " WHERE";
		$arrSize = sizeof($items);
		
		for ($i = 0; $i < $arrSize; $i++) {
			self::$query .= " ".$items[$i][0]." ".$items[$i][1];
			self::$query .= (is_numeric($items[$i][2])) 
				? " ".$items[$i][2]
				: " '".$items[$i][2]."'";
			
			if ($i < $arrSize - 1) {
				self::$query .= " AND";
			}
		}
		
		return self::$instance;
	}
	
	
	public function orderBy($criteria, $order)
	{
		self::$query .= " ORDER BY $criteria $order";
		
		return self::$instance;
	}
	
	
	public static function execute($item = null)
	{
		$stmt = self::$instance->prepare(self::$query);
		$stmt->execute(self::$params);
		
		return $stmt;
	}
}