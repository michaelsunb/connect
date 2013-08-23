<?php
require_once('connect.php');

$model = connect::singleton();
$region_results = $model->query_region();
$grape_variety_results = $model->query_grape_variety();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>
<head></head>
<body>
<h1>Region</h1>
<select>
<?
foreach($region_results as $row)
{
   ?><option value="<? echo $row['region_id']; ?>"><? echo $row['region_name']; ?></option><?
   echo "\n";
}
?>
<option value="all">All</option>
</select>
<h1>Grape Variety</h1>
<select>
<?
foreach($grape_variety_results as $row)
{
   ?><option value="<? echo $row['variety_id']; ?>"><? echo $row['variety']; ?></option><?
   echo "\n";
}
?><option value="all">All</option>
</select>
</body></html>

