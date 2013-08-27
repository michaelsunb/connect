
   <form action="<?= $_SERVER["ASSIGN_PATH"]; ?>results.html" method="get">
      <div>
      Region: 
      <? Helpers::select("region",$this->region_results,array('region_id','region_name'),$this->region); ?>

      </div>
      <div>
      Grape Variety: 
      <? Helpers::select("grape_variety",$this->grape_variety_results,array('variety_id','variety'),$this->grape_variety,SELECT_ALL_TOP); ?>

      </div>
      <div>
      Wine Year:
      Low: <? Helpers::select("wine_year_lo",$this->wine_year_results,array('year','year'),$this->wine_year_lo,SELECT_ALL_TOP); ?> | 
      HI: <? Helpers::select("wine_year_hi",$this->wine_year_results,array('year','year'),$this->wine_year_hi,SELECT_ALL_TOP); ?>
      <?= $this->html_year_error; ?>

      </div>
      <div>
      Search Wine: <input type="search" name="winesearch" value="<?= $this->winesearch; ?>">
      </div>
      <div>
      Search Winery: <input type="search" name="winerysearch" value="<?= $this->winerysearch; ?>">
      </div>
      <div>
      Min Cost: <input type="search" name="min_cost" value="<?= $this->min_cost; ?>" maxlength="6" size="6">
      Max Cost: <input type="search" name="max_cost" value="<?= $this->max_cost; ?>" maxlength="6" size="6">
      <?= $this->html_cost_error; ?>
      </div>
      <input type="submit">
   </form>
<?
if(isset($this->wine_results) && count($this->wine_results) > 1)
{
   ?>   <table>
      <thead>
         <tr>
            <th><a href="results.html<?= $this->html_column; ?>6">id</a></th>
            <th><a href="results.html<?= $this->html_column; ?>0">Wine</a></th>
            <th><a href="results.html<?= $this->html_column; ?>8">Grape Variety</a></th>
            <th><a href="results.html<?= $this->html_column; ?>1">Year</a></th>
            <th><a href="results.html<?= $this->html_column; ?>2">Wine Type</a></th>
            <th><a href="results.html<?= $this->html_column; ?>3">Winery Name</a></th>
            <th><a href="results.html<?= $this->html_column; ?>7">Region</a></th>
            <th><a href="results.html<?= $this->html_column; ?>4">On Hand</a></th>
            <th><a href="results.html<?= $this->html_column; ?>5">Cost</a></th>
            <th><a href="results.html<?= $this->html_column; ?>9">Total<br />Stock Sold</a></th>
            <th><a href="results.html<?= $this->html_column; ?>10">Total<br />Sales Revenue</a></th>
         </tr>
      </thead>
      <tfoot>
         <tr>
            <td>&nbsp;</td>
            <td><?= $this->html_prv_link; ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="text-align:right;"><?= $this->html_nxt_link; ?></td>
         </tr>
      </tfoot>
      <tbody style="text-align:center;"><?
   
   foreach($this->wine_results as $row)
   {
      echo "\n";
      ?>
         <tr>
            <td><a href="wineinfo.html?wine_id=<?= $row['wine_id']; ?>"><?= $row['wine_id']; ?></a></td>
            <td><?= $row['wine_name']; ?></td>
            <td><?= $row['variety']; ?></td>
            <td><?= $row['year']; ?></td>
            <td><?= $row['wine_type']; ?></td>
            <td><?= $row['winery_name']; ?></td>
            <td><?= $row['region_name']; ?></td>
            <td><?= $row['on_hand']; ?></td>
            <td>$<?= $row['cost']; ?></td>
            <td><?= $row['total_qty']; ?></td>
            <td>$<?= $row['total_price']; ?></td>
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
