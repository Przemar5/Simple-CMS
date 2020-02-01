<?php

if (!isset($_SESSION)) {
	session_start();
}

require_once(AUTH);
require_once(MODELS_ROOT . '/Page2.php');
require_once('Validator.php');
//require_once('Sanitizer.php');


class PageController2
{
	const APP_NAME = 'MyCMS';
	
    public static function show($slug)
    {
		$id = $this->pageModel->idBySlug($slug);
		$data = $this->pageModel->select($id)->fetch(PDO::FETCH_ASSOC);
		$page['label'] = self::APP_NAME . ' | ' . $data['label'];

        require_once(VIEW['PAGE']);
    }
	

    public static function home()
    {
        $data = $this->pageModel->all()->fetchAll(PDO::FETCH_ASSOC);
		$page['label'] = self::APP_NAME . ' | Home';
		
        require_once(VIEW['HOME']);
    }
	

    public static function list()
    {
        $data = Page2::all()->fetchAll(PDO::FETCH_ASSOC);
		$page['label'] = self::APP_NAME . ' | Pages';

        require_once(VIEW['LIST_PAGES']);
		
		unset($_SESSION['last_action']);
    }
	

    public static function add()
    {
		$data = (!empty($_SESSION['submitted_data'])) ? $_SESSION['submitted_data'] : null;
		$errors = (!empty($_SESSION['input_errors'])) ? $_SESSION['input_errors'] : null;
		$page['label'] = self::APP_NAME . ' | Add page';
		
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
            if (Page2::insert($request) === 1) {
                $_SESSION['last_action']['success'] = 'New page was created successfully.';
            }
            else {
                $_SESSION['last_action']['error'] = 'Error: new page wasn\'t created.';
            }

            header('Location: ' . URL['LIST_PAGES']);
        }
        else {
			$_SESSION['input_errors'] = $validator->errors;
			$_SESSION['submitted_data'] = $request;

			header('Location: ' . URL['ADD_PAGE']);
		}
    }
	

    public static function edit($slug)
    {
		if (!empty($_SESSION['submitted_data'])) {
			$data = $_SESSION['submitted_data'];
		}
		else {
			$id = Page2::idBySlug($slug);
			$data = Page2::select($id)->fetch(PDO::FETCH_ASSOC);
		}
		
		$errors = (!empty($_SESSION['input_errors'])) ? $_SESSION['input_errors'] : null;
		$page['label'] = self::APP_NAME . ' | Edit ' . $data['label'];
		
        require_once(VIEW['EDIT_PAGE']);
		
		unset($_SESSION['submitted_data']);
		unset($_SESSION['input_errors']);
    }
	
	
    public static function update($request)
    {
		if (is_numeric($request['id'])) {
			$validator = new Validator;
			$rules = [
				'title' => ['required', 'between:7,255', 'regex:[a-zA-Z0-9 _\/\*\+\-\#]', 'unique:pages,'.$request['id']],
				'label' => ['required', 'between:7,70', 'regex:[a-zA-Z0-9 _\/\*\+\-\#]', 'unique:pages,'.$request['id']],
				'slug' => ['required', 'between:3,255', 'regex:[a-zA-Z0-9-]', 'unique:pages,'.$request['id']],
				'body' => ['required', 'min:3', /*'regex:[a-zA-Z0-9 _\/\*\+\-\#\n\r\<\>\,\.\?\:\;\(\)\[\]\"\'\^\%\@\!]'*/],
			];

			if ($validator->validate($request, $rules)) {
				if(Page2::update($request) === 1) {
					$_SESSION['last_action']['success'] = 'Page was edited successfully.';
				} else {
					$_SESSION['last_action']['error'] = 'Page wasn\'t edited.';
				}
				
				header('Location: ' . URL['LIST_PAGES']);
			}
			else {
				$_SESSION['input_errors'] = $validator->errors;
				$_SESSION['submitted_data'] = $request;
				$slug = $this->pageModel->slugById($request['id']);

				header('Location: ' . URL['EDIT_PAGE'] . '/' . $slug);
			}
		}
		else {
			$_SESSION['last_action']['error'] = 'Error: page wasn\'t edited.';

			header('Location: ' . URL['LIST_PAGES']);
		}
    }

	
    public static function delete($request)
    {
		$id = $request['id'];
		
		if (is_numeric($id) && strlen($id) < 11) {
			if (Page2::delete($id) === 1) {
				$_SESSION['last_action']['success'] = 'Page was removed successfully.';
			}
			else {
				$_SESSION['last_action']['error'] = 'Page wasn\'t removed.';
			}
		
			header('Location: ' . URL['LIST_PAGES']);
		}
		else {
			$_SESSION['last_action']['error'] = 'Page wasn\'t removed.';
		
			header('Location: ' . URL['LIST_PAGES']);
		}
    }
}
