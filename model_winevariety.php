<?php
require_once('model_abstract.php');

DEFINE("DEFAULT_TOTAL_LIMIT",30);

class ModelWineVariety extends ModelAbstract
{
   /**
    * query values from wine
    *
    * @param string $wine_name   search string for wine_name.
    * @param string $limit_start start point for pagination.
    * @param string $total_limit total number of rows.
    * @return array              return wine_id, year and wine_name
    *                            array from wine table.
    */
   public function search_wine_name($wine_name, $limit_start = DEFAULT_START_LIMIT,
         $total_limit = DEFAULT_TOTAL_LIMIT)
   {
      $sql = "SELECT 
      `wine_variety`.`id`,
      `wine_variety`.`variety_id`, 
      `wine`.`wine_id`, 
      `wine`.`wine_name`, 
      `wine`.`year`, 
      `wine_type`.`wine_type`, 
      `winery`.`winery_name`, 
      `winery`.`region_id` 
      FROM `wine_variety` 
      JOIN `wine` ON `wine_variety`.`wine_id`=`wine`.`wine_id` 
      JOIN `wine_type` ON `wine`.`wine_type`=`wine_type`.`wine_type_id` 
      JOIN `winery` ON `wine`.`winery_id`=`winery`.`winery_id` 
      WHERE `wine_name` LIKE '%" . $wine_name ."%'
      ORDER BY `wine`.`wine_name` ASC
      LIMIT " . $limit_start . ", " . DEFAULT_TOTAL_LIMIT;

      return $this->retrieve_all($sql);
   }
}
