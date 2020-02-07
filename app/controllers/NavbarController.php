<?php

if (!isset($_SESSION))
{
	session_start();
}

require_once(CONNECTION);
require_once(MODELS_ROOT . '/Page.php');
require_once(MODELS_ROOT . '/Navbar.php');
require_once(MODELS_ROOT . '/Submenu.php');
require_once(MODELS_ROOT . '/Submenu_item.php');
require_once('Validator.php');


class NavbarController
{
	private static $instance = null;
	public static $items = null;
	public static $submenus = null;
	public static $firstLayer = null;
	public static $navbarItems = null;
	
	public static function getInstance()
	{
		if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
	}
	
	private function __construct()
	{
		
	}
	
	public static function getItems()
	{
		
	}
	
	public static function getAllItems()
	{
		return Navbar::all()->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public static function getLinks()
	{
		return Navbar::items()->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public static function getSubmenus()
	{
		return Navbar::submenus()->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public static function convertToReadable($items)
	{
		$result = [];
		
		foreach ($items as $item) {
			if (self::isNotEmpty($item)) {
				if (self::isLink($item)) {
					array_push($result, self::processLinkData($item));
				}
				else if (self::isSubmenu($item)) {
					array_push($result, self::processSubmenuData($item));
				}
			}
		}
		
		return $result;
	}
	
	public static function prepareAsTable($items)
	{
		$result = [];
		
		for ($i = 0; $i < sizeof($items); $i++) {
			if (self::isNotEmpty($items[$i])) {
				//$result[$i] = $items[$i];
				if (self::isLink($items[$i])) {
					array_push($result, self::processLinkData($items[$i]));
					$result[$i]['type'] = 'link';
				}
				else if (self::isSubmenu($items[$i])) {
					array_push($result, self::processSubmenuData($items[$i]));
					$result[$i]['type'] = 'submenu';
				}
				
				if (self::isNotEmpty($items[$i]['parent_id'])) {
					$result[$i]['parent'] = (Submenu::select($items[$i]['parent_id'])->fetch(PDO::FETCH_ASSOC))['label'];
				}
				else {
					$result[$i]['parent'] = 'None';
				}
				
				foreach ($items[$i] as $key => $value) {
					$result[$i][$key] = $items[$i][$key];
				}
				
				$result[$i]['url_edit'] = NAVBAR_SUBMENUS . '/update/' . $items[$i]['id'];
				$result[$i]['url_delete'] = NAVBAR_SUBMENUS . '/delete/' . $items[$i]['id'];
			}
		}
		
		return $result;
	}
	
	public static function view()
	{
		if (!self::$navbarItems) {
			self::$navbarItems = self::getLevelStmt();
			self::$navbarItems = self::getNavbarItems(self::$navbarItems);
		}
		$navbarItems = self::$navbarItems;
		
		require_once(NAVBAR);
	}
	
	
	public static function getNavbarItems($stmt)
	{
		$result = [];
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			if (self::isLink($row)) {
				$row = self::processLinkData($row);
			}
			else if (self::isSubmenu($row)) {
				$newStmt = self::getLevelStmt($row['submenu_id']);
				$row = self::processSubmenuData($row);
				$row['children'] = self::getNavbarItems($newStmt);
			}
			
			array_push($result, $row);
		}
		
		return $result;
	}
	
	
	public static function getLevelData($submenu_id = null)
	{
		if (!$submenu_id) {
			return Navbar::baseLevel()->fetchAll(PDO::FETCH_ASSOC);
		}
		else {
			return Navbar::wholeLevel($submenu_id)->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	
	public static function getLevelStmt($submenu_id = null)
	{
		if (!$submenu_id) {
			return Navbar::baseLevel();
		}
		else {
			return Navbar::wholeLevel($submenu_id);
		}
	}
	
	
	public static function isNotEmpty($item)
	{
		return !empty($item) && $item != null;
	}
	
	
	public static function isLink($item)
	{
		return !empty($item['page_id']);
	}
	
	
	public static function isSubmenu($item)
	{
		return !empty($item['submenu_id']);
	}
	
	
	public static function processLinkData($item)
	{
		return Page::selectWithoutBody($item['page_id'])->fetch(PDO::FETCH_ASSOC);
	}
	
	
	public static function processSubmenuData($item)
	{
		return Submenu::select($item['submenu_id'])->fetch(PDO::FETCH_ASSOC);
	}3
	
	
	public static function hasParent($item)
	{
		return self::isNotEmpty($item['parent_id']);
	}
	
	
	public static function navbar()
	{
		self::getAllItems();
		self::view();
	}
	
	public static function addItem()
	{
		
	}
	
	
	public static function storeSubmenu($request)
	{
		$parent_id = $request['parent_id'];
		$rules = [
			'label' => ['required', 'between:3,55', 'unique:navigation_submenus'],
		];
		$validator = new Validator;
		print_r($request);die();
		
		if ((is_numeric($parent_id) && strlen($parent_id) < 10) || empty($parent_id)) {
			if ($validator->validate($request, $rules)) {
				$param = ['label' => $request['label']];
				
				$level = Navbar::wholeLevel($request['parent_id'])->fetchAll(PDO::FETCH_ASSOC);
				
				if (self::normalizeItemIndexes($level) && empty($request['item_index'])) {
					$request['item_index'] = sizeof($level) + 1;
				}
				else {
					Navbar::increaseItemIndexes($request['item_index'], $request['parent_id']);
				}
				
				if (Submenu::insert($param) === 1) {
					// I already have no idea if using this variable is good idea.
					// I wonder how would it work submitting many forms at the same time
					$lastInsertId = Submenu::lastInsertId();
					$params = [
						'submenu_id' => $lastInsertId,
						'item_index' => $request['item_index'],
						'parent_id' => $request['parent_id']
					];
					
					if (Navbar::insertSubmenu($params)) {
						$_SESSION['last_action']['success'] = 'New submenu was created and added successfully.';
					}
				}
				else {
					$_SESSION['last_action']['error'] = 'Error: new submenu wasn\'t created.';
				}
			}
		}
		
		$_SESSION['input_errors'] = $validator->errors;
		$_SESSION['submitted_data'] = $request;
		
		header('Location: ' . NAVBAR_MANAGER);
	}
	
	
	public static function deleteSubmenu($request)
	{
		$elementId = $request['id'];
		$rules = [
			'id' => ['required', 'between:1,7', 'numeric'],
			'item_index' => ['required', 'between:1,7', 'numeric']
		];
		$validator = new Validator;
		
		if ($validator->validate($request, $rules)) {
			$level = Navbar::wholeLevel($request['parent_id'])->fetchAll(PDO::FETCH_ASSOC);
			$rows;
			
			if (self::isSubmenu($request)) {
				self::replaceItemsToGrandparent($request['submenu_id'], $request['parent_id']);
				Connection::delete()
					->from('navigation_submenus')
					->where([["id", "=", $request['submenu_id']]])
					->execute();
				$rows = Connection::delete()
					->from("navigation_items")
					->where([["submenu_id", "=", $request['submenu_id']]])
					->execute()
					->rowCount();
			}
			else if (self::isLink($request)) {
				$rows = Connection::delete()
					->from("navigation_items")
					->where([["page_id", "=", $request['page_id']]])
					->execute()
					->rowCount();
			}
			
			self::normalizeItemIndexes($level);
			
			if ($rows === 1) {
				$_SESSION['last_action']['success'] = 'Item was removed successfully from navigation.';
			}
			else {
				$_SESSION['last_action']['error'] = 'Error: Item wasn\'t removed from navigation.';
			}
		}
		
		header('Location: ' . NAVBAR_MANAGER);
	}
	
	
	public static function replaceItemsToGrandparent($submenuId, $parentId)
	{
		echo "<pre>";
		
		$params = [
			'submenu_id' => $submenuId,
			'parent_id' => $parentId
		];
		
		require_once(APP_ROOT . '/app/connect/Database.php');		
		
		$grandParentId = Connection::select(["parent_id"])
			->from("navigation_items")
			->where([["submenu_id", "=", $parentId]])
			->execute()
			->fetch(PDO::FETCH_OBJ)
			->parent_id;
		
		$count = Connection::count()
			->from("navigation_items")
			->where([["parent_id", "=", $grandParentId]])
			->execute()
			->fetch(PDO::FETCH_NUM)[0];
		
		$items = self::getLevelData($submenuId);
		
		if (self::isNotEmpty($items)) {
			Navbar::increaseItemIndexes(0, $parentId, $count);
			
			Connection::update("navigation_items")
				->set(["parent_id" => $grandParentId])
				->where([["parent_id", "=", $submenuId]])
				->execute();
		}
	}
	
	
	public static function getGrandparentCount($submenuId)
	{
		return Navbar::countElements($submenuId);
	}
	
	
	public static function normalizeItemIndexes($submenu)
	{
		$updateList = [];
		
		for ($i = 0; $i < sizeof($submenu); $i++) {
			if ($submenu[$i]['item_index'] != $i + 1) {
				$updateList[$submenu[$i]['id']] = $i + 1;
			}
		}
		
		return sizeof($updateList) === Navbar::updateIndexes($updateList);
	}
	
	public static function getChildren()
	{
		
	}
	
	private static function pushItemIndexes($itemIndex, $sumnenu = null)
	{
		
	}
}