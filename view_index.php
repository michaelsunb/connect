
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
      <? Helpers::select("wine_year",$this->wine_year_results,array('year','year'),$this->wine_year,SELECT_ALL_TOP); ?>

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