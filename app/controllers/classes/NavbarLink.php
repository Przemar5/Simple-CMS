<?php

require_once(CONTROLLERS_ROOT . '/classes/NavbarItem.php');
require_once(MODELS_ROOT . '/Page.php');


class NavbarLink extends NavbarItem
{
	public $label;
	public $slug;
	
	
	public function __construct()
	{
		//
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
	
	
	public function store($request)
	{
		$rules = [
			'id'
			'parent_id' => ['required', 'max:11', 'numeric', 'exists_or:navigation_submenus,id,0'],
			'item_index' => ['required', 'max:11', 'numeric'],
		];
		$validator = new Validator;
		
		if ($validator->validate($request, $rules)) {
			$params = [
				'label' => $request['label'],
				'slug' => $request['slug']
			];
			
			if (Submenu::insert($params) === 1) {
				$lastInsertId = Submenu::lastInsertId();
				$params = [
					'submenu_id' => $lastInsertId,
					'item_index' => $request['item_index'],
					'parent_id' => $request['parent_id'],
				];
				
				Connection::getInstance()
					->update('navigation_items')
					->set(['item_index' => 'item_index+1'])
					->where([
						['item_index', '>=', $request['item_index']],
						['parent_id', '=', $request['parent_id']]
					])
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
		
		unset($_SESSION['submitted_data']);
		unset($_SESSION['input_errors']);
	}
	
	
	public static function edit($slug)
	{
		$submenu;
		if (!empty($_SESSION['submitted_data'])) {
			$submenu = $_SESSION['submitted_data'];
		}
		else {
			$submenu = Submenu::selectBySlug($slug);
			$submenu['submenu_id'] = $submenu['id'];
		}
		
		$navbarParams = Navbar::selectBy(['submenu_id' => $submenu['submenu_id']])
			->fetch(PDO::FETCH_ASSOC);
		
		$submenu = array_merge($submenu, $navbarParams);
		$submenus = Submenu::all()->fetchAll(PDO::FETCH_ASSOC);
		
		$data['label'] = APP_NAME . ' | Edit ' . $submenu['label'];
		$data['url_update'] = SUBMENUS . '/' . $submenu['slug'] . '/update';
		$edit = VIEW['SUBMENUS'] . '/edit.php';
		
		require_once($edit);
		
		unset($_SESSION['submitted_data']);
		unset($_SESSION['input_errors']);
	}
	
	
	public function update($request)
	{
		$id = $request['submenu_id'];
		
		if (!empty($id) && is_numeric($id)) {
			$rules = [
				'label' => ['required', 'between:3,55', /*'unique:navigation_submenus,label,'.$id*/],
				'slug' => ['required', 'between:3,55', /*'unique:navigation_submenus,slug,'.$id*/],
				'parent_id' => ['required', 'max:11', 'numeric', 'exists_or:navigation_submenus,id,0'],
				'item_index' => ['required', 'max:11', 'numeric'],
			];
			$validator = new Validator;

			if ($validator->validate($request, $rules)) {
				$params = [
					'id' => $request['submenu_id'],
					'label' => $request['label'],
					'slug' => $request['slug'],
				];
				$currentData = Navbar::selectBy(['submenu_id' => $id])->fetch(PDO::FETCH_ASSOC);
				
				$db = Connection::getInstance();
				$sql = 'UPDATE navigation_submenus
						SET label = :label,
							slug = :slug
						WHERE id = :id';
				$stmt = $db->prepare($sql);
				$stmt->execute($params);
				
				$params = [
					'submenu_id' => $request['submenu_id'],
					'item_index' => $request['item_index'],
					'parent_id' => $request['parent_id'],
				];
				
				$sql = 'UPDATE navigation_items
						SET item_index = item_index+1
						WHERE parent_id = :parent_id
							AND item_index >= :item_index';
				$stmt = $db->prepare($sql);
				$stmt->execute($params);

				$sql = 'UPDATE navigation_items
						SET parent_id = :parent_id,
							item_index = :item_index
						WHERE submenu_id = :submenu_id';
				$stmt = $db->prepare($sql);
				$stmt->execute($params);
				
				$params = [
					'item_index' => $request['item_index'] - 1,
					'parent_id' => $request['parent_id'],
				];

				Navbar::normalizeIndexes($currentData['parent_id']);

				$_SESSION['last_action']['success'] = 'Submenu was updated successfully.';
				header('Location: ' . NAVBAR_MANAGER);
				die();
			}
		}
		
		$_SESSION['last_action']['error'] = 'Error: Submenu wasn\'t created.';
		$_SESSION['input_errors'] = $validator->errors;
		$_SESSION['submitted_data'] = $request;
		
		$slug = Submenu::select($id)['slug'];
		$path = SUBMENUS . '/' . $slug . '/edit';
		
		header('Location: ' . $path);
	}
	
	
	public function delete($request)
	{
		$id = $request['id'];
		
		if (!empty($id) && is_numeric($id) && strlen($id) < 11) {
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
					
//					Navbar::changeParentItemIndexes($submenu['parent_id'], $count - 1);
					
					$sql = 'UPDATE navigation_items
							SET item_index = item_index+'.($count).'
							WHERE parent_id = '.$submenu['submenu_id'];
					$db->query($sql);
					
					$sql = 'UPDATE navigation_items
							SET parent_id = '.$submenu['parent_id'].'
							WHERE parent_id = '.$submenu['submenu_id'];
					$db->query($sql);
					
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
	}
}