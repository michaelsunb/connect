
   <form action="<?= $_SERVER["ASSIGN_PATH"]; ?>results.html" method="get">
      <div>
      Region: 
      <? Helpers::select("region",$this->region_results,array('region_id','region_name'),0); ?>

      </div>
      <div>
      Grape Variety: 
      <? Helpers::select("grape_variety",$this->grape_variety_results,array('variety_id','variety'),0,SELECT_ALL_TOP); ?>

      </div>
      <div>
      Wine Year:
      Low: <? Helpers::select("wine_year_lo",$this->wine_year_results,array('year','year'),0,SELECT_ALL_TOP); ?> | 
      HI: <? Helpers::select("wine_year_hi",$this->wine_year_results,array('year','year'),0,SELECT_ALL_TOP); ?>

      </div>
      <div>
      Search Wine: <input type="search" name="winesearch" value="">
      </div>
      <div>
      Search Winery: <input type="search" name="winerysearch" value="">
      </div>
      <div>
      Min Cost: <input type="search" name="min_cost" value="" maxlength="6" size="6">
      Max Cost: <input type="search" name="max_cost" value="" maxlength="6" size="6">
      </div>
      <input type="submit"> | 
      <input type="button" value="Reset" onclick="location.href='<?= $_SERVER["ASSIGN_PATH"]; ?>'">
   </form>