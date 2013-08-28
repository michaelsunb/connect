<?
/** Part C */
require_once("MiniTemplator.class.php");
require_once('controller_interface.php');

class _indexController implements Controller
{
   /**
    * @var $mini_t  MiniTemplator model.
    */
   private $mini_t;

   /**
    * retrieves the view file, checked by index.php, and use actions
    *
    * @return string return html body contents.
    */
   public function init()
   {
      $this->mini_t = new MiniTemplator;
      $this->mini_t->readTemplateFromFile($_SERVER['DOCUMENT_ROOT'] . $_SERVER["ASSIGN_PATH"] . 'view_index.php');
      $this->mini_t->setVariable("ASSIGN_PATH",$_SERVER["ASSIGN_PATH"]);

      $this->indexAction();

      $this->mini_t->generateOutput();
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

      $html_session = '<h3><a href="index.html?cancel_session=true">Cancel Session</a></h3>';
      if(isset($_GET['start_session']))
      {
         $_SESSION['start_session'] = true;
         $_SESSION['wine_viewed'] = array();
         header('location:'.$_SERVER["ASSIGN_PATH"].'index.html');
         exit;
      }
      elseif(isset($_GET['cancel_session']))
      {
         unset($_SESSION['start_session']);
         unset($_SESSION['wine_viewed']);
         header('location:'.$_SERVER["ASSIGN_PATH"].'index.html');
      }
      elseif(!isset($_SESSION['start_session']) ||
      isset($_SESSION['start_session']) && $_SESSION['start_session'] == false)
      {
         $html_session = '<h3><a href="index.html?start_session=true">Start Session</a></h3>';
      }
      
      if(isset($_SESSION['wine_viewed']) && count($_SESSION['wine_viewed']) > 0)
      {
         $html_session .= '<p><a href="session_viewed.html">View Previous Session</a></p>';
      }

      $this->mini_t->setVariable("html_session",$html_session);
   }
}