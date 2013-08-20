<?php
require_once('connect.php');

$model = assignmentone::singleton();
$results = $model->query_region();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>
<head></head>
<body>
<h1>Region</h1>
<ul>
<?
foreach($results as $row)
{
   ?><li><? echo $row['region_id']; ?> - <? echo $row['region_name']; ?></li><?
}
?>
</ul>
</body></html>

