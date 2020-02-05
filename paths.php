<?php


define('BASE_URL', 'http://localhost/files/Projects/SimpleCMS');
define('APP_ROOT', __DIR__);

define('NAMES', APP_ROOT . '/names.php');

define('MODELS_ROOT', APP_ROOT . '/app/models');
define('MODEL', APP_ROOT . '/app/models/Model.php');
define('VIEW_ROOT', APP_ROOT . '/app/views');
define('NAVBAR', VIEW_ROOT . '/includes/navbar.php');
define('ACTION_FEEDBACK', VIEW_ROOT . '/includes/action_feedback.php');
define('HEADER', VIEW_ROOT . '/templates/header.php');
define('FOOTER', VIEW_ROOT . '/templates/footer.php');
define('CONTROLLERS_ROOT', APP_ROOT . '/app/controllers');
define('ROUTES', APP_ROOT . '/routes.php');
define('ADMIN_PANEL', BASE_URL . '/admin');
define('NAVBAR_MANAGER', BASE_URL . '/navbar');

define('CONNECTION', APP_ROOT . '/app/connect/Connection.php');
define('AUTH', APP_ROOT . '/app/controllers/AuthController.php');
define('ERRORS', APP_ROOT . '/app/includes/errors.php');
define('FILL_INPUT', APP_ROOT . '/app/includes/submitted_data.php');

define('HOME', BASE_URL);
define('CONTACT', BASE_URL . '/contact');
define('SEND', BASE_URL . '/send');
define('SEARCH', BASE_URL . '/search');

define('STYLES', BASE_URL . '/css/style.css');

define('VIEW', [
	'HOME'				=>	APP_ROOT . '/app/views/pages/home.php',
	'CONTACT'			=>	APP_ROOT . '/app/views/pages/contact.php',
	'PAGE'				=>	APP_ROOT . '/app/views/pages/page.php',
	'SEARCH'			=>	APP_ROOT . '/app/views/pages/search.php',
	'LIST_PAGES'		=>	APP_ROOT . '/app/views/admin/list.php',
	'NAVBAR_MANAGER'	=>	APP_ROOT . '/app/views/admin/navbar_manager.php',
	'ADD_PAGE'			=>	APP_ROOT . '/app/views/admin/add.php',
	'EDIT_PAGE'			=>	APP_ROOT . '/app/views/admin/edit.php',
	'CONTACT_MAIL'		=>	APP_ROOT . '/app/views/emails/contact.php',
]);

define('NAVBAR_ITEMS', [
	'Home'		=>	HOME,
	'About'		=>	HOME,
	'Contact'	=>	CONTACT
]);