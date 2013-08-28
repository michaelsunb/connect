   ${html_session}
   <form action="${ASSIGN_PATH}results.html" method="get">
      <div>
         Region: 
         <select id="region" name="region"><!-- $BeginBlock region_select_block -->
           ${select_region}<!-- $EndBlock region_select_block -->
         </select>
         <input type="hidden" name="limits" value="${limits}">
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
      <input type="button" value="Reset" onclick="location.href='${ASSIGN_PATH}index.html'">
   </form>
   <table>
      <thead>
         <tr>
            <th><a href="results.html${html_column}6">id</a></th>
            <th><a href="results.html${html_column}0">Wine</a></th>
            <th><a href="results.html${html_column}8">Grape Variety</a></th>
            <th><a href="results.html${html_column}1">Year</a></th>
            <th><a href="results.html${html_column}2">Wine Type</a></th>
            <th><a href="results.html${html_column}3">Winery Name</a></th>
            <th><a href="results.html${html_column}7">Region</a></th>
            <th><a href="results.html${html_column}4">On Hand</a></th>
            <th><a href="results.html${html_column}5">Cost</a></th>
            <th><a href="results.html${html_column}9">Total<br />Stock Sold</a></th>
            <th><a href="results.html${html_column}10">Total<br />Sales Revenue</a></th>
         </tr>
      </thead>
      <tfoot>
         <tr>
            <td>${html_prv_link}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>${html_limits}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="text-align:right;">${html_nxt_link}</td>
         </tr>
      </tfoot>
      <tbody style="text-align:center;"><!-- $BeginBlock wine_pagination_block -->
         <tr>
            <td><a href="wineinfo.html?wine_id=${wine_id}">${wine_id}</a></td>
            <td>${wine_name}</td>
            <td>${variety}</td>
            <td>${year}</td>
            <td>${wine_type}</td>
            <td>${winery_name}</td>
            <td>${region_name}</td>
            <td>${on_hand}</td>
            <td>$${cost}</td>
            <td>${total_qty}</td>
            <td>$${total_price}</td>
         </tr><!-- $EndBlock wine_pagination_block -->
      </tbody>
   </table>
   ${no_records}