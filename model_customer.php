<?php
require_once('model_abstract.php');

class ModelCustomer extends ModelAbstract
{
   /**
    * query values from join tables
    *
    * @param string $wine_name   search string for wine_name.
    * @param string $winery_name   search string for winery_name.
    * @param string $limit_start start point for pagination.
    * @param string $total_limit total number of rows.
    * @return array              return id, wine_id, year, wine_name
    *                            variety_id, wine_type, region_id
    *                            on_hand, cost, qty, price
    *                            array from joins.
    */
   public function search_cust_id($cust_id)
   {
      $sql = "SELECT *
      FROM `customer`  
      JOIN `titles` ON `customer`.`title_id`=`titles`.`title_id`
      JOIN `countries` ON `customer`.`country_id`=`countries`.`country_id`
      WHERE `customer`.`cust_id` = " . $cust_id ."
      LIMIT 1";

      return $this->retrieve_single($sql);
   }
}
