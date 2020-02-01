<?php

require_once(CONNECTION);


class Page2
{
	
	public function __construct()
	{
		//Connection::getInstance() = Connection::getInstance();
		
	}
	
	
	public static function slugById($id)
	{
		$sql = 'SELECT slug
				FROM pages
				WHERE id = :id';
		$param = ['id' => $id];
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($param);
		
		return $stmt->fetch(PDO::FETCH_OBJ)->slug;
	}
	
	
	public static function idBySlug($slug)
	{
		$sql = 'SELECT id
				FROM pages
				WHERE slug = :slug';
		$param = ['slug' => $slug];
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($param);
		
		return $stmt->fetch(PDO::FETCH_OBJ)->id;
	}

	
    public static function all()
    {
        $sql = 'SELECT id, label, title, slug 
				FROM pages 
				ORDER BY created_at DESC';
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute();
		
		return $stmt;
    }
	
	
	public static function select($id)
	{
		$sql = 'SELECT *
				FROM pages
				WHERE id = :id';
		$param = ['id' => $id];
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($param);
		
		return $stmt;
	}
	
	
	public static function insert($request)
	{
		$sql = 'INSERT INTO pages 
					(label, title, slug, body) 
				VALUES (:label, :title, :slug, :body)';
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($request);
		$rows = $stmt->rowCount();
		
		return $rows;
	}
	
	
	public static function update($request)
	{
		$sql = 'UPDATE pages
				SET title = :title,
					label = :label,
					slug = :slug,
					body = :body
				WHERE id = :id';
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($request);
		$rows = $stmt->rowCount();
		
		return $rows;
	}
	
	
	public static function delete($id)
	{
        $sql = 'DELETE FROM pages 
				WHERE id=:id';
        $param = ['id' => $id];

        $stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($param);
		$rows = $stmt->rowCount();
		
		return $rows;
	}
}