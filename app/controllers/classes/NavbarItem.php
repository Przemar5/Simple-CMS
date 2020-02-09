<?php

if (!isset($_SESSION)) {
	session_start();
}

require_once(MODELS_ROOT . '/Navbar.php');
require_once(CONTROLLERS_ROOT . '/Validator.php');


class NavbarItem
{
	public $id;
	public $pageId;
	public $submenuId;
	public $itemIndex;
	public $parentId;
	
	
	public function __construct($id = null)
	{
		$this->id = $id;
	}
	
	
	public function prepareValues()
	{
		$data = Navbar::select($this->id)->fetch(PDO::FETCH_ASSOC);
		
		$this->pageId = $data['page_id'];
		$this->submenuId = $data['submenu_id'];
		$this->parentId = $data['parent_id'];
		$this->itemIndex = $data['item_index'];
	}
	
	
	public function cloneValues($data)
	{
		$this->pageId = $data['page_id'];
		$this->submenuId = $data['submenu_id'];
		$this->parentId = $data['parent_id'];
		$this->itemIndex = $data['item_index'];
	}
	
	
	public function getNavbarItemData()
	{
		return [
			'id' => $this->id,
			'page_id' => $this->pageId,
			'submenu_id' => $this->submenuId,
			'parent_id' => $this->parentId,
			'item_index' => $this->itemIndex,
		];
	}
}