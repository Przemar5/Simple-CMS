<?php


require_once(CONTROLLERS_ROOT . '/Route.php');
require_once(CONTROLLERS_ROOT . '/PageController.php');
require_once(CONTROLLERS_ROOT . '/NavbarController.php');
require_once(CONTROLLERS_ROOT . '/ContactMailController.php');
require_once(CONTROLLERS_ROOT . '/SearchController.php');

//print_r($_GET);
//die();

Route::set('home', function() {
	PageController::home();
});

Route::set('contact', function() {
	PageController::contact();
});

Route::set('search', function() {
	PageController::search($_GET['phrase']);
});

Route::set('send', function() {
	ContactMailController::send($_POST);
});

Route::set('list', function() {
	PageController::list();
});

Route::set('navbar', function() {
	PageController::navbarManager();
});

Route::set('navbar/submenus', function() {
	switch ($_GET['action']) {
		case 'store':	NavbarController::storeSubmenu($_POST);		break;
		case 'update':	NavbarController::updateSubmenu($_POST);	break;
		case 'delete':	NavbarController::deleteSubmenu($_POST);	break;
	}
});

Route::set('add', function() {
	PageController::add();
});

Route::set('store', function() {
	PageController::store($_POST);
});

Route::set('edit', function() {
	PageController::edit($_GET['page']);
});

Route::set('update', function() {
	PageController::update($_POST);
});

Route::set('delete', function() {
	PageController::delete($_POST);
});

Route::set('show', function() {
	PageController::show($_GET['page']);
});