<?php

session_start();

require_once('../app/paths.php');
require_once(CONTROLLERS_ROOT . '/PageController.php');

$page = new PageController();
$page->list();