<?php

require_once(MODELS_ROOT . '/Navbar.php');
require_once('Validator.php');


class NavbarController
{
	private static $instance = null;
	public static $items = null;
	public static $submenus = null;
	
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
		$navbarItems = self::$items;
		$navbarSubmenus = self::$submenus;
		
		require_once(NAVBAR);
	}
	
	public static function addItem()
	{
		
	}
	
	public static function addSubmenu($request)
	{
		$rules = [
			'label' => ['required', 'between:3,55'],
		];
		$validator = new Validator;
		
		if ($validator->validate($request, $rules)) {
			echo 'Passed';
		}
		else {
			echo 'Not passed';
		}
		
		
		
		print_r($request);
	}
	
	public static function getChildren()
	{
		
	}
}