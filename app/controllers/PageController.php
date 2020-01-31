<?php

if (!isset($_SESSION)) {
	session_start();
}

require_once(AUTH);
require_once(MODELS_ROOT . '/Page.php');
require_once('Validator.php');
//require_once('Sanitizer.php');


class PageController
{
	const APP_NAME = 'MyCMS';
    private $pageModel;

	
    public function __construct()
    {
		$this->pageModel = new Page;
    }

	
    public function show($slug)
    {
		$id = $this->pageModel->idBySlug($slug);
		$data = $this->pageModel->select($id)->fetch(PDO::FETCH_ASSOC);
		$page['label'] = self::APP_NAME . ' | ' . $data['label'];

        require_once(VIEW_ROOT . '/page.php');
    }
	

    public function home()
    {
        $data = $this->pageModel->all()->fetchAll(PDO::FETCH_ASSOC);
		$page['label'] = self::APP_NAME . ' | Home';
		
        require_once(VIEW_ROOT . '/home.php');
    }
	

    public function list()
    {
        $data = $this->pageModel->all()->fetchAll(PDO::FETCH_ASSOC);
		$page['label'] = self::APP_NAME . ' | Pages';

        require_once(VIEW_ROOT . '/admin/list.php');
		
		unset($_SESSION['last_action']);
    }
	

    public function add()
    {
		$data = (!empty($_SESSION['submitted_data'])) ? $_SESSION['submitted_data'] : null;
		$errors = (!empty($_SESSION['input_errors'])) ? $_SESSION['input_errors'] : null;
		$page['label'] = self::APP_NAME . ' | Add page';
		
        require_once(VIEW_ROOT . '/admin/add.php');
		
		unset($_SESSION['submitted_data']);
		unset($_SESSION['input_errors']);
    }
	

    public function store($request)
    {
        $rules = [
            'title' => ['required', 'between:7,255', 'regex:[a-zA-Z0-9 _\/\*\+\-\#]', 'unique:pages'],
            'label' => ['required', 'between:7,70', 'regex:[a-zA-Z0-9 _\/\*\+\-\#]', 'unique:pages'],
            'slug' => ['required', 'between:3,255', 'regex:[a-zA-Z0-9-]', 'unique:pages'],
            'body' => ['required', 'min:3', /*'regex:[a-zA-Z0-9 _\/\*\+\-\#\n\r\<\>\,\.\?\:\;\(\)\[\]\"\'\^\%\@\!]'*/],
        ];
        $validator = new Validator;

        if ($validator->validate($request, $rules)) {
            if ($this->pageModel->insert($request) === 1) {
                $_SESSION['last_action']['success'] = 'New page was created successfully.';
            }
            else {
                $_SESSION['last_action']['error'] = 'Error: new page wasn\'t created.';
            }

            header('Location: ' . BASE_URL . '/admin/list.php');
        }
        else {
			$_SESSION['input_errors'] = $validator->errors;
			$_SESSION['submitted_data'] = $request;

			header('Location: ' . BASE_URL . '/admin/add.php');
		}
    }
	

    public function edit($slug)
    {
		if (!empty($_SESSION['submitted_data'])) {
			$data = $_SESSION['submitted_data'];
		}
		else {
			$id = $this->pageModel->idBySlug($slug);
			$data = $this->pageModel->select($id)->fetch(PDO::FETCH_ASSOC);
		}
		
		$errors = (!empty($_SESSION['input_errors'])) ? $_SESSION['input_errors'] : null;
		$page['label'] = self::APP_NAME . ' | Edit ' . $data['label'];
		
        require_once(VIEW_ROOT . '/admin/edit.php');
		
		unset($_SESSION['submitted_data']);
		unset($_SESSION['input_errors']);
    }
	
	
    public function update($request)
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
				if($this->pageModel->update($request) === 1) {
					$_SESSION['last_action']['success'] = 'Page was edited successfully.';
				} else {
					$_SESSION['last_action']['error'] = 'Page wasn\'t edited.';
				}
				
				header('Location: ' . BASE_URL . '/admin/list.php');
			}
			else {
				$_SESSION['input_errors'] = $validator->errors;
				$_SESSION['submitted_data'] = $request;
				$slug = $this->pageModel->slugById($request['id']);

				header('Location: ' . BASE_URL . '/admin/edit.php?page=' . $slug);
			}
		}
		else {
			$_SESSION['last_action']['error'] = 'Error: page wasn\'t edited.';

			header('Location: ' . BASE_URL . '/admin/list.php');
		}
    }

	
    public function delete($slug)
    {
        $id = $this->pageModel->idBySlug($slug);
		
		if ($this->pageModel->delete($id) === 1) {
			$_SESSION['last_action']['success'] = 'Page was removed successfully.';
		}
		else {
			$_SESSION['last_action']['error'] = 'Page wasn\'t removed.';
		}

		header('Location: ' . BASE_URL . '/admin/list.php');
    }
}
