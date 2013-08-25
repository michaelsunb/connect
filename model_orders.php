<?php
require_once('model_abstract.php');

class ModelOrders extends ModelAbstract
{
   /**
    * query values from orders table
    *
    * @param array $wine_ids  search ids for wine_id.
    * @return array           return all from join tables
    */
   public function search_items($wine_ids)
   {
      $add_comma = false;
      $in_query = "IN(";
      foreach($wine_ids as $key=>$value)
      {
         if($add_comma)
         {
            $in_query .= ",";
         }
         $add_comma = true;
         $in_query .= $key;
      }
      $in_query .= ")";

      $sql = "select *
      from `orders` 
      JOIN `items` ON `orders`.`order_id`=`items`.`order_id` 
      AND `orders`.`cust_id`=`items`.`cust_id` 
      WHERE `items`.`wine_id` " . $in_query . "";
//echo $sql;
      return $this->retrieve_all($sql);
   }

   /**
    * query values from orders table
    *
    * @param int $wine_id  search int for wine_id.
    * @return array        return total_qty and total_price
    *                      array from wine table.
    */
   public function retrieve_totals($wine_id)
   {
      $sql = "SELECT 
      SUM(`items`.`qty`) AS `total_qty`, 
      SUM(`items`.`price`) AS `total_price`
      FROM `orders`
      JOIN `items` ON `orders`.`order_id` = `items`.`order_id`
      AND `orders`.`cust_id` = `items`.`cust_id`
      WHERE `items`.`wine_id` = ".$wine_id."
      LIMIT 1 ";

      return $this->retrieve_single($sql);
   }
}
