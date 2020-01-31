<?php

require_once('app/paths.php');
require_once(CONTROLLERS_ROOT . '/PageController.php');

$slug = $_GET['page'];
$page = new PageController();
$page->show($slug);