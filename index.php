<?php
require_once('model_region.php');
require_once('model_grapevariety.php');
require_once('model_winevariety.php');

DEFINE("ADD_TO_LIMIT",30);

$model_region = new ModelRegion();
$region_results = $model_region->query_region();
$model_grape_varity = new ModelGrapeVariety();
$grape_variety_results = $model_grape_varity->query_grape_variety();

$winesearch = "";
if(isset($_GET['winesearch']))
{
   $winesearch = $_GET['winesearch'];
}

$region = 0;
if(isset($_GET['region']))
{
   $region = $_GET['region'];
}

$grape_variety = 0;
if(isset($_GET['grape_variety']))
{
   $grape_variety = $_GET['grape_variety'];
}

if(!isset($_GET['next']) ||
   (isset($_GET['next']) && $_GET['next'] <= 0) ||
   (isset($_GET['next']) && !preg_match("/^[0-9]+$/", $_GET['next'])))
{
   $limit_start = DEFAULT_START_LIMIT;
   $limit_end = DEFAULT_END_LIMIT;
   $prev_link = DEFAULT_START_LIMIT;
   $next_link = DEFAULT_END_LIMIT;
}
else
{
   $limit_start = $_GET['next'];
   $limit_end = $_GET['next'] + ADD_TO_LIMIT;

   $prev_link = $_GET['next'] - ADD_TO_LIMIT;
   $next_link = $limit_end;
}

$model_wine = new ModelWineVariety();
$wine_results = $model_wine->search_wine_name($winesearch,$limit_start,$limit_end);
?>
<!DOCTYPE HTML>
<html>
<head><title>Michael Baluyos Assignment 1</title></head>
<body>
   <form action="index.php" method="get">
      <input type="hidden" name="next" value="<?= $limit_start; ?>"> 
      Region: 
      <select name="region">
<?
foreach($region_results as $row)
{
   echo "\n         ";
   if($region == $row['region_id'])
   {
      ?><option value="<?= $row['region_id']; ?>" selected><?= $row['region_name']; ?></option><?
   }
   else
   {
      ?><option value="<?= $row['region_id']; ?>"><?= $row['region_name']; ?></option><?
   }
}
?>

      </select>
      <br />
      Grape Variety: 
      <select name="grape_variety">
         <option value="all">All</option>
<?
foreach($grape_variety_results as $row)
{
   echo "\n         ";
   if($grape_variety == $row['variety_id'])
   {
      ?><option value="<?= $row['variety_id']; ?>" selected><?= $row['variety']; ?></option><?
   }
   else
   {
      ?><option value="<?= $row['variety_id']; ?>"><?= $row['variety']; ?></option><?
   }
}
?>

      </select>
      <br />
      Search Wine: <input type="search" name="winesearch" value="<?= $winesearch; ?>">
      <br />
      <input type="submit">
   </form>
<?
$add_gets = 'region='.$region.'&grape_variety='.$grape_variety.'&winesearch='.$winesearch;
if(isset($wine_results) && count($wine_results) > 1)
{
   $html_prv_link = '<a href="?next='.$prev_link.'&'.$add_gets.'">';
   $html_nxt_link = '<a href="?next='.$next_link .'&'.$add_gets.'">';
   ?>   <table>
      <thead>
         <tr>
            <th>Wine</th>
            <th>Grape Variety</th>
            <th>Year</th>
            <th>Wine Type</th>
            <th>Winery Name</th>
         </tr>
      </thead>
      <tfoot>
         <tr>
            <td><?= $html_prv_link; ?>&lt;&lt; Previous</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><?= $html_nxt_link; ?>Next &gt;&gt;</a></td>
         </tr>
      </tfoot>
      <tbody><?
   foreach($wine_results as $row)
   {
      echo "\n";
      ?>
         <tr id="wine_<?= $row['id']; ?>_<?= $row['wine_id']; ?>">
            <td><?= $row['wine_name']; ?></td>
            <td><?= $row['variety']; ?></td>
            <td><?= $row['year']; ?></td>
            <td><?= $row['wine_type']; ?></td>
            <td><?= $row['winery_name']; ?></td>
         </tr><?
   }
   ?>

      </tbody>
   </table><?
}
else
{
   $html_nxt_link = '<a href="?next='.$next_link .'&'.$add_gets.'">';
   ?><a href="?<?= $add_gets; ?>">reset pagination</a><?
}
?>

</body>
</html>

