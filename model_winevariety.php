<?php
require_once('model_abstract.php');

class ModelWineVariety extends ModelAbstract
{
   /**
    * @var order_column pre-defined array for the query order
    */
   private static $order_column = array('`wine`.`wine_name`',
                                       '`wine`.`year`',
                                       '`wine_type`.`wine_type`',
                                       '`winery`.`winery_name`',
                                       '`inventory`.`on_hand`',
                                       '`inventory`.`cost`',
                                       '`wine`.`wine_id`',
                                       '`region`.`region_name`',
                                       '`grape_variety`.`variety`');

   /**
    * query values from join tables
    *
    * @param string $wine_name   search string for wine_name.
    * @param string $winery_name search string for winery_name.
    * @param array  $where       $where[key] is the column name 
    *                            and the value is the user 
    *                            input select..
    * @param int    $order       integer according to 
    *                            $this::$order_column[int].
    * @param int    $min_cost    minimum cost. Has to be lower the max.
    * @param int    $max_cost    maximum cost. Has to be higher the min.
    * @param string $limit_start start point for pagination.
    * @param string $total_limit total number of rows.
    * @return array              return id, wine_id, year, wine_name
    *                            variety_id, wine_type, region_id
    *                            on_hand, cost, qty, price
    *                            array from joins.
    */
   public function search_wine_name($wine_name,
         $winery_name,
         $where = array(),
         $order = DEFAULT_ORDER_COLUMN,
         $lo_year = 0,
         $hi_year = 0,
         $min_cost = 0,
         $max_cost = 0,
         $limit_start = DEFAULT_START_LIMIT,
         $total_limit = DEFAULT_TOTAL_LIMIT)
   {
      $sql = "SELECT 
      `wine_variety`.`id`,
      `grape_variety`.`variety`, 
      `wine`.`wine_id`, 
      `wine`.`wine_name`, 
      `wine`.`year`, 
      `wine_type`.`wine_type`, 
      `winery`.`winery_name`, 
      `region`.`region_name`, 
      `inventory`.`on_hand`, 
      `inventory`.`cost` 
      FROM `wine_variety` 
      JOIN `wine` ON `wine_variety`.`wine_id`=`wine`.`wine_id` 
      JOIN `wine_type` ON `wine`.`wine_type`=`wine_type`.`wine_type_id` 
      JOIN `winery` ON `wine`.`winery_id`=`winery`.`winery_id`  
      JOIN `region` ON `winery`.`region_id`=`region`.`region_id` 
      JOIN `inventory` ON `wine`.`wine_id`=`inventory`.`wine_id` 
      JOIN `grape_variety` ON `wine_variety`.`variety_id`=`grape_variety`.`variety_id` 
      WHERE `wine`.`wine_name` LIKE '%" . $wine_name ."%' 
      AND `winery`.`winery_name` LIKE '%" . $winery_name ."%'";

      /** query condition between min and max. */
      if(($lo_year > 0 && $hi_year > 0) &&
         ($lo_year < $hi_year))
      {
         $sql .= "AND  `wine`.`year`
         BETWEEN ".$lo_year."
         AND ".$hi_year;
      }

      /** query condition between min and max. */
      if(($min_cost > 0 && $max_cost > 0) &&
         ($min_cost < $max_cost))
      {
         $sql .= "AND  `inventory`.`cost`
         BETWEEN ".$min_cost."
         AND ".$max_cost;
      }

      /** add more conditions from select boxes */
      if(count($where)>0)
      {
         foreach($where as $key=>$value)
         {
            $sql .= " AND ".$key." = ".$value;
         }
      }

      $sql .= "
      GROUP BY `wine`.`year`, `wine`.`wine_name`
      ORDER BY ". $this::$order_column[$order] ." ASC
      LIMIT " . $limit_start . ", " . $total_limit;

      return $this->retrieve_all($sql);
   }
}
