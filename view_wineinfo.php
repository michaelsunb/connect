
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
      <input type="button" value="Back" onclick="goBack()"> | 
      <input type="button" value="Reset" onclick="location.href='${ASSIGN_PATH}index.html'">
   </form>
   <h1>Wine #${wine_info_wine_id}</h1>
   <table style="width:100%;text-align:center;">
      <tr>
         <td>
            <table style="width:100%;text-align:center;" border=1>
               <thead>
                  <tr>
                     <th>Wine</th>
                     <th>Year</th>
                     <th>Wine Type</th>
                     <th>Winery Name</th>
                     <th>Region</th>
                     <th>On Hand</th>
                     <th>Cost</th>
                     <th>Grape Variety</th>
                  </tr>
               </thead>
               <tr>
                  <td>${wine_info_wine_name}</td>
                  <td>${wine_info_year}</td>
                  <td>${wine_info_wine_type}</td>
                  <td>${wine_info_winery_name}</td>
                  <td>${wine_info_region_name}</td>
                  <td>${wine_info_on_hand}</td>
                  <td>$${wine_info_cost}</td>
                  <td><!-- $BeginBlock wine_info_variety_block -->
                     <p>${wine_info_variety}</p><!-- $EndBlock wine_info_variety_block -->
                  </td>
               </tr>
            </table>
         </td>
      </tr>
      <tr>
         <td>
            <table style="width:100%;" border=1>
               <thead>
                  <tr>
                     <th>Order id</th>
                     <th>Date</th>
                     <th>Full Name</th>
                     <th>Address</th>
                     <th>City</th>
                     <th>State</th>
                     <th>Zip Code</th>
                     <th>Country</th>
                     <th>Phone Number</th>
                     <th>Birth Date</th>
                     <th>Quantity</th>
                     <th>Price</th>
                     <th>Instructions</th>
                  </tr>
               </thead><!-- $BeginBlock wine_info_pagination_block -->
                  <tr>
                     <td>${wine_info_order_id}</td>
                     <td>${wine_info_date}</td>
                     <td>${wine_info_title} ${wine_info_firstname}  ${wine_info_surname}</td>
                     <td>${wine_info_address}</td>
                     <td>${wine_info_city}</td>
                     <td>${wine_info_state}</td>
                     <td>${wine_info_zipcode}</td>
                     <td>${wine_info_country}</td>
                     <td>${wine_info_phone}</td>
                     <td>${wine_info_birth_date}</td>
                     <td>${wine_info_qty}</td>
                     <td>$${wine_info_price}</td>
                     <td>${wine_info_instructions}</td>
                  </tr><!-- $EndBlock wine_info_pagination_block -->
            </table>
         </td>
      </tr>
   </table>
   <script>
      function goBack()
      {
         window.history.back()
      }
   </script>