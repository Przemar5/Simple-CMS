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
require_once('SubmenuController.php');
require_once('NavbarLinkController.php');

class NavbarController2
{
	private static $instance = null;
	public static $navbarItems = [];
	public static $navigationTree = [];
	
	
	public static function getInstance()
	{
		if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
	}
	
	
	public static function isNotEmpty($item)
	{
		return !empty($item) && $item != null;
	}
	
	
	public static function getRawData()
	{
		self::$navbarItems = Navbar::all()->fetchAll(PDO::FETCH_ASSOC);
		
		return self::$instance;
	}
	
	
	public static function filterBaseLevel()
	{
		if (empty(self::$navigationTree)) {
			foreach (self::$navbarItems as $item) {
				if ($item['parent_id'] == 0) {
					array_push(self::$navigationTree, $item);
				}
			}
		}
		
		return self::$instance;
	}
	
	
	public static function createNavigationTree()
	{
		echo "<pre>";		
		
		$itemsNum = sizeof(self::$navigationTree);
		
		for ($i = 0; $i < $itemsNum; $i++) {
			$item = self::$navigationTree[$i];
			
			if ($item['parent_id'] == 0) {
				if (self::isLink($item)) {
					echo 'IS LINK';
				}
				else if (self::isSubmenu($item)) {
					$item = new SubmenuController($item);
					$item = $item->getData()
						->getChildren();
					
					//self::navigationTree[$i]['children'] = $item;

					print_r($item);
				}
			}
		}
		
		print_r(self::$navigationTree);
	}
	
	/*
	public static function parseData()
	{
		$itemsNum = sizeof(self::$navigationTree);
		
		for ($i = 0; $i < $itemsNum; $i++) {
			$item = self::$navbarItems[$i];
			
			if ($item['parent_id'] == 0) {
				if (self::isLink($item)) {
					echo 'IS LINK';
				}
				else if (self::isSubmenu($item)) {
					$item = new SubmenuController($item);
					//array_push(self::navigationTree, $item->getData());

					echo "<pre>";
					print_r($item);
				}
			}
		}
		
		echo "<pre>";
		print_r(self::$navigationTree);
		die();
	}
	*/
	
	public static function isLink($item)
	{
		return !empty($item['page_id']);
	}
	
	
	public static function isSubmenu($item)
	{
		return !empty($item['submenu_id']);
	}
}