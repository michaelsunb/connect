
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
   <h1>Wine #<?= $this->wine_info['wine_id']; ?></h1>
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
                  <td><?= $this->wine_info['wine_name']; ?></td>
                  <td><?= $this->wine_info['year']; ?></td>
                  <td><?= $this->wine_info['wine_type']; ?></td>
                  <td><?= $this->wine_info['winery_name']; ?></td>
                  <td><?= $this->wine_info['region_name']; ?></td>
                  <td><?= $this->wine_info['on_hand']; ?></td>
                  <td>$<?= $this->wine_info['cost']; ?></td>
                  <td><?
                  foreach($this->wine_info_grapes as $rows)
                  {
                     ?><p><?= $rows['variety']; ?></p><?
                  }
                  ?></td>
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
               </thead><?

            foreach($this->wine_info_orders as $row)
            {
               echo "\n";
               ?>
                  <tr>
                     <td><?= $row['order_id']; ?></td>
                     <td><?= $row['date']; ?></td>
                     <td><?= $row['title']; ?> <?= $row['firstname']; ?>  <?= $row['surname']; ?></td>
                     <td><?= $row['address']; ?></td>
                     <td><?= $row['city']; ?></td>
                     <td><?= $row['state']; ?></td>
                     <td><?= $row['zipcode']; ?></td>
                     <td><?= $row['country']; ?></td>
                     <td><?= $row['phone']; ?></td>
                     <td><?= $row['birth_date']; ?></td>
                     <td><?= $row['qty']; ?></td>
                     <td>$<?= $row['price']; ?></td>
                     <td><?= $row['instructions']; ?></td>
                  </tr><?
            }
            ?>

            </table>
         </td>
      </tr>
   </table>