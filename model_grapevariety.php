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
}
