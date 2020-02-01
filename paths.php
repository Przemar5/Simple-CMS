<?php

define('BASE_URL', 'http://localhost/files/Projects/SimpleCMS');
define('APP_ROOT', __DIR__);

define('MODELS_ROOT', APP_ROOT . '/app/models');
define('VIEW_ROOT', APP_ROOT . '/app/views');
define('CONTROLLERS_ROOT', APP_ROOT . '/app/controllers');
define('ROUTES', APP_ROOT . '/app/routes.php');

define('CONNECTION', APP_ROOT . '/app/connect/Connection.php');
define('AUTH', APP_ROOT . '/app/controllers/AuthController.php');
define('ERRORS', APP_ROOT . '/app/includes/errors.php');


define('URL', [
	'LIST_PAGES' 	=> 	BASE_URL . '/admin/list',
	'ADD_PAGE' 		=> 	BASE_URL . '/admin/add',
	'STORE_PAGE'	=> 	BASE_URL . '/admin/store',
	'EDIT_PAGE'		=>	BASE_URL . '/admin/edit',
	'UPDATE_PAGE'	=> 	BASE_URL . '/admin/update',
	'DELETE_PAGE'	=> 	BASE_URL . '/admin/delete',
]);

define('VIEW', [
	'HOME'			=>	APP_ROOT . '/app/views/home.php',
	'PAGE'			=>	APP_ROOT . '/app/views/page.php',
	'LIST_PAGES'	=>	APP_ROOT . '/app/views/admin/list.php',
	'ADD_PAGE'		=>	APP_ROOT . '/app/views/admin/add.php',
	'EDIT_PAGE'		=>	APP_ROOT . '/app/views/admin/edit.php',
]);