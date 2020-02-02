<?php

session_start();

require_once('../paths.php');
require_once(NAMES);
include_once(ERRORS);


$url = $_GET['url'];
$page = isset($_GET['page']) ? $_GET['page'] : '';


require_once(ROUTES);
