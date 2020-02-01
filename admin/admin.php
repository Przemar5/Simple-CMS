<?php

session_start();

require_once('../paths.php');
require_once(CONTROLLERS_ROOT . '/PageController.php');
include_once(ERRORS);

$page = new PageController();
$url = explode('/', $_GET['url']);
$slug;

//echo $_GET['url'] . '<br>';
//print_r($url);
//echo '<br>';

if (sizeof($url) === 2) 
{
	list($url, $slug) = $url;
}
else {
	$url = $url[0];
}

require_once(ROUTES);


/*
switch (true)
{
	case ($url === 'list'):
		$page->list();
		break;
		
	case ($url === 'add'):
		$page->add();
		break;
		
	case ($url === 'store'):
		$page->store($_POST);
		break;
		
	case ($url === 'edit'):
		$page->edit($slug);
		break;
}
*/
echo 'good';