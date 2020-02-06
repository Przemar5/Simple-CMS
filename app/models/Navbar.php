<?php

require_once(CONNECTION);


class Navbar
{
	public static function items()
    {
        $sql = 'SELECT pages.label, pages.slug
				FROM pages, navigation_items 
				WHERE pages.id = navigation_items.page_id
				ORDER BY item_index ASC';
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute();
		
		return $stmt;
    }
	
	
	public static function submenus()
	{
		$sql = 'SELECT * 
				FROM navigation_submenus';
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute();
		
		return $stmt;
	}
	
	
	public static function all()
	{
		$sql = 'SELECT * 
				FROM navigation_items 
				ORDER BY id DESC';
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute();
		
		return $stmt;
	}
	
	
	public static function baseLevel()
	{
		$sql = 'SELECT * 
				FROM navigation_items 
				WHERE parent_id IS NULL
				ORDER BY item_index ASC';
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute();
		
		return $stmt;
	}
	
	
	public static function wholeLevel($parentId)
	{
		$sql = 'SELECT * 
				FROM navigation_items 
				WHERE parent_id = :parent_id
				ORDER BY item_index ASC';
		$params = ['parent_id' => $parentId];
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($params);
		
		return $stmt;
	}
	
	
	public static function select($id)
	{
		$sql = 'SELECT *
				FROM navigation_items
				WHERE id = :id';
		$param = ['id' => $id];
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($param);
		
		return $stmt;
	}
	
	
	public static function insert($request)
	{
		$sql = 'INSERT INTO navigation_submenus 
					(submenu_id, body) 
				VALUES (:label, :title, :slug, :body)';
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($request);
		$rows = $stmt->rowCount();
		
		return $rows;
	}
	
	
	public static function insertSubmenu($request)
	{
		$sql = "INSERT INTO navigation_items 
					(submenu_id, item_index, parent_id) 
				VALUES (:submenu_id, :item_index, :parent_id)";
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
	
	
	public static function increaseItemIndexes($submenu, $itemIndex)
	{
		$sql = 'UPDATE navigation_items 
				SET item_index = item_index+1 
				WHERE submenu_id = :submenu_id
				AND item_index >= :item_index';
		$param = ['item_index' => $itemIndex];

        $stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($param);
		$rows = $stmt->rowCount();
	}
	
	
	public static function decreaseItemIndexes($submenu, $currentIndex)
	{
		$sql = 'UPDATE navigation_items 
				SET item_index = item_index-1 
				WHERE submenu_id = :submenu_id
				AND item_index >= :item_index';
		$param = ['item_index' => $itemIndex];

        $stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($param);
		$rows = $stmt->rowCount();
	}
	
	public static function normalizeIndexes()
	{
		
	}
	
	public static function updateIndexes($params)
	{
		if (sizeof($params) === 1) {
			$key = array_key_first($params);
			$value = $params[$key];
			$sql = "UPDATE navigation_items
					SET item_index = $value
					WHERE id = $key";
		}
		else {
			$sql = "UPDATE navigation_items
					SET item_index = (CASE";
			$rows = 1;
			
			foreach ($params as $key => $value) {
				$sql .= " WHEN id = $key THEN $value";
				$rows++;
			}
			$sql .= " END) WHERE id IN (";

			foreach ($params as $key => $value) {
				$rows--;
				$sql .= "$key";
				
				if ($rows > 1) {
					$sql .= ", ";
				}
			}

			$sql .= ")";
		}
		
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute();
		$rows = $stmt->rowCount();
		
		return $rows;
	}
}