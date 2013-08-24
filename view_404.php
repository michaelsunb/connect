<?php

require_once('helper_html.php');

?>
   <form action="index.php" method="get">
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
$add_gets = 'region='.$this->region.'&amp;grape_variety='.$this->grape_variety.'&amp;winesearch='.$this->winesearch;
$html_nxt_link = '<a href="/connect/">reset search</a><br />';
$html_nxt_link .= '<a href="/connect/index.html?'.$add_gets.'&amp;winesearch='.$this->winesearch.'">reset pagination</a>';

?><p>No records match your search criteria</p><?
echo $html_nxt_link;
?>

