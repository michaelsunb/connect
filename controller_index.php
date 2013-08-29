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
      if(isset($_POST['submit']) || isset($_COOKIE['submit']))
      {
         $this->postAction();
      }

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
    * Post Action to set the
    * $_POST in a cookie for a
    * two component query module.
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

   /**
    * Start Page
    *
    * @return void.
    */
   private function postAction()
   {
      /** Allow if true to do a search query at results action. */
      $allow_search = true;

      /** Get $_POST requests and check if they are numbers. */
      if(isset($_POST['wine_year_lo']) && !preg_match("/^[0-9]+$/", $_POST['wine_year_lo']))
      {
         $allow_search = false;
      }

      if(isset($_POST['wine_year_hi']) && !preg_match("/^[0-9]+$/", $_POST['wine_year_hi']))
      {
         $allow_search = false;
      }

      if(isset($_POST['wine_year_lo']) && isset($_POST['wine_year_hi']) &&
        ($_POST['wine_year_lo'] > $_POST['wine_year_hi']))
      {
         $allow_search = false;
      }

      if(isset($_POST['grape_variety']) && !preg_match("/^[0-9]+$/", $_POST['grape_variety']))
      {
         $allow_search = false;
      }

      $min_cost = 0;
      if(isset($_POST['min_cost']) && $_POST['min_cost'] != "")
      {
         $min_cost = preg_replace('/^\$/', '', $_POST["min_cost"]);
         if(!preg_match("/^[0-9.]+$/", $min_cost))
         {
            $allow_search = false;
         }
      }

      $max_cost = 0;
      if(isset($_POST['max_cost']) && $_POST['max_cost'] != "")
      {
         $max_cost = preg_replace('/^\$/', '', $_POST["max_cost"]);
         if(!preg_match("/^[0-9.]+$/", $max_cost))
         {
            $allow_search = false;
         }
      }

      if($min_cost > $max_cost)
      {
         $allow_search = false;
      }

      /** 2 to 9 because region All is 1 and region_id 1 produces no results. */
      if(isset($_POST['region']) && !preg_match("/^[0-9]+$/", $_POST['region']))
      {
         $allow_search = false;
      }

      if(isset($_POST['winesearch']))
      {
         /** Don't allow if not spaces as well as all letters. */
         $this->winesearch = $_POST['winesearch'];
         /** Allows all letters. */
         if(!preg_match("/^[A-Za-z]+$/", $_POST['winesearch']) &&
            $_POST['winesearch'] != "")
         {
            $allow_search = false;
         }
      }

      if(isset($_POST['winerysearch']))
      {
         /** Don't allow if not spaces as well as all letters. */
         if(!preg_match("/^[A-Za-z ]+$/", $_POST['winerysearch']) &&
            $_POST['winerysearch'] != "")
         {
            $allow_search = false;
         }
      }

      /** Allow for 2 numbers. */
      if(isset($_POST['column']) && !preg_match("/^[0-9]{0,2}$/", $_POST['column']))
      {
         $allow_search = false;
      }

      /** Allow for 2 numbers with a maximum of 30. */
      if(isset($_POST['limit']) && (!preg_match("/^[0-9]{0,2}$/", $_POST['limit'])
       || $_POST['limit'] >= DEFAULT_TOTAL_LIMIT ))
      {
         $allow_search = false;
      }

      /**
       * Instead of using $_SESSIONs we $_COOKIEs
       * for a two component query module.
       */
      if(!isset($_COOKIE['submit']) && $allow_search)
      {
         /** Set cookie for two component query module. */
         foreach($_POST as $key=>$value)
         {
            setcookie($key, $value, time()+TEN_MINUTES_IN_SEC);
         }

         header('location:'.$_SERVER["ASSIGN_PATH"].'results.html');
         exit;
      }
      else
      {
         /** Delete cookie. */
         foreach($_POST as $key=>$value)
         {
            setcookie($key, NULL, time() - 3600);
         }

         header('location:'.$_SERVER["ASSIGN_PATH"].'index.html');
         exit;
      }
   }
}