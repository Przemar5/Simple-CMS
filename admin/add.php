<?php

session_start();

require_once('../app/paths.php');
require_once(CONTROLLERS_ROOT . '/PageController.php');
include_once(ERRORS);

$page = new PageController();
$page->add();