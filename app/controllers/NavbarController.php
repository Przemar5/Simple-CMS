<?php

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
	public static $navigationTree = null;
	
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
	
	public static function getAllItems()
	{
		if (!self::$firstLayer) {
			self::$firstLayer = Navbar::all()->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	public static function getLinks()
	{
		if (!self::$items) {
			self::$items = Navbar::items()->fetchAll(PDO::FETCH_ASSOC);
		}
		
		return self::$items;
	}
	
	public static function getSubmenus()
	{
		if (!self::$submenus) {
			self::$submenus = Navbar::submenus()->fetchAll(PDO::FETCH_ASSOC);
		}
		
		return self::$submenus;
	}
	
	public static function view()
	{
		
		$navbarItems = Navbar::baseLevel()->fetchAll(PDO::FETCH_ASSOC);
		$items = [];
		
		
		for ($i = 0; $i < sizeof($navbarItems); $i++) {
			$item = $navbarItems[$i];
			
			if (!empty($item) && $item != null) {
				if (!empty($item['page_id'])) {
					array_push($items, Page::selectWithoutBody($item['page_id'])->fetch(PDO::FETCH_ASSOC));
				}
				else if (!empty($item['submenu_id'])) {
					array_push($items, Submenu::select($item['submenu_id'])->fetch(PDO::FETCH_ASSOC));
					
					$itemsTemp[$i]['children'] = Navbar::wholeLevel($item['submenu_id'])->fetchAll(PDO::FETCH_ASSOC);
					
					
					for ($j = 0; $j < sizeof($itemsTemp[$i]['children']); $j++) {
						if (!empty($itemsTemp[$i]['children'][$j]) && $itemsTemp[$i]['children'][$j] != null) {
							
							if (!empty($itemsTemp[$i]['children'][$j]['page_id'])) {
								$items[$i]['children'][$j] = Page::selectWithoutBody($itemsTemp[$i]['children'][$j]['page_id'])->fetch(PDO::FETCH_ASSOC);
							}
							else if (!empty($itemsTemp[$i]['children'][$j]['submenu_id'])) {
//								$items[$i]['children'][$j]['children'] = Submenu_item::selectPages($item['submenu_id'])->fetchAll(PDO::FETCH_ASSOC);
								$itemsTemp[$i]['children'][$j]['children'] = Navbar::wholeLevel($itemsTemp[$i]['children'][$j]['submenu_id'])->fetchAll(PDO::FETCH_ASSOC);
								
								$items[$i]['children'][$j] = Submenu::select($itemsTemp[$i]['children'][$j]['submenu_id'])->fetch(PDO::FETCH_ASSOC);

							}
						}
					}
				}
			}
		}
		
		
		//$items = self::start();
		
		echo "<pre>";
		$navbarItems = $items;
		
		print_r($navbarItems);
		
		self::start();
		
		die();
		
		require_once(NAVBAR);
	}
	
	
	public static function getLevelData($submenu_id = null)
	{
		if (!$submenu_id) {
			return Navbar::baseLevel();//->fetchAll(PDO::FETCH_ASSOC);
		}
		else {
			return Navbar::wholeLevel($submenu_id);//->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	
	public static function start()
	{
		self::$navigationTree = null;
		
		$stmt = Navbar::baseLevel();
		
//		return self::processData($navbarItems);
		$result = [];
		
		$items = self::processData2($stmt->fetchAll(PDO::FETCH_ASSOC));
		
		echo "<pre>";
		print_r($items);
		
		/*
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			array_push($result, self::processData3($row));
		}
		*/
	}
	
	
	public static function processData3($item)
	{
		echo "<pre>";
		print_r($item);
		
		if (self::isNotEmpty($item)) {
			if (self::isLink($item)) {
				return self::processLinkData($item);
			}
			else if (self::isSubmenu($item)) {
				$item = self::processSubmenuData($item);

				$item['children'] = [];
				$stmt = self::getLevelData($item['submenu_id']);
				
				while ($submenu = $stmt->fetch(PDO::FETCH_ASSOC)) {
					array_push($item['children'], self::processData3($submenu));
				}
			}
		}
		
		return $item;
	}
	
	
	public static function processData(&$items, $level = 0)
	{
		for ($i = 0; $i < sizeof($items); $i++) {
			if (!empty($items[$i]) && $items[$i] != null) {
				if (!empty($items[$i]['page_id'])) {
					//echo "Item: $i, page_id = ".$items[$i]["page_id"]."<br><br>";
					array_push($items, Page::selectWithoutBody($items[$i]['page_id'])->fetch(PDO::FETCH_ASSOC));
				}
				else if (!empty($items[$i]['submenu_id'])) {
					array_push($items, Submenu::select($items[$i]['submenu_id'])->fetch(PDO::FETCH_ASSOC));
					
					$itemsTemp[$i]['children'] = Navbar::wholeLevel($items[$i]['submenu_id'])->fetchAll(PDO::FETCH_ASSOC);
					
					$items[$i]['children'] = self::processData($itemsTemp[$i]['children']);
					//self::processData($itemsTemp[$i]['children']);
				}
			}
		}
		return $items;
	}
	
	
	public static function processData2($items)
	{
		for ($i = 0; $i < sizeof($items); $i++) {
			if (self::isNotEmpty($items[$i])) {
				if (self::isLink($items[$i])) {
					array_push($items, self::processLinkData($items[$i]));
				}
				else if (self::isSubmenu($items[$i])) {
					array_push($items, self::processSubmenuData($items[$i]));
					
					$itemsTmp[$i]['children'] = self::getLevelData($items[$i]['submenu_id']);
					
					$items[$i]['children'] = self::processData2($itemsTmp[$i]['children']);
				}
			}
		}
		
		return $items;
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
	}
	
	
	
	
	public static function process(&$items = null)
	{
		for ($i = 0; $i < sizeof($navbarItems); $i++) {
			if (self::isNotEmpty($item)) {
				if (self::isLink($item)) {
					array_push($items, self::processLinkData($item));
				}
				else if (self::isSubmenu($item)) {
					array_push($items, self::processSubmenuData($item));
					
					$items['children'] = self::process($items);
				}
			}
			
			if (!empty($item) && $item != null) {
				if (!empty($item['page_id'])) {
					array_push($items, Page::selectWithoutBody($item['page_id'])->fetch(PDO::FETCH_ASSOC));
				}
				else if (!empty($item['submenu_id'])) {
					array_push($items, Submenu::select($item['submenu_id'])->fetch(PDO::FETCH_ASSOC));
					
//					$items[$i]['children'] = Submenu_item::selectPages($item['submenu_id'])->fetchAll(PDO::FETCH_ASSOC);
					$itemsTemp[$i]['children'] = Navbar::wholeLevel($item['submenu_id'])->fetchAll(PDO::FETCH_ASSOC);
					
					
				}
			}
		}
		
		return $items;
	}
	
	
	public static function navbar()
	{
		self::getAllItems();
		self::view();
	}
	
	public static function addItem()
	{
		
	}
	
	public static function addSubmenu($request)
	{
		$parent_id = $request['parent_id'];
		$rules = [
			'label' => ['required', 'between:3,55'],
		];
		$validator = new Validator;
		
		if ((is_numeric($parent_id) && strlen($parent_id) < 10) || empty($parent_id)) {
			if ($validator->validate($request, $rules)) {
				$param = ['label' => $request['label']];
				
				echo "<pre>";
				print_r($request);
				die();
				
				if (Submenu::insert($param) === 1) {
					// I already have no idea if using this variable is good idea.
					// I wonder how would it work submitting many forms at the same time
					$lastInsertId = Submenu::lastInsertId();
					
					$params = [
						'submenu_id' => $lastInsertId,
						'item_index' => 0,
						'parent_id' => 'parent_id'
					];
					
					if (Navbar::insertSubmenu($params)) {
						echo 'Inserted';
					}
					else {
						echo 'Not inserted';
					}
				}
			}
			
            $_SESSION['last_action']['success'] = 'New page was created successfully.';
		}
		else {
			$_SESSION['last_action']['error'] = 'Error: new page wasn\'t created.';
		}
		
		header('Location: ' . NAVBAR_MANAGER);
	}
	
	public static function getChildren()
	{
		
	}
	
	private static function toggleItemIndexes()
	{
		
	}
}