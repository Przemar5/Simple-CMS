<?php


require_once(CONTROLLERS_ROOT . '/Route.php');
require_once(CONTROLLERS_ROOT . '/PageController2.php');


Route::set('list', function() {
	PageController2::list();
});

Route::set('add', function() {
	PageController2::add();
});

Route::set('store', function() {
	PageController2::store($_POST);
});

Route::set('edit', function() {
	PageController2::edit();
});

Route::set('update', function() {
	PageController2::update($_POST);
});

Route::set('delete', function() {
	PageController2::delete($_POST);
});