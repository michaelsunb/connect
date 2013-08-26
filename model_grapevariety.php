<?php
require_once('model_abstract.php');

class ModelGrapeVariety extends ModelAbstract
{
   /**
    * query variety_id and variety from grape_variety
    *
    * @return array  return variety_id and variety
    *                array from grape_variety table.
    */
   public function query_grape_variety()
   {
      $sql = "select `variety_id`, `variety` from `grape_variety`";

      return $this->retrieve_all($sql);
   }

   /**
    * query values from wine
    *
    * @param string $wine_name   search string for wine_name.
    * @return array              return wine_id, year and wine_name
    *                            array from wine table.
    */
   public function search_wine_id($wine_id)
   {
      $sql = "SELECT  
      `wine_variety`.`wine_id`, 
      `grape_variety`.`variety_id`, 
      `grape_variety`.`variety`
      FROM `grape_variety`  
      JOIN `wine_variety` ON `grape_variety`.`variety_id`=`wine_variety`.`variety_id`
      WHERE `wine_variety`.`wine_id` = " . $wine_id . "";

      return $this->retrieve_all($sql);
   }
}
