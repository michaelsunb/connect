<?php
DEFINE("DEFAULT_START_LIMIT",0);
DEFINE("DEFAULT_END_LIMIT",30);
DEFINE("DEFAULT_TOTAL_LIMIT",30);

DEFINE("ADD_TO_LIMIT",30);

DEFINE("DEFAULT_ORDER_COLUMN",0);

DEFINE("TEN_MINUTES_IN_SEC",600);
DEFINE("SIXTY_MINUTES_IN_SEC",3600);

/** replace + (space) sign with \+ for preg_match */
$query_string = str_replace('+', '\+', $_SERVER["QUERY_STRING"]);
/** Remove any queries from the uri */
$destination = preg_replace('/\?'.$query_string.'/', "", $_SERVER['REQUEST_URI']);

/**
 * Split uri by / into an array to grab 
 * the Action for the URI and
 * removes last array into $matches
 */
$matches = explode("/",$destination);
$controllers = array_pop($matches);

/** create layout */
?>
<!DOCTYPE HTML>
<html>
<head><title>Michael Baluyos Assignment 1</title></head>
<body>
<? 
/** if URI is just / then redirect to index.html */
if(!isset($controllers) || $controllers == null)
{
	header('location:'.$_SERVER["ASSIGN_PATH"].'index.html');
	exit;
}
else
{
   /** remove shtml, htm, or html or php */
   $controller = preg_replace('/(.s?html?|.php)$/', "", $controllers);
}

/**
 * Check here if view script file name exist and
 * if action is alphabetical or 404
 */
$controller_path = 'controller_'.$controller.'.php';
if(!file_exists($controller_path) || !preg_match("/^([a-z]+|404)$/", $controller))
{
	header("HTTP/1.0 404 Not Found");
	header('location:'.$_SERVER["ASSIGN_PATH"].'404.shtml');
	exit;
}

require_once('model_region.php');
require_once('model_grapevariety.php');
require_once('model_wine.php');
require_once('model_winevariety.php');
require_once('model_orders.php');
require_once('model_customer.php');

/**
 * Call Controller filename
 */
require_once($controller_path);

/** Put _ in the beginning for 404 number */
$controller_class = '_'.$controller.'Controller';

/**
 * Call Controller class
 */
$controller_model = new $controller_class();
echo $controller_model->init();

?>

</body>
</html>

