<?
require_once('model_region.php');
require_once('model_grapevariety.php');
require_once('model_wine.php');
require_once('model_winevariety.php');
require_once('model_orders.php');
require_once('model_customer.php');

require_once('helpers.php');

DEFINE("DEFAULT_ORDER_COLUMN",0);

class Controller
{
   /**
    * retrieves the view file, checked by index.php, and use actions
    *
    * @param string $actions     Actions to select which view you want.
    * @param string $file_name   Choose a file from connect directory.
    * @return string             return html body contents.
    */
   public function init($actions,$file_name)
   {
      $action = '_'.$actions.'Action';
      $this->$action();

      /** Store the view file in a internal buffer */
      ob_start();
      require_once($_SERVER['DOCUMENT_ROOT'] . $_SERVER["ASSIGN_PATH"] .$file_name);
      /** Internal buffer copied to a variable string */
      $contents = ob_get_contents();
      /** Discard the buffer contents */
      ob_end_clean();

      return $contents;
   }

   /**
    * @var $models_[a-z]+  Common models used in the actions.
    */
   private $model_wine;
   private $model_winevariety;
   private $model_region;
   private $model_grape_varity;

   /**
    * Repetitive actions are put in here
    *
    * @return void.
    */
   private function commonActions()
   {
      /** Default sql starting from row in table */
      $this->limit_start = DEFAULT_START_LIMIT;

      /** Create new models class. */
      $this->model_orders = new ModelOrders();
      $this->model_wine = new ModelWine();
      $this->model_winevariety = new ModelWineVariety();
      $this->model_region = new ModelRegion();
      $this->model_grape_varity = new ModelGrapeVariety();

      /**
       * query all results for their respective models.
       * For the select boxes.
       */
      $this->wine_year_results = $this->model_wine->query_years();
      $this->region_results = $this->model_region->query_region();
      $this->grape_variety_results = $this->model_grape_varity->query_grape_variety();

      /** Get $_GET requests and check if they are numbers. */
      $this->wine_year_lo = 0;
      if(isset($_GET['wine_year_lo']) && preg_match("/^[0-9]+$/", $_GET['wine_year_lo']))
      {
         $this->wine_year_lo = $_GET['wine_year_lo'];
      }
      $this->wine_year_hi = 0;
      if(isset($_GET['wine_year_hi']) && preg_match("/^[0-9]+$/", $_GET['wine_year_hi']))
      {
         $this->wine_year_hi = $_GET['wine_year_hi'];
      }
      $this->html_year_error = "";
      if($this->wine_year_lo > $this->wine_year_hi)
      {
         $this->html_year_error =
            '<span style="color:red;">Low year must be lower than High year.</span>';
      }
      
      /** Get $_GET requests and check if they are numbers. */
      $this->grape_variety = 0;
      if(isset($_GET['grape_variety']) && preg_match("/^[0-9]+$/", $_GET['grape_variety']))
      {
         $this->grape_variety = $_GET['grape_variety'];
      }
      
      /** Get $_GET requests and check if they are numbers. */
      $this->min_cost = 0;
      if(isset($_GET['min_cost']))
      {
         $min_cost = preg_replace('/^\$/', '', $_GET["min_cost"]);
         if(preg_match("/^[0-9.]+$/", $min_cost))
         {
            $this->min_cost = $min_cost;
         }
      }
      
      /** Get $_GET requests and check if they are numbers. */
      $this->max_cost = 0;
      if(isset($_GET['max_cost']))
      {
         $max_cost = preg_replace('/^\$/', '', $_GET["max_cost"]);
         if(preg_match("/^[0-9.]+$/", $max_cost))
         {
            $this->max_cost = $max_cost;
         }
      }

      /** Show error if min ocst is greater than max cost */
      $this->html_cost_error = "";
      if($this->min_cost > $this->max_cost)
      {
         $this->html_cost_error =
            '<span style="color:red;">Min Cost must be lower than Max Cost</span>';
      }

      /** 2 to 9 because region All is 1 and region_id 1 produces no results. */
      $this->region = 0;
      if(isset($_GET['region']) && preg_match("/^[2-9]+$/", $_GET['region']))
      {
         $this->region = $_GET['region'];
      }

      /** Allow if true to do a search query at results action. */
      $this->allow_search = true;
      $this->winesearch = "";
      if(isset($_GET['winesearch']))
      {
         $this->winesearch = $_GET['winesearch'];
         /** Allows all letters. */
         if(!preg_match("/^[A-Za-z]+$/", $_GET['winesearch']) &&
            $_GET['winesearch'] != "")
         {
            /** Don't allow search query because $_GET request failed. */
            $this->allow_search = false;
         }
      }

      $this->winerysearch = "";
      if(isset($_GET['winerysearch']))
      {
         $this->winerysearch = $_GET['winerysearch'];

         /** Allows spaces as well as all letters. */
         if(!preg_match("/^[A-Za-z ]+$/", $_GET['winerysearch']) &&
            $_GET['winerysearch'] != "")
         {
            /** Don't allow search query because $_GET request failed. */
            $this->allow_search = false;
         }
      }

      $this->column = DEFAULT_ORDER_COLUMN;
      /** Allow for 2 numbers. */
      if(isset($_GET['column']) && preg_match("/^[0-9]{0,2}$/", $_GET['column']))
      {
         $this->column = $_GET['column'];
      }

      $this->limits = DEFAULT_TOTAL_LIMIT;
      /** Allow for 2 numbers with a maximum of 30. */
      if(isset($_GET['limit']) && preg_match("/^[0-9]{0,2}$/", $_GET['limit'])
       && $_GET['limit'] <= DEFAULT_TOTAL_LIMIT )
      {
         $this->limits = $_GET['limit'];
      }

      /** Add above $_GET requests to string for html link. */
      $this->add_gets = 'region='.$this->region;
      $this->add_gets .= '&amp;wine_year_lo='.$this->wine_year_lo;
      $this->add_gets .= '&amp;wine_year_hi='.$this->wine_year_hi;
      $this->add_gets .= '&amp;grape_variety='.$this->grape_variety;
      $this->add_gets .= '&amp;min_cost='.$this->min_cost;
      $this->add_gets .= '&amp;max_cost='.$this->max_cost;

      /** Replace whitespace with %20 for w3c standards. */
      $this->add_gets .= '&amp;winesearch='.str_replace(' ', '%20', $this->winesearch);
      $this->add_gets .= '&amp;winerysearch='.str_replace(' ', '%20', $this->winerysearch);

      /** Add limits to add_gets. */
      $this->add_gets .= '&amp;limit='.$this->limits;

      /** Create url for columns. */
      $this->html_column = '?'.$this->add_gets.'&amp;column=';

      /** Add columns to add_gets. */
      $this->add_gets .= '&amp;column='.$this->column;

      /** Format html a href link. */
      $this->html_nxt_link = '<a href="'.$_SERVER["ASSIGN_PATH"].'index.html">reset search</a><br />';
      $this->html_nxt_link .= '<a href="'.$_SERVER["ASSIGN_PATH"].'results.html?'.$this->add_gets.'">reset pagination</a>';
   }

   /**
    * Start Page
    *
    * @return void.
    */
   protected function _indexAction()
   {
      $this->commonActions();
   }

   /**
    * Action to show a paginated list of wines.
    *
    * @return void.
    */
   protected function _resultsAction()
   {
      $this->commonActions();

      /**
       * For pagination. Check if next is less or equals to 0 or
       * check if $_GET next request has failed number conditions
       * and tehn set to default limits.
       */
      if(!isset($_GET['next']) ||
         (isset($_GET['next']) && $_GET['next'] <= 0) ||
         (isset($_GET['next']) && !preg_match("/^[0-9]+$/", $_GET['next'])))
      {
         /** Default html links. */
         $this->prev_link = DEFAULT_START_LIMIT;
         $this->next_link = $this->limits;
      }
      /** otherwise put $_GET next request into sql and html strings. */
      else
      {
         // User input sql starting from row in table.
         $this->limit_start = $_GET['next'];

         // User input html next and previous links
         $this->prev_link = $_GET['next'] - $this->limits;
         $this->next_link = $_GET['next'] + $this->limits;
      }
      
      /**
       * Select box selected and now adding to sql query.
       * Where $selectsearch[key] is the column name and
       * the value is the user input select.
       */
      $selectsearch = array();
      if($this->region != 0)
      {
         $table_column = '`winery`.`region_id`';
         $selectsearch[$table_column] = $this->region;
      }
      if($this->grape_variety != 0)
      {
         $table_column = '`wine_variety`.`variety_id`';
         $selectsearch[$table_column] = $this->grape_variety;
      }

      $this->wine_results = array();
      /** Allow if true to do a search query at results action. */
      if($this->allow_search)
      {
         $this->wine_results = 
            /**
             * wine_variety model has the sql with lots ofjoins.
             *
             * $this->winesearch    from $_GET['winesearch'] request.
             * $this->winerysearch  from $_GET['winerysearch'] request.
             * $selectsearch        from select box $_GET requests.
             * $this->wine_year_lo  $_GET['wine_year_lo'] request.
             * $this->wine_year_hi  $_GET['wine_year_hi'] request.
             * $this->min_cost      $_GET['min_cost'] request.
             * $this->max_cost      $_GET['max_cost'] request..
             * $this->limit_start   $_GET['next'] request.
             * $this->limits     from DEFAULT_TOTAL_LIMIT which is 30.
             */
            $this->model_winevariety->search_wine_name($this->winesearch,
               $this->winerysearch,
               $selectsearch, 
               $this->column,
               $this->wine_year_lo,
               $this->wine_year_hi,
               $this->min_cost,
               $this->max_cost,
               $this->limit_start,
               $this->limits);
      }

      /**
       * Add 'Next' link if not at the end of pagination.
       * Assumed that the number of results is equal to
       * the total number of results limit set from 
       * the sql query.
       */
      if(count($this->wine_results) == $this->limits)
      {
         $this->html_nxt_link = '<a href="?next='.$this->next_link .'&amp;'.$this->add_gets.'">Next &gt;&gt;</a>';
      }

      /** Add 'Previous' link if not at the beginning of pagination. */
      $this->html_prv_link = '';
      if($this->limit_start != DEFAULT_START_LIMIT)
      {
         $this->html_prv_link = '<a href="?next='.$this->prev_link.'&amp;'.$this->add_gets.'">&lt;&lt; Previous</a>';
      }

      /** Remove limit link in add_gets because we will add a new one. */
      $add_gets = preg_replace('/\&amp;limit=[0-9]{0,2}/', "", $this->add_gets);
      /** Create limits for pagination table. */
      $this->html_limits = '<a href="'.$_SERVER["ASSIGN_PATH"].'results.html?next='.$this->limit_start.'&amp;'.
         $add_gets.'&amp;limit=5">5</a>, ';
      $this->html_limits .= '<a href="'.$_SERVER["ASSIGN_PATH"].'results.html?next='.$this->limit_start.'&amp;'.
         $add_gets.'&amp;limit=10">10</a>, ';
      $this->html_limits .= '<a href="'.$_SERVER["ASSIGN_PATH"].'results.html?next='.$this->limit_start.'&amp;'.
         $add_gets.'&amp;limit=15">15</a>, ';
      $this->html_limits .= '<a href="'.$_SERVER["ASSIGN_PATH"].'results.html?next='.$this->limit_start.'&amp;'.
         $add_gets.'&amp;limit=30">30</a>';
   }

   /**
    * Action to show information for a
    * particular Wine via wine_id.
    *
    * @return void.
    */
   protected function _wineinfoAction()
   {
      $this->wine_id = 0;
      if(isset($_GET['wine_id']) && preg_match("/^[0-9]+$/", $_GET['wine_id']))
      {
         $this->wine_id = $_GET['wine_id'];
      }
      else
      {
         header("HTTP/1.0 404 Not Found");
         header('location:'.$_SERVER["ASSIGN_PATH"].'404.shtml');
         exit;
      }

      $this->commonActions();

      /** Single result. */
      $this->wine_info = $this->model_wine->query_single_wine_id($this->wine_id);

      /** Multiple results. */
      $this->wine_info_grapes = $this->model_grape_varity->search_wine_id($this->wine_id);
      $this->wine_info_orders = $this->model_orders->retrieve_orders($this->wine_id);
   }

   /**
    * 404 missing action to redirect
    * users who are lost.
    *
    * @return void.
    */
   protected function _404Action()
   {
      $this->commonActions();
   }
}