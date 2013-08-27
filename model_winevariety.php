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
                                       '`grape_variety`.`variety`',
                                       '`total_qty`',
                                       '`total_price`');

   /**
    * query values from join tables
    *
    * @param string $wine_name   Search string for wine_name.
    * @param string $winery_name Search string for winery_name.
    * @param array  $where       $where[key] is the column name 
    *                            and the value is the user 
    *                            input select..
    * @param int    $order       Integer according to 
    *                            $this::$order_column[int].
    * @param int    $lo_year     Low bound year has to be lower than
    *                            higher bound year.
    * @param int    $hi_year     Higher bound year has to be higher than
    *                            lower bound year.
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
      `inventory`.`cost`, 
      SUM(`items`.`qty`) AS `total_qty`, 
      SUM(`items`.`price`) AS `total_price` 
      FROM `wine_variety` 
      JOIN `wine` ON `wine_variety`.`wine_id`=`wine`.`wine_id` 
      JOIN `wine_type` ON `wine`.`wine_type`=`wine_type`.`wine_type_id` 
      JOIN `winery` ON `wine`.`winery_id`=`winery`.`winery_id` 
      JOIN `region` ON `winery`.`region_id`=`region`.`region_id` 
      JOIN `inventory` ON `wine`.`wine_id`=`inventory`.`wine_id` 
      JOIN `grape_variety` ON `wine_variety`.`variety_id`=`grape_variety`.`variety_id` 
      JOIN `items` ON `wine`.`wine_id` = `items`.`wine_id` 
      JOIN `orders` ON `items`.`order_id` = `orders`.`order_id` 
                    AND `items`.`cust_id` = `orders`.`cust_id` 
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

      $order_query = '`wine`.`wine_name`';
      if(count($this::$order_column) > $order)
      {
         $order_query = $this::$order_column[$order];
      }

      $sql .= "
      GROUP BY `wine`.`year`, 
      `wine`.`wine_name`, 
      `grape_variety`.`variety`,
      `wine`.`wine_id`
      ORDER BY ". $order_query ." ASC
      LIMIT " . $limit_start . ", " . $total_limit;
//echo $sql;
      return $this->retrieve_all($sql);
   }
}
