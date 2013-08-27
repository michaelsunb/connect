<?php
require_once('model_abstract.php');

class ModelWine extends ModelAbstract
{
   /**
    * query values from wine
    *
    * @return array  return wine_id, year and wine_name
    *                array from wine table.
    */
   public function query_wine()
   {
      $sql = "select `wine_id`, `wine_name`, `year`, `description` from `wine`";

      return $this->retrieve_all($sql);
   }

   /**
    * query values from wine
    *
    * @param int $wine_name   id of the wine.
    * @return array           return wine_id, year and wine_name
    *                         array from wine table.
    */
   public function query_single_wine_id($wine_id)
   {
      $sql = "select 
      `wine`.`wine_id`, 
      `wine`.`wine_name`, 
      `wine`.`year`, 
      `wine_type`.`wine_type`, 
      `winery`.`winery_name`, 
      `region`.`region_name`, 
      `inventory`.`on_hand`, 
      `inventory`.`cost` 
      from `wine`
      JOIN `wine_type` ON `wine`.`wine_type`=`wine_type`.`wine_type_id` 
      JOIN `winery` ON `wine`.`winery_id`=`winery`.`winery_id` 
      JOIN `region` ON `winery`.`region_id`=`region`.`region_id` 
      JOIN `inventory` ON `wine`.`wine_id`=`inventory`.`wine_id` 
      where `wine`.`wine_id` = " . $wine_id . "
      LIMIT 1";

      return $this->retrieve_all($sql);
   }

   /**
    * query values from wine
    *
    * @return array  return wine_id, year and wine_name
    *                array from wine table.
    */
   public function query_years()
   {
      $sql = "select 
      DISTINCT `wine`.`year` 
      from `wine`
      ORDER BY `wine`.`year` DESC ";

      return $this->retrieve_all($sql);
   }
}
