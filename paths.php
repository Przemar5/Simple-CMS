<?php

define('BASE_URL', 'http://localhost/files/Projects/SimpleCMS');
define('APP_ROOT', __DIR__);

define('MODELS_ROOT', APP_ROOT . '/app/models');
define('VIEW_ROOT', APP_ROOT . '/app/views');
define('HEADER', VIEW_ROOT . '/templates/header.php');
define('FOOTER', VIEW_ROOT . '/templates/footer.php');
define('CONTROLLERS_ROOT', APP_ROOT . '/app/controllers');
define('ROUTES', APP_ROOT . '/routes.php');

define('CONNECTION', APP_ROOT . '/app/connect/Connection.php');
define('AUTH', APP_ROOT . '/app/controllers/AuthController.php');
define('ERRORS', APP_ROOT . '/app/includes/errors.php');

define('ADMIN_PANEL', BASE_URL . '/admin');


define('VIEW', [
	'HOME'			=>	APP_ROOT . '/app/views/home.php',
	'PAGE'			=>	APP_ROOT . '/app/views/page.php',
	'LIST_PAGES'	=>	APP_ROOT . '/app/views/admin/list.php',
	'ADD_PAGE'		=>	APP_ROOT . '/app/views/admin/add.php',
	'EDIT_PAGE'		=>	APP_ROOT . '/app/views/admin/edit.php',
]);