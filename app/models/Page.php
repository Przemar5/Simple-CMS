<?php

require_once(CONNECTION);


class Page
{
	private $db;
	
	
	public function __construct()
	{
		$this->db = Connection::getInstance();
	}
	
	
	public function slugById($id)
	{
		$sql = 'SELECT slug
				FROM pages
				WHERE id = :id';
		$param = ['id' => $id];
		$stmt = $this->db->prepare($sql);
		$stmt->execute($param);
		
		return $stmt->fetch(PDO::FETCH_OBJ)->slug;
	}
	
	
	public function idBySlug($slug)
	{
		$sql = 'SELECT id
				FROM pages
				WHERE slug = :slug';
		$param = ['slug' => $slug];
		$stmt = $this->db->prepare($sql);
		$stmt->execute($param);
		
		return $stmt->fetch(PDO::FETCH_OBJ)->id;
	}

	
    public function all()
    {
        $sql = 'SELECT id, label, title, slug 
				FROM pages 
				ORDER BY created_at DESC';
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		
		return $stmt;
    }
	
	
	public function select($id)
	{
		$sql = 'SELECT *
				FROM pages
				WHERE id = :id';
		$param = ['id' => $id];
		$stmt = $this->db->prepare($sql);
		$stmt->execute($param);
		
		return $stmt;
	}
	
	
	public function insert($request)
	{
		$sql = 'INSERT INTO pages 
					(label, title, slug, body) 
				VALUES (:label, :title, :slug, :body)';
		$stmt = $this->db->prepare($sql);
		$stmt->execute($request);
		$rows = $stmt->rowCount();
		
		return $rows;
	}
	
	
	public function update($request)
	{
		$sql = 'UPDATE pages
				SET title = :title,
					label = :label,
					slug = :slug,
					body = :body
				WHERE id = :id';
		$stmt = $this->db->prepare($sql);
		$stmt->execute($request);
		$rows = $stmt->rowCount();
		
		return $rows;
	}
	
	
	public function delete($id)
	{
        $sql = 'DELETE FROM pages 
				WHERE id=:id';
        $param = ['id' => $id];

        $stmt = $this->db->prepare($sql);
		$stmt->execute($param);
		$rows = $stmt->rowCount();
		
		return $rows;
	}
}