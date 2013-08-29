<?
/** Part C */
require_once("MiniTemplator.class.php");
require_once('controller_interface.php');

class _wineinfoController implements Controller
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
      $this->mini_t->readTemplateFromFile($_SERVER['DOCUMENT_ROOT'] . $_SERVER["ASSIGN_PATH"] . 'view_wineinfo.php');
      $this->mini_t->setVariable("ASSIGN_PATH",$_SERVER["ASSIGN_PATH"]);

      $this->indexAction();

      $this->mini_t->generateOutput();
   }

   /**
    * Action to show information for a
    * particular Wine via wine_id.
    *
    * @return void.
    */
   private function indexAction()
   {
      $posts = array();

      if(isset($_COOKIE['submit']))
      {
         foreach($_COOKIE as $key=>$value)
         {
            $posts[$key] = $value;

            /** 
             * We delete $_COOKIEs by setting
             * the cookie 60 minutes before
             * the current time.
             * We don't want the user to refresh
             * the page.
             */
            setcookie($key, NULL, time() - SIXTY_MINUTES_IN_SEC);
         }
      }

      $this->wine_id = 0;
      if(isset($posts['wine_id']) && preg_match("/^[0-9]+$/", $posts['wine_id']))
      {
         $this->wine_id = $posts['wine_id'];
      }
      else
      {
         header("HTTP/1.0 404 Not Found");
         header('location:'.$_SERVER["ASSIGN_PATH"].'404.shtml');
         exit;
      }

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

      /** Single result. */
      $this->wine_info = $model_wine->query_single_wine_id($this->wine_id);

      /** Set the wine results into minitemplator */
      $this->mini_t->setVariable('wine_info_wine_id', $this->wine_info['wine_id']);
      $this->mini_t->setVariable('wine_info_wine_name', $this->wine_info['wine_name']);
      $this->mini_t->setVariable('wine_info_year', $this->wine_info['year']);
      $this->mini_t->setVariable('wine_info_wine_type', $this->wine_info['wine_type']);
      $this->mini_t->setVariable('wine_info_winery_name', $this->wine_info['winery_name']);
      $this->mini_t->setVariable('wine_info_region_name', $this->wine_info['region_name']);
      $this->mini_t->setVariable('wine_info_on_hand', $this->wine_info['on_hand']);
      $this->mini_t->setVariable('wine_info_cost', $this->wine_info['cost']);

      /** Multiple results. */
      $this->wine_info_grapes = $model_grape_varity->search_wine_id($this->wine_id);
      foreach($this->wine_info_grapes as $rows)
      {
         /** Set grape varity results into minitemplator */
         $this->mini_t->setVariable('wine_info_variety', $rows['variety']);
         /** Put into block so that we can use foreach loop */
         $this->mini_t->addBlock("wine_info_variety_block");
      }

      $this->wine_info_orders = $model_orders->retrieve_orders($this->wine_id);
      foreach($this->wine_info_orders as $rows)
      {
         $this->mini_t->setVariable('wine_info_order_id', $rows['order_id']);
         $this->mini_t->setVariable('wine_info_date', $rows['date']);
         $this->mini_t->setVariable('wine_info_title', $rows['title']);
         $this->mini_t->setVariable('wine_info_firstname', $rows['firstname']);
         $this->mini_t->setVariable('wine_info_surname', $rows['surname']);
         $this->mini_t->setVariable('wine_info_address', $rows['address']);
         $this->mini_t->setVariable('wine_info_city', $rows['city']);
         $this->mini_t->setVariable('wine_info_state', $rows['state']);
         $this->mini_t->setVariable('wine_info_zipcode', $rows['zipcode']);
         $this->mini_t->setVariable('wine_info_country', $rows['country']);
         $this->mini_t->setVariable('wine_info_phone', $rows['phone']);
         $this->mini_t->setVariable('wine_info_birth_date', $rows['birth_date']);
         $this->mini_t->setVariable('wine_info_qty', $rows['qty']);
         $this->mini_t->setVariable('wine_info_price', $rows['price']);
         $this->mini_t->setVariable('wine_info_instructions', $rows['instructions']);
         $this->mini_t->addBlock("wine_info_pagination_block");
      }
   }
}