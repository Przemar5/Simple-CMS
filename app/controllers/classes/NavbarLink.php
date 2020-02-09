<?php

require_once(CONTROLLERS_ROOT . '/classes/NavbarItem.php');
require_once(MODELS_ROOT . '/Page.php');


class NavbarLink extends NavbarItem
{
	public $label;
	public $slug;
	
	
	public function __construct($id)
	{
		parent::__construct($id);
	}
	
	
	public function prepareLinkData()
	{
		$this->prepareValues();
		$data = Page::selectProperties($this->pageId, ['label', 'slug'])
			->fetch(PDO::FETCH_OBJ);
		$this->label = $data->label;
		$this->slug = $data->slug;
	}
	
	
	public function getLinkData()
	{
		$data = [
			'label' => $this->label,
			'slug' => $this->slug,
		];
		
		return $data;
	}
	
	
	public function getData()
	{
		$this->prepareLinkData();
		$navbarItemData = $this->getNavbarItemData();
		$linkData = $this->getLinkData();
		$data = array_merge($navbarItemData, $linkData);
		
		return $data;
	}
}