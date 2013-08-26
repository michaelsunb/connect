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
   public function retrieve_orders($wine_id)
   {
      $sql = "select `orders`.`order_id`,
      `orders`.`date`,
      `orders`.`instructions`,
      `orders`.`creditcard`,
      `orders`.`expirydate`,
      `items`.`qty`,
      `items`.`price`,
      `titles`.`title`,
      `customer`.`surname`,
      `customer`.`firstname`,
      `customer`.`initial`,
      `customer`.`address`,
      `customer`.`city`,
      `customer`.`state`,
      `customer`.`zipcode`,
      `countries`.`country`,
      `customer`.`phone`,
      `customer`.`birth_date`
      from `orders` 
      JOIN `customer` ON `orders`.`cust_id`=`customer`.`cust_id` 
      JOIN `titles` ON `customer`.`title_id`=`titles`.`title_id` 
      JOIN `countries` ON `customer`.`country_id`=`countries`.`country_id`
      JOIN `items` ON `orders`.`order_id`=`items`.`order_id` 
      AND `orders`.`cust_id`=`items`.`cust_id` 
      WHERE `items`.`wine_id` = " . $wine_id . "
      ORDER BY `orders`.`order_id` ASC";

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
