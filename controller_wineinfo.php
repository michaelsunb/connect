<?
require_once('controller_interface.php');

class _wineinfoController implements Controller
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
      require_once($_SERVER['DOCUMENT_ROOT'] . $_SERVER["ASSIGN_PATH"] . 'view_wineinfo.php');
      /** Internal buffer copied to a variable string */
      $contents = ob_get_contents();
      /** Discard the buffer contents */
      ob_end_clean();

      return $contents;
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

      /** Single result. */
      $this->wine_info = $model_wine->query_single_wine_id($this->wine_id);

      /** Multiple results. */
      $this->wine_info_grapes = $model_grape_varity->search_wine_id($this->wine_id);
      $this->wine_info_orders = $model_orders->retrieve_orders($this->wine_id);
   }
}