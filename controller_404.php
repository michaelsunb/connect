<?
/** Part C */
require_once("MiniTemplator.class.php");
require_once('controller_interface.php');

class _404Controller implements Controller
{
   /**
    * @var $mini_t  MiniTemplator model.
    */
   private $mini_t;

   /**
    * retrieves the view file, checked by index.php, and use actions
    *
    * @return void.
    */
   public function init()
   {
      $this->mini_t = new MiniTemplator;
      $this->mini_t->readTemplateFromFile($_SERVER['DOCUMENT_ROOT'] . $_SERVER["ASSIGN_PATH"] . 'view_404.php');
      $this->mini_t->setVariable("ASSIGN_PATH",$_SERVER["ASSIGN_PATH"]);

      $this->indexAction();

      $this->mini_t->generateOutput();
   }

   /**
    * 404 missing action to redirect
    * users who are lost.
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

      foreach($this->wine_year_results as $row)
      {
         $this->mini_t->setVariable(
               "select_year_lo",'<option value="'.$row["year"].'">'.$row["year"].'</option>');

         $this->mini_t->addBlock("year_lo_select_block");
      }

      foreach($this->wine_year_results as $row)
      {
         $this->mini_t->setVariable(
               "select_year_hi",'<option value="'.$row["year"].'">'.$row["year"].'</option>');

         $this->mini_t->addBlock("year_hi_select_block");
      }

      foreach($this->grape_variety_results as $row)
      {
         $this->mini_t->setVariable(
               "select_grape_variety",'<option value="'.$row["variety_id"].'">'.$row["variety"].'</option>');

         $this->mini_t->addBlock("grape_variety_select_block");
      }

      foreach($this->region_results as $row)
      {
         $this->mini_t->setVariable(
               "select_region",'<option value="'.$row["region_id"].'">'.$row["region_name"].'</option>');

         $this->mini_t->addBlock("region_select_block");
      }
      
      $this->mini_t->setVariable(
         "html_nxt_link" , '<a href="'.$_SERVER["ASSIGN_PATH"].'index.html">reset search</option><br />');
   }
}