<?php

require_once(MODELS_ROOT . '/Submenu.php');


class SubmenuController extends NavbarController2
{
	public $properties;
	
	
	public function __construct($properties)
	{
		$this->properties['id'] = $properties['submenu_id'];
		$this->properties['parent_id'] = $properties['parent_id'];
		$this->properties['item_index'] = $properties['item_index'];
	}
	
	
	public function getData()
	{
		$data = Submenu::select($this->properties['id'])->fetch(PDO::FETCH_ASSOC);
		$this->properties['label'] = $data['label'];
		
		return $this;
	}
	
	
	public function getChildren()
	{
		$this->properties['children'] = Navbar::selectByParent($this->properties['id'])
			->fetchAll(PDO::FETCH_ASSOC);
		
		return $this;
	}
	
	
	public function parseData()
	{
		$itemsNum = sizeof($this->properties['children']);
		
		for ($i = 0; $i < $itemsNum; $i++) {
			$item = $this->properties['children'][$i];
			
			if ($item['parent_id'] == 0) {
				if (self::isLink($item)) {
					echo 'IS LINK';
				}
				else if (self::isSubmenu($item)) {
					$item = new SubmenuController($item);
					$item = $item->getData();

					echo "<pre>";
					print_r($item);
				}
			}
		}
		
		echo "<pre>";
		print_r(self::$navbarItems);
		die();
	}
}
