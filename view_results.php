<?php
require_once('helper_html.php');

?>
   <form action="/connect/results.html" method="get">
      <input type="hidden" name="next" value="<?= $this->limit_start; ?>">
      Region: 
      <? Html::select("region",$this->region_results,array('region_id','region_name'),$this->region); ?>

      <br />
      Grape Variety: 
      <? Html::select("grape_variety",$this->grape_variety_results,array('variety_id','variety'),$this->grape_variety,SELECT_ALL_TOP); ?>

      <br />
      Search Wine: <input type="search" name="winesearch" value="<?= $this->winesearch; ?>">
      <br />
      <input type="submit">
   </form>
<?
if(isset($this->wine_results) && count($this->wine_results) > 1)
{
   ?>   <table>
      <thead>
         <tr>
            <th>Wine</th>
            <th>Grape Variety</th>
            <th>Year</th>
            <th>Wine Type</th>
            <th>Winery Name</th>
            <th>Region</th>
         </tr>
      </thead>
      <tfoot>
         <tr>
            <td><?= $this->html_prv_link; ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><?= $this->html_nxt_link; ?></td>
         </tr>
      </tfoot>
      <tbody><?
   foreach($this->wine_results as $row)
   {
      $region_row = $this->inArrayActions($this->region_results,$row['region_id'],'region_id');
      $variety_row = $this->inArrayActions($this->grape_variety_results,$row['variety_id'],'variety_id');
      
      echo "\n";
      ?>
         <tr id="wine_<?= $row['id']; ?>_<?= $row['wine_id']; ?>">
            <td><?= $row['wine_name']; ?></td>
            <td><?= $variety_row['variety']; ?></td>
            <td><?= $row['year']; ?></td>
            <td><?= $row['wine_type']; ?></td>
            <td><?= $row['winery_name']; ?></td>
            <td><?= $region_row['region_name']; ?></td>
         </tr><?
         
      
   }
   ?>

      </tbody>
   </table><?
}
else
{
   ?><p>No records match your search criteria</p><?
   echo $this->html_nxt_link;
}

?>

