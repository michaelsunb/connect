
   <form action="/connect/results.html" method="get">
      <input type="hidden" name="next" value="<?= $this->limit_start; ?>">
      Region: 
      <? Helpers::select("region",$this->region_results,array('region_id','region_name'),$this->region); ?>

      <br />
      Grape Variety: 
      <? Helpers::select("grape_variety",$this->grape_variety_results,array('variety_id','variety'),$this->grape_variety,SELECT_ALL_TOP); ?>

      <br />
      Search Wine: <input type="search" name="winesearch" value="<?= $this->winesearch; ?>">
      <br />
      Search Winery: <input type="search" name="winerysearch" value="<?= $this->winerysearch; ?>">
      <br />
      <input type="submit">
   </form>