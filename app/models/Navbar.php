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
					OR parent_id = 0
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
	
	
	public static function selectCount($parentId)
	{
		$sql = 'SELECT COUNT(*)
				FROM navigation_items
				WHERE parent_id = :parent_id';
		$param = ['parent_id' => $parentId];
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($param);
		
		return $stmt->fetch(PDO::FETCH_NUM)[0];
	}
	
	
	public static function selectMainMenu()
	{
		$sql = 'SELECT *
				FROM navigation_items
				WHERE parent_id IS NULL';
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute();
		
		return $stmt;
	}
	
	
	public static function selectByParent($parentId)
	{
		$sql = 'SELECT *
				FROM navigation_items
				WHERE parent_id = :parent_id
				ORDER BY item_index ASC';
		$param = ['parent_id' => $parentId];
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($param);
		
		return $stmt;
	}
	
	
	public static function insertLink($request)
	{
		$sql = 'INSERT INTO navigation_items 
					(id, page_id, submenu_id, item_index, parent_id) 
				VALUES (NULL, :page_id, NULL, :item_index, :parent_id)';
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($request);
		$rows = $stmt->rowCount();
		
		return $rows;
	}
	
	
	public static function insertSubmenu($request)
	{
		$sql = 'INSERT INTO navigation_items 
					(id, page_id, submenu_id, item_index, parent_id) 
				VALUES (NULL, NULL, :submenu_id, :item_index, :parent_id)';
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($request);
		$rows = $stmt->rowCount();
		
//		echo "<pre>";
//		$stmt->debugDumpParams();
//		die();
		
		return $rows;
	}
	
	
	public static function deleteSubmenu($id)
	{
		$sql = "DELETE FROM navigation_items
				WHERE id = :id";
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute(['id' => $id]);
		$rows = $stmt->rowCount();
		
		return $rows;
	}
	
	
	public static function updateParent($params)
	{
		$sql = "UPDATE navigation_items
				SET parent_id = :parent_id
				WHERE parent_id = :submenu_id";
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($params);
		$rows = $stmt->rowCount();
		
		return $rows;
	}
	
	
	public static function countElements($submenuId)
	{
		$sql = "SELECT COUNT(*)
				FROM navigation_items
				WHERE submenu_id = :submenu_id";
		$param = ['submenu_id' => $submenuId];
		$stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($param);
		
		return $stmt->fetch(PDO::FETCH_NUM)[0];
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
	
	
	public static function toggleParent($current, $requested)
	{
		$sql = 'UPDATE navigation_items
				SET parent_id = :requested
				WHERE parent_id = :current';
		$params = [
			'current' => $current,
			'requested' => $requested
		];

        $stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($params);
		$rows = $stmt->rowCount();
	}
	
	
	public static function changeParentItemIndexes($parentId = 0, $number = 1)
	{
		$sql = 'UPDATE navigation_items 
				SET item_index = item_index+(' . $number . ') 
				WHERE parent_id = :parent_id';
		$params = [
			'item_index' => $currentIndex,
			'parent_id' => $parentId,
		];

        $stmt = Connection::getInstance()->prepare($sql);
		$stmt->execute($params);
		$rows = $stmt->rowCount();
		
		return $rows;
	}
	
	
	public static function normalizeIndexes($parentId, $counter = 1)
	{
		$items = self::wholeLevel($parentId)
			->fetchAll(PDO::FETCH_ASSOC);
		$params = [];
		
		foreach ($items as $item) {
			if ($item['id'] !== $counter) {
				$params[$item['id']] = $counter;
			}
			
			$counter++;
		}
		
		if (!empty($params)) {
			self::updateIndexes($params);
		}
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