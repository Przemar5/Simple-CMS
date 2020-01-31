<?php

session_start();

require_once('../app/paths.php');
require_once(CONTROLLERS_ROOT . '/PageController.php');
include_once(ERRORS);

$slug = $_GET['page'];
$page = new PageController();
$page->edit($slug);