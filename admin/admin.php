<?php

session_start();

require_once('../paths.php');
require_once(NAMES);
include_once(ERRORS);
include_once(FILL_INPUT);


$url = $_GET['url'];
$page = isset($_GET['page']) ? $_GET['page'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';


require_once(ROUTES);
