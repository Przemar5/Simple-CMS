<?php

require_once('config.php');


class Database extends PDO
{
	private static $instance = null;
	private static $counter;
	public static $query;
	public static $params;
	
	// Connection variables
    private $host = DB_HOST;
	private $user = DB_USER;
	private $pass = DB_PASSWORD;
	private $dbName = DB_NAME;
	
	public static function getInstance()
	{
		if (!self::$instance instanceof self) {
			self::$instance = new self;
			self::$params = [];
			self::$counter = 0;
		}
		
		return self::$instance;
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
	
	
	public static function from($table)
	{
		self::$query .= " FROM $table";
		
		return self::$instance;
	}
	
	
	public static function select($items)
	{
		self::getInstance();
		
		self::$query = "SELECT";
		$arrSize = sizeof($items);
		
		if ($arrSize > 1) {
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
	
	
	public static function where($items)
	{
		self::$query .= " WHERE";
		$arrSize = sizeof($items);
		
		for ($i = 0; $i < $arrSize; $i++) {
			self::$query .= " ".$items[$i][0]." ".$items[$i][1]." ".$items[$i][2];
			
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
		$stmt->debugDumpParams();
		
		print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
	}
}