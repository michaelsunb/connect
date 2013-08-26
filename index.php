<?php
DEFINE("DEFAULT_START_LIMIT",0);
DEFINE("DEFAULT_END_LIMIT",30);
DEFINE("DEFAULT_TOTAL_LIMIT",30);

DEFINE("ADD_TO_LIMIT",30);

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
$actions = array_pop($matches);

/** create layout */
?>
<!DOCTYPE HTML>
<html>
<head><title>Michael Baluyos Assignment 1</title></head>
<body>
<? 
/** if URI is just / then redirect to index.html */
if(!isset($actions) || $actions == null)
{
	header('location:'.$_SERVER["ASSIGN_PATH"].'index.html');
	exit;
}
else
{
   /** remove shtml, htm, or html or action */
   $action = preg_replace('/(.s?html?|.php)$/', "", $actions);
}

/**
 * Check here if view script file name exist and
 * if action is alphabetical or 404
 */
$view_script_path = 'view_'.$action.'.php';
if(!file_exists($view_script_path) || !preg_match("/^([a-z]+|404)$/", $action))
{
	header("HTTP/1.0 404 Not Found");
	header('location:'.$_SERVER["ASSIGN_PATH"].'404.shtml');
	exit;
}

/**
 * Call Controller class and echo action
 */
require_once('controller.php');
$controller = new Controller();
echo $controller->init($action, $view_script_path);

?>

</body>
</html>

