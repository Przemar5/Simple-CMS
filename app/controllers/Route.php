<?php


class Route
{
	public static $validRoutes = [];
	
	public static function set($route, $function)
	{
		//$slug = substr($_GET['url'], strrpos($url, '/') + 1);
		
		//$url = explode('/', $_GET['url']);
		
		self::$validRoutes[] = $route;
		
		//echo $_GET['url'] . '<br>';
		
		if ($_GET['url'] === $route)
		{
			$function->__invoke();
		}
	}
}