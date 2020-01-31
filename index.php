<?php

require_once('app/paths.php');
require_once(CONTROLLERS_ROOT . '/PageController.php');

$page = new PageController();
$page->home();