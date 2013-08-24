<?php
require_once('model_abstract.php');

class ModelWineVariety extends ModelAbstract
{
   // query wine_name, year and description from wine
   public function count_search_query($wine_name)
   {
      $sql = "SELECT count(*)
      FROM `wine_variety` 
      JOIN `grape_variety` ON `wine_variety`.`variety_id`=`grape_variety`.`variety_id` 
      JOIN `wine` ON `wine_variety`.`wine_id`=`wine`.`wine_id` 
      JOIN `wine_type` ON `wine`.`wine_type`=`wine_type`.`wine_type_id` 
      JOIN `winery` ON `wine`.`winery_id`=`winery`.`winery_id` 
      WHERE `wine_name` LIKE '%" . $wine_name ."%'
      LIMIT 1";

      return $this->retrieve_single($sql);
   }

   // query wine_name, year and description from wine
   public function search_wine_name($wine_name, $limit_start = DEFAULT_START_LIMIT,
         $limit_end = DEFAULT_END_LIMIT)
   {
      $sql = "SELECT 
      `wine_variety`.`id`,
      `grape_variety`.`variety`, 
      `wine`.`wine_id`, 
      `wine`.`wine_name`, 
      `wine`.`year`, 
      `wine_type`.`wine_type`, 
      `winery`.`winery_name`, 
      `winery`.`region_id` 
      FROM `wine_variety` 
      JOIN `grape_variety` ON `wine_variety`.`variety_id`=`grape_variety`.`variety_id` 
      JOIN `wine` ON `wine_variety`.`wine_id`=`wine`.`wine_id` 
      JOIN `wine_type` ON `wine`.`wine_type`=`wine_type`.`wine_type_id` 
      JOIN `winery` ON `wine`.`winery_id`=`winery`.`winery_id` 
      WHERE `wine_name` LIKE '%" . $wine_name ."%'
      ORDER BY `wine`.`wine_name` ASC
      LIMIT " . $limit_start . ", " . DEFAULT_END_LIMIT;

      return $this->retrieve_all($sql);
   }
}
