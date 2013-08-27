   ${html_session}
   <form action="${ASSIGN_PATH}results.html" method="get">
      <div>
      Region: 
         <select id="region" name="region"><!-- $BeginBlock region_select_block -->
           ${select_region}<!-- $EndBlock region_select_block -->
         </select>
      </div>
      <div>
      Grape Variety: 
         <select id="grape_variety" name="grape_variety">
           <option value="0">All</option><!-- $BeginBlock grape_variety_select_block -->
           ${select_grape_variety}<!-- $EndBlock grape_variety_select_block -->
         </select>

      </div>
      <div>
         Wine Year:
         Low: 
         <select id="wine_year_lo" name="wine_year_lo">
           <option value="0">All</option><!-- $BeginBlock year_lo_select_block -->
           ${select_year_lo}<!-- $EndBlock year_lo_select_block -->
         </select> | 
         HI: 
         <select id="wine_year_hi" name="wine_year_hi">
           <option value="0">All</option><!-- $BeginBlock year_hi_select_block -->
           ${select_year_hi}<!-- $EndBlock year_hi_select_block -->
         </select>
         ${html_year_error}
      </div>
      <div>
      Search Wine: <input type="search" name="winesearch" value="${winesearch}">
      </div>
      <div>
      Search Winery: <input type="search" name="winerysearch" value="${winerysearch}">
      </div>
      <div>
      Min Cost: <input type="search" name="min_cost" value="${min_cost}" maxlength="6" size="6">
      Max Cost: <input type="search" name="max_cost" value="${max_cost}" maxlength="6" size="6">
      ${html_cost_error}
      </div>
      <input type="submit"> | 
      <input type="button" value="Reset" onclick="location.href='${ASSIGN_PATH}'">
   </form>