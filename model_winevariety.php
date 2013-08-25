<?php
require_once('model_abstract.php');

class ModelWineVariety extends ModelAbstract
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
   public function search_wine_name($wine_name, $winery_name,
         $where = array(), 
         $limit_start = DEFAULT_START_LIMIT,
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
      `winery`.`region_id`, 
      `inventory`.`on_hand`, 
      `inventory`.`cost` 
      FROM `wine_variety` 
      JOIN `wine` ON `wine_variety`.`wine_id`=`wine`.`wine_id` 
      JOIN `wine_type` ON `wine`.`wine_type`=`wine_type`.`wine_type_id` 
      JOIN `winery` ON `wine`.`winery_id`=`winery`.`winery_id` 
      JOIN `inventory` ON `wine`.`wine_id`=`inventory`.`wine_id` 
      WHERE `wine`.`wine_name` LIKE '%" . $wine_name ."%' 
      AND `winery`.`winery_name` LIKE '%" . $winery_name ."%'";
      
      if(count($where)>0)
      {
         foreach($where as $key=>$value)
         {
            $sql .= " AND ".$key." = ".$value;
         }
      }
      $sql .= "
      ORDER BY `wine`.`wine_name` ASC
      LIMIT " . $limit_start . ", " . $total_limit;

      return $this->retrieve_all($sql);
   }
}
