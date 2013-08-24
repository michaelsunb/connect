<?php
require_once('controller.php');

DEFINE("DEFAULT_START_LIMIT",0);
DEFINE("DEFAULT_END_LIMIT",30);

DEFINE("ADD_TO_LIMIT",30);
DEFINE("ACTION_URI",2);

// Remove any queries from the uri
$destination = preg_replace('/\?'.$_SERVER["QUERY_STRING"].'/', "", $_SERVER['REQUEST_URI']);

// Split uri by / into an array
$matches = explode("/",$destination);
?>
<!DOCTYPE HTML>
<html>
<head><title>Michael Baluyos Assignment 1</title></head>
<body>

<? 

if(!isset($matches[ACTION_URI]) || $matches[ACTION_URI] == '')
{
	header("location:/connect/index.html");
	exit;
}
else
{
   $action = preg_replace('/(.s?html?|.php)$/', "", $matches[ACTION_URI]);
}

$view_script_path = 'view_'.$action.'.php';

if(!file_exists($view_script_path) || !preg_match("/^([a-z]+|404)$/", $action))
{
	header("HTTP/1.0 404 Not Found");
	header("location:/connect/404.shtml");
	exit;
}
$controller = new Controller();
echo $controller->init($action, $view_script_path);

?>

</body>
</html>

