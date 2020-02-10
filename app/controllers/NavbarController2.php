<?php

if (!isset($_SESSION))
{
	session_start();
}

require_once(CONNECTION);
require_once(CONTROLLERS_ROOT . '/classes/NavbarItem.php');
require_once(CONTROLLERS_ROOT . '/classes/NavbarLink.php');
require_once(CONTROLLERS_ROOT . '/classes/NavbarSubmenu.php');
require_once(MODELS_ROOT . '/Page.php');
require_once(MODELS_ROOT . '/Navbar.php');
require_once(MODELS_ROOT . '/Submenu.php');
require_once('Validator.php');
require_once('SubmenuController.php');
//require_once('NavbarLinkController.php');

class NavbarController2
{
	private static $instance = null;
	public static $navigationItems = [];
	public static $navigationTree = [];
	public static $submenus = [];
	
	
	public static function getInstance()
	{
		if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
	}
	
	
	private function __construct()
	{
		//
	}
	
	
	public static function isLink($item)
	{
		return !empty($item['page_id']) && $item['page_id'] != null;
	}
	
	
	public static function isMenu($item)
	{
		return !empty($item['submenu_id']) && $item['submenu_id'] != null;
	}
	
	
	public static function getNavigationItems()
	{
		self::$navigationItems = Navbar::all()->fetchAll(PDO::FETCH_ASSOC);
		
		return self::$navigationItems;
	}
	
	
	public static function getPreparedNavigationItems()
	{
		self::getNavigationItems();
		
		foreach (self::$navigationItems as &$item) {
			if (self::isLink($item)) {
				$navbarItem = new NavbarLink;
				$navbarItem->id = $item['id'];
				$navbarItem = $navbarItem->getData();
				$item['type'] = 'Link';
			}
			else if (self::isMenu($item)) {
				$navbarItem = new NavbarSubmenu;
				$navbarItem->id = $item['id'];
				$navbarItem = $navbarItem->getData();
				$item['type'] = 'Submenu';
			}
			
			if ($item['parent_id'] > 0) {
				$item['parent'] = Submenu::select($item['parent_id'])['label'];
			}
			else {
				$item['parent'] = 'Navbar';
			}
			
			$item['url_edit'] = SUBMENUS . '/' . $navbarItem['slug'] . '/edit';
			$item['url_delete'] = SUBMENUS . '/' . $navbarItem['slug'] . '/delete';
			
			$item = array_merge($item, $navbarItem);
		}
		
		return self::$navigationItems;
	}
	
	
	public static function getSubmenus()
	{
		self::$submenus = Submenu::all()->fetchAll(PDO::FETCH_ASSOC);
		
		return self::$submenus;
	}
	
	
	public static function getNavigationTree()
	{
		$navigationTree = Navbar::baseLevel()
			->fetchAll(PDO::FETCH_ASSOC);
		
		foreach ($navigationTree as $item) {
			if (self::isLink($item)) {
				$navbarItem = new NavbarLink;
				$navbarItem->id = $item['id'];
			}
			else if (self::isMenu($item)) {
				$navbarItem = new NavbarSubmenu;
				$navbarItem->id = $item['id'];
				$navbarItem = $navbarItem->getData();
				$navbarItem['children'] = [];
				$children = Navbar::wholeLevel($item['submenu_id'])->fetchAll(PDO::FETCH_ASSOC);
				
				foreach ($children as $child) {
					if (self::isLink($child)) {
						$element = new NavbarLink;
						$element->id = $child['id'];
						$element = $element->getData();
					}
					else if (self::isMenu($child)) {
						$element = new NavbarSubmenu;
						$element->id = $child['id'];
						$element = $element->getData();
						
						$element['children'] = [];
						$grandChildren = Navbar::wholeLevel($child['submenu_id'])->fetchAll(PDO::FETCH_ASSOC);

						foreach ($grandChildren as $grandChild) {
							if (self::isLink($grandChild)) {
								$subElement = new NavbarLink;
								$subElement->id = $grandChild['id'];
								$subElement = $subElement->getData();
							}
							else if (self::isMenu($grandChild)) {
								$subElement = new NavbarSubmenu;
								$subElement->id = $grandChild['id'];
								$subElement = $subElement->getData();
							}

							array_push($element['children'], $subElement);
						}
					}
					
					array_push($navbarItem['children'], $element);
				}
			}
			
			array_push(self::$navigationTree, $navbarItem);
		}
	}
	
	
	public static function storeSubmenu($request)
	{
		if (self::isLink($item)) {
			
		}
		else if (self::isMenu($item)) {

		}
	}

	
	public static function view()
	{
		$navigationItems = self::$navigationTree;
		
		require_once(INCLUDES . '/navbar.php');
	}
}