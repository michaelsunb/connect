<?php
require_once('model_region.php');
require_once('model_grapevariety.php');

$model_region = new ModelRegion();
$region_results = $model_region->query_region();
$model_grape_varity = new ModelGrapeVariety();
$grape_variety_results = $model_grape_varity->query_grape_variety();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>
<head></head>
<body>
<h1><?= $this->test;?></h1>
</body></html>

