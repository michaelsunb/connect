<?
require_once('controller_interface.php');

class _indexController implements Controller
{
   /**
    * retrieves the view file, checked by index.php, and use actions
    *
    * @return string return html body contents.
    */
   public function init()
   {
      $this->indexAction();

      /** Store the view file in a internal buffer */
      ob_start();
      require_once($_SERVER['DOCUMENT_ROOT'] . $_SERVER["ASSIGN_PATH"] . 'view_index.php');
      /** Internal buffer copied to a variable string */
      $contents = ob_get_contents();
      /** Discard the buffer contents */
      ob_end_clean();

      return $contents;
   }

   /**
    * Start Page
    *
    * @return void.
    */
   private function indexAction()
   {
      /** Create new models class. */
      $model_orders = new ModelOrders();
      $model_wine = new ModelWine();
      $model_winevariety = new ModelWineVariety();
      $model_region = new ModelRegion();
      $model_grape_varity = new ModelGrapeVariety();

      /**
       * query all results for their respective models.
       * For the select boxes.
       */
      $this->wine_year_results = $model_wine->query_years();
      $this->region_results = $model_region->query_region();
      $this->grape_variety_results = $model_grape_varity->query_grape_variety();
   }
}