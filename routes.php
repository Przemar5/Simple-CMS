<?php


require_once(CONTROLLERS_ROOT . '/Route.php');
require_once(CONTROLLERS_ROOT . '/PageController.php');


Route::set('home', function() {
	PageController::home();
});

Route::set('list', function() {
	PageController::list();
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