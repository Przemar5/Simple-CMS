<?php

if (!isset($_SESSION)) {
	session_start();
}

require_once(CONTROLLERS_ROOT . '/classes/NavbarItem.php');
require_once(MODELS_ROOT . '/Submenu.php');


class NavbarSubmenu extends NavbarItem
{
	public $label;
	public $children = [];
	
	
	public function __construct($id)
	{
		parent::__construct($id);
	}
	
	
	public function prepareSubmenuData()
	{
		$this->prepareValues();
		$data = Submenu::selectProperties($this->submenuId, ['label'])
			->fetch(PDO::FETCH_OBJ);
		$this->label = $data->label;
	}
	
	
	public function getSubmenuData()
	{
		$data = [
			'label' => $this->label,
		];
		
		return $data;
	}
	
	
	public function getData()
	{
		$this->prepareSubmenuData();
		$navbarItemData = $this->getNavbarItemData();
		$linkData = $this->getSubmenuData();
		$data = array_merge($navbarItemData, $linkData);
		
		return $data;
	}
	
	
	public function store($request)
	{
		$rules = [
			'label' => ['required', 'between:3,55', 'unique:navigation_submenus'],
			'parent_id' => ['required', 'max:11', 'numeric', 'exists_or:navigation_submenus,id,0'],
			'item_index' => ['required', 'max:11', 'numeric'],
		];
		$validator = new Validator;
		
		if ($validator->validate($request, $rules)) {
			if (Submenu::insert(['label' => $request['label']]) === 1) {
				$lastInsertId = Submenu::lastInsertId();
				$params = [
					'submenu_id' => $lastInsertId,
					'item_index' => $request['item_index'],
					'parent_id' => $request['parent_id'],
				];
				
				Connection::getInstance()
					->update('navigation_items')
					->set(['item_index' => 'item_index+1'])
					->where([['item_index', '>=', $request['item_index']]])
					->execute();
				
				//Navbar::changeParentItemIndexes($params['item_index'], $params['parent_id']);
					
				if (Navbar::insertSubmenu($params) === 1) {
					Navbar::normalizeIndexes($params['parent_id']);
					
					$_SESSION['last_action']['success'] = 'New submenu was created and added successfully.';
					
					header('Location: ' . NAVBAR_MANAGER);
					die();
				}
			}
		}
		
		$_SESSION['input_errors'] = $validator->errors;
		$_SESSION['submitted_data'] = $request;
		$_SESSION['last_action']['error'] = 'Error: new submenu wasn\'t created.';
		
		header('Location: ' . NAVBAR_MANAGER);
	}
	
	
	public function update($request)
	{
		echo "<pre>";
		print_r($request);
		
		$rules = [
			'label' => ['required', 'between:3,55', 'unique:navigation_submenus'],
			'parent_id' => ['required', 'max:11', 'numeric', 'exists_or:navigation_submenus,id,0'],
			'item_index' => ['required', 'max:11', 'numeric'],
		];
		$validator = new Validator;
		echo 'GOOD';
		die();
		
//		if ($validator->validate($request, $rules)) {
//			if (Submenu::insert(['label' => $request['label']]) === 1) {
//				$lastInsertId = Submenu::lastInsertId();
//				$params = [
//					'submenu_id' => $lastInsertId,
//					'item_index' => $request['item_index'],
//					'parent_id' => $request['parent_id'],
//				];
//					
//				if (Navbar::insertSubmenu($params) === 1) {
//					Navbar::changeItemIndexes($params['item_index'], $params['parent_id']) === 0;
//					Navbar::normalizeIndexes($params['parent_id']);
//					
//					$_SESSION['last_action']['success'] = 'New submenu was created and added successfully.';
//					
//					header('Location: ' . NAVBAR_MANAGER);
//				}
//			}
//		}
//		
//		$_SESSION['last_action']['error'] = 'Error: new submenu wasn\'t created.';
//		$_SESSION['input_errors'] = $validator->errors;
//		$_SESSION['submitted_data'] = $request;
		
		header('Location: ' . NAVBAR_MANAGER);
	}
	
	
	public function delete($request)
	{
		echo "<pre>";
		
		$id = $request['id'];
		
		if (is_numeric($id) && strlen($id) < 11) {
			$submenu = Navbar::select($id)->fetch(PDO::FETCH_ASSOC);
			
			if (Submenu::delete($submenu['submenu_id']) === 1) {
				if (Navbar::delete($id) === 1) {
					$db = Connection::getInstance();
					
					$sql = 'UPDATE navigation_items
							SET item_index = item_index-1
							WHERE parent_id = '.$submenu['parent_id'].'
								AND item_index > '.$submenu['item_index'];
					$db->query($sql);
					
					$sql = 'SELECT COUNT(*)
							FROM navigation_items
							WHERE parent_id = '.$submenu['parent_id'];
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$count = $stmt->fetch(PDO::FETCH_NUM)[0];
					
					Navbar::changeParentItemIndexes($submenu['parent_id'], $count - 1);
					
//					$sql = 'UPDATE navigation_items
//							SET item_index = item_index+'.($count-1).'
//							WHERE parent_id = '.$submenu['submenu_id'];
//					$db->query($sql);
					
					$sql = 'UPDATE navigation_items
							SET parent_id = '.$submenu['parent_id'].'
							WHERE parent_id = '.$submenu['submenu_id'];
					$db->query($sql);
					
//					
//					Connection::getInstance()
//						->update('navigation_items')
//						->set(['item_index' => 'item_index-1'])
//						->where([
//							['parent_id', '='. $submenu['parent_id']],
//							['item_index', '>', $submenu['item_index']]
//						])->execute();
//					// Get number of parent's items
//					$parentItemsCount = Connection::getInstance()
//						->count()
//						->from('navigation_items')
//						->where([['parent_id', '=', $submenu['parent_id']]])
//						->execute()
//						->fetchAll(PDO::FETCH_ASSOC);
//					
//					Connection::getInstance()
//						->update('navigation_items')
//						->set(['item_index' => 'item_index+'.$parentItemsCount])
//						->where([['parent_id', '=', $submenu['submenu_id']]])
//						->execute();
//					
//					Connection::getInstance()
//						->update('navigation_items')
//						->set(['parent_id' => $submenu['parent_id']])
//						->where([['parent_id', '=', $submenu['submenu_id']]])
//						->execute();
					
					
//					Navbar::normalizeIndexes($submenu['parent_id'], 1);
//					$parentElementsCount = Navbar::selectCount($submenu['parent_id']);
//					Navbar::changeItemIndexes(0, $submenu['submenu_id'], $parentElementsCount - 2);
//					Navbar::toggleParent($submenu['submenu_id'], $submenu['parent_id']);
//					Navbar::normalizeIndexes($submenu['parent_id'], 1);
					
					$_SESSION['last_action']['success'] = 'Submenu was removed successfully.';
					
					header('Location: ' . NAVBAR_MANAGER);
					die();
				}
			}
		}
		
		$_SESSION['last_action']['error'] = 'Submenu wasn\'t removed.';
		
		header('Location: ' . NAVBAR_MANAGER);
	}
	
	
	
//		$parent_id = $request['parent_id'];
//		$rules = [
//			'label' => ['required', 'between:3,55', 'unique:navigation_submenus'],
//		];
//		$validator = new Validator;
//		print_r($request);die();
//		
//		if ((is_numeric($parent_id) && strlen($parent_id) < 10) || empty($parent_id)) {
//			if ($validator->validate($request, $rules)) {
//				$param = ['label' => $request['label']];
//				
//				$level = Navbar::wholeLevel($request['parent_id'])->fetchAll(PDO::FETCH_ASSOC);
//				
//				if (self::normalizeItemIndexes($level) && empty($request['item_index'])) {
//					$request['item_index'] = sizeof($level) + 1;
//				}
//				else {
//					Navbar::increaseItemIndexes($request['item_index'], $request['parent_id']);
//				}
//				
//				if (Submenu::insert($param) === 1) {
//					// I already have no idea if using this variable is good idea.
//					// I wonder how would it work submitting many forms at the same time
//					$lastInsertId = Submenu::lastInsertId();
//					$params = [
//						'submenu_id' => $lastInsertId,
//						'item_index' => $request['item_index'],
//						'parent_id' => $request['parent_id']
//					];
//					
//					if (Navbar::insertSubmenu($params)) {
//						$_SESSION['last_action']['success'] = 'New submenu was created and added successfully.';
//					}
//				}
//				else {
//					$_SESSION['last_action']['error'] = 'Error: new submenu wasn\'t created.';
//				}
//			}
//		}
//		
//		$_SESSION['input_errors'] = $validator->errors;
//		$_SESSION['submitted_data'] = $request;
//		
//		header('Location: ' . NAVBAR_MANAGER);
}