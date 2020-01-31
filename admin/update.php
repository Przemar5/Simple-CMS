<?php

session_start();

require_once('../app/paths.php');
require_once(CONTROLLERS_ROOT . '/PageController.php');

$request = $_POST;
$page = new PageController();
$page->update($request);