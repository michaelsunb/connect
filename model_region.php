<?php
require_once('model_abstract.php');

class ModelRegion extends ModelAbstract
{
   /**
    * query region_id and region_name from region
    *
    * @return array  return region_id and region_name
    *                array from region table.
    */
   public function query_region()
   {
      $sql = "select `region_id`, `region_name` from region";

      return $this->retrieve_all($sql);
   }
}
