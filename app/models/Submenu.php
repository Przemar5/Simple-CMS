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
				FROM navigation_items 
				ORDER BY item_index ASC';
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
		
		return $stmt;
	}
	
	
	public static function insert($request)
	{
		$sql = 'INSERT INTO navigation_submenus (label) 
				VALUES (:label)';
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($request);
		$rows = $stmt->rowCount();
		
		return $rows;
	}
	
	
	public static function update($request)
	{
		$sql = 'UPDATE navigation_items
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
        $sql = 'DELETE FROM navigation_items 
				WHERE id=:id';
        $param = ['id' => $id];

        $stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($param);
		$rows = $stmt->rowCount();
		
		return $rows;
	}
}