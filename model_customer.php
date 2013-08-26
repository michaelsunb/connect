<?php
require_once('model_abstract.php');

class ModelCustomer extends ModelAbstract
{
   /**
    * query values from join tables
    *
    * @param int $cust_id  search string for wine_name.
    * @return array        return all from customer, titles
    *                      and countries table according to
    *                      cust_id
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
