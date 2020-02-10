<?php

require_once(CONNECTION);


class Submenu
{
	public static function query($sql, $params = [])
    {
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute();
		
		return $stmt;
    }
	
	
	public static function lastInsertId()
	{
		return Connection::getInstance()->lastInsertId();
	}
	
	
	public static function all()
	{
		$sql = 'SELECT * 
				FROM navigation_submenus 
				ORDER BY id ASC';
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute();
		
		return $stmt;
	}
	
	
	public static function select($id)
	{
		$sql = 'SELECT *
				FROM navigation_submenus
				WHERE id = :id';
		$param = ['id' => $id];
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($param);
		
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	
	public static function selectBySlug($slug)
	{
		$sql = 'SELECT *
				FROM navigation_submenus
				WHERE slug = :slug';
		$param = ['slug' => $slug];
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($param);
		
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	
	public static function selectProperties($id, $properties = [])
	{
		$sql = "SELECT ";
		$sql .= implode(', ', $properties);
		$sql .=	" FROM navigation_submenus
				WHERE id = :id";
		$param = ["id" => $id];
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($param);
		
		return $stmt;
	}
	
	
	public static function insert($request)
	{
		$sql = 'INSERT INTO navigation_submenus (label, slug) 
				VALUES (:label, :slug)';
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($request);
		$rows = $stmt->rowCount();
		
		return $rows;
	}
	
	
	public static function update($request)
	{
		$sql = "UPDATE navigation_submenus
				SET label = :label,
					slug = :slug
				WHERE id = :id";
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($request);
		$rows = $stmt->rowCount();
		
		return $rows;
	}
	
	
	public static function delete($id)
	{
        $sql = 'DELETE FROM navigation_submenus 
				WHERE id=:id';
        $param = ['id' => $id];

        $stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($param);
		$rows = $stmt->rowCount();
		
		return $rows;
	}
}