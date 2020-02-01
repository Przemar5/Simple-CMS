<?php

session_start();

require_once('paths.php');
include_once(ERRORS);


$page = isset($_GET['page']) ? $_GET['page'] : '';


require_once(ROUTES);
