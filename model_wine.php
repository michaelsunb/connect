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

   /**
    * query values from wine
    *
    * @param string $sql   search string for wine_name.
    * @return array        return wine_id, year and wine_name
    *                      array from wine table.
    */
   public function search_wine_name($wine_name)
   {
      $sql = "select `wine_id`, 
      `wine_name`, 
      `year`, 
      `description`
      from wine 
      WHERE `wine_name` LIKE '%" . $wine_name . "%'";

      return $this->retrieve_all($sql);
   }
}
