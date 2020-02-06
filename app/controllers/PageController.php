<?php

if (!isset($_SESSION))
{
	session_start();
}

require_once(AUTH);
require_once(MODELS_ROOT . '/Page.php');
require_once('Validator.php');
//require_once('Sanitizer.php');


class PageController
{
    public static function show($slug)
    {
		$id = Page::idBySlug($slug);
		$data['page'] = Page::select($id)->fetch(PDO::FETCH_ASSOC);
		$data['label'] = APP_NAME . ' | ' . $data['page']['label'];

        require_once(VIEW['PAGE']);
    }
	

    public static function home()
    {
        $data['pages'] = Page::all()->fetchAll(PDO::FETCH_ASSOC);
		$data['label'] = APP_NAME . ' | Home';
		
		foreach ($data['pages'] as $key => $value) {
			$data['pages'][$key]['url_show'] = BASE_URL . '/' . $data['pages'][$key]['slug'];
		}
		
		$navbar = NavbarController::getInstance();
		$navbar::getItems();
		$navbar::view();
		
        require_once(VIEW['HOME']);
		
		unset($_SESSION['last_action']);
    }
	

    public static function contact()
    {
		$data['label'] = APP_NAME . ' | Contact us';
		
        require_once(VIEW['CONTACT']);
		
		unset($_SESSION['submitted_data']);
		unset($_SESSION['input_errors']);
    }
	

    public static function search($phrase)
    {
		$data['label'] = APP_NAME . ' | Search';
		
		$search = new SearchController;
		$pages = $search->search($phrase)->fetchAll(PDO::FETCH_ASSOC);
		$foundInfo = $search->getFoundInfo();
		
		foreach ($pages as $key => $value) {
			$pages[$key]['url_show'] = BASE_URL . '/' . $pages[$key]['slug'];
		}
		
        require_once(VIEW['SEARCH']);
    }
	

    public static function list()
    {
        $pages = Page::all()->fetchAll(PDO::FETCH_ASSOC);
		$data['label'] = APP_NAME . ' | Pages';
		$data['url_add'] = BASE_URL . '/add';

		foreach ($pages as $key => $value) {
			$pages[$key]['url_show'] = BASE_URL . '/' . $pages[$key]['slug'];
			$pages[$key]['url_edit'] = BASE_URL . '/' . $pages[$key]['slug'] . '/edit';
			$pages[$key]['url_delete'] = BASE_URL . '/' . $pages[$key]['slug'] . '/delete';
		}
		
		$navbar = NavbarController::getInstance();
		$navbar::getItems();
		$navbar::getSubmenus();
		$navbar::view();
		
        require_once(VIEW['LIST_PAGES']);
		
		unset($_SESSION['last_action']);
    }
	

    public static function navbarManager()
    {
		$data['label'] = APP_NAME . ' | Pages';
		$data['url_add'] = BASE_URL . '/add';
		
		NavbarController::navbar();
		$submenus = NavbarController::getSubmenus();
		$navbarItems = NavbarController::getAllItems();
		$navbarItems = NavbarController::prepareAsTable($navbarItems);
		$data['url_submenu_add'] = NAVBAR_MANAGER . '/submenus/store';
		$data['url_item_add'] = NAVBAR_MANAGER . '/items/store';
		
        require_once(VIEW['NAVBAR_MANAGER']);
		
		unset($_SESSION['last_action']);
    }
	

    public static function add()
    {
		$data['label'] = APP_NAME . ' | Add page';
		$data['url_store'] = BASE_URL . '/store';
		
        require_once(VIEW['ADD_PAGE']);
		
		unset($_SESSION['submitted_data']);
		unset($_SESSION['input_errors']);
    }
	

    public static function store($request)
    {
        $rules = [
            'title' => ['required', 'between:7,255', 'regex:[a-zA-Z0-9 _\/\*\+\-\#]', 'unique:pages'],
            'label' => ['required', 'between:7,70', 'regex:[a-zA-Z0-9 _\/\*\+\-\#]', 'unique:pages'],
            'slug' => ['required', 'between:3,255', 'regex:[a-zA-Z0-9-]', 'unique:pages'],
            'body' => ['required', 'min:3', /*'regex:[a-zA-Z0-9 _\/\*\+\-\#\n\r\<\>\,\.\?\:\;\(\)\[\]\"\'\^\%\@\!]'*/],
        ];
        $validator = new Validator;

        if ($validator->validate($request, $rules)) {
            if (Page::insert($request) === 1) {
                $_SESSION['last_action']['success'] = 'New page was created successfully.';
            }
            else {
                $_SESSION['last_action']['error'] = 'Error: new page wasn\'t created.';
            }

            header('Location: ' . BASE_URL . '/admin');
        }
        else {
			$_SESSION['input_errors'] = $validator->errors;
			$_SESSION['submitted_data'] = $request;

			header('Location: ' . BASE_URL . '/add');
		}
    }
	

    public static function edit($slug)
    {
		if (!empty($_SESSION['submitted_data'])) {
			$page = $_SESSION['submitted_data'];
		}
		else {
			$id = Page::idBySlug($slug);
			$page = Page::select($id)->fetch(PDO::FETCH_ASSOC);
		}
		
		$data['label'] = APP_NAME . ' | Edit ' . $page['label'];
		$data['url_update'] = BASE_URL . '/' . $page['slug'] . '/update';
		
		$navbarPages = Page::all();
		
        require_once(VIEW['EDIT_PAGE']);
		
		unset($_SESSION['submitted_data']);
		unset($_SESSION['input_errors']);
    }
	
	
    public static function update($request)
    {
		$id = $request['id'];
		
		if (is_numeric($id) && strlen($id) < 11) {
			$validator = new Validator;
			$rules = [
				'title' => ['required', 'between:7,255', 'regex:[a-zA-Z0-9 _\/\*\+\-\#]', 'unique:pages,'.$request['id']],
				'label' => ['required', 'between:7,70', 'regex:[a-zA-Z0-9 _\/\*\+\-\#]', 'unique:pages,'.$request['id']],
				'slug' => ['required', 'between:3,255', 'regex:[a-zA-Z0-9-]', 'unique:pages,'.$request['id']],
				'navbar_place' => ['numeric', 'max:7'],
				'body' => ['required', 'min:3', /*'regex:[a-zA-Z0-9 _\/\*\+\-\#\n\r\<\>\,\.\?\:\;\(\)\[\]\"\'\^\%\@\!]'*/],
			];

			if ($validator->validate($request, $rules)) {
				if(Page::update($request) === 1) {
					$_SESSION['last_action']['success'] = 'Page was edited successfully.';
				} else {
					$_SESSION['last_action']['error'] = 'Page wasn\'t edited.';
				}
				
				header('Location: ' . BASE_URL . '/admin');
			}
			else {
				$_SESSION['input_errors'] = $validator->errors;
				$_SESSION['submitted_data'] = $request;
				$slug = Page::slugById($id);

				header('Location: ' . BASE_URL . '/' . $slug . '/edit');
			}
		}
		else {
			$_SESSION['last_action']['error'] = 'Error: page wasn\'t edited.';

			header('Location: ' . BASE_URL . '/admin');
		}
    }

	
    public static function delete($request)
    {
		$id = $request['id'];
		
		if (is_numeric($id) && strlen($id) < 11) {
			if (Page::delete($id) === 1) {
				$_SESSION['last_action']['success'] = 'Page was removed successfully.';
			}
			else {
				$_SESSION['last_action']['error'] = 'Page wasn\'t removed.';
			}
		
			header('Location: ' . BASE_URL . '/admin');
		}
		else {
			$_SESSION['last_action']['error'] = 'Page wasn\'t removed.';
		
			header('Location: ' . BASE_URL . '/admin');
		}
    }
}
